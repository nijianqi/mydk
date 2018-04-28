<?php

namespace app\home\controller;

use think\Controller;
use think\Db;

class Login extends Controller
{

    protected $beforeActionList = [
        'checkUser'
    ];

    public function checkUser()
    {
        $client_user_info = getClientUserInfo();
        if ($client_user_info) {
            $this->redirect('@home/index');
        }
    }

    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        $username = $this->request->post('username', '', 'trim');
        $password = $this->request->post('password', '', 'trim');
        (empty($username) || strlen($username) < 4) && $this->error('登录账号长度不能少于4位有效字符!');
        (empty($password) || strlen($password) < 4) && $this->error('登录密码长度不能少于4位有效字符!');
        $employee = Db::name('att_per_finger_info')->where(['Emp_MircoMsg_Uid' => $username])->find();  //用户登录表
        if (empty($employee)) {
            $this->error("登录账号或密码不存在，请重新输入！", '@home/login');
        }
        if ($employee['u_status'] == 0) {
            $this->error("登录账号被禁用，请联系管理员！", '@home/login');
        }
        if ($employee['Emp_MircoMsg_Upwd'] !== md5($password)) {
            $this->error("登录密码与账号不匹配，请重新输入!!", '@home/login');
        }
         session('emp_id', $employee['u_id']);
        $this->success('登录成功！', '@home/index');
    }

}
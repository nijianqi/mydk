<?php

namespace app\home\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    protected $beforeActionList = [
        'checkUser',
        'checktime' => ['except' => 'index,logout']
    ];

    public function checkUser()
    {
        $client_user_info = getClientUserInfo();
        if (empty($client_user_info)) {
            $this->redirect('@home/login');
        }
    }

    public function checktime()
    {
        $MorningTime = strtotime(date('Y-m-d', \time()) . ' 08:00:00');
        $AfternoonTime = strtotime(date('Y-m-d', \time()) . ' 17:00:00');
        $NowTime = time();
        if ($NowTime > $MorningTime || $NowTime > $AfternoonTime) {
            $this->error('当前时间不在打卡时间范围内！', '@home/index');
        }
    }

    public function index()
    {
        return $this->fetch();

    }

    public function daka()
    {

        $sqlStr = "exec [up_add_record] ?,?,?";
        $return = Db::query($sqlStr, ['', session('emp_id'), date('Y-m-d H:i:s',time())]);
        if($return[0][0]['flag'] = '100'){
            $this->error('打卡失败！', '@home/index');
        }else{
            $this->success('打卡成功！', '@home/index');
        }

    }

    public function buqian($time)
    {
        $sqlStr = "exec [up_add_record] ?,?,?";
        $return = Db::query($sqlStr, ['', session('emp_id'), $time]);
        if($return[0][0]['flag'] = '100'){
            $this->error('打卡失败！', '@home/index');
        }else{
            $this->success('打卡成功！', '@home/index');
        }
    }

    public function logout()
    {
       session_destroy();
        $this->success('注销账号成功！', '@home/login');
    }
}

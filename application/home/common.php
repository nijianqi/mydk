<?php

function getClientUserInfo()
{
    $client_user_info = null;
    if (session('emp_id')) {
        $user_info = Db::table("att_per_finger_info")
            ->where('u_id=:u_id and u_status!=0',
                ['u_id' => session('emp_id')])
            ->find();
        $client_user_info = $user_info;
    }
    return $client_user_info;
}
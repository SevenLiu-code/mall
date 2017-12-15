<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15
 * Time: 9:21
 */
include_once './lib/fun.php';
if(!checkLogin()){
    msg(2, '不合法连接，正在跳转', 'login.php');
} else {
    unset($_SESSION['user']);
    msg(2, '退出成功，正在跳转', 'login.php');
}

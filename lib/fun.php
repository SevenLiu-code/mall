<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5
 * Time: 9:31
 */
function mysqlInit($host, $username, $password, $db) // 连接数据库
{
    $con = mysqli_connect($host, $username, $password, $db);
    if (!$con) {
       return fasle;
        }
    mysqli_set_charset($con, 'utf8');
    return $con;
}
function creatPassword($password) // 密码加密
{
    if (!$password) {
        return false;
    }
    return md5(md5($password) . 'imooc');
}

/**
 * @param $type 1: 成功  2: 失败
 * @param null $msg
 * @param null $url
 */
function msg($type, $msg=null, $url=null) //消息提示
{
    $tourl = "Location:http://localhost/project/mall/message.php?&type={$type}";
    $tourl.= $msg ? "&msg={$msg}" : ''; //当msg为空时，url不写入
    $tourl.= $url ? "&url={$url}" : '';
    header($tourl);
    exit;
}
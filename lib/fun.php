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
    if (!$password)
    {
        return false;
    }
    return md5(md5($password).'imooc');

}
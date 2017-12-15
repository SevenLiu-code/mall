<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 14:36
 */
include_once './lib/fun.php';
if(!checkLogin()){
    msg(2, '请先登录!', 'login.php');
}
//数据操作
$con = mysqlInit('localhost','root', 'root', 'imooc_mall');
if (!$con) {
    echo '数据库连接失败'.mysqli_connect_error();
    exit;
}
$goodsID = $_GET['id'];
if (!$goodsID) {
    msg(2, '非法参数');
}
//根据商品ID校验商品
$sql = "SELECT * FROM `im_goods` WHERE `id` = '{$goodsID}'";
$obj = mysqli_query($con, $sql);
if(!$goods = mysqli_fetch_assoc($obj)) {
    msg(2, '画品不存在', 'index.php');
}
$sql = "UPDATE `im_goods` SET `status` = -1 WHERE `id` = {$goodsID}";
if($result = mysqli_query($con, $sql)){
    msg(1, '操作成功', 'index.php');
}else {
    msg(2, '操作失败', 'index.php');
}
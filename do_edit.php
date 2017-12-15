<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13
 * Time: 13:44
 */
include_once './lib/fun.php';
if(!checkLogin()){
    msg(2, '请先登录!', 'login.php');
}
if(!empty($_POST['name'])) {
    //数据操作
    $con = mysqlInit('localhost','root', 'root', 'imooc_mall');
    if (!$con) {
        echo '数据库连接失败'.mysqli_connect_error();
        exit;
    }
    $goodsID = $_POST['id'];
    if (!$goodsID) {
        msg(2, '非法参数');
    }
    //根据商品ID校验商品
    $sql = "SELECT * FROM `im_goods` WHERE `id` = '{$goodsID}'";
    $obj = mysqli_query($con, $sql);
    if(!$goods = mysqli_fetch_assoc($obj)) {
        msg(2, '画品不存在', 'index.php');
    }
    $name = trim(mysqli_real_escape_string($con, $_POST['name']));
    $price = intval($_POST['price']);
    $des = trim(mysqli_real_escape_string($con, $_POST['des']));
    $content = trim(mysqli_real_escape_string($con, $_POST['content']));
    $nameLen = mb_strlen($name, 'utf-8');
    if ($nameLen<=0&&$nameLen>30){
        msg(2, '画品名应在1-30字符之内');
    }
    $priceLen = mb_strlen($price, 'utf-8');
    if ($priceLen<=0&&$priceLen>999999999){
        msg(2, '请输入最多9位正整数');
    }
    $desLen = mb_strlen($des, 'utf-8');
    if ($desLen<=0&&$desLen>100){
        msg(2, '画品简介应在1-100字符之内');
    }
    $contentLen = mb_strlen($content, 'utf-8');
    if (empty($content)) {
        msg(2, '画品详情信息不能为空');
    }
    // 编辑信息数组
    $upDate = array(
        'name' => $name,
        'price' => $price,
        'des' => $des,
        'content' => $content
    );
    // 仅当用户选择照片时，进行图片上传验证
    if ($_FILES['file']['size'] > 0 ) {
        $pic = imgUpload($_FILES['file']);
        $upDate['pic'] = $pic;
    }
    // 剔除未更改字段
    foreach ($upDate as $k=>$v) {
        if ($goods[$k] == $v){
            unset($upDate[$k]); // 销毁指定的变量
        }
    }
    $now = $_SERVER['REQUEST_TIME']; // 时间戳
    if(empty($upDate)){
        msg(1, '更新成功', 'index.php');
    }
    $upDateSlq = '';
    $upDate['update_time'] = $now;
    foreach ($upDate as $k=>$v) {
        $upDateSlq .= " `{$k}` = '{$v}',";
    }
    $upDateSlq = rtrim($upDateSlq, ','); // 去除多余逗号
    unset($sql, $obj);
    $sql = "UPDATE `im_goods` SET ".$upDateSlq." WHERE `id` = '{$goodsID}'";
    if(mysqli_query($con, $sql)){
        // echo mysqli_affected_rows($con); die; // 取得前一次 MySQL 操作所影响的记录行数
        msg(1, '更新成功','edit.php?id=' . $goodsID);
    } else {
        msg(1, '更新失败','edit.php?id=' . $goodsID);
    }
} else {
    msg(2, '路由非法，等待跳转', 'index.php');
}
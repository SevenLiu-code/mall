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

function imgUpload($file) {
    if (!is_uploaded_file($file['tmp_name'])){ // 检测上传文件是否合法
        msg(2, '请上传符合规范的图像');
    }
    $type = $file['type'];
    if (!in_array($type, array("image/png","image/gif","image/jpeg"))){ //图像类型验证
        msg(2, '请上传png，gif，jpeg的图像');
    }
    $now = $_SERVER['REQUEST_TIME'];// 时间戳
    // 上传目录
    $uploadPath = './static/file/';
    //上传目录访问url
    $uploadUrl = '/static/file/';
    //上传文件夹
    $fileDir = date('Y/m/d', $now);
    //检测目前是否存在
    if (!is_dir($uploadPath.$fileDir)) {
        mkdir($uploadPath.$fileDir, 0755, true); //递归创建目录
    }
    // 获取文件名
    $ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION)); //拿到文件扩展名
    $img = uniqid().mt_rand(1000, 9999).'.'.$ext; // 图片名称
    $imgPath = $uploadPath.$fileDir.$img; //图片屋里地址
    $imgUrl = 'http://localhost/project/mall'.$uploadUrl.$fileDir.$img; //图片url地址
    if(!move_uploaded_file($file['tmp_name'], $imgPath)){
        msg(2, '服务器繁忙，请稍后再试...');
    } else { return $imgUrl; }
}
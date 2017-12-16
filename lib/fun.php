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
    if (!in_array($type, array("image/png", "image/gif","image/jpeg"))){ //图像类型验证
        msg(2, '请上传png，gif，jpeg的图像');
    }
    // 上传目录
    $uploadPath = './static/file/';
    //上传目录访问url
    $uploadUrl = '/static/file/';
    //上传文件夹
    $fileDir = date('Y/m/d', $_SERVER['REQUEST_TIME']);
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

/**
 * 检测用户是否登录
 */
function checkLogin() {
    session_start();
    if (!isset($_SESSION['user']) || empty($_SESSION['user'])){
        return false;
    }
    return true;
}

//获取utl
function getUrl(){
    $url = '';
    $url .= $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}
// page分页
/**
 * @param $total 数据总数
 * @param $currentPage 当前页
 * @param $pageSize 每页显示数目
 * @param int $show 分页显示个数
 * @return string
 */
function pages($total, $currentPage, $pageSize, $show=6) {
        $pageStr = '';
    if ($total > $pageSize){
        $pageStr .= '<div class="page-nav"><ul>';
        $totalPage = ceil($total / $pageSize); //向上取整，获取总页数
        // 对当前页进行处理
        $currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
        // 当前仅当当前页大于1的时候出现首页和上一页
        if($currentPage>1){ // 如果当前页大于1，$currentPage只能为1或大于1
            $pageStr .= "<li><a href='1'>首页</a></li><li><a href='". ($currentPage-1) . "'>上一页</a></li>";
        } else {
            $pageStr .= '<li><a href="javascript:;">首页</a></li><li><a href="javascript:;">上一页</a></li>';
        }

        $from = max(1,($currentPage - intval($show/2)));// 分页起始页
        $to = $from + $show - 1; // 分页结束页
        //var_dump($from, $to); exit;
        if ($to>=$totalPage) { // 当结束页大于总页
            $to = $totalPage;
            $from =max(1,$totalPage - $show + 1 );
        }

        if ($from>1){ // 如果起始页大于1
            $pageStr .= "<li>...</li>";
        }

        for($i = $from; $i <= $to; $i++)
        {
            if($i != $currentPage)
            {
                $pageStr .= "<li><a href='" . $i . "'>{$i}</a></li>";
            }
            else
            {
                $pageStr .= "<li><span class='first-page'>{$i}</span></li>";
            }
        }

        if ($to<$totalPage){ // 如果结束页小于总页数
            $pageStr .= "<li>...</li>";
        }
        if ($currentPage<$totalPage) { //如果当前页小总页数，$currentPage只能为小于$totalPage或等于
            $pageStr .= "<li><a href='" .($currentPage+1). "'>下一页</a></li>";
            $pageStr .= "<li><a href='{$totalPage}'>尾页</a></li>";
        } else {
            $pageStr .= "<li><a href='javascript:;'>下一页</a></li><li><a href='javascript:;'>尾页</a></li>";
        }
        $pageStr .= '</ul></div>';
    }
    return $pageStr;
}
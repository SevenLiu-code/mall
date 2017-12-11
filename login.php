<?php
session_start();
if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location:http://localhost/project/mall/index.php'); exit;
}
if (!empty($_POST['username'])) {
    include_once './lib/fun.php';
    //数据操作
    $con = mysqlInit('localhost','root', 'root', 'imooc_mall');
    if (!$con) {
        echo '数据库连接失败'.mysqli_connect_error();
        exit;
    }
    $username = trim(mysqli_real_escape_string($con, $_POST['username']));
    $password = trim(mysqli_real_escape_string($con, $_POST['password']));
    if (!$username) {
        msg(2, '用户名不能为空！');
    }
    if (!$password) {
        msg(2, '密码不能为空！');
    }
    $sql = "SELECT * FROM `im_user` WHERE `username` = '{$username}' LIMIT 1";
    $obj = mysqli_query($con, $sql);
    $result = mysqli_fetch_assoc($obj);
    if(is_array($result) && !empty($result)){
        if ( creatPassword($password) === $result['password']) {
            $_SESSION['user'] = $result;
            msg(1, '登录成功！', 'index.php' );
        } else { msg(2, '密码错误，请重新输入！'); }
    } else {
        echo msg(2, '用户名不存在！');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户登录</title>
    <link href="./static/css/common.css" type="text/css" rel="stylesheet">
    <link href="./static/css/add.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="./static/css/login.css">
    <style> input { text-indent: 1em; } </style>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="javascript:;">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="./static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>用户登录</p>
                    </div>
                    <form class="login-table" id="login-form" action="login.php" method="POST">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input id="username" type="text" class="yhmiput" name="username" placeholder="请输入用户名">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input id="password" type="password" class="yhmiput" name="password" placeholder="请输入密码">
                        </div>
                        <div class="login-btn"><button type="submit">登录</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span> ©2017 POWERED BY IMOOC.INC</p>
</div>

</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#login-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val();
            if (username == '' || username.length <= 0) {
                layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }


            return true;
        })

    })
</script>
</html>
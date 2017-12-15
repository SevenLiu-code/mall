<?php
include_once './lib/fun.php';
if($login = checkLogin()){
    $user = $_SESSION['user'];
}
//数据操作
$con = mysqlInit('localhost','root', 'root', 'imooc_mall');
if (!$con) {
    echo '数据库连接失败'.mysqli_connect_error();
    exit;
}
// 商品查询
    // 检测page参数
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    // 最小值取1
    $page = max($page, 1);
    // 每页显示条数
    $pageSize = 2;
    $offset = $pageSize*($page-1);
    $sql = "SELECT * FROM `im_goods` WHERE `status` = 1 ORDER BY `id` ASC, `view` DESC LIMIT {$offset},{$pageSize}";
    $obj = mysqli_query($con, $sql);
    $goods = array();
    while($result = mysqli_fetch_assoc($obj)){
        $goods[] = $result;
    }
    var_dump($goods);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|首页</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
                <?php if($login): ?>
                <li><span>管理员：<?php echo $user['username']; ?></span></li>
                <li><a href="./publish.php">发布</a></li>
                <li><a href="./login_out.php" onclick="javascript:if(!confirm('确定退出吗？')) return false;">退出</a></li>
                <?php else: ?>
                <li><a href="login.php">登录</a></li>
                <li><a href="./register.php">注册</a></li>
                <?php endif; ?>
        </ul>
    </div>
</div>
<div class="content">
    <div class="banner">
        <img class="banner-img" src="./static/image/welcome.png" width="732px" height="372" alt="图片描述">
    </div>
    <div class="img-content">
        <ul>
                <li>
                    <img class="img-li-fix" src="" alt="">
                    <div class="info">
                        <a href="detail.php?id="><h3 class="img_title"></h3></a>
                        <p>
                        </p>
                        <div class="btn">
                            <a href="edit.php?id=" class="edit">编辑</a>
                            <a href="delete.php?id=" class="del">删除</a>
                        </div>
                    </div>
                </li>
        </ul>
    </div>
</div>

<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        $('.del').on('click',function () {
            if(confirm('确认删除该画品吗?'))
            {
                window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>


</html>

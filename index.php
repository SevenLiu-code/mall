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
    $sql = "SELECT COUNT(`id`) AS total FROM `im_goods` WHERE `status` = 1";
    $obj = mysqli_query($con, $sql);
    $result = mysqli_fetch_assoc($obj);
    $total = isset($result['total']) ? $result['total'] : 0;
    // 每页显示条数
    $pageSize = 3;
    // 如果传入page数大于实际数，取实际最大数
    $page = $page > ceil($total / $pageSize) ? ceil($total / $pageSize) : $page;
    $offset = $pageSize*($page-1);
    unset($sql, $obj, $result); // 释放变量
    // 查我需要的
    $sql = "SELECT `id`, `pic`,`name`,`des` FROM `im_goods` WHERE `status` = 1 ORDER BY `id` ASC, `view` DESC LIMIT {$offset},{$pageSize}";
    $obj = mysqli_query($con, $sql);
    $goods = array();
    while($result = mysqli_fetch_assoc($obj)){
        $goods[] = $result;
    }
    $pages = pages($total, $page, $pageSize, 5);
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
            <?php foreach ($goods as $v): ?>
                <li>
                    <a href="detail.php?id=<?php echo $v['id']; ?>">
                        <img class="img-li-fix" src=" <?php echo $v['pic']; ?> " alt="" style="height: 220px;">
                    </a>
                    <div class="info">
                        <a href="detail.php?id=<?php echo $v['id']; ?>"><h3 class="img_title"><?php echo $v['name']; ?></h3></a>
                        <p><?php echo $v['des']; ?></p>
                        <div class="btn">
                            <a href="edit.php?id=<?php echo $v['id']; ?>" class="edit">编辑</a>
                            <a href="delete.php?id=<?php echo $v['id']; ?>" class="del">删除</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php echo $pages; ?>
</div>

<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        $('.del').on('click', function () {
            if(confirm('确认删除该画品吗?'))
            {
                window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>


</html>

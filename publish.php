<?php
session_start();
include_once './lib/fun.php';
if(!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    msg(2, '请先登录！', 'login.php');
    exit;
}
$user = $_SESSION['user'];
if(!empty($_POST['name'])){
    //数据操作
    $con = mysqlInit('localhost','root', 'root', 'imooc_mall');
    if (!$con) {
        echo '数据库连接失败'.mysqli_connect_error();
        exit;
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
    $now = $_SERVER['REQUEST_TIME']; // 时间戳
        // 要做商品唯一性处理
        $sql = "SELECT COUNT(`id`) AS total FROM `im_goods` WHERE name = '{$name}'";
        $obj = mysqli_query($con, $sql);
        $result = mysqli_fetch_assoc($obj);
        if(isset($result['total'])&&$result['total'] > 0){
            msg(2, '画品信息已存在');
        } else {
            $pic = imgUpload($_FILES['file']);
        // 入库处理
        $sql = "INSERT INTO `im_goods`(`name`, `price`, `pic`, `des`, `content`, `user_id`, `create_time`, `update_time`, `view`) 
VALUES('{$name}', '{$price}', '{$pic}', '{$des}', '{$content}', '{$user['id']}', '{$now}', '{$now}', 0)";
        if(mysqli_query($con, $sql)){
            $result = mysqli_query($con, "SELECT LAST_INSERT_ID()"); //插入成功的主键ID;
            $goodsId = mysqli_fetch_assoc($result);
            var_dump($goodsId);die;
        } else {
            echo mysqli_error($con); exit;
        }
    }


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|发布画品</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
    <style>
        input[type="text"], textarea { text-indent: 1em; }
    </style>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><span>管理员：<?php echo $user['username']; ?></span></li>
            <li><a href="#">退出</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="addwrap">
        <div class="addl fl">
            <header>发布画品</header>
            <form name="publish-form" id="publish-form" action="publish.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">画品名称</label><input type="text" name="name" id="name" placeholder="请输入画品名称">
                </div>
                <div class="additem">
                    <label id="for-price">价值</label><input type="text" name="price" id="price" placeholder="请输入画品价值">
                </div>
                <div class="additem">
                    <!-- 使用accept html5属性 声明仅接受png gif jpeg格式的文件                -->
                    <label id="for-file">画品</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file"
                      name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">画品简介</label><textarea id="des" name="des"
                     placeholder="请输入画品简介"></textarea>
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-content">画品详情</label>
                    <div style="margin-left: 120px" id="container">
                        <textarea id="content" name="content"></textarea>
                    </div>
                </div>
                <div style="margin-top: 20px">
                    <button type="submit">发布</button>
                </div>
            </form>
        </div>
        <div class="addr fr">
            <img src="./static/image/index_banner.png">
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script src="./static/js/kindeditor/kindeditor-all-min.js"></script>
<script src="./static/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var K = KindEditor;
    K.create('#content', {
        width      : '475px',
        height     : '400px',
        minWidth   : '30px',
        minHeight  : '50px',
        items      : [
            'undo', 'redo', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'clearhtml',
            'fontsize', 'forecolor', 'bold',
            'italic', 'underline', 'link', 'unlink', '|'
            , 'fullscreen'
        ],
        afterCreate: function () {
            this.sync();
        },
        afterChange: function () {
            //编辑器失去焦点时直接同步，可以取到值
            this.sync();
        }
    });
</script>

<script>
    $(function () {
        $('#publish-form').submit(function () {
            var name = $('#name').val(),
                price = $('#price').val(),
                file = $('#file').val(),
                des = $('#des').val(),
                content = $('#content').val();
            if (name.length <= 0 || name.length > 30) {
                layer.tips('画品名应在1-30字符之内', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            //验证为正整数
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('请输入最多9位正整数', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }

            if (file == '' || file.length <= 0) {
                layer.tips('请选择图片', '#file', {time: 2000, tips: 2});
                $('#file').focus();
                return false;
            }

            if (des.length <= 0 || des.length >= 100) {
                layer.tips('画品简介应在1-100字符之内', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }

            if (content.length <= 0) {
                layer.tips('请输入画品详情信息', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;
        })
    })
</script>
</html>

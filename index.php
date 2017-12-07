<?php
session_start();
if(!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location:http://localhost/project/mall/login.php'); exit;
}
    echo '商品中心';
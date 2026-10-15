<?php
session_start();

$conn = mysqli_connect("sql206.infinityfree.com", "if0_43181285", "FbWxVt0xWptcOA", "if0_43181285_inventory");
if (!$conn) {
    die("Database connection failed.");
}

function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

function require_admin() {
    require_login();
    if ($_SESSION['role'] != 'admin') {
        die("Access denied. Admins only.");
    }
}

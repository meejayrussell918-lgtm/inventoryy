<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "inventory_db");
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

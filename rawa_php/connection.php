<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "rawa_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// ===== دوال مساعدة للسيشن =====
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function currentUserName() {
    return ($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? '');
}
?>

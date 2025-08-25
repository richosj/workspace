<?php
require_once __DIR__ . "/session.php";

// 로그인 안 된 경우 로그인 페이지로 이동
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin/login.php");
    exit;
}

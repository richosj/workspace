<?php
require_once "./config/db.php";

$username = "admin";
$password = "1234";

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admin_users (username,password) VALUES (?,?)");
$stmt->execute([$username,$hash]);

echo "관리자 계정(admin/1234) 생성 완료!";
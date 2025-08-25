<?php
require_once "../app/config/db.php";
require_once "../app/includes/session.php";
require_once "../app/models/AdminUser.php";

$model = new AdminUser($pdo);

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $model->create($_POST['username'], $_POST['password']);
    echo "회원가입 완료! <a href='login.php'>로그인</a>";
    exit;
}
?>
<form method="post">
  <input name="username" placeholder="아이디"><br>
  <input type="password" name="password" placeholder="비밀번호"><br>
  <button>회원가입</button>
</form>

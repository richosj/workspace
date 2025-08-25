<?php
require_once "../app/config/db.php";
require_once "../app/includes/session.php";
require_once "../app/models/AdminUser.php";

$model = new AdminUser($pdo);

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $user = $model->findByUsername($_POST['username']);
    if ($user && password_verify($_POST['password'],$user['password'])) {
        $_SESSION['admin_id']=$user['id'];
        $_SESSION['admin_name']=$user['username'];
        $model->updateLastLogin($user['id']);
        header("Location: index.php"); exit;
    } else {
        $error="아이디/비밀번호 오류";
    }
}
?>
<form method="post">
  <input name="username" placeholder="아이디"><br>
  <input type="password" name="password" placeholder="비밀번호"><br>
  <button>로그인</button>
  <?php if(!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
</form>

<?php
require 'config/db.php';
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if ($_POST['login']==='Admin26' && $_POST['password']==='Demo20') {
        $_SESSION['admin']=true;
        header('Location: admin.php'); exit;
    }
    $err='Неверные данные администратора';
}
$title='Админ — Вход'; include 'includes/header.php';
?>
<div class="card">
  <h2>Вход администратора</h2>
  <?php if($err): ?><div class="alert alert-error"><?= $err ?></div><?php endif; ?>
  <form method="post">
    <div class="form-group"><label>Логин</label><input type="text" name="login" required></div>
    <div class="form-group"><label>Пароль</label><input type="password" name="password" required></div>
    <button class="btn">Войти</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
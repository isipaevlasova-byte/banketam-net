<?php
require 'config/db.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $login=trim($_POST['login']);
    $pass=$_POST['password'];
    $stmt=$pdo->prepare("SELECT * FROM users WHERE login=?");
    $stmt->execute([$login]);
    $u=$stmt->fetch();
    if ($u && password_verify($pass,$u['password'])) {
        $_SESSION['user_id']=$u['id'];
        $_SESSION['fio']=$u['fio'];
        header('Location: cabinet.php'); exit;
    }
    $error='Неверный логин или пароль';
}
$title='Вход'; include 'includes/header.php';
?>
<div class="card">
  <h2>Вход</h2>
  <?php if($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
  <form method="post">
    <div class="form-group"><label>Логин</label><input type="text" name="login" required></div>
    <div class="form-group"><label>Пароль</label><input type="password" name="password" required></div>
    <button class="btn">Войти</button>
    <a class="link" href="register.php">Еще не зарегистрированы? Регистрация</a>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
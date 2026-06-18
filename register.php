<?php
require 'config/db.php';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $login = trim($_POST['login']);
    $pass  = $_POST['password'];
    $fio   = trim($_POST['fio']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    if (!preg_match('/^[a-zA-Z0-9]{6,}$/',$login))
        $errors['login']='Логин: латиница и цифры, минимум 6 символов';
    if (strlen($pass)<8)
        $errors['password']='Пароль минимум 8 символов';
    if ($fio==='') $errors['fio']='Введите ФИО';
    if (!preg_match('/^\+?[0-9\s\-\(\)]{7,}$/',$phone)) $errors['phone']='Некорректный телефон';
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors['email']='Некорректный email';

    if (!$errors) {
        $stmt=$pdo->prepare("SELECT id FROM users WHERE login=?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) $errors['login']='Логин уже занят';
    }

    if (!$errors) {
        $hash=password_hash($pass,PASSWORD_DEFAULT);
        $stmt=$pdo->prepare("INSERT INTO users(login,password,fio,phone,email) VALUES(?,?,?,?,?)");
        $stmt->execute([$login,$hash,$fio,$phone,$email]);
        $success=true;
    }
}
$title='Регистрация';
include 'includes/header.php';
?>
<div class="card">
  <h2>Регистрация</h2>
  <?php if($success): ?>
    <div class="alert alert-success">Регистрация успешна! <a href="login.php">Войти</a></div>
  <?php else: ?>
  <form method="post">
    <div class="form-group">
      <label>Логин</label>
      <input type="text" name="login" value="<?= htmlspecialchars($_POST['login']??'') ?>">
      <?php if(!empty($errors['login'])): ?><span class="error-text"><?= $errors['login'] ?></span><?php endif; ?>
    </div>
    <div class="form-group">
      <label>Пароль</label>
      <input type="password" name="password">
      <?php if(!empty($errors['password'])): ?><span class="error-text"><?= $errors['password'] ?></span><?php endif; ?>
    </div>
    <div class="form-group">
      <label>ФИО</label>
      <input type="text" name="fio" value="<?= htmlspecialchars($_POST['fio']??'') ?>">
      <?php if(!empty($errors['fio'])): ?><span class="error-text"><?= $errors['fio'] ?></span><?php endif; ?>
    </div>
    <div class="form-group">
      <label>Телефон</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone']??'') ?>">
      <?php if(!empty($errors['phone'])): ?><span class="error-text"><?= $errors['phone'] ?></span><?php endif; ?>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>">
      <?php if(!empty($errors['email'])): ?><span class="error-text"><?= $errors['email'] ?></span><?php endif; ?>
    </div>
    <button class="btn" type="submit">Зарегистрироваться</button>
    <a class="link" href="login.php">Уже зарегистрированы? Вход</a>
  </form>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
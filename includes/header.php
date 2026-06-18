<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= $title ?? 'Банкетам.Нет' ?></title>
<link rel="stylesheet" href="/banketam-net/assets/css/style.css">
</head>
<body>
<header>
  <div class="container">
    <a href="/banketam-net/index.php" class="logo">Банкетам<span>.Нет</span></a>
    <nav>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="/banketam-net/cabinet.php">Личный кабинет</a>
        <a href="/banketam-net/booking.php">Оформить заявку</a>
        <a href="/banketam-net/logout.php">Выход</a>
      <?php elseif (!empty($_SESSION['admin'])): ?>
        <a href="/banketam-net/admin.php">Панель</a>
        <a href="/banketam-net/admin_logout.php">Выход</a>
      <?php else: ?>
        <a href="/banketam-net/login.php">Вход</a>
        <a href="/banketam-net/register.php">Регистрация</a>
        <a href="/banketam-net/admin_login.php">Админ</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container"></main>
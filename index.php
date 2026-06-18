<?php require 'config/db.php'; $title='Главная — Банкетам.Нет'; include 'includes/header.php'; ?>

<section class="hero">
  <h1>Банкетам<span style="color:var(--text);">.Нет</span></h1>
  <p>Портал бронирования помещений для проведения банкетов.<br>
     Выберите идеальное место для вашего мероприятия.</p>
  <a href="register.php" class="btn btn-inline"> Начать бронирование</a>
</section>

<div class="rooms-grid">
  <div class="room-card">
    <img src="assets/images/room1.jpg" alt="Банкетный зал">
    <div class="info">
      <h3>Банкетный зал</h3>
      <p>Вместимость до 200 гостей</p>
    </div>
  </div>
  <div class="room-card">
    <img src="assets/images/room2.jpg" alt="Ресторан">
    <div class="info">
      <h3>Ресторан</h3>
      <p>Уютная атмосфера</p>
    </div>
  </div>
  <div class="room-card">
    <img src="assets/images/room3.jpg" alt="Летняя веранда">
    <div class="info">
      <h3>Летняя веранда</h3>
      <p>Открытое пространство</p>
    </div>
  </div>
  <div class="room-card">
    <img src="assets/images/room4.jpg" alt="Закрытая веранда">
    <div class="info">
      <h3>Закрытая веранда</h3>
      <p>Приватное мероприятие</p>
    </div>
  </div>
</div>



<?php include 'includes/footer.php'; ?>
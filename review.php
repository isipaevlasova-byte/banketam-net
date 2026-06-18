<?php
require 'config/db.php';
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$bid=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT * FROM bookings WHERE id=? AND user_id=?");
$stmt->execute([$bid,$_SESSION['user_id']]);
$b=$stmt->fetch();
if (!$b || $b['status']==='Новая') { header('Location: cabinet.php'); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $r=(int)$_POST['rating']; $c=trim($_POST['comment']);
    $stmt=$pdo->prepare("INSERT INTO reviews(booking_id,user_id,rating,comment) VALUES(?,?,?,?)");
    $stmt->execute([$bid,$_SESSION['user_id'],$r,$c]);
    header('Location: cabinet.php'); exit;
}
$title='Отзыв'; include 'includes/header.php';
?>
<div class="card">
  <h2>Отзыв о заявке #<?= $bid ?></h2>
  <form method="post">
    <div class="form-group">
      <label>Оценка</label>
      <select name="rating" required>
        <option value="5">★★★★★</option>
        <option value="4">★★★★</option>
        <option value="3">★★★</option>
        <option value="2">★★</option>
        <option value="1">★</option>
      </select>
    </div>
    <div class="form-group">
      <label>Комментарий</label>
      <input type="text" name="comment" placeholder="Ваше мнение">
    </div>
    <button class="btn">Отправить</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
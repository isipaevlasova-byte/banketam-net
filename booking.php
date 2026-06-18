<?php
require 'config/db.php';
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$rooms=$pdo->query("SELECT * FROM rooms")->fetchAll();
$pays=$pdo->query("SELECT * FROM payment_methods")->fetchAll();
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $room=(int)$_POST['room_id'];
    $pay=(int)$_POST['payment_id'];
    $date=$_POST['banquet_date'];
    $d=DateTime::createFromFormat('d.m.Y',$date);
    if (!$d) $msg='<div class="alert alert-error">Введите дату в формате ДД.ММ.ГГГГ</div>';
    else {
        $stmt=$pdo->prepare("INSERT INTO bookings(user_id,room_id,payment_id,banquet_date) VALUES(?,?,?,?)");
        $stmt->execute([$_SESSION['user_id'],$room,$pay,$d->format('Y-m-d')]);
        header('Location: cabinet.php'); exit;
    }
}
$title='Оформление заявки'; include 'includes/header.php';
?>
<div class="card">
  <h2>Новая заявка</h2>
  <?= $msg ?>
  <form method="post">
    <div class="form-group">
      <label>Помещение</label>
      <select name="room_id" required>
        <?php foreach($rooms as $r): ?>
          <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Дата банкета (ДД.ММ.ГГГГ)</label>
      <input type="text" name="banquet_date" placeholder="01.01.2026" pattern="\d{2}\.\d{2}\.\d{4}" required>
    </div>
    <div class="form-group">
      <label>Способ оплаты</label>
      <select name="payment_id" required>
        <?php foreach($pays as $p): ?>
          <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn">Отправить заявку</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
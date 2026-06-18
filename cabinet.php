<?php
require 'config/db.php';
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$uid=$_SESSION['user_id'];
$stmt=$pdo->prepare("
    SELECT b.*, r.name AS room_name, p.name AS pay_name,
    (SELECT id FROM reviews WHERE booking_id=b.id) AS review_id
    FROM bookings b
    JOIN rooms r ON r.id=b.room_id
    JOIN payment_methods p ON p.id=b.payment_id
    WHERE b.user_id=? ORDER BY b.created_at DESC
");
$stmt->execute([$uid]);
$bookings=$stmt->fetchAll();
$title='Личный кабинет'; include 'includes/header.php';
?>

<section class="hero" style="padding:40px 30px;">
  <h1 style="font-size:28px;">Добро пожаловать, <?= htmlspecialchars($_SESSION['fio']) ?></h1>
  <p>Управляйте своими заявками и оставляйте отзывы</p>
</section>

<div class="slider" id="slider">
  <div class="slides">
    <div class="slide"><img src="assets/images/room1.jpg"></div>
    <div class="slide"><img src="assets/images/room2.jpg"></div>
    <div class="slide"><img src="assets/images/room3.jpg"></div>
    <div class="slide"><img src="assets/images/room4.jpg"></div>
  </div>
  <button class="slider-btn prev" onclick="moveSlide(-1)">‹</button>
  <button class="slider-btn next" onclick="moveSlide(1)">›</button>
</div>

<div class="toolbar">
  <h2 style="color:var(--gold);font-size:22px;">Мои заявки</h2>
  <a href="booking.php" class="btn btn-small" style="margin-left:auto;">+ Новая заявка</a>
</div>

<?php if(!$bookings): ?>
  <p style="color:var(--text-muted);text-align:center;padding:40px;">У вас пока нет заявок.</p>
<?php else: ?>
<div class="table-wrap">
<table>
<tr><th>№</th><th>Помещение</th><th>Дата</th><th>Оплата</th><th>Статус</th><th>Отзыв</th></tr>
<?php foreach($bookings as $b):
    $cls=['Новая'=>'status-new','Банкет назначен'=>'status-assigned','Банкет завершен'=>'status-done'][$b['status']];
?>
<tr>
  <td>#<?= $b['id'] ?></td>
  <td><?= htmlspecialchars($b['room_name']) ?></td>
  <td><?= date('d.m.Y',strtotime($b['banquet_date'])) ?></td>
  <td><?= htmlspecialchars($b['pay_name']) ?></td>
  <td><span class="status <?= $cls ?>"><?= $b['status'] ?></span></td>
  <td>
    <?php if($b['status']!=='Новая' && !$b['review_id']): ?>
      <a href="review.php?id=<?= $b['id'] ?>" class="btn btn-small">Оставить</a>
    <?php elseif($b['review_id']): ?>
      <span style="color:#a3c89a;font-size:13px;">✓ Оставлен</span>
    <?php else: ?>
      <span style="color:var(--text-muted);font-size:13px;">—</span>
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; ?>
</table>
</div>
<?php endif; ?>

<script>
let idx=0;
const slides=document.querySelector('.slides');
const total=document.querySelectorAll('.slide').length;
function moveSlide(d){idx=(idx+d+total)%total;slides.style.transform=`translateX(-${idx*100}%)`;}
setInterval(()=>moveSlide(1),3000);
</script>
<?php include 'includes/footer.php'; ?>
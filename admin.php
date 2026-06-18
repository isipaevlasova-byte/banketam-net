<?php
require 'config/db.php';
if (empty($_SESSION['admin'])) { header('Location: admin_login.php'); exit; }

if (isset($_POST['change_status'])) {
    $stmt=$pdo->prepare("UPDATE bookings SET status=? WHERE id=?");
    $stmt->execute([$_POST['status'],(int)$_POST['id']]);
    header('Location: admin.php'.(isset($_GET['filter'])?'?filter='.urlencode($_GET['filter']):'')); exit;
}

$filter=$_GET['filter']??'';
$sort=$_GET['sort']??'created_at';
if (!in_array($sort,['id','banquet_date','status','created_at'])) $sort='created_at';
$page=max(1,(int)($_GET['page']??1));
$perPage=8; $offset=($page-1)*$perPage;

$where=''; $params=[];
if ($filter && in_array($filter,['Новая','Банкет назначен','Банкет завершен'])) {
    $where=' WHERE b.status=?'; $params[]=$filter;
}
$total=$pdo->prepare("SELECT COUNT(*) FROM bookings b $where");
$total->execute($params);
$totalCount=$total->fetchColumn();
$pages=ceil($totalCount/$perPage);

$sql="SELECT b.*, u.fio, u.phone, r.name room_name, p.name pay_name
      FROM bookings b
      JOIN users u ON u.id=b.user_id
      JOIN rooms r ON r.id=b.room_id
      JOIN payment_methods p ON p.id=b.payment_id
      $where ORDER BY b.$sort DESC LIMIT $perPage OFFSET $offset";
$stmt=$pdo->prepare($sql);
$stmt->execute($params);
$bookings=$stmt->fetchAll();

$title='Админ-панель'; include 'includes/header.php';
?>

<section class="hero" style="padding:40px 30px;">
  <h1 style="font-size:28px;">⚙️ Панель администратора</h1>
  <p>Управление заявками клиентов</p>
</section>

<div class="toolbar">
  <a href="admin.php" class="btn btn-small <?= !$filter?'':'btn-secondary' ?>">Все</a>
  <a href="admin.php?filter=Новая" class="btn btn-small <?= $filter==='Новая'?'':'btn-secondary' ?>">Новые</a>
  <a href="admin.php?filter=Банкет+назначен" class="btn btn-small <?= $filter==='Банкет назначен'?'':'btn-secondary' ?>">Назначенные</a>
  <a href="admin.php?filter=Банкет+завершен" class="btn btn-small <?= $filter==='Банкет завершен'?'':'btn-secondary' ?>">Завершённые</a>
  <span class="toolbar-info">Найдено заявок: <strong style="color:var(--gold);"><?= $totalCount ?></strong></span>
</div>

<div class="table-wrap">
<table>
<tr>
  <th><a href="?sort=id<?= $filter?'&filter='.urlencode($filter):'' ?>">№ ↓</a></th>
  <th>Клиент</th><th>Телефон</th><th>Помещение</th>
  <th><a href="?sort=banquet_date<?= $filter?'&filter='.urlencode($filter):'' ?>">Дата ↓</a></th>
  <th>Оплата</th><th>Статус</th><th>Действие</th>
</tr>
<?php foreach($bookings as $b):
    $cls=['Новая'=>'status-new','Банкет назначен'=>'status-assigned','Банкет завершен'=>'status-done'][$b['status']];
?>
<tr>
  <td>#<?= $b['id'] ?></td>
  <td><?= htmlspecialchars($b['fio']) ?></td>
  <td><?= htmlspecialchars($b['phone']) ?></td>
  <td><?= htmlspecialchars($b['room_name']) ?></td>
  <td><?= date('d.m.Y',strtotime($b['banquet_date'])) ?></td>
  <td><?= htmlspecialchars($b['pay_name']) ?></td>
  <td><span class="status <?= $cls ?>"><?= $b['status'] ?></span></td>
  <td>
    <form method="post" style="display:flex;gap:6px;">
      <input type="hidden" name="id" value="<?= $b['id'] ?>">
      <select name="status" style="padding:6px 10px;font-size:12px;">
        <option <?= $b['status']==='Новая'?'selected':'' ?>>Новая</option>
        <option <?= $b['status']==='Банкет назначен'?'selected':'' ?>>Банкет назначен</option>
        <option <?= $b['status']==='Банкет завершен'?'selected':'' ?>>Банкет завершен</option>
      </select>
      <button name="change_status" class="btn btn-small">OK</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
</div>

<?php if($pages>1): ?>
<div style="margin-top:24px;display:flex;gap:8px;justify-content:center;">
<?php for($i=1;$i<=$pages;$i++): ?>
  <a href="?page=<?= $i ?><?= $filter?'&filter='.urlencode($filter):'' ?>"
     class="btn btn-small <?= $i==$page?'':'btn-secondary' ?>"><?= $i ?></a>
<?php endfor; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
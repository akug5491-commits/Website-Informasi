<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once __DIR__ . '/../db.php';

// Ambil semua konten
$stmt = $conn->prepare("SELECT id,title,category,created_at FROM programs ORDER BY created_at DESC");
$stmt->execute(); $programs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><title>Dashboard Admin</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head><body>
<header class="navbar">
  <div class="header-flex">
    <h1>📊 Dashboard Admin</h1>
    <div>
      <span style="margin-right:12px;">Halo, <?=htmlspecialchars($_SESSION['username'])?></span>
      <a class="btn ghost" href="logout.php">Logout</a>
    </div>
  </div>
</header>

<main class="container">
  <div class="card" style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Konten</h2>
    <a class="btn primary" href="add.php">+ Tambah Konten</a>
  </div>

  <div class="card">
    <table class="table">
      <thead><tr><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach($programs as $p): ?>
        <tr>
          <td><?=htmlspecialchars($p['title'])?></td>
          <td><?=htmlspecialchars($p['category'])?></td>
          <td><?=htmlspecialchars($p['created_at'])?></td>
          <td>
            <a class="btn ghost" href="edit.php?id=<?=$p['id']?>">Edit</a>
            <a class="btn danger" onclick="return confirm('Hapus konten ini?')" href="delete.php?id=<?=$p['id']?>">Hapus</a>
          </td>
        </tr>
        <?php endforeach;?>
        <?php if(empty($programs)): ?>
        <tr><td colspan="4"><i>Belum ada konten.</i></td></tr>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</main>
</body></html>

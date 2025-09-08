<?php
require_once __DIR__ . '/../db.php';
if(!isset($_GET['id'])){ die("Konten tidak ditemukan."); }
$id=(int)$_GET['id'];

$stmt=$conn->prepare("SELECT * FROM programs WHERE id=?");
$stmt->bind_param("i",$id); $stmt->execute(); $p=$stmt->get_result()->fetch_assoc();
if(!$p){ die("Konten tidak ditemukan."); }
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><title><?=htmlspecialchars($p['title'])?></title>
<link rel="stylesheet" href="../css/reader.css">
<style>
.back{background:#fff;color:#1e90ff;border:1px solid #1e90ff;padding:8px 12px;border-radius:10px;text-decoration:none}
.back:hover{background:#e9f3ff}
</style>
</head><body>
<header class="navbar">
  <div class="container" style="display:flex;align-items:center;justify-content:space-between;">
    <a class="back" href="index.php">⬅ Kembali</a>
    <h1 style="margin:0;font-size:20px;"><?=htmlspecialchars($p['title'])?></h1>
    <span></span>
  </div>
</header>

<main class="container">
  <article class="card">
    <span class="badge"><?=htmlspecialchars($p['category'])?></span>
    <h2 class="card-title"><?=htmlspecialchars($p['title'])?></h2>
    <p><?=nl2br(htmlspecialchars($p['description']))?></p>
    <div class="meta">Dibuat: <?=htmlspecialchars($p['created_at'])?></div>
    <div style="margin-top:12px;display:flex;gap:10px;">
      <?php if(!empty($p['file_path'])): ?>
        <a class="btn primary" href="download.php?id=<?=$p['id']?>">Download Lampiran</a>
      <?php endif; ?>
      <a class="btn" href="index.php">Kembali</a>
    </div>
  </article>
</main>
</body></html>

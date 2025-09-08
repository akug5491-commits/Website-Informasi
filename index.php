<?php
require_once __DIR__ . '/../db.php';

// filter
$search = trim($_GET['search'] ?? '');
$cat    = trim($_GET['category'] ?? 'all');

$sql = "SELECT id,title,description,category,created_at FROM programs WHERE 1=1";
$params=[]; $types="";

if($search!==''){
  $sql .= " AND (title LIKE CONCAT('%',?,'%') OR description LIKE CONCAT('%',?,'%'))";
  $params[]=$search; $params[]=$search; $types.="ss";
}
if($cat!=='all'){
  $sql .= " AND category = ?";
  $params[]=$cat; $types.="s";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if(!empty($params)){ $stmt->bind_param($types, ...$params); }
$stmt->execute(); $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><title>Konten Terbaru</title>
<link rel="stylesheet" href="../css/reader.css">
</head><body>
<header class="navbar"><div class="container"><h1>📖 Konten Terbaru</h1></div></header>

<main class="container">
  <form class="filter" method="get">
    <input type="text" name="search" placeholder="🔍 Cari judul atau isi..." value="<?=htmlspecialchars($search)?>" style="flex:1;min-width:200px">
    <select name="category">
      <option value="all" <?=$cat==='all'?'selected':''?>>Semua Kategori</option>
      <?php foreach(['QETABO','BATARA','ALUSI','PATEN','Berita','Artikel','Edukasi','Umum'] as $k): ?>
        <option value="<?=$k?>" <?=$cat===$k?'selected':''?>><?=$k?></option>
      <?php endforeach;?>
    </select>
    <button class="btn primary" type="submit">Filter</button>
    <a class="btn" href="index.php">Reset</a>
  </form>

  <div class="grid grid-2">
    <?php foreach($rows as $p): ?>
    <article class="card">
      <span class="badge"><?=htmlspecialchars($p['category'])?></span>
      <h3 class="card-title"><?=htmlspecialchars($p['title'])?></h3>
      <p>
        <?php
          $desc=$p['description']??'';
          $limit=140;
          $excerpt = mb_strlen($desc)>$limit ? mb_substr($desc,0,$limit).'…' : $desc;
          echo nl2br(htmlspecialchars($excerpt));
        ?>
      </p>
      <div class="meta">Dibuat: <?=htmlspecialchars($p['created_at'])?></div>
      <div style="margin-top:10px;display:flex;gap:8px;">
        <a class="btn primary" href="detail.php?id=<?=$p['id']?>">Baca Selengkapnya</a>
      </div>
    </article>
    <?php endforeach; ?>
    <?php if(empty($rows)): ?>
      <p><i>Tidak ada konten ditemukan.</i></p>
    <?php endif; ?>
  </div>
</main>
</body></html>

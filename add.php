<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $file_path = null;

    if (!empty($_FILES['file']['name'])) {
        $targetDir = __DIR__ . '/../uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['file']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            $file_path = "uploads/" . $fileName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO programs (title, description, category, file_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $category, $file_path);

    if ($stmt->execute()) {
        $message = "✅ Konten berhasil ditambahkan!";
    } else {
        $message = "❌ Gagal menambahkan konten: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Konten</title>
  <link rel="stylesheet" href="../css/form.css">
</head>
<body>
  <div class="container">
    <h1>➕ Tambah Konten Baru</h1>

    <?php if ($message): ?>
      <div class="alert <?= strpos($message, 'berhasil') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label>Judul</label>
      <input type="text" name="title" placeholder="Masukkan judul konten" required>

      <label>Deskripsi</label>
      <textarea name="description" rows="5" placeholder="Tulis deskripsi konten" required></textarea>

      <label>Kategori</label>
      <select name="category" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="Berita">📰 Berita</option>
        <option value="Artikel">📄 Artikel</option>
        <option value="Edukasi">🎓 Edukasi</option>
        <option value="Batara">🌏 Batara</option>
        <option value="Umum">📌 Umum</option>
      </select>

      <label>Upload File (Opsional)</label>
      <input type="file" name="file">

      <button type="submit" class="btn primary">💾 Simpan</button>
      <a href="dashboard.php" class="btn ghost">⬅️ Kembali</a>
    </form>
  </div>
</body>
</html>

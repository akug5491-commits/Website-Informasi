<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Ambil data lama
$stmt = $conn->prepare("SELECT * FROM programs WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$program = $result->fetch_assoc();

if (!$program) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $file_path = $program['file_path'];

    // Jika upload file baru
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

    // Update database
    $stmt = $conn->prepare("UPDATE programs SET title=?, description=?, category=?, file_path=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $description, $category, $file_path, $id);

    if ($stmt->execute()) {
        $message = "✅ Konten berhasil diperbarui!";
        // refresh data
        $program['title'] = $title;
        $program['description'] = $description;
        $program['category'] = $category;
        $program['file_path'] = $file_path;
    } else {
        $message = "❌ Gagal memperbarui konten: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Konten</title>
  <link rel="stylesheet" href="../css/form.css">
</head>
<body>
  <div class="container">
    <h1>✏️ Edit Konten</h1>

    <?php if ($message): ?>
      <div class="alert <?= strpos($message, 'berhasil') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label>Judul</label>
      <input type="text" name="title" value="<?= htmlspecialchars($program['title']) ?>" required>

      <label>Deskripsi</label>
      <textarea name="description" rows="5" required><?= htmlspecialchars($program['description']) ?></textarea>

      <label>Kategori</label>
      <select name="category" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="Berita" <?= $program['category']=="Berita"?"selected":"" ?>>📰 Berita</option>
        <option value="Artikel" <?= $program['category']=="Artikel"?"selected":"" ?>>📄 Artikel</option>
        <option value="Edukasi" <?= $program['category']=="Edukasi"?"selected":"" ?>>🎓 Edukasi</option>
        <option value="Batara" <?= $program['category']=="Batara"?"selected":"" ?>>🌏 Batara</option>
        <option value="Umum" <?= $program['category']=="Umum"?"selected":"" ?>>📌 Umum</option>
      </select>

      <label>Upload File Baru (Opsional)</label>
      <input type="file" name="file">
      <?php if ($program['file_path']): ?>
        <p><small>File saat ini: <a href="../<?= htmlspecialchars($program['file_path']) ?>" target="_blank">Lihat File</a></small></p>
      <?php endif; ?>

      <button type="submit" class="btn primary">💾 Simpan Perubahan</button>
      <a href="dashboard.php" class="btn ghost">⬅️ Kembali</a>
    </form>
  </div>
</body>
</html>

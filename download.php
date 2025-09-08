<?php
require_once __DIR__ . '/../db.php';
if(!isset($_GET['id'])){ die("ID tidak valid."); }
$id=(int)$_GET['id'];

$stmt=$conn->prepare("SELECT title,file_path,description,category FROM programs WHERE id=?");
$stmt->bind_param("i",$id); $stmt->execute(); $row=$stmt->get_result()->fetch_assoc();
if(!$row){ die("Konten tidak ditemukan."); }

if(!empty($row['file_path'])){
  // download file lampiran yang diupload admin
  $full = __DIR__ . '/../' . $row['file_path'];
  if(!file_exists($full)){ die("File tidak ditemukan."); }
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="'.basename($full).'"');
  header('Content-Length: ' . filesize($full));
  readfile($full); exit;
} else {
  // jika tidak ada file, download sebagai .txt dari isi deskripsi
  $filename = preg_replace('/[^A-Za-z0-9_\-]/','_', $row['title']).'.txt';
  header('Content-Type: text/plain; charset=utf-8');
  header('Content-Disposition: attachment; filename="'.$filename.'"');
  echo "Judul: ".$row['title']."\n";
  echo "Kategori: ".$row['category']."\n\n";
  echo $row['description']; exit;
}

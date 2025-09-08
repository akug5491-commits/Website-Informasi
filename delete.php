<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once __DIR__ . '/../db.php';

if(!isset($_GET['id'])){ header("Location: dashboard.php"); exit; }
$id = (int)$_GET['id'];

// ambil file untuk dihapus
$sel = $conn->prepare("SELECT file_path FROM programs WHERE id=?");
$sel->bind_param("i",$id); $sel->execute(); $row=$sel->get_result()->fetch_assoc();

$del = $conn->prepare("DELETE FROM programs WHERE id=?");
$del->bind_param("i",$id);
if($del->execute()){
  if($row && $row['file_path'] && file_exists(__DIR__ . '/../'.$row['file_path'])){
    @unlink(__DIR__ . '/../'.$row['file_path']);
  }
}
header("Location: dashboard.php"); exit;

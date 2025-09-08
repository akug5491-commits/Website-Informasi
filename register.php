<?php
session_start();
require_once __DIR__ . '/../db.php';
$success = $error = "";

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $u = trim($_POST['username']??'');
  $p = trim($_POST['password']??'');
  $c = trim($_POST['confirm']??'');

  if($p!==$c){ $error="Password & Konfirmasi tidak sama"; }
  elseif(strlen($u)<3 || strlen($p)<4){ $error="Username/Password terlalu pendek"; }
  else{
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s",$u); $stmt->execute(); $r=$stmt->get_result();
    if($r->num_rows>0){ $error="Username sudah dipakai"; }
    else{
      $hash = password_hash($p, PASSWORD_DEFAULT);
      $ins = $conn->prepare("INSERT INTO users(username,password) VALUES(?,?)");
      $ins->bind_param("ss",$u,$hash);
      if($ins->execute()){ $success="Registrasi berhasil! Silakan login."; }
      else{ $error="Gagal menyimpan akun."; }
    }
  }
}
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><title>Register Admin</title>
<link rel="stylesheet" href="../css/login.css">
</head><body>
<div class="login-wrapper">
  <div class="login-card">
    <h2>📝 Register Admin</h2>
    <?php if($error):?><div class="error"><?=$error?></div><?php endif;?>
    <?php if($success):?><div class="success"><?=$success?></div><?php endif;?>
    <form method="post">
      <input name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm" placeholder="Konfirmasi Password" required>
      <button class="btn" type="submit">Register</button>
    </form>
    <div class="small">Sudah punya akun? <a href="login.php">Login</a></div>
  </div>
</div>
</body></html>

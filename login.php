<?php
session_start();
require_once __DIR__ . '/../db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $u = trim($_POST['username']??'');
  $p = trim($_POST['password']??'');

  $stmt = $conn->prepare("SELECT id,username,password FROM users WHERE username=? LIMIT 1");
  $stmt->bind_param("s",$u); $stmt->execute(); $res=$stmt->get_result();
  if($res->num_rows===1){
    $user=$res->fetch_assoc();
    if(password_verify($p,$user['password'])){
      $_SESSION['user_id']=$user['id'];
      $_SESSION['username']=$user['username'];
      header("Location: dashboard.php"); exit;
    } else { $error="Password salah"; }
  } else { $error="User tidak ditemukan"; }
}
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><title>Login Admin</title>
<link rel="stylesheet" href="../css/login.css">
</head><body>
<div class="login-wrapper">
  <div class="login-card">
    <h2>🔐 Login Admin</h2>
    <?php if($error):?><div class="error"><?=$error?></div><?php endif;?>
    <form method="post">
      <input name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button class="btn" type="submit">Login</button>
    </form>
    <div class="small">Belum punya akun? <a href="register.php">Daftar</a></div>
  </div>
</div>
</body></html>

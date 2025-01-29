<?php
$pesan = '';
$username = $_GET['username'] ?? null;
$password = $_GET['password'] ?? null;
$username = $_POST['username'] ?? $username;
$password = $_POST['password'] ?? $password;

include 'login-styles.php';

if (isset($_POST['btn_login'])) {
  $_POST['username'] = strip_tags(strtolower($_POST['username']));
  $default_pass = $_POST['username'] == strtolower($_POST['password']) ? 1 : 0;
  $and_pass = $default_pass ? "password is null" : "password='$_POST[password]'";
  $s = "SELECT nama,username,role,status,whatsapp from tb_user WHERE username='$_POST[username]' and $and_pass";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $d = mysqli_fetch_assoc($q);
    $status = $d['status'];

    if ($status) {
      $_SESSION['jadwal_username'] = $_POST['username'];
      echo '<script>location.replace("?")</script>';
    } else {
      include 'login-verifikasi_akun.php';
    }
    exit;
  } else {
    $pesan = 'Maaf, username dan password tidak tepat.';
  }
}
$pesan = $pesan == '' ? $pesan : "<div class='alert alert-danger'>$pesan</div>";
?>

<div class="screen_login">
  <form method=post class="form_login">
    <div class="nama_universitas">Universitas Anda</div>
    <h1><span class="judul_sim">Smart Scheduling System</span> <span class="span_login">Login!</span></h1>
    <div>
      <img class='logo' src="assets/img/favicon.png">
    </div>
    <p class="deskripsi">Dengan Gamification Techniques dan Algoritma Natural Artificial Intelligence</p>
    <?= $pesan ?>
    <div class="login-input">
      <input type="text" class="form-control mb-2" placeholder="username" name="username" minlength=3 maxlength=20 required value="<?= $username ?>">
      <input type="password" class="form-control mb-2" placeholder="password" name="password" minlength=3 maxlength=20 required value="<?= $password ?>">
      <button class="btn btn-primary w-100 mt-2 mb-4" name=btn_login>Login</button>
      <div class="text-small">
        <a href="?register">Register</a> |
        <a href="?lupa_password">Lupa Password</a>
      </div>
    </div>
  </form>
</div>
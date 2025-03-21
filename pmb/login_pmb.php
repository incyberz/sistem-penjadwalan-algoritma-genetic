<?php
set_title('Login PMB');
$pesan = '';
$username = $_GET['username'] ?? null;
$password = $_GET['password'] ?? null;
$username = $_POST['username'] ?? $username;
$password = $_POST['password'] ?? $password;

include 'login_pmb-styles.php';

if (isset($_POST['btn_login'])) {
  $username = strip_tags(strtolower($_POST['username']));
  $s = "SELECT * from tb_akun WHERE username='$username' and password=md5('$_POST[password]')";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $pesan = '';
    $d = mysqli_fetch_assoc($q);
    $_SESSION['pmb_username'] = $_POST['username'];

    if ($d['whatsapp_status']) {
      jsurl('./?daftar&step=3');
    } else {
      jsurl('./?daftar&step=2');
      // include 'login_pmb-verifikasi_akun.php';
    }
    exit;
  } else {
    $pesan = 'Maaf, username dan password tidak tepat.';
  }
}
$pesan = !$pesan ? $pesan : "<div class='alert alert-danger'>$pesan</div>";
?>

<div class="screen_login">
  <form method=post class="form_login">
    <div class="nama_universitas">Universitas Anda</div>
    <h1><span class="judul_sim"></span> <span class="span_login">Login PMB!</span></h1>
    <!-- <div>
      <img class='logo' src="assets/img/favicon.png">
    </div> -->
    <p class="deskripsi">Jadilah bagian dari Kampus Impian dan raih masa depan cerah bersama kami.</p>
    <?= $pesan ?>
    <div class="login-input">
      <input type="text" class="form-control mb-2" placeholder="username" name="username" minlength=3 maxlength=20 required value="<?= $username ?>">
      <input type="password" class="form-control mb-2" placeholder="password" name="password" minlength=3 maxlength=20 required value="<?= $password ?>">
      <button class="btn btn-primary w-100 mt-2 mb-4" name=btn_login>Login</button>
      <div class="text-small">
        <a href="?daftar">Daftar Baru</a> |
        <a href="?lupa_password">Lupa Password</a>
      </div>
    </div>
  </form>
</div>
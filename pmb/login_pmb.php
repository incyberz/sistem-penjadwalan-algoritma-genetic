<?php
# ============================================================
# CEK JIKA SEDANG LOGIN 
# ============================================================
if ($username) {
  alert('Anda sedang login.', 'info');
  $s = "SELECT last_step FROM tb_akun WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  jsurl("?daftar&step=$d[last_step]");
}

# ============================================================
# NORMAL FLOW
# ============================================================
set_title('Login PMB');
$pesan = '';
$username = $_GET['username'] ?? null;
$password = $_GET['password'] ?? null;
$username = $_POST['username'] ?? $username;
$password = $_POST['password'] ?? $password;

$get_role = $_GET['role'] ?? null;
$h1 = $get_role ? 'Login Petugas' : 'Login PMB!';

include 'login_pmb-styles.php';

if (isset($_POST['btn_login'])) {
  $username = strip_tags(strtolower($_POST['username']));
  $tb = $get_role ? 'tb_petugas_pmb' : 'tb_akun';
  $s = "SELECT * from $tb WHERE username='$username' and password=md5('$_POST[password]')";
  // echo '<pre>';
  // var_dump($_SESSION);
  // var_dump($s);
  // echo '<b style=color:red>Developer SEDANG DEBUGING: exit(true)</b></pre>';
  // exit;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $pesan = '';
    $d = mysqli_fetch_assoc($q);
    $_SESSION['pmb_username'] = $_POST['username'];

    $d['role'] = $d['role'] ?? null;

    if ($d['role']) {
      $_SESSION['pmb_role'] = $d['role'];
      jsurl("./?$d[role]");
    } elseif ($d['whatsapp_status']) {
      jsurl("./?daftar&step=$d[last_step]");
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
    <h1><span class="judul_sim"></span> <span class="span_login"><?= $h1 ?></span></h1>
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
      <div class="text-small">
        <hr>
        <a href="?login_pmb&role=petugas">Login Petugas PMB</a>
      </div>
    </div>
  </form>
</div>
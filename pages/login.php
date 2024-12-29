<?php
$pesan = '';
if (isset($_POST['btn_login'])) {
  $_POST['username'] = strip_tags(strtolower($_POST['username']));
  $s = "SELECT 1 from tb_petugas WHERE username='$_POST[username]' and password='$_POST[password]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $_SESSION['ekost_username'] = $_POST['username'];
    echo '<script>location.replace("?")</script>';
    exit;
  } else {
    $pesan = 'Maaf, username dan password tidak tepat.';
  }
}
$pesan = $pesan == '' ? $pesan : "<div class='alert alert-danger'>$pesan</div>";
?>

<style>
  .body {
    background: linear-gradient(#eee, #ccf)
  }

  .login {
    margin-left: auto;
    margin-right: auto;
    max-width: 500px;
    border: solid 1px #ccc;
    border-radius: 15px;
    padding: 15px;
    /* background: white; */
    margin-top: 15px;

  }

  .logo {
    width: 150px;
  }

  .login-input {
    background: #eef
  }

  .judul-sim {
    font-weight: bold;
    letter-spacing: 1px;
    font-size: 20px
  }
</style>
<div class="login text-center gradasi-hijau">
  <h1>LOGIN</h1>
  <div>
    <img class='logo' src="assets/img/logo-login.png">
  </div>
  <div class="judul-sim mb-4 mt-2">
    PENJADWALAN
  </div>
  <?= $pesan ?>
  <form method=post>
    <div class="wadah text-left login-input">
      <input type="text" class="form-control mb-2" placeholder="username" name="username" minlength=3 maxlength=20 required>
      <input type="password" class="form-control mb-2" placeholder="password" name="password" minlength=3 maxlength=20 required>
      <button class="btn btn-primary btn-block" name=btn_login>Login</button>
    </div>
  </form>

</div>
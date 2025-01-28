<?php
$pesan = '';
if (isset($_POST['btn_login'])) {
  $_POST['username'] = strip_tags(strtolower($_POST['username']));
  $default_pass = $_POST['username'] == strtolower($_POST['password']) ? 1 : 0;
  $and_pass = $default_pass ? "password is null" : "password='$_POST[password]'";
  $s = "SELECT 1 from tb_user WHERE username='$_POST[username]' and $and_pass";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $_SESSION['jadwal_username'] = $_POST['username'];
    echo '<script>location.replace("?")</script>';
    exit;
  } else {
    $pesan = 'Maaf, username dan password tidak tepat.';
  }
}
$pesan = $pesan == '' ? $pesan : "<div class='alert alert-danger'>$pesan</div>";
?>

<style>
  * {
    /* margin: 0;
    padding: 0; */
    font-family: 'Century Gothic', 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
  }

  body {
    background: linear-gradient(#eee, #ccf) !important;
    min-height: 100vh;
  }

  .screen_login {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    border: solid 1px red;
    height: 100%;
    text-align: center;
  }

  .form_login {
    margin: auto;
    max-width: 400px;
    border: solid 1px #ccc;
    border-radius: 15px;
    padding: 30px 15px;
    background: linear-gradient(#fff, #efe);
  }

  .logo {
    width: 150px;
  }

  .login-input input {
    /* background: #eef */
    text-align: center;
  }

  .nama_universitas,
  .judul_sim {
    /* font-weight: bold; */
    letter-spacing: 1px;
    font-size: 20px;
    color: blue
  }

  .span_login {
    display: block;
    letter-spacing: 2px;
    font-size: 30px;
    margin: 15px 0;
  }

  .deskripsi {
    font-size: 12px;
    color: gray;
    font-style: italic;
    margin: 12px 0 40px 0
  }
</style>
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
      <input type="text" class="form-control mb-2" placeholder="username" name="username" minlength=3 maxlength=20 required>
      <input type="password" class="form-control mb-2" placeholder="password" name="password" minlength=3 maxlength=20 required>
      <button class="btn btn-primary w-100 mt-2" name=btn_login>Login</button>
    </div>
  </form>
</div>
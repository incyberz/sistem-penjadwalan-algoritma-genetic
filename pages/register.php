<?php
$pesan = '';
$nama = $_POST['nama'] ?? null;
$username = $_POST['username'] ?? null;
$whatsapp = $_POST['whatsapp'] ?? null;
$role = $_POST['role'] ?? null;
$no_id = $_POST['no_id'] ?? null;
if (isset($_POST['username'])) {
  $username = trim(strip_tags(strtolower($_POST['username'])));

  $s = "SELECT 1 FROM tb_user WHERE username = '$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $pesan = "Username [ $username ] telah ada, silahkan pakai yang lain.";
  } else {
    // $pesan = 'OK';
    $s = "INSERT INTO tb_user (
      nama,
      username,
      whatsapp,
      role
    ) VALUES (
      '$_POST[nama]',
      '$username',
      '$_POST[whatsapp]',
      '$_POST[role]'
    )";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    die("
      <div class='alert alert-success'>
        <b>Insert akun sukses.</b><hr>
        Silahkan login menggunakan akun default Anda:
        <ul>
          <li><b>username:</b> $username</li>
          <li><b>password:</b> $username</li>
        </ul>
        <a class='btn btn-primary' href='?login&username=$username&password=$username'>Login</a>
      </div>
    ");
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
    margin: 12px 0 30px 0
  }

  .label {
    font-size: 12px;
    font-style: italic;
    font-weight: bold;
  }

  .input_info {
    font-size: 12px;
    font-style: italic;
    color: gray;
    margin-bottom: 15px;
  }

  .label_active {
    color: blue;
    font-weight: bold;
  }

  label {
    display: block;
    cursor: pointer;
  }

  label:hover {
    background: yellow;
  }
</style>
<div class="screen_login">
  <form method=post class="form_login">
    <div class="nama_universitas">Universitas Anda</div>
    <h1><span class="judul_sim">Smart Scheduling System</span> <span class="span_login">Register!</span></h1>
    <?= $pesan ?>
    <div class="petunjuk mb-2">Silahkan masukan data Anda !</div>
    <div class="login-input">
      <input type="text" class="form-control mb-2 upper" placeholder="Nama Anda..." value="<?= $nama ?>" name="nama" id="nama" minlength=3 maxlength=50 required>
      <input type="text" class="form-control mb-4 lower" placeholder="username" value="<?= $username ?>" name="username" id="username" minlength=3 maxlength=20 required>
      <input type="text" class="form-control mb-1" placeholder="whatsapp" value="<?= $whatsapp ?>" name="whatsapp" id="whatsapp" minlength=10 maxlength=14 required>
      <div class="input_info">hanya dengan whatsapp aktif akun Anda dapat terverifikasi</div>
      <!-- <input type="password" class="form-control mb-2" placeholder="password" name="password" minlength=3 maxlength=20 required>
      <input type="password" class="form-control mb-2" placeholder="confirm password" name="password2" minlength=3 maxlength=20 required> -->

      <div class="card p-2">
        <div class="label mb-2">Role Anda:</div>
        <div class="d-flex">
          <label>
            <input type="radio" name=role required value=MHS> Mhs
          </label>
          <label>
            <input type="radio" name=role required value=DSN> Dosen
          </label>
          <label>
            <input type="radio" name=role required value=AKD> Akademik
          </label>
          <label>
            <input type="radio" name=role required value=PIM> Pimpinan
          </label>
          <label>
            <input type="radio" name=role required value=KEU> Keuangan
          </label>

        </div>
        <input type="text" class="form-control mb-2 upper mt-2" placeholder="NIM/NIDN/NIK Anda..." name="no_id" id="nama" minlength=8 maxlength=16 required value="<?= $no_id ?>">

      </div>

      <button class="btn btn-primary w-100 mt-2 mb-4" name=btn_register>Register via Whatsapp</button>
      <div class="text-small">
        <a href="?login">Login</a>
      </div>
    </div>
  </form>
</div>
<script>
  $(function() {
    $('#nama').keyup(function() {
      let username = $(this).val().toUpperCase();
      $(this).val(username.replace("'", '`').replace(/[^A-Z `']/g, ''));
    })
    $('#username').keyup(function() {
      let username = $(this).val().toLowerCase();
      $(this).val(username.replace(/[^a-z0-9]/g, ''));
    })
    $('label').click(function() {

      $('label').removeClass('label_active');
      $(this).addClass('label_active');
    })
  })
</script>

<?php
include 'includes/script_whatsapp.php';
echo script_whatsapp('whatsapp');

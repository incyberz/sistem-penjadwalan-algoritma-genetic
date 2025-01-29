<?php
if (isset($_POST['nim'])) {
  $s = "INSERT INTO tb_mhs (
    id_user,
    id_shift,
    id_prodi,
    angkatan,
    nama,
    nim
  ) VALUES (
    '$id_user',
    '$_POST[id_shift]',
    '$_POST[id_prodi]',
    '$_POST[angkatan]',
    '$user[nama]',
    '$_POST[nim]'
  )";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  alert("Insert data mhs sukses.", 'success');
  jsurl();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register as Mhs</title>
  <?php
  include 'includes/head_devs.php';
  include 'global_gets_and_cookies.php';
  include 'opt_prodi.php';
  include 'opt_shift.php';
  include 'opt_angkatan.php';
  ?>
</head>

<body>
  <h1 class="tengah">Register as Mhs</h1>
  <form method=post class="wadah gradasi-toska m2 mx-auto " style="max-width: 400px;">
    <input disabled class="form-control mb2" value="Nama: <?= $user['nama'] ?>">

    <select name="id_shift" class="form-control mb2">
      <?= $opt_shift ?>
    </select>
    <select name="id_prodi" class="form-control mb2">
      <?= $opt_prodi ?>
    </select>
    <select name="angkatan" class="form-control mb2">
      <?= $opt_angkatan ?>
    </select>

    <input required minlength="7" maxlength="10" placeholder="NIM..." name="nim" class="form-control mb2">

    <label class="pointer hover f12 mb2">
      <input type="checkbox" required> Saya menyatakan data diatas sudah benar
    </label>

    <button class="btn btn-primary w-100">Register as Mhs</button>
  </form>
</body>

</html>
<?php
if (isset($_POST['btn_submit_password'])) {
  # ============================================================
  # CEK USERNAME DAN PASSWORD > JIKA OK MAKA PASANGKAN, LALU DELETE AKUN LAMA
  # ============================================================
  $s = "SELECT 1 FROM tb_user WHERE username='$_POST[username]' AND password=md5('$_POST[password]') ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    div_alert('danger', 'Data ZZZ tidak ditemukan');
  } else {
    # ============================================================
    # PASANGKAN LALU DELETE AKUN LAMA
    # ============================================================

  }

  echo '<pre>';
  var_dump($_POST);
  echo '<b style=color:red>DEBUGING: echopreExit</b></pre>';
  exit;
}
if (isset($_POST['nidn'])) {
  # ============================================================
  # CEK JIKA NIDN ADA BISA DI-CLAIM
  # ============================================================
  $s = "SELECT a.*,
  (SELECT username FROM tb_user WHERE id=a.id_user) username, 
  (SELECT password FROM tb_user WHERE id=a.id_user) password 
  FROM tb_dosen a WHERE a.nidn='$_POST[nidn]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) { // konflik nidn
    // echolog('konflik nidn');
    $d = mysqli_fetch_assoc($q);

    /// debug ZZZ
    // $d['username'] = 'abi';
    // $d['password'] = md5('123');

    // echo '<pre>';
    // var_dump("$d[password] == $_POST[password]");
    // echo '</pre>';


    if ($d['username'] and $d['password']) {
      // echolog('(username ada + password ada) > masukan password');
      // data user sudah ada > masukan password  
      echo "
        <div>
          <div style='margin:auto; max-width:500px'>
            <div class='wadah tengah'>
              <b class=red>NIDN sudah ada di database.</b> 
              <div class=blue>Silahkan masukan password akun Anda!</div>
            </div>
            <style>
              .wadah input, .wadah button{
                display: block;
                width:100%;
                padding: 5px 10px;
                text-align:center;
              }
            </style>
            <form method=post class='wadah gradasi-kuning'>
              <input type=hidden name=username value='$d[username]' />
              <input type=hidden name=nidn value='$_POST[nidn]' />
              <input type=hidden name=id_prodi value='$_POST[id_prodi]' />
              <input class='mb2' disabled value='username: $d[username]' />
              <input type=password class='mb2' name=password placeholder='enter password...' required />
              <button class='btn btn-primary w-100' name=btn_submit_password value=$d[id]>Submit Password</button>
            </form>
          </div>
        </div>
      ";
      exit;
    } else { // data user belum ada
      echolog('(!username || password is null) > cukup pasangkan');
      $s = "UPDATE tb_dosen SET id_user=$id_user WHERE id=$d[id]";
      echolog("$s");
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  } else { // tidak konflik nidn
    # ============================================================
    # INSERT DATA DOSEN
    # ============================================================
    $id_prodi_or_null = $_POST['id_prodi'] ? $_POST['id_prodi'] : 'NULL';
    $s = "INSERT INTO tb_dosen (
      id_user,
      nama,
      nidn,
      id_prodi
    ) VALUES (
      '$id_user',
      '$user[nama]',
      '$_POST[nidn]',
      $id_prodi_or_null
    )";
    // echolog($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert("Insert data dosen sukses.", 'success');
  }


  jsurl();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register as Dosen</title>
  <?php
  include 'includes/head_devs.php';
  include 'global_gets_and_cookies.php';
  include 'opt_prodi.php';
  ?>
</head>

<body>
  <h1 class="tengah">Register as Dosen</h1>
  <form method=post class="wadah gradasi-toska m2 mx-auto " style="max-width: 400px;">
    <input disabled class="form-control mb2" value="Nama: <?= $user['nama'] ?>">

    <select name="id_prodi" class="form-control mb2">
      <option value="">--Belum/Tidak Homebase--</option>
      <?= $opt_prodi ?>
    </select>

    <input required minlength="7" maxlength="10" placeholder="NIDN/NIDK/NUPTK..." name="nidn" class="form-control mb2">

    <label class="pointer hover f12 mb2">
      <input type="checkbox" required> Saya menyatakan data diatas sudah benar
    </label>

    <button class="btn btn-primary w-100">Register as Dosen</button>
  </form>
</body>

</html>
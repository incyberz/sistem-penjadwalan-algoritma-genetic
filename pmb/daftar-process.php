<?php
if (isset($_POST['btn_set_password'])) {
  $s = "UPDATE tb_akun SET password=md5('$_POST[password]') WHERE username='$_POST[btn_set_password]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $pesan = 'passwordOK';
} elseif (isset($_POST['btn_pilih_jurusan'])) {
  $s = "UPDATE tb_pmb SET id_prodi=$_POST[btn_pilih_jurusan] WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_pilih_ulang_jurusan'])) {
  $s = "UPDATE tb_pmb SET id_prodi=NULL WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_pilih_jalur'])) {
  $s = "UPDATE tb_pmb SET id_jalur=$_POST[id_jalur] WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_reset_jalur'])) {
  $s = "UPDATE tb_pmb SET id_jalur=NULL WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_daftar'])) {
  $post_username = strip_tags(addslashes($_POST['username']));
  $post_nama = strip_tags(addslashes($_POST['nama']));
  $post_whatsapp = strip_tags(addslashes($_POST['whatsapp']));
  $post_asal_sekolah = strip_tags(addslashes($_POST['asal_sekolah']));
  $post_jeda_tahun_lulus = strip_tags(addslashes($_POST['jeda_tahun_lulus']));

  $s = "INSERT INTO tb_akun (
    username,
    tahun_pmb,
    nama,
    whatsapp,
    asal_sekolah,
    jeda_tahun_lulus
  ) VALUES (
    '$post_username',
    '$tahun_pmb',
    '$post_nama',
    '$post_whatsapp',
    '$post_asal_sekolah',
    '$post_jeda_tahun_lulus'
  ) ON DUPLICATE KEY UPDATE 
    created_at = CURRENT_TIMESTAMP
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $pesan = 'OK';
} elseif (isset($_POST['btn_upload_berkas']) || isset($_POST['btn_replace_berkas'])) {
  if (isset($_POST['btn_replace_berkas'])) {
    $t = explode('--', $_POST['btn_replace_berkas']);
    $jenis_berkas = $t[0];
    $src = $t[1];
    # ============================================================
    # DELETE DB
    # ============================================================
    $s = "DELETE FROM tb_berkas WHERE jenis_berkas='$jenis_berkas' AND username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    # ============================================================
    # DELETE FILE SEBELUMNYA
    # ============================================================
    unlink($src);
    alert('Delete file sebelumnya berhasil.', 'success');
  } else {
    $jenis_berkas = $_POST['btn_upload_berkas'];
  }
  include '../includes/resize_img.php';

  $file = $_FILES['file'];
  $path = 'uploads/berkas';
  $time = date('ymdHis');
  $new_file = strtolower("$username-$jenis_berkas-$time.jpg");
  $to = "$path/$new_file";

  if (move_uploaded_file($file['tmp_name'], $to)) {
    resize_img($to);
    # ============================================================
    # INSERT DB
    # ============================================================
    $s = "INSERT INTO tb_berkas (
      username,
      jenis_berkas,
      file,
      status
    ) VALUES (
      '$username',
      '$jenis_berkas',
      '$new_file',
      NULL
    )";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert('Upload sukses.', 'success');
  }

  jsurl();
} elseif ($_POST) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  alert('Belum ada handler untuk data POST diatas. Hubungi Developer!');
  exit;
}

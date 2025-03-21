<?php
if (isset($_POST['btn_set_password'])) {
  $s = "UPDATE tb_akun SET password=md5('$_POST[password]') WHERE username='$_POST[btn_set_password]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $pesan = 'passwordOK';
} elseif (isset($_POST['btn_pilih_jurusan'])) {
  $s = "UPDATE tb_pmb SET id_prodi=$_POST[btn_pilih_jurusan] WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $pesan = 'jurusanOK';
} elseif (isset($_POST['btn_daftar'])) {
  $post_username = strip_tags(addslashes($_POST['username']));
  $post_nama = strip_tags(addslashes($_POST['nama']));
  $post_whatsapp = strip_tags(addslashes($_POST['whatsapp']));
  $post_asal_sekolah = strip_tags(addslashes($_POST['asal_sekolah']));
  $post_tahun_lulus = strip_tags(addslashes($_POST['tahun_lulus']));

  $s = "INSERT INTO tb_akun (
    username,
    nama,
    whatsapp,
    asal_sekolah,
    tahun_lulus
  ) VALUES (
    '$post_username',
    '$post_nama',
    '$post_whatsapp',
    '$post_asal_sekolah',
    '$post_tahun_lulus'
  ) ON DUPLICATE KEY UPDATE 
    created_at = CURRENT_TIMESTAMP
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $pesan = 'OK';
}

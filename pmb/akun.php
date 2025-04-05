<?php
$akun = [];
if ($username) {
  $s = "SELECT * FROM tb_akun WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) die(alert('Data akun tidak ditemukan'));
  $akun = mysqli_fetch_assoc($q);
  $nama_user = ucwords(strtolower($akun['nama']));
  $last_digit_whatsapp = substr($akun['whatsapp'], -3);
}

<?php
if (!$username) jsurl('./?login_pmb');
$s = "SELECT * FROM tb_data_sekolah WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $data_sekolah = mysqli_fetch_assoc($q);
} else {
  $tahun_lulus = $tahun_pmb - $akun['jeda_tahun_lulus'];
  $s = "INSERT INTO tb_data_sekolah (username,nama_sekolah,tahun_lulus) VALUES ('$username','$akun[asal_sekolah]',$tahun_lulus)";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

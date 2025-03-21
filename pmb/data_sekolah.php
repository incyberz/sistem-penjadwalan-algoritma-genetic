<?php
if (!$username) die('belum login.');
$s = "SELECT * FROM tb_data_sekolah WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $data_sekolah = mysqli_fetch_assoc($q);
} else {
  $s = "INSERT INTO tb_data_sekolah (username) VALUES ('$username')";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

<?php
if (!$username) die('belum login.');
$s = "SELECT * FROM tb_data_orangtua WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $data_orangtua = mysqli_fetch_assoc($q);
} else {
  $s = "INSERT INTO tb_data_orangtua (username) VALUES ('$username')";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

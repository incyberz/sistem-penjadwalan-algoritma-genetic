<?php
$s = "SELECT * FROM tb_petugas WHERE username = '$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$petugas = mysqli_fetch_assoc($q);
if (!$petugas) {
  // unset($_SESSION['jadwal_username']);
  // die("User [$username] tidak ada.");
}
$id_petugas = $petugas['id'];

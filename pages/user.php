<?php
$s = "SELECT * FROM tb_user WHERE username = '$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$user = mysqli_fetch_assoc($q);
if (!$user) {
  // unset($_SESSION['jadwal_username']);
  // die("User [$username] tidak ada.");
}
$id_user = $user['id'];
$role = $user['role'];
echo "<span class=hideit id=id_user>$id_user</span>";
echo "<span class=hideit id=role>$role</span>";

if ($role == 'DSN') {
  $s = "SELECT a.*,
  b.gender,
  b.whatsapp,
  b.image,
  (SELECT id FROM tb_st WHERE id_dosen=a.id AND id_ta=$ta_aktif) id_st 
  FROM tb_dosen a 
  JOIN tb_user b ON a.id_user=b.id 
  WHERE a.id_user=$id_user";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) die(alert('Data User Dosen tidak ditemukan'));
  $dosen = mysqli_fetch_assoc($q);
  $id_dosen = $dosen['id'];
}

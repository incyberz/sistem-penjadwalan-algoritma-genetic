<?php
$s = "SELECT * FROM tb_user WHERE username = '$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$user = mysqli_fetch_assoc($q);
if (!$user) {
  // unset($_SESSION['jadwal_username']);
  // die("User [$username] tidak ada.");
}
$id_user = $user['id'];
echo "<span class=hideit id=id_user>$id_user</span>";

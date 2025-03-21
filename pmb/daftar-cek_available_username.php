<?php
include "../conn.php";
$username = $_GET['username'] ?? die('undefined username.');
$s = "SELECT 1 FROM tb_akun WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) die("maaf, username [$username] sudah ada, silahkan pakai yang lain!");
echo 'sukses';

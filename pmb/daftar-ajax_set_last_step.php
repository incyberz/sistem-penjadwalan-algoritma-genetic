<?php
session_start();
$username = $_SESSION['pmb_username'] ?? 'username undefined.';
$step = $_GET['step'] ?? 'step undefined.';
if ($step === '') die('step is empty.');
include '../conn.php';
$s = "UPDATE tb_akun SET last_step='$step' WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
OK
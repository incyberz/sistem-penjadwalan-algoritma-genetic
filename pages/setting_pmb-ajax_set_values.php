<?php
session_start();
$username = $_SESSION['jadwal_username'] ?? 'username undefined.';
$role = $_SESSION['jadwal_role'] ?? 'role undefined.';
if ($role != 'AKD') die('role is not AKD.');


$tb = $_GET['tb'] ?? die('tb undefined.');
$field = $_GET['field'] ?? die('field undefined.');
$new_val = $_GET['new_val'] ?? die('new_val undefined.');
$acuan = $_GET['acuan'] ?? die('acuan undefined.');
$acuan_val = $_GET['acuan_val'] ?? die('acuan_val undefined.');

if ($tb === '') die('tb is empty.');
if ($field === '') die('field is empty.');
if ($new_val === '') die('new_val is empty.');

// allowed NULL
$new_val = $new_val == 'NULL' ? 'NULL' : "'$new_val'";

include '../conn.php';
$s = "UPDATE tb_$tb SET $field=$new_val WHERE $acuan='$acuan_val'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

die('OK');

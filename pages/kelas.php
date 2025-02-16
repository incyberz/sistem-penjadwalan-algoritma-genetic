<?php
if (!isset($id_kelas) || !$id_kelas) die(udef('id_kelas @kelas'));
$s = "SELECT * FROM tb_kelas WHERE id = '$id_kelas'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$kelas = mysqli_fetch_assoc($q);

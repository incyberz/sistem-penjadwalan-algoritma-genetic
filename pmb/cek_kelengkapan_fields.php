<?php
$s = "SELECT * FROM tb_$tb a WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
unset($d['username']);
unset($d['id_sekolah']);
$total = count($d);
$terisi = 0;
foreach ($d as $key => $value) {
  if ($value !== null) $terisi++;
}
$persen = $total ? round($terisi * 100 / $total) : 0;

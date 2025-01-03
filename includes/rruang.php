<?php
$s = "SELECT * FROM tb_ruang";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rruang = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rruang[$d['id']] = $d;
}

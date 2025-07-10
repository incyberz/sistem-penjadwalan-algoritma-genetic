<?php
$s = "SELECT * FROM tb_status_bimbingan ";
$q =  mysqli_query($cn, $s) or die(mysqli_error($cn));
$rStatusBimbingan = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rStatusBimbingan[$d['id']] = $d['status'];
}

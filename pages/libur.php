<?php
$tahun_ta_depan = $tahun_ta + 1;
$s = "SELECT * FROM tb_libur 
WHERE id >= '$tahun_ta-7-1' -- dari Juli tahun ini 
AND id <= '$tahun_ta_depan-7-1' -- hingga Juli tahun depan 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$libur = [];
while ($d = mysqli_fetch_assoc($q)) {
  $libur[$d['id']] = $d;
}

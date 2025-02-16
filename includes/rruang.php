<?php
$s = "SELECT * 
FROM tb_ruang 
WHERE id != 1 -- exclude ruang online 
ORDER BY no,nama 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rruang = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rruang[$d['id']] = $d;
}

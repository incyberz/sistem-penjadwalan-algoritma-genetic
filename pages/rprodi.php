<?php
$s = "SELECT * FROM tb_prodi a WHERE 1";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rprodi = [];
while ($d = mysqli_fetch_assoc($q)) {
  $session_id_prodi = $session_id_prodi ?? $d['id'];
  $rprodi[$d['id']] = $d;
}

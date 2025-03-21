<?php
# ============================================================
# AWAL PMB 
# ============================================================
$s = "SELECT * FROM tb_ta WHERE awal <= '$now' AND akhir > '$now'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  alert('Tidak ada TA aktif untuk saat ini. Silahkan Manage TA.');
} elseif (mysqli_num_rows($q) > 1) {
  alert('Double TA aktif untuk saat ini. Silahkan Manage TA.');
} else {
  $ta = mysqli_fetch_assoc($q);
  $awal_pmb = $ta['awal_pmb'];
  $tahun_ta = substr($ta['id'], 0, 4);
}

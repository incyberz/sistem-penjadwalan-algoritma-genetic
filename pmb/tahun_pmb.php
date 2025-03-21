<?php
# ============================================================
# AWAL PMB 
# ============================================================
$bulan = intval(date('m'));
$tahun = date('Y');
$default_tahun_pmb = $bulan >= 9 ? $tahun + 1 : $tahun;
$get_tahun_pmb = $_GET['tahun_pmb'] ?? $default_tahun_pmb;
$s = "SELECT * FROM tb_tahun_pmb WHERE tahun_pmb=$get_tahun_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  alert("Belum ada Tahun PMB [$get_tahun_pmb]. Silahkan buat dahulu!");
} else {
  $d = mysqli_fetch_assoc($q);
  $tahun_pmb = $d['tahun_pmb'];
  $awal_pmb = $d['awal'];
  $akhir_pmb = $d['akhir'];
}

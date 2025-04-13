<?php
include '../includes/eta.php';
include 'tahun_pmb.php';
include 'gelombang_aktif.php';
include 'info_hari_ini.php';
// include 'session_manager.php';

petugas_only();

$today_show = date('d-M-Y');

echo "
  <h2 class='mb-4 tengah'>Dashboard Petugas PMB</h2>
  $info_hari_ini
";
?>

<div class='d-lg-none'>
  <b class=red>Dashboard PMB hanya dapat diakses via laptop, minimal 992 pixel lebar layar.</b>
</div>

<div class='d-none d-lg-block'>
  <?php include 'petugas-dashboard.php'; ?>
</div>
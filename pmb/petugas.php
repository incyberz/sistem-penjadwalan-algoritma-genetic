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
include 'petugas-dashboard.php';
include 'petugas-dashboard-berkas.php';
include 'petugas-dashboard-jadwal_tes.php';
include 'petugas-dashboard-stuck.php';
include 'petugas-dashboard-feedback.php';
include 'petugas-dashboard-peta_pendaftar.php';
include 'petugas-dashboard-sekolah_asal.php';
include 'petugas-dashboard_grafik.php';

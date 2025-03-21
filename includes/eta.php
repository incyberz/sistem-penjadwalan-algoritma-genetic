<?php
function eta2($datetime, $indo = 1)
{
  return eta(strtotime($datetime) - strtotime('now'));
}

function eta($detik, $indo = 1)
{
  $menit = '';
  $jam = '';
  $hari = '';
  $minggu = '';
  $bulan = '';

  if ($detik >= 0) {
    if ($detik < 60) {
      return $indo ? "$detik detik lagi" : "$detik seconds left";
    } elseif ($detik < 60 * 60) {
      $menit = ceil($detik / 60);
      return $indo ? "$menit menit lagi" : "$menit minutes left";
    } elseif ($detik < 60 * 60 * 24) {
      $jam = ceil($detik / (60 * 60));
      return $indo ? "$jam jam lagi" : "$jam hours left";
    } elseif ($detik < 60 * 60 * 24 * 7) {
      $hari = ceil($detik / (60 * 60 * 24));
      return $indo ? "$hari hari lagi" : "$hari days left";
    } elseif ($detik < 60 * 60 * 24 * 7 * 4) {
      $minggu = ceil($detik / (60 * 60 * 24 * 7));
      return $indo ? "$minggu minggu lagi" : "$minggu weeks left";
    } elseif ($detik < 60 * 60 * 24 * 365) {
      $bulan = ceil($detik / (60 * 60 * 24 * 7 * 4));
      return $indo ? "$bulan bulan lagi" : "$bulan monts left";
    } else {
      $tahun = ceil($detik / (60 * 60 * 24 * 365));
      return $indo ? "$tahun tahun lagi" : "$tahun years left";
    }
  } else {
    if ($detik > -60) {
      $detik = -$detik;
      return $indo ? "$detik detik yang lalu" : "$detik seconds ago";
    } elseif ($detik > -60 * 60) {
      $menit = ceil($detik / 60);
      $menit = -$menit;
      return $indo ? "$menit menit yang lalu" : "$menit minutes ago";
    } elseif ($detik > -60 * 60 * 24) {
      $jam = ceil($detik / (60 * 60));
      $jam = -$jam;
      return $indo ? "$jam jam yang lalu" : "$jam hours ago";
    } elseif ($detik > -60 * 60 * 24 * 7) {
      $hari = ceil($detik / (60 * 60 * 24));
      $hari = -$hari;
      return $indo ? "$hari hari yang lalu" : "$hari days ago";
    } elseif ($detik > -60 * 60 * 24 * 7 * 4) {
      $minggu = ceil($detik / (60 * 60 * 24 * 7));
      $minggu = -$minggu;
      return $indo ? "$minggu minggu yang lalu" : "$minggu weeks ago";
    } elseif ($detik > -60 * 60 * 24 * 365) {
      $bulan = ceil($detik / (60 * 60 * 24 * 7 * 4));
      $bulan = -$bulan;
      return $indo ? "$bulan bulan yang lalu" : "$bulan monts ago";
    } else {
      $tahun = ceil($detik / (60 * 60 * 24 * 365));
      $tahun = -$tahun;
      return $indo ? "$tahun tahun yang lalu" : "$tahun years ago";
    }
  }
}

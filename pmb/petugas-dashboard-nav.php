<?php
# ============================================================
# NAVS 
# ============================================================
$nav_gels = '';
$nav_gels2 = '';
$s = "SELECT * FROM tb_gelombang WHERE tahun_pmb=$tahun_pmb ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_gel = mysqli_num_rows($q);
if (!$count_gel) die("Belum ada data Gelombang untuk tahun ini.");
$rgel = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rgel[$d['nomor']] = $d;
  $nav_active = $get_gel == $d['nomor'] ? 'nav-active' : '';
  $nav_gels .= "<a class='nav $nav_active hover' href=?petugas&time=all_time&gel=$d[nomor]><span class=putih>Gel-$d[nomor]</span></a>";
  $nav_gels2 .= "<a class='nav $nav_active hover' href=?pendaftar&gel=$d[nomor]><span class=putih>Gel-$d[nomor]</span></a>";
}

$nav_active = $get_gel == 'all' ? 'nav-active' : '';
$nav_gels .= "<a class='nav $nav_active hover' href=?petugas&time=all_time&gel=all><span class=putih>All</span></a>";


$rtime = [
  'hari_ini' => [
    'title' => 'Hari ini',
    'awal' => $today,
    'akhir' => "$today 23:59:59",
  ],
  'bulan_ini' => [
    'title' => 'Bulan ini',
    'awal' => date('Y-m') . '-01',
    'akhir' => "$today 23:59:59",
  ],
  'all_time' => [
    'title' => 'All time',
    'awal' => $awal_pmb,
    'akhir' => "$today 23:59:59",
  ],
];
$nav_times = '';
foreach ($rtime as $key => $rv) {
  $nav_active = $get_time == $key ? 'nav-active' : '';
  $nav_times .= "<a class='nav $nav_active hover' href=?petugas&time=$key&gel=all><span class=putih>$rv[title]</span></a>";
}

$awal = $rtime[$get_time]['awal'];
$akhir = $rtime[$get_time]['akhir'];
if ($get_gel != 'all') {
  if ($get_gel == 1) {
    $awal = $awal_pmb;
    $akhir = $rgel[$get_gel]['batas_akhir'];
  } elseif ($get_gel > 1 and $get_gel <= $count_gel) {
    $awal = date('Y-m-d', strtotime('+1 day', strtotime($rgel[$get_gel - 1]['batas_akhir'])));
    $akhir = $rgel[$get_gel]['batas_akhir'];
  } else {
    die("Invalid value untuk gelombang: $get_gel");
  }
}

$awal_show = date('D, M d, Y', strtotime($awal));
$akhir_show = $get_gel == 'all' ? date('D, M d, Y, H:i:s', strtotime($akhir)) : date('D, M d, Y', strtotime($akhir));

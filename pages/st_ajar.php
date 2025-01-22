<?php
# ============================================================
# SK AJAR
# ============================================================
$id_st = $_GET['id_st'] ?? '';
$id_dosen = $_GET['id_dosen'] ?? '';
$print = $_GET['print'] ?? '';
$aksi = $_GET['aksi'] ?? 'manage';
if (!$id_st) $aksi = 'list_dosen';
$siap_assign = true;
$pesan_error = '';
$mk_available = 0;
$img_next = img_icon('next');

# ============================================================
# DATA DOSEN + DATA ST/MK SEBELUMNYA
# ============================================================
// $rkumk = [];
// $Create = 'Create';
// if ($id_dosen) {
//   $s = "SELECT a.*,
//   (SELECT id FROM tb_st WHERE id_dosen=a.id AND id_ta=$ta_aktif) id_st
//   FROM tb_dosen a WHERE a.id=$id_dosen";
//   $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
//   $dosen = mysqli_fetch_assoc($q);
//   $spasi = $dosen['gelar_depan'] ? ' ' : '';
//   $koma = $dosen['gelar_belakang'] ? ', ' : '';
//   $nama_lengkap_dosen = "$dosen[gelar_depan]$spasi$dosen[nama]$koma$dosen[gelar_belakang]";
//   if ($dosen['id_st']) {
//     $Create = 'Manage';
//     # ============================================================
//     # GET MK PADA SURAT TUGAS
//     # ============================================================
//     $s = "SELECT a.*,
//     -- (SELECT COUNT(1) FROM tb_st_mk_kelas WHERE id_st_detail=a.id) jumlah_kelas 
//     (SELECT 0) jumlah_kelas 
//     FROM tb_st_detail a 
//     WHERE a.id_st='$dosen[id_st]'";
//     $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
//     while ($d = mysqli_fetch_assoc($q)) {
//       $rkumk[$d['id_kumk']] = ['jumlah_kelas' => $d['jumlah_kelas']];
//     }
//   }
// }

include 'st_ajar-styles.php';
include 'st_ajar-processors.php';

# ============================================================
# DATA KURIKULUM
# ============================================================
// $s = "SELECT a.*,b.fakultas,
// (SELECT nama FROM tb_prodi WHERE id=a.id_prodi) nama_prodi  
// FROM tb_kurikulum a 
// JOIN tb_prodi b ON a.id_prodi=b.id 
// WHERE a.id = $id_kurikulum";
// $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// $kurikulum = mysqli_fetch_assoc($q);

# ============================================================
# DATES
# ============================================================
// $Tahun = intval($kurikulum['id_ta'] / 10);
include 'includes/arr_bulan_romawi.php';
$bulan_romawi = $arr_bulan_romawi[date('m')];


if ($aksi) {
  $file = "st_ajar-$aksi.php";
  if (file_exists("pages/$file")) {
    include $file;
  } elseif ($aksi == 'drop_mk') {
    $id = $_GET['id_st_detail'] ?? udef('id_st_detail');
    $s = "DELETE FROM tb_st_detail WHERE id='$id'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    jsurl("?st_ajar&aksi=manage&id_st=$id_st", 5000);
  } else {
    alert("Aksi [$aksi] belum ada handler.");
  }
}

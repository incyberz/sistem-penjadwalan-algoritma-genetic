<?php
# ============================================================
# SK AJAR
# ============================================================
$id_st = $_GET['id_st'] ?? '';
$get_id_st = $id_st;
$get_id_kelas = $_GET['id_kelas'] ?? '';

if ($role == 'DSN' and (!$id_st or $id_st != $dosen['id_st'])) {

  if ($dosen['id_st']) {
    jsurl("?st&id_st=$dosen[id_st]");
  } else {
    alert("Anda belum punya Surat Tugas di TA. $tahun_ta $Gg. <hr>Silahkan hubungi Akademik untuk info lanjut.");
    exit;
  }
}
$id_dosen = $_GET['id_dosen'] ?? '';
$print = $_GET['print'] ?? '';
$aksi = $_GET['aksi'] ?? 'manage';
if (!$id_st) $aksi = 'list_dosen';
$siap_assign = true;
$pesan_error = '';
$mk_available = 0;
$img_next = img_icon('next');

include 'st-styles.php';
include 'st-processors.php';

# ============================================================
# DATES
# ============================================================
include 'includes/arr_bulan_romawi.php';
$bulan_romawi = $arr_bulan_romawi[date('m')];
if ($aksi) {
  $file = "st-$aksi.php";
  if (file_exists("pages/$file")) {
    include $file;
  } elseif ($aksi == 'drop_mk') {
    $id = $_GET['id_st_detail'] ?? udef('id_st_detail');
    $s = "DELETE FROM tb_st_detail WHERE id='$id'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    jsurl("?st&aksi=manage&id_st=$id_st", 500);
  } else {
    alert("Aksi [$aksi] belum ada handler.");
  }
}

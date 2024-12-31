<?php
# ============================================================
# SK AJAR
# ============================================================
$id_kurikulum = $_GET['id_kurikulum'] ?? udef('id_kurikulum');
$aksi = $_GET['aksi'] ?? 'create';
$siap_assign = true;
$pesan_error = '';

include 'st_ajar-styles.php';
include 'st_ajar-processors.php';

# ============================================================
# DATA KURIKULUM
# ============================================================
$s = "SELECT a.*,
(SELECT nama FROM tb_prodi WHERE id=a.id_prodi) nama_prodi  
FROM tb_kurikulum a WHERE a.id = $id_kurikulum";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$dkur = mysqli_fetch_assoc($q);
// $Ganjil = $dkur['id_ta'] % 2 == 0 ? 'Genap' : 'Ganjil';

# ============================================================
# DATES
# ============================================================
$Tahun = intval($dkur['id_ta'] / 10);
include 'includes/arr_bulan_romawi.php';
$bulan_romawi = $arr_bulan_romawi[date('m')];
$untuk_mengampu = "<p>Untuk mengampu matakuliah di <b>TA. $Tahun $Ganjil</b> sebagai berikut:</p>";


if ($aksi) {
  $file = "st_ajar-$aksi.php";
  if (file_exists("pages/$file")) {
    include $file;
  } elseif ($aksi == 'drop_mk') {
    $id_st = $_GET['id_st'] ?? udef('id_st');
    $id_mk = $_GET['id_mk'] ?? udef('id_mk');
    $s = "DELETE FROM tb_st_mk WHERE id_st = '$id_st' AND id_mk='$id_mk'";
    echolog($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    jsurl("?st_ajar&id_kurikulum=2&aksi=manage&id_st=$id_st");
  } else {
    alert("Aksi [$aksi] belum ada handler.");
  }
}

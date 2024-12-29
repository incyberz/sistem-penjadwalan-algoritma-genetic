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
$is_ganjil = $dkur['id_ta'] % 2 == 0 ? 0 : 1;
$Ganjil = $is_ganjil ? 'Ganjil' : 'Genap';
$Tahun = intval($dkur['id_ta'] / 10);
include 'includes/arr_bulan_romawi.php';
$bulan_romawi = $arr_bulan_romawi[date('m')];


if ($aksi) {
  $file = "st_ajar-$aksi.php";
  if (file_exists("pages/$file")) {
    include $file;
  } else {
    alert("Aksi [$aksi] belum ada handler.");
  }
}

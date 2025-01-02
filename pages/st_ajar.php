<?php
# ============================================================
# SK AJAR
# ============================================================
$img_next = img_icon('next');
$id_dosen = $_GET['id_dosen'] ?? '';
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if (!$id_kurikulum) {
  $s = "SELECT * FROM tb_kurikulum WHERE id_ta=$ta_aktif";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $divs = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $divs .= "
      <div>
        <a class='btn btn-success mb2' href='?st_ajar&id_kurikulum=$d[id]'>$d[nama]</a>
      </div>";
  }

  set_title('Buat Surat Tugas...');
  echo "
    <div>
      <div class='mb2'>Buat Surat Tugas dengan <span class=blue>Rekomendasi Homebase</span>:</div>
      $divs
    </div>";

  exit;
}
$aksi = $_GET['aksi'] ?? 'create';
$siap_assign = true;
$pesan_error = '';
$mk_available = 0;


# ============================================================
# DATA DOSEN + DATA ST/MK SEBELUMNYA
# ============================================================
$rmk = [];
$Create = 'Create';
if ($id_dosen) {
  $s = "SELECT a.*,
  (SELECT id FROM tb_st WHERE id_dosen=a.id AND id_ta=$ta_aktif) id_st
  FROM tb_dosen a WHERE a.id=$id_dosen";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $dosen = mysqli_fetch_assoc($q);
  $spasi = $dosen['gelar_depan'] ? ' ' : '';
  $koma = $dosen['gelar_belakang'] ? ', ' : '';
  $nama_lengkap_dosen = "$dosen[gelar_depan]$spasi$dosen[nama]$koma$dosen[gelar_belakang]";
  if ($dosen['id_st']) {
    $Create = 'Manage';
    # ============================================================
    # GET MK PADA SURAT TUGAS
    # ============================================================
    $s = "SELECT a.*,
    (SELECT COUNT(1) FROM tb_st_mk_kelas WHERE id_st_mk=a.id) jumlah_kelas 
    FROM tb_st_mk a WHERE a.id_st='$dosen[id_st]'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    while ($d = mysqli_fetch_assoc($q)) {
      $rmk[$d['id_mk']] = ['jumlah_kelas' => $d['jumlah_kelas']];
    }
  }
}

include 'st_ajar-styles.php';
include 'st_ajar-processors.php';

# ============================================================
# DATA KURIKULUM
# ============================================================
$s = "SELECT a.*,
(SELECT nama FROM tb_prodi WHERE id=a.id_prodi) nama_prodi  
FROM tb_kurikulum a WHERE a.id = $id_kurikulum";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$kurikulum = mysqli_fetch_assoc($q);

# ============================================================
# DATES
# ============================================================
$Tahun = intval($kurikulum['id_ta'] / 10);
include 'includes/arr_bulan_romawi.php';
$bulan_romawi = $arr_bulan_romawi[date('m')];
$untuk_mengampu = "<p>Untuk mengampu matakuliah di <b>TA. $Tahun $Gg</b> sebagai berikut:</p>";


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

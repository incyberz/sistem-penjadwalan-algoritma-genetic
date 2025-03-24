<?php
session_start();
$username = $_SESSION['pmb_username'] ?? 'username undefined.';
$tb = $_GET['tb'] ?? 'tb undefined.';
$field = $_GET['field'] ?? 'field undefined.';
$new_val = $_GET['new_val'] ?? 'new_val undefined.';

if ($tb === '') die('tb is empty.');
if ($field === '') die('field is empty.');
if ($new_val === '') die('new_val is empty.');

// disallowed NULL
// $new_val = $new_val ? "'$new_val'" : 'NULL';

# ====================================
# EXCEPTION FIELDS
# ====================================
include '../conn.php';
if ($field == 'nomor_ktp') {
  $id_kec = substr($new_val, 0, 6);
  $tgl = substr($new_val, 6, 2);
  $bln = substr($new_val, 8, 2);
  $thn = substr($new_val, 10, 2);
  $counter = substr($new_val, 12, 4);

  $s = "SELECT 
  a.nama_kec,
  a.kode_pos,
  b.nama_kab,
  c.nama_prov 
  FROM tb_kec a 
  JOIN tb_kab b ON a.id_kab=b.id_kab 
  JOIN tb_prov c ON b.id_prov=c.id_prov 
  WHERE a.id_kec='$id_kec'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    die("Kode Nomor KTP tidak valid.\nSilahkan hubungi Petugas jika ada kesalahan kode kecamatan.");
  } else {
    $kec = mysqli_fetch_assoc($q);
    $nama_kec = strtoupper($kec['nama_kec']);
    $nama_kab = strtoupper($kec['nama_kab']);
    $nama_prov = strtoupper($kec['nama_prov']);
    $kode_pos = strtoupper($kec['kode_pos']);
  }


  if ($tgl > 40) {
    $tgl -= 40;
    $gender = 'P';
  } else {
    $gender = 'L';
  }

  $tahun = $thn > 70 ? "19$thn" : "20$thn";

  if (!strtotime("$tahun-$bln-$tgl")) die("Kode Nomor KTP tidak valid.\nSilahkan hubungi Petugas jika ada kesalahan tanggal lahir.");
  $tgl = $tgl < 10 ? "0$tgl" : $tgl;
  $tl = "$tahun-$bln-$tgl";

  if (!intval($counter)) die("Kode Nomor KTP tidak valid.\nSilahkan hubungi Petugas jika ada kesalahan pada counter kependudukan.");

  $arr = [
    'nama_kec' => $nama_kec,
    'nama_kab' => $nama_kab,
    'nama_prov' => $nama_prov,
    'kode_pos' => $kode_pos,
    'gender' => $gender,
    'tl' => $tl,
  ];

  # ============================================================
  # SAVE DB BY NOMOR KTP
  # ============================================================
  $s = "UPDATE tb_$tb SET 
    nomor_ktp='$new_val',
    gender='$gender',
    kecamatan='$nama_kec', 
    kabupaten='$nama_kab', 
    provinsi='$nama_prov' 
  WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $tpl = str_replace('KAB. ', '', $nama_kab);
  $s = "UPDATE tb_$tb SET tempat_lahir='$tpl' WHERE username='$username' AND tempat_lahir is null";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "UPDATE tb_$tb SET tanggal_lahir='$tl' WHERE username='$username' AND tanggal_lahir is null";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "UPDATE tb_$tb SET kode_pos='$kode_pos' WHERE username='$username' AND kode_pos is null";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  # ============================================================
  # REPLY JSON NOMOR KTP
  # ============================================================
  echo json_encode($arr);
} else {
  $s = "UPDATE tb_$tb SET $field='$new_val' WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  # ============================================================
  # CEK KELENGKAPAN FIELDS
  # ============================================================
  include 'cek_kelengkapan_fields.php';

  echo "OK--$terisi--$total--$persen";
}

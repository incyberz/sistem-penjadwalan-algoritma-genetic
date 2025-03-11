<?php
if (0) {
  # ============================================================
  # DEBUGGING
  # ============================================================
  echo '<pre>';
  echo '<b style=color:red>Developer SEDANG DEBUGING</b></pre>';
  $role = 'AKD'; // zzz debug
  $id_mhs = 15; // aena
}

set_h2("Bimbingan", "Bimbingan Mahasiswa PAL, PKL, dan TA");
$arr_hari = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$p = $_GET['p'] ?? null;


# ============================================================
# BIMBINGAN PROCESSORS 
# ============================================================
include 'bimbingan-processors.php';

# ============================================================
# SELECT PESERTA BIMBINGAN
# ============================================================
$select_peserta_bimbingan = "SELECT 
a.*,
b.id as id_mhs,
b.nama as nama_mhs,
b.nim,
d.id as id_dosen,
d.nama as nama_dosen,
e.singkatan as prodi 

FROM tb_peserta_bimbingan a 
JOIN tb_mhs b ON b.id=a.id_mhs 
JOIN tb_bimbingan c ON c.id=a.id_bimbingan 
JOIN tb_dosen d ON d.id=c.id_dosen 
JOIN tb_prodi e ON e.id=b.id_prodi
WHERE c.id_ta = $ta_aktif
";


if (!$p) {
  if ($role == 'DSN') {
    include 'bimbingan-dashboard-dosen.php';
  } elseif ($role == 'AKD') {
    include 'bimbingan-dashboard-akademik.php';
  } elseif ($role == 'MHS') {
    include 'bimbingan-dashboard-mhs.php';
  } else {
    die("Belum ada dashboard bimbingan untuk role [$role]");
  }
} else {
  $file = "pages/bimbingan-$p.php";
  if (file_exists($file)) {
    include $file;
  } else {
    die("<b class=red>Proses [$p] belum ada.</b>");
  }
}

// if (isset($_POST['btn_add_bimbingan'])) {
//   $s = "INSERT INTO tb_bimbingan (
//     id,
//     id_ta,
//     id_dosen,
//     wag,
//     hari_availables
//   ) VALUES (
//     '$ta_aktif-$id_dosen',
//     $ta_aktif,
//     $id_dosen,
//     '$_POST[wag]',
//     '$_POST[hari_availables]'
//   ) ON DUPLICATE KEY UPDATE 
//     wag='$_POST[wag]',
//     hari_availables='$_POST[hari_availables]'
//   ";
//   $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
//   jsurl();
// }

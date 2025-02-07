<?php
session_start();
$id_ta = $_SESSION['ta_aktif'] ?? die(`Undefined session-index [ta_aktif]. \n\nSilahkan relogin!`);

$id_dosen = $_GET['id_dosen'] ?? die("Undefined index [id_dosen]");
$id_kumk = $_GET['id_kumk'] ?? die("Undefined index [id_kumk]");
$id_user = $_GET['id_user'] ?? die("Undefined index [id_user]");
$id_shift = $_GET['id_shift'] ?? die("Undefined index [id_shift]");
$id_kelass = $_GET['id_kelass'] ?? die("Undefined index [id_kelass]"); // auto assign ke kelas-kelas

if ($id_kelass === '') die('Index [id_kelass] cannot empty.');
if ($id_shift === '') die('Index [id_shift] cannot empty.');
if ($id_user === '') die('Index [id_user] cannot empty.');

include '../conn.php';

# ============================================================
# 1 KU-MK WAJIB 1 DOSEN || DELETE ST DETAIL SEBELUMNYA 
# ============================================================
$s = "DELETE FROM tb_st_detail WHERE id_kumk='$id_kumk'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

# ============================================================
# CREATE SURAT TUGAS JIKA BELUM ADA
# ============================================================
$id_st = "$id_ta-$id_dosen";
$s = "INSERT INTO tb_st (
  id,
  id_dosen,
  id_ta,
  id_user
) VALUES (
  '$id_st',
  '$id_dosen',
  '$id_ta',
  '$id_user'
) ON DUPLICATE KEY UPDATE 
  tanggal=CURRENT_TIMESTAMP, 
  id_user=$id_user
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$rid_kelas = explode(', ', $id_kelass);
foreach ($rid_kelas as $id_kelas) {
  # ============================================================
  # INSERT ST-DETAIL
  # ============================================================
  $id_st_detail = "$id_ta-$id_dosen-$id_kumk-$id_kelas-$id_shift"; // TA-DS-KU-MK-KLS-SHIFT 	
  $unik_kumk = "$id_kumk-" . strtolower($id_shift); // KU MK SHIFT
  $s = "INSERT INTO tb_st_detail (
    id,
    id_st,
    id_kumk,
    id_kelas,
    id_shift
  ) VALUES (
    '$id_st_detail',
    '$id_st',
    '$id_kumk',
    '$id_kelas',
    '$id_shift'
  ) 
  ON DUPLICATE KEY UPDATE -- Rule: 1 dosen 1 MK 1 shift
    id='$id_st_detail',
    id_st='$id_st',
    id_kumk='$id_kumk',
    id_kelas='$id_kelas',
    id_shift='$id_shift'
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}


echo 'sukses';

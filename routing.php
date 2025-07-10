<?php
# ============================================================
# GET PARAM
# ============================================================
$param = null;
if ($_GET) {
  foreach ($_GET as $key => $value) {
    $param = $key;
    break;
  }
}

# ============================================================
# dilarang login jika sudah login
# ============================================================
if ($param == 'login' and $username) die('<script>location.replace("?")</script>');

# ============================================================
# ADDRESS ROUTE 
# ============================================================
$home = $role == 'MHS' ? 'home_mhs' : 'home';
$arr_route = [
  '' => $home,
  '?' => $home,
  // 'add-peserta-bimbingan' => 'bimbingan-add_peserta_bimbingan',
  // 'add-dosen-pembimbing' => 'bimbingan&p=add_dosen_pembimbing',
  // 'daftar-peserta-bimbingan' => 'bimbingan&p=daftar_peserta_bimbingan',
  // 'riwayat-laporan-bimbingan' => 'bimbingan&p=riwayat_laporan',
];

// custom param berdasarkan address route
if (key_exists($param, $arr_route)) $param = $arr_route[$param];


# ============================================================
# SWITCH PARAMETER
# ============================================================
// default konten berada di folder pages
$konten = "pages/$param.php";

if (file_exists($konten)) {
  if ($username) {
    include $konten;
  } else {
    if ($param == 'register' || $param == 'lupa_password' || $param == 'verifikasi_whatsapp') {
      // include page tersebut
      include "pages/$param.php";
    } else {
      include 'pages/login.php';
    }
  }
} else {
  include 'na.php';
}

// if ($param != 'ubah_password') {
//   if ($username and $password == '') {
//     if (!isset($_SESSION['dipa_master_username'])) {
//       echo '<script>location.replace("?ubah_password")</script>';
//       exit;
//     }
//   }
// }

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
$arr_route = [
  '' => 'home',
  '?' => 'home',
];


# ============================================================
# SWITCH PARAMETER
# ============================================================
$konten = null;
if (key_exists($param, $arr_route)) {
  $param = $arr_route[$param];
}

$konten = $konten ?? $param;

// default konten berada di folder pages
if (!file_exists($konten)) $konten = "pages/$konten.php";

if (file_exists($konten)) {
  if ($username) {
    include $konten;
  } else {
    include 'pages/login.php';
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

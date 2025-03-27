<?php
session_start();
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
$https_api_wa = 'https://api.whatsapp.com/send';
$text_wa_from = "\n\n```From: Smart PMB System \nat $now```";
$arr_hari = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$arr_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$arr = explode('?', $_SERVER['REQUEST_URI']);
$nama_server = "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]$arr[0]";
$username = $_SESSION['pmb_username'] ?? null;

include '../conn.php';
include '../config.php';
include '../includes/jsurl.php';
include '../includes/set_h2.php';
include '../includes/alert.php';
$p = '';
foreach ($_GET as $key => $value) {
  $p = $key;
  break;
}

# ============================================================
# SESSION MANAGER 
# ============================================================
include 'session_manager.php';

# ============================================================
# TAHUN, AWAL, AKHIR PMB 
# ============================================================
include 'tahun_pmb.php';


?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pendaftaran Akun</title>
  <?php if ($is_live) { ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <?php } else { ?>
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <script src="../../assets/vendor/jquery/jquery-3.7.1.min.js"></script>

  <?php } ?>
  <link rel="stylesheet" href="../assets/css/insho_styles_min.css">


</head>

<body>
  <div class="container mt-5">
    <?php

    if ($p) {
      if ($awal_pmb) {
        include "$p.php";
      } else {
        include 'set_awal_pmb.php';
      }
    } else {
      jsurl('welcome.php');
    }
    ?>

  </div>
</body>

</html>

<?php if ($is_live) { ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php } else { ?>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<?php } ?>
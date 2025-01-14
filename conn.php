<?php
# ============================================================
# DATABASE CONNECTION
# ============================================================
date_default_timezone_set("Asia/Jakarta");
$is_live = $_SERVER['SERVER_NAME'] == 'localhost' ? 0 : 1;

$db_server = 'localhost';
if ($is_live) {
  $db_user = "mmcclini_admin";
  $db_pass = "MMC-Clinic2024";
  $db_name = "mmcclini_jadwal";
} else {
  $db_user = 'root';
  $db_pass = '';
  $db_name = 'db_jadwal';
}

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
$cn = $conn;
if ($conn->connect_errno) {
  echo "Error Konfigurasi# Tidak dapat terhubung ke MySQL Server :: $db_name";
  exit();
}

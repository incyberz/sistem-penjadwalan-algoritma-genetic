<?php
# ============================================================
# DATABASE CONNECTION
# ============================================================
$is_live = $_SERVER['SERVER_NAME'] == 'localhost' ? 0 : 1;

if ($is_live) {
  $db_server = 'localhost';
  $db_user = 'pesc7881_insho';
  $db_pass = "hasd2q'qC3D}+Hzj@TT";
  $db_name = 'pesc7d3881_dipa';
} else {
  $db_server = 'localhost';
  $db_user = 'root';
  $db_pass = '';
  $db_name = 'db_jadwal';
}

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($conn->connect_errno) {
  echo "Error Konfigurasi# Tidak dapat terhubung ke MySQL Server :: $db_name";
  exit();
}

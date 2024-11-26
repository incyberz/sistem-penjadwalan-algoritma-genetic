<?php
# ============================================================
# APLIKASI PENJADWALAN KULIAH
# ============================================================



# ============================================================
# INCLUDES
# ============================================================
include 'conn.php';
include 'config.php';
include 'includes/jadwal_styles.php';
include 'includes/arr_sql.php';

$includes = [
  'insho_style',
  'jsurl',
  'date_management',
  'echolog',
];

foreach ($includes as $file) {
  $path = $is_live ? "includes/$file.php" : "../includes/$file.php";
  $live = $is_live ? 'live' : 'local';
  if (!file_exists($path)) die("<hr>File [ $file ] is required for $live server.<hr>");
  include $path;
}



# ============================================================
# SELECT MATA KULIAH || CREATE TABLES
# ============================================================
try {
  foreach ($arr_sql as $key => $sql) {
    $sql = "SELECT * FROM tb_$key";
    $result = $conn->query($sql);

    if ($result === false) {
      throw new Exception("Tabel [ $key ] belum ada.");
      break;
    }
  }
} catch (Exception $e) {  // Tangkap dan tampilkan error
  echo $e->getMessage();
  echolog('MEMBUAT TABLES<hr>');

  # ============================================================
  # CREATE TABLES
  # ============================================================
  foreach ($arr_sql as $key => $sql) {
    echolog($sql);
    $conn->query($sql);
  }
}

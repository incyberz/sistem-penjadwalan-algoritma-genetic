<?php
session_start();
# ============================================================
# APLIKASI PENJADWALAN KULIAH
# ============================================================

# ============================================================
# DEBUGGING
# ============================================================
$_SESSION['jadwal_username'] = 'insho';


# ============================================================
# INCLUDES
# ============================================================
include 'conn.php';
include 'config.php';
include 'includes/jadwal_styles.php';
include 'includes/arr_sql.php';
include 'includes/arr_tb_master.php';

$includes = [
  'alert',
  'date_management',
  'echolog',
  'img_icon',
  'insho_style',
  'jsurl',
  'set_h2',
];

foreach ($includes as $file) {
  $path = $is_live ? "includes/$file.php" : "../includes/$file.php";
  $live = $is_live ? 'live' : 'local';
  if (!file_exists($path)) die("<hr>File [ $file ] is required for $live server.<hr>");
  include $path;
}



# ============================================================
# SELECT || CREATE TABLES
# ============================================================
try {
  foreach ($arr_sql as $key => $sql) {
    $sql = "SELECT 1 FROM tb_$key LIMIT 1";
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


# ============================================================
# LOGIN INFO
# ============================================================
$username = $_SESSION['jadwal_username'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Penjadwalan</title>
  <?php include 'includes/head_devs.php'; ?>
</head>

<body>
  <div class="container">
    <?php include 'pages/header.php'; ?>
    <main>
      <section>
        <?php include 'routing.php'; ?>
      </section>
    </main>
  </div>
</body>

<?php include $is_live ? 'includes/script_btn_aksi.php' : '../includes/script_btn_aksi.php'; ?>

</html>
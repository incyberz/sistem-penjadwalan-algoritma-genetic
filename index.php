<?php
session_start();
# ============================================================
# APLIKASI PENJADWALAN KULIAH
# ============================================================
$ta_aktif = 20241;
$is_ganjil = $ta_aktif % 2 == 0 ? 0 : 1;
$Ganjil = $is_ganjil  ? 'Ganjil' : 'Genap';
$min_ta = 2024;
$max_ta = 2030;
$min_ta_ganjil = $min_ta . '1';
$max_ta_genap = $max_ta . '2';
$tahun_ini = date('Y');
$tahun_ini_ganjil = $tahun_ini . '1';
$tahun_ini_genap = $tahun_ini . '2';


# ============================================================
# DEBUGGING
# ============================================================
$_SESSION['jadwal_username'] = 'yunita';


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
  'insho_styles',
  'jsurl',
  'set_h2',
  'udef',
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

  # ============================================================
  # CREATE TABLES
  # ============================================================
  echolog('<b class="darkblue f20">UPDATING TABLES</b><hr>');
  foreach ($arr_sql as $key => $sql) {
    echolog($sql);
    $conn->query($sql);
  }
  alert("Tables created successfully. | <a href='?'>Back to Home</a>", 'success');
  exit;
}


# ============================================================
# LOGIN INFO
# ============================================================
$username = $_SESSION['jadwal_username'] ?? '';
$user = [];
if ($username) include 'pages/user.php';


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
  <div class="container" style="position: relative;">
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
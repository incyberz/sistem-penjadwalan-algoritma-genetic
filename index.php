<?php
session_start();

# ============================================================
# DEBUGGING
# ============================================================
if (!isset($_SESSION['jadwal_username'])) $_SESSION['jadwal_username'] = 'yunita';
if (!isset($_SESSION['jadwal_ta_aktif'])) $_SESSION['jadwal_ta_aktif'] = 20242;

# ============================================================
# CONFIGIRATION FILE
# ============================================================
include 'config.php';



# ============================================================
# INCLUDES
# ============================================================
include 'conn.php';
include 'includes/jadwal_styles.php';
include 'includes/arr_sql.php';
include 'includes/arr_tb_master.php';

$includes = [
  'alert',
  'date_managements',
  'div_alert',
  'echolog',
  'hak_akses',
  'tanggal',
  'img_icon',
  'insho_styles',
  'jsurl',
  'key2kolom',
  'nama_hari',
  'nama_bulan',
  'set_h2',
  'udef',
];
foreach ($includes as $v) {
  $file = "includes/$v.php";
  if (file_exists($file)) {
    include $file;
  } elseif (file_exists("../$file")) {
    include "../$file"; // at htdocs or main server

  } else {
    die("<b style=color:red>File include [ $v ] diperlukan untuk menjalankan sistem.</b>");
  }
}


# ============================================================
# ICONS
# ============================================================
$img_next = img_icon('next');
$img_wa_disabled = img_icon('wa_disabled');
$img_unique = img_icon('unique');
$img_help = img_icon('help');
$null = '<i class="f12 abu">null</i>';
$unverified = '<i class="f12 red">unverified</i>';

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
  echo '<div style="color:red;padding:15px"><b>' . $e->getMessage() . '</b></div>';

  # ============================================================
  # CREATE TABLES
  # ============================================================
  echolog('<b class="darkblue f20 p2 mt4">PERFORM AUTOMATIC UPDATING TABLES</b><hr>');
  foreach ($arr_sql as $key => $sql) {

    $conn->query($sql);
  }
  alert("<div style='padding:15px;color:green'>Tables created successfully. | <a href='?'>Back to Home</a></div>", 'success');
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
  <?php
  include 'includes/head_devs.php';
  include 'global_gets_and_cookies.php';
  ?>
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
<script>
  $(function() {
    $('.ondev').click(function() {
      alert(`Fitur ini masih dalam tahap pengembangan. Terimakasih sudah mencoba!\n\n\ninfo lanjut: silahkan hubungi developer!`)
    })
  })
</script>
<?php
session_start();
// session_destroy();

# ============================================================
# SESSION
# ============================================================
$username = $_SESSION['jadwal_username'] ?? '';

# ============================================================
# DEFAULT NULL VALUES
# ============================================================
$user = [];
$dosen = [];
$mhs = [];
$role = null;
$pesan = null;
$id_dosen = null;
$id_mhs = null;
$styles_badge_prodi = null;

# ============================================================
# CONFIGIRATION FILE
# ============================================================
include 'config.php';
include 'conn.php';

# ============================================================
# GLOBAL VARIABLES
# ============================================================
$https_api_wa = 'https://api.whatsapp.com/send';
$now = date('Y-m-d H:i:s');
$text_wa_from = "\n\n```From: Smart Scheduling System \nat $now```";
$arr_hari = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$arr_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$dotdot = $is_live ? '.' : '..';
$arr = explode('?', $_SERVER['REQUEST_URI']);
$nama_server = "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]$arr[0]";
$default_fakultas = 'FKOM';
$get_fakultas = $_GET['fakultas'] ?? null;
$session_fakultas = $_SESSION['fakultas'] ?? $default_fakultas;
$fakultas = $get_fakultas ?? $session_fakultas;
$ta_sebelumnya = $ta_aktif - 10;


# ============================================================
# INCLUDES
# ============================================================
if ($username) {

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
    'read_only',
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
  $img_history = img_icon('history');
  $null = '<i class="f12 abu">null</i>';
  $unverified = '<i class="f12 red">unverified</i>';
  $verified = '<b class="f12 green">verified</b>';

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
  include 'pages/user.php';
  include 'pages/ta.php';
  // if ($role == 'AKD') include 'pages/rprodi.php';
  include 'pages/rprodi.php';

  # ============================================================
  # GLOBAL ICONS
  # ============================================================
  $img_drop = img_icon('drop');
} // end if $username


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Gamified SIAKAD</title>
  <?php
  include 'includes/head_devs.php';
  include 'global_gets_and_cookies.php';
  include 'includes/siakad_styles.php';
  include 'includes/siakad_functions.php';
  echo "<style>$styles_badge_prodi</style>";
  ?>
</head>

<body>
  <div class="container">
    <?php if ($username) include 'pages/header.php'; ?>
    <main>
      <section>
        <?php include 'routing.php'; ?>
      </section>
    </main>
  </div>
  <?php
  if ($role) {
    if ($role == 'AKD') {
      # ============================================================
      # SET SESSION IF NOT SET
      # ============================================================
      if ($get_id_prodi and $get_id_prodi != $session_id_prodi) $_SESSION['id_prodi'] = $get_id_prodi;
      if ($get_id_shift and $get_id_shift != $session_id_shift) $_SESSION['id_shift'] = $get_id_shift;
      if ($get_semester and $get_semester != $session_semester) $_SESSION['semester'] = $get_semester;
      if ($get_counter and $get_counter != $session_counter) $_SESSION['counter'] = $get_counter;
    }
    include 'pages/ontops.php';
  }
  ?>
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
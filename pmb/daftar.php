<?php
# ============================================================
# SET AS PETUGAS
# ============================================================
if ($username) include 'akun.php';
if (isset($akun) and $akun['role']) jsurl("./?$akun[role]");

# ============================================================
# NORMAL FLOW PENDAFTAR
# ============================================================
$bulan_skg = intval(date('m'));
$tahun_skg = $bulan_skg >= 9 ? date('Y') : date('Y') - 1;
$tahun_lalu = $tahun_skg - 1;
$ta_skg = "$tahun_skg-" . ($tahun_skg + 1);
$ta_lalu = "$tahun_lalu-$tahun_skg";

$post_nama = $_POST['nama'] ?? null;
$post_whatsapp = $_POST['whatsapp'] ?? null;
$post_username = $_POST['username'] ?? null;
$post_asal_sekolah = $_POST['asal_sekolah'] ?? null;
$post_tahun_lulus = $_POST['tahun_lulus'] ?? null;


$pesan = null;


if ($username) include 'pmb.php';
include 'daftar-styles.php';
include 'daftar-process.php';

# ============================================================
# MANAJEMEN STEP
# ============================================================
$get_step = $_GET['step'] ?? 1;
if ($get_step > 1 and !$username) {
  alert('Sesi login telah berakhir. <a href=?login_pmb>Silahkan relogin</a>!');
  exit;
  echo '<pre>';
  var_dump($_SESSION);
  echo '</pre>';
  die('Silahkan relogin!');
}
// $rstep = [
//   1 => 'Pendaftaran Akun',
//   2 => 'Verifikasi Akun',
//   3 => 'Melengkapi Biodata',
//   4 => 'Melengkapi Data Sekolah',
//   5 => 'Melengkapi Data Orangtua',
//   6 => 'Memilih Jurusan',
//   7 => 'Melengkapi Berkas',
//   8 => 'Tes PMB',
//   9 => 'Registrasi Ulang',
// ];

$rstep = [];
$s = "SELECT * FROM tb_status_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $rstep[$d['id']] = $d['status'];
}


?>



<div class="mx-auto" style="max-width: 500px;">
  <div class="hideit" id="get_step"><?= $get_step ?></div>
  <?php
  include 'daftar-stepper.php';
  if ($pesan == 'OK') {
    include 'daftar-set_password.php';
  } elseif ($pesan == 'passwordOK') {
    alert("
      Set password sukses.
      <hr>
      Silahkan login menggunakan password baru Anda. 
      <a class='mt-2 btn btn-primary w-100' href='?login_pmb&username=$_POST[btn_set_password]'>Login</a>
    ", 'success');
  } else if ($pesan) { // pesan error
    alert($pesan);
  } else {
    $nama_step = strtolower(str_replace(' ', '_', $rstep[$get_step]));
    include "daftar-step$get_step-$nama_step.php";
    echo "
      <hr>
      <div class='tengah mb4'>
        <a onclick='return confirm(`Yakin logout?`)' href='./?logout_pmb'>Logout</a>
      </div>
      <hr>
    ";
  }

  if ($get_step < count($rstep)) {
    $next_step = $get_step + 1;
    $nama_next_step = $rstep[$next_step];
    include 'daftar-form_next_step.php';
  }

  ?>
</div>
<?php include 'daftar-script.php'; ?>
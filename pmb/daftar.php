<?php
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

include 'akun.php';
include 'pmb.php';
include 'daftar-styles.php';
include 'daftar-process.php';

# ============================================================
# MANAJEMEN STEP
# ============================================================
$get_step = $_GET['step'] ?? 1;
$rstep = [
  1 => 'Pendaftaran Akun',
  2 => 'Verifikasi Akun',
  3 => 'Melengkapi Biodata',
  4 => 'Melengkapi Data Sekolah',
  5 => 'Melengkapi Data Orangtua',
  6 => 'Memilih Jurusan',
  7 => 'Melengkapi Berkas',
  8 => 'Tes PMB',
  9 => 'Registrasi Ulang',
];

?>



<div class="mx-auto" style="max-width: 500px;">
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
  }
  ?>


</div>
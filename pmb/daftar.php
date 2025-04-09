<?php
# ============================================================
# SET AS PETUGAS
# ============================================================
if ($username) {
  include 'akun.php';
  if ($akun['last_step']) include 'pmb.php';
  if ($pmb['tanggal_finish_registrasi']) jsurl('?dashboard_pendaftar');
}
if (isset($akun) and $akun['role']) jsurl("./?$akun[role]");

# ============================================================
# DEFAULT WHATSAPP PETUGAS
# ============================================================
$s = "SELECT username, nama, whatsapp FROM tb_akun WHERE role='petugas' AND is_petugas_default=1";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  alert('Belum ada Petugas PMB default.');
  exit;
} else {
  $d = mysqli_fetch_assoc($q);
  $nama_petugas = $d['nama'];
  $default_whatsapp_petugas = $d['whatsapp'];
  $whatsapp_petugas = $d['whatsapp'];
  $username_petugas = $d['username'];
}


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

    $isi_feedback = !$pmb['pernah_tes'] ? '' : "
      <div>
        <a target=_blank onclick='return confirm(`Isi Feedback PMB?`)' href='./?feedback'>Isi Feedback</a>
      </div>
    ";

    echo "
      <hr>
      <div class='d-flex flex-between mb4'>
        <div class=text-secondary>
          <i class=f12>Login as</i> <span class='text-black'>$nama_user</span>
        </div>
        $isi_feedback
        <div>
          <a onclick='return confirm(`Yakin logout?`)' href='./?logout_pmb'>Logout</a>
        </div>
      </div>
      <hr>
      <div style='height:100px'>&nbsp;</div>

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
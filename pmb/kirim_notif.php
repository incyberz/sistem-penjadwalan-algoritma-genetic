<?php
$get_username = $_GET['username'] ?? kosong('username');
$get_jenis = $_GET['jenis'] ?? kosong('jenis'); // jenis notif
$get_at = $_GET['at'] ?? kosong('at'); // at formulir | berkas | registrasi
$get_jenis_berkas = $_GET['jenis_berkas'] ?? kosong('jenis_berkas'); // jenis_berkas wajib
$get_last_terima_notif = $_GET['last_terima_notif'] ?? null;

set_h2("Kirim Notif");

$s = "SELECT nama, whatsapp FROM tb_akun WHERE username='$get_username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$pendaftar = mysqli_fetch_assoc($q);
$nama_pendaftar = ucwords(strtolower($pendaftar['nama']));

include 'kirim_notif-process.php';
require_once '../includes/eta.php';


$link_info = null;
$bg = 'secondary';
if ($get_jenis == 'peringatan') {
  $Jenis = 'Peringatan';
  $pesan = "Kami selaku Panitia PMB $tahun_pmb ingin menyampaikan bahwa Anda *belum melakukan upload berkas PMB dengan jenis berkas [ $get_jenis_berkas ]*. Mohon segera diupload demi kelancaran proses PMB. Klik pada link info untuk menuju ke laman Upload Berkas.";
  $link_info = "$nama_server?daftar&step=7";
  $bg = 'warning';
  // if ($get_at == 'berkas') {
  // } else {
  //   stop("Belum ada handler untuk jenis dokumen: [$get_at] pada notif [$get_jenis]");
  // }
} elseif ($get_jenis == 'selamat') {
  $Jenis = 'Selamat';
  if ($get_at == 'berkas') {
    if ($get_jenis_berkas == 'FORMULIR') {
      $pesan = "Terimakasih! Anda *telah melakukan Pembayaran Formulir PMB* dan telah upload Bukti Pembayaran-nya. Semoga Anda lulus pada Ujian Tes PMB!";
    } elseif ($get_jenis_berkas == 'REGISTRASI') {
      $pesan = "Anda *telah melakukan Pembayaran Registrasi Ulang PMB* dan telah upload Bukti Pembayaran-nya. Anda telah resmi menjadi Mahasiswa Baru $tahun_pmb. Jangan lupa untuk mengisi Feedback dan bergabung ke Grup MABA untuk menerima info-info selanjutnya.";
    } else {
      $pesan = "Terimakasih! Anda *telah melakukan upload berkas PMB dengan jenis berkas [ $get_jenis_berkas ]*. Silahkan cek status berkas lainnya pada link info.";
    }
    $link_info = "$nama_server?daftar&step=7";
    $bg = 'success';
  } else {
    stop("Belum ada handler untuk jenis dokumen: [$get_at] pada notif [$get_jenis]");
  }
} else {
  stop("Belum ada handler untuk jenis notif: $get_jenis");
}

$dari = 'petugas';
$nomor_tujuan = $pendaftar['whatsapp'];
$nama_penerima = $nama_pendaftar;
$eta = $get_last_terima_notif ? eta2($get_last_terima_notif) : '<i>belum pernah kirim</i>';
include 'link_kirim_whatsapp.php';

$text_preview = str_replace("\n", '<br>', $text_wa);
$text_preview = str_replace('```', '', $text_preview);



$kirim_notif_ke_petugas = "
  Last notif: $eta
  <hr>
  <div method=post class='card'>
    <div class='card-header bg-$bg tengah putih'>Notifikasi untuk $nama_penerima</div>
    <div class='card-body'>
      <div class='f14 abu miring mb1'>Preview Notif:</div>
      <div id='text_preview' class='form-control'>$text_preview</div>
      <form method=post>
        <input type=hidden value='$link_wa' name=link_wa>
        <button class='btn btn-$bg w-100 mt2' value='$get_username--$get_jenis_berkas' name=btn_kirim_notif>Kirim $Jenis</button>
      </form>
    </div>
  </div>

";

echo "
<div class='mt2 mb4 bg-info putih p2 br5'>
  $kirim_notif_ke_petugas
</div>
";

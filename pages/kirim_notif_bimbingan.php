<?php
$get_id_mhs = $_GET['id_mhs'] ?? kosong('id_mhs');
$get_jenis = $_GET['jenis'] ?? kosong('jenis'); // jenis notif
// $get_at = $_GET['at'] ?? kosong('at'); // at formulir | berkas | registrasi
// $get_jenis_berkas = $_GET['jenis_berkas'] ?? kosong('jenis_berkas'); // jenis_berkas wajib
// $get_last_terima_notif = $_GET['last_terima_notif'] ?? null;

set_h2("Kirim Notif");

$s = "SELECT 
a.*,
d.id as id_pembimbing,
(SELECT whatsapp FROM tb_user WHERE id=a.id_user) whatsapp 
FROM tb_mhs a 
JOIN tb_peserta_bimbingan b ON a.id=b.id_mhs 
JOIN tb_bimbingan c ON b.id_bimbingan=c.id
JOIN tb_dosen d ON c.id_dosen=d.id

WHERE a.id='$get_id_mhs'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$mhs = mysqli_fetch_assoc($q);
$nama_mhs = ucwords(strtolower($mhs['nama']));

$s = "SELECT * FROM tb_dosen a 
JOIN tb_bimbingan b ON a.id=b.id_dosen 
WHERE a.id=$mhs[id_pembimbing]";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$dosen = mysqli_fetch_assoc($q);

require_once 'includes/namaLengkapDosen.php';
$namaLengkapDosen = namaLengkapDosen($dosen['nama'], $dosen['gelar_depan'], $dosen['gelar_belakang']);


$bg = 'secondary';
if ($get_jenis == 'peringatan') {
  $Jenis = 'Peringatan';
  $pesan = "Pesan Peringatan ⚠️ bahwa kamu *belum melakukan upload berkas Bimbingan di minggu ini*. Mohon segera upload ya❗";
  $bg = 'warning';
} else {
  stop("Belum ada handler untuk jenis notif: $get_jenis");
}

// $mhs['whatsapp'] = '6287729007318'; // ZZZ

$dari = 'petugas';
$nomor_tujuan = $mhs['whatsapp'];
$penerima = $nama_mhs;
$pengirim = "$namaLengkapDosen (Pembimbing)";
$phone = $mhs['whatsapp'] ?? kosong('phone-whatsapp-mhs');
$link_info = "$nama_server?bimbingan";
$notif_title = 'NOTIF BIMBINGAN';
$custom_message = $dosen['custom_message'] ?? '-';
include 'includes/formKirimWhatsapp.php';
echo $formKirimWhatsapp;

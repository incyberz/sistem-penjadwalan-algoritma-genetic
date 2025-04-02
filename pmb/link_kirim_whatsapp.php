<?php
if (!isset($pesan) || !$pesan) die('undefined index [pesan] @link_kirim_whatsapp.php');
if (!isset($dari) || !$dari) die('undefined index [dari: petugas|pendaftar] @link_kirim_whatsapp.php');


// $link_info = "$nama_server?login&username=$get_username&password=$get_username";

$link_info = $link_info ?? '';
if ($link_info) $link_info = "\n\nLink Info: \n$link_info";


$waktu = 'pagi';
if (date('H') >= 9) {
  $waktu = 'siang';
} elseif (date('H') >= 15) {
  $waktu = 'sore';
} elseif (date('H') >= 18) {
  $waktu = 'malam';
}

if ($dari == 'petugas') {
  $nama_pengirim = $pengirim ?? 'Petugas PMB';
  if (!isset($nomor_tujuan) || !$nomor_tujuan) die('undefined index [nomor_tujuan] @link_kirim_whatsapp.php');
  if (!isset($nama_penerima) || !$nama_penerima) die('undefined index [nama_penerima] @link_kirim_whatsapp.php');
} else { // dari pendaftar ke Petugas
  $nomor_tujuan = $default_whatsapp_petugas ?? die('undefined index [default_whatsapp_petugas] @link_kirim_whatsapp.php');
  $nama_penerima = 'Petugas PMB';
  if (!isset($nama_pendaftar) || !$nama_pendaftar) die('undefined index [nama_pendaftar] @link_kirim_whatsapp.php');
  $nama_pengirim = $nama_pendaftar;
}

$text_wa = "```================================\nNOTIF PMB\n================================```\n\nSelamat $waktu $nama_penerima,\n\n$pesan\nTerimakasih.\n\nDari: $nama_pengirim\n$text_wa_footer$link_info";
$text_wa_encoded = urlencode($text_wa);

$link_wa = "$https_api_wa?phone=$nomor_tujuan&text=$text_wa_encoded";

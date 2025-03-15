<?php
if (!isset($pesan) || !$pesan) die('pesan tidak ada @hubungi.php');
// $link_login = "$nama_server?login&username=$get_username&password=$get_username";
$link_login = '';
$Petugas = 'Petugas Akademik';

$nim = $mhs['nim'] ?? '';
$nidn = $dosen['nidn'] ?? '';
$pelapor = "$user[nama] - $nim$nidn";
$phone = $phone ?? $petugas_default['whatsapp'];
$text_wa = "```================================\nNOTIF FROM MHS\n================================```\n\nSelamat $waktu $Petugas,\n\n$pesan. Terimakasih.\n\nDari:\n$pelapor\n\n$link_login$text_wa_from";
$text_wa = urlencode($text_wa);

$link_wa = "$https_api_wa?phone=$phone&text=$text_wa";
$hubungi = "<a target=_blank href='$link_wa'>$img_wa</a>";

<?php
if (isset($_POST['btnKirimNotif'])) {

  $txtAwal = str_replace('<br>', "%0a", $_POST['txtAwal']);
  $txtPenutup = str_replace('<br>', "%0a", $_POST['txtPenutup']);
  $custom_message = trim($_POST['custom_message']);

  $custom_message = strlen($custom_message) < 5 ? "%0a" : "%0a%0a$custom_message%0a";

  $text_wa = "$txtAwal$custom_message$txtPenutup";


  $link_wa = "$https_api_wa?phone=$nomor_tujuan&text=$text_wa";

  echo "<a class='btn btn-primary w-100' href='$link_wa' target=_blank>Kirim Notif</a>";

  exit;
}


$waktu = 'pagi';
if (date('H') >= 9) {
  $waktu = 'Siang';
} elseif (date('H') >= 15) {
  $waktu = 'Sore';
} elseif (date('H') >= 18) {
  $waktu = 'Malam';
}

if (!isset($pesan) || !$pesan) kosong('pesan');
if (!isset($dari) || !$dari) kosong('dari');
if (!isset($phone) || !$phone) kosong('phone');
if (!isset($pengirim) || !$pengirim) kosong('pengirim');
if (!isset($penerima) || !$penerima) kosong('penerima');
if (!isset($link_info) || !$link_info) kosong('link_info');
if (!isset($notif_title) || !$notif_title) kosong('notif_title');
if (!isset($custom_message) || !$custom_message) kosong('custom_message');

$link_info = urlencode($link_info);

# ============================================================
# TEXT WA + CUSTOM MESSAGE
# ============================================================
$custom_message = strlen($custom_message) > 5 ? $custom_message : '';

$txtAwal = "```===========================<br>$notif_title<br>===========================```<br><br>Selamat $waktu $penerima,<br><br>$pesan";
$txtPenutup = "Terimakasih.<br><br>Dari: $pengirim<br>$text_wa_footer<br><br>Link Info: <br>$link_info";

$formKirimWhatsapp = "
  <form method=post id=formKirimWhatsapp>
    <div method=post class='card'>
      <div class='card-header bg-$bg tengah putih'>Notifikasi untuk $penerima</div>
      <div class='card-body gradasi-kuning'>

        <div class='f14 abu miring mb1'>Preview Notif:</div>
        <div id='text_preview' class='form-control gradasi-toska'>$txtAwal</div>
        <input type=hidden value='$txtAwal' name=txtAwal>

        <div class='f14 abu miring mb1 mt4'>Custom Notif dari Anda (opsional):</div>
        <textarea name=custom_message class='form-control' rows=6>$custom_message</textarea>
        <div class='f12 abu miring mb1 mt1'>Custom Notif akan tersimpan untuk mhs bimbingan lainnya</div>

        <div class='f14 abu miring mb1 mt4'>Notif Penutup:</div>
        <div class='form-control gradasi-toska'>$txtPenutup</div>
        <input type=hidden value='$txtPenutup' name=txtPenutup>

        <button class='btn btn-$bg w-100 mt2' value='$get_id_mhs' name=btnKirimNotif>Kirim $Jenis</button>

      </div>
    </div>
  </form>
";

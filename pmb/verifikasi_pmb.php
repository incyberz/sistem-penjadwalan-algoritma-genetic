<?php
include '../includes/udef.php';

set_h2('Verifikasi Akun PMB');
petugas_only();

$get_username = $_GET['username'] ?? udef('username');
$get_nama = $_GET['nama'] ?? udef('nama');
$get_whatsapp = $_GET['whatsapp'] ?? udef('whatsapp');
$get_success = $_GET['success'] ?? null;


if ($get_success) {
  alert('Verifikasi Success...', 'success');

  $link_login = "$nama_server?login_pmb&username=$get_username";

  $text_asal = "```================================\nVERIFIKASI SUKSES\nfrom: Admin System\n================================```\n\nSelamat $get_nama,\n\nAkun Anda sudah kami verifikasi, silahkan melanjutkan Pendaftaran PMB menggunakan link berikut. Terimakasih.\n\nLink:\n$link_login\n\n```From: Smart PMB System \nat $now```";
  $preview = str_replace("\n\n", '<br>.<br>', $text_asal);
  $preview = str_replace("\n", '<br>', $preview);
  $preview = str_replace('```', '', $preview);

  $text_wa = urlencode($text_asal);

  $link_wa = "$https_api_wa?phone=$get_whatsapp&text=$text_wa";

  echo "
    <div class='card p-2'>
      <div class='card p-2 wa_preview' >$preview</div>
      <a target=_blank href='$link_wa' class='btn btn-primary w-100 mt-2'>Kirim Link ke User Baru</a>
    </div>
    <div class='tengah mt2'>
      <a href=?petugas>Home Petugas</a>
    </div>

  ";
} else {

  if (isset($_POST['btn_verifikasi_pmb'])) {
    $sql_update_wa = $_POST['sesuai'] ? '' : "whatsapp='$_POST[revisi_wa]',";

    $s = "UPDATE tb_akun SET 
      $sql_update_wa
      whatsapp_status=1,
      verif_date = CURRENT_TIMESTAMP,
      verif_by = '$username'
    WHERE whatsapp_status is null AND username='$get_username'";
    // die($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

    jsurl("?verifikasi_pmb&nama=$get_nama&username=$get_username&whatsapp=$get_whatsapp&success=1");
  }

  $get_whatsapp_striped =
    substr($get_whatsapp, 0, 5) . '-' .
    substr($get_whatsapp, 5, 4) . '-' .
    substr($get_whatsapp, 9, 4);

  // ZZZZ BUTTON TOLAK VERIFIKASI
  echo "
    <p>Verifikasi Nomor Whatsapp atas nama:</p>
    <ul>
      <li><b>nama:</b> $get_nama</li>
      <li><b>username:</b> $get_username</li>
      <li><b>whatsapp:</b> <span class='f30 darkred'>$get_whatsapp_striped</span> <b class=blue>(pastikan nomor-nya sama dengan yang masuk ke whatsapp Anda)</b></li>
    </ul>
    <form method=post>
      <div class='mt2 mb3'>
        <label class='hover d-block text-success'>
          <input type=radio name=sesuai class=radio value=1 id=radio--1 required> Nomor Whatsapp diatas sudah sesuai. 
        </label>
        <label class='hover d-block text-danger'>
          <input type=radio name=sesuai class=radio value=0 id=radio--0 required> Nomor Whatsapp berbeda. 
        </label>
        <div id=blok_revisi_wa class='hideit ml4'>
          <div class='f14 mb1 mt4'>Masukan Nomor Whatsapp yang masuk:</div>
          <input class='form-control f30 consolas' name=revisi_wa id=revisi_wa value='$get_whatsapp' minlength=11 maxlength=14>
        </div>
      </div>
      <button name=btn_verifikasi_pmb class='btn btn-primary w-100' value='$get_username'>Verifikasi Whatsapp</button>
    </form>
  ";
}
?>
<script>
  $(function() {

    $('.radio').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      if (id == '1') {
        $('#blok_revisi_wa').slideUp();
        $('#revisi_wa').prop('required', false);
      } else {
        $('#blok_revisi_wa').slideDown();
        $('#revisi_wa').prop('required', true);
      }
    })

    $("#revisi_wa").keyup(function() {
      let val = $(this).val();
      val = val.replace(/[^0-9]/g, ""); // Hanya angka
      if (val.startsWith("08")) {
        val = "628" + val.substring(2);
      } else if (!val.startsWith("628") && val.length >= 4) {
        val = "";
      }
      $(this).val(val);
    });
  });
</script>
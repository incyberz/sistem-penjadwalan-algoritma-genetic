<?php
require_once "$dotdot/includes/set_h2.php";
require_once "./includes/hak_akses.php";
set_h2('Verifikasi Whatsapp');
if (isset($role) and hak_akses('verifikasi_whatsapp', $role)) {

  $get_username = $_GET['username'] ?? udef('username');
  $get_nama = $_GET['nama'] ?? udef('nama');
  $get_role = $_GET['role'] ?? udef('role');
  $get_whatsapp = $_GET['whatsapp'] ?? udef('whatsapp');
  $get_success = $_GET['success'] ?? null;


  if ($get_success) {
    alert('Verifikasi Success...', 'success');

    $link_login = "$nama_server?login&username=$get_username&password=$get_username";

    $text_asal = "```================================\nVERIFIKASI SUKSES\nfrom: Admin System\n================================```\n\nSelamat $get_nama,\n\nAkun Anda sudah kami verifikasi, silahkan login menggunakan link berikut. Terimakasih.\n\nLink:\n$link_login$text_wa_from";
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

    ";
  } else {

    if (isset($_POST['btn_verifikasi_whatsapp'])) {
      $s = "UPDATE tb_user SET status=1 WHERE status is null AND username='$get_username'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

      # ============================================================
      # AUTO CREATE DATA MHS
      # ============================================================
      // $s = "SELECT id FROM tb_user WHERE username='$get_username'";
      // $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      // $d = mysqli_fetch_assoc($q);
      // $id_user = $d['id_user'];

      // $s = "INSERT INTO tb_mhs (

      // ) VALUES (
      // )";
      // $q = mysqli_query($cn, $s) or die(mysqli_error($cn));




      jsurl("?verifikasi_whatsapp&nama=$get_nama&username=$get_username&role=$get_role&whatsapp=$get_whatsapp&success=1");
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
        <li><b>role:</b> $get_role</li>
        <li><b>whatsapp:</b> <span class='f30 darkred'>$get_whatsapp_striped</span> <b class=blue>(pastikan nomor-nya sama dengan yang masuk ke whatsapp Anda)</b></li>
      </ul>
      <form method=post>
        <label class='hover mt2 mb2 d-block'>
          <input type=checkbox required> Nomor Whatsapp diatas sudah sesuai. 
        </label>
        <button name=btn_verifikasi_whatsapp class='btn btn-primary w-100' value='$get_username'>Verifikasi Whatsapp</button>
      </form>
    ";
  }
} else {
  echo "<div class='alert alert-danger'>Anda harus login dengan Role Akademik untuk melakukan verifikasi Nomor Whatsapp. | <a href='?logout'>Logout</a></div>";
}

<?php
require_once "$dotdot/includes/set_h2.php";
require_once "./includes/hak_akses.php";
set_h2('Verifikasi Whatsapp');
if (isset($role) and hak_akses('verifikasi_whatsapp', $role)) {

  if (isset($_POST['btn_verifikasi_whatsapp'])) {
    $s = "UPDATE tb_user SET "
  }

  $get_username = $_GET['username'] ?? udef('username');
  $get_nama = $_GET['nama'] ?? udef('nama');
  $get_role = $_GET['role'] ?? udef('role');
  $get_whatsapp = $_GET['whatsapp'] ?? udef('whatsapp');

  $get_whatsapp_striped =
    substr($get_whatsapp, 0, 5) . '-' .
    substr($get_whatsapp, 5, 4) . '-' .
    substr($get_whatsapp, 9, 4);

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
} else {
  echo "<div class='alert alert-danger'>Anda harus login dengan Role Akademik untuk melakukan verifikasi Nomor Whatsapp. | <a href='?logout'>Logout</a></div>";
}

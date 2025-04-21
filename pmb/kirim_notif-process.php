<?php
if (isset($_POST['btn_kirim_notif'])) {

  $t = explode('--', $_POST['btn_kirim_notif']);
  $s = "UPDATE tb_akun SET last_terima_notif = CURRENT_TIMESTAMP WHERE username='$t[0]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  sukses("Update last_terima_notif pada akun [$t[0]]");
  jsurl($_POST['link_wa'], 3000);
}

<?php
if (isset($_POST['btn_set_nonaktif'])) {
  $s = "UPDATE tb_akun SET active_status = $_POST[active_status] WHERE username = '$_POST[btn_set_nonaktif]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

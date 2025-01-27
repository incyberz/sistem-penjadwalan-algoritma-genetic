<?php
if (isset($_POST['btn_verifikasi_st'])) {
  $s = "UPDATE tb_st SET verif_by='$user[id]', verif_date = CURRENT_TIMESTAMP WHERE id='$_POST[btn_verifikasi_st]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}
if (isset($_POST['btn_rollback_verif'])) {
  $s = "UPDATE tb_st SET verif_by=NULL, verif_date = NULL WHERE id='$_POST[btn_rollback_verif]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

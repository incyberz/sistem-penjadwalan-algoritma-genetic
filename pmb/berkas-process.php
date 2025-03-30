<?php
if (isset($_POST['btn_submit_verif'])) {
  if ($_POST['status'] == -1) {
    $alasan_reject = "'$_POST[alasan_reject]'";
  } elseif ($_POST['status'] == 1) {
    $alasan_reject = 'NULL';
  } else {
    die('Invalid value status berkas.');
  }

  $s = "UPDATE tb_berkas SET status = $_POST[status], alasan_reject=$alasan_reject WHERE id=$_POST[btn_submit_verif] ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  jsurl();
} elseif (isset($_POST['btn_undo_verif'])) {
  $s = "UPDATE tb_berkas SET status = NULL, alasan_reject=NULL WHERE id=$_POST[btn_undo_verif] ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

<?php
if (isset($_POST['btn_kirim_feedback'])) {
  $jawabans = strip_tags(addslashes($_POST['jawabans']));
  $s = "INSERT INTO tb_feedback_respon (
    responden,
    tahun_pmb,
    jawabans
  ) VALUES (
    '$username',
    $tahun_pmb,
    '$jawabans'
  ) ON DUPLICATE KEY UPDATE 
    jawabans = '$jawabans',
    last_update = CURRENT_TIMESTAMP
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

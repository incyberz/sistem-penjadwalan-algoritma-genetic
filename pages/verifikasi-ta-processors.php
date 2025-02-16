<?php
if (isset($_POST['btn_save_TA'])) {

  $values = '';
  $last_jenis = '';
  foreach ($_POST['me'] as $no => $tgl_jenis) {
    $no_fill = sprintf('%02d', $no);
    $t = explode('--', $tgl_jenis);
    $id = 'TA-NO-Y-m-d-jenis';
    $id = "$ta_aktif-$no_fill-$t[0]";
    $koma = $values ? ',' : '';
    if ($t[1] != 'UAS' and $last_jenis == 'UAS') break;
    $values .= "
      $koma('$id',$ta_aktif,'$t[1]')
    ";
    $last_jenis = $t[1];
  }

  $s = "DELETE FROM tb_me WHERE id_ta=$ta_aktif";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "INSERT INTO tb_me (id,id_ta,jenis) VALUES $values";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));


  jsurl();
}
if (isset($_POST['btn_confirm_verifikasi_TA'])) {

  $s = "UPDATE tb_ta SET 
  verif_at = CURRENT_TIMESTAMP, 
  verif_by = $id_user, 
  status = 100 
  WHERE id=$ta_aktif";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  jsurl();
}
if (isset($_POST['btn_rollback_verifikasi_TA'])) {

  $s = "UPDATE tb_ta SET 
  verif_at = NULL, 
  verif_by = NULL, 
  status = NULL 
  WHERE id=$ta_aktif";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  jsurl();
}

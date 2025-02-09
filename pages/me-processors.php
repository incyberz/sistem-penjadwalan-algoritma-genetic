<?php
if (isset($_POST['btn_save_ME'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  $values = '';
  $last_jenis = '';
  foreach ($_POST['me'] as $no => $tgl_jenis) {
    $no_fill = sprintf('%02d', $no);
    $t = explode('--', $tgl_jenis);
    $id = 'TA-NO-Y-m-d-jenis';
    $id = "$ta_aktif-$no_fill-$t[0]";
    $koma = $values ? ',' : '';
    $values .= "$koma('$id',$ta_aktif,'$t[1]')";
    if ($t[1] != 'UAS' and $last_jenis == 'UAS') break;
    $last_jenis = $t[1];
  }

  $s = "DELETE FROM tb_me WHERE id_ta=$ta_aktif";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "INSERT INTO tb_me (id,id_ta,jenis) VALUES $values";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));


  jsurl();
  exit;
}

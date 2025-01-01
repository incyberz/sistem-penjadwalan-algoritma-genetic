<?php
if (isset($_POST['btn_create_st'])) {
  $id_st = "$_POST[id_ta]-$_POST[id_dosen]";
  $s = "INSERT INTO tb_st (
    id,
    id_dosen,
    id_ta,
    tanggal,
    id_petugas
  ) VALUES (
    '$id_st',
    $_POST[id_dosen],
    $_POST[id_ta],
    CURRENT_TIMESTAMP,
    $id_petugas
  ) ON DUPLICATE KEY UPDATE id_petugas=$id_petugas";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  foreach ($_POST['id_mk'] as $id_mk => $value) {
    $id = "$id_st-$id_mk";
    $s = "INSERT INTO tb_st_mk (
      id,
      id_st,
      id_mk
    ) VALUES (
      '$id',
      '$id_st',
      $id_mk
    ) ON DUPLICATE KEY UPDATE id_mk=$id_mk, id_st='$id_st'";
    echolog($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }
  jsurl("?st_ajar&id_kurikulum=$id_kurikulum&aksi=manage&id_st=$id_st");
}

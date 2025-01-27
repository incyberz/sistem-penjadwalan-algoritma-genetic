<?php
if (isset($_POST['btn_create_st'])) {
  $id_st = "$ta_aktif-$id_dosen";
  $s = "INSERT INTO tb_st (
    id,
    id_dosen,
    id_ta,
    tanggal,
    id_user
  ) VALUES (
    '$id_st',
    $id_dosen,
    $_POST[id_ta],
    CURRENT_TIMESTAMP,
    $id_user
  ) ON DUPLICATE KEY UPDATE id_user=$id_user";
  echolog('Processing...');
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  if (isset($_POST['id_kumk'])) {
    foreach ($_POST['id_kumk'] as $id_kumk => $value) {
      $id = "$id_st-$id_kumk";
      $s = "INSERT INTO tb_st_detail (
        id,
        id_st,
        id_kumk
      ) VALUES (
        '$id',
        '$id_st',
        '$id_kumk'
      ) ON DUPLICATE KEY UPDATE id_kumk='$id_kumk', id_st='$id_st'";

      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  }
  jsurl("?st&aksi=manage&id_st=$id_st");
}

<?php
if (isset($_POST['btn_assign_bimbingan'])) {

  if (isset($_POST['rmhs'])) {
    foreach ($_POST['rmhs'] as $id_mhs) {
      // insert into tb_peserta_bimbingan
      $s = "INSERT INTO tb_peserta_bimbingan (
        id,
        id_bimbingan,
        id_mhs
      )VALUES (
        '$_POST[id_bimbingan]-$id_mhs',
        '$_POST[id_bimbingan]',
        $id_mhs
      ) ON DUPLICATE KEY UPDATE
        assign_at=NOW()
      ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
    jsurl('?daftar-peserta-bimbingan');
  }
}

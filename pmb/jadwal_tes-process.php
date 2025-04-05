<?php
if (isset($_POST['btn_set_jadwal'])) {

  if ($_POST['waktu_sama']) {
    $s = "SELECT id FROM tb_tes_pmb WHERE tahun_pmb = $tahun_pmb";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $rid_tes = [];
    while ($d = mysqli_fetch_assoc($q)) {
      array_push($rid_tes, $d['id']);
    }
  } else {
    $rid_tes = [$_POST['btn_set_jadwal']];
  }

  foreach ($rid_tes as $id_tes) {
    if ($_POST['id_jadwal_tes']) {
      $s = "UPDATE tb_jadwal_tes SET 
        awal = '$_POST[tanggal] $_POST[jam]',
        durasi = $_POST[durasi],
        lokasi = '$_POST[lokasi]'
      WHERE id = $_POST[id_jadwal_tes]";
    } else {
      $s = "INSERT INTO tb_jadwal_tes (
        id_tes,
        awal,
        durasi,
        lokasi
      ) VALUES (
        $id_tes,
        '$_POST[tanggal] $_POST[jam]',
        $_POST[durasi],
        '$_POST[lokasi]'
      )";
    }
    echo $s;
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }
  jsurl();
}

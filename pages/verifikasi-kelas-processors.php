<?php
if ($_POST) {

  if (isset($_POST['btn_drop'])) {
    $s = "DELETE FROM tb_peserta_kelas WHERE id_mhs = $_POST[btn_drop] AND id_kelas=$kelas[id]";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert("Dropping mhs sukses.", 'success');
    jsurl("?verifikasi&tb=kelas&id=$get_id&last_aksi=manage_peserta", 500);
  }

  if (isset($_POST['btn_add'])) {
    $nama_mhs_baru = trim(strtoupper(mysqli_real_escape_string($cn, $_POST['nama_mhs_baru'])));
    $s = "INSERT INTO tb_mhs (id_prodi,nama,angkatan) VALUES ($kelas[id_prodi], '$nama_mhs_baru',$angkatan)";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

    alert("Insert Mhs Baru sukses.", 'success');
    jsurl("?verifikasi&tb=kelas&id=$get_id&last_aksi=manage_peserta", 1000);
  }

  if (isset($_POST['btn_assign'])) {

    foreach ($_POST['id_mhs'] as $id_mhs => $value) {
      $new_id = "$kelas[id]-$id_mhs";
      $s = "INSERT INTO tb_peserta_kelas (
        id,
        id_kelas,
        id_mhs,
        assign_by
      ) VALUES (
        '$new_id',
        $kelas[id],
        $id_mhs,
        $petugas[id]
      ) ON DUPLICATE KEY UPDATE 
        assign_by = $petugas[id]
      ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      alert("Assign Mhs sukses.", 'success');
    }

    jsurl('', 1000);
  }

  $s = "DESCRIBE tb_kelas";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $fields = [];
  while ($d = mysqli_fetch_assoc($q)) {
    array_push($fields, $d['Field']);
  }

  foreach ($_POST as $key => $value) {
    if (in_array($key, $fields)) {
      $s = "UPDATE tb_kelas SET $key='$value' WHERE id='$id'";
      echolog($s);
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      alert("Update field [$key] sukses.", 'success');
    } elseif ($key == 'ZZZ') {
      // $s = "UPDATE tb_kelas SET $key='$value' WHERE id='$id'";
      // // $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      // alert("Update data kosma sukses.", 'success');
      // // jsurl('', 1000);
    } else {
      die("Belum ada handler untuk field [$key]");
    }
  }
  jsurl("?verifikasi&tb=kelas&id=$get_id");
}

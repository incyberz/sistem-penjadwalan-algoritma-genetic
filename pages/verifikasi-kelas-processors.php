<?php
if ($_POST) {

  if (isset($_POST['btn_approve'])) {
    $s = "UPDATE tb_kelas SET status=100 WHERE id=$get_id";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert("Approve sukses.", 'success');
    jsurl();
  }

  if (isset($_POST['btn_drop'])) {
    $s = "DELETE FROM tb_peserta_kelas WHERE id_mhs = $_POST[btn_drop] AND id_kelas=$kelas[id]";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert("Dropping mhs sukses.", 'success');
    jsurl("?verifikasi&tb=kelas&id=$get_id&last_aksi=manage_peserta");
  }

  if (isset($_POST['btn_add'])) {
    $nama_mhs_baru = trim(strtoupper(mysqli_real_escape_string($cn, $_POST['nama_mhs_baru'])));
    $s = "INSERT INTO tb_mhs (
      id_prodi,
      id_shift,
      nama,
      angkatan
    ) VALUES (
      $kelas[id_prodi],
      '$kelas[id_shift]',
      '$nama_mhs_baru',
      $angkatan
    )";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

    alert("Insert Mhs Baru sukses.", 'success');
    jsurl("?verifikasi&tb=kelas&id=$get_id&last_aksi=manage_peserta");
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
        $user[id]
      ) ON DUPLICATE KEY UPDATE 
        assign_by = $user[id]
      ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      alert("Assign Mhs sukses.", 'success');
    }

    jsurl();
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
    } elseif ($key == 'whatsapp_kosma') {
      // cek jika kosma belum punya data user
      $id_kosma = $_POST['id_kosma'] ?? null;
      if ($id_kosma) {
        $s = "SELECT a.id_user FROM tb_mhs a 
        JOIN tb_user b ON a.id_user=b.id
        WHERE a.id=$id_kosma";
        echolog($s);
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        if (!mysqli_num_rows($q)) {
          # ============================================================
          # AUTO CREATE 
          # ============================================================
          $s = "SELECT MAX(id) as max_id FROM tb_user";
          $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
          $d = mysqli_fetch_assoc($q);
          $max_id = $d['max_id'];
          $id_user = $max_id + 1;

          $s = "INSERT INTO tb_user (
            id,
            username,
            whatsapp,
            role
          ) VALUES (
            $id_user,
            'kosma$get_id',
            '$value',
            'MHS'
          )";
          echolog($s);
          $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
          alert("Insert data user baru sukses. id: $id_user", 'success');
        } else {
          $d = mysqli_fetch_assoc($q);
          $id_user = $d['id_user'];
          $s = "UPDATE tb_user SET whatsapp='$value' WHERE id=$id_user";
          $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
          alert("Update whatsapp kosma sukses.", 'success');
        }
        $s = "UPDATE tb_mhs SET id_user='$id_user' WHERE id=$id_kosma";
        echolog($s);
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        alert(
          "Update field id_user kosma sukses.",
          'success'
        );
      } else {
        die('Data POST id_kosma is null.');
      }
    } else {
      die("<b class=red>Belum ada handler untuk field [$key]</b>");
    }
  }
  jsurl("?verifikasi&tb=kelas&id=$get_id");
}

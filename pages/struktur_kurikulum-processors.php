<?php
if (isset($_POST['btn_assign_mk'])) {
  $t = explode('-', $_POST['btn_assign_mk']);
  $id_prodi = $t[0] ?? udef('value[0]');
  $id_kurikulum = $t[1] ?? udef('value[1]');
  $semester = $t[2] ?? udef('value[2]');
  $id_mks = $t[3] ?? udef('value[3]');

  $t = explode(';', $id_mks);
  foreach ($t as $id_mk) {
    $id = "$id_kurikulum-$id_mk";
    if ($id_mk) {
      $s = "INSERT INTO tb_kumk (
      id, 
      id_kurikulum, 
      semester, 
      id_mk
      ) VALUES (
      '$id', 
      '$id_kurikulum', 
      '$semester', 
      '$id_mk'
      )";
      mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  }
}
if (isset($_POST['btn_drop_mk'])) {
  $s = "DELETE FROM tb_kumk WHERE id='$_POST[btn_drop_mk]'";
  mysqli_query($cn, $s) or die(mysqli_error($cn));
}
if (isset($_POST['btn_hapus_mk'])) {
  $s = "DELETE FROM tb_mk WHERE id='$_POST[btn_hapus_mk]'";
  mysqli_query($cn, $s) or die(mysqli_error($cn));
}
if (isset($_POST['btn_tambah_mk'])) {
  $t = explode('-', $_POST['btn_tambah_mk']);
  $id_prodi = $t[0];
  $id_kurikulum = $t[1];
  $semester = $t[2];

  $s = "SELECT (MAX(no) + 1) as new_no  FROM tb_mk WHERE id_prodi='$id_prodi' AND semester='$semester'";
  if ($_POST['is_mkdu']) $s = "SELECT (MAX(no) + 1) as new_no  FROM tb_mk WHERE id_prodi is null";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $new_no = mysqli_fetch_assoc($q)['new_no'];
  $new_no = $new_no ?? 1;
  $new_no = sprintf('%03d', $new_no);

  $time = date('ymdHis');
  if ($_POST['is_mkdu']) {
    $kode = "MKDU-$new_no";
    $id_prodi = 'NULL';
  } else {
    $kode = "MK-$id_prodi-$semester-$new_no";
  }

  $s = "INSERT INTO tb_mk (
    id_prodi,
    kode,
    nama,
    sks,
    semester,
    no
  ) VALUES (
    $id_prodi,
    '$kode',
    '$_POST[nama_mk]',
    '$_POST[sks]',
    '$semester',
    $new_no
  )";

  mysqli_query($cn, $s) or die(mysqli_error($cn));
}

if ($_POST) {
  $view_semester = $semester ?? $view_semester; // replace view_semester with $semester if $semester is set
  jsurl("?struktur_kurikulum&id_prodi=$id_prodi&mode=$mode&view_semester=$view_semester");
}

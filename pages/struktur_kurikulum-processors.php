<?php
if (isset($_POST['btn_assign_mk'])) {
  $t = explode('-', $_POST['btn_assign_mk']);
  $part_id_prodi = $t[0] ?? udef('value[0]');
  $id_kurikulum = $t[1] ?? udef('value[1]');
  $part_semester = $t[2] ?? udef('value[2]');
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
      '$part_semester', 
      '$id_mk'
      )";
      mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  }
}
if (isset($_POST['btn_assign_mk_sebelumnya'])) {
  $t = explode('-', $_POST['btn_assign_mk_sebelumnya']);
  $id_prodi = $t[0] ?? udef('value[0]');
  $id_kurikulum = $t[1] ?? udef('value[1]');
  $semester = $t[2] ?? udef('value[2]');
  // $id_prodi-$id_kurikulum-$semester

  // get id_mk yang di-assign di TA sebelumnya
  $s = "SELECT d.id as id_mk 
  FROM tb_kumk a 
  JOIN tb_kurikulum b ON a.id_kurikulum=b.id 
  JOIN tb_st_detail c ON a.id=c.id_kumk  
  JOIN tb_mk d ON a.id_mk=d.id  
  WHERE 1  
  AND b.id_ta = $ta_sebelumnya
  AND b.id_prodi = $id_prodi
  AND c.id_shift = '$id_shift'
  AND a.semester = $semester";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $s = "INSERT INTO tb_kumk (
    id, -- id_kumk
    id_kurikulum, 
    semester, 
    id_mk
    ) VALUES (
    '$id_kurikulum-$d[id_mk]', -- id_kumk
    $id_kurikulum, 
    $semester, 
    $d[id_mk]
    )";
    mysqli_query($cn, $s) or die(mysqli_error($cn));
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
  $part_id_prodi = $t[0];
  $id_kurikulum = $t[1];
  $part_semester = $t[2];

  $s = "SELECT (MAX(no) + 1) as new_no  FROM tb_mk WHERE id_prodi='$part_id_prodi' AND semester='$part_semester'";
  if ($_POST['is_mkdu']) $s = "SELECT (MAX(no) + 1) as new_no  FROM tb_mk WHERE id_prodi is null";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $new_no = mysqli_fetch_assoc($q)['new_no'];
  $new_no = $new_no ?? 1;
  $new_no_zerofill = sprintf('%03d', $new_no);

  $time = date('ymdHis');
  if ($_POST['is_mkdu']) {
    $kode = "MKDU-$new_no_zerofill";
    $part_id_prodi = 'NULL';
  } else {
    $kode = "MK-$part_id_prodi-$part_semester-$new_no_zerofill";
  }

  $_POST['nama_mk'] = preg_replace('/[^A-Z0-9\s\(\)]/', '', trim(strtoupper($_POST['nama_mk'])));

  $s = "INSERT INTO tb_mk (
    id_prodi,
    kode,
    nama,
    sks,
    semester,
    no
  ) VALUES (
    $part_id_prodi,
    '$kode',
    '$_POST[nama_mk]',
    '$_POST[sks]',
    '$part_semester',
    $new_no
  )";

  mysqli_query($cn, $s) or die(mysqli_error($cn));
}

if (isset($_POST['btn_update_sks'])) {
  $s = "UPDATE tb_mk SET sks=$_POST[sks] WHERE id=$_POST[btn_update_sks]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}

if (isset($_POST['btn_update_nama_mk'])) {
  $nama_mk = preg_replace('/[^A-Z0-9\s\(\)]/', '', trim(strtoupper($_POST['nama_mk'])));
  $s = "UPDATE tb_mk SET nama='$nama_mk' WHERE id=$_POST[btn_update_nama_mk]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}

if ($_POST) {
  if (!$id_prodi) {
    echo '<pre>';
    var_dump($id_prodi);
    echo '<b style=color:red>DEBUGING: echopreExit</b></pre>';
    exit;
  }
  jsurl("?struktur_kurikulum&id_prodi=$id_prodi&mode=$mode&semester=$get_semester&id_shift=$id_shift", 300);
}

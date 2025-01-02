<?php
if (isset($_POST['btn_book'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';


  $id_ruang = $_POST['id_ruang'];

  $tmp = explode('__', $_POST['btn_book']);
  // $id_kelas = $tmp[0]; // sudah ada di id_st_mk_kelas
  $weekday = $tmp[0];
  $id_sesi = $tmp[1];

  $tmp = explode('__', $_POST['id_radio']);
  $id_st_mk_kelas = $tmp[0];
  $sks = $tmp[1];

  $jam_mulai = '7:30';
  $jam_selesai = '10:00';

  # ============================================================
  # VALIDASI BATAS ID_SESI BY SKS
  # ============================================================
  // ZZZ SKIPPED

  # ============================================================
  # INSERT | UPDATE JADWAL
  # ============================================================
  $s = "INSERT INTO tb_jadwal (
    id,
    id_ruang,
    id_sesi_at_book,
    weekday,
    jam_mulai,
    jam_selesai,
    assign_by
  ) VALUES (
    '$id_st_mk_kelas',
    '$id_ruang',
    '$id_sesi',
    '$weekday',
    '$jam_mulai',
    '$jam_selesai',
    '$petugas[id]'
  ) ON DUPLICATE KEY UPDATE assign_date = CURRENT_TIMESTAMP 
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));



  # ============================================================
  # INSERTS MULTIPLE PEMAKAIAN RUANG SESUAI JUMLAH SKS MK
  # ============================================================
  for ($i = $id_sesi; $i <= $id_sesi + $sks; $i++) {
    $id = "id_st_mk_kelas-$i";
    $s = "INSERT INTO tb_pemakaian_ruang (
      id,
      id_st_mk_kelas,
      id_ruang,
    ) VALUES (
      '$id',
      '$id_st_mk_kelas',
      '$id_ruang'
    ) ON DUPLICATE KEY UPDATE id_ruang = $id_ruang 
    ";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }




  // jsurl();
  exit;
}

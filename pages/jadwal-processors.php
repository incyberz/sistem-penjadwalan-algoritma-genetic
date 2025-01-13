<?php
if (isset($_POST['btn_delete_jadwal'])) {
  $id_st_mk_kelas = $_POST['btn_delete_jadwal'];

  $s = "DELETE FROM tb_pemakaian_ruang WHERE id_st_mk_kelas='$id_st_mk_kelas'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "DELETE FROM tb_jadwal WHERE id='$id_st_mk_kelas'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  jsurl();
}
if (isset($_POST['btn_book'])) {
  $id_ruang = $_POST['id_ruang'];

  $tmp = explode('__', $_POST['btn_book']);
  // $id_kelas = $tmp[0]; // sudah ada di id_st_mk_kelas
  $weekday = $tmp[0];
  $id_sesi = $tmp[1];

  $tmp = explode('__', $_POST['id_radio']);
  $id_st_mk_kelas = $tmp[0];
  $sks = $tmp[1];

  $tmp = explode('-', $id_st_mk_kelas);
  $id_dosen = $tmp[1];
  $id_mk = $tmp[2];
  $id_kelas = $tmp[3];




  # ============================================================
  # VALIDASI SATU DOSEN TIDAK BISA DI DUA RUANGAN
  # ============================================================
  $s = "SELECT 
  a.id 
  FROM tb_jadwal a 
  WHERE a.id_sesi_at_book='$id_sesi' 
  AND weekday = '$weekday'
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $id_dosens = [];
  $id_jadwal = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $tmp = explode('-', $d['id']);
    array_push($id_dosens, $tmp[1]);
    $id_jadwal = $id_jadwal ? $id_jadwal : $d['id'];
  }

  if (in_array($id_dosen, $id_dosens)) {
    # ============================================================
    # GET DATA KONFLIK | UNIQUE DOSEN
    # ============================================================
    $unik_dosen = "$ta_aktif-$id_dosen-$weekday-$id_sesi";
    $d = [];
    include 'show_konflik_dosen.php';
    // exit;
  } else {
    # ============================================================
    # PROSES NORMAL
    # ============================================================

    # ============================================================
    # INSERTS MULTIPLE PEMAKAIAN RUANG SESUAI JUMLAH SKS MK
    # ============================================================
    echolog('perform multiple insert pemakaian ruang sesuai SKS-MK...');
    $last_id_sesi = '';
    $ada_error = 0;
    $j = 0;
    for ($i = $id_sesi; $i < $id_sesi + $sks; $i++) {
      $j++;
      if ($i == 6) $i = 7; // shalat dzuhur
      if ($i == 13) $i = 14; // shalat magrib
      echolog("inserting SKS ke : $j...");
      $id = "$id_st_mk_kelas-$i";

      $unik_dosen = 'TA-Dosen-W-S';
      $unik_dosen = "$ta_aktif-$id_dosen-$weekday-$i";

      $s = "SELECT 1 FROM tb_pemakaian_ruang WHERE unik_dosen='$unik_dosen'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (mysqli_num_rows($q)) {
        include 'show_konflik_dosen.php';
        $ada_error = 1;
        // exit;
        break;
      }
    }

    if (!$ada_error) {
      for ($i = $id_sesi; $i < $id_sesi + $sks; $i++) {
        if ($i == 6) $i = 7; // shalat dzuhur
        if ($i == 13) $i = 14; // shalat magrib
        echolog("id_sesi terpakai: $i");
        $id = "$id_st_mk_kelas-$i";

        $unik_dosen = 'TA-Dosen-W-S';
        $unik_dosen = "$ta_aktif-$id_dosen-$weekday-$i";

        $s = "INSERT INTO tb_pemakaian_ruang (
          id,
          id_st_mk_kelas,
          id_sesi,
          id_ruang,
          unik_dosen
        ) VALUES (
          '$id',
          '$id_st_mk_kelas',
          '$i',
          '$id_ruang',
          '$unik_dosen'
        ) -- ON DUPLICATE KEY UPDATE id_sesi = $i 
        ";

        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        $last_id_sesi = $i;
      }

      # ============================================================
      # JAM MULAI DAN JAM SELESAI
      # ============================================================
      $s = "SELECT 
      (SELECT awal FROM tb_sesi WHERE id=$id_sesi) jam_mulai,
      (SELECT akhir FROM tb_sesi WHERE id=$last_id_sesi) jam_selesai
      ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      $d = mysqli_fetch_assoc($q);
      $jam_mulai = $d['jam_mulai'];
      $jam_selesai = $d['jam_selesai'];


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
      echolog('inserting jadwal...');

      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

      jsurl();
      exit;
    } // tidak ada error saat insert pemakaian ruang
  }
}

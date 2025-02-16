<?php
if (isset($_POST['btn_delete_jadwal'])) {
  $id_st_detail = $_POST['btn_delete_jadwal'];

  $s = "DELETE FROM tb_pemakaian_ruang WHERE id_st_detail='$id_st_detail'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "DELETE FROM tb_jadwal WHERE id='$id_st_detail'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}
if (isset($_POST['btn_locked_jadwal'])) {
  $id_st_detail = $_POST['btn_locked_jadwal'];

  $s = "SELECT is_locked FROM tb_jadwal WHERE id='$id_st_detail'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $is_locked = $d['is_locked'] ? 'NULL' : 1;

  $s = "UPDATE tb_jadwal SET is_locked=$is_locked WHERE id='$id_st_detail'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}
if (isset($_POST['btn_book'])) {
  $id_ruang = $_POST['id_ruang'];

  $tmp = explode('__', $_POST['btn_book']);
  // $id_kelas = $tmp[0]; // sudah ada di id_st_detail
  $weekday = $tmp[0];
  $id_sesi = $tmp[1];

  $tmp = explode('__', $_POST['id_radio']);
  $id_st_detail = $tmp[0];
  $sks = $tmp[1];

  // TA-DS-KU-MK-KLS-SHIFT 	
  // 0  1  2  3  4   5
  $tmp = explode('-', $id_st_detail);
  $id_dosen = $tmp[1];
  $id_kurikulum = $tmp[2];
  $id_mk = $tmp[3];
  $id_kelas = $tmp[4];
  $id_shift = $tmp[5];
  $id_kumk = "$id_kurikulum-$id_mk";
  $get_id_kelas = $id_kelas; // refokus ke kelas ini

  // echolog("
  // <br>weekday:$weekday 
  // <br>id_sesi:$id_sesi 
  // <br>id_st_detail:$id_st_detail 
  // <br>sks:$sks 
  // <br>id_dosen:$id_dosen 
  // <br>id_mk:$id_mk 
  // <br>id_kelas:$id_kelas
  // <br>id_kurikulum:$id_kurikulum
  // <br>id_kumk:$id_kumk
  // <br>id_shift:$id_shift
  // ");



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
    echo '<pre>';
    var_dump($unik_dosen);
    echo '</pre>';
    $d = []; // inisialisasi
    include 'show_konflik_dosen.php';
    // exit;
  } else {
    # ============================================================
    # PROSES NORMAL
    # ============================================================

    # ============================================================
    # INSERTS MULTIPLE PEMAKAIAN RUANG SESUAI JUMLAH SKS MK
    # ============================================================
    // echolog('perform multiple insert pemakaian ruang sesuai SKS-MK...');
    $last_id_sesi = '';
    $ada_error = 0;
    $j = 0;
    for ($cid_sesi = $id_sesi; $cid_sesi < $id_sesi + $sks; $cid_sesi++) {
      $j++;
      if ($cid_sesi == 6) $cid_sesi = 7; // shalat dzuhur
      if ($cid_sesi == 13) $cid_sesi = 14; // shalat magrib
      // echolog("inserting SKS ke : $j dengan current_id_sesi: $cid_sesi");

      # ============================================================
      # PENGECEKAN KETERSEDIAAN SESI DI KELAS INI
      # ============================================================
      $s = "SELECT id_st_detail FROM tb_pemakaian_ruang WHERE unik_dosen LIKE 'TA-%-W-S' AND id_st_detail LIKE 'TA-DS-KU-MK-KLS-SHIFT'";
      $s = "SELECT id_st_detail FROM tb_pemakaian_ruang WHERE unik_dosen LIKE 'TA-%-W-S' AND id_st_detail LIKE 'TA-%-KLS-SHIFT'";
      $s = "SELECT id_st_detail FROM tb_pemakaian_ruang 
      WHERE unik_dosen LIKE '$ta_aktif-%-$weekday-$cid_sesi' 
      -- AND id_st_detail LIKE 'TA-%-KLS-SHIFT'
      AND id_st_detail LIKE '$ta_aktif-%-$id_kelas-$id_shift'
      ";
      // echolog("<b class=blue>Pengecekan Ketersediaan Sesi</b>, $s");
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (mysqli_num_rows($q)) die(alert("
        <b class=red>Sesi tidak mencukupi untuk $sks SKS</b>
        <hr>
        Sesi $cid_sesi pada hari dan kelas tersebut sudah dipakai oleh dosen lain. Silahkan pakai sesi atau hari lain.
        <span class='consolas abu'>
        <br>- weekday: $weekday
        <br>- cid_sesi: $cid_sesi
        <br>- ta_aktif: $ta_aktif
        </span>
        <hr>
        proses dibatalkan... <span class='btn btn-primary btn-sm' onclick='location.replace(`?jadwal&id_shift=$id_shift&id_kelas=$get_id_kelas`)'>OK</span>

      "));




      # ============================================================
      # PENGECEKAN KONFLIK DOSEN
      # ============================================================
      $unik_dosen = 'TA-Dosen-W-cid_sesi';
      $unik_dosen = "$ta_aktif-$id_dosen-$weekday-$cid_sesi";

      $s = "SELECT id_st_detail FROM tb_pemakaian_ruang WHERE unik_dosen='$unik_dosen'";
      // echolog("Pengecekan Konflik Dosen, $s");
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (mysqli_num_rows($q)) {
        $d = mysqli_fetch_assoc($q);
        if ($id_st_detail != $d['id_st_detail']) {
          echolog("
            <br>unik_dosen:TA-Dosen-W-cid_sesi
            <br>unik_dosen:$unik_dosen
            <br>id_st_detail:$id_st_detail || $d[id_st_detail]
          ");

          include 'show_konflik_dosen.php';
          $ada_error = 1;
          // exit;
          break;
        }
      }
    }

    # ============================================================
    # JIKA TIDAK ADA KONFLIK DOSEN & RUANG > INSERT
    # ============================================================
    if (!$ada_error) {
      for ($cid_sesi = $id_sesi; $cid_sesi < $id_sesi + $sks; $cid_sesi++) {
        if ($cid_sesi == 6) $cid_sesi = 7; // shalat dzuhur
        if ($cid_sesi == 13) $cid_sesi = 14; // shalat magrib
        // echolog("id_sesi terpakai: $cid_sesi");
        $id = "$id_st_detail-$cid_sesi"; // id_pemakaian_ruang

        $unik_dosen = 'TA-Dosen-W-S';
        $unik_dosen = "$ta_aktif-$id_dosen-$weekday-$cid_sesi";

        $s = "INSERT INTO tb_pemakaian_ruang (
          id,
          id_st_detail,
          id_sesi,
          id_ruang,
          unik_dosen
        ) VALUES (
          '$id',
          '$id_st_detail',
          '$cid_sesi',
          '$id_ruang',
          '$unik_dosen'
        ) ON DUPLICATE KEY UPDATE unik_dosen = '$unik_dosen' -- allow reinsert 
        ";
        // echolog("ZZZ: $s");
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        $last_id_sesi = $cid_sesi;
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
        '$id_st_detail',
        '$id_ruang',
        '$id_sesi',
        '$weekday',
        '$jam_mulai',
        '$jam_selesai',
        '$user[id]'
      ) ON DUPLICATE KEY UPDATE assign_date = CURRENT_TIMESTAMP 
      ";
      // echolog("insert multiple ($sks) data pemakaian ruang...OK");
      // echolog('inserting jadwal...');

      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    } // tidak ada error|konfliks saat insert pemakaian ruang
  } // tidak ada konflik dosen
} // end book sesi

if ($_POST) {
  jsurl($full_param);
}

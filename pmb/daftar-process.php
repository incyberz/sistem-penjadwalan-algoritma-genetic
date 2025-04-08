<?php
if (isset($_POST['btn_set_password'])) {
  $s = "UPDATE tb_akun SET password=md5('$_POST[password]') WHERE username='$_POST[btn_set_password]'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $pesan = 'passwordOK';
} elseif (isset($_POST['btn_pilih_jurusan'])) {
  $s = "UPDATE tb_pmb SET id_prodi=$_POST[btn_pilih_jurusan] WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_pilih_ulang_jurusan'])) {
  $s = "UPDATE tb_pmb SET id_prodi=NULL WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_pilih_jalur'])) {
  $s = "UPDATE tb_pmb SET id_jalur=$_POST[id_jalur] WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_reset_jalur'])) {
  $s = "UPDATE tb_pmb SET id_jalur=NULL WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_daftar'])) {
  $post_username = strip_tags(addslashes($_POST['username']));
  $post_nama = strip_tags(addslashes($_POST['nama']));
  $post_whatsapp = strip_tags(addslashes($_POST['whatsapp']));
  $post_asal_sekolah = strip_tags(addslashes($_POST['asal_sekolah']));
  $post_jeda_tahun_lulus = strip_tags(addslashes($_POST['jeda_tahun_lulus']));

  $s = "INSERT INTO tb_akun (
    username,
    tahun_pmb,
    nama,
    whatsapp,
    asal_sekolah,
    jeda_tahun_lulus
  ) VALUES (
    '$post_username',
    '$tahun_pmb',
    '$post_nama',
    '$post_whatsapp',
    '$post_asal_sekolah',
    '$post_jeda_tahun_lulus'
  ) ON DUPLICATE KEY UPDATE 
    created_at = CURRENT_TIMESTAMP
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $pesan = 'OK';
} elseif (isset($_POST['btn_upload_berkas']) || isset($_POST['btn_replace_berkas'])) {
  if (isset($_POST['btn_replace_berkas'])) {
    $t = explode('--', $_POST['btn_replace_berkas']);
    $jenis_berkas = $t[0] ?? die('jenis_berkas undefined.');
    $src = $t[1] ?? die('src undefined.');
    # ============================================================
    # DELETE DB
    # ============================================================
    $s = "DELETE FROM tb_berkas WHERE jenis_berkas='$jenis_berkas' AND username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    # ============================================================
    # DELETE FILE SEBELUMNYA
    # ============================================================
    unlink($src);
    alert('Delete file sebelumnya berhasil.', 'success');
  } else {
    $jenis_berkas = $_POST['btn_upload_berkas'];
  }
  include '../includes/resize_img.php';

  $file = $_FILES['file'];
  $path = 'uploads/berkas';
  $time = date('ymdHis');
  $new_file = strtolower("$username-$jenis_berkas-$time.jpg");
  $to = "$path/$new_file";

  if (move_uploaded_file($file['tmp_name'], $to)) {

    $nomor_berkas = isset($_POST['nomor_berkas']) ? "'$_POST[nomor_berkas]'" : 'NULL';
    $tanggal_berkas = isset($_POST['tanggal_berkas']) ? "'$_POST[tanggal_berkas]'" : 'NULL';
    $nominal = isset($_POST['nominal']) ? "'$_POST[nominal]'" : 'NULL';

    resize_img($to);
    # ============================================================
    # INSERT DB
    # ============================================================
    $s = "INSERT INTO tb_berkas (
      username,
      jenis_berkas,
      file,
      nomor_berkas,
      tanggal_berkas,
      nominal,
      status
    ) VALUES (
      '$username',
      '$jenis_berkas',
      '$new_file',
      $nomor_berkas,
      $tanggal_berkas,
      $nominal,
      NULL
    )";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert('Upload sukses.', 'success');
  }

  jsurl();
} elseif (isset($_POST['btn_set_next_step'])) {
  $next_step = $_POST['btn_set_next_step'];
  if ($next_step > $akun['last_step']) {
    $s = "UPDATE tb_akun SET last_step = $next_step WHERE username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }

  if ($next_step == 5) { // sebelumnya step data sekolah, cek jika sekolah baru
    $s = "SELECT * FROM tb_data_sekolah WHERE username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $data_sekolah = mysqli_fetch_assoc($q);
    $id_sekolah = $data_sekolah['id_sekolah'];
    if (!$id_sekolah) {
      # ============================================================
      # INSERT DATA SEKOLAH BARU
      # ============================================================
      $s = "SELECT (max(id)+1) as new_id_sekolah FROM tb_sekolah";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      $d = mysqli_fetch_assoc($q);
      $new_id_sekolah = $d['new_id_sekolah'];

      $s = "INSERT INTO tb_sekolah (
        id,
        nama_sekolah,
        jenis_sekolah,
        sekolah_negeri,
        alamat_sekolah,
        kecamatan,
        jurusans
      ) VALUES (
        $new_id_sekolah,
        '$data_sekolah[nama_sekolah]',
        '$data_sekolah[jenis_sekolah]',
        '$data_sekolah[sekolah_negeri]',
        '$data_sekolah[alamat_sekolah]',
        '$data_sekolah[kecamatan]',
        '$data_sekolah[jurusan]'
      )";
      echo '<pre>';
      var_dump($s);
      echo '</pre>';
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

      $s = "UPDATE tb_data_sekolah SET id_sekolah=$new_id_sekolah WHERE username='$username'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    } else { // sudah ada id_sekolah
      $s = "SELECT * FROM tb_sekolah WHERE id='$id_sekolah'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      $sekolah = mysqli_fetch_assoc($q);

      # ============================================================
      # HANYA MENAMBAH JURUSAN KE DATA SEKOLAH YANG ADA
      # ============================================================
      $jurusan_telah_ada = 0;
      $t = explode(';', $sekolah['jurusans']);
      foreach ($t as $key => $value) {
        $value = trim($value);
        if ($value == $data_sekolah['jurusan']) {
          $jurusan_telah_ada = 1;
          break;
        }
      }

      echo '<pre>';
      var_dump($jurusan_telah_ada);
      echo '</pre>';

      if (!$jurusan_telah_ada) {
        array_push($t, $data_sekolah['jurusan']);
        $jurusans = implode(';', $t);

        $s = "UPDATE tb_sekolah SET jurusans = '$jurusans' WHERE id='$id_sekolah'";
        echo '<pre>';
        var_dump($s);
        echo '</pre>';
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      }
    }
  }

  jsurl("./?daftar&step=$next_step");
} elseif (isset($_POST['btn_reset_data_ortu'])) {
  $s = "DELETE FROM tb_data_orangtua WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_submit_data_awal_ortu'])) {
  $s = "UPDATE tb_data_orangtua SET 
    ayah_meninggal = $_POST[ayah_meninggal],
    ibu_meninggal = $_POST[ibu_meninggal],
    ortu_cerai = $_POST[ortu_cerai],
    tinggal_dengan = $_POST[tinggal_dengan],
    punya_wali = $_POST[punya_wali] 
  WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  if ($_POST['ayah_meninggal']) {
    $s = "UPDATE tb_data_orangtua SET 
      pendidikan_ayah = '-',
      pekerjaan_ayah = '-',
      pendapatan_ayah = 0,
      whatsapp_ayah = '-' 
    WHERE username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }

  if ($_POST['ibu_meninggal']) {
    $s = "UPDATE tb_data_orangtua SET 
      pendidikan_ibu = '-',
      pekerjaan_ibu = '-',
      pendapatan_ibu = 0,
      whatsapp_ibu = '-' 
    WHERE username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }

  if (!$_POST['punya_wali']) {
    $s = "UPDATE tb_data_orangtua SET 
      nama_wali = '-',
      hubungan_dg_wali = '-',
      pendidikan_wali = '-',
      pekerjaan_wali = '-',
      pendapatan_wali = 0,
      whatsapp_wali = '-' 
    WHERE username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }

  jsurl();
} elseif (isset($_POST['btn_next_step'])) {
  $next_step = $_POST['btn_next_step'] + 1;
  $s = "SELECT last_step FROM tb_akun WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $last_step = $d['last_step'] ?? 0;
  if ($last_step < $next_step) {
    $s = "UPDATE tb_akun SET last_step=$next_step WHERE username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  } else {
    $next_step = $last_step;
  }
  jsurl("./?daftar&step=$next_step");
} elseif (isset($_POST['btn_finish_registrasi'])) {
  $s = "UPDATE tb_pmb SET tanggal_finish_registrasi = CURRENT_TIMESTAMP WHERE username = '$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
} elseif (isset($_POST['btn_kirim_notifikasi'])) {
  $s = "UPDATE tb_berkas SET last_notif = CURRENT_TIMESTAMP WHERE id = $_POST[btn_kirim_notifikasi]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  echo "
    <script>
      location.replace(\"$_POST[link_wa]\")
    </script>
  ";
  exit;
} elseif ($_POST) {

  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  alert('Belum ada handler untuk data POST diatas. Hubungi Developer!');
  exit;
}

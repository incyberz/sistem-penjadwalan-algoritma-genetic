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
} elseif (isset($_POST['btn_reply_bimbingan'])) {

  $t = explode('-', $_POST['btn_reply_bimbingan']);
  $id_mhs = $t[2];
  $id_peserta_bimbingan = "$t[0]-$t[1]-$t[2]";
  $id_laporan = $t[3] ?? die('undefined id_laporan');
  $path = "uploads/bimbingan/$id_mhs";
  $id_status_reply = $_POST['id_status_reply'] ?? die('undefined id_status_reply.');

  if ($id_status_reply == 5) { // laporan diterima, tidak perlu ada reply file

    # ============================================================
    # UPDATE STATUS MHS 
    # ============================================================
    $s = "UPDATE tb_peserta_bimbingan SET id_status_bimbingan=$_POST[status_bimbingan] WHERE id='$id_peserta_bimbingan'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));


    # ============================================================
    # UPDATE LAPORAN DITERIMA (5)
    # ============================================================
    $komentar = strip_tags(addslashes($_POST['komentar']));

    $s = "UPDATE tb_laporan_bimbingan SET 
    id_status=5,
    komentar = '$komentar',
    reply_date = CURRENT_TIMESTAMP 
    WHERE id=$id_laporan";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo '<br>Laporan updated.';



    # ============================================================
    # SET REVISED (4) LAPORAN TERDAHULU (3)
    # ============================================================
    $id_peserta_bimbingan = "$t[0]-$t[1]-$t[2]";
    $s = "UPDATE tb_laporan_bimbingan SET id_status=4 
    WHERE id_peserta_bimbingan='$id_peserta_bimbingan' 
    AND id_status=3";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo '<br>Laporan terdahulu revised.';


    jsurl();
  } else {

    # ============================================================
    # GET DATA LAPORAN
    # ============================================================
    $s = "SELECT * FROM tb_laporan_bimbingan WHERE id=$id_laporan";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data Laporan tidak ditemukan'));
    $laporan = mysqli_fetch_assoc($q);


    $time = date('ymdHis');
    $new_reply_file = str_replace('.docx', "-replied-$time.docx", $laporan['file']);

    # ============================================================
    # MOVE FILE
    # ============================================================
    if (move_uploaded_file($_FILES['reply_file']['tmp_name'], "$path/$new_reply_file")) {
      echo '<br>move file sukses.';
      $komentar = strip_tags(addslashes($_POST['komentar']));

      $s = "UPDATE tb_laporan_bimbingan SET
        id_status = $_POST[id_status_reply],
        komentar = '$komentar',
        reply_file = '$new_reply_file',
        reply_date = CURRENT_TIMESTAMP
      WHERE id=$id_laporan 
      ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      echo '<br>Laporan updated.';
    } else {
      $pesan = 'tidak dapat move_uploaded_file';
    }
  }

  jsurl();
} elseif (isset($_POST['btn_delete_laporan'])) {

  $t = explode('-', $_POST['btn_delete_laporan']);
  $id_mhs = $t[2];
  $id_laporan = $t[3] ?? die('undefined id_laporan.');
  if (!$id_laporan) die('id_laporan is empty.');
  $path = "uploads/bimbingan/$id_mhs";

  $s = "SELECT * FROM tb_laporan_bimbingan WHERE id=$id_laporan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data Laporan Bimbingan tidak ditemukan'));
  $laporan = mysqli_fetch_assoc($q);
  if ($laporan['file']) {
    unlink("$path/$laporan[file]");
    echo 'Unlink file laporan: OK';
  }
  if ($laporan['reply_file']) {
    unlink("$path/$laporan[reply_file]");
    echo 'Unlink reply_file laporan: OK';
  }
  $s = "DELETE FROM tb_laporan_bimbingan WHERE id=$id_laporan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo 'Delete data laporan: OK';
  jsurl();
} elseif (isset($_POST['btn_kirim_laporan'])) {
  $id_peserta_bimbingan = $_POST['btn_kirim_laporan'];
  $path = "uploads/bimbingan/$id_mhs";
  if (!file_exists($path)) mkdir($path);

  # ============================================================
  # HANDLE FILE 
  # ============================================================
  $pesan = null;
  $file = $_FILES['fileLaporan'];
  if (strtolower(substr($file['name'], -5)) == '.docx') {
    echo '<br>Filename: OK...';
    if ($file['size'] > 1000 and $file['size'] < 10000000) {
      echo '<br>Filesize: OK...';
      $time = date('ymdHis');
      $to_file = "$id_mhs-bab-$_POST[bab]-$time.docx";
      if (move_uploaded_file($file['tmp_name'], "$path/$to_file")) {
        echo "<br>Movingfile [$to_file]: OK...";
        $catatan = strip_tags(addslashes($_POST['catatan']));
        # ============================================================
        # INSERT INTO TB LAPORAN
        # ============================================================
        $s = "INSERT INTO tb_laporan_bimbingan (
          id_peserta_bimbingan, -- varchar(20)
          catatan,
          file
        ) VALUES (
          '$id_peserta_bimbingan', -- varchar(20)
          '$catatan',
          '$to_file'
        )";
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        echo "<br>Save-DB: OK...";
        jsurl();
      } else {
        $pesan = "cannot move upload file.";
      }
    } else {
      $pesan = "filesize exceed: [$file[size]]";
    }
  } else {
    $pesan = "filename not OK: [$file[name]]";
  }

  exit;
}

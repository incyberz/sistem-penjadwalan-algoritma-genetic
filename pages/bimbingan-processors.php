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
} elseif (isset($_POST['btn_kirim_laporan'])) {

  $id_peserta_bimbingan = $_POST['btn_kirim_laporan'];
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  echo '<pre>';
  var_dump($_FILES);
  echo '</pre>';
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
      $nama_awal = strtolower(str_replace('.docx', '', str_replace(' ', '-', $file['name'])));
      $time = date('ymdHis');
      $to_file = "$id_mhs-$nama_awal-$time.docx";
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

<?php
# ============================================================
# AUTO-INSERT KURIKULUM JIKA NUM_ROWS != COUNT_TA x COUNT_PRODI
# ============================================================
$s = "SELECT
(SELECT COUNT(1) FROM tb_kurikulum) count_kurikulum,
(SELECT COUNT(1) FROM tb_prodi) count_prodi,
(SELECT COUNT(1) FROM tb_ta WHERE id <= $tahun_ini_genap ) count_ta
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
if ($d['count_kurikulum'] != $d['count_prodi'] * $d['count_ta']) {
  $s = "SELECT id,nama FROM tb_prodi";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $id_prodi = $d['id'];
    $nama_prodi = $d['nama'];

    $s2 = "SELECT id FROM tb_ta";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    while ($d2 = mysqli_fetch_assoc($q2)) {
      $id_ta = $d2['id'];
      if ($id_ta > $tahun_ini_genap) break; // skip kur untuk tahun depan
      $s3 = "INSERT INTO tb_kurikulum (
        id_ta,
        id_prodi,
        nama
      ) VALUES (
        $id_ta,
        $id_prodi,
        '$nama_prodi $id_ta'
      ) ON DUPLICATE KEY UPDATE id_ta=$id_ta";
      echolog($s3);
      $q3 = mysqli_query($cn, $s3) or die(mysqli_error($cn));
    }
  }
  jsurl();
}

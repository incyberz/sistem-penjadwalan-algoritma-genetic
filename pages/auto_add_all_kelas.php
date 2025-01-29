<?php
# ============================================================
# AUTO ADD ALL GRUP KELAS
# ============================================================
$s = "SELECT a.* FROM tb_prodi a JOIN tb_rombel b ON a.id=b.id_prodi";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($prodi = mysqli_fetch_assoc($q)) {
  $id_prodi = $prodi['id'];
  foreach ($rshift as $id_shift => $arr_shift) {
    for ($semester = 1; $semester <= $prodi['jumlah_semester']; $semester++) {
      echolog("ZZZ: ta_aktif: $ta_aktif | id_prodi: $id_prodi | id_shift: $id_shift | semester: $semester | ");
      $s = "INSERT INTO tb_kelas ";
    }
  }
}

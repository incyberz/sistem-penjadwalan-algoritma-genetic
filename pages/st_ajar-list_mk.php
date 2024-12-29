<?php
$sql_ganjil = $is_ganjil ? " (semester % 2 = 1) " : " (semester % 2 = 0) ";
$s = "SELECT * FROM tb_mk 
WHERE id_prodi=$dkur[id_prodi] 
AND $sql_ganjil -- atau genap 
ORDER BY semester";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $last_smt = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $id = $d['id'];
    $separator = $last_smt != $d['semester'] ? '<hr>' : '';
    $list_mk .= "
      $separator
      <div>
        <label class=label_mk>
          <input class=check_mk type='checkbox' name='id_mk[$id]'> SM$d[semester] - $d[nama] - $d[sks] SKS
        </label>
      </div>  
    ";
    $last_smt = $d['semester'];
  }
} else {
  $pesan_error = alert("Belum ada satupun MK di prodi [$dkur[nama_prodi]] semester [$Ganjil]<hr><a href='?crud&tb=mk'>Buat Pilihan MK</a>", 'danger', '', false);
  $list_mk .= $pesan_error;
  $siap_assign = false;
}

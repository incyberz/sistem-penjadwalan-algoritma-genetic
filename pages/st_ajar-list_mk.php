<?php
$sql_ganjil = $is_ganjil ? " (a.semester % 2 = 1) " : " (a.semester % 2 = 0) ";
$s = "SELECT a.*,
c.id as id_prodi,
c.singkatan as prodi 
FROM tb_mk a 
JOIN tb_kurikulum b ON a.id_kurikulum=b.id
JOIN tb_prodi c ON b.id_prodi=c.id
-- WHERE 1 
-- AND $sql_ganjil -- atau genap 
ORDER BY c.id, a.semester 
";
echo '<pre>';
var_dump($s);
echo '</pre>';
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $last_smt = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $id = $d['id'];
    $separator = $last_smt != $d['semester'] ? '<hr>' : '';
    $list_mk .= "
      $separator
      <div id=div_mk__$id>
        <label class=label_mk>
          <input class=check_mk type='checkbox' name='id_mk[$id]'> $d[prodi]-SM$d[semester] - $d[nama] - $d[sks] SKS
        </label>
      </div>  
    ";
    $last_smt = $d['semester'];
  }
  $list_mk = "
    <div class='mb1 f12'>Ceklis MK yang akan diberikan:</div>
    $list_mk
  ";
} else {
  $pesan_error = alert("Belum ada satupun MK di prodi [$dkur[nama_prodi]] semester [$Ganjil]<hr><a href='?crud&tb=mk'>Buat Pilihan MK</a>", 'danger', '', false);
  $list_mk .= $pesan_error;
  $siap_assign = false;
}

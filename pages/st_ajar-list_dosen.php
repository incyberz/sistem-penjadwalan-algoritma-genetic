<?php
$s = "SELECT a.*,
(SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi) homebase 
FROM tb_dosen a";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  while ($d = mysqli_fetch_assoc($q)) {

    if ($d['id_prodi'] == $dkur['id_prodi']) {
      $blue =  'blue bold';
      $homebase = "(homebase $d[homebase])";
    } elseif ($d['homebase']) {
      $blue = '';
      $homebase = "($d[homebase])";
    } else {
      $blue = 'abu miring';
      $homebase = "(LB)";
    }
    $list_dosen .= "
      <div class='$blue'>
        <label class='pointer label_dosen' id=label_dosen__$d[id]>
          <input type='checkbox' name='check_dosen[$d[id]]'> 
          <span class=nama_dosen id=nama_dosen__$d[id]>$d[nama]</span> 
          $homebase
          <span class=hideit id=gelar_depan__$d[id]>$d[gelar_depan]</span> 
          <span class=hideit id=gelar_belakang__$d[id]>$d[gelar_belakang]</span> 
          <span class=hideit id=nidn__$d[id]>$d[nidn]</span> 
        </label>
      </div>  
    ";
  }
} else {
  $list_dosen .= alert("Belum ada satupun dosen di prodi [$dkur[nama_prodi]]<hr><a href='?crud&tb=dosen'>Buat Pilihan dosen</a>", 'danger', '', false);
  $siap_assign = false;
}

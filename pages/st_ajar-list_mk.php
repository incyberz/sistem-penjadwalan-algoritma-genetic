<?php
$s = "SELECT * FROM tb_mk WHERE id_prodi=$dkur[id_prodi]";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  while ($d = mysqli_fetch_assoc($q)) {
    $list_mk .= "
      <div>
        <label>
          <input type='checkbox' name='id_mk[]'> $d[nama]
        </label>
      </div>  
    ";
  }
} else {
  $list_mk .= alert("Belum ada satupun MK di prodi [$dkur[nama_prodi]]<hr><a href='?crud&tb=mk'>Buat Pilihan MK</a>", 'danger', '', false);
  $siap_assign = false;
}

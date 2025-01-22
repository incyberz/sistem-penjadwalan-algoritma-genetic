<?php
$s = "SELECT * FROM tb_kelas WHERE id_prodi=$kurikulum[id_prodi]";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  while ($d = mysqli_fetch_assoc($q)) {
    // $id=$d['id'];
    $list_kelas .= "
      <div>
        <label>
          <input type='checkbox' name='id_kelas[]'> $d[nama]
        </label>
      </div>  
    ";
  }
} else {
  $pesan_error = alert("Belum ada satupun kelas di prodi [$kurikulum[nama_prodi]]<hr><a href='?crud&tb=kelas'>Buat Kelas</a>", 'danger', '', false);
  $list_kelas .= $pesan_error;
  $siap_assign = false;
}

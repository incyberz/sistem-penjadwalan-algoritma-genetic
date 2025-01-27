<?php
if ($kelas['id_dosen_wali']) {
  $status_syarat = "<b>Dosen Wali:</b> $kelas[nama_dosen_wali] $img_check <span class=btn_aksi id=form_ubah_id_dosen_wali__toggle>$img_edit</span>";
  $hideit = 'hideit';
} else {
  $hideit = '';
}
$id_dosen_wali = $kelas['id_dosen_wali'] ?? $kelas['nama'];

$s = "SELECT a.id,a.nidn,a.nama 
FROM tb_dosen a 
WHERE a.id_prodi = $kelas[id_prodi] 
AND (a.status > 0 or status is null)
ORDER BY a.nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$opt_dosen = '';
while ($d = mysqli_fetch_assoc($q)) {
  $selected = $d['id'] == $kelas['id_dosen_wali'] ? 'selected' : '';
  $opt_dosen .= "<option value=$d[id] $selected>$d[nama] - NIDN. $d[nidn]</option>";
}

$input_syarat = "
  <form method=post class=$hideit id=form_ubah_id_dosen_wali>
    <div class='flexy wadah gradasi-kuning mt1'>
      <div><b>Dosen homebase $kelas[prodi]:</b></div>
      <div>
        <select class='form-control' name=id_dosen_wali value='$id_dosen_wali'>
          $opt_dosen
        </select>
      </div>
      $btn_save
    </div>
  </form>
";

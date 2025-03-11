<?php
$tr = '';
$s = $select_peserta_bimbingan;
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$i = 0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $tr .= "
    <tr>
      <td>$i</td>
      <td>$d[nama_mhs]</td>
      <td>$d[nama_dosen]</td>
      <td>$d[assign_at]</td>
      <td>$img_drop</td>
    </tr>
  ";
}

?>

<h2 class="mb-4">Daftar Peserta Bimbingan</h2>

<div class="card mt-4">
  <div class="card-header bg-success text-white">Daftar Mahasiswa dan Dosen Pembimbing</div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>NIM</th>
          <th>Nama Mahasiswa</th>
          <th>Nama Dosen Pembimbing</th>
          <th>Assign At</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="daftarAssign">
        <?= $tr ?>
      </tbody>
    </table>
  </div>
</div>
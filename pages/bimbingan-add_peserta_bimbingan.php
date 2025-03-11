<style>
  .info {
    font-size: 10px;
    color: #555;

  }

  .kolom {
    height: 400px;
    overflow-y: scroll;
    margin-top: -15px;
    padding: 15px;
  }

  .kolom-kiri {
    background: linear-gradient(#aaffff77, #ccffff44);
    margin-left: -15px;
    margin-right: -8px;
  }

  .kolom-kanan {
    background: linear-gradient(#aaaaff99, #ccccff33);
    margin-left: -15px;
    margin-right: -15px;
  }
</style>

<?php
$checks_mhs = '';
$s = "SELECT a.id, a.nama, a.nim 
FROM tb_mhs a 
LEFT JOIN tb_peserta_bimbingan b ON a.id = b.id_mhs 
WHERE a.eligible_bimbingan = 1 -- ready pembayaran dll 
AND b.id_bimbingan IS NULL
ORDER BY a.nama
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $checks_mhs .= "
    <div class='form-check'>
      <input class='form-check-input check-mhs' type='checkbox' value='$d[id]' id='mhs$d[id]' name='rmhs[$d[id]]'>
      <label class='form-check-label' for='mhs$d[id]'>$d[nama] ($d[nim])</label>
    </div>  
  ";
}

$checks_dosen = '';
$s = "SELECT a.id, a.nama, a.nidn,
b.id as id_bimbingan 
FROM tb_dosen a 
JOIN tb_bimbingan b ON a.id = b.id_dosen -- ada data membimbing di TA ini atau TA sebelumnya
WHERE a.eligible_membimbing = 1 -- eligible membimbing 
AND b.id_ta = $ta_aktif 
ORDER BY a.nama
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $checks_dosen .= "
    <div class='form-check'>
      <input required class='form-check-input' type='radio' value='$d[id_bimbingan]' id='dosen$d[id]' name='id_bimbingan' checked>
      <label class='form-check-label' for='dosen$d[id]'>$d[nama] ($d[nidn])</label>
    </div>  
  ";
}


?>

<h2 class="mb-4">Add Peserta Bimbingan</h2>

<div class="card">
  <div class="card-header bg-primary text-white">Form Assign Mahasiswa ke Dosen Pembimbing</div>
  <div class="card-body">
    <form method=post id="formAssignBimbingan">
      <div class="row">
        <div class="col-md-6">
          <div class="kolom kolom-kiri">
            <h5>Mahasiswa Eligible yang Belum Punya Pembimbing</h5>
            <div class="info mb2">Status Bimbingan: 1 s.d 99</div>
            <?= $checks_mhs ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="kolom kolom-kanan">
            <h5>Dosen Eligible Membimbing</h5>
            <div class="info mb2">eligible_membimbing: 1 TA <?= $ta_aktif ?></div>
            <?= $checks_dosen ?>
          </div>
        </div>
      </div>
      <button class="btn btn-success mt-3 w-100" name=btn_assign_bimbingan>Assign Bimbingan</button>
      <a href="?daftar-peserta-bimbingan" class="btn btn-secondary w-100 mt2">Daftar Peserta Bimbingan</a>
    </form>
  </div>
  <div class="mt2">
  </div>
</div>
<?php
set_title('Melengkapi Biodata');
include 'biodata.php';

# ============================================================
# DESCRIBE BIODATA 
# ============================================================
$s = "DESCRIBE tb_biodata";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$Fields = [];
while ($d = mysqli_fetch_assoc($q)) {
  if ($d['Field'] == 'username') continue;
  $Fields[$d['Field']] = $d;
}

$tr_biodata = '';
foreach ($Fields as $field => $v) {
  $type = $v['Type'] == 'date' ? 'date' : '';
  $type = strpos('salt' . $v['Type'], 'int(') > 0 ? 'number' : $type;

  if ($field == 'gender') {
    $input = "
      <div class='py-1 d-flex gap-4'>
        <label><input type=radio name=$field value=L> Laki-laki</label>
        <label><input type=radio name=$field value=P> Perempuan</label>
      </div>
    ";
  } elseif ($field == 'agama') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio name=$field value=0 checked> Islam</label>
        <label><input type=radio name=$field value=1> Kristen</label>
        <label><input type=radio name=$field value=2> Lainnya</label>
      </div>
    ";
  } elseif ($field == 'warga_negara') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio name=$field value=0 checked> Indonesia</label>
        <label><input type=radio name=$field value=1> Asing</label>
      </div>
    ";
  } elseif ($field == 'cacat_fisik') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio name=$field value=0 checked> Tidak</label>
        <label><input type=radio name=$field value=1> Disabilitas</label>
      </div>
    ";
  } elseif ($field == 'sudah_bekerja') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio name=$field value=0 checked> Belum</label>
        <label><input type=radio name=$field value=1> Sudah</label>
      </div>
    ";
  } elseif ($field == 'punya_usaha') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio name=$field value=0 checked> Belum</label>
        <label><input type=radio name=$field value=1> Punya</label>
      </div>
    ";
  } else {
    $input = "
      <input 
        type='$type' 
        class='form-control editable editable-biodata' 
        id=$field 
        value='$biodata[$field]'
      />
    ";
  }

  $kolom = str_replace('_', ' ', $field);
  $tr_biodata .= "
    <tr>
      <td class='kolom'>$kolom</td>
      <td>$input</td>
    </tr>
  ";
}


?>
<div class="card">
  <div class="card-header bg-primary text-white text-center">
    Info Akun
  </div>
  <div class="card-body">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
        <input type="text" class="form-control" id="username" value="<?= $username ?>" disabled>
      </div>
    </div>

    <div class="mb-3">
      <label for="nama" class="form-label">Nama</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
        <input type="text" class="form-control" id="nama" name=nama placeholder="Masukkan Nama" required minlength="3" maxlength="30" value="<?= $akun['nama'] ?>">
      </div>
    </div>

  </div>
</div>
<h3 class="mt-3 text-center">Pengisian Biodata</h3>
<p class="text-center">Untuk kelancaran pengisian silahkan sediakan Kartu Keluarga atau KTP Anda!</p>
<table class="table table-dark table-striped">
  <?= $tr_biodata ?>
</table>
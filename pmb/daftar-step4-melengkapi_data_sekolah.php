<?php
set_title('Melengkapi Biodata');
include 'data_sekolah.php';

# ============================================================
# DESCRIBE BIODATA 
# ============================================================
$s = "DESCRIBE tb_data_sekolah";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$Fields = [];
while ($d = mysqli_fetch_assoc($q)) {
  if ($d['Field'] == 'username') continue;
  $Fields[$d['Field']] = $d;
}

$tr_data_sekolah = '';
foreach ($Fields as $field => $v) {
  $type = $v['Type'] == 'date' ? 'date' : '';
  $type = strpos('salt' . $v['Type'], 'int(') > 0 ? 'number' : $type;

  if ($field == 'jenis_sekolah') {
    $input = "
      <div class='py-1 d-flex gap-4'>
        <label><input type=radio name=$field value=1> SMA</label>
        <label><input type=radio name=$field value=2> SMK</label>
        <label><input type=radio name=$field value=3> MA</label>
      </div>
    ";
  } elseif ($field == 'sekolah_negeri') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio name=$field value=0> Swasta</label>
        <label><input type=radio name=$field value=1> Negeri</label>
        <label><input type=radio name=$field value=2> Inter</label>
      </div>
    ";
  } else {
    $input = "
      <input 
        type='$type' 
        class='form-control editable editable-data_sekolah' 
        id=$field 
        value='$data_sekolah[$field]'
      />
    ";
  }

  $kolom = str_replace('_', ' ', $field);
  $tr_data_sekolah .= "
    <tr>
      <td class='kolom'>$kolom</td>
      <td>$input</td>
    </tr>
  ";
}

$tahun_lulus = $tahun_pmb - $akun['jeda_tahun_lulus'];



?>
<h3 class="mt-3 text-center">Pengisian Data Sekolah</h3>
<p class="text-center">Untuk kelancaran pengisian silahkan sediakan Buku Raport atau Copy Ijazah (SKL)!</p>
<table class="table table-dark table-striped">
  <tr>
    <td class='kolom'>Tahun Lulus</td>
    <td><?= $tahun_lulus ?></td>
  </tr>
  <?= $tr_data_sekolah ?>
</table>
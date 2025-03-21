<?php
set_title('Melengkapi Biodata');
include 'data_orangtua.php';

# ============================================================
# DESCRIBE BIODATA 
# ============================================================
$s = "DESCRIBE tb_data_orangtua";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$Fields = [];
while ($d = mysqli_fetch_assoc($q)) {
  if ($d['Field'] == 'username') continue;
  $Fields[$d['Field']] = $d;
}

$tr_data_orangtua = '';
foreach ($Fields as $field => $v) {
  $type = $v['Type'] == 'date' ? 'date' : '';
  $type = strpos('salt' . $v['Type'], 'int(') > 0 ? 'number' : $type;

  if ($field == 'ayah_meninggal' || $field == 'ibu_meninggal') {
    $input = "
      <div class=''>
        <label class=d-block><input type=radio name=$field value=0 checked> Masih hidup</label>
        <label class=d-block><input type=radio name=$field value=1> Sudah Meninggal</label>
      </div>
    ";
  } elseif ($field == 'pendidikan_ayah' || $field == 'pendidikan_ibu' || $field == 'pendidikan_wali') {
    $input = "
      <div class=''>
        <label class=d-block><input type=radio name=$field value=TS> Tidak Sekolah</label>
        <label class=d-block><input type=radio name=$field value=SD> Sekolah Dasar</label>
        <label class=d-block><input type=radio name=$field value=SP> SLTP</label>
        <label class=d-block><input type=radio name=$field value=SA> SMA</label>
        <label class=d-block><input type=radio name=$field value=D3> Diploma</label>
        <label class=d-block><input type=radio name=$field value=S1> Sarjana</label>
        <label class=d-block><input type=radio name=$field value=S2> Magister</label>
        <label class=d-block><input type=radio name=$field value=S3> Doktor</label>
      </div>
    ";
  } elseif ($field == 'pendapatan_ayah' || $field == 'pendapatan_ibu' || $field == 'pendapatan_wali') {
    $input = "
      <div class=''>
        <label class=d-block><input type=radio name=$field value=0> Tidak Berpenghasilan</label>
        <label class=d-block><input type=radio name=$field value=1> Dibawah 1jt</label>
        <label class=d-block><input type=radio name=$field value=2> Dibawah 2jt</label>
        <label class=d-block><input type=radio name=$field value=3> Dibawah 3jt</label>
        <label class=d-block><input type=radio name=$field value=4> 3jt lebih</label>
      </div>
    ";
  } elseif ($field == 'punya_wali') {
    $input = "
      <div class=''>
        <label><input type=radio name=$field value=0 checked> Tidak Punya</label>
        <label><input type=radio name=$field value=1> Saya dibiayai oleh Wali</label>
      </div>
    ";
  } else {
    $input = "
      <input 
        type='$type' 
        class='form-control editable editable-data_orangtua' 
        id=$field 
        value='$data_orangtua[$field]'
      />
    ";
  }

  $kolom = str_replace('_', ' ', $field);
  $tr_data_orangtua .= "
    <tr>
      <td class='kolom'>$kolom</td>
      <td>$input</td>
    </tr>
  ";
}

$title = $rstep[$get_step];

?>
<h3 class="mt-3 text-center"><?= $title ?></h3>
<p class="text-center">Untuk kelancaran pengisian silahkan sediakan Buku Raport atau Copy Ijazah (SKL)!</p>

<table class="table table-dark table-striped">
  <?= $tr_data_orangtua ?>
</table>
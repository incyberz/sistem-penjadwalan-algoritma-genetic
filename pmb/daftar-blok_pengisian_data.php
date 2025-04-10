<?php
# ============================================================
# DESCRIBE TABLE
# ============================================================
$s = "DESCRIBE tb_$tb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$Fields = [];
while ($d = mysqli_fetch_assoc($q)) {
  if (
    $d['Field'] == 'username'
    || $d['Field'] == 'id_sekolah'
  ) continue;
  $Fields[$d['Field']] = $d;
}

$tr_input_data = '';
$ktp = $biodata['nomor_ktp'] ?? null;
$asal_sekolah = $akun['asal_sekolah'] ?? null;
$tahun_lulus = $tahun_pmb - $akun['jeda_tahun_lulus'];

foreach ($Fields as $field => $v) {

  # ============================================================
  # DATA ORANGTUA SKIP FIELDS
  # ============================================================
  if ($tb == 'data_orangtua') {

    if (key_exists($field, $rpertanyaan_awal)) {
      continue;
    }

    if ($data['ayah_meninggal']) {
      if (
        $field == 'pendidikan_ayah'
        || $field == 'pekerjaan_ayah'
        || $field == 'pendapatan_ayah'
        || $field == 'whatsapp_ayah'
      ) {
        continue;
      }
    }

    if ($data['ibu_meninggal']) {
      if (
        $field == 'pendidikan_ibu'
        || $field == 'pekerjaan_ibu'
        || $field == 'pendapatan_ibu'
        || $field == 'whatsapp_ibu'
      ) {
        continue;
      }
    }
    if (!$data['punya_wali']) {
      if (
        $field == 'nama_wali'
        || $field == 'hubungan_dg_wali'
        || $field == 'pendidikan_wali'
        || $field == 'pekerjaan_wali'
        || $field == 'pendapatan_wali'
        || $field == 'whatsapp_wali'
      ) {
        continue;
      }
    }
  } // END DATA ORANGTUA SKIP FIELDS
  # ============================================================

  # ============================================================
  # NORMAL LOOP
  # ============================================================
  $type = $v['Type'] == 'date' ? 'date' : '';
  $type = strpos('salt' . $v['Type'], 'int(') > 0 ? 'number' : $type;

  $disabled = '';
  if ($ktp && ($field == 'gender' || $field == 'kecamatan' || $field == 'kabupaten' || $field == 'provinsi')) $disabled = 'disabled';

  if ($tb == 'data_sekolah') {
    if (!$is_new_sekolah) {
      if (
        $field == 'nama_sekolah'
        || $field == 'alamat_sekolah'
        || $field == 'kecamatan'
        || $field == 'jenis_sekolah'
        || $field == 'sekolah_negeri'
      ) {
        $disabled = 'disabled';
      }
    }
  }

  $checkedL = $data[$field] == 'L' ? 'checked' : '';
  $checkedP = $data[$field] == 'P' ? 'checked' : '';
  $checked0 = $data[$field] == '0' ? 'checked' : '';
  $checked1 = $data[$field] == '1' ? 'checked' : '';
  $checked2 = $data[$field] == '2' ? 'checked' : '';
  $checked3 = $data[$field] == '3' ? 'checked' : '';

  if ($field == 'gender') {
    $input = "
      <div class='py-1 d-flex gap-4'>
        <label><input type=radio class='radio' name=$field value=L id=$tb-$field-L $checkedL $disabled> Laki-laki</label>
        <label><input type=radio class='radio' name=$field value=P id=$tb-$field-P $checkedP $disabled> Perempuan</label>
      </div>
    ";
  } elseif ($field == 'agama') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio class='radio' id=$tb-$field-0 name=$field value=0 $checked0> Islam</label>
        <label><input type=radio class='radio' id=$tb-$field-1 name=$field value=1 $checked1> Kristen</label>
        <label><input type=radio class='radio' id=$tb-$field-2 name=$field value=2 $checked2> Lainnya</label>
      </div>
    ";
  } elseif ($field == 'warga_negara') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio class='radio' id=$tb-$field-0 name=$field value=0 $checked0> Indonesia</label>
        <label><input type=radio class='radio' id=$tb-$field-1 name=$field value=1 $checked1> Asing</label>
      </div>
    ";
  } elseif ($field == 'cacat_fisik') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio class='radio' id=$tb-$field-0 name=$field value=0 $checked0> Tidak</label>
        <label><input type=radio class='radio' id=$tb-$field-1 name=$field value=1 $checked1> Disabilitas</label>
      </div>
    ";
  } elseif ($field == 'sudah_bekerja') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio class='radio' id=$tb-$field-0 name=$field value=0 $checked0> Belum</label>
        <label><input type=radio class='radio' id=$tb-$field-1 name=$field value=1 $checked1> Sudah</label>
      </div>
    ";
  } elseif ($field == 'punya_usaha') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio class='radio' id=$tb-$field-0 name=$field value=0 $checked0> Belum</label>
        <label><input type=radio class='radio' id=$tb-$field-1 name=$field value=1 $checked1> Punya</label>
      </div>
    ";


    # ============================================================
    # DATA SEKOLAH
    # ============================================================
  } elseif ($field == 'jenis_sekolah') {
    $input = "
      <div class='py-1 d-flex gap-4'>
        <label><input type=radio class='radio' id=$tb-$field-1 name=$field value=1 $checked1 $disabled> $rjenis_sekolah[1]</label>
        <label><input type=radio class='radio' id=$tb-$field-2 name=$field value=2 $checked2 $disabled> $rjenis_sekolah[2]</label>
        <label><input type=radio class='radio' id=$tb-$field-3 name=$field value=3 $checked3 $disabled> $rjenis_sekolah[3]</label>
      </div>
    ";
  } elseif ($field == 'sekolah_negeri') {
    $input = "
      <div class='py-1 d-flex gap-3'>
        <label><input type=radio class='radio' id=$tb-$field-1 name=$field value=1 $checked1 $disabled> $rsekolah_negeri[1]</label>
        <label><input type=radio class='radio' id=$tb-$field-2 name=$field value=2 $checked2 $disabled> $rsekolah_negeri[2]</label>
      </div>
    ";
  } elseif ($tb == 'data_orangtua' and substr($field, 0, 10) == 'pendidikan') {
    $labels = '';
    foreach ($rpendidikan as $k => $v) {
      $checked = $k == $data[$field] ? 'checked' : '';
      $labels .= "
        <label class=d-block><input type=radio class='radio' id=$tb-$field-$k name=$field value=$k $checked $disabled> $v</label>
      ";
    }
    $input = "
      <div class='py-1'>
        $labels
      </div>
    ";
  } elseif ($tb == 'data_orangtua' and substr($field, 0, 10) == 'pendapatan') {
    $labels = '';
    foreach ($rpendapatan as $k => $v) {
      $checked = ($k == $data[$field] and $data[$field] !== null) ? 'checked' : '';
      $labels .= "
        <label class=d-block><input type=radio class='radio' id=$tb-$field-$k name=$field value=$k $checked $disabled> $v</label>
      ";
    }
    $input = "
      <div class='py-1'>
        $labels
      </div>
    ";
  } else {

    # ============================================================
    # INPUT TEXT FIELDS
    # ============================================================

    $whatsapp_ortu_class = '';
    if ($field == 'nama_sekolah') {
      if (!$data[$field]) $data[$field] = $asal_sekolah;
    } elseif ($field == 'tahun_lulus') {
      if (!$data[$field]) $data[$field] = $tahun_lulus;
    } elseif ($field == 'whatsapp_ayah' || $field == 'whatsapp_ibu' || $field == 'whatsapp_wali') {
      $whatsapp_ortu_class = 'whatsapp_ortu';
    }



    $input = "
      <input 
        $disabled
        type='$type' 
        class='form-control input-editable $whatsapp_ortu_class' 
        id='$tb-$field' 
        value='$data[$field]'
      />
    ";
  }

  $kolom = str_replace('_', ' ', $field);
  $tr_input_data .= "
    <tr>
      <td class='kolom' width=40%>$kolom</td>
      <td>
        <div class=td-values-input-data>$input</div>
        <div id='$tb-$field-info'></div>
      </td>
    </tr>
  ";
}

include 'cek_kelengkapan_fields.php';
$progress = "
  <div><span id='lengkap-of'>$terisi</span> of <span id='total-of'>$total</span> items</div>
  <div class='progress'>
    <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='$persen' aria-valuemin='0' aria-valuemax='100' style='width: $persen%' id=progress-bar>$persen%</div>
  </div>
";
if ($persen == 100) {
  echo "
    <script>
      $(function(){
        $('#form_next_step').slideDown();
      })
    </script>
  ";
}

$info_akun = '';
if ($tb == 'biodata') include 'info_akun.php';

echo "
  $info_akun
  <div class='bg-success putih tengah progress-sticky'>
    <h3>$progress_h3</h3>
    $progress
  </div>
  <p class='text-center mt2'>$petunjuk</p>
  <div class='blok-table-input-data'>
    <table class='table table-dark table-striped'>
      $tr_input_data
    </table>
  </div>
";

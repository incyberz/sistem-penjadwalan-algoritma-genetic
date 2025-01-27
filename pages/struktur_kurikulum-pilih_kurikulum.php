<?php
$div_fakultas = '';
foreach ($rfakultas as $fakultas => $arr_fakultas) {
  $div_shifts = '';
  $col = round(12 / count($rshift));
  foreach ($rshift as $key => $value) {
    $s = "SELECT * FROM tb_prodi WHERE fakultas='$fakultas' ORDER BY jenjang, nama";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $list = '';
    while ($d = mysqli_fetch_assoc($q)) {
      $list .= "<div><a class='btn btn-primary w-100 mb2' href='?struktur_kurikulum&id_prodi=$d[id]&id_shift=$key'>$d[fakultas] - $d[jenjang] - $d[nama] - $value[nama]</a></div>";
    }
    $div_shifts .= "
      <div class=col-$col>
        <div>Kelas $value[nama]:</div>
        <div class='wadah mt2'>$list</div>
      </div>
    ";
  }
  $div_fakultas .= "
    <div class=wadah>
      <h3>$fakultas</h3>
      <div class=row>$div_shifts</div>
      <a href='?crud&tb=prodi&note=Tambah Prodi untuk Fakultas $fakultas'>Tambah Prodi $fakultas</a>
    </div>
  ";
}

set_h2('Pilih Kurikulum', "<div class=petunjuk>Silahkan Pilih Prodi yang mana yang ingin Anda akses $img_help</div>");
echo "
  $div_fakultas
";

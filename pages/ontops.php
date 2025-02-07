<style>
  .ontop {
    position: fixed;
    z-index: 1000;
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    font-size: 12px;
    padding: 5px;
    /* border: solid 1px #eee; */
    box-shadow: 0 0 25px gray;
  }
</style>
<?php
function ontop($value, $styles = null)
{

  return "
    <div class='ontop' style='$styles'>
      $value
    </div>
  ";
}

function warna_teks($hexColor)
{
  // Menghapus tanda '#' jika ada
  $hexColor = ltrim($hexColor, '#');

  // Konversi ke nilai RGB
  $r = hexdec(substr($hexColor, 0, 2));
  $g = hexdec(substr($hexColor, 2, 2));
  $b = hexdec(substr($hexColor, 4, 2));

  // Menghitung luminance dengan rumus persepsi manusia
  $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b);

  // Menentukan apakah warna gelap atau terang
  return $luminance < 128 ? 'white' : 'black';
}




echo  ontop($ta_aktif, "
  top:-10px;
  left:-15px;
  background:yellow;
  border-radius: 10px;
  padding: 15px 10px 5px 20px;
  font-weight: bold;
  color: black;
");

if ($id_shift) {
  $bg = $id_shift == 'R' ? '#a4fDaA' : '#FFB74D';
  echo  ontop($id_shift, "
  top: 80px;left: -10px;
  padding: 5px 8px 5px 15px;
  background: $bg;
  border-radius: 5px;
  ");
}

if ($id_prodi) {
  $warna_hexa = $rprodi[$id_prodi]['warna_hexa'] ?? 'ffff00';
  $color = warna_teks($warna_hexa);

  echo  ontop($rprodi[$id_prodi]['singkatan'], "
  top: 40px;
  left: -10px;
  padding: 5px 8px 5px 15px;
  background: #$warna_hexa;
  border-radius: 5px;
  color: $color;
  ");
}

if ($semester) {
  echo  ontop($semester, "
  top: 120px;
  left: -10px;
  padding: 5px 8px 5px 15px;
  background: #a4fDaA;
  border-radius: 5px;
  ");
}

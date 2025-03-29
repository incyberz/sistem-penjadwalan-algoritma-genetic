<?php
set_h2('Set Awal PMB');
if (isset($_POST['btn_set_awal_pmb'])) {
  $ta1 = "$_POST[btn_set_awal_pmb]1";
  $ta2 = "$_POST[btn_set_awal_pmb]2";

  $s = "UPDATE tb_ta SET awal_pmb='$_POST[awal_pmb]' WHERE id=$ta1 OR id=$ta2";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  alert('Set Awal PMB sukses.', 'success');
  jsurl();
}

alert('Awal PMB belum ditentukan.');
// petugas_only();


$tahun_ta_berikutnya = $tahun_ta + 1;
$estimasi_awal_pmb = "$tahun_ta-10-01";
echo "
  <div>Tahun TA saat ini: $tahun_ta</div>
  <div>Bulan Awal PMB: $arr_bulan[9]</div>

  <div>Estimasi Awal PMB untuk TA $tahun_ta_berikutnya</div>
  <h3 class='text-center bordered p2 gradasi-kuning mt1 mb4'>1 $arr_bulan[9] $tahun_ta</h3>

  <form method=post class=card>
    <div class='card-header gradasi-toska tengah'>Form Set Awal PMB</div>
    <div class='card-body'>
      <input type=date value='$estimasi_awal_pmb' class='form-control mb2'>
      <button class='btn btn-primary w-100' name=btn_set_awal_pmb value=$tahun_ta>Set Awal PMB</button>
    </div>

  </form>
";

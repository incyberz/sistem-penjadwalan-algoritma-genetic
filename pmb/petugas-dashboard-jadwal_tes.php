<?php
$rUI = [
  'belum_tes' => [
    'title' => 'Peserta Belum Tes',
    'satuan' => 'peserta',
    'href' => '?jadwal_tes',
    'bg' => 'bg-danger',
    'sql' => "SELECT 1 FROM tb_pmb WHERE nomor_peserta is not null AND tanggal_lulus_tes is null AND last_test is null",
  ],
  'mengulang' => [
    'title' => 'Mengulang Tes',
    'satuan' => 'peserta',
    'href' => '?jadwal_tes&mengulang=1',
    'bg' => 'bg-warning',
    'sql' => "SELECT 1 FROM tb_pmb WHERE nomor_peserta is not null AND tanggal_lulus_tes is null AND last_test is not null",
  ],
  'belum_dilaksanakan' => [
    'title' => 'Belum Dilaksanakan',
    'satuan' => 'jadwal',
    'href' => '?jadwal_tes&status=-1',
    'bg' => 'bg-danger',
  ],
  'terlaksana' => [
    'title' => 'Tes Terlaksana',
    'satuan' => 'jadwal',
    'href' => '?jadwal_tes&status=null',
    'bg' => 'bg-success',
  ],
];


$cols = '';
foreach ($rUI as $key => $rv) {
  $count = 0;

  $s = $rv['sql'] ?? '';
  if ($s) {
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $count = mysqli_num_rows($q);
  }

  $bg = $count ? $rv['bg'] : 'bg-secondary';

  $cols .= "
    <div class='col-3'>
      <div class='card $bg mb-3'>
        <div class='card-body'>
          <a href=$rv[href] class=hover>
            <h5 class='card-title'>
              <span class='putih'>$rv[title]</span>
            </h5>
            <div class='card-text f40'>
              <span id='pendaftar-count' class=putih>$count</span>
              <span class='f12 miring putih'>$rv[satuan]</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  ";
}

?>
<div class='d-lg-none'>
  <b class=red>Dashboard Jadwal Tes hanya dapat diakses via laptop, minimal 992 pixel lebar layar.</b>
</div>

<div class='d-none d-lg-block'>
  <div class='card mt4'>
    <div class='card-header bg-info putih tengah'>Jadwal Tes PMB - All Time</div>
    <div class='card-body gradasi-toska putih'>
      <div class='row'>
        <?= $cols ?>
      </div>
    </div>
  </div>
</div>
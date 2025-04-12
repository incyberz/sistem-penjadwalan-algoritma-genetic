<?php
$rUI = [
  'belum_tes' => [
    'title' => 'Belum Tes/Mengulang',
    'satuan' => 'peserta',
    'href' => '?jadwal_tes',
    'bg' => 'bg-danger',
    'sql' => "SELECT 1 FROM tb_pmb WHERE nomor_peserta is not null AND tanggal_lulus_tes is null",
  ],
  'terlaksana' => [
    'title' => 'Tes Terlaksana',
    'satuan' => 'jadwal',
    'href' => '?jadwal_tes&status=null',
    'bg' => 'bg-success',
    'sql' => "SELECT 1 FROM tb_jadwal_tes a WHERE awal < '$today' -- kemarin, dst",
  ],
  'belum_dilaksanakan' => [
    'title' => 'Belum Dilaksanakan',
    'satuan' => 'jadwal',
    'href' => '?jadwal_tes&status=null',
    'bg' => 'bg-danger',
    'sql' => "SELECT 1 FROM tb_jadwal_tes a WHERE awal >= '$today' -- ETA jadwal",
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

  $konten = "
    <h5 class='card-title'>
      <span class='putih'>$rv[title]</span>
    </h5>
    <div class='card-text f40'>
      <span id='pendaftar-count' class=putih>$count</span>
      <span class='f12 miring putih'>$rv[satuan]</span>
    </div>
  ";


  $col_size = 3;
  $cols2 = '';
  if ($key == 'belum_dilaksanakan') {
    $col_size = 6;
    # ============================================================
    # ETA | INFO JADWAL YANG BELUM DILAKSANAKAN 
    # ============================================================
    $s = "SELECT * FROM tb_jadwal_tes a 
    JOIN tb_tes_pmb b ON a.id_tes=b.id
    JOIN tb_jenis_tes c ON b.jenis_tes=c.jenis_tes
    WHERE a.awal >= '$today' -- ETA jadwal";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $li = '';
    while ($d = mysqli_fetch_assoc($q)) {
      $eta = eta2($d['awal']);
      $li .= "
        <li class='d-flex flex-between'>
          <div>$d[title]</div>
          <div>$eta</div>
        </li>
      ";
    }

    $konten = "
      <div class='row'>
        <div class='col-5'>
          $konten
        </div>
        <div class='col-7'>
          <ol class='putih m0 p0 pl3 f14'>$li</ol>
        </div>
      </div>
    ";
  }

  $cols .= "
    <div class='col-$col_size'>
      <div class='card $bg mb-3'>
        <div class='card-body'>
          <a href='?jadwal_tes&status=null' class=hover>
            $konten
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
  <!-- <div class='card mt4'>
    <div class='card-header bg-info putih tengah'>Tes PMB</div>
    <div class='card-body gradasi-toska putih'>
    </div>
  </div> -->
  <div class='row mt2'>
    <?= $cols ?>
  </div>
</div>
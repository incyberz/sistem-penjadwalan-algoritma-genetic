<?php
$rUI = [
  'total_berkas' => [
    'title' => 'Total Berkas',
    'satuan' => 'files',
    'href' => '?berkas',
    'bg' => 'bg-info',
    'kondisi' => '1',
  ],
  'verified' => [
    'title' => 'Berkas Verified',
    'satuan' => 'files',
    'href' => '?berkas&status=1',
    'bg' => 'bg-success',
    'kondisi' => 'a.status=1',
  ],
  'rejected' => [
    'title' => 'Berkas Rejected',
    'satuan' => 'files',
    'href' => '?berkas&status=-1',
    'bg' => 'bg-warning',
    'kondisi' => 'a.status=-1',
  ],
  'unverified' => [
    'title' => 'Perlu Review',
    'satuan' => 'files',
    'href' => '?berkas&status=null',
    'bg' => 'bg-danger',
    'kondisi' => 'a.status is null',
  ],
];


$cols = '';
foreach ($rUI as $key => $rv) {
  $s = "SELECT 1 FROM tb_berkas a 
  JOIN tb_akun b ON a.username=b.username
  WHERE b.tahun_pmb=$tahun_pmb
  AND $rv[kondisi]
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $count = mysqli_num_rows($q);

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
  <b class=red>Dashboard Berkas hanya dapat diakses via laptop, minimal 992 pixel lebar layar.</b>
</div>

<div class='d-none d-lg-block'>
  <!-- <div class='card mt4'>
    <div class='card-header bg-info putih tengah'>Berkas PMB</div>
    <div class='card-body gradasi-toska putih'>
    </div>
  </div> -->
  <div class='row mt4'>
    <?= $cols ?>
  </div>
</div>
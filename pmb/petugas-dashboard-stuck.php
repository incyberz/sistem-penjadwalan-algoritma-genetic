<?php
include '../includes/img_icon.php';
include '../includes/key2kolom.php';

$rstuck = [];

$rstuck_at = [
  'formulir' => [
    'title' => 'Stuck Bayar Formulir',
    'th' => 'Last Daftar',
    'no_data' => "Semua Pendaftar sudah bayar Formulir",
    'sql' => "SELECT 
      a.nama,
      a.created_at as last_date,
      (
        SELECT 1 FROM tb_berkas WHERE username=a.username
        AND jenis_berkas='FORMULIR') sudah_bayar_formulir

      FROM tb_akun a 
      WHERE a.active_status = 1 -- hanya pendaftar aktif
      ORDER BY sudah_bayar_formulir,
      a.created_at 
      LIMIT 3
    ",
  ],
  'berkas' => [
    'title' => 'Stuck Upload Berkas',
    'th' => 'Belum Upload',
    'no_data' => "Semua Peserta sudah Upload Berkas",
    'sql' => "SELECT 
      a.nama,
      b.upload_at as last_date,
      (
        SELECT (jumlah_syarat_berkas - jumlah_upload_berkas) 
        FROM tb_pmb WHERE username=a.username) belum_upload_count

      FROM tb_akun a 
      JOIN tb_berkas b ON a.username=b.username 
      JOIN tb_pmb c ON a.username=c.username 
      WHERE b.jenis_berkas = 'FORMULIR' 
      AND (b.status = 1 OR b.status is null) -- tidak termasuk yang reject 
      AND c.jumlah_syarat_berkas is not null -- sudah masuk tahapan upload berkas 
      AND a.active_status = 1 -- hanya pendaftar aktif
      ORDER BY belum_upload_count DESC,
      b.upload_at 
      LIMIT 3
    ",
  ],
  'registrasi' => [
    'title' => 'Stuck Registrasi Ulang',
    'th' => 'Tanggal Lulus Tes',
    'no_data' => "Semua Peserta Tes saat ini sudah Registrasi Ulang",
    'sql' => "SELECT 
      a.nama,
      b.tanggal_lulus_tes as last_date,
      (
        SELECT 1 FROM tb_berkas WHERE username=a.username
        AND jenis_berkas='REGISTRASI') sudah_registrasi

      FROM tb_akun a 
      JOIN tb_pmb b ON a.username=b.username
      WHERE a.active_status = 1 -- hanya pendaftar aktif
      AND b.tanggal_lulus_tes is not null -- hanya peserta yang lulus
      AND b.tanggal_finish_registrasi is null -- hanya peserta yang belum registrasi
      ORDER BY sudah_registrasi,
      a.created_at 
      LIMIT 3
    ",
  ],
];

foreach ($rstuck_at as $k => $v) {
  $q = mysqli_query($cn, $v['sql']) or die(mysqli_error($cn));

  $rstuck[$k] = '';
  $bg = 'danger';
  if (mysqli_num_rows($q)) {
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $tgl = date('d-M-Y', strtotime($d['last_date']));
      $eta = eta2($d['last_date']);
      $dots = strlen($d['nama']) > 20 ? '...' : '';
      $nama = ucwords(strtolower(substr($d['nama'], 0, 20))) . $dots;
      $info = '';
      if ($k == 'formulir' || $k == 'registrasi') {
        $info = "<div class='f12 abu'>$tgl, <i>$eta</i></div>";
      } elseif ($k == 'berkas') {
        if (!$d['belum_upload_count']) continue;
        $info = "<div class='f12 abu'>$d[belum_upload_count] berkas</div>";
      }
      $rstuck[$k] .= "
        <tr>
          <td>$i</td>
          <td>
            $nama
            $info
          </td>
          <td>
            <button class='btn btn-danger btn-sm'>Follow Up</button>
          </td>
        </tr>
      ";
    }
  } else { // no data
    $bg = 'secondary';
  }


  $rstuck[$k] =  $rstuck[$k] ? "
    <table class=table>
      <thead>
        <th>No</th>
        <th>$v[th]</th>
        <th>Aksi</th>
      </thead>
      $rstuck[$k]
    </table>  
  " : "
    <div class='text-success'>
      $v[no_data] $img_check
    </div>
  ";

  $rstuck[$k] = "
    <div class='col-4'>
      <div class='card'>
        <div class='card-header tengah putih bg-$bg'>$v[title]</div>
        <div class='card-body'>
          $rstuck[$k]
        </div>
      </div>
    </div>
  ";
}


echo "<div class='row mt4'>" . join('', $rstuck) . "</div>";

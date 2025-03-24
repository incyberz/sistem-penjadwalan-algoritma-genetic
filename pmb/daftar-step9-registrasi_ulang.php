<style>
  .blok-info-syarat {
    margin: 0 -15px;
    padding: 15px;
    padding-bottom: 30px;
  }
</style>
<?php
set_title('Registrasi Ulang');
$belum_lengkap = '<i class="f12 red">belum lengkap</i>';


$rsyarat = [
  'verifikasi_akun' => [
    'tb' => 'akun',
    'fields' => [
      'whatsapp_status' => 1,
    ],
  ],
  'pengisian_biodata' => [
    'tb' => 'biodata',
    'fields' => [
      'all' => 1,
    ],
  ],
  'data_sekolah' => [
    'tb' => 'data_sekolah',
    'fields' => [
      'all' => 1,
    ],
  ],
  'data_orangtua' => [
    'tb' => 'data_orangtua',
    'fields' => [
      'all' => 1,
    ],
  ],
  'memilih_jurusan' => [
    'tb' => 'pmb',
    'fields' => [
      'id_prodi' => 1,
    ],
  ],
  'memilih_jalur' => [
    'tb' => 'pmb',
    'fields' => [
      'id_jalur' => 1,
    ],
  ],
  'verifikasi_berkas' => [
    'tb' => 'berkas',
    'fields' => [
      'all' => 1,
    ],
  ],
  'tes_pmb' => [
    'tb' => 'pmb',
    'fields' => [
      'status_lulus' => 1,
    ],
  ],
];

$info_syarats = '';
foreach ($rsyarat as $syarat => $v) {
  $SYARAT = strtoupper(str_replace('_', ' ', $syarat));
  $info_syarats .= "
    <div class='blok-info-syarat border-top gradasi-merah'>
      <b class=f12>$SYARAT</b>: 
      <div><i class='red f12 '>belum memenuhi</i></div>
    </div>
  ";
}

echo "
    <div class='card mb3'>
      <div class='card-header bg-primary putih tengah'>
        Syarat Registrasi
      </div>
      <div class='card-body gradasi-toska'>
        $info_syarats
        <div class='mt4 mb2 text-danger f14 miring'>
          Silahkan penuhi dahulu semua persyaratan Registrasi Ulang.
        </div>
        <button class='btn btn-secondary w-100' disabled>Registrasi Ulang</button>
        <hr/>
        <p class='text-success'>
          Jika semua persyaratan terpenuhi maka Anda akan mendapatkan <b>NIM</b>, <b>Jas Almamater</b>, dan terdaftar sebagai <b>Mahasiswa Baru</b> di Kampus Masoem University.
        </p>
      </div>
    </div>


";

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

echo "
    <div class='card mb3'>
      <div class='card-header bg-primary putih tengah'>
        <h2>Syarat Registrasi:</h2>
      </div>
      <div class='card-body tengah gradasi-toska'><i class=red>--Anda belum ujian--</i></div>
    </div>


";

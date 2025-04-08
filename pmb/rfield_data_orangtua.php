<?php
$rpendidikan = [
  'TS' => 'Tidak Sekolah',
  'SD' => 'SD',
  'SL' => 'SLTP Sederajat',
  'SA' => 'SMA Sederajat',
  'D3' => 'Diploma (D3)',
  'S1' => 'Sarjana (S1)',
  'S2' => 'Magister (S2)',
  'S3' => 'Doktor (S3)',
];
$rpendapatan = [
  0 => 'Tidak berpenghasilan',
  1 => '0 s.d 1jt',
  2 => '1 s.d 2jt',
  3 => '2 s.d 3jt',
  4 => '3jt lebih',
];

$rfield['data_orangtua'] = [
  'ayah_meninggal' => [
    'fields' => [
      0 => 'Ayah masih hidup.',
      1 => 'Ayah sudah meninggal.',
    ]
  ],
  'ibu_meninggal' => [
    'fields' => [
      0 => 'Ibu masih hidup.',
      1 => 'Ibu sudah meninggal.',
    ]
  ],
  'ortu_cerai' => [
    'fields' => [
      0 => 'Ayah dan Ibu berumahtangga.',
      1 => 'Bercerai atau meninggal.',
    ]
  ],
  'tinggal_dengan' => [
    'fields' => [
      0 => 'Ayah dan Ibu',
      1 => 'Ayah',
      2 => 'Ibu',
      3 => 'Wali',
      4 => 'Sendirian',
      5 => 'Di Asrama',
    ]
  ],
  'punya_wali' => [
    'fields' => [
      0 => 'Tidak punya wali.',
      1 => 'Saya punya wali.',
    ]
  ],
];

<?php
$arr_info_tambahan = [
  'ta' => [
    'nama' => "dari \"$min_ta_ganjil\" s.d \"$max_ta_genap\"",
    'awal' => 'biasanya awal September di tahun tersebut',
    'akhir' => 'biasanya di akhir Agustus di tahun berikutnya',
  ],
  'prodi' => [
    'nama' => 'harus unik',
    'singkatan' => 'harus unik',
  ],
  'kurikulum' => [
    'nama' => 'biasanya nama prodi digabung dengan TA',
  ],
  'kelas' => [
    'nama' => 'disarankan dg <b>format:</b> JENJANG-PRODI-ANGKATAN-SEMESTER-COUNTER, <b>misal:</b> S1-SI-2024-SM3-A',
    'kapasitas' => 'biasanya tidak > 40 mhs',
  ],
  'dosen' => [
    'nama' => 'hanya diperbolehkan A-Z (abaikan titik, petik, dll)',
    'whatsapp' => 'awali dengan "628..."',
  ],
  'user' => [
    'nama' => 'hanya diperbolehkan A-Z (abaikan titik, gelar, dll)',
    'username' => 'hanya a-z dan 0-9',
    'whatsapp' => 'awali dengan "628..."',
    'role' => 'pilihan: AKD, KEU, atau PIM',
  ],
  'ruang' => [
    'nama' => 'biasanya dg <b>format:</b> GEDUNG-LANTAI-COUNTER, <b>misal:</b> B-3-01',
    'kapasitas' => 'biasanya max 40 kursi',
    'lokasi' => 'info gedung, lantai, akses, dll',
  ],
];

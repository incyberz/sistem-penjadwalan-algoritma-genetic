<?php
# ============================================================
# KONFIGURASI PENJADWALAN KULIAH
# ============================================================

# ============================================================
# IDENTITAS KAMPUS
# ============================================================
$nama_kampus = 'UNIVERSITAS MA`SOEM';

$rfakultas = [
  'FKOM' => 'Fakultas Komputer',
  'FTEK' => 'Fakultas Teknik',
  'FKIP' => 'Fakultas Keguruan dan Ilmu Pendidikan',
  'FEBI' => 'Fakultas Ekonomi dan Bisnis Syariah',
  'FAPERTA' => 'Fakultas Pertanian',
];

$rrole = [
  'AKD' => 'Staf Akademik',
  'KEU' => 'Staf Keuangan',
  'PIM' => 'Kaprodi / Pimpinan',
];

$rjenjang = [
  'D3' => [
    'title' => 'Diploma III',
    'jumlah_semester' => 6,
  ],
  'S1' => [
    'title' => 'Sarjana',
    'jumlah_semester' => 8,
  ],
];

$rshift = [
  'R' => [
    'title' => 'Reguler',
    'jam_awal' => '7:30',
    'jam_akhir' => '17:00',
  ],
  'NR' => [
    'title' => 'Non Reguler',
    'jam_awal' => '17:20',
    'jam_akhir' => '21:45',
  ],
];



# ============================================================
# TAHUN AJAR
# ============================================================
$min_ta = 2024;
$max_ta = 2030;
$min_ta_ganjil = $min_ta . '1';
$max_ta_genap = $max_ta . '2';

$tahun_ini = date('Y');
$tahun_ini_ganjil = $tahun_ini . '1';
$tahun_ini_genap = $tahun_ini . '2';

$ta_aktif = $_SESSION['jadwal_ta_aktif'] ?? $tahun_ini_ganjil;
$is_ganjil = $ta_aktif % 2 == 0 ? 0 : 1;
$tahun_ta = substr($ta_aktif, 0, 4);
$Gg = $is_ganjil  ? 'Ganjil' : 'Genap';
$GG = strtoupper($Gg);
$default_semester = $ta_aktif % 2 == 0 ? 2 : 1;


# ============================================================
# SENIN PERTAMA PERKULIAHAN
# ============================================================
$senin_pertama = '2025-2-3';
if (date('w', strtotime($senin_pertama)) != 1) die(alert("Weekday Senin Pertama harus bernilai 1 (hari Senin)."));


# ============================================================
# ARRAY HARI + AVAILABLE WEEKDAY
# ============================================================
$weekday_start = 1; // senin
$weekday_end = 5; // jumat
$rhari = [];
for ($i = $weekday_start; $i <= $weekday_end; $i++) {
  $jeda = $i - 1;
  $date = date('Y-m-d', strtotime("+$jeda day", strtotime($senin_pertama)));
  $rhari[$date] = [
    'weekday' => date('w', strtotime($date)),
    'tanggal' => date('d', strtotime($date)),
    'bulan' => date('m', strtotime($date)),
    'tahun' => date('Y', strtotime($date))
  ];
}

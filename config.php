<?php
# ============================================================
# KONFIGURASI PENJADWALAN KULIAH
# ============================================================

# ============================================================
# IDENTITAS KAMPUS
# ============================================================
$nama_kampus = 'UNIVERSITAS MA`SOEM';
$lokasi_titimangsa = 'Cipacing';

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
  'DSN' => 'Dosen',
  'MHS' => 'Mahasiswa',
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
    'nama' => 'Reguler',
    'jam_awal' => '7:30',
    'jam_akhir' => '17:00',
  ],
  'NR' => [
    'nama' => 'Non Reguler',
    'jam_awal' => '17:20',
    'jam_akhir' => '21:45',
  ],
];

# ============================================================
# PETUGAS DEFAULT
# ============================================================
$petugas_default = [
  'nama' => 'Yunita',
  'jabatan' => 'Sekprodi FKom',
  'whatsapp' => '62895338753271',
];
$petugas_default = [
  'nama' => 'Iin Sholihin',
  'jabatan' => 'Developer',
  'whatsapp' => '6287729007318',
];


# ============================================================
# TAHUN AJAR
# ============================================================
$min_ta = 2024;
$max_ta = 2030;
$min_ta_ganjil = $min_ta . '1';
$max_ta_genap = $max_ta . '2';

$tahun_ini = date('Y');
$bulan_ini = intval(date('m'));
$tahun_ini_ganjil = $tahun_ini . '1';
$tahun_ini_genap = $tahun_ini . '2';

$ta_default = $tahun_ini_ganjil; // contoh tahun ini ganjil: 20251
if ($bulan_ini <= 1) $ta_default -= 10; // Jan 2025 masih 20241 = 20251 - 10
if ($bulan_ini <= 7) $ta_default -= 9; // Jul 2025 masih 20242 = 20251 - 9
// if ($bulan_ini > 8){} // mulai ags tahun ini

$ta_aktif = $_SESSION['ta_aktif'] ?? $ta_default;
$is_ganjil = $ta_aktif % 2 == 0 ? 0 : 1;
$tahun_ta = substr($ta_aktif, 0, 4);
$Gg = $is_ganjil  ? 'Ganjil' : 'Genap';
$GG = strtoupper($Gg);
$default_semester = $ta_aktif % 2 == 0 ? 2 : 1; // pertama yg muncul ganjil atau genap
$tahun_akademik = "$tahun_ta-" . ($tahun_ta + 1); // contoh 2024-2025

# ============================================================
# SENIN PERTAMA PERKULIAHAN
# ============================================================
// $senin_pertama = '2025-2-3'; // pertama masuk perkuliahan
// digantikan oleh ta.php

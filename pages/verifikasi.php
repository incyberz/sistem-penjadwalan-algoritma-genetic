<?php
# ============================================================
# GET VARIABLE
# ============================================================
$tb = $_GET['tb'] ?? 'prodi';
$id = $_GET['id'] ?? null;
$get_id = $id;
echo "<span class=hideit id=tb>$tb</span>";

set_h2("Verifikasi $tb", "Tahun Ajar $tahun_ta $Gg");
include 'verifikasi-styles.php';

$syarats = [
  'prodi' => [
    'title' => 'Program Studi',
    'syarat' => [
      'Data Kaprodi' => 'nama kaprodi, whatsapp kaprodi, image profil',
      'Data Prodi' => 'SK Prodi, akreditasi prodi, nomor prodi PDDIKTI, dll',
    ]
  ],
  'kelas' => [
    'title' => 'Kelas Mahasiswa',
    'syarat' => [
      'Label Kelas' => 'yaitu custom penamaan kelas agar mudah diingat , default nya sama dengan nama kelas',
      'Peserta Kelas' => 'terdapat minimal satu orang mahasiswa peserta kelas tersebut',
      'Kosma Kelas' => 'terdapat data kosma kelas dan whatsapp nya',
      'Dosen Wali' => 'data dosen wali dan kegiatan perwalian',
      'Prodi Terverifikasi' => 'prodi induk kelas ini harus sudah terverifikasi',
    ]
  ],
  'dosen' => [
    'title' => 'Data Dosen',
    'syarat' => [
      'Nama Lengkap' => 'nama lengkap dosen sesuai KTP (tanpa gelar)',
      'Gelar Dosen' => 'gelar depan dan gelar belakang, hanya gelar akademik',
      'NIDN' => 'data NIDN/NUPTK dosen, jabatan akademik, dan sertifikasi',
      'Bidang Keilmuan' => 'untuk informasi saat assign dengan MK yang cocok',
      'Whatsapp Dosen' => 'whatsapp dosen yang dapat dihubungi mahasiswa',
    ]
  ],
  'ruang' => [
    'title' => 'Ruangan Perkuliahan',
    'syarat' => [
      'Nama Ruang' => 'singkatan atau nama ruang',
      'Kapasitas' => 'kapasitas menentukan bisa tidaknya Join Kelas',
      'Lokasi' => 'lokasi gedung, lantai, koridor, atau blok',
      'Fasilitas' => 'fasilitas yang ada di ruang tersebut',
      'Kondisi' => 'apakah siap, rusak, atau sedang maintenance',
    ]
  ],
];

$syarat = $syarats[$tb] ?? [];

if ($syarat and !$id) {
  $li = '';
  foreach ($syarat['syarat'] as $item => $desc) {
    $li .= "
      <li>
        <b>$item</b>,
        $desc
      </li>
    ";
  }
  echo "
    <h3>Persyaratan Verifikasi $syarat[title]</h3>
    <p>Agar Status $syarat[title] Terverifikasi harus memenuhi persyaratan:</p>
    <ol>$li</ol>  
  ";
}


$file = "pages/verifikasi-$tb.php";
if (file_exists($file)) {
  include $file;
} else {
  if ($tb == 'st') {
    echo "
      <p class=petunjuk>$img_help Untuk Verifikasi Surat Tugas dapat dilakukan dengan cara:</p>
      <ol>
        <li>
          Masuk Menu <a href='?st'>ST (Surat Tugas)</a>
        </li>
        <li>Pilih salah satu dosen, lalu klik Tombol Next $img_next</li>
        <li>Klik Tombol Verifikasi Surat Tugas</li>
      </ol>
    ";
  } elseif ($tb == 'jadwal') {
    jsurl('?jadwal');
  } elseif ($tb == 'mk') {
    jsurl('?jadwal'); // mk terjadwal
  } else {
    alert("Belum ada informasi [ prosedur ] lainnya untuk proses verifikasi [$tb]");
  }
}

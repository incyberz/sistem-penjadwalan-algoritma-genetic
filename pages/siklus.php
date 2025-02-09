<?php
set_h2('Siklus Akademik', "Siklus Akademik pada Tahun Ajaran $tahun_ta $Gg");

$arr_siklus = [
  'ta-kelas' => [
    'title' => 'Seting Tahun Ajar dan Kelas',
    'deskripsi' => 'Menentukan tahun ajaran yang aktif serta pembagian kelas untuk mahasiswa.',
    'sub' => [
      'Seting Tahun Ajar' => [
        'href' => "?detail&tb=ta&id=$ta_aktif",
        'poin' => [
          'Penentuan awal perkuliahan',
          'Menentukan tanggal mulai dan berakhirnya tahun akademik',
          'Menyesuaikan kalender akademik dengan kebijakan institusi',
        ],
      ],
      'Penentuan Minggu Efektif' => [
        'href' => '?',
        'poin' => [
          'Menetapkan jumlah minggu perkuliahan dalam satu semester',
          'Menyesuaikan dengan kalender akademik dan kebijakan pemerintah',
        ],
      ],
      'Penyesuaian Hari Libur' => [
        'href' => '?',
        'poin' => [
          'Menentukan hari libur nasional dan institusional',
          'Mengatur jadwal pengganti jika ada libur akademik',
        ],
      ],
      'Seting Rombel Kelas' => [
        'href' => '?crud&tb=kelas',
        'poin' => [
          'Menentukan jumlah rombongan belajar untuk tiap prodi tiap semester',
          'Menyesuaikan jumlah rombel berdasarkan daya tampung dan jumlah mahasiswa',
          'Mengelompokkan mahasiswa berdasarkan angkatan atau kurikulum',
        ],
      ],
      'Assign Peserta Mhs' => [
        'href' => '?verifikasi&tb=kelas',
        'poin' => [
          'Assign data mahasiswa yang telah mengisi KRS ke dalam grup kelas',
          'Memastikan mahasiswa mendapatkan kelas sesuai dengan pilihan KRS',
          'Menyesuaikan kelas berdasarkan jumlah peserta dan kapasitas ruangan',
        ],
      ],
    ],
  ],
  'mk-dosen' => [
    'title' => 'Assign MK dan Dosen',
    'deskripsi' => 'Menentukan mata kuliah yang ditawarkan dalam semester berjalan dan mengaitkan dosen pengampu.',
    'sub' => [
      'Penentuan MK Pilihan' => [
        'href' => '?crud&tb=mk',
        'poin' => [
          'Menentukan daftar mata kuliah yang dibuka dalam semester berjalan',
          'Menyesuaikan dengan kurikulum dan kebutuhan mahasiswa',
        ],
      ],
      'Assign Dosen' => [
        'href' => '?struktur_kurikulum',
        'poin' => [
          'Menentukan dosen pengampu untuk setiap mata kuliah',
          'Menyesuaikan beban mengajar dosen dengan SKS yang diampu',
        ],
      ],
      'Validasi SKS' => [
        'href' => '?penugasan_dosen',
        'poin' => [
          'Memverifikasi mata kuliah dan dosen yang telah ditugaskan',
          'Melakukan revisi jika SKS mengajar dosen terlalu banyak/sedikit',
          'Melakukan revisi jika ada kesalahan dalam penugasan',
        ],
      ],
    ],
  ],
  'st-jadwal' => [
    'title' => 'Surat Tugas dan Penjadwalan',
    'deskripsi' => 'Membuat surat tugas mengajar untuk dosen dan menyusun jadwal kuliah setiap kelas.',
    'sub' => [
      'Manage Surat Tugas' => [
        'href' => '?st',
        'poin' => [
          'Verifikasi Surat Tugas per dosen',
          'Print (as PDF) Surat Tugas mengajar untuk tiap dosen',
          'Menentukan jumlah SKS dan mata kuliah yang diampu',
        ],
      ],
      'Penjadwalan' => [
        'href' => '?jadwal',
        'poin' => [
          'Menyusun jadwal kuliah berdasarkan kelas dan dosen',
          'Menyesuaikan dengan ketersediaan ruang dan waktu',
          'Menghindari bentrok jadwal antara mata kuliah dan dosen',
        ],
      ],
      'Finalisasi Jadwal' => [
        'href' => '?jadwal',
        'poin' => [
          'Memastikan jadwal sudah sesuai dengan kapasitas kelas dan ruangan',
          'Melakukan revisi jika ada perubahan mendadak',
        ],
      ],
    ],
  ],
  'me-sesi' => [
    'title' => 'Sesi Kuliah dan Presensi',
    'deskripsi' => 'Menentukan jumlah minggu efektif per semester serta menetapkan sesi perkuliahan.',
    'sub' => [
      'Manage Sesi Perkuliahan' => [
        'href' => '?',
        'poin' => [
          'Menentukan jumlah sesi perkuliahan dalam satu minggu',
          'Menyesuaikan jam awal dan durasi setiap sesi berdasarkan kebijakan akademik',
        ],
      ],
      'Manage Aturan Presensi Mhs' => [
        'href' => '?',
        'poin' => [
          'Menetapkan persentase minimal kehadiran mahasiswa agar dapat mengikuti ujian',
          'Menentukan toleransi keterlambatan mahasiswa dalam sesi perkuliahan',
          'Mengatur metode pencatatan presensi mahasiswa (manual, RFID, QR Code, atau integrasi dengan Learning Management System)',
          'Menyesuaikan status izin, sakit, atau alpa dengan kebijakan akademik',
        ],
      ],
      'Manage Aturan Presensi Dosen' => [
        'href' => '?',
        'poin' => [
          'Menentukan persentase minimal kehadiran dosen dalam mengajar setiap semester',
          'Menetapkan mekanisme pencatatan presensi dosen (manual, RFID, QR Code, atau sistem otomatis berbasis lokasi)',
          'Menyesuaikan kebijakan pengganti sesi kuliah jika dosen berhalangan hadir',
          'Mengatur pelaporan ketidakhadiran dosen kepada pihak akademik',
        ],
      ],
      'Monitoring Presensi Dosen dan Mahasiswa' => [
        'href' => '?',
        'poin' => [
          'Menampilkan daftar presensi mahasiswa dan dosen secara real-time berdasarkan sesi perkuliahan',
          'Menyediakan rekapitulasi kehadiran mahasiswa dan dosen untuk setiap mata kuliah',
          'Memberikan peringatan kepada mahasiswa yang persentase kehadirannya mendekati batas minimal',
          'Menyediakan fitur verifikasi atau koreksi presensi jika terjadi kesalahan pencatatan',
        ],
      ],
      'Monitoring Mhs Bermasalah' => [
        'href' => '?',
        'poin' => [
          'Mendeteksi mahasiswa dengan persentase kehadiran di bawah standar akademik',
          'Mengirimkan notifikasi otomatis kepada mahasiswa yang bermasalah terkait presensi',
          'Menampilkan daftar mahasiswa yang perlu mendapatkan perhatian khusus dari dosen wali atau akademik',
          'Menyediakan fitur eskalasi ke bagian kemahasiswaan untuk tindak lanjut lebih lanjut',
        ],
      ],
    ],
  ],

  'sesi-ujian' => [
    'title' => 'Ujian dan Pelaporan',
    'deskripsi' => 'Jadwal ujian, cetak KHS, dan pelaporan proses akademik.',
    'sub' => [
      'Penjadwalan Ujian' => [
        'href' => '?ZZZ',
        'poin' => [
          'Menyusun jadwal ujian tengah semester (UTS) dan ujian akhir semester (UAS)',
          'Mengatur ketersediaan ruang dan pengawas ujian',
        ],
      ],
      'Input Nilai' => [
        'href' => '?ZZZ',
        'poin' => [
          'Dosen menginput nilai mahasiswa setelah ujian selesai',
          'Memastikan nilai sesuai dengan kebijakan akademik',
        ],
      ],
      'Cetak KHS' => [
        'href' => '?ZZZ',
        'poin' => [
          'Mencetak Kartu Hasil Studi (KHS) mahasiswa',
          'Memberikan akses kepada mahasiswa untuk melihat hasil studi',
        ],
      ],
      'Pelaporan Akademik' => [
        'href' => '?ZZZ',
        'poin' => [
          'Membuat laporan akademik untuk keperluan internal dan eksternal',
          'Melaporkan hasil akademik ke sistem nasional (jika diperlukan)',
        ],
      ],
    ],
  ],
];




$sikluss = '';
$siklus_infos = '';
$i = 0;
foreach ($arr_siklus as $k => $v) {
  $i++;

  $arr = $v['sub'];
  $li = '';
  $j = 0;
  foreach ($arr as $k2 => $v2) {
    $j++;
    $li2 = '';
    foreach ($v2['poin'] as $v3) {
      $li2 .= "<li>$v3</li>";
    }

    $li .= "
      <li>
        <b>
          $i.$j <a target=_blank href='$v2[href]'>$k2 $img_next</a>
        </b>
        <ul>
          $li2
        </ul>
      </li>
    ";
  }


  $sikluss .= "
    <div class='siklus'>
      <div>
        <div class='siklus-no'>$i</div>
        <div class='siklus-title'>$v[title]</div>
      </div>
    </div>
  ";
  $siklus_infos .= "
    <div class='siklus-info wadah gradasi-toska'>
      <div class='siklus-title f30'>$i. $v[title]</div>
      <ul style='list-style:none' class='pl4ZZZ'>
        $li
      </ul>
    </div>
  ";
}
?>
<style>
  .siklus {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: white;
    margin: 15px;
    display: flex;
    align-items: center;
    text-align: center;
    justify-content: center;
    padding: 15px;
    font-size: 14px;
    font-weight: bold;
    box-shadow: 0 0 15px gray;
  }

  .siklus-no {
    font-size: 30px;
  }
</style>
<div class="wadah gradasi-toska flexy flex-center">
  <?= $sikluss ?>
</div>
<?= $siklus_infos ?>
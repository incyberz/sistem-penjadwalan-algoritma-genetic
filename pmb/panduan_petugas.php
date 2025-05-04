<?php
set_title('Panduan Petugas PMB');
require_once '../includes/img_icon.php';
$get_key = $_GET['key'] ?? null;

$rpanduan = [
  'akun' => [
    'title' => 'Mendapatkan Akun Petugas PMB',
    'icon' => '🎯',
    'list' => [
      'Yang dapat menambahkan Akun Petugas PMB adalah Role Admin System',
      'Informasi username dan password akan dikirimkan dari whatsapp Admin ke whatsapp Petugas Baru',
      'Wajib mengubah password jika password masih sama dengan username',
    ],
  ],
  'login' => [
    'title' => 'Login ke Dashboard Petugas',
    'icon' => '🎯',
    'list' => [
      'Login sebagai Petugas menggunakan Akun Petugas',
      'Jika berhasil akan diteruskan ke Dashboard Petugas',
      'Jika lupa password gunakan fitur Reset Password',
      'Fitur Reset Password membutuhkan konfirmasi dari Admin System',
      'Jika Admin System sudah verifikasi reset password maka password Anda adalah sama dengan username',
    ],
  ],
  'dashboard' => [
    'title' => 'Cara Membaca Dashboard',
    'icon' => '📊',
    'list' => [
      'Rekapitulasi Filter Default Dashboard adalah berdasarkan All Time dan <b>Gelombang Aktif</b>',
      'Dashboard awal terdiri dari Navigasi, Info hari ini, Global Counts, dan Grafik Pendaftar Harian',
      '<span class="badge bg-danger">Merah</span> artinya harus segera dikerjakan, wajib ditangani segera',
      '<span class="badge bg-warning">Kuning</span> artinya perhatian, mungkin saja perlu penanganan',
      '<span class="badge bg-primary">Biru</span> artinya rekomendasi, itu adalah petunjuk langkah utama',
      '<span class="badge bg-info">Biru muda</span> artinya hanya sekedar informasi',
      '<span class="badge bg-success">Hijau</span> artinya proses yang sudah berjalan lancar',
      'Untuk melihat grafik lainnya Filter Dashboard harus <b>All Time dan All Gelombang</b>',
      'Terdapat Sebagian Grafik yang tersembunyi, untuk menampilkannya Anda harus klik Link/Button Toggle',
      'Jika Angka pada Grafik di klik maka akan diteruskan ke <b>Page Detail</b> berikutnya',
    ],
  ],
  'berkas' => [
    'title' => 'Penanganan Berkas Pendaftar PMB',
    'icon' => '🎯',
    'list' => [
      'Setiap pendaftar wajib upload berkas wajib dan berkas tambahan',
      'Berkas wajib diantaranya Softcopy KK, KTP, Ijazah/SKL, Foto, dan Bukti Pembayaran',
      'Berkas tambahan disesuaikan dengan Jalur Daftar yang ia pilih. Berkas tambahan diantaranya Scan Raport, Sertifikas Prestasi, SKTM, atau dokumen khusus lainnya',
      "Saat Verifikasi Berkas tugas Anda melakukan check pada:
        <ol class='text-primary'>
          <li>
            Kesesuaian Dokumen $img_check
            <div class='f12 abu mb2'>Apakah gambar yang diupload sama dengan Judul Berkas?</div>
          </li>
          <li>
            Keaslian Dokumen $img_check
            <div class='f12 abu mb2'>Perhatikan gambar! Temukan kemungkinan bahwa gambar itu adalah hasil editan!</div>
          </li>
          <li>
            Kesesuaian Nominal (jika ada) $img_check
            <div class='f12 abu mb2'>Biasanya untuk Bukti Pembayaran</div>
          </li>
          <li>
            Kesesuaian Nomor Berkas (jika ada) $img_check
            <div class='f12 abu mb2'>Semisal Nomor Ijazah, Nomor Sertifikat, atau Nomor Surat</div>
          </li>
          <li>
            Kesesuaian Penanggalan (jika ada) $img_check
            <div class='f12 abu mb2'>Semisal Tanggal Sertifikat, dll</div>
          </li>
        </ol>
         ",
    ],
  ],
  'jadwal' => [
    'title' => 'Manage Jadwal Tes',
    'icon' => '🎯',
    'list' => [
      'Default Jadwal Tes yaitu secara offline dan close-book di ruangan kampus',
      'Jika via online ada kekhawatiran peserta menggunakan teknologi AI untuk membantu menjawab pertanyaannya',
      'Jadwal Tes harus dijadwalkan oleh Petugas pada tanggal saat ini hingga mendatang',
      'Jadwal Tes yang melewati hari ini dianggap sudah dilaksanakan',
      'Jika belum ada jadwal tes maka pendaftar dapat melakukan Notif Request Jadwal Tes',
      'Petugas dapat Custom Konfigurasi Jadwal ke online dan set anytime (kapan saja)',
    ],
  ],
  'tes' => [
    'title' => 'Manage Pelaksanaan Tes',
    'icon' => '🎯',
    'list' => [
      'Default Pelaksanaan Tes di ruangan kampus secara close-book dan offline',
      'Default Soal Tes dapat diakses secara CBT via jaringan LAN (non-internet), atau di lab komputer',
      'Soal Tes dapat di print dan diperiksa secara manual',
    ],
  ],
  'hasil-tes' => [
    'title' => 'Manage Hasil Tes',
    'icon' => '🎯',
    'list' => [
      'Default Pemeriksaan via CBT secara otomatis dan peserta dapat langsung melihat Nilai Tes nya',
      "Nilai tes dapat menentukan kelulusan. Secara default:
        <ol class=''>
          <li class='text-success'>Nilai >= 70
            <div class='f12 mb2'>Dinyatakan Lulus.</div>
          </li>
          <li class='text-danger'>Nilai < 50
            <div class='f12 mb2'>Tidak lulus, peserta dapat mengulang di Jadwal Tes berikutnya dengan maksimal 3 kali kesempatan</div>
          </li>
          <li class='text-warning'>Nilai antara 50 s.d 70
            <div class='f12 mb2'>Kelulusan secara manual oleh Tim Pemeriksa apakah diluluskan atau tidak dengan melihat gabungan dari Nilai Tes lainnya</div>
          </li>
        </ol>
      ",
      'Peserta dianggap <b class=text-success>Lulus Tes</b> jika: semua jenis tes (TPA, TKB, wawancara, dll) dinyatakan lulus',
    ],
  ],
  'follow-up' => [
    'title' => 'Follow Up Stuck Proses Pendaftaran',
    'icon' => '🎯',
    'list' => [
      "Stuck Pendaftaran biasanya terjadi saat:
        <ol class='text-danger'>
          <li>Pendaftar belum Pembayaran Formulir</li>
          <li>Pendaftar belum Melengkapi Berkas Pendaftaran</li>
          <li>Pendaftar belum Pembayaran Registrasi</li>
        </ol>
      ",
      'Proses dianggap <b class=text-danger>Stuck</b> jika melebihi 3 hari dari last activity atau sudah mendekati batas tanggal pembayaran',
      'Daftar Peserta yang dianggap stuck berada di Dashboard Petugas',
      'Petugas PMB wajib melakukan <b class=text-primary>Follow Up</b> terhadap proses PMB yang stuck',
      "Proses Follow Up yaitu:
        <ol class=''>
          <li>Mengirimkan Pesan Notifikasi ke Pendaftar via Whatsapp Gateway</li>
          <li>Set Status Akun (opsional jika whatsapp non-aktif)</li>
          <li>Mengirimkan pesan tambahan untuk kasus lainnya</li>
        </ol>
      ",
    ],
  ],
  'manage-feedback' => [
    'title' => 'Manage Feedback Pendaftar',
    'icon' => '🎯',
    'list' => [
      'Membaca feedback saran dan masukan dari pendaftar terbaru',
      'Set hide (menyembunyikan) saran dan masukan jika mengisi asal-asalan atau tidak layak',
      'Menangani laporan error atau memberikan petunjuk kepada pendaftar tentang laporan kasus ringan',
      'Melaporkan laporan error tentang syntax-error atau system-error lainnya kepada Developer',
    ],
  ],
];

$accordions = '';
$i = 0;
foreach ($rpanduan as $k => $v) {
  $i++;
  $rlist = $v['list'] ?? [];
  $lists = '';
  foreach ($rlist as $key => $list) {
    $lists .= "<li>$list</li>";
  }


  $show = ($k == $get_key || $i == $get_key) ? 'show' : '';
  $accordions .= "
    <div class='accordion-item'>
      <h2 class='accordion-header' id='heading$k'>
        <button
          class='accordion-button'
          type='button'
          data-bs-toggle='collapse'
          data-bs-target='#collapse$k'>
          $v[icon] $i. $v[title]
        </button>
      </h2>
      <div
        id='collapse$k'
        class='accordion-collapse collapse $show'
        data-bs-parent='#panduanPetugas'>
        <div class='accordion-body'>
          <ul>$lists</ul>
        </div>
      </div>
    </div>  
  ";
}
?>

<h1 class="mb-4">Panduan Petugas PMB</h1>

<div class="accordion" id="panduanPetugas">
  <?= $accordions ?>
</div>
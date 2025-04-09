<?php
session_start();
include '../includes/jsurl.php';
// include '../includes/eta.php';
include '../includes/alert.php';
include '../includes/img_icon.php';
$session_username = $_SESSION['pmb_username'] ?? null;
$get_username = $_GET['username'] ?? null;
$role = $_SESSION['pmb_role'] ?? null;
if ($role == 'petugas' and !$get_username) die('undefined index [username] for Petugas.');
$is_login = $session_username ? true : false;
if (!$session_username and !$get_username) {
  echo ('<b style=color:red>Undefined index [username].</b>');
  jsurl('./?', 3000);
}
$incomplete = false;

$username = $session_username ?? $get_username; // dahulukan username sendiri
$undefined = '<b style=color:red>undefined</b>';
$img_reject = img_icon('reject');

include '../conn.php';
include 'tahun_pmb.php';

$s = "SELECT *,
a.nama as nama_pendaftar,
f.nama as prodi,
g.nama_jalur as jalur,
b.tanggal_finish_registrasi as finish_registrasi,
a.created_at as mulai_pmb

FROM tb_akun a 
JOIN tb_pmb b ON a.username=b.username 
-- JOIN tb_biodata c ON a.username=c.username 
-- JOIN tb_data_sekolah d ON a.username=d.username 
-- JOIN tb_data_orangtua e ON a.username=e.username 
JOIN tb_prodi f ON b.id_prodi=f.id 
JOIN tb_jalur g ON b.id_jalur=g.id 
WHERE a.username = '$username'
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$pmb = mysqli_fetch_assoc($q);
if (!$pmb) {
  echo ('<h1 style="color:red; margin:20px; padding:20px; text-align:center">Data PMB invalid.</h1>');
  jsurl('./?', 5000);
  exit;
}

$jumlah_verif = $pmb['jumlah_verifikasi_berkas'];
$jumlah_syarat = $pmb['jumlah_syarat_berkas'];


$arr = explode('?', $_SERVER['REQUEST_URI']);
$nama_server_full = "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]$arr[0]";
$nama_server = str_replace('cetak_pmb.php', '', $nama_server_full);
$url = "$nama_server_full?username=$pmb[username]";

$img_src = "../assets/img/icon/peserta.png";
$nama = ucwords(strtolower($pmb['nama_pendaftar']));
$mulai_pmb = date('d-M-Y', strtotime($pmb['mulai_pmb']));

if ($is_login) {
  # ============================================================
  # FOTO
  # ============================================================
  $s = "SELECT a.file FROM tb_berkas a WHERE username = '$username' AND jenis_berkas='FOTO'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) die(alert('Data Foto tidak ditemukan'));
  $d = mysqli_fetch_assoc($q);
  $file = $d['file'];
  $img_src = "uploads/berkas/$file";
}


if ($pmb['finish_registrasi']) {
  $finish_show = date('d-M-Y H:i', strtotime($pmb['finish_registrasi']));
  $mulai_pmb_show = "$mulai_pmb s.d $finish_show";
  $durasi = strtotime($pmb['finish_registrasi']) - strtotime($pmb['mulai_pmb']);
  $dd = ceil($durasi / (3600 * 24));
  $jj = ceil(($durasi % (3600 * 24)) / 3600);
  $mm = ceil($durasi % 3600 / 60);
  $ss = $durasi % 60;
  $finish_dalam = "<span class=consolas>$dd<i>d</i>:$jj<i>h</i>:$mm<i>m</i>:$ss<i>s</i></span>";
} else {
  $incomplete = 1;
  $mulai_pmb_show = $undefined;
  $finish_dalam = $undefined;
}


$nims = $pmb['nim_sementara'] ? $pmb['nim_sementara'] : '<b style=color:red>belum punya</b>';
$qr_title = $is_login ? '' : "
    <div class='f40'>QR CODE VERIFICATION SYSTEM</div>
    <style>.kertas{margin: 30px auto !important}</style>
  ";

$blok_pmb = "
  <div class='card mb3'>
    <div class='card-header bg-success text-white tengah'>
      $qr_title
      <div class='f30'>HASIL PENERIMAAN MAHASISWA BARU $tahun_pmb UNIVERSITAS ANDA BANDUNG</div>
    </div>
    <div class=card-body>
      <div class='d-flex gap-4 py-2'>
        <div class='px-4'>
          <img src='$img_src' class='img-profil'>
        </div>
        <div>
          <h2 class='f24 bold'>$nama</h2>
          <div><i>NIM-S</i>: <span class=''>$nims</span></div>
          <ul class='text-success pl3 pt1 m0'>
            <li><b>Program Studi</b>: $pmb[prodi]</li>
            <li><b>Jalur Daftar</b>: $pmb[jalur]</li>
            <li><b>Mulai PMB</b>: $mulai_pmb_show</li>
            <li><b>Finish Dalam</b>: $finish_dalam</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
";

# ============================================================
# UI FOR ALL
# ============================================================
$card_header_class = '';
$card_body_class = '';














if ($is_login) {
  $header = "  
    <h1 class='pt2'>CETAK PMB</h1>
    <div class='abu mb4'>Kertas A4, margin: 1-1-1-1</div>
  ";

  # ============================================================
  # BIODATA
  # ============================================================
  $s = "SELECT * FROM tb_biodata a WHERE a.username = '$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $bio = mysqli_fetch_assoc($q);

  $ttl = ucwords(strtolower($bio['tempat_lahir'])) . ', ' . date('d-M-Y', strtotime($bio['tanggal_lahir']));
  $alamat = ucwords(strtolower($bio['blok_dusun']))
    . ' RT. ' . sprintf('%03d', $bio['rt'])
    . ' RW. ' . sprintf('%02d', $bio['rw'])
    . ' Desa. ' . ucwords(strtolower($bio['desa']))
    . ' Kec. ' . ucwords(strtolower($bio['kecamatan']))
    . '  ' . ucwords(strtolower($bio['kabupaten']))
    . ' Prov. ' . ucwords(strtolower($bio['provinsi']))
    . ', Kode Pos. ' . ucwords(strtolower($bio['kode_pos']));

  $rgender = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
  $ragama = ['0' => 'Islam', '1' => 'Kristen', '2' => 'Lainnya'];
  $rwarga_negara = ['0' => 'Indonesia', '1' => 'Asing'];
  $suku = ucwords(strtolower($bio['suku']));

  $rcacat_fisik = [0 => 'Tidak', 1 => 'Ya'];
  $rsudah_bekerja = [0 => 'Belum Bekerja', 1 => 'Sudah Bekerja'];
  $rpunya_usaha = [0 => 'Belum Punya', 1 => 'Usahawan'];

  $blok_bio = "
    <div class='card mb3'>
      <div class='card-header bg-success text-white tengah $card_header_class'>BIODATA</div>
      <div class='card-body $card_body_class'>
        <div class=row>
          <div class=col-6>
            <ul class='pl3'>
              <li><b>Nomor KTP</b>: $bio[nomor_ktp]</li>
              <li><b>TTL</b>: $ttl</li>
              <li><b>Alamat</b>: $alamat</li>
              <li><b>Pekerjaan</b>: " . $rsudah_bekerja[$bio['sudah_bekerja']] . "</li>
              <li><b>Wirausaha</b>: " . $rpunya_usaha[$bio['punya_usaha']] . "</li>
            </ul>
          </div>
          <div class=col-6>
            <ul class='pl3'>
              <li><b>Gender</b>: " . $rgender[$bio['gender']] . "</li>
              <li><b>Agama</b>: " . $ragama[$bio['agama']] . "</li>
              <li><b>Warga Negara</b>: " . $rwarga_negara[$bio['warga_negara']] . "</li>
              <li><b>Suku</b>: $suku</li>
              <li><b>Anak ke</b>: $bio[anak_ke] dari $bio[total_saudara] saudara</li>
              <li><b>Disabilitas</b>: " . $rcacat_fisik[$bio['cacat_fisik']] . "</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  ";







  # ============================================================
  # DATA SEKOLAH
  # ============================================================
  $tb = 'data_sekolah';
  include "rfield_$tb.php"; // shared array fields
  $s = "SELECT * FROM tb_$tb a WHERE a.username = '$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $sekolah = mysqli_fetch_assoc($q);
  $tr_sekolah = '';

  $jenis_sekolah = $rjenis_sekolah[$sekolah['jenis_sekolah']];
  $sekolah_negeri = $rsekolah_negeri[$sekolah['sekolah_negeri']];
  $alamat_sekolah = ucwords(strtolower($sekolah['alamat_sekolah']));
  $kecamatan_sekolah = ucwords(strtolower($sekolah['kecamatan_sekolah']));

  $blok_sekolah = "
    <div class='card mb3'>
      <div class='card-header bg-success text-white tengah $card_header_class'>SEKOLAH ASAL</div>
      <div class='card-body $card_body_class'>
        <ul class='pl3'>
          <li><b>Sekolah</b>: $sekolah[nama_sekolah], $jenis_sekolah, $sekolah_negeri</li>
          <li><b>Alamat</b>: $alamat_sekolah, Kec. $kecamatan_sekolah </li>
          <li><b>Jurusan</b>: $sekolah[jurusan]</li>
          <li><b>Tahun Lulus</b>: $sekolah[tahun_lulus]</li>
          <li><b>No Ijazah</b>: $sekolah[no_ijazah]</li>
          <li><b>NIS</b>: $sekolah[nis]</li>
        </ul>
      </div>
    </div>
  ";












  # ============================================================
  # DATA ORANGTUA
  # ============================================================
  $tb = 'data_orangtua';
  include "rfield_$tb.php"; // shared array fields

  $s = "SELECT * FROM tb_$tb a WHERE a.username = '$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $ortu = mysqli_fetch_assoc($q);

  $nama_ayah = ucwords(strtolower($ortu['nama_ayah']));
  $nama_ibu = ucwords(strtolower($ortu['nama_ibu']));
  $info_ayah_meninggal = $ortu['ayah_meninggal'] ? '<i>(meninggal)</i>' : null;
  $info_ibu_meninggal = $ortu['ibu_meninggal'] ? '<i>(meninggal)</i>' : null;
  $tinggal_dengan = $rfield[$tb]['tinggal_dengan']['fields'][$ortu['tinggal_dengan']];

  # ============================================================
  # AYAH
  # ============================================================
  $li_ayah = ''; // hide data ayah jika meninggal
  if (!$ortu['ayah_meninggal']) {
    $pendapatan_ayah = $rpendapatan[$ortu['pendapatan_ayah']];
    $li_ayah = "<li><b>Pendidikan</b>: $ortu[pendidikan_ayah], $ortu[pekerjaan_ayah]</li>";
    $li_ayah .= "<li><b>Pendapatan ayah</b>: $pendapatan_ayah</li>";
  }

  # ============================================================
  # IBU
  # ============================================================
  $li_ibu = ''; // hide data ibu jika meninggal
  if (!$ortu['ibu_meninggal']) {
    $pendapatan_ibu = $rpendapatan[$ortu['pendapatan_ibu']];
    $li_ibu = "<li><b>Pendidikan</b>: $ortu[pendidikan_ibu], $ortu[pekerjaan_ibu]</li>";
    $li_ibu .= "<li><b>Pendapatan ibu</b>: $pendapatan_ibu</li>";
  }

  # ============================================================
  # WALI
  # ============================================================
  if ($ortu['punya_wali'] || $ortu['tinggal_dengan'] == 3) {
    $nama_wali = ucwords(strtolower($ortu['nama_wali']));
    $hubungan_dg_wali = ucwords(strtolower($ortu['hubungan_dg_wali']));
    $pendapatan_wali = $rpendapatan[$ortu['pendapatan_wali']];
    $li_wali = "<li><b>Nama wali</b>: $nama_wali, $ortu[pendidikan_wali], $ortu[pekerjaan_wali]</li>";
    $li_wali .= "<li><b>Hubungan dg wali</b>: $hubungan_dg_wali</li>";
    $li_wali .= "<li><b>Pendapatan wali</b>: $pendapatan_wali</li>";
  } else {
    $li_wali = "<li><b>Nama wali</b>: <i>tidak punya</i></li>";
  }

  $blok_ortu = "
    <div class='card mb3'>
      <div class='card-header bg-success text-white tengah $card_header_class'>DATA ORANGTUA</div>
      <div class='card-body $card_body_class'>
        <ul class='pl3'>
          <li><b>Ayah</b>: $nama_ayah $info_ayah_meninggal</li>
          $li_ayah
          <li><b>Ibu</b>: $nama_ibu $info_ibu_meninggal</li>
          $li_ibu
          <li><b>Tinggal dengan</b>: $tinggal_dengan</li>
          $li_wali
        </ul>
      </div>
    </div>
  ";


  # ============================================================
  # BERKAS
  # ============================================================
  $s = "SELECT * FROM tb_berkas a 
  JOIN tb_jenis_berkas b ON a.jenis_berkas=b.jenis_berkas 
  WHERE username='$username' 
  ORDER BY b.nomor
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $li_berkas = '';
  while ($d = mysqli_fetch_assoc($q)) {
    if ($d['status'] == 1 and $d['verif_date'] and $d['verif_by']) {
      $info_berkas = "<i class='f12 text-success'>verified $img_check</i>";
    } else {
      $incomplete = 1;
      $info_berkas = "<i class='f12 text-danger'>unverified $img_warning</i>";
    }
    $li_berkas .= "<li><b>$d[nama_berkas]</b>: $info_berkas</li>";
  }

  $blok_berkas = "
    <div class='card mb3'>
      <div class='card-header bg-success text-white tengah $card_header_class'>BERKAS</div>
      <div class='card-body $card_body_class'>
        <ul class='pl3'>
          $li_berkas
        </ul>
      </div>
    </div>
  ";

  $footer = "
    <button class='btn btn-primary mt4 mb2' id=btn_cetak id=btn_cetak>Cetak PMB</button>
    <div class='abu mb4'>Jika ingin mencetak ke PDF, silahkan pilih printer: Microsoft Print to PDF atau Virtual PDF Printer lainnya.</div>

  ";
  $qr_result = '';
?>
  <script src="../../assets/vendor/jquery/jquery-3.7.1.min.js"></script>
  <script>
    $(function() {
      $('#btn_cetak').click(function() {
        $('.unprint').hide();
        window.print();
      })
    })
  </script>
<?php
} else { // not login | public
  $header = '';
  $footer = '';

  $RBlok = [];
  $rblok = [
    'biodata' => [
      'title' => 'BIODATA',
      'value' => $pmb['persen_biodata'],
    ],
    'data_sekolah' => [
      'title' => 'DATA SEKOLAH',
      'value' => $pmb['persen_data_sekolah'],
    ],
    'data_orangtua' => [
      'title' => 'DATA ORANGTUA',
      'value' => $pmb['persen_data_orangtua'],
    ],
    'berkas' => [
      'title' => 'BERKAS PMB',
      'value' => "$jumlah_verif of $jumlah_syarat",
    ],
  ];
  foreach ($rblok as $k => $v) {
    if ($k == 'berkas') {
      if ($jumlah_syarat == $jumlah_verif) {
        $icon = $img_check;
        $success = 'success';
      } else {
        $incomplete = 1;
        $icon = $img_reject;
        $success = 'danger';
      }
      $isi = "<span class='text-$success'><b>Verifikasi Berkas</b>: $v[value] berkas $icon</span>";
    } else {
      if ($v['value'] == 100) {
        $icon = $img_check;
        $success = 'success';
      } else {
        $incomplete = 1;
        $icon = $img_reject;
        $success = 'danger';
      }
      $val = $v['value'] ?? 0;
      $isi = "<span class='text-$success'><b>Pengisian</b>: $val% $icon</span>";
    }
    $RBlok[$k] = "
      <div class='card mb3'>
        <div class='card-header bg-success text-white tengah $card_header_class'>$v[title]</div>
        <div class='card-body tengah $card_body_class'>
          $isi
        </div>
      </div>
    ";
  } // end foreach

  $blok_bio = $RBlok['biodata'];
  $blok_sekolah = $RBlok['data_sekolah'];
  $blok_ortu = $RBlok['data_orangtua'];
  $blok_berkas = $RBlok['berkas'];

  # ============================================================
  # QR RESULT
  # ============================================================
  if ($incomplete) {
    $qr_result = "
      <hr>
      <div class='tengah red bold f30'>PROSES PMB INCOMPLETE ...</div>
      <hr>
    ";
  } else {
    $qr_result = "
      <hr>
      <div class='tengah text-success bold f30'>DOKUMEN INI TERVERIFIKASI $img_check</div>
      <hr>
    ";
  }
} // end not login




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak PMB - <?= $nama ?></title>
  <link rel="stylesheet" href="../assets/css/insho_styles_min.css">
  <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="cetak_pmb.css">
  <?php include 'dashboard_pendaftar-styles.php'; ?>
</head>

<body>
  <div class="unprint">
    <?= $header ?>
    <div class="d-md-none alert alert-danger my-4 bold text-danger">Sebaiknya Anda mengakses page ini via tablet atau laptop karena banyak elemen yang terpotong.</div>
  </div>
  <div class="kertas">
    <?= $blok_pmb ?>
    <?= $blok_bio ?>
    <div class="row">
      <div class="col-6">
        <?= $blok_sekolah ?>
        <?= $blok_berkas ?>
      </div>
      <div class="col-6">
        <?= $blok_ortu ?>
      </div>
    </div>
    <?= $qr_result ?>
    <?php if ($is_login) { ?>
      <div class='qr-code'>
        <div class=qr-code-header>Verified by System</div>
        <?php
        require_once '../includes/qrcode.php';
        $qr = QRCode::getMinimumQRCode($url, QR_ERROR_CORRECT_LEVEL_L);
        $qr->printHTML('4px');
        ?>
      </div>
    <?php } ?>
    <div class="kertas-footer">
      <b>Data From</b>: Smart PMB System, <?= date('F d, Y, H:i') ?>, by: Al-Baiti Coding, 2025
      <br>
      <a href="<?= $nama_server ?>"><?= $nama_server ?></a>
    </div>
  </div>
  <div class="unprint">
    <?= $footer ?>
  </div>
</body>

</html>
<style>
  .blok-info-syarat {
    margin: 0 -15px;
    padding: 15px;
    padding-bottom: 30px;
  }
</style>
<?php
set_title('Registrasi Ulang');
include '../includes/img_icon.php';
include '../includes/script_btn_aksi.php';

$belum_lengkap = '<i class="f12 red">belum lengkap</i>';
$belum_memenuhi = '<i class="f12 red">belum memenuhi</i>';
$unverified = '<i class="f12 red">unverified</i>';
$verified = "<i class='f12 green'>verified $img_check</i>";

$img_reject = img_icon('reject');
$belum_upload = "<i class='f12 red'>Belum upload Bukti Pembayaran Registrasi Ulang $img_warning</i>";
$belum_diverifikasi = '<i class="red f14">menunggu verifikasi oleh Petugas.</i>';
$image_missing = '<i class="red f14">berkas hilang</i>';
$terverifikasi = "<i class='green f14'>terverifikasi $img_check</i>";
$berkas_ditolak = "<b class='red f14'>$img_reject Berkas ditolak karena tidak sesuai dg ketentuan. Silahkan Replace!</b>";
$path = 'uploads/berkas';
$sudah_upload = false;
$sudah_terverifikasi = false;
$ditolak = false;
$jumlah_ok = 0;


$prodi_terpilih = $pmb['prodi_terpilih'] ? "<div>$pmb[prodi_terpilih]</div>" : '';
$jalur_terpilih = $pmb['jalur_terpilih'] ? "<div>Jalur: $pmb[jalur_terpilih]</div>" : '';

$tbs = ['biodata', 'data_sekolah', 'data_orangtua'];
foreach ($tbs as $tb) {
  include 'cek_kelengkapan_fields.php';
  $s = "UPDATE tb_pmb SET persen_$tb = $persen WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}

// if ($pmb)

$rsyarat = [
  'verifikasi_akun' => [
    'step' => 2,
    'data' => $akun['whatsapp_status'],
    'ok_value' => 1,
    'show_ok' => $verified,
    'show_not_ok' => $unverified,
  ],
  'pengisian_biodata' => [
    'step' => 3,
    'data' => $pmb['persen_biodata'],
    'ok_value' => 100,
    'show_ok' => "<div class='f12 green'>Pengisian 100% $img_check</div>",
    'show_not_ok' => $belum_lengkap,
  ],
  'data_sekolah' => [
    'step' => 4,
    'data' => $pmb['persen_data_sekolah'],
    'ok_value' => 100,
    'show_ok' => "<div class='f12 green'>Pengisian 100% $img_check</div>",
    'show_not_ok' => $belum_lengkap,
  ],
  'data_orangtua' => [
    'step' => 5,
    'data' => $pmb['persen_data_orangtua'],
    'ok_value' => 100,
    'show_ok' => "<div class='f12 green'>Pengisian 100% $img_check</div>",
    'show_not_ok' => $belum_lengkap,
  ],
  'memilih_jurusan' => [
    'step' => 6,
    'data' => $pmb['id_prodi'],
    'ok_value' => 'not_null',
    'show_ok' => "$prodi_terpilih $verified",
    'show_not_ok' => $unverified,
  ],
  'memilih_jalur' => [
    'step' => 7,
    'data' => $pmb['id_jalur'],
    'ok_value' => 'not_null',
    'show_ok' => "$jalur_terpilih $verified",
    'show_not_ok' => $unverified,
  ],
  'verifikasi_berkas' => [
    'step' => 7,
  ],
  'tes_pmb' => [
    'step' => 8,
    'data' => $pmb['tanggal_lulus_tes'],
    'ok_value' => 'not_null',
    'show_ok' => "<div class='f12 green'>Selamat! Anda Lulus tes PMB $img_check</div>",
    'show_not_ok' => '<i class="f12 darkred">belum tes</i>',
  ],
  'pembayaran_registrasi_ulang' => [
    'step' => 9,
  ],
];

$info_syarats = '';
foreach ($rsyarat as $syarat => $v) {
  $SYARAT = strtoupper(str_replace('_', ' ', $syarat));
  $status = $belum_memenuhi;

  $ok_value = $v['ok_value'] ?? null;
  $merah = 'merah';

  if ($ok_value) {
    $kondisi = $ok_value == 'not_null' ? $v['data'] : $v['data'] == $ok_value;
    if ($kondisi) {
      if ($v['data']) {
        $status = $v['show_ok'];
        $merah = 'hijau';
        $jumlah_ok++;
      } else {
        $status = $v['show_not_ok'];
      }
    }
  } elseif ($syarat == 'verifikasi_berkas') {

    // $pmb['jumlah_verifikasi_berkas'] = 4;

    $jumlah_syarat_berkas = $pmb['jumlah_syarat_berkas'];
    $jumlah_upload_berkas = $pmb['jumlah_upload_berkas'];
    $jumlah_verifikasi_berkas = $pmb['jumlah_verifikasi_berkas'];

    $is_check_upload = $jumlah_upload_berkas == $jumlah_syarat_berkas ? $img_check : $img_warning;
    $is_check_verifikasi = $jumlah_verifikasi_berkas == $jumlah_syarat_berkas ? $img_check : $img_warning;

    $status = "
      <ul class='f12'>
        <li>
          <b>Upload Berkas</b>: $jumlah_upload_berkas of $jumlah_syarat_berkas $is_check_upload
        </li>
        <li>
          <b>Verifikasi Berkas</b>: $jumlah_verifikasi_berkas of $jumlah_syarat_berkas $is_check_verifikasi
        </li>
      </ul>
    ";

    if ($jumlah_upload_berkas == $jumlah_syarat_berkas and $jumlah_verifikasi_berkas == $jumlah_syarat_berkas) {
      $merah = 'hijau';
      $jumlah_ok++;
    }
  } elseif ($syarat == 'pembayaran_registrasi_ulang') {
    $jenis_berkas = 'REGISTRASI';
    $status = '';
    $hideit_info = '';
    $sudah_bayar_RU = 0;

    $s = "SELECT * FROM tb_berkas WHERE username='$username' AND jenis_berkas = '$jenis_berkas'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $img_berkas = $belum_upload;
    $merah = 'merah';
    $btn_upload = "<button class='btn btn-primary' name=btn_upload_berkas value=$jenis_berkas>Upload</button>";

    if (mysqli_num_rows($q) > 1) die(alert("Dual berkas detected. username [$username], jenis_berkas: [$jenis_berkas] "));
    if (mysqli_num_rows($q)) {
      $d = mysqli_fetch_assoc($q);
      $file = $d['file'];
      $src = "$path/$file";
      if (file_exists($src)) {
        $merah = 'hijau';
        $jumlah_ok++;
        $status_berkas_show = $belum_diverifikasi;
        $sudah_upload = 1;
        $hideit_info = 'hideit';
        if ($d['status'] == 1) {
          $status_berkas_show = $terverifikasi;
          $sudah_terverifikasi = 1;
        }
        if ($d['status'] == -1) {
          $merah = 'merah';
          $status_berkas_show = "
            $berkas_ditolak
            <hr>
            <div class=red><b>alasan</b>: $d[alasan_reject]</div>
            <hr>
          ";
          $ditolak = 1;
        }
        $btn_upload = "<button class='d-block btn btn-sm btn-secondary' name=btn_replace_berkas value='$jenis_berkas--$src' onclick='return confirm(`Replace berkas?`)'>Replace</button>";
        $img_berkas = "
          <div class='mt2'>
            <a href='$src' target=_blank>
              <img class='img-fluid' src='$src'>
            </a>
            <div class='mt2 f14 mb4'>Status Berkas: $status_berkas_show</div>
          </div>
        ";
      } else {
        $img_berkas = $image_missing;
      }
    }


    if ($sudah_bayar_RU) {
      $status = "
        <ul>
          <li class='f12 green'>Sudah Upload Bukti Pembayaran Registrasi Ulang. $img_check</li>
          <li class='f12 darkred'>Sedang menunggu Verifikasi Pembayaran. $img_warning</li>
        </ul>
      ";
    }

    include '../includes/eta.php';
    include 'gelombang_aktif.php';

    $biaya_registrasi_ulang_show = number_format($pmb['biaya_registrasi_ulang']);
    $diskon = $gelombang['diskon_registrasi_ulang'] ?? 0;
    $nominal_transfer = (ceil($pmb['biaya_registrasi_ulang'] * (100 - $diskon) / 100000) * 1000) + $last_digit_whatsapp;
    $nominal_transfer_show = number_format($nominal_transfer);
    $diskon_show = $gelombang['diskon_registrasi_ulang'] ?? 0;

    $form_upload = $sudah_terverifikasi ? '' : "
      <form method=post enctype=multipart/form-data class='card mt2'>
        <div class='card-header tengah'>Form Upload Bukti Bayar Registrasi Ulang</div>
        <div class='card-body'>
          <div class='mb3'>
            <div class='f12 mb1'>Nominal yang Anda bayarkan:</div>
            <input type=number name=nominal required class='form-control' min=100000 max=100000000>
          </div>
          <div class='f12 mb1'>Bukti Transfer (Bukti Bayar):</div>
          <div class='d-flex gap-2'>
            <input type=file name=file accept=.jpg required class='form-control d-block flex-fill'>
            $btn_upload
          </div>
          <div class='f12 abu miring mb3'>)* ekstensi JPG</div>

          <div class=f14>
            <div class='btn-aksi' id=info_pembayaran--toggle>Info Pembayaran:</div>
            <ul class='f14 m0 $hideit_info' id=info_pembayaran >
              <li><b>Gelombang</b>: $gelombang[nomor] - $tahun_pmb</li>
              <li><b>Biaya Registrasi</b>: Rp $biaya_registrasi_ulang_show,-</li>
              <li><b>Diskon Gelombang</b>: $diskon_show%</li>
              <li><b>Whatsapp</b>: <span class=consolas>...$last_digit_whatsapp</span></li>
              <li class='darkblue mt2 mb2'><b>Nominal Transfer</b>: <div class=f24>Rp $nominal_transfer_show,-</div></li>
              <li><b>No. Rekening</b>: <span class='f20 darkblue consolas'>$pmb[no_rek]</span></li>
              <li><b>Bank</b>: $pmb[bank]</li>
              <li><b>Atas nama</b>: $pmb[atas_nama]</li>
              <li><b>Batas Akhir</b>: $batas_akhir_show</li>
              <li><b>ETA</b>: $eta_gelombang</li>
            </ul>
          </div>
        </div>

      </form>
    ";

    $status .= "
      $img_berkas
      $form_upload
    ";
  }

  $info_syarats .= "
    <div class='blok-info-syarat border-top gradasi-$merah'>
      <a href='./?daftar&step=$v[step]'><b class=f12>$SYARAT</b></a>: 
      <div>$status</div>
    </div>
  ";
}

$btn_reg = $jumlah_ok == count($rsyarat) ? "
  <div class='alert alert-success mt4 tengah'>Semua Data dan Berkas sudah lengkap.</div>
  <form method=post class='mt2'>
    <div class=mb3>
      <label class=d-block>
        <input required type=checkbox>
        Saya sudah melunasi seluruh pembayaran PMB
      </label>
      <label class=d-block>
        <input required type=checkbox>
        Pilihan Prodi dan Jalur yang saya pilih telah benar
      </label>
      <label class=d-block>
        <input required type=checkbox>
        Saya mengisi seluruh data sesuai dokumen fisik
      </label>
      <label class=d-block>
        <input required type=checkbox>
        Saya bersedia diminta berkas fisik (copy asli)
      </label>
    </div>
    <button class='btn btn-primary w-100' name=btn_finish_registrasi>Finish Registrasi</button>
  </form>  
" : "
  <div class='mt4 mb2 text-danger f14 miring'>
    Silahkan penuhi dahulu semua persyaratan Registrasi Ulang.
  </div>
  <button class='btn btn-secondary w-100' disabled>Registrasi Ulang</button>
";

echo "
    <div class='card mb3'>
      <div class='card-header bg-primary putih tengah'>
        Syarat Registrasi
      </div>
      <div class='card-body gradasi-toska'>
        $info_syarats
        $btn_reg
        <hr/>
        <p class='text-success'>
          Jika semua persyaratan terpenuhi maka Anda akan mendapatkan <b>NIM</b>, <b>Jas Almamater</b>, dan terdaftar sebagai <b>Mahasiswa Baru</b> di Kampus Masoem University.
        </p>
      </div>
    </div>


";

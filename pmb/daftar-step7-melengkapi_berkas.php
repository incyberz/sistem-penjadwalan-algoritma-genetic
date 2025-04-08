<?php
set_title('Melengkapi Biodata');


include '../includes/eta.php';
include '../includes/img_icon.php';
$img_reject = img_icon('reject');
$belum_upload = '<i class="red f14">belum upload</i>';
$belum_diverifikasi = '<i class="red f14">menunggu verifikasi oleh Petugas.</i>';
$image_missing = '<i class="red f14">berkas hilang</i>';
$terverifikasi = "<i class='green f14'>terverifikasi oleh Petugas $img_check</i>";
$ditolak = "<b class='red f14'>$img_reject Berkas ditolak karena tidak sesuai dg ketentuan. Silahkan Replace!</b>";
$path = 'uploads/berkas';
$jumlah_upload_berkas = 0;
$jumlah_verifikasi_berkas = 0;
$jumlah_reject = 0;
$jumlah_syarat_berkas = 0;


# ============================================================
# DATA BERKAS 
# ============================================================
$s = "SELECT * FROM tb_berkas WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_berkas = mysqli_num_rows($q);
$rberkas = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rberkas[$d['id']] = $d;
}

$title = $rstep[$get_step];


# ============================================================
# GELOMBANG AKTIF
# ============================================================
include 'gelombang_aktif.php';

if ($pmb['id_jalur']) {

  # ============================================================
  # ARRAY JENIS BERKAS ALL
  # ============================================================
  $rjenis_berkas = [];
  $s = "SELECT * FROM tb_jenis_berkas ORDER BY nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $rjenis_berkas[$d['jenis_berkas']] = $d;
  }

  # ============================================================
  # SELECT SELECTED JALUR
  # ============================================================
  $s = "SELECT * FROM tb_jalur WHERE id=$pmb[id_jalur]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $jalur = mysqli_fetch_assoc($q);

  $rsyarat_berkas = explode(';', str_replace(' ', '', $jalur['berkas_wajib']));
  $jumlah_syarat_berkas = count($rsyarat_berkas);

  $div_berkas = '';

  $i = 0;
  foreach ($rsyarat_berkas as $jenis_berkas) {
    $i++;
    $b = $rjenis_berkas[$jenis_berkas];
    $info_pengganti = ')* wajib upload';
    if ($b['pengganti']) {
      $b2 = $rjenis_berkas[$b['pengganti']];
      $info_pengganti = ")* dapat digantikan dengan [ <span class=hitam>$b2[nama_berkas]</span> ]";
    }

    $accept = '';
    $ekstensi_info = '';
    if ($b['ekstensi']) {
      $ekstensi_info = ', ekstensi: ' . strtoupper($b['ekstensi']);
      $accept = ".$b[ekstensi]";
    }

    $s = "SELECT * FROM tb_berkas WHERE username='$username' AND jenis_berkas = '$jenis_berkas'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $img_berkas = $belum_upload;
    $gradasi = 'merah';
    $btn_upload = "<button class='d-block btn btn-sm btn-primary' name=btn_upload_berkas value=$jenis_berkas>Upload</button>";
    $status = null;

    if (mysqli_num_rows($q) > 1) die(alert("Dual berkas detected. username [$username], jenis_berkas: [$jenis_berkas] "));
    if (mysqli_num_rows($q)) {
      $d = mysqli_fetch_assoc($q);
      $status = $d['status'];
      $file = $d['file'];
      $src = "$path/$file";
      if (file_exists($src)) {
        $gradasi = 'hijau';
        $jumlah_upload_berkas++;

        # ============================================================
        # KIRIM NOTIF KE PETUGAS
        # ============================================================
        $boleh_kirim = 0;
        if ($d['last_notif']) {
          $eta = eta2($d['last_notif']);
          if (strtotime('now') - strtotime($d['last_notif']) > 3600) { // boleh kirim notif lagi
            $boleh_kirim = 1;
          } else { // tunggu besok lagi untuk kirim notif
            $kirim_notif_ke_petugas = "Kirim notif terakhir pada [ $d[last_notif] ], $eta, tunggu besok pada jam yang sama untuk mengirim ulang notifikasi. ";
          }
        } else {
          $eta = 'belum pernah kirim notif untuk berkas ini.';
          $boleh_kirim = 1;
        }

        if ($boleh_kirim) {

          $dari = 'pendaftar';
          $nomor_tujuan = $default_whatsapp_petugas;
          $nama_penerima = "Petugas PMB ($nama_petugas)";
          $nama_pendaftar = ucwords(strtolower($akun['nama']));
          $link_info = "$nama_server?berkas&status=null";
          $eta_upload = eta2($d['upload_at']);
          $pesan = "Saya sudah upload berkas [ $d[jenis_berkas] ] pada [ $d[upload_at] ], $eta_upload. Mohon segera diverifikasi. ";
          include 'link_kirim_whatsapp.php';
          $text_preview = str_replace("\n", '<br>', $text_wa);
          $text_preview = str_replace('```', '', $text_preview);
          $bg = 'success';



          $kirim_notif_ke_petugas = "
            Last notif: $eta
            <hr>
            <form method=post class='card'>
              <div class='card-header bg-$bg tengah putih'>Notifikasi untuk $nama_penerima</div>
              <div class='card-body'>
                <div class='f14 abu miring mb1'>Preview Notif:</div>
                <div id='text_preview' class='form-control'>$text_preview</div>
                <input type=hidden value='$link_wa' name=link_wa>
                <button class='btn btn-$bg w-100 mt2' value='$d[id]' name=btn_kirim_notifikasi onclick='return confirm(`Kirim Notif?`)'>Kirim Notifikasi Whatsapp</button>
              </div>
            </form>
          ";
          $kirim_notif_ke_petugas = "
            Last notif: $eta
            <hr>
            <div method=post class='card'>
              <div class='card-header bg-$bg tengah putih'>Notifikasi untuk $nama_penerima</div>
              <div class='card-body'>
                <div class='f14 abu miring mb1'>Preview Notif:</div>
                <div id='text_preview' class='form-control'>$text_preview</div>
                <form method=post>
                  <input type=hidden value='$link_wa' name=link_wa>
                  <button class='btn btn-$bg w-100 mt2' value='$d[id]' name=btn_kirim_notifikasi>Kirim Notifikasi</button>
                </form>
              </div>
            </div>
          
          ";
        }

        $status_berkas = "
          $belum_diverifikasi
          <div class='mt2 mb4 bg-info putih p2 br5'>
            $kirim_notif_ke_petugas
          </div>
        ";
        if ($d['status'] == 1) {
          $jumlah_verifikasi_berkas++;
          $status_berkas = $terverifikasi;
        }
        if ($d['status'] == -1) {
          $jumlah_reject++;
          $gradasi = 'merah';
          $status_berkas = "$ditolak<hr><b class=red>alasan: [ $d[alasan_reject] ]</b><hr>";
        }
        $btn_upload = "<button class='d-block btn btn-sm btn-secondary' name=btn_replace_berkas value='$jenis_berkas--$src' onclick='return confirm(`Replace berkas?`)'>Replace</button>";
        $img_berkas = "
          <div class='mt2'>
            <a href='$src' target=_blank>
              <img class='img-fluid' src='$src'>
            </a>
            <div class='mt2 f14 mb4'>Status Berkas: $status_berkas</div>
          </div>
        ";
      } else { // file berkas hilang
        $img_berkas = $image_missing;
      }
    } // end if rows



    # ============================================================
    # EXCEPTION FORMULIR
    # ============================================================
    $info_pembayaran = '';
    if ($jenis_berkas == 'FORMULIR') {
      $biaya_daftar_show = number_format($pmb['biaya_daftar']);
      $last_digit_whatsapp = substr($pmb['whatsapp'], -3);
      $diskon = $gelombang['diskon_biaya_daftar'] ?? 0;
      $nominal_transfer = (ceil($pmb['biaya_daftar'] * (100 - $diskon) / 100000) * 1000) + $last_digit_whatsapp;
      $nominal_transfer_show = number_format($nominal_transfer);
      $info_pembayaran = "
        <div class='card mb2 mt2'>
          <div class='card-header bg-info tengah'>Info Pembayaran untuk:</div>
          <div class='card-body gradasi-kuning'>
            <ul class='f14 m0'>
              <li><b>Gelombang</b>: $gelombang[nomor] - $tahun_pmb</li>
              <li><b>Biaya Pendaftaran</b>: Rp $biaya_daftar_show,-</li>
              <li><b>Diskon Gelombang</b>: $gelombang[diskon_biaya_daftar]%</li>
              <li><b>Whatsapp</b>: <span class=consolas>...$last_digit_whatsapp</span></li>
              <li class='darkblue mt2 mb2'><b>Nominal Transfer</b>: <div class=f24>Rp $nominal_transfer_show,-</div></li>
              <li><b>No. Rekening</b>: <span class='f20 darkblue consolas'>$pmb[no_rek]</span></li>
              <li><b>Bank</b>: $pmb[bank]</li>
              <li><b>Atas nama</b>: $pmb[atas_nama]</li>
              <li><b>Batas Akhir</b>: $batas_akhir_show</li>
              <li><b>ETA</b>: $eta_gelombang</li>
            </ul>

            <p class='f14 miring mt4 green'>
              Setelah bukti bayar terverifikasi Anda akan mendapatkan <b>Nomor Peserta Ujian PMB</b> dan layanan PMB penting lainnya.
            </p>
          </div>
        </div>
      ";
    }

    $form_id = 'form_' . strtolower($jenis_berkas);
    $blok_btn_upload = $status == 1 ? '' : "
      <div class='d-flex gap-2'>
        <input required type=file name=file class='d-block flex-fill form-control' accept='$accept'> 
        $btn_upload
      </div>
      <div class='f12 abu miring mt1 ml2'>$info_pengganti$ekstensi_info</div>    
    ";

    $input_wajibs = '';
    $info_field_wajibs = '';
    if ($b['field_wajibs'] and $d['status'] != 1) {
      $field_wajibs = explode(';', $b['field_wajibs']);
      foreach ($field_wajibs as $field) {
        if ($field) {
          $value_show = $d[$field];
          $type = 'text';
          if ($field == 'nomor_berkas') {
            $kolom = "Nomor $b[nama_berkas]";
          } elseif ($field == 'tanggal_berkas') {
            $kolom = "Tanggal $b[nama_berkas]";
            $type = 'date';
            $value_show = date('d-M-Y', strtotime($d[$field]));
          } elseif ($field == 'nominal') {
            $type = 'number';
            $kolom = "Nominal Transfer";
            $value_show = 'Rp ' . number_format($value_show) . ',-';
          } else {
            $kolom = $field;
          }
          $input_wajibs .= "
            <div class='mt2 mb2'>
              <div class='f14 mb1'>$kolom</div>
              <input required type=$type name=$field class='form-control'>
            </div>
          ";

          if ($d[$field]) { // jika sudah ada datanya
            $info_field_wajibs .= "<li class=''><b>$kolom</b>: $value_show</li>";
          }
        }
      }
      $info_field_wajibs = "<ul class='mt4 mb4'>$info_field_wajibs</ul>";
    } else {
      if ($jenis_berkas == 'KK') {
        echo '<hr>FIELD WAJIB IGNORED ' . $jenis_berkas;
        var_dump($b['field_wajibs']);
        var_dump($d['status']);
      }
    }



    $div_berkas .= "
      <div class='border-top blok-syarat gradasi-$gradasi' id=$form_id>
        <div class='darkblue mb2'>
          <div class=f30>$i. <b>$b[nama_berkas]</b></div> 
          $info_field_wajibs
          $img_berkas
        </div>
        <form method=post enctype='multipart/form-data'>
          $input_wajibs
          $blok_btn_upload
          $info_pembayaran
        </form>
      </div>
    ";
  }

  $persen = !$jumlah_syarat_berkas ? 0 : round($jumlah_upload_berkas * 100 / $jumlah_syarat_berkas);
  $persen2 = !$jumlah_upload_berkas ? 0 : round($jumlah_verifikasi_berkas * 100 / $jumlah_upload_berkas);

  # ============================================================
  # AUTO SAVE COUNT
  # ============================================================
  $s = "UPDATE tb_pmb SET 
    jumlah_syarat_berkas = $jumlah_syarat_berkas,
    jumlah_upload_berkas = $jumlah_upload_berkas,
    jumlah_verifikasi_berkas = $jumlah_verifikasi_berkas
  WHERE username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  if ($persen == 100) {
    echo "
      <script>
        $(function() {
          $('#form_next_step').slideDown();
        })
      </script>
    ";
  }

  if (!$jumlah_syarat_berkas || $jumlah_upload_berkas == $jumlah_verifikasi_berkas) {
    $verif_info = '';
    $verif_berkas_of = "<div class='f14 mb1 text-success'><span class=verif-progress-header>Verifikasi berkas $img_check</span></div>";
  } else {
    $verif_info = "<div class='f14 mb1'>Untuk mempercepat proses verifikasi, Anda dapat menggunakan layanan Whatsapp Gateway yang mengirim notifikasi langsung ke Petugas PMB.</div>";
    $verif_berkas_of = "<div class='f14 mb1 text-danger'><span class=verif-progress-header>Verifikasi berkas: $jumlah_verifikasi_berkas of $jumlah_upload_berkas</span></div>";
  }


  echo "
    <style>.blok-syarat{
      margin: 0 -15px;
      padding: 15px;
      padding-bottom: 35px;
    }</style>
    <form method=post class='card mb4'>
      <div class='card-header tengah bg-success putih'>Jalur Pilihan Anda</div>
      <div class='card-body tengah'>
        <div class='f18 mb2'>Jalur $jalur[nama_jalur]</div>
        <button class='btn btn-sm btn-secondary w-100' name=btn_reset_jalur onclick='return confirm(`Reset Jalur Daftar?`)'>Reset Jalur</button>
      </div>
    </form>

    <div class='darkblue tengah putih bg-primary p2' style='margin: 15px -15px'>
      <h3>Persyaratan Berkas</h3>
      <div class='f14 mb1'>Upload: $jumlah_upload_berkas of $jumlah_syarat_berkas</div>
      <div class='p2 pl4 pr4' style='background: #ffffffaa; margin: 0 -15px -15px -15px'>
        <div class='progress mb1'>
          <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='$persen' aria-valuemin='0' aria-valuemax='100' style='width: $persen%'>$persen%</div>
        </div>
      </div>
    </div>

    
    <div class='darkblue tengah putih bg-info p2 pl3 pr3' style='margin: 15px -15px'>
      $verif_berkas_of
      <div class='progress mb1'>
        <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='$persen2' aria-valuemin='0' aria-valuemax='100' style='width: $persen2%'>$persen2%</div>
      </div>
      $verif_info
    </div>


    $div_berkas
    ";
} else { // belum memilih jalur
  # ============================================================
  # RADIO JALUR PENDAFTARAN
  # ============================================================
  $radio_jalur = '';
  $s = "SELECT * FROM tb_jalur WHERE tahun_pmb=$tahun_pmb";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    alert("Belum ada Gelombang Pendaftaran PMB untuk tahun $tahun_pmb");
  } else {
    $rjalur = [];
    while ($d = mysqli_fetch_assoc($q)) {

      if ($d['syarat_jeda_tahun_lulus'] !== null and $akun['jeda_tahun_lulus'] > $d['syarat_jeda_tahun_lulus']) {
        // tidak bisa pilih jalur ini
        $radio_jalur .= "
          <label class='block label-jalur' id=label-jalur--$d[id]>
            <input disabled type=radio> $d[nama_jalur] | <i class='f12 darkred'>Tahun Lulus tidak memenuhi</i>
          </label>
        ";
      } else {
        $list_berkas = '';
        $t = explode(';', $d['berkas_wajib']);
        foreach ($t as $berkas) {
          $list_berkas .= "<li>$berkas</li>";
        }
        $radio_jalur .= "
          <label class='block label-jalur' id=label-jalur--$d[id]>
            <input required type=radio name=id_jalur value=$d[id]> $d[nama_jalur]
          </label>
          <div id='berkas--$d[id]' class='berkas card hideit mt2 mb3'>
            <div class='card-header bg-secondary putih'>Berkas Wajib:</div>
            <div class='card-body'>
              <ol class='f14 m0'>
                $list_berkas
              </ol>
            </div>
          </div>
        ";
      }
    }
  }

  $tahun_lulus_show = $tahun_pmb - $akun['jeda_tahun_lulus'];
  $jeda_show = !$akun['jeda_tahun_lulus'] ? '' : " | <i class=f12>Jeda $akun[jeda_tahun_lulus] tahun</i>";

  echo "
    <div class='card mb2'>
      <div class='card-header tengah  bg-info'>Info Persyaratan</div>
      <div class='card-body'>
        <ul class='f14 m0'>
          <li><b>Gelombang</b>: $gelombang[nomor] - $tahun_pmb</li>
          <li><b>Batas Akhir</b>: $batas_akhir_show</li>
          <li><b>ETA</b>: $eta_gelombang</li>
          <li><b>Tahun Lulus</b>: $tahun_lulus_show $jeda_show</li>
        </ul>
      </div>
    </div>
    
    <form method=post class='card mb2'>
      <div class='card-header tengah putih bg-success'>Jalur Pendaftaran</div>
      <div class='card-body'>
        $radio_jalur
        <button class='btn btn-primary w-100 mt2' name=btn_pilih_jalur>Pilih Jalur ini</button>
      </div>
    </form>
    <script>
      $(function() {
        $('.label-jalur').click(function() {
          let tid = $(this).prop('id');
          let rid = tid.split('--');
          let aksi = rid[0];
          let id = rid[1];
          $('.berkas').hide();
          $('#berkas--' + id).slideDown();
        })
      })
    </script>
  ";
} // end // belum memilih jalur

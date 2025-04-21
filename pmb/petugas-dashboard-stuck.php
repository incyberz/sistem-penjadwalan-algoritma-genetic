<?php
include '../includes/key2kolom.php';
require_once '../includes/eta.php';
require_once '../includes/script_btn_aksi.php';

$rstuck = [];

$SELECT = "SELECT 
  a.username,
  a.nama,
  a.last_terima_notif,
  a.created_at as last_date,
  b.id_jalur,
  (
    SELECT 1 FROM tb_berkas WHERE username=a.username
    AND jenis_berkas='FORMULIR') sudah_bayar_formulir,
  (
    SELECT (jumlah_syarat_berkas - jumlah_upload_berkas) 
    FROM tb_pmb WHERE username=a.username) belum_upload_count,
  (
    SELECT 1 FROM tb_berkas WHERE username=a.username
    AND jenis_berkas='REGISTRASI') sudah_bayar_registrasi

  FROM tb_akun a 
  JOIN tb_pmb b ON a.username=b.username
";

$WHERE = "
  WHERE a.tahun_pmb = $tahun_pmb
  AND a.active_status = 1 -- hanya pendaftar aktif 
  AND 1 -- bukan petugas
";

$rstuck_at = [
  'formulir' => [
    'title' => 'Stuck Bayar Formulir',
    'th' => 'Last Daftar',
    'no_data' => "Semua Pendaftar sudah bayar Formulir",
    'sql' => "
      $SELECT
      $WHERE
      ORDER BY sudah_bayar_formulir, a.created_at 
    ",
  ],
  'berkas' => [
    'title' => 'Stuck Upload Berkas',
    'th' => 'Belum Upload',
    'no_data' => "Semua Peserta sudah Upload Berkas",
    'sql' => "
      $SELECT
      JOIN tb_berkas c ON a.username=c.username 
      $WHERE 
      AND c.jenis_berkas = 'FORMULIR' 
      AND (c.status = 1 OR c.status is null) -- tidak termasuk yang reject 
      AND b.jumlah_syarat_berkas is not null -- sudah masuk tahapan upload berkas 
      ORDER BY belum_upload_count DESC, c.upload_at 
    ",
  ],
  'registrasi' => [
    'title' => 'Stuck Registrasi Ulang',
    'th' => 'Tanggal Lulus Tes',
    'no_data' => "Semua Peserta Tes saat ini sudah Registrasi Ulang",
    'sql' => "
      $SELECT
      $WHERE
      AND b.tanggal_lulus_tes is not null -- hanya peserta yang lulus
      AND b.tanggal_finish_registrasi is null -- hanya peserta yang belum registrasi
      ORDER BY sudah_bayar_registrasi, a.created_at 
    ",
  ],
];

$get_at = $get_at ?? null;
$get_username = $get_username ?? null;
$navs = '';
$link_back = '';

# ============================================================
# SELECT KODE ACTIVE STATUS NEGATIF (REJECT) 
# ============================================================
$select_kode_reject = '';
if ($get_at) {
  $s = "SELECT * FROM tb_status_akun WHERE id < 0 ORDER BY id DESC";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $opt = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $opt .= "<option value=$d[id] >Alasan: $d[status]</option>";
  }
  $select_kode_reject = "<select class='form-control' name=active_status>$opt</select>";
}

foreach ($rstuck_at as $at => $v) {
  if ($get_at) {
    $link_back = "<a href=?petugas>Home Petugas</a>";
    # ============================================================
    # NAVIGASI FOLLOW UP
    # ============================================================
    $sty = $get_at == $at ? 'style="border: solid 3px blue"' : '';
    $navs .= "
      <div class='py-1 px-3 gradasi-toska br5' $sty>
        <i>at</i> <a href='?follow_up&at=$at&username=$get_username'>$at</a>
      </div>
    ";
  }

  $col = 4;
  $limit = 3;
  if ($get_at) {
    $limit = 100;
    $col = 12;
    if ($get_at != $at) continue; // at formulir | berkas | registrasi
  }

  # ============================================================
  # EXECUTE SQL STUCKER
  # ============================================================
  $s = "$v[sql] LIMIT $limit";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $rstuck[$at] = '';
  $bg = 'danger';
  if (mysqli_num_rows($q)) {
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      if ($get_username) {
        if ($get_username != $d['username']) continue; // username yang ini saja
      }
      $i++;
      $tgl = date('d-M-Y', strtotime($d['last_date']));
      $eta = eta2($d['last_date']);
      $dots = strlen($d['nama']) > 20 ? '...' : '';
      $nama = ucwords(strtolower(substr($d['nama'], 0, 20))) . $dots;
      $info = '';
      $tr_berkas = '';
      // if ($at == 'formulir' || $at == 'registrasi') {
      //   $info = "<div class='f12 abu'>$tgl, <i>$eta</i></div>";
      // } elseif ($at == 'berkas') {
      // if (1) {
      // } // end if at == berkas
      // if (!$d['belum_upload_count']) continue;

      $info = $get_at ? "
        <div class='f12 abu'>$d[belum_upload_count] berkas</div>
      " : "
        <div class='f12 abu'>$tgl, <i>$eta</i></div>
      ";
      if ($get_username) {
        # ============================================================
        # SHOW BERKAS YANG TELAH DIUPLOAD
        # ============================================================
        $s2 = "SELECT a.* FROM tb_berkas a 
        JOIN tb_akun b ON a.username=b.username 
        WHERE b.tahun_pmb=$tahun_pmb 
        AND a.username='$d[username]'";
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
        $ruploaded_berkas = [];
        while ($d2 = mysqli_fetch_assoc($q2)) {
          $ruploaded_berkas[$d2['jenis_berkas']] = $d2;
        }

        # ============================================================
        # SHOW BERKAS YANG WAJIB DIUPLOAD
        # ============================================================
        $s2 = "SELECT c.berkas_wajib FROM tb_pmb a 
        JOIN tb_akun b ON a.username=b.username 
        JOIN tb_jalur c ON a.id_jalur=c.id 
        WHERE b.tahun_pmb=$tahun_pmb 
        AND a.username='$d[username]'";
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
        $d2 = mysqli_fetch_assoc($q2);

        $last_terima_notif_show = !$d['last_terima_notif'] ? '-' :  date('d-M-Y', strtotime($d['last_terima_notif'])) . ' - ' . eta2($d['last_terima_notif']);

        # ============================================================
        # LIST BERKAS WAJIB
        # ============================================================
        if ($d2) { // jika sudah pilih jalur daftar
          $rberkas_wajib = explode(';', $d2['berkas_wajib']);
        } elseif ($get_at == 'formulir') {
          $rberkas_wajib = ['FORMULIR'];
        }
        foreach ($rberkas_wajib as $jenis_berkas) {
          if ($get_at == 'formulir') {
            if ($jenis_berkas != 'FORMULIR') continue;
          } elseif ($get_at == 'berkas') {
            if ($jenis_berkas == 'FORMULIR' || $jenis_berkas == 'REGISTRASI') continue;
          } elseif ($get_at == 'registrasi') {
            if ($jenis_berkas != 'REGISTRASI') continue;
          } else {
            stop("Invalid get_at: [$get_at]");
          }
          $verif_by = null;
          if (key_exists($jenis_berkas, $ruploaded_berkas)) {
            $verif_by = $ruploaded_berkas[$jenis_berkas]['verif_by'];
            $verif_date = date('d-M-Y H:i', strtotime($ruploaded_berkas[$jenis_berkas]['verif_date']));
            $by = !$ruploaded_berkas[$jenis_berkas]['verif_by'] ? "
              belum diverifikasi oleh Petugas $img_warning
            " : "
              <span class=text-success><b>verified by</b>: $verif_by at $verif_date</span>
            ";
            $Telah_upload = "
              <span class=text-center>telah upload $img_check</span>
              <div class='f12'>$by</div>
            ";
          } else {
            $Telah_upload = "<span class=text-danger>belum upload $img_warning</span>";
          }

          $jenis = $verif_by ? 'selamat' : 'peringatan';
          $warning = $verif_by ? 'success' : 'warning';

          $tr_berkas .= "
            <tr>
              <td><b>$jenis_berkas</b>: $Telah_upload</td>
              <td>
                <a 
                  href='?kirim_notif&username=$get_username&jenis=$jenis&at=$get_at&jenis_berkas=$jenis_berkas&last_terima_notif=$d[last_terima_notif]' 
                  class='btn btn-$warning btn-sm proper'
                >Kirim $jenis</a>
              </td>
            </tr>
          ";
        }

        $tr_berkas = "
          <hr>
          <div class='alert alert-info my-2'><b>Last Terima Notif</b>: $last_terima_notif_show</div>
          <table class='table my-2'>$tr_berkas</table>
        ";
      } // end if username spesifik yang ini
      # ============================================================
      # AKSI
      # ============================================================
      $btn_aksi = "<a href='?follow_up&at=$at&username=$d[username]' class='btn btn-danger btn-sm'>Follow Up</a>";
      if ($get_at) {
        $btn_aksi = "
          <span class='btn btn-danger btn-sm btn-aksi' id=form-set-nonaktif-$d[username]--toggle>Set Nonaktif</span>
          <form method=post class='hideit card my-2' id=form-set-nonaktif-$d[username]>
            <div class='card-header bg-danger text-white'>Form Set Nonaktif</div>
            <div class='card-body gradasi-merah'>
              <p>
                Jika Saudara/i $nama:
                <ul> 
                  <li>tidak bisa dihubungi via whatsapp; atau</li> 
                  <li>menyatakan Resign; atau </li>
                  <li>penyebab black-list lainnya,</li>
                </ul> 
                maka Anda dapat Set Nonaktif agar tidak lagi muncul di Daftar Wajib Follow Up.
              </p>
              <p class=text-danger>
                Perhatian! Status Nonaktif menyebabkan pendaftar tidak bisa melanjutkan proses pendaftaran, kecuali sudah diizinkan kembali oleh Petugas.
              </p>

              $select_kode_reject

              <label class='d-block my-2 hover'>
                <input type=checkbox required> 
                Saya menyatakan bahwa pilihan alasan diatas sudah benar.
              </label>

              <button class='btn btn-danger w-100 mt-2' name=btn_set_nonaktif value=$d[username]>Set Nonaktif</button>
            </div>
          </form>
        ";
      }


      # ============================================================
      # FINAL TR
      # ============================================================
      $rstuck[$at] .= "
        <tr>
          <td>$i</td>
          <td>
            <a href='?follow_up&at=$at&username=$d[username]'>$nama</a>
            $info
          </td>
          <td>
            $btn_aksi
            $tr_berkas
          </td>
        </tr>
      ";
    }
  } else { // no data
    $bg = 'secondary';
  }


  $rstuck[$at] =  $rstuck[$at] ? "
    <table class=table>
      <thead>
        <th>No</th>
        <th>$v[th]</th>
        <th>Aksi</th>
      </thead>
      $rstuck[$at]
    </table>  
  " : "
    <div class='text-success'>
      $v[no_data] $img_check
    </div>
  ";

  # ============================================================
  # FINAL ARRAY STUCKS
  # ============================================================
  $rstuck[$at] = "
    <div class='col-$col'>
      <div class='card'>
        <div class='card-header tengah putih bg-$bg'>$v[title]</div>
        <div class='card-body'>
          $rstuck[$at]
        </div>
      </div>
    </div>
  ";
}

if ($get_username) {
  $navs .= "
    <div class='py-1 px-3 gradasi-kuning br5'>
      <i class='abu f12'>filtered by</i> <b> username</b>: $get_username
    </div>
    <div class='py-1 px-3 bg-primary br5'>
      <a href='?follow_up&at=$get_at' class=text-white>Clear Filter</a>
    </div>
  ";
}


echo "
  <div class='d-flex'>
    <div>$link_back</div>
    <div class=flex-fill>
      <div class='d-flex flex-center gap-3'>
        $navs
      </div>
    </div>
  </div>
  <div class='row mt4'>" . join('', $rstuck) . "</div>
";

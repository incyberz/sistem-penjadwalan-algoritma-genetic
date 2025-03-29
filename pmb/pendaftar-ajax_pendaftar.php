<?php
session_start();
$username = $_SESSION['pmb_username'] ?? 'username undefined.';
$role = $_SESSION['pmb_role'] ?? 'role undefined.';
if ($role != 'petugas') die('role bukan petugas.');

include '../conn.php';
// include '../includes/eta.php';
include '../includes/key2kolom.php';
include 'tahun_pmb.php';
// include 'gelombang_aktif.php';


$active_status = $_GET['active_status'] ?? die('undefined active_status.');
$whatsapp_status = $_GET['whatsapp_status'] ?? die('undefined whatsapp_status.');
$sudah_bayar = $_GET['sudah_bayar'] ?? die('undefined sudah_bayar.');
$lulus_tes_pmb = $_GET['lulus_tes_pmb'] ?? die('undefined lulus_tes_pmb.');
$sudah_registrasi = $_GET['sudah_registrasi'] ?? die('undefined sudah_registrasi.');
$tanggal_awal = $_GET['tanggal_awal'] ?? die('undefined tanggal_awal.');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? die('undefined tanggal_akhir.');
$get_csv = $_GET['get_csv'] ?? die('undefined get_csv.');

if ($get_csv) {
  $arr_header = [];
  $tanggal = 'all-time';
  if ($tanggal_awal) {
    $tanggal = date('d-M-Y', strtotime($tanggal_awal));
    if ($tanggal_akhir) {
      $tanggal .= '--' . date('d-M-Y', strtotime($tanggal_akhir));
    }
  }
  $src_csv = "csv/data_pmb-$tanggal.csv";
  $file = fopen($src_csv, "w+");
  fputcsv($file, ["DATA PMB $tahun_pmb"]);
  fputcsv($file, [' ~ active_status', $active_status]);
  fputcsv($file, [' ~ whatsapp_status', $whatsapp_status]);
  fputcsv($file, [' ~ sudah_bayar', $sudah_bayar]);
  fputcsv($file, [' ~ lulus_tes_pmb', $lulus_tes_pmb]);
  fputcsv($file, [' ~ sudah_registrasi', $sudah_registrasi]);
  fputcsv($file, [' ~ tanggal_awal', $tanggal_awal]);
  fputcsv($file, [' ~ tanggal_akhir', $tanggal_akhir]);
  fputcsv($file, [' ']);

  $rspasi['pmb'] = ['DATA PMB' => ' '];
  $rspasi['biodata'] = ['BIODATA' => ' '];
  $rspasi['data_sekolah'] = ['DATA SEKOLAH' => ' '];
  $rspasi['data_orangtua'] = ['DATA ORANGTUA' => ' '];
}

$nm_hari = ['Ah', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'];

$rhide = [
  'asal_sekolah',
  'jeda_tahun_lulus',
  'whatsapp',
  'active_status',
  'info_status',
  'whatsapp_status',
  'last_step',
  'lulus_tes_pmb',
  'file_bayar_formulir',
  'file_bayar_registrasi',
];

if ($active_status == 1) {
  $sql_active_status = "a.active_status = 1";
} else {
  $rhide = array_filter($rhide, fn($item) => $item != 'active_status');
  $rhide = array_filter($rhide, fn($item) => $item != 'info_status');
  $sql_active_status =  "a.active_status != 1";

  $rstatus_akun = [];
  $s = "SELECT * FROM tb_status_akun";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $rstatus_akun[$d['id']] = $d;
  }
}

if ($whatsapp_status == 1) {
  $sql_whatsapp_status = "a.whatsapp_status = 1";
  // $rhide = array_filter($rhide, fn($item) => $item != 'whatsapp');
  $show_wa = 1;
  include '../includes/img_icon.php';
} else {
  $show_wa = 0;
  $sql_whatsapp_status = "a.whatsapp_status != 1 ";
  $sql_whatsapp_status = 1; // tampilkan semua status whatsapp
}

if ($sudah_bayar) {
  $rhide = array_filter($rhide, fn($item) => $item != 'file_bayar_formulir');
}

if (!($lulus_tes_pmb || $sudah_registrasi)) {
  $rhide = array_filter($rhide, fn($item) => $item != 'asal_sekolah');
  $rhide = array_filter($rhide, fn($item) => $item != 'jeda_tahun_lulus');
}

if ($lulus_tes_pmb) {
  include '../includes/eta.php';
  $rhide = array_filter($rhide, fn($item) => $item != 'lulus_tes_pmb');
  $sql_lulus_tes_pmb = "a.lulus_tes_pmb is not null";
} else {
  $sql_lulus_tes_pmb = 1;
}

if ($sudah_registrasi) {
  $rhide = array_filter($rhide, fn($item) => $item != 'file_bayar_registrasi');
}

$sql_tanggal_awal = $tanggal_awal ? "a.created_at >= '$tanggal_awal'" : 1;
$sql_tanggal_akhir = $tanggal_akhir ? "a.created_at <= '$tanggal_akhir 23:59:59'" : 1;

$select_fields = $get_csv ? "
  a.username,
  a.created_at as tanggal_daftar,
  a.nama,
  a.whatsapp,
  a.asal_sekolah,
  a.jeda_tahun_lulus as tahun_lulus_akun,
  a.info_status as info_batal_pmb,
  a.lulus_tes_pmb as tanggal_lulus_pmb,
  (SELECT 1 FROM tb_pmb WHERE username=a.username) ada_data_pmb,
  (SELECT 1 FROM tb_biodata WHERE username=a.username) ada_biodata,
  (SELECT 1 FROM tb_data_sekolah WHERE username=a.username) ada_data_sekolah,
  (SELECT 1 FROM tb_data_orangtua WHERE username=a.username) ada_data_orangtua
" : "
  a.*
";

$order_by = $get_csv ? "
  ada_data_pmb DESC, 
  ada_biodata DESC, 
  ada_data_sekolah DESC, 
  ada_data_orangtua DESC, 
" : '';

$s = "SELECT 
$select_fields,
(
  SELECT file FROM tb_berkas 
  WHERE status=1 -- berkas terverifikasi
  AND jenis_berkas = 'FORMULIR' 
  AND username=a.username ) file_bayar_formulir,
(
  SELECT file FROM tb_berkas 
  WHERE status=1 -- berkas terverifikasi
  AND jenis_berkas = 'REGISTRASI' 
  AND username=a.username ) file_bayar_registrasi


FROM tb_akun a 
WHERE a.role is null 
AND $sql_active_status 
AND $sql_whatsapp_status 
AND $sql_lulus_tes_pmb 
AND $sql_tanggal_awal
AND $sql_tanggal_akhir 
ORDER BY $order_by a.created_at DESC, a.nama
";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$num_rows = mysqli_num_rows($q);
if (!$num_rows) {
  die('<div class=tengah><b class=red>-- data tidak ditemukan --</b><br><a href=?pendaftar>Clear Filter</a></div>');
} else {
  $i = 0;
  $j = 0;
  $th = '<th>No</th>';
  while ($d = mysqli_fetch_assoc($q)) {
    if ($get_csv) {
      if ($sudah_bayar and !$d['file_bayar_formulir']) continue; // hide peserta yang belum bayar
      if ($sudah_registrasi and !$d['file_bayar_registrasi']) continue; // hide peserta yang belum bayar

      $j++;
      foreach ($d as $key => $value) {
        if ($j == 1) {
          array_push($arr_header, $key);
        }
        if ($key == 'tahun_lulus_akun') {
          $d[$key] = $tahun_pmb - $value;
        }
      }

      # ============================================================
      # DATA PMB
      # ============================================================
      $s2 = [];

      $s2['pmb'] = "SELECT 
      (SELECT nama FROM tb_prodi WHERE id=a.id_prodi) nama_prodi,
      (SELECT nama_jalur FROM tb_jalur_pmb WHERE id=a.id_jalur) nama_jalur,
      a.id_gelombang as gelombang,
      a.no_peserta_ujian,
      a.jumlah_syarat_berkas,
      a.jumlah_upload_berkas,
      a.jumlah_verifikasi_berkas,
      a.tanggal_registrasi_ulang
      FROM tb_pmb a WHERE a.username='$d[username]'";

      $s2['biodata'] = "SELECT a.* FROM tb_biodata a WHERE a.username='$d[username]'";
      $s2['data_sekolah'] = "SELECT a.* FROM tb_data_sekolah a WHERE a.username='$d[username]'";
      $s2['data_orangtua'] = "SELECT a.* FROM tb_data_orangtua a WHERE a.username='$d[username]'";

      foreach ($s2 as $tb => $sql) {
        $q2 = mysqli_query($cn, $sql) or die(mysqli_error($cn));
        if (mysqli_num_rows($q2)) {
          $d2 = mysqli_fetch_assoc($q2);
          unset($d2['username']); // cegah index username double pada array_merge

          $arr_header2 = [];
          foreach ($d2 as $key => $value) {
            array_push($arr_header2, $key);
          }

          $d = array_merge($d, $rspasi[$tb], $d2);
          $arr_header = array_merge($arr_header, $rspasi[$tb], $arr_header2);
        }
      }


      # ============================================================
      # PUT CSV
      # ============================================================
      if ($j == 1) fputcsv($file, $arr_header);
      fputcsv($file, $d);
    } else {

      # ============================================================
      # NORMAL LOOP WHILE TANPA CSV
      # ============================================================
      if ($sudah_bayar and !$d['file_bayar_formulir']) continue; // hide peserta yang belum bayar
      if ($sudah_registrasi and !$d['file_bayar_registrasi']) continue; // hide peserta yang belum bayar
      $i++;
      $td = "<td>$i</td>";
      foreach ($d as $key => $value) {
        $value_show = null;
        $kolom_show = null;
        if (
          $key == 'tahun_pmb'
          || $key == 'password'
          || $key == 'jumlah_tes'
          || $key == 'role'
          || $key == 'username'
        ) {
          continue;
        } elseif (in_array($key, $rhide)) {
          continue;
        } elseif ($key == 'nama') {
          if ($show_wa) {
            $text_wa = urlencode("Hallo $d[nama]!\n\n");
            $value_show = "$value <a target=_blank href='https://api.whatsapp.com/send/?phone=$d[whatsapp]&text=$text_wa'>$img_wa</a>";
          }
        } elseif ($key == 'file_bayar_formulir' || $key == 'file_bayar_registrasi') {
          $kolom = $key == 'file_bayar_formulir' ? 'Bukti Bayar Fornulir' : 'Bukti Bayar Registrasi';
          $href = "uploads/berkas/$value";
          if (file_exists($href)) {
            $value_show = "<a target=_blank href='$href'>Lihat File</a>";
          } else {
            $value_show = "<b class='f12 red'>Berkas Hilang</b>";
          }
        } elseif ($key == 'lulus_tes_pmb') {
          $kolom_show = 'Lulus Tes';
          $tgl_lulus = date('Y-m-d', strtotime($value));
          $value_show = date('d F, Y', strtotime($tgl_lulus));
          $eta = eta2($tgl_lulus);
          $value_show .= ", $eta";
        } elseif ($key == 'jeda_tahun_lulus') {
          $kolom_show = 'Tahun Lulus';
          $value_show = $tahun_pmb - $value;
        } elseif ($key == 'active_status') {
          $status = $rstatus_akun[$value]['status'];
          $value_show = "<span class='red f14'>$status ($value)</span>";
        } elseif ($key == 'info_status') {
          $kolom_show = 'Informasi Pembatalan PMB';
          $value_show = "<span class=red>$value</span>";
        } elseif ($key == 'created_at') {
          $kolom_show = 'Tanggal';
          $hari = $nm_hari[date('w', strtotime($value))];
          $value_show = "$hari, " . date('d-M', strtotime($value));
        }



        # ============================================================
        # CREATE HEADER TABLE
        # ============================================================
        if ($i == 1) {
          $kolom = $kolom_show ?? key2kolom($key);
          $th .= "<th class='td-$key '>$kolom</th>";
        }

        # ============================================================
        # FINAL OUTPUT TD
        # ============================================================
        $value_show = $value_show ?? $value;
        $td .= "<td class='td-$key '>$value_show</td>";
      } // end foreach td



      # ============================================================
      # FINAL OUTPUT TR
      # ============================================================
      $tr .= "<tr 
      class='
        tr 
        tr-$d[username] 
        tr-active_status-$d[active_status]
        tr-whatsapp_status-$d[whatsapp_status]
        tr-last_step-$d[last_step]
      '
      >$td</tr>";
    }
  }

  if ($get_csv) {
    fputcsv($file, [' ']);
    fputcsv($file, ['DATA FROM:', 'Gamified PMB System']);
    fputcsv($file, ['AT:', date('F d, Y, H:i:s')]);
    fclose($file);
    # ============================================================
    # FINAL ECHO CSV
    # ============================================================
    echo "
      <div class='alert alert-success tengah mb2'>
        <a class='btn btn-primary' target=_blank href='$src_csv' id=btn_download_csv>
          Download CSV (Excel)
        </a>
      </div>
      <div class='tengah mt2 abu miring f14 hideit' id=info_reload>
        Page akan reload dalam 5 detik...
      </div>---$j
    ";
  } else {

    # ============================================================
    # FINAL ECHO
    # ============================================================
    echo "
      <table class='table table-striped'>
        <thead>
          <tr>$th</tr>
        </thead>
        <tbody>
          $tr
        </tbody>
      </table>---$i
    ";
  }
}

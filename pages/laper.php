<?php
# ============================================================
# LAPORAN PEMAKAIAN RUANGAN
# ============================================================
$get_id_ruang = $_GET['id_ruang'] ?? '';
$get_weekday = $_GET['weekday'] ?? '';
$mode = $_GET['mode'] ?? '';

include 'laper-nav_header.php';
include 'laper-styles.php';

# ============================================================
# DATA RUANG
# ============================================================
include 'includes/rruang.php';
if (!$rruang) die(alert("Belum ada Data Ruang Perkuliahan. | <a href='?crud&tb=ruang'>Manage Ruang</a>"));
$ths_ruang = '';
foreach ($rruang as $id_ruang => $arr_ruang) {
  if ($get_id_ruang and $id_ruang != $get_id_ruang) continue;
  $ths_ruang .= "<th>$arr_ruang[nama]</th>";
}

# ============================================================
# DATA PEMAKAIAN RUANG DI TA INI
# ============================================================
$rpenjadwalan = [];
$rpemakaian = []; // ruang
include 'jadwal-pemakaian_ruang.php';

# ============================================================
# MAIN BLOK JADWAL
# ============================================================
$blok_pemakaian = '';
foreach ($rhari as $date => $v) {

  if ($get_weekday and $v['weekday'] != $get_weekday) continue;
  $s = "SELECT * FROM tb_sesi";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr1 = '';
  $tr2 = '';
  while ($sesi = mysqli_fetch_assoc($q)) {
    $tds_ruang = '';
    foreach ($rruang as $id_ruang => $arr_ruang) {
      if ($get_id_ruang and $id_ruang != $get_id_ruang) continue;
      $filtered_ruang = $id_ruang == $get_id_ruang ? 'filtered_ruang' : '';
      if (
        $sesi['is_break']
        || ($v['weekday'] == 5 and ($sesi['id'] == 5 || $sesi['id'] == 7))
      ) {
        $tds_ruang .= "<td class='tengah sesi_break' colspan=100%>break</td>";
        break;
      } else {
        $nama_kelas = $rpemakaian[$v['weekday']][$id_ruang][$sesi['id']]['nama_kelas'] ?? '';
        if ($nama_kelas) {
          $tmp = str_replace("-$ta_aktif", '', $nama_kelas);
          $t = explode('-', $tmp);
          $counter = isset($t[4]) ? "-$t[4]" : '';
          $smt = str_replace('SM', '', $t[3]);
          $nama_kls = $nama_kelas ? "$t[1]/$smt$counter" : '-';
          $ruang_terisi = 'ruang_terisi';
          if ($get_id_ruang || $mode == 'detail') {
            $fk = $rpemakaian[$v['weekday']][$id_ruang][$sesi['id']] ?? '';
            $style_lebar = $mode == 'detail' ? 'min-width:400px; max-height:20px !important;overflow:hidden;font-size:12px' : '';
            $nama_kls = "<div style='$style_lebar'>$nama_kelas | $fk[nama_lengkap_dosen] |  $fk[nama_mk]</div>";
          } else {
            $nama_kls = "<div style='width:60px;'>$nama_kls</div>";
          }
        } else {
          $nama_kls = '-';
          $ruang_terisi = '';
        }
        $tds_ruang .= "<td class='tengah $ruang_terisi $filtered_ruang'>$nama_kls</td>";
      }
    }

    $awal = date('H:i', strtotime($sesi['awal']));
    $akhir = date('H:i', strtotime($sesi['akhir']));

    $tr1 .= "
      <tr>
        <td>$sesi[nama]</td>
        <td>$awal - $akhir</td>
      </tr>
    ";
    $tr2 .= "
      <tr>
        $tds_ruang
      </tr>
    ";
  }

  $nama_hari = nama_hari($date);
  $blok_pemakaian .= "
    <h4 class='darkblue nama_hari p1 tengah'>$nama_hari</h4>

    <div style='display:grid; grid-template-columns:200px auto;'>
      <div style='background:#cff'>
        <table class='table table-bordered'>
          <thead>
            <th>Sesi</th>
            <th>Pukul</th>
          </thead>
          $tr1
        </table>
      </div>
      <div style='overflow: scroll; border-right: solid 1px #ccc'>
        <table class='table table-bordered'>
          <thead>
            $ths_ruang
          </thead>
          $tr2
        </table>
      </div>
    </div>
  ";
}

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  $blok_pemakaian
";

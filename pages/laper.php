<?php
# ============================================================
# LAPORAN PEMAKAIAN RUANGAN
# ============================================================
set_h2('LAPORAN PEMAKAIAN RUANG', "<h3>TA. $tahun_ta $Gg</h3>");

include 'laper-styles.php';

# ============================================================
# DATA RUANG
# ============================================================
include 'includes/rruang.php';
if (!$rruang) die(alert("Belum ada Data Ruang Perkuliahan. | <a href='?crud&tb=ruang'>Manage Ruang</a>"));
$ths_ruang = '';
foreach ($rruang as $id => $arr_ruang) {
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
  $s = "SELECT * FROM tb_sesi";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr1 = '';
  $tr2 = '';
  while ($sesi = mysqli_fetch_assoc($q)) {
    // $id=$d['id'];

    $tds_ruang = '';
    foreach ($rruang as $id_ruang => $arr_ruang) {
      $nama_kelas = $rpemakaian[$v['weekday']][$id_ruang][$sesi['id']]['nama_kelas'] ?? '';
      if ($nama_kelas) {
        $t = explode('-', $nama_kelas);
        $counter = isset($t[5]) ? "-$t[5]" : '';
        $smt = str_replace('SM', '', $t[3]);
        $nama_kls = $nama_kelas ? "$t[1]/$smt$counter" : '-';
        $ruang_terisi = 'ruang_terisi';
      } else {
        $nama_kls = '-';
        $ruang_terisi = '';
      }
      $tds_ruang .= "<td class='tengah $ruang_terisi'><div style='width:60px;'>$nama_kls</div></td>";
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

  $blok_pemakaian .= "
    <h4 class='darkblue nama_hari p1 tengah'>$v[nama_hari]</h4>

    <div style='display:grid; grid-template-columns:200px auto;'>
      <div style='background:red'>
        <table class='table table-bordered'>
          <thead>
            <th>Sesi</th>
            <th>Pukul</th>
          </thead>
          $tr1
        </table>
      </div>
      <div  style='overflow: scroll'>
        <table class='table table-bordered' style='width:1500px'>
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

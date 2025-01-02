<?php
# ============================================================
# JADWAL PER FAKULTAS
# ============================================================
$fakultas = 'FKOM';
$semester = 1;
$shift = 'R';
$SHIFT = $shift == 'R' ? 'REGULER' : 'NON-REGULER';
include 'jadwal-functions.php';
include 'jadwal-styles.php';
include 'jadwal-processors.php';
set_title("JADWAL " . strtoupper($fakultas));

echo "
  <div class='tengah gradasi-toska p-3'>
    <h1>FAKULTAS KOMPUTER UNIVERSITAS MA'SOEM</h1>
    <h2>JADWAL MATA KULIAH $tahun_ta $GG</h2>
    <h3>KELAS $SHIFT SEMESTER $semester</h3>
  </div>
";

# ============================================================
# ARRAY KELAS PER SEMESTER PER SHIFT
# ============================================================
$rkelas = [];
$s = "SELECT
a.id, 
a.nama as nama_kelas 
FROM tb_kelas a 
join tb_prodi b ON a.id_prodi=b.id 
WHERE b.fakultas='$fakultas' 
AND a.semester = '$semester' 
AND a.shift = '$shift'
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $pesan = "Belum ada Data Kelas untuk semester [$semester] kelas [$SHIFT] fakultas [$fakultas].";
  alert("$pesan | <a href='?crud&tb=kelas&note=$pesan'>Manage Kelas</a>");
  exit;
}
while ($d = mysqli_fetch_assoc($q)) {
  $rkelas[$d['id']] = $d;
}
$col = intval(12 / count($rkelas));
if ($col < 3) $col = 3;

# ============================================================
# SENIN PERTAMA PERKULIAHAN + ARRAY HARI
# ============================================================
$senin_pertama = '2025-2-3';
if (date('w', strtotime($senin_pertama)) != 1) die(alert("Weekday Senin Pertama harus bernilai 1 (hari Senin)."));

$rhari = [];
for ($i = 0; $i < 5; $i++) {
  $date = date('Y-m-d', strtotime("+$i day", strtotime($senin_pertama)));
  $rhari[$date] = [
    'weekday' => date('w', strtotime($date)),
    'nama_hari' => nama_hari($date),
    'tanggal' => date('d', strtotime($date)),
    'bulan' => nama_bulan($date),
    'tahun' => date('Y', strtotime($date))
  ];
}


# ============================================================
# DATA SESI
# ============================================================
$s = "SELECT * FROM tb_sesi WHERE shift='$shift'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rsesi = [];
if (!mysqli_num_rows($q)) {
  die(alert("Belum ada Data Sesi Perkuliahan untuk shift-kelas [$shift]. | <a href='?crud&tb=sesi'>Manage Sesi</a>"));
}
while ($d = mysqli_fetch_assoc($q)) {
  $rsesi[$d['id']] = $d;
}


# ============================================================
# DATA RUANG
# ============================================================
$opt_ruang = '';
$radio_ruang = '';
$s = "SELECT * FROM tb_ruang";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rruang = [];
if (!mysqli_num_rows($q)) {
  die(alert("Belum ada Data Ruang Perkuliahan. | <a href='?crud&tb=ruang'>Manage Ruang</a>"));
}
while ($d = mysqli_fetch_assoc($q)) {
  $rruang[$d['id']] = $d;
  $opt_ruang .= "<option value='$d[id]'>$d[nama]</option>";
  $radio_ruang .= "
    <label class=label_ruang>
      <div>
        <input required type=radio name=id_ruang value=$d[id]>
        R.$d[nama]
      </div>
    </label>
  ";
}



# ============================================================
# MAIN BLOK JADWAL
# ============================================================
$blok_jadwal = '';
foreach ($rhari as $date => $v) {
  $blok_jadwal .= "
    <h4 class='darkblue nama_hari p1 tengah'>$v[nama_hari]</h4>
  ";
  $cols = '';
  foreach ($rkelas as $id_kelas => $arr_kelas) {
    $kelas = $arr_kelas['nama_kelas'];

    # ============================================================
    # DATA AVAILABLE ST-DETAIL | UNSIGNED JADWAL
    # ============================================================
    $radio_st_mk_kelas = '';
    $s = "SELECT 
    a.id,
    d.nama as nama_mk,
    d.sks,
    e.nama as nama_dosen

    FROM tb_st_mk_kelas a 
    JOIN tb_st_mk b ON a.id_st_mk=b.id 
    JOIN tb_st c ON b.id_st=c.id 
    JOIN tb_mk d ON b.id_mk=d.id 
    JOIN tb_dosen e ON c.id_dosen=e.id 
    JOIN tb_kelas f ON a.id_kelas=f.id 
    JOIN tb_kurikulum g ON d.id_kurikulum=g.id 
    JOIN tb_prodi h ON g.id_prodi=h.id 
    WHERE 1 
    AND d.semester = '$semester' 
    AND f.nama = '$kelas' 
    AND f.shift = '$shift' 
    AND h.fakultas = '$fakultas' 


    ORDER BY nama_mk";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    // $rst_mk_kelas = [];
    if (!mysqli_num_rows($q)) {
      die(alert("Belum ada Data Surat Tugas Perkuliahan detail. | <a href='?st_ajar'>Manage Surat Tugas</a>"));
    }
    while ($d = mysqli_fetch_assoc($q)) {
      // $rst_mk_kelas[$d['id']] = $d;
      $radio_st_mk_kelas .= "
        <label class=label_mk_dosen>
          <div>
            <input required type=radio name=id_radio value=$d[id]__$d[sks]>
            $d[nama_mk]
          </div>
          <div class='abu miring f14'>$d[nama_dosen]- $d[sks] SKS</div>
        </label>
      ";
    }
    # ============================================================
    # END ST_TR_KELAS
    # ============================================================



    # ============================================================
    # LOOP SESI
    # ============================================================
    $tr = '';
    foreach ($rsesi as $id_sesi => $arr_sesi) {
      $awal = date('H:i', strtotime($arr_sesi['awal']));
      $akhir = date('H:i', strtotime($arr_sesi['akhir']));
      if ($arr_sesi['is_break']) {
        $tr .= "
          <tr class='gradasi-kuning'>
            <td colspan=100%>
              <div class='tengah abu miring'>
                $arr_sesi[info]
              </div>
            </td>
          </tr>
        ";
      } else {
        $tr .= "
          <tr>
            <td>$awal - $akhir</td>
            <td>
              <div>
                <button class='toggle_book btn btn-success btn-sm w-100' id=toggle_book__$arr_kelas[id]__$v[weekday]__$id_sesi>Book Sesi $id_sesi</button>
                <form method=post class='hideit wadah mt2 gradasi-kuning form_book' id=form_book__$arr_kelas[id]__$v[weekday]__$id_sesi>
                  <div class='mb2'>
                    <div class='bold f12 mb2'>Pilihan MK:</div>
                    $radio_st_mk_kelas
                  </div>
                  <div class='mb2 blok_radio_ruang'>
                    <div class='bold f12 mb1'>Ruang:</div>
                    $radio_ruang
                  </div>
                  <button class='btn btn-primary w-100 mb2' name=btn_book value='$v[weekday]__$id_sesi'>Book</button>
                  <div class=tengah><span class='btn_cancel pointer darkred'>Cancel</span></div>
                </form>
              </div>
            </td>
            <td>?</td>
          </tr>
        ";
      } // non break sesi
    } // end foreach $rsesi


    $col = 12; // aborted old code

    $cols .= "
      <div class='col-$col'>
        <div class=blok_jadwal>
          <table class='table table-hover'>
            <thead>
              <th width=20%>WAKTU</th>
              <th>$kelas</th>
              <th width=15%>RUANG</th>
            </thead>
            $tr
          </table>
        </div>      
      </div>      
    ";
  }
  $blok_jadwal .= "<div class=row>$cols</div>";
}


echo $blok_jadwal;
?>
<script>
  $(function() {
    $('.toggle_book').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_kelas = rid[1];
      let weekday = rid[2];
      let id_sesi = rid[3];
      $('#form_book__' + id_kelas + '__' + weekday + '__' + id_sesi).slideToggle();
      $('.toggle_book').prop('disabled', 1);
    });
    $('.btn_cancel').click(function() {
      $('.toggle_book').prop('disabled', 0);
      $('.form_book').slideUp();
    });
  })
</script>
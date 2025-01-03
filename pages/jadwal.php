<?php
# ============================================================
# JADWAL PER FAKULTAS
# ============================================================
$fakultas = $_GET['fakultas'] ?? 'FKOM';
$semester = $_GET['semester'] ?? 1;
$shift = $_GET['shift'] ?? 'R';
$SHIFT = $shift == 'R' ? 'REGULER' : 'NON-REGULER';
include 'jadwal-functions.php';
include 'jadwal-styles.php';
include 'jadwal-processors.php';
set_title("JADWAL " . strtoupper($fakultas));

$KAMPUS = strtoupper($nama_kampus);
$FAK = strtoupper($rfakultas[$fakultas]);

echo "
  <div class='tengah gradasi-toska p-3'>
    <h1>$FAK $KAMPUS</h1>
    <h2>JADWAL MATA KULIAH $tahun_ta $GG</h2>
    <h3>KELAS $SHIFT SEMESTER $semester</h3>
    <h4>D3-KA</h4>
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
$nav_kelas = '';
$nav_kelas_active = 'nav_kelas_active';
$nav_kelas_pertama = null;
while ($d = mysqli_fetch_assoc($q)) {
  $rkelas[$d['id']] = $d;
  $nav_kelas_pertama = $nav_kelas_pertama ?? $d['nama_kelas'];
  $nav_kelas .= "<div class='bordered br5 p1 px-2 nav_kelas $nav_kelas_active pointer' id=nav_kelas__$d[id]><span>$d[nama_kelas]</span></div>";
  $nav_kelas_active = '';
}
$nav_kelas = "<div class='flexy flex-center mt4'>$nav_kelas</div>";
$col = intval(12 / count($rkelas));
if ($col < 3) $col = 3;




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
include 'includes/rruang.php';
if (!$rruang) die(alert("Belum ada Data Ruang Perkuliahan. | <a href='?crud&tb=ruang'>Manage Ruang</a>"));

# ============================================================
# DATA PEMAKAIAN RUANG DI TA INI
# ============================================================
$rpenjadwalan = [];
$rpemakaian = []; // ruang
include 'jadwal-pemakaian_ruang.php';








# ============================================================
# MAIN BLOK JADWAL
# ============================================================
$blok_jadwal = '';
foreach ($rhari as $date => $v) {
  $blok_jadwal .= "
    <h4 class='darkblue nama_hari p1 tengah'>$v[nama_hari]</h4>
  ";
  $cols = '';
  $hideit_kelas = '';
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
    e.nama as nama_dosen,
    (SELECT 1 FROM tb_jadwal WHERE id=a.id) sudah_terjadwal

    FROM tb_st_mk_kelas a 
    JOIN tb_st_mk b ON a.id_st_mk=b.id 
    JOIN tb_st c ON b.id_st=c.id 
    JOIN tb_mk d ON b.id_mk=d.id 
    JOIN tb_dosen e ON c.id_dosen=e.id 
    JOIN tb_kelas f ON a.id_kelas=f.id 
    JOIN tb_kurikulum g ON d.id_kurikulum=g.id 
    JOIN tb_prodi h ON g.id_prodi=h.id 
    LEFT JOIN tb_jadwal i ON a.id=i.id 
    WHERE 1 
    AND d.semester = '$semester' 
    AND f.nama = '$kelas' 
    AND f.shift = '$shift' 
    AND h.fakultas = '$fakultas' 
    AND i.id is null 


    ORDER BY nama_mk";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    // $rst_mk_kelas = [];
    $num_rows_pilihan_mk = mysqli_num_rows($q);
    if (!$num_rows_pilihan_mk) {
      // die(alert("Belum ada Data Surat Tugas Perkuliahan detail untuk semester [$semester] kelas [$kelas-$shift] fakultas [$fakultas]. | <a href='?st_ajar'>Manage Surat Tugas</a>"));
      // tidak apa2 habis mungkin di prodi lain se-fakultas masih ada 
    }
    while ($d = mysqli_fetch_assoc($q)) {
      $radio_st_mk_kelas .= "
        <label class='label_mk_dosen'>
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
        # ============================================================
        # SESI BREAK
        # ============================================================
        $tr .= "
          <tr class='gradasi-kuning'>
            <td colspan=100%>
              <div class='tengah abu miring'>
                Break: $arr_sesi[info]
              </div>
            </td>
          </tr>
        ";
      } else {
        # ============================================================
        # SESI NORMAL
        # ============================================================

        # ============================================================
        # CEK JIKA SESI SUDAH TERPAKAI 
        # ============================================================
        $terpakai = $rpenjadwalan[$v['weekday']][$id_kelas][$id_sesi] ?? null;
        if ($terpakai) {
          # ============================================================
          # SUDAH TERPAKAI
          # ============================================================
          if ($terpakai['id_sesi'] == $terpakai['id_sesi_at_book']) {
            $jam_mulai = date('H:i', strtotime($terpakai['jam_mulai']));
            $jam_selesai = date('H:i', strtotime($terpakai['jam_selesai']));
            $tr .= "
              <tr>
                <td>$jam_mulai - $jam_selesai</td>
                <td>
                  $terpakai[nama_mk]
                  <div class='abu miring f14'>$terpakai[nama_lengkap_dosen]</div>
                </td>
                <td>$terpakai[nama_ruang]</td>
              </tr>
            ";
          }
          // exit;
        } elseif (
          !$rsesi[$id_sesi]['bookable']
          || ($v['weekday'] == 5 and ($id_sesi == 5 or $id_sesi == 7)) // jumat
        ) {
          # ============================================================
          # BOOKABLE FALSE
          # ============================================================
          $tr .= "
              <tr>
                <td>$awal - $akhir</td>
                <td class=tengah>
                  <i class='f12 abu'>unbookable</i>
                </td>
                <td>-</td>
              </tr>
            ";
        } else {

          # ============================================================
          # RADIO RUANG HARUS LOOP DISINI
          # ============================================================
          $radio_ruang = '';
          foreach ($rruang as $id_ruang => $arr_ruang) {
            // apakah ruangan ini sudah terpakai di hari senin sesi 12
            $id_ruang_terpakai = $rpemakaian[$v['weekday']][$id_ruang][$id_sesi]['id_ruang'] ?? null;
            if ($id_ruang_terpakai == $id_ruang) {
              $ruang_terpakai = 'ruang_terpakai';
              $disabled = 'disabled';
            } else {
              $ruang_terpakai = '';
              $disabled = '';
            }
            $radio_ruang .= "
              <label class='label_ruang $ruang_terpakai'>
                <div>
                  <input required type=radio name=id_ruang value=$arr_ruang[id] $disabled />
                  R.$arr_ruang[nama]
                </div>
              </label>
            ";
          }

          if (!$num_rows_pilihan_mk) {
            # ============================================================
            # PILIHAN MK HABIS
            # ============================================================
            $pesan = "Pilihan MK dan Surat Tugas untuk kelas [ $kelas ] habis";
            $tr .= "
              <tr>
                <td>$awal - $akhir</td>
                <td>
                  <span class='abu f12 miring'>$pesan | <a target=_blank href='?crud&tb=mk&note=$pesan'>Add</a></span>
                </td>
                <td>?</td>
              </tr>
            ";
          } else {
            # ============================================================
            # BOLEH BOOKING
            # ============================================================
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
          }
        } // END IF BOLEH BOOKING
      } // non break sesi
    } // end foreach $rsesi


    $col = 12; // aborted old code

    $cols .= "
      <div class='col-$col'>
        <div class='$hideit_kelas blok_kelas blok_kelas__$id_kelas'>
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
    $hideit_kelas = 'hideit';
  }
  $blok_jadwal .= "<div class=row>$cols</div>";
}

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  $nav_kelas
  $blok_jadwal
";
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
    $('.nav_kelas').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);

      // $('.toggle_book').prop('disabled', 0);
      $('.blok_kelas').slideUp();
      $('.blok_kelas__' + id).slideDown();
      $('.nav_kelas').removeClass('nav_kelas_active');
      $(this).addClass('nav_kelas_active');
    });
  })
</script>
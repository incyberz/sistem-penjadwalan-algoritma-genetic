<?php
# ============================================================
# JADWAL PER FAKULTAS
# ============================================================
$fakultas = $_GET['fakultas'] ?? 'FKOM';
$semester = $_GET['semester'] ?? $default_semester;
$id_shift = $_GET['id_shift'] ?? 'R';
$get_id_kelas = $_GET['id_kelas'] ?? ''; // fokus at kelas ini
$SHIFT = $id_shift == 'R' ? 'REGULER' : 'NON-REGULER';

# ============================================================
# MELIHAT JADWAL LAMA
# ============================================================
$get_id_kurikulum = $_GET['id_kurikulum'] ?? '';
if ($get_id_kurikulum) {
  $s = "SELECT * FROM tb_kurikulum WHERE id=$get_id_kurikulum";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $kurikulum = mysqli_fetch_assoc($q);
  $ta_aktif = $kurikulum['id_ta']; // replace UI dengan request id_kurikulum lama
  $semester = $ta_aktif % 2 == 0 ? 2 : 1;
  echo "<div class='p2 tengah bg-yellow darkblue f30'>JADWAL LAMA DI TA. $ta_aktif</div>";
}

# ============================================================
# GET VALIDATIONS
# ============================================================
if (!key_exists($fakultas, $rfakultas))
  die(alert("Fakultas [$fakultas] tidak ada di dalam system. | <a href='?'>Konfigurasi</a>"));
if (!key_exists($id_shift, $rshift))
  die(alert("Shift Kelas [$id_shift] tidak ada di dalam system. | <a href='?'>Konfigurasi</a>"));

include 'jadwal-functions.php';
include 'jadwal-styles.php';
include 'jadwal-processors.php';
set_title("JADWAL " . strtoupper($fakultas));

# ============================================================
# HAK AKSES
# ============================================================
$hak = [];
$hak['delete_jadwal'] = hak_akses('delete_jadwal', $user['role']);
$hak['book_jadwal'] = hak_akses('book_jadwal', $user['role']);
$hak['barter_jadwal'] = hak_akses('barter_jadwal', $user['role']);


# ============================================================
# CUSTOM HEADER AND NAVIGATIONS
# ============================================================
include 'jadwal-nav_header.php';




# ============================================================
# DATA SESI
# ============================================================
$s = "SELECT * FROM tb_sesi WHERE id_shift='$id_shift'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rsesi = [];
if (!mysqli_num_rows($q)) {
  die(alert("Belum ada Data Sesi Perkuliahan untuk shift-kelas [$id_shift]. | <a href='?crud&tb=sesi'>Manage Sesi</a>"));
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
$rkumk_count = [];
$rnama_kelas = [];
foreach ($rhari as $date => $v) {
  $nama_hari = nama_hari($date);
  $blok_jadwal .= "
    <h4 class='darkblue nama_hari p1 tengah'>$nama_hari</h4>
  ";
  $div_blok_kelas = '';
  foreach ($rkelas as $id_kelas => $arr_kelas) {
    $kelas = $arr_kelas['nama_kelas'];
    $rnama_kelas[$id_kelas] = $kelas;
    $hideit_kelas = ($get_id_kelas and $id_kelas == $get_id_kelas) ? '' : 'hideit';


    # ============================================================
    # DATA AVAILABLE ST-DETAIL | UNSIGNED JADWAL
    # ============================================================
    $radio_st_mk_kelas = '';
    $c_id_dosen = $dosen ? "c.id_dosen = $dosen[id]" : 1;


    $s = "SELECT 
    a.id,
    d.nama as nama_mk,
    d.sks,
    e.nama as nama_dosen,
    (SELECT 1 FROM tb_jadwal WHERE id=a.id) sudah_terjadwal

    FROM tb_st_detail a  
    JOIN tb_st c ON a.id_st=c.id 
    JOIN tb_kumk d2 ON a.id_kumk=d2.id 
    JOIN tb_mk d ON d2.id_mk=d.id 
    JOIN tb_dosen e ON c.id_dosen=e.id 
    JOIN tb_kelas f ON a.id_kelas=f.id 
    JOIN tb_kurikulum g ON d2.id_kurikulum=g.id 
    JOIN tb_prodi h ON g.id_prodi=h.id 
    LEFT JOIN tb_jadwal i ON a.id=i.id 
    WHERE 1 
    AND d.semester = '$semester' 
    AND f.nama = '$kelas' 
    AND f.id_shift = '$id_shift' 
    AND h.fakultas = '$fakultas' 
    AND i.id is null 
    AND $c_id_dosen


    ORDER BY nama_mk";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    // $rst_mk_kelas = [];
    $kumk_count = mysqli_num_rows($q);
    $rkumk_count[$id_kelas] = $kumk_count;

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
          $is_mine = $terpakai['id_dosen'] == $id_dosen ? 1 : 0;
          if ($terpakai['id_sesi'] == $terpakai['id_sesi_at_book']) {
            $jam_mulai = date('H:i', strtotime($terpakai['jam_mulai']));
            $jam_selesai = date('H:i', strtotime($terpakai['jam_selesai']));
            $form_delete_jadwal = "
              <form method=post class='m0 p0 inline '>
                <button class=transparan name=btn_delete_jadwal value=$terpakai[id_st_detail] onclick='return confirm(`Hapus Jadwal ini?`)'>$img_delete</button>
              </form>
            ";
            $delete_jadwal = $hak['delete_jadwal'] ? $form_delete_jadwal : '';

            // boleh delete jadwal sendiri
            $delete_jadwal = $is_mine ? $form_delete_jadwal : $delete_jadwal;

            $sks_info = "<div class='f12 miring abu'>( $terpakai[sks] SKS )</div>";
            $tr_mine = $is_mine ? 'tr_mine' : '';
            $tr .= "
              <tr class='$tr_mine'>
                <td>$jam_mulai - $jam_selesai$sks_info</td>
                <td>
                  $terpakai[nama_mk] $delete_jadwal
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
              $nama_ruang_show = "
                <a target=_blank href='?laper&id_ruang=$id_ruang&weekday=$v[weekday]' onclick='return confirm(`Ruangan ini terpakai?\n\nKlik OK untuk melihat Laporan Pemakaian Ruangan ini.`)'>
                  <span class='f10 red'>R.$arr_ruang[nama]</span>
                </a>
              ";
            } else {
              $ruang_terpakai = '';
              $disabled = '';
              $nama_ruang_show = "R.$arr_ruang[nama]";
            }
            $radio_ruang .= "
              <label class='label_ruang $ruang_terpakai'>
                <div>
                  <input required type=radio name=id_ruang value=$arr_ruang[id] $disabled />
                  $nama_ruang_show
                </div>
              </label>
            ";
          }

          if (!$kumk_count) {
            # ============================================================
            # PILIHAN MK HABIS
            # ============================================================
            $pesan = $dosen ? '-' : "Pilihan MK di Surat Tugas untuk kelas [ $kelas ] habis | <a target=_blank href='?st&note=$pesan'>Add Surat Tugas</a>";
            $s3 = "SELECT id FROM tb_kurikulum WHERE id_prodi=$arr_kelas[id_prodi] AND id_ta=$ta_aktif";
            $q3 = mysqli_query($cn, $s3) or die(mysqli_error($cn));
            $d3 = mysqli_fetch_assoc($q3);
            $id_kurikulum = $d3['id'];



            $tr .= "
              <tr>
                <td>$awal - $akhir</td>
                <td>
                  <span class='abu f12 miring'>$pesan</span>
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
                        <div class='bold f12 mb1 green'>Ruang Available:</div>
                        $radio_ruang
                      </div>
                      <button class='btn btn-primary w-100 mb2' name=btn_book value='$v[weekday]__$id_sesi'>Book</button>
                      <div class=tengah><span class='btn btn-secondary w-100 btn_cancel pointer darkred'>Cancel</span></div>
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

    $div_blok_kelas .= "
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
    ";
  }

  $blok_jadwal .= "
    <div class=blok_jadwal>
      $div_blok_kelas
    </div>
  ";
}

# ============================================================
# FINAL ECHO
# ============================================================
$script = '';
foreach ($rkumk_count as $id_kelas => $count) {
  if ($count) {
    $script .= "
      $('#kumk_count__$id_kelas').show();
      $('#kumk_count__$id_kelas').text($count);
    ";
  } else {
    $script .= "
      $('#kumk_count__$id_kelas').hide();
      $('#kumk_count__$id_kelas').text('0');
    ";
  }
}
if ($script) $script = "<script>$(function() {;$script})</script>";
echo "
  $blok_jadwal
  $script
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
      $('#form_book__' + id_kelas + '__' + weekday + '__' + id_sesi).slideDown();
      $('.toggle_book').prop('disabled', 1);
      $('#nav_kelas').slideUp();
    });
    $('.btn_cancel').click(function() {
      $('.toggle_book').prop('disabled', 0);
      $('.form_book').slideUp();
      $('#nav_kelas').slideDown();
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
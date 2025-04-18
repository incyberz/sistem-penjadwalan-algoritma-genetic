<?php
// set_h2('Minggu Efektif', "Tahun Ajaran $tahun_ta $Gg");
include 'pages/libur.php';
include 'verifikasi-ta-styles.php';
include 'verifikasi-ta-processors.php';

$arr_jenis = [
  'ME' => 'Minggu Efektif',
  'MT' => 'Minggu Tenang',
  'ML' => 'Minggu Libur',
  'UTS' => 'UTS',
  'UAS' => 'UAS',
];

# ============================================================
# DATA M.E YANG ADA
# ============================================================
$s = "SELECT * FROM tb_me WHERE id_ta=$ta_aktif";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$me = [];
while ($d = mysqli_fetch_assoc($q)) {
  $me[$d['id']] = $d;
}



# ============================================================
# MAIN CREATE UI
# ============================================================
$me_count_max = 30; // minggu, stopped by akhir TA

$blok_rows = '';
$pekan_aktif = 0;
$me_count = 0;
$uts_count = 0;
$uas_count = 0;
$senin_tanggal = $senin_pertama;
$last_jenis = '';
$telah_uts = 0;
$telah_uas = 0;
for ($pekan = 1; $pekan <= $me_count_max; $pekan++) {

  # ============================================================
  # ROWS HEADER
  # ============================================================
  if ($pekan == 1) {

    $item_tgl_header = '';
    for ($w = $weekday_start; $w <= $weekday_end; $w++) {
      $hari = $arr_hari[$w];
      $item_tgl_header .= "
        <div class='item_tgl_header'>
          <div class=proper>$hari</div>
        </div>
      ";
    }

    # ============================================================
    # BLOK HEADER
    # ============================================================
    $blok_rows .= "
      <div class='flexy mb2 gradasi-toska pt1 pb1 bold'>
        <div class=me_no_header>
          &nbsp;
        </div>
        <div>
          <div class='flexy'>$item_tgl_header</div>
        </div>
        <div>
          <div class=info_header>
            Info Libur
          </div>
        </div>
        <div>
          <div class=aksi_header>
            Change to
          </div>
        </div>
      </div>
    ";
  }

  # ============================================================
  # STOP ROWS PADA AKHIR TA
  # ============================================================
  if (strtotime($senin_tanggal) > strtotime($ta['akhir'])) {
    $info_akhir_ta = "TA $ta_aktif berakhir pada " . date("d-M-Y", strtotime($ta['akhir']));
    $Jumat = $arr_hari[$weekday_end];
    $blok_rows .= "
      <div class='flexy flex-center mb2'>
        <div>
          <div class='f12 abu'>
            TA $ta_aktif berakhir pada
          </div>
          <input name=akhir_ta id=akhir_ta type=date required value='$ta[akhir]' class='form-control'>
          <script>
            $(document).ready(function() {
              let akhir_ta = $('#akhir_ta').val();
              $('#akhir_ta').on('change', function() {
                let selectedDate = new Date($(this).val());
                let dayOfWeek = selectedDate.getDay(); // 0: Minggu, 1: Senin, ..., 6: Sabtu
                
                if (dayOfWeek !== $weekday_end) { // Jika bukan hari Senin
                    alert('Tanggal yang dipilih bukan hari $Jumat! Silakan pilih hari $Jumat.');
                    $(this).val(akhir_ta); // Mengosongkan input | mengembalikan ke value awal
                }
              });
            });
          </script>        
        </div>
      </div>      
    ";
    break;
  }


  # ============================================================
  # MAIN LOOP ROWS
  # ============================================================
  # ============================================================
  # INFO LIBUR
  # ============================================================
  $info_libur = '';
  $ada_hari_kerja = false;
  $tanggal = $senin_tanggal;
  $tgls = '';
  for ($i = $weekday_start; $i <= $weekday_end; $i++) { // LOOP WEEKDAY
    $tgl = intval(date('d', strtotime($tanggal)));
    $bln = date('M', strtotime($tanggal));
    $bg = intval(date('m', strtotime($tanggal))) % 2 == 0 ? '#efe' : '#eff';

    if (key_exists($tanggal, $libur)) { // ARRAY LIBUR
      $bg = '#faa';
      $nama_libur = $libur[$tanggal]['nama'];
      $info_libur .= "<div>$tgl $bln -  $nama_libur</div>";
    } else {
      $ada_hari_kerja = true; // termasuk ME
    }

    $id = "item_tgl__$pekan" . "__$tanggal";
    $tgls .= "
      <div class='item_tgl ' style='background:$bg' id='$id'>
        <div>
          <div>$tgl <span class=bln>$bln</span></div>
        </div>
      </div>
    ";
    $tanggal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal)));
  }
  $info_libur = $info_libur ? $info_libur : "<span class='abu miring f10'>(tidak ada libur)</span>";


  # ============================================================
  # ADA HARI KERJA = PEKAN EFEKTIF
  # ============================================================
  if ($ada_hari_kerja) {
    $pekan_aktif++;
    $pekan_fill = sprintf('%02d', $pekan);
    $id = 'TA-NO-Y-m-d';
    $id =  "$ta_aktif-$pekan_fill-$senin_tanggal"; // ME | ML | MT | UAS | UTS
    $jenis = key_exists($id, $me) ? $me[$id]['jenis'] : 'ME';
    if ($last_jenis == 'UTS' and $jenis != 'UTS') $telah_uts = 1;
    if ($last_jenis == 'UAS' and $jenis != 'UAS') $telah_uas = 1;

    if ($jenis == 'ME') {
      $me_count++;
      $me_count_fill = sprintf('%02d', $me_count);
      $blok_me_no = "<div class='f10 blue bold'>$jenis</div>$me_count_fill";
    } else {
      $blok_me_no = "<div class='f18 darkred'>$jenis</div>";
    }

    # ============================================================
    # CREATE SELECT CHANGE TO JENIS PEKAN
    # ============================================================
    $opt = '';
    foreach ($arr_jenis as $key_jenis => $title) { // LOOP JENIS PEKAN
      if (!$telah_uts and $key_jenis == 'UAS') continue;
      if ($telah_uas and ($key_jenis == 'UTS'  || $key_jenis == 'ME')) continue;
      $selected = $jenis == $key_jenis ? 'selected' : '';
      $opt .= "<option value='$senin_tanggal--$key_jenis' $selected>$title</option>";
    }
    $select = "<select class='form-control' name=me[$pekan]>$opt</select>";


    // EXCEPTION, jika setelah UAS set ke MT
    $blok_me_no = $telah_uas ? "<div class='f10 abu miring'>MT</div>" : $blok_me_no;
    $last_jenis = $jenis;
  } else { // TIDAK ADA HARI KERJA = MINGGU LIBUR
    $blok_me_no = "<div class='f10 abu miring'>ML</div>";
    $select = '<span class="abu miring">Minggu Libur</span>';
  }

  // warna border
  $warna_border = '';
  if ($jenis == 'UTS' || $jenis == 'UAS') {
    if ($jenis == 'UTS') $uts_count++;
    if ($jenis == 'UAS') $uas_count++;

    $warna_border = 'pekan_ujian gradasi-kuning';
  } elseif ($jenis == 'MT') {
    $warna_border = 'minggu_tenang gradasi-abu';
  }


  # ============================================================
  # FINAL ROWS
  # ============================================================
  $blok_rows .= "
    <div class='flexy mb2 $warna_border'>
      <div class=me_no>
        $blok_me_no
      </div>
      <div>
        <div class=flexy>
          $tgls
        </div>
      </div>
      <div>
        <div class=info>
          $info_libur
        </div>
      </div>
      <div>
        <div class=aksi>
          $select
        </div>
      </div>
    </div>
  ";
  $senin_tanggal = date('Y-m-d', strtotime("+7 day", strtotime($senin_tanggal)));
}

# ============================================================
# VERIFIKASI TA
# ============================================================
$verifikasi_ta = '';
if ($me_count >= 14 and $uts_count and $uas_count) {
  if ($ta['verif_at']) {

?>
    <script>
      $(function() {
        $('input').prop('disabled', 1);
        $('select').prop('disabled', 1);
        $('button').prop('disabled', 1);
        $('.rollback').prop('disabled', 0);
      })
    </script>


<?php
    $verifikasi_ta = "
      <div class='tengah'>
        <span class=green>Sudah terverifikasi pada [ $ta[verif_at] ] by user-id [ $ta[verif_by] ]</span>
        <span class='red hover btn_aksi' id=blok_rollback__toggle>Rollback Verifikasi</span>
      </div>
      <div class='hideit' id=blok_rollback>
        <div class='d-flex justify-content-center align-item-center'>
          <form method=post class='wadah gradasi-kuning mt3'>
            <h3 class='tengah darkred'>Rollback Verifikasi Tahun Ajar</h3>
            <p class=darkabu>Saat TA belum diverifikasi, <br>Anda <b>TIDAK</b> dapat mengakses fitur:</p>
            <ul>
              <li><a target=_blank href='?manage_sesi'>Manage Sesi Perkuliahan</a>;</li> 
              <li><a target=_blank href='?manage_presensi_mhs'>Manage Presensi Mahasiswa</a>; dan </li>
              <li><a target=_blank href='?manage_presensi_dosen'>Manage Presensi Dosen</a>.</li>
            </ul>
            <label class='d-block mb1'>
              <input type=checkbox required class=rollback>
              Saya menyatakan bahwa terdapat Revisi Seting TA.
            </label>
  
            <button class='btn btn-danger w-100 mt3 rollback' name=btn_rollback_verifikasi_TA id=btn_rollback_verifikasi_TA>Confirm Rollback TA</button>
  
          </form>
        </div>
  
      </div>
    ";
  } else {

    $verifikasi_ta = "
      <div class='tengah'>
        <span class='btn btn-success btn_aksi' id=blok_verifikasi__toggle>Verifikasi Tahun Ajar</span>
      </div>
      <div class='hideit' id=blok_verifikasi>
        <div class='d-flex justify-content-center align-item-center'>
          <form method=post class='wadah gradasi-kuning mt3'>
            <h3 class='tengah purple'>Verifikasi Tahun Ajar</h3>
            <p class=darkabu>Setelah diverifikasi Seting TA tidak bisa lagi diubah, <br>namun Anda dapat mengakses fitur:</p>
            <ul>
              <li><a target=_blank href='?manage_sesi'>Manage Sesi Perkuliahan</a>;</li> 
              <li><a target=_blank href='?manage_presensi_mhs'>Manage Presensi Mahasiswa</a>; dan </li>
              <li><a target=_blank href='?manage_presensi_dosen'>Manage Presensi Dosen</a>.</li>
            </ul>
            <label class='d-block mb1'>
              <input type=checkbox required>
              Saya menyatakan bahwa Penanggalan TA sudah benar.
            </label>
            <label class='d-block mb1'>
              <input type=checkbox required>
              Saya menyatakan bahwa Perencanaan Minggu Efektif sudah tepat.
            </label>
            <label class='d-block mb1'>
              <input type=checkbox required>
              Saya menyatakan bahwa Penyesuaian dengan Hari Libur sudah dilakukan.
            </label>
  
            <button class='btn btn-primary w-100 mt3' name=btn_confirm_verifikasi_TA>Confirm Verifikasi TA</button>
  
          </form>
        </div>
  
      </div>
    ";
  }
} else {
  $verifikasi_ta = '<div class="abu f10 miring tengah"><div class="darkred f12">belum bisa verifikasi.</div>minggu efektif minimal 14, uts minimal 1, uas minimal 1</div>';
}

# ============================================================
# FINAL ECHO
# ============================================================
$selected_weekday_end_5 = $weekday_end == 5 ? 'selected' : '';
$selected_weekday_end_6 = $weekday_end == 6 ? 'selected' : '';
echo "
  <form method=post class='flexy flex-center'>
    <div class='flexy'>
      <div>
        <div class='f12 abu'>
          Senin Pertama Perkuliahan
        </div>
        <input name=senin_pertama id=senin_pertama type=date required value='$ta[senin_pertama]' class='form-control'>
        <script>
          $(document).ready(function() {
            let senin_pertama = $('#senin_pertama').val();
            $('#senin_pertama').on('change', function() {
              let selectedDate = new Date($(this).val());
              let dayOfWeek = selectedDate.getDay(); // 0: Minggu, 1: Senin, ..., 6: Sabtu
              
              if (dayOfWeek !== 1) { // Jika bukan hari Senin
                  alert('Tanggal yang dipilih bukan hari Senin! Silakan pilih hari Senin.');
                  $(this).val(senin_pertama); // Mengosongkan input | mengembalikan ke value awal
              }
            });
          });
        </script>
      </div>
      <div>
        <div class='f12 abu'>
          Awal Pekan
        </div>
        <select name=weekday_start class='form-control' disabled>
          <option value=1>Hari Senin</option>
          <option value=0>Hari Ahad</option>
        </select>
      </div>
      <div>
        <div class='f12 abu'>
          Akhir Pekan
        </div>
        <select name=weekday_end class='form-control'>
          <option value=5 $selected_weekday_end_5>Hari Jumat</option>
          <option value=6 $selected_weekday_end_6>Hari Sabtu</option>
        </select>
      </div>
    </div>
    <div class='wadah'>
      $blok_rows
      <button class='btn btn-primary w-100' name=btn_save_TA value='$ta_aktif-$tanggal'>Save Seting Tahun Ajar</button>
    </div>
  </form>
  $verifikasi_ta
";
?>
<script>
  $(function() {
    $('.item_tgl').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let pekan = rid[1];
      let tanggal = rid[2];
      console.log(aksi, pekan, tanggal);
    })
  })
</script>
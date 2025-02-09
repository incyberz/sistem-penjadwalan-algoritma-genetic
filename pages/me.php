<style>
  .item_tgl {
    height: 80px;
    width: 80px;
    text-align: center;
    border: solid 1px #eee;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .2s;
    cursor: pointer;
  }

  .item_tgl:hover,
  .item_tgl_active {
    border: solid 4px blue;
    letter-spacing: .5px;
    color: blue;
    font-weight: bold;
  }

  .me_no {
    color: darkblue;
    font-size: 30px;
    width: 50px;
    text-align: right;
  }

  .bln {
    font-size: 9px;
  }

  .info {
    border: solid 1px #ddd;
    border-radius: 5px;
    width: 200px;
    height: 100%;
    padding: 10px;
    font-size: 10px;
  }

  .pekan_ujian,
  .minggu_tenang {
    border: solid 2px darkred;
    border-radius: 10px;
    padding: 15px 15px 15px 0;
  }

  .minggu_tenang {
    border: solid 2px #ccc;
  }
</style>
<?php
set_h2('Minggu Efektif', "Tahun Ajaran $tahun_ta $Gg");
include 'pages/libur.php';
include 'me-processors.php';

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

  if (strtotime($senin_tanggal) > strtotime($ta['akhir'])) {
    $info_akhir_ta = "TA $ta_aktif berakhir pada " . date("d-M-Y", strtotime($ta['akhir']));
    $blok_rows .= "
      <div class='mt3 mb2'>
        <div class='abu miring f12 tengah'>
          )* $info_akhir_ta
        </div>
      </div>
    ";
    break;
  }
  $info_libur = '';
  $ada_hari_kerja = false;
  $tanggal = $senin_tanggal;
  $tgls = '';
  $autos = 'auto';
  for ($i = $weekday_start; $i <= $weekday_end; $i++) {
    $autos .= ' auto';
    $tgl = intval(date('d', strtotime($tanggal)));
    $bln = date('M', strtotime($tanggal));
    $bg = intval(date('m', strtotime($tanggal))) % 2 == 0 ? '#efe' : '#eff';

    if (key_exists($tanggal, $libur)) {
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

    $opt = '';
    foreach ($arr_jenis as $key_jenis => $title) {
      if (!$telah_uts and $key_jenis == 'UAS') continue;
      if ($telah_uas and ($key_jenis == 'UTS'  || $key_jenis == 'ME')) continue;
      // if ($key_jenis == 'UTS' and $last_jenis != 'UTS') $telah_uts = 1;
      // if ($key_jenis == 'UAS' and $last_jenis != 'UAS') $telah_uas = 1;
      $selected = $jenis == $key_jenis ? 'selected' : '';
      $opt .= "<option value='$senin_tanggal--$key_jenis' $selected>$title</option>";
    }

    $select = "
      <select class='form-control' name=me[$pekan]>
        $opt
      </select>    
    ";

    // jika setelah UAS
    $blok_me_no = $telah_uas ? "<div class='f10 abu miring'>MT</div>" : $blok_me_no;
    $last_jenis = $jenis;
  } else {
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

  $info_libur = $info_libur ? $info_libur : "<span class='abu miring f10'>(tidak ada libur)</span>";

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
# VERIFIKASI ME
# ============================================================
$verifikasi_me = '';
if ($me_count >= 14 and $uts_count and $uas_count) {
  $verifikasi_me = 'Verifikasi Minggu Efektif';
} else {
  $verifikasi_me = '<div class="abu f10 miring tengah"><div class="darkred f12">belum bisa verifikasi.</div>minggu efektif minimal 14, uts minimal 1, uas minimal 1</div>';
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
      <button class='btn btn-primary w-100' name=btn_save_ME value='$ta_aktif-$tanggal'>Save Minggu Efektif</button>
    </div>
  </form>
  $verifikasi_me
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
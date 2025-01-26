<?php
// set_h2('Create Surat Tugas', "$tahun_ta");

# ============================================================
# LIST KELAS
# ============================================================
$list_kelas = '';
include 'st-list_kelas.php';

$list_mk = '';
$list_dosen = '';
$blok_dosen = '<div class="tengah bold mb2">Buat Surat Tugas untuk:</div>';

$btn_create = '';

if ($pesan_error) {
  echo $pesan_error;
} else if ($id_dosen) {

  # ============================================================
  # LIST mk
  # ============================================================
  include 'st-list_mk.php';
  $disabled_mk_unavailable = $mk_available ? '' : 'disabled';
  $disabled_mk_unavailable_info = $mk_available ? '' : 'Tidak ada MK available';

  $blok_dosen = "
    <input type=hidden name=id_ta value=$kurikulum[id_ta]>
    <hr>
    <div class='tengah'>
      <h3 class='bold f18 m0'>SURAT TUGAS PENGAJARAN TA. $tahun_ta $GG</h3>
      <div class='consolas mb4'>No. [AUTO]/E-UM/$bulan_romawi/$Tahun</div>
    </div>

    <p>
      Yang bertanda tangan di bawah ini Dekan Fakultas Komputer, menugaskan kepada:
    </p>
    <ul class=hideita id=dosen_selected>
      <li><b>Nama:</b> <span id=nama_dosen_selected class='blue bold'>$nama_lengkap_dosen</span></li>
      <li><b>NIDN:</b> <span id=nidn_dosen_selected>$dosen[nidn]</span></li>
    </ul>  
  ";

  $btn_create = "
    <div class='blok_btn'>
      <div class='red'>$disabled_mk_unavailable_info</div>
      <button class='btn btn-primary w-100 mt2' name=btn_create_st id=btn_create_st $disabled_mk_unavailable>$Create Surat Tugas</button>
    </div>
  ";
} else {
  # ============================================================
  # LIST DOSEN
  # ============================================================
  if ($siap_assign) {
    include 'st-list_dosen.php';
  } else {
    $list_dosen .= $pesan_error;
  }
}

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <form method=post>
    $blok_dosen
    $list_dosen
    $list_mk
    $btn_create
  </form>
";

?>

<script>
  $(function() {
    $('.label_mk').click(function() {
      if ($('.check_mk:checked').length) {
        $('.blok_btn').fadeIn();
      } else {
        $('.blok_btn').fadeOut();
      }
    })
  })
</script>
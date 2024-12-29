<?php
set_h2('Create Surat Tugas', $dkur['nama']);

# ============================================================
# LIST KELAS
# ============================================================
$list_kelas = '';
include 'st_ajar-list_kelas.php';

# ============================================================
# LIST mk
# ============================================================
$list_mk = '';
include 'st_ajar-list_mk.php';

# ============================================================
# LIST DOSEN
# ============================================================
$list_dosen = '';
if ($siap_assign) {
  include 'st_ajar-list_dosen.php';
} else {
  $list_dosen .= $pesan_error;
}



echo "
  <form method=post>
    <input type=hidden name=id_ta value=$dkur[id_ta]>
    <hr>
    <div class='tengah'>
      <h3 class='bold f18 m0'>SURAT TUGAS PENGAJARAN</h3>
      <div class='consolas mb4'>No. [AUTO]/E-UM/$bulan_romawi/$Tahun</div>
    </div>

    <p>
      Yang bertanda tangan di bawah ini Dekan Fakultas Komputer, menugaskan kepada:
    </p>
    <ul class=hideit id=dosen_selected>
      <li><b>Nama:</b> <span id=nama_dosen_selected>Iin, M.Kom</span></li>
      <li><b>NIDN:</b> <span id=nidn_dosen_selected>12345678</span></li>
      <li class=hideita><input name=id_dosen id=id_dosen></li>
    </ul>

    <div class='row' id=list_dosen>
      <div class='col-sm-12'>
        <div class='wadah gradasi-hijau'>
          <div class='mb1 f12'>Pilih salah satu Dosen:</div>
          $list_dosen
        </div>
      </div>
    </div>

    <div id=pilih_mk class=hideit>
      <p>Untuk mengampu matakuliah sebagai berikut:</p>
      <div class=row id=list_mk>
        <div class='col-sm-12'>
          <div class='wadah gradasi-toska'>
            <div class='mb1 f12'>Ketik dan Pilihan MK Prodi semester $Ganjil:</div>
            $list_mk
            <div class='blok_btn hideit'>
              <button class='btn btn-primary w-100 mt2' name=btn_create_st id=btn_create_st>Create Surat Tugas</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
";
?>

<script>
  $(function() {
    $('.label_dosen').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      let nama_dosen = $('#nama_dosen__' + id).text();
      let gelar_depan = $('#gelar_depan__' + id).text();
      let gelar_belakang = $('#gelar_belakang__' + id).text();
      let nidn = $('#nidn__' + id).text();
      if (gelar_depan) nama_dosen = gelar_depan + ' ' + nama_dosen;
      if (gelar_belakang) nama_dosen += ', ' + gelar_belakang;
      console.log(aksi, id, nama_dosen, nidn);

      $('#id_dosen').val(id);
      $('#nama_dosen_selected').text(nama_dosen);
      $('#nidn_selected').text(nidn);
      $('#list_dosen').slideUp();
      $('#dosen_selected').slideDown();
      $('#pilih_mk').slideDown();


    });

    $('.label_mk').click(function() {
      if ($('.check_mk:checked').length) {
        $('.blok_btn').fadeIn();
      } else {
        $('.blok_btn').fadeOut();
      }
    })
  })
</script>
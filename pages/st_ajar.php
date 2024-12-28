<?php
# ============================================================
# SK AJAR
# ============================================================
$id_kurikulum = $_GET['id_kurikulum'] ?? udef('id_kurikulum');
$siap_assign = true;

# ============================================================
# DATA KURIKULUM
# ============================================================
$s = "SELECT a.*,
(SELECT nama FROM tb_prodi WHERE id=a.id_prodi) nama_prodi  
FROM tb_kurikulum a WHERE a.id = $id_kurikulum";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$dkur = mysqli_fetch_assoc($q);

set_h2('Surat Tugas', $dkur['nama']);

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



echo "
  <div class='row'>
    <div class='col-sm-4'>
      <div class='wadah gradasi-hijau'>
        <div class='mb1 f12'>Ketik dan Pilih Dosen:</div>
        <input id=keyword_dosen class='form-control'>
      </div>

    </div>
    <div class='col-sm-4'>
      <div class='wadah gradasi-hijau'>
        <div class='mb1 f12'>Ketik dan Pilih MK:</div>
        $list_mk
      </div>

    </div>
    <div class='col-sm-4'>
      <div class='wadah gradasi-hijau'>
        <div class='mb1 f12'>Untuk kelas:</div>
        $list_kelas
      </div>

    </div>
  </div>
";

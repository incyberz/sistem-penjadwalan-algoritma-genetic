<?php
# ============================================================
# STRUKTUR KURIKULUM
# ============================================================
function tbs_mk($last_semester, $thead, $tr_mk, $sum_sks)
{
  return "
    <div class='col-6'>
      <h4 class='darkblue mt2'>Semester $last_semester</h4>
      <table class='table table-hover table-striped'>
        $thead
        $tr_mk
        <tfoot class='bold gradasi-kuning'>
          <td colspan=2 class=right>JUMLAH SKS</td>
          <td colspan=2>$sum_sks</td>
        </tfoot>
      </table>
    </div>
  ";
}

$id_prodi = $_GET['id_prodi'] ?? '';

$d_kur = [];
if (!$id_prodi) {
  # ============================================================
  # WAJIB PILIH PRODI
  # ============================================================
  $s = "SELECT * FROM tb_prodi ORDER BY jenjang, nama";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $list = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $list .= "<div class='bordered br5 p2 gradasi-hijau'><a href='?struktur_kurikulum&id_prodi=$d[id]'>$d[jenjang] - $d[nama]</a></div>";
  }
  echo "
    <div>Struktur Kurikulum untuk prodi:</div>
    <div class='flexy wadah mt2'>$list</div>
  ";
} else {

  # ============================================================
  # STRUKTUR KURIKULUM GANJIL GENAP
  # ============================================================
  $thead = "
    <thead>
      <th>No</th>
      <th>MK</th>
      <th>SKS</th>
    </thead>
  ";

  # ============================================================
  # PROPERTI PRODI
  # ============================================================
  $s = "SELECT * FROM tb_prodi WHERE id=$id_prodi";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $prodi = mysqli_fetch_assoc($q);



  # ============================================================
  # MAIN SELECT MK | ALL SEMESTER | THIS PRODI | GANJIL GENAP
  # ============================================================
  $s = "SELECT 
  a.*,
  d.id as id_prodi,  
  d.singkatan as prodi,
  (SELECT COUNT(1) FROM tb_st_mk WHERE id_mk=a.id) count_st_mk  
  FROM tb_mk a 
  JOIN tb_kumk b ON a.id=b.id_mk 
  JOIN tb_kurikulum c ON b.id_kurikulum=c.id 
  JOIN tb_prodi d ON c.id_prodi=d.id 
  -- WHERE b.id_ta = $ta_aktif 
  WHERE c.id_ta LIKE '$tahun_ta%' 
  AND c.id_prodi = $id_prodi 
  ORDER BY  b.semester, a.nama";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $num_rows = mysqli_num_rows($q);
  $tr_mk = '';
  $tbs_mk = '';
  $last_semester = '';
  $sum_sks = 0;
  $total_sks = 0;
  if ($num_rows) {
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      if ($last_semester != $d['semester']) $i = 1;
      if ($last_semester != $d['semester'] and $last_semester !== '') {
        $tbs_mk .= tbs_mk($last_semester, $thead,  $tr_mk, $sum_sks);
        $tr_mk = '';
        $sum_sks = 0;
      }
      $sum_sks += $d['sks'];
      $total_sks += $d['sks'];

      $last_semester = $d['semester'];

      $tr_mk .= "
        <tr 
          class='hideita tr_mk tr_mk__$d[prodi] tr_mk__$d[id_prodi]__$d[semester]' 
          id=tr_mk__$d[id]
        >
          <td>$i</td>
          <td>$d[nama]</td>
          <td>$d[sks]</td>
        </tr>
      ";
    } // end while
    // add last tr to tb
    $tbs_mk .= tbs_mk($last_semester, $thead,  $tr_mk, $sum_sks);
  }

  set_title("$prodi[jenjang]-$prodi[singkatan] - Struktur Kurikulum");

  echo "
    <div class='tengah gradasi-toska p-3'>
      <h2>STRUKTUR KURIKULUM</h2>
      <h3>$prodi[nama] $tahun_ta</h3>
    </div>
    <div class=row>  
      $tbs_mk
    </div>
    <div class='border-top border-bottom gradasi-kuning mt-4 p2 tengah'>  
      <b>TOTAL : </b> <span id=total_sks class='f30'>$total_sks</span> SKS
    </div>
  ";
}

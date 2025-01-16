<?php
# ============================================================
# TR MK | TABLE ROW TIAP SEMESTER
# ============================================================
$s = "SELECT 
a.id as id_mk,
a.sks,
b.id as id_kumk,
b.semester,
a.nama as nama_mk,
a.id_prodi,  
c.id as id_kurikulum,
d.singkatan as prodi,
(SELECT COUNT(1) FROM tb_st_mk WHERE id_kumk=b.id) count_st,
( 
  SELECT p.id FROM tb_st p 
  JOIN tb_st_mk q ON p.id=q.id_st 
  WHERE q.id_kumk=b.id LIMIT 1) id_st_pertama  
FROM tb_mk a 
JOIN tb_kumk b ON a.id=b.id_mk 
JOIN tb_kurikulum c ON b.id_kurikulum=c.id 
JOIN tb_prodi d ON c.id_prodi=d.id 
-- WHERE b.id_ta = $ta_aktif 
WHERE c.id_ta LIKE '$tahun_ta%' 
AND c.id_prodi = $id_prodi 
AND b.semester = '$semester'
ORDER BY  b.semester, a.nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$num_rows = mysqli_num_rows($q);
if (!$num_rows) {
  // echolog("Tidak ada MK di semester $semester");
  $tr_mk .= "
    <tr>
      <td colspan=100%><div class='alert alert-danger tengah'>Belum ada data MK di semester $semester</div></td>
    </tr>
  ";
} else {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $rmk[$semester][$d['id_mk']] = $d; // updating array assigned mk
    $i++;
    $sum_sks += $d['sks'];
    $total_sks += $d['sks'];

    $last_semester = $d['semester'];

    $dosen_pengampu = '-';
    include 'struktur_kurikulum-tr_mk-dosen_pengampu.php';

    $link_to_st = '';
    $form_drop = '';
    if ($mode_edit) {
      if ($d['count_st']) {
        $form_drop = "
          <span onclick='alert(`Tidak dapat Drop karena sudah ada $d[count_st] Surat Tugas`)'>$img_drop_disabled</span>
        ";
        $link_to_st = " | <a href='?st_ajar&id_kurikulum=$d[id_kurikulum]&aksi=manage&id_st=$d[id_st_pertama]'>Surat Tugas ($d[count_st])</a>";
      } else {
        $form_drop = "
          <form method=post class='d-inline'>
            <button class='transparan' name=btn_drop_mk value=$d[id_kumk] onclick='return confirm(`Drop MK ini?`)'>
              $img_drop
            </button>
          </form>
        ";
      }
    }

    $MKDU = $d['id_prodi'] ? '' : $MKDU_badge;

    $tr_mk .= "
      <tr class='hideita tr_mk tr_mk__$d[prodi] tr_mk__$d[id_prodi]__$d[semester]' id=tr_mk__$d[id_kumk]>
        <td>$i</td>
        <td>$form_drop $d[nama_mk] $MKDU $link_to_st</td>
        <td>$d[sks]</td>
        <td>$dosen_pengampu</td>
      </tr>
    ";
  } // end while
} // end if num_rows
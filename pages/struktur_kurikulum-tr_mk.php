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
(
  SELECT COUNT(1) FROM tb_st_detail 
  WHERE id_kumk=b.id 
  AND id_shift='$id_shift') count_st_detail
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
  $masuk_mode_editing = $mode == 'edit' ? 'Silahkan Tambah dan Assign MK Baru' : " | <a href='?struktur_kurikulum&id_prodi=$id_prodi&mode=edit&semester=$semester&id_shift=$id_shift'>Masuk Mode Editing</a>";
  $tr_mk .= "
    <tr>
      <td colspan=100%>
        <div class='alert alert-danger tengah'>
          Belum ada data MK di semester [$semester] $masuk_mode_editing 
        </div>
      </td>
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

    $link_to_st = '';
    if ($d['count_st_detail']) {
      $s2 = "SELECT 
      a.id as id_st, 
      c.id as id_dosen, 
      c.nama as dosen_pengampu 
      FROM tb_st a 
      JOIN tb_st_detail b ON b.id_st=a.id 
      JOIN tb_dosen c ON a.id_dosen=c.id 
      WHERE b.id_kumk='$d[id_kumk]' 
      AND b.id_shift = '$id_shift'
      ";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $d2 = mysqli_fetch_assoc($q2);
      $d['dosen_pengampu'] = $d2['dosen_pengampu'];
      $d['id_dosen'] = $d2['id_dosen'];
      $d['id_st'] = $d2['id_st'];
      $link_to_st = "<a onclick='return confirm(`Cek Surat Tugas?`)' href='?st_ajar&aksi=manage&id_st=$d[id_st]'>$img_next</a>";
    } else {
      $d['dosen_pengampu'] = '-';
      $d['id_dosen'] = null;
      $d['id_st'] = null;
    }

    if ($mode_edit) {
      if ($rkelas_aktif) { // sudah ada kelas-kelas aktif di semester ini
        $dosen_pengampu = "
          <div class='flexy flex-between'>
            <div>
              <span id=dosen_pengampu__$d[id_kumk]>$d[dosen_pengampu]</span>
              <span class=btn_aksi id=blok_edit_dosen_pengampu$d[id_kumk]__toggle>$img_edit</span> 
            </div>
            <div>
              $link_to_st
            </div>
          </div>
        ";
        include 'struktur_kurikulum-tr_mk-blok_edit_dosen_pengampu.php';
        $dosen_pengampu .= $blok_edit_dosen_pengampu;
      } else {
        $dosen_pengampu = '<i class="red f12">belum bisa assign</i>';
      }
    } else {
      $dosen_pengampu = $d['dosen_pengampu'];
    }

    $form_drop = '';
    if ($mode_edit) {
      if ($d['count_st_detail']) {
        $form_drop = "
          <span onclick='alert(`Tidak dapat Drop karena sudah ada $d[count_st_detail] Surat Tugas`)'>$img_drop_disabled</span>
        ";
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
        <td>$form_drop $d[nama_mk] $MKDU </td>
        <td>$d[sks]</td>
        <td>$dosen_pengampu</td>
      </tr>
    ";
  } // end while
} // end if num_rows
<?php
$confirm_set_all_dosen_sebelumnya = $_GET['confirm_set_all_dosen_sebelumnya'] ?? null;
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
  AND id_shift='$id_shift') count_st_detail,
(
  SELECT COUNT(1) FROM tb_st_detail p 
  JOIN tb_jadwal q ON p.id=q.id 
  WHERE p.id_kumk=b.id 
  AND p.id_shift='$id_shift') terjadwal,
(
  -- SELECT COUNT(1) FROM tb_dosen p
  SELECT CONCAT(p.id,'-',p.nama) FROM tb_dosen p
  JOIN tb_st q ON p.id=q.id_dosen 
  JOIN tb_st_detail r ON q.id=r.id_st 
  JOIN tb_kumk s ON r.id_kumk=s.id 
  WHERE s.id_mk = a.id 
  AND r.id_shift = '$id_shift' 
  AND q.id_ta = $ta_sebelumnya
  ) dosen_sebelumnya

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
    $terjadwal = $d['terjadwal'];

    $link_to_st = '';
    if ($d['count_st_detail']) {
      # ============================================================
      # INFO DOSEN PENGAMPU JIKA SUDAH ASSIGN
      # ============================================================
      $s2 = "SELECT 
      a.id as id_st, 
      b.id as id_st_detail, 
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
      $d['dosen_pengampu'] = $d2['dosen_pengampu']; // info dosen pengampu jika sudah assign
      $d['id_dosen'] = $d2['id_dosen'];
      $d['id_st'] = $d2['id_st'];
      $link_to_st = "<a onclick='return confirm(`Cek Surat Tugas?`)' href='?st&aksi=manage&id_st=$d[id_st]'>$img_next</a>";
    } else {
      $d['dosen_pengampu'] = '-';
      $d['id_dosen'] = null;
      $d['id_st'] = null;
    }


    $id_dosen_sebelumnya = null;
    $nama_dosen_sebelumnya = null;
    if ($d['dosen_sebelumnya']) {
      $t = explode('-', $d['dosen_sebelumnya']);
      $id_dosen_sebelumnya = $t[0];
      $nama_dosen_sebelumnya = strlen($t[1]) <= 15 ? $t[1] : substr($t[1], 0, 12) . '...';
      if ($id_dosen_sebelumnya == $d['id_dosen']) {
        $nama_dosen_sebelumnya = '(sama)';
      } else {
        $semua_dosen_sebelumnya_sama = 0;

        if ($confirm_set_all_dosen_sebelumnya) {
          # ============================================================
          # SET DOSEN SEBELUMNYA KE KUMK INI
          # ============================================================
          $id_kumk = $d['id_kumk'] ?? die(udef('id_mk'));

          // CEK APAKAH DOSEN INI SUDAH PUNYA ST? || INSERT | UPDATE TB_ST
          $id_st = "$ta_aktif-$id_dosen_sebelumnya";
          $s2 = "INSERT INTO tb_st (
            id,
            id_dosen,
            id_ta,
            tanggal,
            id_user
          ) VALUES (
            '$id_st',
            $id_dosen_sebelumnya,
            $ta_aktif,
            CURRENT_TIMESTAMP,
            $id_user
          ) ON DUPLICATE KEY UPDATE id_user=$id_user";
          echolog('Insert | Update ST Dosen...');
          $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));

          # ============================================================
          # 1 KU-MK WAJIB 1 DOSEN || DELETE ST DETAIL SEBELUMNYA 
          # ============================================================
          $s2 = "DELETE FROM tb_st_detail WHERE id_kumk='$id_kumk'";
          echolog('1 KU-MK WAJIB 1 DOSEN | checking rule...');
          $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));


          foreach ($rkelas_aktif as $nama_kelas => $arr_kelas) {
            echolog("Inserting ST Detail untuk kelas [$nama_kelas]...");
            $id_kelas = $arr_kelas['id_kelas'];
            # ============================================================
            # INSERT ST-DETAIL
            # ============================================================
            $id_st_detail = "$ta_aktif-$id_dosen_sebelumnya-$id_kumk-$id_kelas-$id_shift"; // TA-DS-KU-MK-KLS-SHIFT 	
            $unik_kumk = "$id_kumk-" . strtolower($id_shift); // KU MK SHIFT
            $s2 = "INSERT INTO tb_st_detail (
              id,
              id_st,
              id_kumk,
              id_kelas,
              id_shift
            ) VALUES (
              '$id_st_detail',
              '$id_st',
              '$id_kumk',
              '$id_kelas',
              '$id_shift'
            ) 
            ON DUPLICATE KEY UPDATE -- Rule: 1 dosen 1 MK 1 shift
              id='$id_st_detail',
              id_st='$id_st',
              id_kumk='$id_kumk',
              id_kelas='$id_kelas',
              id_shift='$id_shift'
            ";
            echolog('Insert ST Detail...');
            $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
          }
        } // end confirm_set_all_dosen_sebelumnya

      } // end dosen sebelumnya != dosen skg (exists)
    } // end if dosen_sebelumnya exists



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

    $td_dosen_sebelumnya = !$mode_edit ? '' : "
      <td>
        <span class='f12 abu miring'>
          $nama_dosen_sebelumnya
        </span>
      </td>    
    ";

    # ============================================================
    # EDIT NAMA | EDIT SKS
    # ============================================================
    $edit_sks = '';
    $edit_nama_mk = '';
    if ($mode_edit) {
      $edit_nama_mk = "
        <span class=btn_aksi id=form_ubah_nama_mk$d[id_kumk]__toggle>$img_edit</span>
        <form method=post id=form_ubah_nama_mk$d[id_kumk] class='wadah mt2 gradasi-kuning hideit'>
          <input required minlength=3 maxlength=30 name=nama_mk value='$d[nama_mk]' class='form-control upper' />
          <div class='mt1 mb2 abu miring f12'>hanya diperbolehkan A-Z, 0-9, dan tanda kurung ( )</div>
          <button class='btn btn-warning btn-sm mt2' name=btn_update_nama_mk value=$d[id_mk]>Update Nama MK</button>
        </form>
      ";

      if ($terjadwal) {
        $edit_sks = "<span onclick='alert(`Tidak bisa mengubah SKS karena sudah terjadwal.`)'>$img_edit_disabled</span>";
      } else {

        $radios = '';
        for ($sks = 1; $sks <= 6; $sks++) {
          $disabled = $sks == $d['sks'] ? 'disabled' : '';
          $radios .= "
            <label class='d-block'>
              <input type=radio name=sks value=$sks $disabled> $sks
            </label>
          ";
        }

        $edit_sks = "
          <span class=btn_aksi id=form_ubah_sks$d[id_kumk]__toggle>$img_edit</span>
          <form method=post id=form_ubah_sks$d[id_kumk] class='wadah mt2 gradasi-kuning hideit'>
            $radios
            <button class='btn btn-warning btn-sm mt2' name=btn_update_sks value=$d[id_mk]>Update SKS</button>
          </form>
        ";
      }
    }

    # ============================================================
    # FINAL OUTPUT TR
    # ============================================================
    $tr_mk .= "
      <tr class='hideita tr_mk tr_mk__$d[prodi] tr_mk__$d[id_prodi]__$d[semester]' id=tr_mk__$d[id_kumk]>
        <td>$i</td>
        <td>$form_drop $d[nama_mk] $MKDU $edit_nama_mk</td>
        <td>$d[sks] $edit_sks</td>
        <td>$dosen_pengampu</td>
        $td_dosen_sebelumnya
      </tr>
    ";
  } // end while
} // end if num_rows

if ($confirm_set_all_dosen_sebelumnya) {
  echolog('Set All Dosen Sebelumnya sukses.');
  jsurl("?struktur_kurikulum&id_prodi=$id_prodi&mode=edit&semester=$semester&id_shift=$id_shift");
}

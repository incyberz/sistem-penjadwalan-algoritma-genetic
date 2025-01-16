<?php
# ============================================================
# PILIHAN MK PER SEMESTER PADA STRUKTUR KURIKULUM
# ============================================================
$pilihan_mk = "<div class='f14 abu miring mb2'>)* tidak ada pilihan MK di semester $semester yang dapat di-assign</div>";
$col = 12;

$s2 = "SELECT 
a.id as id_mk, 
a.nama as nama_mk, 
a.sks,
a.id_prodi,
(SELECT COUNT(1) FROM tb_kumk WHERE id_mk=a.id) count_assign

FROM tb_mk a
WHERE (a.id_prodi=$id_prodi OR a.id_prodi is null) 
AND a.semester = '$semester' 
-- AND b.id is null 
ORDER BY a.nama
";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
if (mysqli_num_rows($q2)) {
  $pilihan_mk = '';
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $id_mk_assigned = $rmk[$semester][$d2['id_mk']]['id_mk'] ?? '';
    $MKDU = $d2['id_prodi'] ? '' : $MKDU_badge;
    $cbmk_id = "cbmk-$d2[id_prodi]-$id_kurikulum-$semester-$d2[id_mk]";

    if ($d2['id_mk'] == $id_mk_assigned) { // sudah assign
      $blok_input = "
        <div class='miring abu'>
          <input disabled type=checkbox /> $d2[nama_mk] $MKDU <span class=green>(assigned)</span>
        </div>
      ";
    } else { // belum di assign

      if ($d2['count_assign']) {
        $form_delete = "
          <span onclick='alert(`Tidak dapat menghapus MK karena sudah di assign di Struktur Kurikulum prodi lain.`)'>
            $img_delete_disabled 
          </span>
        ";
      } else {
        $form_delete = "
          <form method=post class='d-inline'> 
            <button class='transparan' onclick='return confirm(`Hapus MK ini?\n\n`)' name=btn_hapus_mk value=$d2[id_mk]>$img_hapus</button>
          </form>
        ";
      }

      $blok_input = "
        <div class='row'>
          <div class=col-8>
            <input type=checkbox value='$d2[id_mk]' class='cbmk cbmk-$semester' id=$cbmk_id /> $d2[nama_mk] $MKDU
          </div>
          <div class=col-2>
            $d2[sks] SKS 
          </div>
          <div class='col-2 kanan'>
            <a onclick='return confirm(`Edit MK ini?`)' href='?detail&tb=mk&id=$d2[id_mk]'>$img_edit</a>
            $form_delete            
          </div>
        </div>
      ";
    }
    $pilihan_mk .= "
      <label>
        $blok_input
      </label>
    ";
  }
  $pilihan_mk = "
    <div class='wadah bg-white mt2 blok_pilihan_mk' id=blok_pilihan_mk__$id_kurikulum>
      <h4>Pilihan MK (Assign)</h4>
      <div class=mb2>Pilihan MK Semester $semester Prodi $singkatan_prodi</div>
      <div class=mb2>
        $pilihan_mk
      </div>
      <form method=post>
        <button class='btn btn-primary' id='btn_assign_mk-$semester' name='btn_assign_mk' disabled>Assign MK</button>
      </form>       
    </div>
  ";
}

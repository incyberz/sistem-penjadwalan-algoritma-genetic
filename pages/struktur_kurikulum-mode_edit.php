<?php

$col = 12;

$pilihan_mk = '';
$s2 = "SELECT 
a.id as id_mk, 
a.nama as nama_mk, 
a.sks,
a.id_prodi

FROM tb_mk a
LEFT JOIN tb_kumk b ON a.id=b.id_mk 
WHERE 1 
AND a.semester = '$semester' 
AND b.id is null 
ORDER BY a.nama
";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
if (mysqli_num_rows($q2)) {
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $MKDU = $d2['id_prodi'] ? '' : $MKDU_badge;
    $cbmk_id = "cbmk-$d2[id_prodi]-$id_kurikulum-$semester-$d2[id_mk]";
    $pilihan_mk .= "
      <label>
        <div class='row'>
          <div class=col-8>
            <input type=checkbox value='$d2[id_mk]' class='cbmk cbmk-$semester' id=$cbmk_id /> $d2[nama_mk] $MKDU
          </div>
          <div class=col-2>
            $d2[sks] SKS 
          </div>
          <div class='col-2 kanan'>
            <a onclick='return confirm(`Edit MK ini?`)' href='?detail&tb=mk&id=$d2[id_mk]'>$img_edit</a>
            
            <form method=post class='d-inline'> 
              <button class='transparan' onclick='return confirm(`Hapus MK ini?\n\n`)' name=btn_hapus_mk value=$d2[id_mk]>$img_hapus</button>
            </form>

          </div>
        </div>
      </label>
    ";
  }
  // $disabled_assign_mk = '';
} else {
  // $disabled_assign_mk = 'disabled';
  $pilihan_mk = "<i class='f14 abu'>tidak ada pilihan MK di semester $semester yang dapat di-assign</i>";
}

$id_ku_sm = $id_kurikulum . "_$semester";

$blok_add_mk = "
  <div class='btn_aksi pointer' id=blok_add_mk_$id_ku_sm" . "__toggle>$img_add Tambah MK untuk Semester $semester</div>
  <div id=blok_add_mk_$id_ku_sm class='mt2'>
    <div class=row>
      <div class=col-6>
        <div class='wadah bg-white mt2 blok_pilihan_mk' id=blok_pilihan_mk__$id_kurikulum>
          <h4>Assign Pilihan MK</h4>
          <div class=mb2>Pilihan MK Semester $semester Prodi $singkatan_prodi</div>
          <div class=mb2>
            $pilihan_mk
          </div>
          <form method=post>
          <button class='btn btn-primary' id='btn_assign_mk-$semester' name='btn_assign_mk' disabled>Assign MK</button>
          </form>       
        </div>
      </div>
      <div class=col-6>
        <form method=post class='wadah bg-white mt2 form_tambah_mk' id=form_tambah_mk__$id_kurikulum>
          <h4>Buat MK Baru</h4>
          <div class=mb2>Untuk Semester $semester Prodi $singkatan_prodi</div>
          <div class=mb2>
            <label>
              <input type=radio name=is_mkdu value=0 checked> MK Prodi 
            </label>
            <label>
              <input type=radio name=is_mkdu value=1> MKDU (berlaku di semua prodi)
            </label>
          </div>
          <div class=mb1>
            <input required minlength=5 class='form-control' name=nama_mk placeholder='Nama MK'>
          </div>
          <div class='mb2 flexy'>
            <label class=d-block><input type=radio name=sks value=1> 1 SKS</label>
            <label class=d-block><input type=radio name=sks value=2 checked> 2 SKS</label>
            <label class=d-block><input type=radio name=sks value=3> 3 SKS</label>
            <label class=d-block><input type=radio name=sks value=4> 4 SKS</label>
            <label class=d-block><input type=radio name=sks value=5> 5 SKS</label>
            <label class=d-block><input type=radio name=sks value=6> 6 SKS</label>
          </div>
          <button class='btn btn-primary' name='btn_tambah_mk' value='$id_prodi-$id_kurikulum-$semester'>Tambah MK Baru Semester $semester</button>       
        </form>
      </div>
    </div>
  </div>
";

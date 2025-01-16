<?php
$form_tambah_mk = "
  <form method=post class='wadah bg-white mt2 form_tambah_mk' id=form_tambah_mk__$id_kurikulum>
    <h4>Tambah MK Baru</h4>
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
";

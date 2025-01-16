<?php
include 'struktur_kurikulum-pilihan_mk.php';
include 'struktur_kurikulum-form_tambah_mk.php';

$id_ku_sm = $id_kurikulum . "_$semester";
$blok_add_mk = "
  <hr class=mt4>
  <div id=blok_add_mk_$id_ku_sm class='mt2'>
    <div class=row>
      <div class=col-6>
        $pilihan_mk
      </div>
      <div class=col-6>
        $form_tambah_mk
      </div>
    </div>
  </div>
";

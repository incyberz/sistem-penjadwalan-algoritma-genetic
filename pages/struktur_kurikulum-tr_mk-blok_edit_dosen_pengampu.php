<?php
# ============================================================
# FITUR KETIK DAN PILIH DOSEN PENGAMPU
# ============================================================
$blok_edit_dosen_pengampu = "
  <div id=blok_edit_dosen_pengampu$d[id_kumk] class='hideit mt2'>
    <input class='form-control keyword' type=text id=keyword__$d[id_kumk]__$d[id_dosen] placeholder='Enter Nama, min 3 huruf'>
    <ul class='list_dosen hideit f12 m0 mt2 mb2 p1 bordered bg-white' id=list_dosen__$d[id_kumk]></ul>
  </div>
";

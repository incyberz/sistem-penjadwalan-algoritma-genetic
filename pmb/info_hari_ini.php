<?php
$today_show = date('d-M-Y');
$info_hari_ini = "
  <div class='alert alert-info d-flex gap-4 flex-wrap justify-content-center'>
    <div>
      <b>Hari ini</b>: $today_show
    </div>
    <div>
      <b>Gelombang</b>: <span id=gelombang_aktif>$gelombang[nomor]</span>-$tahun_pmb
    </div>
    <div>
      <b>Hingga</b>: $batas_akhir_show, $eta_gelombang
    </div>
  </div>
";

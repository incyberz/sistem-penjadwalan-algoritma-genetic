<?php
$filtered_info = '';
$filtered_info .= $get_weekday ? "<div class=filtered_ruang>weekday=$get_weekday</div>" : '';
$filtered_info .= $get_id_ruang ? "<div class=filtered_ruang>id_ruang=$get_id_ruang</div>" : '';
$mode_info = $mode ? "<a href='?laper'>Show Less</a>" : "<a href='?laper&mode=detail'>Show Details</a>";
$mode_info = $filtered_info ? '' : $mode_info;
$filtered_info = !$filtered_info ? '' : "
  <div class='flexy flex-center'>
    <div class='abu f12 miring pt1'>Filtered by:</div>
    $filtered_info
    <div>
      <a href='?laper'>Clear Filter</a>
    </div>
  </div>
";

set_h2('LAPORAN PEMAKAIAN RUANG', "<h3>TA. $tahun_ta $Gg</h3>$filtered_info$mode_info");

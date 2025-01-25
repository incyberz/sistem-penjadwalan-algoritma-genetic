<?php
# ============================================================
# KONTEN TAMBAHAN UNTUK PROGRESS LAINNYA
# ============================================================
$divs = '';
$sub_divs = '';
if ($tb == 'ruang') {
  foreach ($rshift as $id_shift => $arr_shift) {
    $s = "SELECT 
      (SELECT count(1) FROM tb_ruang a WHERE a.status=1) count_ruang, 
      (SELECT count(1) FROM tb_sesi a WHERE a.is_break is null AND id_shift = '$id_shift') count_sesi,
      (
        SELECT count(1) FROM tb_ruang a 
        JOIN tb_pemakaian_ruang b ON a.id=b.id_ruang 
        JOIN tb_st_detail c ON b.id_st_detail=c.id 
        WHERE a.status=1 
        AND c.id_shift = '$id_shift'
      ) as count_terpakai
    ";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $d = mysqli_fetch_assoc($q);
    $count_total = $d['count_ruang'] * $d['count_sesi'];
    $count_filter = $d['count_terpakai'];
    $rcount[$tb]['title2'] = 'Pemakaian Ruangan';

    $persen = !$rcount[$tb]['count_total'] ? 0 : round(($count_filter / $count_total) * 100);

    $count_of = "
      <span class=count_filter>$count_filter</span> 
      of 
      <span class=count_total>$count_total</span> 
      <span class=satuan>sesi kelas available</span>
    ";


    $sub_divs .=  "
      <div class='mt4 mb2 sub_div'><b>Pemakaian $id_shift:</b> $count_of</div> 
      <div class='wadah gradasi-toska'>
        <!-- bootstrap progress -->
        <div class='progress'>
          <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='$persen' aria-valuemin='0' aria-valuemax='100' style='width:$persen%'>
            $persen%
          </div>
  
        </div>
      </div>
    ";
  }

  $divs .= "
    <hr>
    <h4 class=darkblue>Progress Pemakaian Sesi Ruangan</h4>
    <div class='wadah gradasi-toska'>
      $sub_divs
    </div>
  ";
}

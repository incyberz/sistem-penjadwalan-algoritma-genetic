<?php
# ============================================================
# KONTEN TAMBAHAN UNTUK PROGRESS LAINNYA
# ============================================================
$sub_divs = '';
if ($tb == 'prodi') {
  $rcount[$tb]['title2'] = 'Penjadwalan MK per Prodi';
  $sql_mk_total = $rcount['mk']['sql_total'];
  $s = "SELECT *,
  (
    SELECT count(1) FROM tb_kumk a 
    JOIN tb_kurikulum b ON a.id_kurikulum=b.id 
    WHERE b.id_ta=$ta_aktif AND b.id_prodi=p.id) count_kumk,
  (
    SELECT count(1) FROM tb_jadwal a 
    JOIN tb_st_detail c ON a.id=c.id
    JOIN tb_st d ON c.id_st=d.id
    JOIN tb_kumk e ON c.id_kumk=e.id
    JOIN tb_kurikulum f ON e.id_kurikulum=f.id
    WHERE d.id_ta=$ta_aktif AND f.id_prodi=p.id) count_penjadwalan
  
  FROM tb_prodi p ORDER BY p.fakultas, p.nama";
  // echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {

    if ($d['count_kumk']) {
      $persen = round(($d['count_penjadwalan'] / $d['count_kumk']) * 100);
      $sty_red = $d['count_penjadwalan'] ? '' : 'style=color:red';
      $count_of = "
        <span class=count_filter $sty_red>$d[count_penjadwalan]</span> 
        of 
        <span class=count_total>$d[count_kumk]</span> 
        <span class=satuan $sty_red>MK Terjadwal</span>
      ";
    } else {
      $persen = 0;
      $count_of = "<i class='red bold consolas'>Belum ada satupun MK di prodi ini.</i>";
    }

    $sub_divs .=  "
      <div class='mt4 mb1'>
        <b>$d[singkatan]:</b> $count_of
      </div> 
      <div class='wadah bg-white'>
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
    <h4 class=darkblue>Progress Penjadwalan per Prodi</h4>
    <div class='wadah gradasi-toska'>
      $sub_divs
    </div>
  ";
}

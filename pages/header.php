<?php
$img_manage = img_icon('manage');

$li = '';
foreach ($arr_tb_master as $tb) {
  $li .= "<li><a href='?crud&tb=$tb'>$tb</a></li>";
}
?>
<h1 class="tengah">Sistem Penjadwalan Akademik</h1>
<div class="tengah f12 abu miring">dengan Algoritma Natural Artificial Intelligence</div>
<div class="tengah bold mt2 mb1">
  TA Aktif: <?= $ta_aktif ?> | <?= $Gg ?>
  <a href="?home&show_config=1"><?= $img_manage ?></a>
</div>
<header style="position: sticky; top:0">
  <nav>
    <ul>
      <li><a href="?">Home</a></li>
      <?= $li ?>
      <li><a href="?struktur_kurikulum">SKur</a></li>
      <li><a href="?st_ajar">ST</a></li>
      <li><a href="?jadwal">Jadwal</a></li>
      <li><a href="?laper">Laper</a></li>
    </ul>
  </nav>
</header>
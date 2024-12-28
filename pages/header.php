<?php
$li = '';
foreach ($arr_tb_master as $tb) {
  $li .= "<li><a href='?crud&tb=$tb'>$tb</a></li>";
}
?>
<header>
  <h1>Sistem Penjadwalan</h1>
  <nav>
    <ul>
      <li><a href="?">Home</a></li>
      <?= $li ?>
      <li><a href="?jadwal">Jadwal</a></li>
      <li><a href="?struktur_kurikulum">SKur</a></li>
    </ul>
  </nav>
</header>
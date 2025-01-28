<?php
$img_manage = img_icon('manage');

$li = '';
foreach ($arr_tb_master as $tb) {
  $li .= "<li><a href='?crud&tb=$tb'>$tb</a></li>";
}
?>
<div class="no_print">
  <h1 class="tengah">Sistem Penjadwalan Akademik</h1>
  <div class="tengah f12 abu miring">Aplikasi Hibah Penelitian: Scheduling System dengan Algoritma Natural Artificial Intelligence</div>
  <div class="tengah bold mt2 mb1">
    TA Aktif: <span id="ta_aktif" class=hideit><?= $ta_aktif ?></span><span id="tahun_ta"><?= $tahun_ta ?></span> | <span id="Gg"><?= $Gg ?></span>
    <a href="?home&show_config=1"><?= $img_manage ?></a>
  </div>
  <header style="position: sticky; top:0;z-index:99; border-bottom: solid 1px #ccc">
    <nav>
      <div class="flexy flex-between">
        <ul>
          <li><a href="?">Home</a></li>
          <?= $li ?>
          <li><a href="?struktur_kurikulum">SKur</a></li>
          <li><a href="?st">ST</a></li>
          <li><a href="?jadwal">Jadwal</a></li>
          <li><a href="?laper">Laper</a></li>
          <li><a href="?home&show_config=1">Conf</a></li>
          <li><a href="?progress">Progress</a></li>
        </ul>
        <ul>
          <li><a href="?detail&tb=user&id=<?= $id_user ?>"><?= $username ?> - <?= $role ?></a></li>
          <li class="mr3"><a href="?logout" onclick="return confirm(`Logout?`)"><?= $img_login_as ?></a></li>
        </ul>
      </div>
    </nav>
  </header>
</div>
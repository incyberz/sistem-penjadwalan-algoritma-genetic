<?php
$img_manage = img_icon('manage');

$li = '';
$ul_master = '';
if ($role == 'AKD') {
  $arr_manage = [];
  $arr_manage['SKur'] = 'struktur_kurikulum';
  $arr_manage['ST'] = 'st';
  $arr_manage['Jadwal'] = 'jadwal';
  $arr_manage['ME'] = 'me';



  $li .= "<li class=''><span class='btn_aksi pointer brown' id=ul_master__toggle>masters</span></li>";

  $arr_tb_info = ['pemakaian', 'progress', 'penugasan', 'siklus'];
  $li_info = '';
  foreach ($arr_tb_info as $info) {
    $li_info .= "<li class='li_info'><a href='?$info'>$info</a></li>";
  }
  $ul_info = "<ul class='pl3 pr3'>$li_info</ul>";

  $li_master = '';
  foreach ($arr_tb_master as $tb) {
    $li_master .= "<li class='li_master'><a href='?crud&tb=$tb'>$tb</a></li>";
  }
  $ul_master = "<ul class='pl3 pr3'>$li_master</ul>";

  # ============================================================
  # LIST MANAGE FOR AKD
  # ============================================================
  foreach ($arr_manage as $caption => $href) {
    $li .= "<li><a href='?$href'>$caption</a></li>";
  }
  $li .= "<li class=''><span class='btn_aksi pointer brown' id=ul_info__toggle>info</span></li>";
} elseif ($role == 'DSN') {
  $arr_4dosen = [];
  $arr_4dosen['ST'] = 'st';
  $arr_4dosen['Jadwal'] = 'jadwal';
  $arr_4dosen['Progress'] = 'progress';
  foreach ($arr_4dosen as $caption => $href) {
    $li .= "<li><a href='?$href'>$caption</a></li>";
  }
}

$nav_fakultas = '';
foreach ($rfakultas as $key => $value) {
  if ($key == $fakultas) {
    $nav_fakultas .= "<div class='abu miring'>$key</div>";
  } else {
    $nav_fakultas .= "<div><a onclick='return confirm(`Pindah ke Fakultas $key?`)' href='?pindah_fakultas&fakultas=$key'>$key</a></div>";
  }
}


?>
<h1 class="tengah mt2">Smart Gamified SIAKAD</h1>
<div class="tengah f12 abu miring">With The Power of Gamifications and Artificial Intelligence</div>
<div class="tengah bold mt2 mb1">
  <span class="btn_aksi hover darkblue" id=nav_fakultas__toggle><?= $fakultas ?></span> |
  TA Aktif:
  <a href="?home&show_config=1">
    <span id="ta_aktif" class=hideit><?= $ta_aktif ?></span>
    <span id="tahun_ta"><?= $tahun_ta ?></span> |
    <span id="Gg"><?= $Gg ?></span>
    <?= $img_manage ?>
  </a>
  <div class='wadah gradasi-kuning mt2 hideit' id=nav_fakultas>
    <div class='flexy flex-center'>
      <?= $nav_fakultas ?>
    </div>
  </div>
</div>
<header style="position: sticky; top:0;z-index:99; border-bottom: solid 1px #ccc">
  <nav>
    <div class="flexy flex-between">
      <ul class="pl3">
        <li><a href="?">Home</a></li>
        <?= $li ?>
      </ul>
      <ul>
        <li><a href="?detail&tb=user&id=<?= $id_user ?>"><?= $username ?> - <?= $role ?></a></li>
        <li class="mr3"><a href="?logout" onclick="return confirm(`Logout?`)"><?= $img_login_as ?></a></li>
      </ul>
    </div>
    <div id=ul_master class='hideit'>
      <div class="flexy">
        <div class="bordered mx-3 p1 br5 gradasi-kuning">
          <?= $ul_master ?>

        </div>
      </div>
    </div>
    <div id=ul_info class='hideit'>
      <div class="flexy">
        <div class="bordered mx-3 p1 br5 gradasi-kuning">
          <?= $ul_info ?>

        </div>
      </div>
    </div>
  </nav>
</header>
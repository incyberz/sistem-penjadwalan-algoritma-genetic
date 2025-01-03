<?php
$show_config = $_GET['show_config'] ?? null;

if (isset($_POST['btn_set_ta'])) {
  echolog("Setting ke TA. $_POST[btn_set_ta]");
  $_SESSION['jadwal_ta_aktif'] = $_POST['btn_set_ta'];
  jsurl();
}

$div_ta = '';
$s = "SELECT * FROM tb_ta";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $tahun = substr($d['id'], 0, 4);
  $gg = $d['id'] % 2 == 0  ? 'Genap' : 'Ganjil';

  $div_ta .= $d['id'] == $ta_aktif ? "
    <div class='col-6 mb2'>
      <span class='btn btn-secondary w-100' onclick='alert(`Anda sedang berada di TA ini.`)'>
        $tahun $gg
      </span>
    </div>
  " : "
    <div class='col-6 mb2'>
      <button class='btn btn-success w-100' value=$d[id] name=btn_set_ta onclick='return confirm(`Ganti ke TA ini?`)'>
        $tahun $gg
      </button>
    </div>
  ";
}


$petugas['nama'] = strtolower($petugas['nama']);
$hideit = $show_config ? '' : 'hideit';
echo "
  <h1>Welcome <span id='nama_user' class='proper'>$petugas[nama]</span>!!!</h1>
  <ul>
    <li><b>Role:</b> $petugas[role] </li>
    <li><b>Whatsapp:</b> $petugas[whatsapp] </li>
    <li>
      <b>TA Aktif:</b> 
      $tahun_ta $Gg 
      <span class=btn_aksi id=set_ta_aktif__toggle>$img_manage</span>
    </li>
  </ul>
  <form method=post id='set_ta_aktif' class='$hideit mt2 wadah gradasi-toska'>
    <h3>Set TA Aktif</h3>
    <div class=row>
      $div_ta
    </div>
  </form>
";

<?php
// if ($role == 'MHS') jsurl('?home_mhs');
$show_config = $_GET['show_config'] ?? null;

if (isset($_POST['btn_set_ta'])) {
  echolog("Setting ke TA. $_POST[btn_set_ta]");
  $_SESSION['ta_aktif'] = $_POST['btn_set_ta'];
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


$user['nama'] = strtolower($user['nama']);
$hideit = $show_config ? '' : 'hideit';

# ============================================================
# COUNTS
# ============================================================
include 'counts.php';
echo "<div style='margin: -20px 0 20px 5px'><a href='?progress'><i class=f12>more at progress...</i></a></div>";

$tmp = str_replace('$', '', file_get_contents('config.php'));
echo "
  <h1>Welcome <a href='?detail&tb=user&id=$user[id]' id='nama_user' class='proper'>$user[nama]</a>!!!</h1>
  <p class=petunjuk>Silahkan ikuti kalimat petunjuk yang berwarna biru yang biasanya terdapat icon $img_help Anda sekarang boleh klik pada Menu apapun yang tersedia.</p>
  <ul>
    <li><b>Role:</b> $user[role] </li>
    <li><b>Whatsapp:</b> $user[whatsapp] </li>
    <li>
      <b>TA Aktif:</b> 
      $tahun_ta $Gg 
      <span class=btn_aksi id=set_ta_aktif__toggle>$img_manage</span>
    </li>
    <li>
      <b>Konfigurasi System:</b> 
      Manage 
      <span class=btn_aksi id=set_konfigurasi__toggle>$img_manage</span>
    </li>
  </ul>

  <form method=post id='set_ta_aktif' class='$hideit mt2 wadah gradasi-toska'>
    <h3>Set TA Aktif</h3>
    <div class=row>
      $div_ta
    </div>
  </form>

  <form method=post id='set_konfigurasi' class='$hideit mt2 wadah gradasi-toska'>
    <h3>Set Konfigurasi System</h3>
    <div class='alert alert-danger'>Maaf, Customisasi Konfigurasi masih dalam tahap pengembangan.</div>
    <b>Default Konfigurasi:</b>
    <hr>
    <pre>
$tmp
    </pre>
  </form>
";

<style>
  .div_count {
    min-width: 150px;
    background: #eef;
    padding: 10px;
    border: solid 1px #ccc;
    border-radius: 5px;
  }

  a:hover {
    text-decoration: none;
  }
</style>
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

# ============================================================
# COUNTS
# ============================================================
$img_filter = img_icon('filter');
$rcount = [
  'prodi' => [
    'title' => 'Prodi',
    'href' => '?crud&tb=prodi',
    'sql' => "SELECT 1 FROM tb_prodi",
  ],
  'mk' => [
    'title' => "Mata Kuliah",
    'href' => '?crud&tb=mk',
    'sql' => "SELECT 1 FROM tb_kumk a JOIN tb_kurikulum b ON a.id_kurikulum=b.id WHERE b.id_ta=$ta_aktif",
  ],
  'kelas' => [
    'title' => "Grup Kelas",
    'href' => '?crud&tb=kelas',
    'sql' => "SELECT 1 FROM tb_kelas WHERE id_ta=$ta_aktif",
  ],
  'dosen' => [
    'title' => 'Dosen Aktif',
    'href' => '?crud&tb=dosen',
    'sql' => "SELECT 1 FROM tb_dosen WHERE status=1",
  ],
  'ruang' => [
    'title' => 'Ruangan',
    'href' => '?crud&tb=ruang',
    'sql' => "SELECT 1 FROM tb_ruang ",
  ],
  'st' => [
    'title' => "Surat Tugas",
    'href' => '?st_ajar',
    'sql' => "SELECT 1 FROM tb_st WHERE id_ta=$ta_aktif",
  ],
  'jadwal' => [
    'title' => 'Penjadwalan',
    'href' => '?jadwal',
    'sql' => "SELECT 1 FROM tb_jadwal a 
    JOIN tb_st_detail c ON a.id=c.id
    JOIN tb_st d ON c.id_st=d.id
    WHERE d.id_ta=$ta_aktif",
  ],
];

$div_counts = '';
foreach ($rcount as $tb => $arr) {
  $q = mysqli_query($cn, $arr['sql']) or die(mysqli_error($cn));
  $count = mysqli_num_rows($q);
  $All = strpos($arr['sql'], $ta_aktif) ? '' : 'All ';
  $div_counts .= "
    <div class=div_count>
      <div class='darkblue f12'>
        <span class=pointer onclick='alert(`All artinya data $arr[title] berlaku di semua Tahun Ajar.`)'>$All</span>
        <a href='$arr[href]'>
          $arr[title]
        </a>
      </div> 
      <div class='f30 abu'>$count</div> 
    </div>
  ";
}

$tmp = str_replace('$', '', file_get_contents('config.php'));
echo "
  <div class='flexy mb4'>
    $div_counts
  </div>
  <h1>Welcome <a href='?detail&tb=petugas&id=$petugas[id]' id='nama_user' class='proper'>$petugas[nama]</a>!!!</h1>
  <ul>
    <li><b>Role:</b> $petugas[role] </li>
    <li><b>Whatsapp:</b> $petugas[whatsapp] </li>
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

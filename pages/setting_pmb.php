<?php
include 'pmb/tahun_pmb.php';
include 'includes/script_btn_aksi.php';
include 'setting_pmb-script_btn_save.php';
$bm = '<b class=red>*</b>';

$rsetting = [
  1 => [
    'tb' => 'tahun_pmb',
    'nav' => 'Tahun',
    'sql_where' => "tahun_pmb='$tahun_pmb'",
    'title' => 'Setting Tahun PMB',
    'no_edit' => [],
  ],
  2 => [
    'tb' => 'gelombang',
    'nav' => 'Gelombang',
    'sql_where' => "tahun_pmb='$tahun_pmb'",
    'title' => 'Setting Gelombang Pendaftaran',
    'no_edit' => [],
  ],
  3 => [
    'tb' => 'akun',
    'nav' => 'Petugas',
    'sql_where' => "role is not null",
    'title' => 'Setting Petugas PMB',
    'no_edit' => [],
  ],
  4 => [
    'tb' => 'go',
    'nav' => 'Go!',
    'title' => 'Go',
  ],
];

$nav = '';
foreach ($rsetting as $step => $v) {
  $nav .= "<div><a href='?setting_pmb&step=$step'>$v[nav]</a></div>";
}


$step = $_GET['step'] ?? 1;

$setting = $rsetting[$step];
$tb = $setting['tb'];
if ($tb == 'go') {
  alert("Back to <a href='?setting_pmb'>Setting PMB</a> | Go to <a href='pmb/'>Laman PMB</a>", 'success tengah');
  exit;
}

$title = $setting['title'];
$sql_where = $setting['sql_where'];

$s = "DESCRIBE tb_$tb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rField = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rField[$d['Field']] = $d;
}


$s = "SELECT a.* 
FROM tb_$tb a WHERE $sql_where";
echo '<pre>';
var_dump($s);
echo '</pre>';
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    foreach ($d as $key => $value) {
      $show_value = null;
      $input = null;
      $req_icon = null;
      $t = explode('(', $rField[$key]['Type']);
      $Type = $t[0];
      $Length = null;
      if (isset($t[1]) and $t[1]) {
        $t = explode(')', $t[1]);
        $Length = $t[0];
      }
      $Null = $rField[$key]['Null'] == 'NO' ? '' : 1;
      $req_icon = $rField[$key]['Null'] == 'NO' ? $bm : '';

      # ============================================================
      # CUSTOM VALUES EACH TABLE
      # ============================================================
      if ($tb == 'tahun_pmb') {
        $acuan = 'tahun_pmb'; // id_gelombang;
        $acuan_val = $d['tahun_pmb']; // id_gelombang;
        $four_id = "$tb-$key-$acuan-$acuan_val";
        if ($key == 'tahun_pmb') {
          continue;
        }
      } elseif ($tb == 'gelombang') {
        $acuan = 'id'; // id_gelombang;
        $acuan_val = $d['id']; // id_gelombang;
        $four_id = "$tb-$key-$acuan-$acuan_val";
        if ($key == 'id' || $key == 'tahun_pmb') {
          continue;
        }
      } elseif ($tb == 'akun') {
        $acuan = 'username'; // id_gelombang;
        $acuan_val = $d['username']; // id_gelombang;
        $four_id = "$tb-$key-$acuan-$acuan_val";
        if (
          $key == 'created_at'
          || $key == 'tahun_pmb'
          || $key == 'asal_sekolah'
          || $key == 'jeda_tahun_lulus'
          || $key == 'password'
          || $key == 'last_step'
          || $key == 'active_status'
          || $key == 'whatsapp_status'
        ) {
          continue;
        }
      }

      # ============================================================
      # JENIS INPUT BERDASARKAN TIPE DATA
      # ============================================================
      if (!isset($four_id) || !$four_id) die(alert("four_id untuk tabel: $tb belum ditentukan."));
      if ($Type == 'date') {
        $show_value = date('d-M-Y', strtotime($value));
        $input = "<input id=$four_id class='flex-fill form-control form-control-sm' type=date value='$value'>";
      } elseif (strpos("salt$Type", 'int(')) {
        $show_value = number_format($value);
        $input = "<input id=$four_id class='flex-fill form-control form-control-sm' type=number value='$value'>";
      } elseif (strpos("salt$Type", 'char')) {
        $input = "<input id=$four_id class='flex-fill form-control form-control-sm' type=text value='$value'>";
      } elseif ($Type == 'text') {
        $input = "<textarea id=$four_id class='flex-fill form-control form-control-sm' rows=6>$value</textarea>";
      } else if ($Type == 'enum' || $Type == 'set') {
        $t = explode(',', $Length);
        $opt = '';
        foreach ($t as $v) {
          $v = str_replace('\'', '', $v);
          $opt .= "<option>$v</option>";
        }
        $input = "<select id=$four_id class='flex-fill form-control form-control-sm' >$opt</select>";
      } else {
        echo '<pre>';
        var_dump($Type);
        echo "<b class=red>Belum ada handler untu Type diatas. field: $key. exited</b></pre>";
        exit;
      }


      $show_value = $show_value ?? $value;
      $konten = !$input ? $value : "
        <div class='flex flex-between'>
          <div id=show_value-$four_id>$show_value</div> 
          <div class=hideit id=old_val-$four_id>$value</div> 
          <div class=hideit id=null-$four_id>$Null</div> 
          <div class=btn-aksi id='blok_edit-$four_id--toggle'>$img_edit</div>
        </div>
        <div class='hideit mt2 mb2' id=blok_edit-$four_id>
          <div class='d-flex gap-2 '>
            $input
            <button class='btn btn-sm btn-primary btnSave' id=btnSave-$four_id >Save</button>
          </div>
        </div>
      ";

      # ============================================================
      # CUSTOM KOLOM
      # ============================================================
      if ($tb == 'gelombang' and $key == 'nomor') {
        $kolom = 'Gelombang';
        $separator_sty = "<tr><td colspan=100% >&nbsp;</td></tr>";
        $toska = 'toska';
        $konten = $value;
      } elseif ($tb == 'akun' and $key == 'username') {
        $kolom = 'Username';
        $separator_sty = "<tr><td colspan=100% >&nbsp;</td></tr>";
        $toska = 'toska';
        $konten = $value;
      } else {
        $kolom = key2kolom($key);
        $separator_sty = null;
        $toska = null;
      }
      $tr .= "
        $separator_sty
        <tr class='gradasi-$toska' >
          <td width=35%>$kolom $req_icon</td>
          <td>$konten</td>
        </tr>
      ";
    }
  }
}

$nav = "<div class='d-flex gap-2 justify-content-center'>$nav</div>";
set_h2('Setting PMB', $nav);

echo $tr ? "
  <div class='card'>
    <div class='card-header tengah bg-success text-white'>$title</div>
    <div class='card-body'>
      <div class='tengah mb4'>
        <div class='f12 mb1'>Tahun PMB Aktif</div>
        <div class='f30'>$tahun_pmb</div>
      </div>
      <table class=table>
        $tr
      </table>
    </div>
  </div>
" : div_alert('danger', "Data $tb tidak ditemukan.");

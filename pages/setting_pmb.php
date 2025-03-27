<?php
include 'pmb/tahun_pmb.php';
include 'includes/script_btn_aksi.php';
$bm = '<b class=red>*</b>';

$rsetting = [
  1 => [
    'tb' => 'tahun_pmb',
    'sql_where' => "tahun_pmb='$tahun_pmb'",
    'title' => 'Setting Tahun PMB',
    'no_edit' => [],
  ],
  2 => [
    'tb' => 'gelombang',
    'sql_where' => "tahun_pmb='$tahun_pmb'",
    'title' => 'Setting Gelombang Pendaftaran',
    'no_edit' => [],
  ],
];

$nav = '';
foreach ($rsetting as $step => $v) {
  $nav .= "<div><a href='?setting_pmb&step=$step'>$v[tb]</a></div>";
}


$step = $_GET['step'] ?? 1;

$setting = $rsetting[$step];
$tb = $setting['tb'];
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
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    foreach ($d as $key => $value) {
      $show_value = null;
      $input = null;
      $req = null;

      # ============================================================
      # CUSTOM VALUES
      # ============================================================
      if ($tb == 'tahun_pmb') {
        if ($key == 'tahun_pmb') {
          continue;
        } elseif ($key == 'awal' || $key == 'akhir') {
          $show_value = date('d-M-Y', strtotime($value));
          $input = "<input id=$tb-$key class='flex-fill form-control form-control-sm' type=date value='$value'>";
        } elseif ($key == 'biaya_daftar' || $key == 'biaya_registrasi_ulang') {
          $show_value = 'Rp ' . number_format($value) . ',-';
          $input = "<input id=$tb-$key class='flex-fill form-control form-control-sm' type=number value='$value'>";
        } elseif ($key == 'no_rek' || $key == 'bank' || $key == 'atas_nama') {
          $input = "<input id=$tb-$key class='flex-fill form-control form-control-sm' value='$value'>";
        }
      } elseif ($tb == 'gelombang') {
        if ($key == 'id' || $key == 'tahun_pmb') {
          continue;
        } else {
          $Type = $rField[$key]['Type'];
          $Null = $rField[$key]['Null'] == 'NO' ? '' : 1;
          $req = $rField[$key]['Null'] == 'NO' ? $bm : '';
          if ($Type == 'date') {
            $show_value = date('d-M-Y', strtotime($value));
            $input = "<input id=$tb-$key class='flex-fill form-control form-control-sm' type=date value='$value'>";
          } elseif (strpos("salt$Type", 'int(')) {
            $show_value = number_format($value);
            $input = "<input id=$tb-$key class='flex-fill form-control form-control-sm' type=number value='$value'>";
          } elseif (strpos("salt$Type", 'char(')) {
            $input = "<input id=$tb-$key class='flex-fill form-control form-control-sm' type=text value='$value'>";
          } elseif ($Type == 'text') {
            $input = "<textarea id=$tb-$key class='flex-fill form-control form-control-sm' rows=6>$value</textarea>";
          } else {
            echo '<pre>';
            var_dump($Type);
            echo '<b class=red>Belum ada handler untu Type diatas. exited</b></pre>';
            exit;
          }

          // exit;
        }
        // echo '<pre>';
        // var_dump('$');
        // echo '<b style=color:red>Developer SEDANG DEBUGING: exit(true)</b></pre>';
        // exit;
      }

      $show_value = $show_value ?? $value;
      $konten = !$input ? $value : "
        <div class='flex flex-between'>
          <div id=show_value-$tb-$key>$show_value</div> 
          <div class=hideit id=old_value-$tb-$key>$value</div> 
          <div class=hideit id=null-$tb-$key>$Null</div> 
          <div class=btn-aksi id='blok_edit-$tb-$key--toggle'>$img_edit</div>
        </div>
        <div class='hideita mt2 mb2' id=blok_edit-$tb-$key>
          <div class='d-flex gap-2 '>
            $input
            <button class='btn btn-sm btn-primary btnSave' id=btnSave-$tb-$key >Save</button>
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
      } else {
        $kolom = key2kolom($key);
        $separator_sty = null;
        $toska = null;
      }
      $tr .= "
        $separator_sty
        <tr class='gradasi-$toska' >
          <td width=40%>$kolom $req</td>
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
    <div class='card-header tengah bg-success text-white'>$v[title]</div>
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

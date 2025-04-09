<link rel="stylesheet" href="feedback.css">
<?php
set_title('Form Feedback Peserta PMB');
include 'feedback-process.php';

$feedback_terisi = 0;
$last_update = null;

# ============================================================
# JAWABANS FROM DB
# ============================================================
$rvalue = [];
$s = "SELECT * FROM tb_feedback_respon WHERE responden='$username' AND tahun_pmb=$tahun_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $d = mysqli_fetch_assoc($q);
  $last_update = $d['last_update'];
  $t = explode('|', $d['jawabans']);
  foreach ($t as $k => $v) {
    if ($v) {
      $t2 = explode('--', $v);
      $tipe = $t2[0] ?? kosong('tipe');
      $id = $t2[1] ?? kosong('id');
      $value = $t2[2] ?? kosong('value');
      $sub_value = null;
      if ($tipe == 'skala') {
        $sub_value = $t2[3] ?? ''; // mungkin kosong
      }
      $rvalue[$id] = [
        'id' => $id,
        'value' => $value,
        'sub_value' => $sub_value,
      ];
    }
  }
}


# ============================================================
# MAIN SELECT
# ============================================================
$s = "SELECT * FROM tb_feedback WHERE tahun_pmb='$tahun_pmb'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_feedback = mysqli_num_rows($q);
if (!$jumlah_feedback) stop('Belum ada pertanyaan feedback di PMB tahun ini');

$rtipe = [
  'pilihan',
  'input',
  'komentar',
];

$rhal = [
  'teknis' => [
    'bg' => 'info',
    'gradasi' => '',
    'title' => 'Perihal Teknis Pendaftaran'
  ],
  'pelayanan' => [
    'bg' => 'info',
    'gradasi' => '',
    'title' => 'Perihal Pelayanan Panitia'
  ],
  'kesan' => [
    'bg' => 'success',
    'gradasi' => 'hijau',
    'title' => 'Kesan dan Pesan Anda'
  ],
];

$rbg = [
  1 => 'danger',
  2 => 'warning',
  3 => 'info',
  4 => 'success',
  5 => 'success',
];

$list_pertanyaan = [];
foreach ($rhal as $k => $v) {
  $list_pertanyaan[$k] = ''; // inisialisasi
}

while ($d = mysqli_fetch_assoc($q)) {
  $id = $d['id'];
  $hal = $d['hal'];
  $pertanyaan = $d['pertanyaan'] ?? mati('pertanyaan');
  $pertanyaan = ucfirst(str_replace('??', '?', trim($pertanyaan) . '?'));
  $sub_value_db = $rvalue[$id]['sub_value'];
  $value_db = $rvalue[$id]['value'];
  if ($value_db) $feedback_terisi++;

  # ============================================================
  # LENGTH HANDLER
  # ============================================================
  $minlength_info = !$d['minlength'] ? '' : "minimal $d[minlength] karakter";
  $maxlength_info = !$d['maxlength'] ? '' : "maksimal $d[maxlength] karakter";
  $koma = ($minlength_info && $maxlength_info) ? ', ' : '';
  $length_info = str_replace(' karakter, maksimal ', ' s.d ', "$minlength_info$koma$maxlength_info");
  $length_info = "<div class='f12 mt1 abu miring' id=length_info--$id>$length_info</div>";

  # ============================================================
  # JAWABANS HANDLER
  # ============================================================
  $jawabans = '';
  if ($d['tipe'] == 'pilihan') {
    $t = explode(';', $d['opsis']);
    $id_no = 0;
    foreach ($t as $k => $v) {
      if ($v) {
        $t2 = explode('=', $v);
        $value = $t2[0];
        $title = $t2[1];
        if ($value && $title) {
          $id_no++;
          $checked = $value == $value_db ? 'checked' : '';
          $required = $value == $value_db ? '' : 'required';
          $jawabans .= "
            <input
              $checked
              $required
              type='radio'
              class='btn-check radio'
              name='radio--$id'
              id='radio-$id_no--$id'
              value='$value'
              />
            <label class='btn btn-outline-$rbg[$value]' for='radio-$id_no--$id'>$title</label>          
          ";
        }
      }
    }

    # ============================================================
    # GROUPING PILIHAN JAWABAN
    # ============================================================
    $jawabans = "
      <input type=hidden id=skala--$id class=feedback value='$value_db'>
      <div class='btn-group w-100' role='group'>
        $jawabans
      </div>
    ";
    # ============================================================
  } elseif ($d['tipe'] == 'input' || $d['tipe'] == 'komentar') {
    $params = "
      class='form-control input feedback'
      id='input--$id' 
      minlength='$d[minlength]'
      maxlength='$d[maxlength]'
      required 
    ";

    if ($d['tipe'] == 'input') {
      $jawabans .= "<input $params value='$value_db' />$length_info";
    } else {
      $jawabans .= "<textarea $params rows='3'>$value_db</textarea>$length_info";
    }
  } else {
    stop("belum ada handler untuk tipe feedback: $d[tipe]");
  }

  # ============================================================
  # SUB PERTANYAAN
  # ============================================================
  if ($d['sub_pertanyaan']) {
    $hideit = $sub_value_db ? '' : 'hideit';
    $sub_pertanyaan = "
      <div class='mt-2 mb-2 $hideit' id=blok_sub_pertanyaan--$id>
        <div class='f12 mb1 darkred' id=sub_pertanyaan--$id>$d[sub_pertanyaan]</div>
        <input 
          value = '$sub_value_db'
          type='text' 
          class='form-control sub_pertanyaan input' 
          id='input--$id' 
          minlength='$d[minlength]'
          maxlength='$d[maxlength]'
        />
        $length_info
      </div>
    ";
  } else {
    $sub_pertanyaan = "<i id=sub_pertanyaan--$id></i>";
  }

  # ============================================================
  # LIST PERTANYAAN + JAWABANS
  # ============================================================
  $belum_terisi = $value_db ? '' : 'belum_terisi';
  $list_pertanyaan[$hal] .= "
  <div class='blok_feedback $belum_terisi' id=blok_feedback--$id>
    <label class='form-label' for='$hal-$id'>$pertanyaan</label>
    $jawabans  
    $sub_pertanyaan
    <i id=minlength--$id class=hideit>$d[minlength]</i>
    <i id=maxlength--$id class=hideit>$d[maxlength]</i>
    <i id=tipe--$id class=hideit>$d[tipe]</i>
  </div>   
  ";
}















# ============================================================
# FEEDBACKS
# ============================================================
$feedbacks = '';
foreach ($rhal as $hal => $rv) {
  $feedbacks .= "
    <div class='card mb-4'>
      <div class='card-header bg-$rv[bg] putih tengah'>$rv[title]</div>
      <div class='card-body gradasi-$rv[gradasi] p-0'>
        $list_pertanyaan[$hal]
      </div>
    </div>  
  ";
}







$persen = !$jumlah_feedback ? 0 : round($feedback_terisi * 100 / $jumlah_feedback);
$Ulang = $rvalue ? 'Ulang' : '';
$last_update_info = !$last_update ? '' : "
  <div class='alert alert-success mt-2'>
    <b class='text-primary'>Halo responden!<br>Anda pernah memberikan feedback sebelumnya.</b>
    <div class='mt2'>
      <a href='?daftar'>Back to Home</a>
    </div>
    <hr>
    Last Update: " . date('d-M-Y H:i', strtotime($last_update)) . "
    <div class='mt1 f12 abu '>Jika ingin mengubahnya silahkan Kirim Ulang Feedback Anda</div>
  </div>
";
echo "
  <div style='position:relative; max-width: 500px; margin: 15px auto'>
    <h1 class='mt-2 mb-4 tengah'>Form Feedback <div class='f24'>Peserta PMB $tahun_pmb</div></h1>
    <div class='mb-2'>
      <b>Nama</b>: <i>anonim</i> 
      <div class='f12 abu'>(kami merahasiakan identitas dan feedback dari Anda)</div>
      $last_update_info
    </div>
    <div class='blok_progress bg-light text-dark py-2' style='position:sticky;top:0;z-index:999'>
      <div class='mb1 f12 tengah'><span id=feedback_terisi>$feedback_terisi</span> of <span id=jumlah_feedback>$jumlah_feedback</span> feedback</div>
      <div class='progress'>
        <div id=progress class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='$persen' aria-valuemin='0' aria-valuemax='100' style='width:$persen%'>
          $persen%
        </div>
      </div>
    </div>

    <h2 class='mt-4'>Pertanyaan Feedback</h2>
    <form method='post' class='my-4'>
      $feedbacks
      <div class='hideit red f10'>jawabans: <textarea id=jawabans name=jawabans class='form-control'>$jawabans</textarea></div>
      <button type='submit' class='btn btn-primary w-100' name=btn_kirim_feedback>Kirim $Ulang Feedback</button>
    </form>    
  </div>
";
?>
<script src="feedback.js"></script>
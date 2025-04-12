<?php
# ============================================================
# DB FEEDBACKS
# ============================================================
$s = "SELECT * FROM tb_feedback WHERE tahun_pmb = $tahun_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rfeedback = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rfeedback[$d['id']] = $d;
}

# ============================================================
# RESPONS
# ============================================================
$s = "SELECT * FROM tb_feedback_respon WHERE tahun_pmb = $tahun_pmb ORDER BY last_update DESC";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$voters = mysqli_num_rows($q);

$sarans = '';
$kendalas = '';
$sarans_count = 0;
$kendalas_count = 0;
$rating = [];
$sum_rating = [];
$rrating = ['teknis', 'pelayanan'];
foreach ($rrating as $v) {
  $sum_rating[$v] = 0;
}

while ($d = mysqli_fetch_assoc($q)) {
  $t = explode('|', $d['jawabans']);
  foreach ($t as $k => $v) {
    if ($v) {
      $t2 = explode('--', $v);
      $tipe = $t2[0] ?? kosong('tipe');
      $id = $t2[1] ?? kosong('id');
      $value = $t2[2] ?? kosong('value');
      $sub_value = null;

      $feedback = $rfeedback[$id];

      if ($tipe == 'skala') {
        $sub_value = $t2[3] ?? ''; // mungkin kosong (tidak perlu ada)
        if ($feedback['tipe'] == 'rating') {
          $sum_rating[$feedback['hal']] += $value; // feedback tekniks atau pelayanan
        } elseif ($feedback['sub_pertanyaan']) {
          if ($sub_value) {
            $kendalas_count++;
            $kendalas .= "
              <div class='f12 my-2'>
                <div>$feedback[sub_pertanyaan]</div>
                <b>$d[responden]</b>: <i class=text-danger>$sub_value</i>
              </div>
            ";
          }
        }
      } elseif ($tipe == 'input') {
        if ($feedback['hal'] == 'saran') {
          $sarans_count++;
          $eta = eta2($d['last_update']);
          $sarans .= "
            <div class='f12 my-2'>
              <b>$d[responden]</b>: <i class=text-info>$value</i> 
              <br>
              <span class=abu>$eta</span>
            </div>
          ";
        }
      }
    }
  }
}

# ============================================================
# KALKULASI RATING
# ============================================================
foreach ($rrating as $v) {
  $rating[$v] = round($sum_rating[$v] / $voters, 1);
}


echo "
  <div class='card mt4'>
    <div class='card-header bg-info putih tengah'>Feedback Pendaftar</div>
    <div class='card-body'>
      <div class='row'>
        <div class='col-3'>
          <div class='border-right h-100 tengah'>
            <div class='text-primary '>Rating Website</div>
            <div>
              <span class='f40'>$rating[teknis]</span> of 5
              <div class='f14 abu'>$voters voters</div>
            </div>
          </div>
        </div>
        <div class='col-3'>
          <div class='border-right h-100 tengah'>
            <div class='text-primary '>Rating Layanan</div>
            <div>
              <span class='f40'>$rating[pelayanan]</span> of 5
              <div class='f14 abu'>$voters voters</div>
            </div>
          </div>
        </div>
        <div class='col-3'>
          <div class='border-right h-100 pr2'>
            <div class='text-info tengah '>Saran dan Masukan</div>
            $sarans
          </div>
        </div>
        <div class='col-3'>
          <div class=''>
            <div class='text-danger tengah '>Laporan Kendala</div>
            $kendalas
          </div>
        </div>
      </div>
    </div>
  </div>
";

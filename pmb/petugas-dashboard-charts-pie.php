<?php
# ============================================================
# GENDER FOR GRAFIK
# ============================================================
$pie_names = [];
$pie_counts = [];
$pie_charts = '';

$rpie = [
  'gender' => [
    'title' => 'Gender Peserta'
  ],
  'klask' => [
    'title' => 'Sekolah Asal'
  ],
  'jurusan' => [
    'title' => 'Jurusan Sekolah'
  ],
  'prodi' => [
    'title' => 'Program Studi'
  ],
  'jalur' => [
    'title' => 'Jalur Daftar'
  ],
  'gelombang' => [
    'title' => 'Gelombang Pendaftaran'
  ],
];

$label_names['gender'] = 'L;P'; // exception for gender
$label_counts['gender'] = join(';', $rgender);

foreach ($rpie as $pie => $arr) {
  $pie_names[$pie] = $label_names[$pie] ?? kosong("label_names:$pie");
  $pie_counts[$pie] = $label_counts[$pie] ?? kosong("label_counts:$pie");

  $pie_charts .= "
    <div class='col-4'>
      <div class='pie-chart'>
        <div class='hideit' id='pie_names--$pie'>$pie_names[$pie]</div>
        <div class='hideit' id='pie_counts--$pie'>$pie_counts[$pie]</div>

        <div class='card gradasi-toska'>
          <div class='card-body'>
            <h5 class='card-title'>$arr[title]</h5>
            <div id='grafik--$pie'></div>
            <script>
              document.addEventListener('DOMContentLoaded', () => {
                let pie_names = document.getElementById('pie_names--$pie').innerHTML.split(';');
                let pie_counts = document.getElementById('pie_counts--$pie').innerHTML.split(';');
                let counts = [];
                pie_counts.forEach((value, index) => {
                  counts[index] = parseInt(value);
                })

                new ApexCharts(document.querySelector('#grafik--$pie'), {
                  series: counts,
                  chart: {
                    height: 350,
                    type: 'pie',
                    toolbar: {
                      show: false
                    }
                  },
                  labels: pie_names
                }).render();
              });
            </script>
          </div>
        </div>      
      </div>  
    </div>  
  ";
}

?>
<style>
  .pie-chart {
    margin-bottom: 15px;
  }

  .pie-chart .card-title {
    text-align: center;
    border-bottom: solid 1px #ddf;
    padding-bottom: 10px;
  }
</style>

<div class="card mt4 mb2">
  <div class="card-header bg-info putih tengah">PMB Pie Charts</div>
</div>
<div class='row'>
  <?= $pie_charts ?>
</div>
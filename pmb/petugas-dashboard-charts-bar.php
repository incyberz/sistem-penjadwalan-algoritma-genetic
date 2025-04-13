<?php
# ============================================================
# GENDER FOR GRAFIK
# ============================================================
$bar_names = [];
$bar_counts = [];
$bar_charts = '';

$rbar = [
  'kec' => [
    'title' => 'Kecamatan Terbanyak'
  ],
  'kab' => [
    'title' => 'Kabupaten Terbanyak'
  ],
  'prov' => [
    'title' => 'Provinsi Terbanyak'
  ],
  'sekolah' => [
    'title' => 'Sekolah Terbanyak'
  ],
];

$label_names['gender'] = 'L;P'; // exception for gender
$label_counts['gender'] = join(';', $rgender);

foreach ($rbar as $bar => $arr) {
  $bar_names[$bar] = $label_names[$bar] ?? kosong("label_names:$bar");
  $bar_counts[$bar] = $label_counts[$bar] ?? kosong("label_counts:$bar");

  $bar_charts .= "
    <div class='col-4'>
      <div class='bar-chart'>
        <div class='hideit' id='bar_names--$bar'>$bar_names[$bar]</div>
        <div class='hideit' id='bar_counts--$bar'>$bar_counts[$bar]</div>

        <div class='card gradasi-toska'>
          <div class='card-body'>
            <h5 class='card-title'>$arr[title]</h5>
            <div id='grafik--$bar'></div>

            <script>
              document.addEventListener('DOMContentLoaded', () => {
                let bar_names = document.getElementById('bar_names--$bar').innerHTML.split(';');
                let bar_counts = document.getElementById('bar_counts--$bar').innerHTML.split(';');
                new ApexCharts(document.querySelector('#grafik--$bar'), {
                  series: [{
                    name: 'Count',
                    data: bar_counts
                  }],
                  chart: {
                    type: 'bar',
                    height: 350
                  },
                  plotOptions: {
                    bar: {
                      horizontal: true,
                      columnWidth: '55%',
                      endingShape: 'rounded'
                    },
                  },
                  dataLabels: {
                    enabled: false
                  },
                  stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                  },
                  xaxis: {
                    categories: bar_names,
                  },
                  yaxis: {
                    title: {
                      text: ''
                    }
                  },
                  fill: {
                    opacity: 1
                  },
                  tooltip: {
                    y: {
                      formatter: function(val) {
                        return ' ' + val + ' pendaftar'
                      }
                    }
                  }
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
  .bar-chart {
    margin-bottom: 15px;
  }

  .bar-chart .card-title {
    text-align: center;
    border-bottom: solid 1px #ddf;
    padding-bottom: 10px;
  }
</style>

<div class="card mt4 mb2">
  <div class="card-header bg-info putih tengah">PMB Best Charts</div>
</div>
<div class='row'>
  <?= $bar_charts ?>
</div>
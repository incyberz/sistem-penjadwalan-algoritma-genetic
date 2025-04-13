<div class="card gradasi-toska">
  <div class="card-body">
    <h5 class="card-title">Kecamatan Terbanyak</h5>

    <!-- FROM DB -->
    <?php
    if (!isset($label_names['kec']) || !$label_names['kec']) stop('kec_names tidak boleh kosong pada grafik kecamatan.');
    if (!isset($label_counts['kec']) || !$label_counts['kec']) stop('kec_counts tidak boleh kosong pada grafik kecamatan.');

    echo "
      <div class='hideit' id='kec_names'>$label_names[kec]</div>
      <div class='hideit' id='kec_counts'>$label_counts[kec]</div>
    ";
    ?>

    <!-- Column Chart -->
    <div id="grafikKec"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        let kec_names = document.getElementById('kec_names').innerHTML.split(';');
        let kec_counts = document.getElementById('kec_counts').innerHTML.split(';');
        new ApexCharts(document.querySelector("#grafikKec"), {
          series: [{
            name: 'Count',
            data: kec_counts
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
            categories: kec_names,
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
                return " " + val + " pendaftar"
              }
            }
          }
        }).render();
      });
    </script>
    <!-- End Column Chart -->

  </div>
</div>
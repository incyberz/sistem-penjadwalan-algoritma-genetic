<div class="card gradasi-toska">
  <div class="card-body">
    <h5 class="card-title">Kabupaten Terbanyak</h5>

    <!-- FROM DB -->
    <?php
    if (!isset($label_names['kab']) || !$label_names['kab']) stop('kab_names tidak boleh kosong pada grafik kabupaten.');
    if (!isset($label_counts['kab']) || !$label_counts['kab']) stop('kab_counts tidak boleh kosong pada grafik kabupaten.');

    echo "
      <div class='hideit' id='kab_names'>$label_names[kab]</div>
      <div class='hideit' id='kab_counts'>$label_counts[kab]</div>
    ";
    ?>

    <!-- Column Chart -->
    <div id="grafikKab"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        let kab_names = document.getElementById('kab_names').innerHTML.split(';');
        let kab_counts = document.getElementById('kab_counts').innerHTML.split(';');
        new ApexCharts(document.querySelector("#grafikKab"), {
          series: [{
            name: 'Count',
            data: kab_counts
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
            categories: kab_names,
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
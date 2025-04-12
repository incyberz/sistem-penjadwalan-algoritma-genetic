<div class="card gradasi-toska">
  <div class="card-body">
    <h5 class="card-title">Sekolah Terbanyak</h5>

    <!-- FROM DB -->
    <?php
    if (!isset($lokasi_names['sekolah']) || !$lokasi_names['sekolah']) stop('sekolah_names tidak boleh kosong pada grafik Sekolah ini.');
    if (!isset($lokasi_counts['sekolah']) || !$lokasi_counts['sekolah']) stop('sekolah_counts tidak boleh kosong pada grafik Sekolah ini.');

    echo "
      <div class='hideit' id='sekolah_names'>$lokasi_names[sekolah]</div>
      <div class='hideit' id='sekolah_counts'>$lokasi_counts[sekolah]</div>
    ";
    ?>

    <!-- Column Chart -->
    <div id="grafikSekolah"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        let sekolah_names = document.getElementById('sekolah_names').innerHTML.split(';');
        let sekolah_counts = document.getElementById('sekolah_counts').innerHTML.split(';');
        new ApexCharts(document.querySelector("#grafikSekolah"), {
          series: [{
            name: 'Count',
            data: sekolah_counts
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
            categories: sekolah_names,
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
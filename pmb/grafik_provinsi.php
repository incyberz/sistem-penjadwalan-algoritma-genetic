<div class="card gradasi-toska">
  <div class="card-body">
    <h5 class="card-title">Provinsi Terbanyak</h5>

    <!-- FROM DB -->
    <?php
    if (!isset($label_names['prov']) || !$label_names['prov']) stop('prov_names tidak boleh kosong pada grafik provinsi.');
    if (!isset($label_counts['prov']) || !$label_counts['prov']) stop('prov_counts tidak boleh kosong pada grafik provinsi.');

    echo "
      <div class='hideit' id='prov_names'>$label_names[prov]</div>
      <div class='hideit' id='prov_counts'>$label_counts[prov]</div>
    ";
    ?>

    <!-- Column Chart -->
    <div id="grafikProv"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        let prov_names = document.getElementById('prov_names').innerHTML.split(';');
        let prov_counts = document.getElementById('prov_counts').innerHTML.split(';');
        new ApexCharts(document.querySelector("#grafikProv"), {
          series: [{
            name: 'Count',
            data: prov_counts
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
            categories: prov_names,
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
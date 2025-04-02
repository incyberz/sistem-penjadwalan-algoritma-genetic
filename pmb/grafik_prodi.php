<div class="card gradasi-toska">
  <div class="card-body">
    <h5 class="card-title">Pendaftar per Prodi</h5>

    <!-- Column Chart -->
    <div id="grafikProdi"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#grafikProdi"), {
          series: [{
            name: 'Count',
            data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 46, 36]
          }],
          chart: {
            type: 'bar',
            height: 350
          },
          plotOptions: {
            bar: {
              horizontal: false,
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
            categories: ['KA', 'SI', 'BD', 'IF', 'TI', 'PS', 'MBS', 'PBI', 'BK', 'AG', 'TP'],
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
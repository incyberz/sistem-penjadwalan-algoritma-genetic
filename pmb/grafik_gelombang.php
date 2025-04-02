<div class="card gradasi-toska">
  <div class="card-body">
    <h5 class="card-title">Pendaftar per Gelombang</h5>

    <!-- Column Chart -->
    <div id="grafikGelombang"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#grafikGelombang"), {
          series: [{
            name: 'Count',
            data: [342, 55, 78, 0]
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
            categories: ['Gel-1', 'Gel-2', 'Gel-3', 'Gel-4'],
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
<div class="card gradasi-toska">
  <div class="card-body">
    <h5 class="card-title">Pendaftar per Jalur</h5>

    <!-- Column Chart -->
    <div id="grafikJalur"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#grafikJalur"), {
          series: [{
            name: 'Count',
            data: [342, 55, 10, 23]
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
            categories: ['Reguler', 'Bea-1', 'Bea-2', 'KIP Kuliah'],
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
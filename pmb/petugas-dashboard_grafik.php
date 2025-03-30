<?php
$tgls = '';
$counts = '';
$start = $today;
for ($i = 20; $i >= 0; $i--) {
  $koma = $tgls ? ',' : '';
  $tgl = date('Y-m-d', strtotime("-$i day", strtotime($start)));
  $tgls .= $koma . date('d M', strtotime($tgl));
  $s = "SELECT 1 FROM tb_akun WHERE role is null AND created_at >= '$tgl' AND created_at <= '$tgl 23:59:59'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $counts .= $koma . mysqli_num_rows($q);
}
echo "<i id=tgls class=hideit>$tgls</i>";
echo "<i id=counts class=hideit>$counts</i>";


?>

<script src="../assets/js/apexcharts.min.js"></script>
<div class="card mt2">
  <div class="card-body">
    <h5 class="card-title">Pendaftar harian</h5>

    <!-- Line Chart -->
    <div id="reportsChart"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        let tgls = document.getElementById('tgls').innerHTML.split(',');
        let counts = document.getElementById('counts').innerHTML.split(',');
        console.log(tgls, counts);

        new ApexCharts(document.querySelector("#reportsChart"), {
          series: [{
            name: 'Pendaftar',
            data: counts,
          }],
          chart: {
            height: 350,
            type: 'area',
            toolbar: {
              show: false
            },
          },
          markers: {
            size: 4
          },
          colors: ['#4154f1', '#2eca6a', '#ff771d'],
          fill: {
            type: "gradient",
            gradient: {
              shadeIntensity: 1,
              opacityFrom: 0.3,
              opacityTo: 0.4,
              stops: [0, 90, 100]
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'smooth',
            width: 2
          },
          xaxis: {
            type: 'text',
            categories: tgls
          },
          tooltip: {
            x: {
              format: 'dd/MM/yy HH:mm'
            },
          }
        }).render();
      });
    </script>
    <!-- End Line Chart -->

  </div>

</div>
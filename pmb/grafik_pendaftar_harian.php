<?php
include '../includes/selisih_hari.php';

$tgls = null;
$counts = null;
$durasi = 1;
$batas_akhir = $today;
if ($get_time == 'all_time' and $get_gel == 'all') {
  $durasi = 20;
} elseif ($get_time == 'hari_ini') {
  $durasi = 1;
} elseif ($get_time == 'bulan_ini') {
  $durasi = selisih_hari(date('Y-m') . '-01');
} elseif ($get_gel != 'all') {
  # ============================================================
  # CARI BATASAN GELOMBANG
  # ============================================================
  $s = "SELECT * FROM tb_gelombang WHERE tahun_pmb = $tahun_pmb ORDER BY nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $rgel = [];
  while ($d = mysqli_fetch_assoc($q)) {
    if ($d['nomor'] == 1) {
      $d['batas_awal'] = $awal_pmb;
    } else {
      $batas_akhir_sebelumnya = $rgel["$tahun_pmb-" . ($d['nomor'] - 1)]['batas_akhir'];
      $d['batas_awal'] = date('Y-m-d', strtotime('+1 day', strtotime($batas_akhir_sebelumnya)));
    }

    $rgel[$d['id']] = $d;

    if ($d['nomor'] == $get_gel) {
      $batas_akhir = $d['batas_akhir'];
      $durasi = intval((strtotime($d['batas_akhir']) - strtotime($d['batas_awal'])) / (3600 * 24));
    }
  }
} else {
  stop("Belum ada handler untuk get_time [ $get_time ] and get_gel [ $get_gel ]");
}

for ($i = $durasi; $i >= 0; $i = $i - 1) {
  $koma = $tgls ? ',' : '';
  $tgl = date('Y-m-d', strtotime("-$i day", strtotime($batas_akhir)));
  $tgls .= $koma . date('d M', strtotime($tgl));

  $s = "SELECT 1 FROM tb_akun a 
  JOIN tb_pmb b ON a.username=b.username -- menghindari data dummy akun
  WHERE 1 
  AND a.created_at >= '$tgl' 
  AND a.created_at <= '$tgl 23:59:59'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $counts .= $koma . mysqli_num_rows($q);
}

echo "<i id=tgls class=hideit>$tgls</i>";
echo "<i id=counts class=hideit>$counts</i>";

$tgls = $tgls ?? kosong('$tgls');
$counts = $counts ?? kosong('counts');
?>

<div class="card mt4 gradasi-toska" id=grafik_pendaftar>
  <div class="card-body">
    <h5 class="card-title">Pendaftar Harian</h5>

    <!-- Line Chart -->
    <div id="reportsChart"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        let tgls = document.getElementById('tgls').innerHTML.split(',');
        let counts = document.getElementById('counts').innerHTML.split(',');
        // console.log(tgls, counts);

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
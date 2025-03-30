<?php
include '../includes/script_btn_aksi.php';
include 'petugas-dashboard-styles.php';
set_title('Dashboard Petugas PMB');

$pendaftar_count = 0;
$invalid_whatsapp = 0;
$peserta_count = 0;
$lulus_tes = 0;
$belum_lulus = 0;
$tidak_lulus = 0;
$maba_count = 0;
$sudah_bayar_formulir = 0;
$belum_bayar_formulir = 0;
$sudah_registrasi = 0;
$belum_registrasi = 0;

$get_time = $_GET['time'] ?? 'all_time';
$get_gel = $_GET['gel'] ?? $gelombang_aktif;

# ============================================================
# NAVS 
# ============================================================
include 'petugas-dashboard-nav.php';


# ============================================================
# ARRAY STATUS AKUN
# ============================================================
$rstatus_akun = [];
$rstatus_akun_count = []; // jumlah pendaftar berdasarkan status
$s = "SELECT * FROM tb_status_akun ORDER BY id DESC";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $rstatus_akun[$d['id']] = $d;
  $rstatus_akun_count[$d['id']] = 0; // set nilai awal
}

# ============================================================
# ARRAY STATUS PMB
# ============================================================
$rstatus_pmb = [];
$rstatus_pmb_count = []; // jumlah pendaftar berdasarkan status
$s = "SELECT * FROM tb_status_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $rstatus_pmb[$d['id']] = $d;
  $rstatus_pmb_count[$d['id']] = 0; // set nilai awal
}

# ============================================================
# MAIN SELECT ALL AKUN
# ============================================================
$s = "SELECT 
a.active_status, 
a.whatsapp_status, 
a.last_step, 
a.lulus_tes_pmb, 
a.jumlah_tes, 
(
  SELECT 1 FROM tb_berkas 
  WHERE status=1 -- berkas terverifikasi
  AND jenis_berkas = 'FORMULIR' 
  AND username=a.username ) sudah_bayar_formulir,
(
  SELECT 1 FROM tb_berkas 
  WHERE status=1 -- berkas terverifikasi
  AND jenis_berkas = 'REGISTRASI' 
  AND username=a.username ) sudah_registrasi
FROM tb_akun a 
WHERE a.role is null
AND a.created_at >= '$awal'
AND a.created_at <= '$akhir'
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$pendaftar_count = mysqli_num_rows($q);
while ($d = mysqli_fetch_assoc($q)) {
  if ($d['active_status'] == 1) {
    if ($d['whatsapp_status']) {
      $peserta_count++;
      if ($d['sudah_bayar_formulir']) {
        $sudah_bayar_formulir++;
        if ($d['lulus_tes_pmb']) {
          $lulus_tes++;
          if ($d['sudah_registrasi']) {
            $sudah_registrasi++;
          } else {
            $belum_registrasi++;
          }
        } else {
          if ($d['jumlah_tes']) {
            $tidak_lulus++;
          } else {
            $belum_lulus++;
          }
        }
      } else {
        $belum_bayar_formulir++;
        $rstatus_pmb_count[$d['last_step']]++; // hanya menghitung yang belum bayar
      }
    } else {
      $invalid_whatsapp++;
    }
  }
  $rstatus_akun_count[$d['active_status']]++;
}

# ============================================================
# PESERTA REJECTED
# ============================================================
$peserta_rejected = '';
$rejected_count = 0;
foreach ($rstatus_akun_count as $id_status => $count) {
  if ($count and $id_status < 0) {
    $rejected_count += $count;
    $ket = $rstatus_akun[$id_status]['status'];
    $peserta_rejected .= "<div><span class='badge bg-danger'>$count - $ket</span></div>";
  }
}

# ============================================================
# PESERTA BELUM BAYAR
# ============================================================
$peserta_belum_bayar = '';
foreach ($rstatus_pmb_count as $id_status => $count) {
  if ($count) {
    $ket = $rstatus_pmb[$id_status]['status'];
    $peserta_belum_bayar .= "<div><span class='badge bg-danger'>$count - $ket</span></div>";
  }
}




$rUI = [
  'pendaftar' => [
    'title' => 'Pendaftar',
    'count' => $pendaftar_count,
    'satuan' => 'baru mencoba',
    'params' => '',
    'bg' => 'bg-pendaftar',
  ],
  'peserta' => [
    'title' => 'Peserta PMB',
    'count' => $peserta_count,
    'satuan' => 'peserta aktif',
    'params' => 'whatsapp_status=1',
    'bg' => 'bg-peserta',
  ],
  'lulus' => [
    'title' => 'Peserta Tes',
    'count' => $sudah_bayar_formulir,
    'satuan' => 'peserta',
    'params' => 'sudah_bayar=1',
    'bg' => 'bg-lulus',
  ],
  'registrasi' => [
    'title' => 'Mhs Baru',
    'count' => $sudah_registrasi,
    'satuan' => 'mhs',
    'params' => 'sudah_bayar=1',
    'bg' => 'bg-success',
  ],
];


$cols = '';
foreach ($rUI as $key => $rv) {
  $cols .= "
    <div class='col-3'>
      <div class='card $rv[bg] mb-3'>
        <div class='card-body'>
          <a href='?pendaftar&gel=$get_gel&$rv[params]' class=hover>
            <h5 class='card-title'>
              <span class='putih'>$rv[title]</span>
            </h5>
            <div class='card-text f40'>
              <span id='pendaftar-count' class=putih>$rv[count]</span>
              <span class='f12 miring putih'>$rv[satuan]</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  ";
}




echo "
  <div class='d-lg-none'>
    <b class=red>Dashboard PMB hanya dapat diakses via laptop, minimal 992 pixel lebar layar.</b>
  </div>

  <div class='d-none d-lg-block'>
    <div class='card'>
      <div class='card-header '>
        <div class='d-flex justify-content-center gap-4'>
          <div>$nav_times</div>
          <div>$nav_gels</div>
        </div>
      </div>
      <div class='card-body gradasi-toska putih'>
        <div class='abu tengah f12 mb2'><span class=text-primary>$awal_show</span> hingga <span class=text-primary>$akhir_show</span></div>
        <div class='row'>$cols</div>

        <div class='tengah abu f12'><span class='btn-aksi hover' id=detail_counts--toggle>Show Detail Counts</span></div>
        <div class='hideit' id=detail_counts>
          <div class='row mt2'>
            <div class='col-3'>
              <div class='card bg-pendaftar mb-3'>
                <div class='card-body'>
                  <span class=f30>$pendaftar_count</span> Pendaftar
                  <div><span class='badge bg-primary'>$peserta_count Peserta</span></div>
                  <div><span class='badge bg-danger'>$invalid_whatsapp Invalid Whatsapp</span></div>
                  <div><span class='badge bg-danger'>$rejected_count Rejected</span></div>
                  <div class='ml4'>$peserta_rejected</div>
                </div>
              </div>
            </div>
            <div class='col-3'>
              <div class='card bg-peserta mb-3'>
                <div class='card-body'>
                  <span class=f30>$peserta_count</span> Peserta
                  <div><span class='badge bg-primary'>$sudah_bayar_formulir Sudah Bayar (Siap Tes)</span></div>
                  <div><span class='badge bg-danger'>$belum_bayar_formulir Belum Bayar Formulir</span></div>
                  <div class='ml4'>$peserta_belum_bayar</div>
                </div>
              </div>
            </div>
            <div class='col-3'>
              <div class='card bg-lulus mb-3'>
                <div class='card-body'>
                  <span class=f30>$sudah_bayar_formulir</span> Peserta Tes
                  <div><span class='badge bg-success'>$lulus_tes Lulus Tes</span></div>
                  <div><span class='badge bg-danger'>$belum_lulus Belum Lulus</span></div>
                  <div><span class='badge bg-danger'>$tidak_lulus Tidak Lulus</span></div>
                </div>
              </div>
            </div>
            <div class='col-3'>
              <div class='card bg-info mb-3'>
                <div class='card-body'>
                  <span class=f30>$lulus_tes</span> Lulus Tes
                  <div><span class='badge bg-success'>$sudah_registrasi Sudah registrasi (Mhs Baru)</span></div>
                  <div><span class='badge bg-danger'>$belum_registrasi Belum Registrasi</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>



";

?>


























<script>
  // let time = 'all_time';
  // let gelombang = $('#gelombang_aktif').text();
  // $('#nav-time-all_time').addClass('nav-active');
  // $('#nav-gel-' + gelombang).addClass('nav-active');

  // $(function() {
  //   $('.nav-time').click(function() {
  //     $('.nav-time').removeClass('nav-active');
  //     $(this).addClass('nav-active');
  //     time = $(this).text().replace(' ', '_').toLowerCase();
  //   });
  // });
</script>
<?php
set_h2('Jadwal Tes PMB');
include '../includes/eta.php';
include '../includes/script_btn_aksi.php';

include 'jadwal_tes-process.php';

$s = "SELECT * FROM tb_pmb a 
JOIN tb_akun b ON a.username=b.username
WHERE a.nomor_peserta is not null 
AND a.tanggal_lulus_tes is null
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$tr = '';
$i = 0;
if (!mysqli_num_rows($q)) {
  alert('Data Peserta Tes tidak ditemukan');
  exit;
} else {
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $last_test = $d['last_test'] ? $d['last_test'] : '-';
    $tr .= "
      <tr>
        <td>$i</td>
        <td>$d[nama]</td>
        <td>$last_test</td>
      </tr>
    ";
  }
  echo "
    <div class='card mb3'>
      <div class='card-header f20 tengah putih bg-warning'>Peserta Belum Tes</div>
      <div class='card-body gradasi-merah'>
        <table class=table>
          <thead>
            <th>No</th>
            <th>Peserta Tes</th>
            <th>Terakhir Tes</th>
          </thead>
          $tr
        </table>
      </div>
    </div>
  ";
}


# ============================================================
# LIST TEST 
# ============================================================
$s = "SELECT a.*,
b.title as nama_tes,
b.ket as ket_tes

FROM tb_tes_pmb a 
JOIN tb_jenis_tes b ON a.jenis_tes=b.jenis_tes 
WHERE a.tahun_pmb = $tahun_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$i = 0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $id_tes = $d['id'];

  # ============================================================
  # JADWAL TES TERAKHIR
  # ============================================================
  $s2 = "SELECT a.* FROM tb_jadwal_tes a 
  JOIN tb_tes_pmb b ON a.id_tes=b.id 
  WHERE awal < '$today' -- kemarin, dst
  AND b.jenis_tes = '$d[jenis_tes]'
  ";
  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  if (mysqli_num_rows($q2)) {
    $terakhir_jadwal = '';
    while ($d2 = mysqli_fetch_assoc($q2)) {
      $tgl = date('d-M-Y', strtotime($d2['awal']));
      $jam = date('H:i', strtotime($d2['awal']));
      $jam_show = $d2['durasi'] ? "$jam ($d2[durasi] menit)" : "$jam s.d selesai";
      $eta = eta2($d2['awal']);
      $terakhir_jadwal .= "
        <div class=text-secondary>
          <div><b>Tanggal</b>: $tgl</div>
          <div><b>Jam</b>: $jam_show</div>
          <div><b>Lokasi</b>: $d2[lokasi]</div>
          <div class=f12>$eta</div>
        </div>
      ";
    }
  } else {
    $terakhir_jadwal = "<div class='text-secondary f12 miring'>belum pernah dilaksanakan.</i>";
  }


  # ============================================================
  # JADWAL SEKARANG
  # ============================================================
  $s2 = "SELECT a.* 
  FROM tb_jadwal_tes a 
  JOIN tb_tes_pmb b ON a.id_tes=b.id 
  WHERE a.awal >= '$today' -- hari ini, dst 
  AND b.jenis_tes = '$d[jenis_tes]'
  ORDER BY a.awal 
  LIMIT 1
  ";
  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  if (mysqli_num_rows($q2)) {
    $d2 = mysqli_fetch_assoc($q2);
    $tanggal = date('Y-m-d', strtotime($d2['awal']));
    $jam = date('H:i', strtotime($d2['awal']));
    $jam_show = $d2['durasi'] ? "$jam ($d2[durasi] menit)" : "$jam s.d selesai";
    $durasi = $d2['durasi'];
    $lokasi = $d2['lokasi'];
    $Set_Jadwal = 'Ubah Jadwal';
    $id_jadwal_tes = $d2['id'];
    $eta = eta2($d2['awal']);
    include 'jadwal_tes-form_set_jadwal.php';

    $tgl = date('d-M-Y', strtotime($d2['awal']));
    $jam = date('H:i', strtotime($d2['awal']));
    $eta = eta2($d2['awal']);
    $jadwal_skg = "
      <div class='text-success'>
        <div><b>Tanggal</b>: $tgl</div>
        <div><b>Jam</b>: $jam ($d2[durasi] menit)</div>
        <div><b>Lokasi</b>: $d2[lokasi]</div>
        <div class='d-flex flex-between'>
          <div class=f12>$eta</div> 
          <div>$form_set_jadwal</div>
        </div>
      </div>
    ";
  } else {
    $Set_Jadwal = 'Set Jadwal';
    $id_jadwal_tes = '';
    $tanggal = null;
    $jam = '08:00';
    $durasi = '0';
    $lokasi = null;
    include 'jadwal_tes-form_set_jadwal.php';
    $jadwal_skg = "
      <b class=red>belum ada.</b>
      $form_set_jadwal  
    ";
  }




  $tr .= "
    <tr id=tr__$d[id]>
      <td>$i</td>
      <td>
        $d[nama_tes]
        <div class='f12'>$d[ket_tes]</div>
      </td>
      <td>$terakhir_jadwal</td>
      <td>$jadwal_skg</td>
    </tr>
  ";
}

echo "
  <div class='card mb3'>
    <div class='card-header f20 tengah putih bg-info'>Manage Jadwal Tes</div>
    <div class='card-body gradasi-toska'>
      <table class=table>
        <thead>
          <th>No</th>
          <th>Jenis Tes PMB</th>
          <th>Terakhir Jadwal</th>
          <th width=35% class='bg-success putih'>Jadwal Sekarang</th>
        </thead>
        $tr
      </table>
    </div>
  </div>
";
?>
<div class="tengah p2">
  <a href="?petugas">Home Petugas</a>
</div>
<?php
$s = "SELECT 
nomor_ktp
FROM tb_biodata a 
JOIN tb_akun b ON a.username=b.username 
WHERE (b.active_status > 0 OR b.active_status is null) -- non peserta reject 
AND b.tahun_pmb = $tahun_pmb -- di tahun ini
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$rkec_all = [];
$rkab_all = [];
$rprov_all = [];
while ($d = mysqli_fetch_assoc($q)) {
  if ($d['nomor_ktp']) {
    $id_kec = substr($d['nomor_ktp'], 0, 6);
    if (!isset($rkec_all[$id_kec])) {
      $rkec_all[$id_kec] = 1; // inisialisasi jumlah kec
    } else {
      $rkec_all[$id_kec]++; // increment jumlah kec
    }

    // kab
    $id_kab = substr($d['nomor_ktp'], 0, 4);
    if (!isset($rkab_all[$id_kab])) {
      $rkab_all[$id_kab] = 1; // inisialisasi 
    } else {
      $rkab_all[$id_kab]++; // increment
    }

    // prov
    $id_prov = substr($d['nomor_ktp'], 0, 2);
    if (!isset($rprov_all[$id_prov])) {
      $rprov_all[$id_prov] = 1; // inisialisasi 
    } else {
      $rprov_all[$id_prov]++; // increment
    }
  }
}

# ============================================================
# URUTKAN ARRAY VALUE DESCENDING
# ============================================================
arsort($rkec_all);
arsort($rkab_all);
arsort($rprov_all);


# ============================================================
# TOP 10
# ============================================================
$top10 = [];
$rlokasi = [
  'kec' => [
    'array' => $rkec_all,
    'sql' => null,
  ],
  'kab' => [
    'array' => $rkab_all,
    'sql' => null,
  ],
  'prov' => [
    'array' => $rprov_all,
    'sql' => null,
  ],
];

foreach ($rlokasi as $lokasi => $rlokasi_all) {
  $top10[$lokasi] = [];
  $i = 0;
  $where_id[$lokasi] = '';
  foreach ($rlokasi_all['array'] as $id_lokasi => $count) {
    $i++;
    $OR = $where_id[$lokasi] ? 'OR' : '';
    if ($i > 10) break; // batasi hanya 10
    $top10[$lokasi][$id_lokasi] = $count;
    $where_id[$lokasi] .= " $OR id_$lokasi = '$id_lokasi'";
  }
  $where_id[$lokasi] = "($where_id[$lokasi])";

  # ============================================================
  # TAMBAHKAN DATA DARI DB
  # ============================================================
  $s = "SELECT id_$lokasi as id, nama_$lokasi as nama FROM tb_$lokasi WHERE $where_id[$lokasi]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $d['count'] = $top10[$lokasi][$d['id']];
    $top10[$lokasi][$d['id']] = $d; // replace count dg array
  }

  # ============================================================
  # DATA KEC FOR GRAFIK
  # ============================================================
  $lokasi_names[$lokasi] = '';
  $lokasi_counts[$lokasi] = '';
  foreach ($top10[$lokasi] as $id_lokasi => $d) {
    $koma = $lokasi_names[$lokasi] ? ';' : '';
    $lokasi_names[$lokasi] .= $d['nama'] ? "$koma$d[nama]" : stop("invalid id_$lokasi: $id_lokasi. Segera hubungi developer!");
    $lokasi_counts[$lokasi] .= "$koma$d[count]";
  }
}

// echo '<pre>';
// var_dump($top10);
// echo '<b style=color:red>Developer SEDANG DEBUGING: exit(true)</b></pre>';
// exit;








?>
<div class="card mt4">
  <div class="card-header bg-info putih tengah">Peta Pendaftar </div>
  <!-- <div class="card-body">
    </div> -->
</div>
<div class="row mt2">
  <div class="col-4">
    <?php include 'grafik_kecamatan.php'; ?>
  </div>
  <div class="col-4">
    <?php include 'grafik_kabupaten.php'; ?>
  </div>
  <div class="col-4">
    <?php include 'grafik_provinsi.php'; ?>
  </div>
</div>
<div class='f12 abu miring tengah mb4 mt1'>* Hanya Pendaftar Aktif (non-Reject)</div>
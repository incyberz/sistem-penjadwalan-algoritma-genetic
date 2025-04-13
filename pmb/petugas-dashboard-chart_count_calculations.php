<?php
$s = "SELECT 
(
  SELECT nomor_ktp FROM tb_biodata 
  WHERE username=a.username) nomor_ktp,
(
  SELECT id_sekolah FROM tb_data_sekolah
  WHERE username=a.username) id_sekolah,
(
  SELECT jenis_sekolah FROM tb_data_sekolah
  WHERE username=a.username) jenis_sekolah,
(
  SELECT jurusan FROM tb_data_sekolah
  WHERE username=a.username) jurusan,
(
  SELECT q.singkatan FROM tb_pmb p 
  JOIN tb_prodi q ON p.id_prodi=q.id 
  WHERE username=a.username) prodi,
(
  SELECT q.singkatan FROM tb_pmb p 
  JOIN tb_jalur q ON p.id_jalur=q.id 
  WHERE username=a.username) jalur,
(
  SELECT id_gelombang FROM tb_pmb
  WHERE username=a.username) gelombang,
(
  SELECT gender FROM tb_biodata
  WHERE username=a.username) gender
FROM tb_akun a 
JOIN tb_pmb b ON a.username=b.username -- wajib punya data PMB (non-dummy)
WHERE (a.active_status > 0 OR a.active_status is null) -- non peserta reject 
AND a.tahun_pmb = $tahun_pmb -- di tahun ini
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$rkec_all = [];
$rkab_all = [];
$rprov_all = [];

$rsekolah_all = [];
$rklask_all = [];
$rjurusan_all = [];

$rprodi_all = [];
$rjalur_all = [];
$rgelombang_all = [];

$rgender = [];

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

  if ($d['id_sekolah']) {
    $id_sekolah = $d['id_sekolah'];
    if (!isset($rsekolah_all[$id_sekolah])) {
      $rsekolah_all[$id_sekolah] = 1; // inisialisasi jumlah sekolah
    } else {
      $rsekolah_all[$id_sekolah]++; // increment jumlah sekolah
    }
  }

  if ($d['jenis_sekolah']) {
    $jenis_sekolah = $d['jenis_sekolah'];
    if (!isset($rklask_all[$jenis_sekolah])) {
      $rklask_all[$jenis_sekolah] = 1; // inisialisasi jumlah sekolah
    } else {
      $rklask_all[$jenis_sekolah]++; // increment jumlah sekolah
    }
  }

  if ($d['jurusan']) {
    $jurusan = $d['jurusan'];
    if (!isset($rjurusan_all[$jurusan])) {
      $rjurusan_all[$jurusan] = 1; // inisialisasi jumlah sekolah
    } else {
      $rjurusan_all[$jurusan]++; // increment jumlah sekolah
    }
  }

  if ($d['prodi']) {
    $prodi = $d['prodi'];
    if (!isset($rprodi_all[$prodi])) {
      $rprodi_all[$prodi] = 1; // inisialisasi jumlah sekolah
    } else {
      $rprodi_all[$prodi]++; // increment jumlah sekolah
    }
  }

  if ($d['jalur']) {
    $jalur = $d['jalur'];
    if (!isset($rjalur_all[$jalur])) {
      $rjalur_all[$jalur] = 1; // inisialisasi jumlah sekolah
    } else {
      $rjalur_all[$jalur]++; // increment jumlah sekolah
    }
  }

  if ($d['gelombang']) {
    $gelombang = $d['gelombang'];
    if (!isset($rgelombang_all[$gelombang])) {
      $rgelombang_all[$gelombang] = 1; // inisialisasi jumlah sekolah
    } else {
      $rgelombang_all[$gelombang]++; // increment jumlah sekolah
    }
  }

  if ($d['gender']) {
    $gender = $d['gender'];
    if (!isset($rgender[$gender])) {
      $rgender[$gender] = 1; // inisialisasi jumlah sekolah
    } else {
      $rgender[$gender]++; // increment jumlah sekolah
    }
  }
}


# ============================================================
# URUTKAN ARRAY VALUE DESCENDING
# ============================================================
arsort($rkec_all);
arsort($rkab_all);
arsort($rprov_all);

arsort($rsekolah_all);
arsort($rklask_all);
arsort($rjurusan_all);

arsort($rprodi_all);
arsort($rjalur_all);
arsort($rgelombang_all);



# ============================================================
# TOP 10
# ============================================================
$top10 = [];
$rtb = [
  'kec' => [
    'array' => $rkec_all,
    'tb' => 'kec',
    'where_id' => 'id_kec',
  ],
  'kab' => [
    'array' => $rkab_all,
    'tb' => 'kab',
    'where_id' => 'id_kab',
  ],
  'prov' => [
    'array' => $rprov_all,
    'tb' => 'prov',
    'where_id' => 'id_prov',
  ],
  'sekolah' => [
    'array' => $rsekolah_all,
    'tb' => 'sekolah',
    'where_id' => 'id',
  ],
  'klask' => [
    'array' => $rklask_all,
    'tb' => 'sekolah',
    'where_id' => '',
  ],
  'jurusan' => [
    'array' => $rjurusan_all,
    'tb' => 'sekolah',
    'where_id' => 'id',
  ],
  'prodi' => [
    'array' => $rprodi_all,
    'tb' => 'prodi',
    'where_id' => 'id',
  ],
  'jalur' => [
    'array' => $rjalur_all,
    'tb' => 'jalur',
    'where_id' => 'id',
  ],
  'gelombang' => [
    'array' => $rgelombang_all,
    'tb' => 'gelombang',
    'where_id' => 'id',
  ],
];

foreach ($rtb as $tb => $arr) {
  $top10[$tb] = [];
  $i = 0;
  $where_id[$tb] = '';
  foreach ($arr['array'] as $id_tb => $count) {
    $i++;
    $OR = $where_id[$tb] ? 'OR' : '';
    if ($i > 10) break; // batasi hanya 10
    $top10[$tb][$id_tb] = $count;
    $where_id[$tb] .= $arr['where_id'] ? " $OR $arr[where_id] = '$id_tb'" : '';
  }
  $where_id[$tb] = "($where_id[$tb])";

  if ($tb == 'klask' || $tb == 'jurusan' || $tb == 'prodi' || $tb == 'jalur' || $tb == 'gelombang') {
    if ($tb == 'klask') {
      $ds = [
        1 => 'SMA',
        2 => 'SMK',
        3 => 'MA',
      ];
      foreach ($ds as $id => $nama) {
        $d['id'] = $id;
        $d['nama'] = $nama;
        $d['count'] = $top10[$tb][$d['id']]; // tambahkan count dari data top 10
        $top10[$tb][$d['id']] = $d; // replace count dg array
      }
    } else {
      $ds = $arr['array'];
      foreach ($ds as $jurusan => $count) {
        $d['id'] = $jurusan;
        $d['nama'] = $jurusan;
        $d['count'] = $count; // tambahkan count dari data top 10
        $top10[$tb][$d['id']] = $d; // replace count dg array
      }
      // echo '<pre>';
      // var_dump($ds);
      // echo '<b style=color:red>Developer SEDANG DEBUGING: exit(true)</b></pre>';
      // exit;
    }
  } else {
    # ============================================================
    # TAMBAHKAN DATA DARI DB
    # ============================================================
    $s = "SELECT $arr[where_id] as id, nama_$tb as nama FROM tb_$arr[tb] WHERE $where_id[$tb]";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    while ($d = mysqli_fetch_assoc($q)) {
      $d['count'] = $top10[$tb][$d['id']]; // tambahkan count dari data top 10
      $top10[$tb][$d['id']] = $d; // replace count dg array
    }
  }

  # ============================================================
  # DATA LABELS FOR GRAFIK
  # ============================================================
  $label_names[$tb] = '';
  $label_counts[$tb] = '';
  foreach ($top10[$tb] as $id_tb => $d) {
    $koma = $label_names[$tb] ? ';' : '';
    $label_names[$tb] .= $d['nama'] ? "$koma$d[nama]" : stop("invalid id_$tb: $id_tb. Segera hubungi developer!");
    $label_counts[$tb] .= "$koma$d[count]";
  }
}

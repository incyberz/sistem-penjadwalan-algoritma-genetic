<style>
  .div_counts {
    display: grid;
    grid-template-columns: auto auto auto auto auto auto auto;
  }

  .div_count {
    min-width: 100px;
    background: #eef;
    padding: 10px;
    border: solid 1px #ccc;
    border-radius: 5px;
  }

  a:hover {
    text-decoration: none;
  }
</style>
<?php
$rcount = [
  'prodi' => [
    'title' => 'Prodi',
    'title2' => '',
    'href' => '?crud&tb=prodi',
    'class' => 'd-none d-md-block',
    'sql_filter' => "SELECT 1 FROM tb_prodi WHERE status=100",
    'sql_total' => "SELECT 1 FROM tb_prodi",
    'satuan' => 'Prodi Terverifikasi',
    'deskripsi' => 'Prodi yang sudah terverifikasi (status: 100)',
  ],
  'mk' => [
    'title' => "Mata Kuliah",
    'title2' => 'MK Terjadwal',
    'href' => '?crud&tb=mk',
    'class' => '',
    'sql_filter' => "SELECT 1 FROM tb_kumk a 
      JOIN tb_kurikulum b ON a.id_kurikulum=b.id 
      JOIN tb_st_detail c ON a.id=c.id_kumk 
      WHERE b.id_ta=$ta_aktif
      ",
    'sql_total' => "SELECT 1 FROM tb_kumk a 
      JOIN tb_kurikulum b ON a.id_kurikulum=b.id 
      WHERE b.id_ta=$ta_aktif
      ",
    'satuan' => "MK Kurikulum $tahun_ta $Gg",
    'deskripsi' => 'MK Kurikulum artinya MK yang terdapat pada <a target=_blank href=?struktur_kurikulum>Struktur Kurikulum</a>, sedangkan MK Terjadwal artinya MK yang sudah terpasang di Jadwal Kuliah',
  ],
  'kelas' => [
    'title' => "Grup Kelas",
    'title2' => 'Grup Kelas Terverifikasi',
    'href' => '?crud&tb=kelas',
    'class' => 'd-none d-lg-block',
    'sql_filter' => "SELECT 1 FROM tb_kelas WHERE status=100",
    'sql_total' => "SELECT 1 FROM tb_kelas WHERE id_ta=$ta_aktif",
    'deskripsi' => 'Kelas Terverifikasi artinya semua informasi mengenai kelas tersebut sudah lengkap',
  ],
  'dosen' => [
    'title' => 'Dosen Aktif',
    'title2' => 'Dosen Terjadwal',
    'href' => '?crud&tb=dosen',
    'class' => 'd-none d-sm-block',
    'sql_filter' => "SELECT 1 FROM tb_dosen a 
      JOIN tb_st b ON a.id=b.id_dosen -- punya ST
      WHERE a.status=1 -- dosen aktif 
      AND b.id_ta = $ta_aktif
      ",
    'sql_total' => "SELECT 1 FROM tb_dosen WHERE status=1 -- dosen aktif",
    'satuan' => 'dosen',
    'deskripsi' => "Dosen Terjadwal artinya yang sudah punya Surat Tugas di TA $tahun_ta $Gg",
  ],
  'ruang' => [
    'title' => 'Ruangan',
    'title2' => '',
    'href' => '?crud&tb=ruang',
    'class' => 'd-none d-lg-block',
    'sql_filter' => '',
    'sql_total' => "SELECT 1 FROM tb_ruang WHERE status=1 -- ready pakai",
    'satuan' => 'Ruangan Terpakai',
    'deskripsi' => "Ruang Terpakai adalah ruang yang pernah dipakai di TA $tahun_ta $Gg dengan [status: 100]",
  ],
  'st' => [
    'title' => "Surat Tugas",
    'title2' => 'Verified ST',
    'href' => '?st',
    'class' => '',
    'sql_filter' => "SELECT 1 FROM tb_st WHERE id_ta=$ta_aktif AND verif_date is not null",
    'sql_total' => "SELECT 1 FROM tb_st WHERE id_ta=$ta_aktif",
    'satuan' => 'Surat Tugas',
    'deskripsi' => "Verified ST artinya Surat Tugas yang sudah terverifikasi oleh Petugas atau Pimpinan/Kaprodi",
  ],
  'jadwal' => [
    'title' => 'Penjadwalan',
    'href' => '?jadwal',
    'class' => '',
    'sql_filter' => "SELECT 1 FROM tb_jadwal a 
      JOIN tb_st_detail c ON a.id=c.id
      JOIN tb_st d ON c.id_st=d.id
      WHERE d.id_ta=$ta_aktif
      ",
    'sql_total' => "SELECT 1 FROM tb_st_detail a
      JOIN tb_st b ON a.id_st=b.id
      WHERE b.id_ta=$ta_aktif
      ",
    'satuan' => 'Penjadwalan (MK pada Surat Tugas Terverifikasi)',
    'deskripsi' => "Syarat Penjadwalan yaitu Surat Tugas sudah diverifikasi dan kelas aktif sudah ada.",
  ],
];

$div_counts = '';
foreach ($rcount as $tb => $arr) {
  $q = mysqli_query($cn, $arr['sql_total']) or die(mysqli_error($cn));
  $count_total = mysqli_num_rows($q);
  $rcount[$tb]['count_total'] = $count_total;
  $All = strpos($arr['sql_total'], $ta_aktif) ? '' : 'All ';
  $title = "<a href='$arr[href]' class=div_count_title>$arr[title]</a>";

  $span_count_total = "<span id=count_total__$tb>$count_total</span>";
  $div_count_of = "<div class='f30 abu'>$span_count_total</div>";
  $div_count_OK = '';

  if (isset($detail_progress)) {
    if ($tb == 'ruang') {
      # ============================================================
      # HITUNG RUANG AKTIF
      # ============================================================
      $s = "SELECT 1,
      (
        SELECT COUNT(1) FROM tb_pemakaian_ruang p
        WHERE id LIKE '$ta_aktif-%' 
        AND id_ruang=a.id
        ) terpakai 
      FROM tb_ruang a WHERE a.status=1";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      $terpakai = 0;
      while ($d = mysqli_fetch_assoc($q)) {
        if ($d['terpakai']) $terpakai++;
      }
      $rcount[$tb]['title2'] = 'Ruangan Terpakai';
      $rcount[$tb]['count_filter'] = $terpakai;
      $count_filter = $terpakai;
    } else {
      $q = mysqli_query($cn, $arr['sql_filter']) or die(mysqli_error($cn));
      $count_filter = mysqli_num_rows($q);
    }
    $rcount[$tb]['count_filter'] = $count_filter;
    $All = (strpos($arr['sql_filter'], $ta_aktif) || $count_filter) ? '' : 'All '; // replace All sign
    $title2 = $rcount[$tb]['title2'] ?? null;
    $title = $title2 ? $title2 : $arr['title'];
    $div_count_OK = ($count_total && $count_total == $count_filter) ? 'div_count_OK' : 'div_count_warning';
    $icon = ($count_total && $count_filter == $count_total) ? $img_check : '';
    $div_count_of = "
      <div class='f30 abu'>
        <span id=count_filter__$tb class=green>$count_filter</span>
        <i class=f10>of</i>
        <i class=f14>$span_count_total</i>
        $icon
      </div>
    ";
  }

  $div_count_active = $tb == $get_tb ? 'div_count_active' : '';

  $div_counts .= "
    <div class='div_count $div_count_active $div_count_OK $arr[class]' id=div_count__$tb>
      <div class='darkblue f12'>
        <span class=pointer onclick='alert(`All artinya data $arr[title] berlaku di semua Tahun Ajar.`)'>$All</span>
        $title
      </div> 
      $div_count_of 
    </div>
  ";
}

echo "
  <div class='div_counts mb4 gap-1'>
    $div_counts
  </div>
";

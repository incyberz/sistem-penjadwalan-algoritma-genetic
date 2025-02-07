<?php
session_start();
$keyword = $_GET['keyword'] ?? die("Undefined index [keyword]");
$id_prodi = $_GET['id_prodi'] ?? die("Undefined index [id_prodi]");
$id_kumk = $_GET['id_kumk'] ?? die("Undefined index [id_kumk]");
$id_dosen = $_GET['id_dosen'] ?? '';

include '../conn.php';

$s = "SELECT 
a.id,
a.id_prodi,
a.nama,
(SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi) homebase, 
(SELECT id FROM tb_st WHERE id_dosen=a.id AND id_ta='$_SESSION[ta_aktif]') id_st, 
(SELECT verif_date FROM tb_st WHERE id_dosen=a.id AND id_ta='$_SESSION[ta_aktif]') verif_date 
FROM tb_dosen a 
WHERE nama like '%$keyword%' 
AND a.id != '$id_dosen'
ORDER BY a.nama
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
$i = 0;
if (!mysqli_num_rows($q)) {
  echo "
      <li class='p2 gradasi-merah abu miring f12 tengah'>
        dosen tidak ditemukan | <a target=_blank onclick='return confirm(`Tambah dosen baru?`)' href='?crud&tb=dosen&note=Tambah Dosen untuk Struktur Kurikulum'>Tambah</a>
      </li>
    ";
} else {
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $nama = strlen($d['nama']) > 23 ? substr($d['nama'], 0, 20) . '...' : $d['nama'];
    if ($d['homebase']) {
      $homebase = $d['homebase'];
      $li_class = $d['id_prodi'] == $id_prodi ? 'blue bold' : '';
    } else {
      $homebase = '(LB)';
      $li_class = 'miring abu';
    }
    if ($d['verif_date']) {
      $verif_date = date('d F, Y');
      echo "
        <li  class='flexy flex-between p2 gradasi-kuning'>
          <div onclick='alert(`Surat Tugas sudah terverifikasi.\n\nRollback dahulu jika ingin re-assign Surat Tugas.`)' style='cursor:not-allowed'>$nama</div>
          <div>
            <a href='?st&id_st=$d[id_st]' target=_blank>ST verified at $verif_date</a>
          </div>
        </li>
      ";
    } else {
      echo "
        <li id='item_list_dosen__$d[id]__$id_kumk' class='$li_class item_list_dosen flexy flex-between'>
          <div id='nama_dosen__$d[id]__$id_kumk'>$nama</div>
          <div>$homebase</div>
        </li>
      ";
    }
  }
}

<?php
$get_id_kelas = $_GET['id_kelas'] ?? udef('id_kelas');

$s = "SELECT * FROM tb_kelas WHERE id=$get_id_kelas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $kelas = mysqli_fetch_assoc($q);
  set_h2('Pencarian Surat Tugas', "TA $tahun_ta $Gg Kelas <b class=darkblue>$kelas[nama]</b> ");
} else {
  die(alert("Data kelas tidak ditemukan."));
}

$s = "SELECT *,
a.id_st, 
c.nama as nama_dosen, 
e.nama as nama_mk,
(SELECT 1 FROM tb_jadwal WHERE id=a.id) terjadwal 
FROM tb_st_detail a 
JOIN tb_st b ON a.id_st=b.id 
JOIN tb_dosen c ON b.id_dosen=c.id 
JOIN tb_kumk d ON a.id_kumk=d.id 
JOIN tb_mk e ON d.id_mk=e.id 
WHERE id_kelas = $get_id_kelas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$tr = '';
$i = 0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $terjadwal = $d['terjadwal'] ? 'terjadwal' : '-';
  $tr .= "
    <tr id=tr__$d[id]>
      <td>$i</td>
      <td>
        <a href='?st&id_st=$d[id_st]'>$d[nama_dosen]</a>
      </td>
      <td>$d[nama_mk]</td>
      <td>$terjadwal</td>
    </tr>
  ";
}

echo "
  <table class=table>
    <thead>
      <th>No</th>
      <th>Dosen</th>
      <th>MK</th>
      <th>Status</th>
    </thead>
    $tr
  </table>";

<?php
if (!isset($unik_dosen)) udef('unik_dosen');
$s = "SELECT 
    e.nama as nama_dosen,
    f.nama as nama_mk,
    f.semester,
    g.nama as nama_kelas,
    h.nama as nama_ruang,
    i.nama as nama_prodi,
    1
    FROM tb_pemakaian_ruang a 
    JOIN tb_st_detail b ON a.id_st_detail=b.id 
    JOIN tb_kumk c ON b.id_kumk=c.id 
    JOIN tb_st d ON b.id_st=d.id 
    JOIN tb_dosen e ON d.id_dosen=e.id 
    JOIN tb_mk f ON c.id_mk=f.id 
    JOIN tb_kelas g ON b.id_kelas=g.id 
    JOIN tb_ruang h ON a.id_ruang=h.id 
    JOIN tb_prodi i ON g.id_prodi=i.id 
    WHERE a.unik_dosen='$unik_dosen'
    ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$arr = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$nama_hari = $arr[$weekday];

alert("
  <span class='consolas red'>CONFLICT DETECTED</span>
  <hr>
  Di hari <b>$nama_hari</b> sesi <b>ke-$id_sesi</b>, 
  Dosen <b>$d[nama_dosen]</b> sedang aktif mengajar di: 
    <ul>
      <li><b>Ruang:</b> $d[nama_ruang]</li>
      <li><b>MK:</b> $d[nama_mk]</li>
      <li><b>Kelas:</b> $d[nama_kelas]</li>
      <li><b>Prodi:</b> $d[nama_prodi]</li>
      <li><b>Semester:</b> $d[semester]</li>
    </ul>
  <hr>
  proses dibatalkan... <span class='btn btn-primary btn-sm' onclick='location.replace(`?jadwal&id_kelas=$get_id_kelas`)'>OK</span>
");
exit;

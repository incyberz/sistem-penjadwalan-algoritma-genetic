<?php
$s = "SELECT 
a.*,
c.jam_mulai,
c.jam_selesai,
c.weekday,
c.id_sesi_at_book,
d.id as id_kelas,
d.nama as nama_kelas,
f.nama as nama_mk,
f.sks,
CONCAT(COALESCE(h.gelar_depan,''),' ',h.nama,', ',COALESCE(h.gelar_belakang,'')) as nama_lengkap_dosen,
i.nama as nama_ruang

FROM tb_pemakaian_ruang a 
JOIN tb_st_detail b ON a.id_st_detail=b.id 
JOIN tb_jadwal c ON b.id=c.id 
JOIN tb_kelas d ON b.id_kelas=d.id 
JOIN tb_kumk e ON b.id_kumk=e.id 
JOIN tb_mk f ON e.id_mk=f.id 
JOIN tb_st g ON b.id_st=g.id 
JOIN tb_dosen h ON g.id_dosen=h.id 
JOIN tb_ruang i ON a.id_ruang=i.id 
WHERE a.id LIKE '$ta_aktif-%'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rpenjadwalan = [];
$rpemakaian = []; // ruang
while ($d = mysqli_fetch_assoc($q)) {
  $rpenjadwalan[$d['weekday']][$d['id_kelas']][$d['id_sesi']] = $d;
  $rpemakaian[$d['weekday']][$d['id_ruang']][$d['id_sesi']] = $d;
}

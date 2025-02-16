<?php

# ============================================================
# DATA AVAILABLE MK (ST-DETAIL) | UNSIGNED JADWAL
# ============================================================
$available_kumk = '';
$c_id_dosen = $dosen ? "c.id_dosen = $dosen[id]" : 1;


$s = "SELECT 
a.id,
d.nama as nama_mk,
d.sks,
e.nama as nama_dosen,
(SELECT 1 FROM tb_jadwal WHERE id=a.id) sudah_terjadwal

FROM tb_st_detail a  
JOIN tb_st c ON a.id_st=c.id 
JOIN tb_kumk d2 ON a.id_kumk=d2.id 
JOIN tb_mk d ON d2.id_mk=d.id 
JOIN tb_dosen e ON c.id_dosen=e.id 
JOIN tb_kelas f ON a.id_kelas=f.id 
JOIN tb_kurikulum g ON d2.id_kurikulum=g.id 
JOIN tb_prodi h ON g.id_prodi=h.id 
LEFT JOIN tb_jadwal i ON a.id=i.id 
WHERE 1 
AND d.semester = '$get_semester' 
AND f.id = '$get_id_kelas' 
AND f.id_shift = '$get_id_shift' 
AND h.fakultas = '$get_fakultas' 
AND i.id is null 
AND $c_id_dosen
ORDER BY nama_mk";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// $rst_mk_kelas = [];
$kumk_count = mysqli_num_rows($q);
// $rkumk_count[$id_kelas] = $kumk_count;

while ($d = mysqli_fetch_assoc($q)) {
  $available_kumk .= "
    <label class='label_mk_dosen'>
      <div>
        <input required type=radio name=id_radio value=$d[id]__$d[sks]>
        $d[nama_mk]
      </div>
      <div class='abu miring f14'>$d[nama_dosen]- $d[sks] SKS</div>
    </label>
  ";
}

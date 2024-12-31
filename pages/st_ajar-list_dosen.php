<?php
$get_id_dosen = $_GET['id_dosen'] ?? '';
$sql_id_dosen = $get_id_dosen ? "a.id = $get_id_dosen" : '1';
$s = "SELECT a.*,
(
  SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi) homebase,
(
  SELECT id FROM tb_st p  
  WHERE p.id_dosen=a.id 
  AND p.id_ta = $ta_aktif) id_st,
(
  SELECT COUNT(1) FROM tb_st_mk p 
  JOIN tb_st q ON p.id_st=q.id 
  WHERE q.id_dosen=a.id 
  AND q.id_ta = $ta_aktif) count_mk,
(
  SELECT COUNT(1) FROM tb_st_mk p 
  JOIN tb_st q ON p.id_st=q.id 
  JOIN tb_mk r ON p.id_mk=r.id 
  JOIN tb_st_mk_kelas s ON s.id_st_mk=p.id 
  WHERE q.id_dosen=a.id 
  AND q.id_ta = $ta_aktif) count_sks

FROM tb_dosen a WHERE $sql_id_dosen";

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  while ($d = mysqli_fetch_assoc($q)) {

    if ($d['id_prodi'] == $dkur['id_prodi']) {
      $blue =  'blue bold';
      $homebase = "(homebase $d[homebase])";
    } elseif ($d['homebase']) {
      $blue = '';
      $homebase = "($d[homebase])";
    } else {
      $blue = 'abu miring';
      $homebase = "(LB)";
    }

    $this_list_mk = '';
    $id_mks = '';
    if ($d['count_mk']) {
      $s2 = "SELECT b.id,b.nama as nama_mk FROM tb_st_mk a 
      JOIN tb_mk b ON a.id_mk=b.id 
      WHERE a.id_st='$d[id_st]'";
      // echolog($s2);
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $li = '';
      while ($d2 = mysqli_fetch_assoc($q2)) {
        $id_mks .= "$d2[id],";
        $li .= "<li>$d2[nama_mk]</li>";
      }
      $this_list_mk = "<ul>$li</ul>";
    }


    $list_dosen .= "
      <div class='$blue'>
        <label class='pointer label_dosen' id=label_dosen__$d[id]>
          <input type='checkbox' name='check_dosen[$d[id]]'> 
          <span class=nama_dosen id=nama_dosen__$d[id]>$d[nama]</span> 
          $homebase
          - <span class=hideita id=count_mk__$d[id]>$d[count_mk]</span> MK
          - <span class=hideita id=count_sks__$d[id]>$d[count_sks]</span> SKS
          <span class=hideit id=gelar_depan__$d[id]>$d[gelar_depan]</span> 
          <span class=hideit id=gelar_belakang__$d[id]>$d[gelar_belakang]</span> 
          <span class=hideit id=nidn__$d[id]>$d[nidn]</span> 
        </label>
      </div>  
      <div id=list_mk__$d[id] class=hideit>
        <div class='wadah gradasi-kuning'>
          $this_list_mk
          <a class='btn btn-primary' href='?st_ajar&id_kurikulum=$id_kurikulum&aksi=manage&id_st=$d[id_st]'>Manage Surat Tugas ini</a>
        </div>
        <div id=id_mks__$d[id]>$id_mks</div>

        <hr>
        <div class=mb2>atau silahkan: Tambahkan MK Lainnya.</div>
      </div>
    ";
  }

  $list_dosen = ($get_id_dosen ? '' : "<div class='mb1 f12'>Pilih salah satu Dosen:</div>") . $list_dosen;
} else {
  $list_dosen .= alert("Belum ada satupun dosen di prodi [$dkur[nama_prodi]]<hr><a href='?crud&tb=dosen'>Buat Pilihan dosen</a>", 'danger', '', false);
  $siap_assign = false;
}

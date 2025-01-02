<?php
$sql_id_dosen = $id_dosen ? "a.id = $id_dosen" : '1';
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
  SELECT SUM(r.sks) FROM tb_st_mk p 
  JOIN tb_st q ON p.id_st=q.id 
  JOIN tb_mk r ON p.id_mk=r.id 
  JOIN tb_st_mk_kelas s ON s.id_st_mk=p.id 
  WHERE q.id_dosen=a.id 
  AND q.id_ta = $ta_aktif) sum_sks

FROM tb_dosen a WHERE $sql_id_dosen";

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;

    if ($d['id_prodi'] == $kurikulum['id_prodi']) {
      $blue =  'blue bold';
      $homebase = "homebase $d[homebase]";
    } elseif ($d['homebase']) {
      $blue = 'green bold';
      $homebase = "$d[homebase]";
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

    $count_mk = $d['count_mk'] ? "<span class='blue bold'><span id=count_mk__$d[id]>$d[count_mk]</span> MK</span>" : '-';
    $sum_sks = $d['sum_sks'] ? "<span class='blue bold'><span id=sum_sks__$d[id]>$d[sum_sks]</span> SKS</span>" : '-';

    $list_dosen .= "
      <tr>
        <td>$i</td>
        <td>
          <a href='?st_ajar&id_kurikulum=2&id_dosen=$d[id]' class='pointer a_dosen' id=a_dosen__$d[id]>
            <span class='nama_dosen $blue' id=nama_dosen__$d[id]>$d[nama]</span> $img_next
          </a>
        </td>
        <td><span class='$blue'>$homebase</span></td>
        <td>$count_mk</td>
        <td>$sum_sks</td>
      </tr>  
    ";
  } // end while
  $list_dosen = "
    <div class='row' id=list_dosen>
      <div class='col-sm-12'>
        <div class='wadah gradasi-hijau'>
          <table class='table table-hover table-striped'>
            <thead>
              <th>No</th>
              <th>Dosen</th>
              <th>Homebase</th>
              <th>MK</th>
              <th>SKS</th>
            </thead>
            $list_dosen
          </table>
        </div>
      </div>
    </div>
  ";
} else {
  $list_dosen .= alert("Belum ada satupun dosen di prodi [$kurikulum[nama_prodi]]<hr><a href='?crud&tb=dosen'>Buat Pilihan dosen</a>", 'danger', '', false);
  $siap_assign = false;
}

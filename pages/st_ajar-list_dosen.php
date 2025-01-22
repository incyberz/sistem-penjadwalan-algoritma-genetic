<?php
$kurikulum = [];
$kurikulum['id_prodi'] = null;

$list_dosen = '';

$s = "SELECT a.*,
(
  SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi) homebase,
(
  SELECT id FROM tb_st p  
  WHERE p.id_dosen=a.id 
  AND p.id_ta = $ta_aktif) id_st,
(
  SELECT COUNT(1) FROM tb_st_detail p 
  JOIN tb_st q ON p.id_st=q.id 
  WHERE q.id_dosen=a.id 
  AND q.id_ta = $ta_aktif) count_kumk,
(
  SELECT SUM(r.sks) FROM tb_st_detail p 
  JOIN tb_kumk q ON p.id_kumk=q.id 
  JOIN tb_mk r ON q.id_mk=r.id 
  JOIN tb_st s ON p.id_st=s.id 
  WHERE s.id_dosen=a.id 
  AND s.id_ta = $ta_aktif) sum_sks

FROM tb_dosen a 
WHERE 1 
ORDER BY sum_sks DESC, a.nama  

";


$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;

    if ($d['id_prodi'] and $d['id_prodi'] == $kurikulum['id_prodi']) {
      $blue =  'blue bold';
      $homebase = "homebase $d[homebase]";
    } elseif ($d['homebase']) {
      $blue = 'darkblue ';
      $homebase = "$d[homebase]";
    } else {
      $blue = 'abu miring';
      $homebase = "(LB)";
    }



    if ($d['count_kumk']) {
      $count_kumk = "<span class='blue bold'><span id=count_mk__$d[id]>$d[count_kumk]</span> MK</span>";
      $sum_sks = "<span class='blue bold'><span id=count_mk__$d[id]>$d[sum_sks]</span> SKS</span>";
      $manage_st = "<a href='?st_ajar&id_st=$d[id_st]' class='pointer' id=a_dosen__$d[id]>$img_next</a>";
      $hideit = '';
      $tr_class = 'punya_st';
    } else {
      $tr_class = 'no_st';
      // $hideit = 'hideit';
      $hideit = '';
      $count_kumk = '-';
      $sum_sks = '-';
      $manage_st = '';
    }

    $list_dosen .= "
      <tr class='tr_dosen tr_dosen__$tr_class $hideit'>
        <td>$i</td>
        <td>
          <span class='nama_dosen $blue' id=nama_dosen__$d[id]>$d[nama]</span>
          $manage_st
        </td>
        <td><span class='$blue'>$homebase</span></td>
        <td>$count_kumk</td>
        <td>$sum_sks</td>
      </tr>  
    ";
  } // end while
  $list_dosen = "
    <div id=list_dosen>
      <div class=tengah>
        <h3 class=mb2>Rekap Surat Tugas Mengajar Dosen</h3>
        <h4 class=mb2>Tahun Ajar $tahun_ta $Gg</h4>
      </div>
      <div class='wadah gradasi-hijau'>
        <table id=tb_dosen class='table table-hover table-striped'>
          <thead>
            <th>No</th>
            <th>Dosen</th>
            <th>Homebase</th>
            <th>MK</th>
            <th>SKS</th>
          </thead>
          $list_dosen
        </table>
        <script>let table = new DataTable('#tb_dosen');</script>
      </div>
    </div>
  ";
} else {
  $list_dosen .= alert("Belum ada satupun dosen di prodi [$kurikulum[nama_prodi]]<hr><a href='?crud&tb=dosen'>Buat Pilihan dosen</a>", 'danger', '', false);
  $siap_assign = false;
}

echo "
  $list_dosen
  <div>
    <button class='ondev btn btn-primary' >Print</button>
    <button class='ondev btn btn-success' >Export</button>
  </div>
";

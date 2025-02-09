<?php
set_title('Penugasan Dosen');
$row_height = '20px; max-width:150px; white-space:nowrap; overflow:hidden'; // pixel

# ============================================================
# CREATE TH PRODI DAN SHIFT
# ============================================================
$s = "SELECT * FROM tb_prodi ORDER BY fakultas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rprodi = [];
while ($d = mysqli_fetch_assoc($q)) {
  $rprodi[$d['id']] = $d;
}

$th_prodi = '';
$th_shift = '';
$rprodi_shift_smt = [];
$rprodi_shift = [];
foreach ($rprodi as $id_prodi => $d) {
  // $th_prodi .= "<th colspan=2>$d[jenjang]-$d[singkatan]</th>";
  $th_prodi .= "<th colspan=2>$d[singkatan]</th>";
  foreach ($rshift as $id_shift => $arr_shift) {
    $th_shift .= "<th>$id_shift</th>";
    $rprodi_shift["$id_prodi-$id_shift"] = null;
  }
}



# ============================================================
# MAIN SELECT DOSEN
# ============================================================
$s = "SELECT a.* ,
(SELECT singkatan FROM tb_prodi WHERE id=a.id) homebase,
(
  SELECT SUM(p.sks) FROM tb_mk p 
  JOIN tb_kumk q ON p.id=q.id_mk 
  JOIN tb_st_detail r ON q.id=r.id_kumk 
  JOIN tb_st s ON r.id_st=s.id 
  WHERE s.id_ta = $ta_aktif 
  AND s.id_dosen = a.id  
  ) jumlah_sks,
(
  SELECT SUM(p.sks) FROM tb_mk p 
  JOIN tb_kumk q ON p.id=q.id_mk 
  JOIN tb_st_detail r ON q.id=r.id_kumk 
  JOIN tb_st s ON r.id_st=s.id 
  WHERE s.id_ta = $ta_sebelumnya 
  AND s.id_dosen = a.id  
  ) jumlah_sks_sebelumnya
FROM tb_dosen a WHERE 1 
ORDER BY a.nama 
-- LIMIT 10
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$total_sks = 0;
$total_sks_sebelumnya = 0;
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $total_sks += $d['jumlah_sks'];
    $total_sks_sebelumnya += $d['jumlah_sks_sebelumnya'];


    $rmk = [];
    $s2 = "SELECT 
    e.id as id_kelas, 
    e.nama as nama_kelas, 
    e.id_shift, 
    d.id as id_mk, 
    d.nama as nama_mk,
    d.sks 
    FROM tb_st a 
    JOIN tb_st_detail b ON a.id=b.id_st 
    JOIN tb_kumk c ON b.id_kumk=c.id 
    JOIN tb_mk d ON c.id_mk=d.id 
    JOIN tb_kelas e ON b.id_kelas=e.id 
    WHERE a.id_dosen = $d[id] 
    ORDER BY nama_mk
    ";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $count_mk = mysqli_num_rows($q2);
    if ($count_mk) {
      while ($d2 = mysqli_fetch_assoc($q2)) {
        $id_mk = $d2['id_mk'];
        $nama_mk = $d2['nama_mk'];
        if (!isset($rmk[$nama_mk])) {
          $rmk[$nama_mk] = [$d2]; // indexing by nama_mk (mampatkan)
        } else {
          array_push($rmk[$nama_mk], $d2);
        }
      }
    }

    $mks = '';
    $skss = '';
    $j = 0;
    foreach ($rmk as $nama_mk => $arr_mk) {
      $j++;
      $nomor = count($rmk) == 1 ? '' : "$j. ";
      $mks .= "<div style='height:$row_height'>$nomor$nama_mk</div>";
      $skss .= "<div style='height:$row_height'>" . $arr_mk[0]['sks'] . "</div>";
    }

    $counts = '';
    $tds_counts = '';
    foreach ($rprodi_shift as $id_prodi_shift => $value_null) {
      $counts = '';
      foreach ($rmk as $nama_mk => $arr_mk) {
        # ============================================================
        # PENCARIAN REALTIME COUNT ST DETAIL
        # ============================================================
        $t = explode('-', $id_prodi_shift);
        $id_prodi = $t[0];
        $id_shift = $t[1];
        $id_dosen = $d['id'];

        $s2 = "SELECT 1 FROM tb_st a 
        JOIN tb_st_detail b ON a.id=b.id_st 
        JOIN tb_kumk c ON b.id_kumk=c.id 
        JOIN tb_kurikulum d ON c.id_kurikulum=d.id 
        JOIN tb_mk e ON c.id_mk=e.id 
        WHERE a.id_dosen = $id_dosen
        AND a.id_ta = $ta_aktif 
        AND d.id_prodi = $id_prodi 
        AND b.id_shift = '$id_shift' 
        AND e.nama = '$nama_mk' 
        ";
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
        $count = mysqli_num_rows($q2);
        $sty = $count ? "class='blue bold tengah' style='background:yellow; height:$row_height'" : "class='tengah shift-$id_shift' style='height:$row_height'";
        $count = $count ? $count : '-';


        $counts .= "<div $sty>$count</div>";
      }
      $tds_counts .= "<td style='padding-left:0;padding-right:0'>$counts</td>";
    }

    $jumlah_sks = $d['jumlah_sks'];
    $jumlah_sks_sebelumnya = $d['jumlah_sks_sebelumnya'];
    $naik = $jumlah_sks - $jumlah_sks_sebelumnya;
    $jumlah_sks = $jumlah_sks ? "<a target=_blank href='?st&id_st=$ta_aktif-$d[id]'>$jumlah_sks</a>" : '-';
    $jumlah_sks_sebelumnya = $jumlah_sks_sebelumnya ? "<a target=_blank href='?st&id_st=$ta_sebelumnya-$d[id]'>$jumlah_sks_sebelumnya</a>" : '-';


    $tr .= "
      <tr>
        <td>$i</td>
        <td><div style='height:$row_height'>$d[nama]</div></td>
        <td>$d[homebase]</td>
        <td>$mks</td>
        <td>$skss</td>
        $tds_counts
        <td>$jumlah_sks</td>
        <td>$jumlah_sks_sebelumnya</td>
        <td>$naik</td>
      </tr>
    ";
  }
}

$colspan_total = 27;

$tb = $tr ? "
  <style>
    section {padding-top:15px}
    .shift-R{background: #DFFFDE}
    .shift-NR{background:rgb(255, 243, 218)}
  </style>
  <h2 class='tengah darkblue f24'>Penugasan Dosen</h2>
  <div style='position:relative; height: 70vh; overflow-y:scroll; border-bottom: solid 1px #eee; background: linear-gradient(white,#efe)'>
    <table class='table f12 table-bordered m-0'>
      <thead style='position:sticky; top:0;'>
        <tr>
          <th rowspan=2>No</th>
          <th rowspan=2>Nama</th>
          <th rowspan=2>Homebase</th>
          <th rowspan=2>MK</th>
          <th rowspan=2>SKS</th>
          $th_prodi
          <th rowspan=2>Jumlah SKS</th>
          <th rowspan=2>Smt Lalu</th>
          <th rowspan=2>Naik</th>
        </tr>
        <tr>
          $th_shift
        </tr>
      </thead>
      $tr
      <tfoot  class='gradasi-toska pt2 pb2' style='position:sticky; bottom:0'>
        <tr>
          <td colspan=$colspan_total>
            TOTAL SKS
          </td>
          <td>
            $total_sks
          </td>
          <td>
            $total_sks_sebelumnya
          </td>
          <td>
            &nbsp;
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
" : div_alert('danger', "Data dosen tidak ditemukan.");
echo "$tb";

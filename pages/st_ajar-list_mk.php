<?php
# ============================================================
# JUMLAH KELAS DI TIAP SEMESTER TIAP PRODI
# ============================================================
$rcount_kelas = [];

for ($i = 1; $i <= 8; $i++) {
  $s = "SELECT a.id ,
  (SELECT COUNT(1) FROM tb_kelas WHERE id_prodi=a.id AND semester = '$i') count_kelas
  FROM tb_prodi a";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $rcount_kelas[$i][$d['id']] = $d['count_kelas'];
  }
}


# ============================================================
# MAIN SELECT 
# ============================================================
$sql_ganjil = $is_ganjil ? " (
  a.semester = '1' 
  OR a.semester = '3'
  OR a.semester = '5'
  OR a.semester = '7'
) " : " (
  a.semester = '2'
  OR a.semester = '4'
  OR a.semester = '6'
  OR a.semester = '8'
) ";
$s = "SELECT 
a.id as id_mk,
a.nama as nama_mk,
a.sks,
a.semester,
b.id as id_kumk, 
d.id as id_prodi,
d.singkatan as prodi,
(
  SELECT COUNT(1) FROM tb_st_mk p 
  JOIN tb_st_mk_kelas q ON q.id_st_mk=p.id -- jumlah assign ke tiap kelas
  WHERE p.id_kumk=b.id -- untuk KU-MK yang ini
  AND p.id_st LIKE '$ta_aktif-%' -- persen artinya semua dosen 
  ) count_assigned 
FROM tb_mk a 
JOIN tb_kumk b ON a.id=b.id_mk
JOIN tb_kurikulum c ON b.id_kurikulum=c.id
JOIN tb_prodi d ON c.id_prodi=d.id
-- WHERE 1 
AND $sql_ganjil -- atau genap 
ORDER BY d.id, b.semester 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $last_smt = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $id_kumk = $d['id_kumk'];
    $separator = $last_smt != $d['semester'] ? '<hr>' : '';
    $checked = key_exists($id_kumk, $rkumk) ? 'checked' : '';
    $jumlah_kelas_assigned = $rkumk[$id_kumk]['jumlah_kelas'] ?? 0;
    if ($jumlah_kelas_assigned) {
      $disabled = 'disabled';
      $jumlah_kelas_info = " x $jumlah_kelas_assigned kelas";
      $blue = 'blue bold';
    } else {
      $disabled = '';
      $jumlah_kelas_info = '';
      $blue = '';
    }


    # ============================================================
    # JIKA JUMLAH KELAS SUDAH MAX MAKA DISABLED (SUDAH DIALOKASIKAN)
    # ============================================================
    $max_assigned = $rcount_kelas[$d['semester']][$d['id_prodi']];
    if (!$max_assigned) {
      $disabled = 'disabled';
      $pesan = "Belum ada kelas di prodi $d[prodi] semester $d[semester]";
      $sudah_teralokasi = "<a target=_blank href='?crud&tb=kelas&note=$pesan' onclick='return confirm(`Buat kelas di Tab baru?`)'><span class=red>$pesan</span></a>";
      $abu = 'abu';
    } elseif ($max_assigned == $d['count_assigned']) {
      $sudah_teralokasi = "<span class='green'>( sudah teralokasi $img_check )</span>";
      $disabled = 'disabled';
      $abu = 'abu';
      $abu = $blue ? $blue :  'abu miring';
    } else {
      $mk_available++;
      $disabled = ''; // masih boleh diceklis
      $sudah_teralokasi = '';
      $abu = '';
    }

    $list_mk .= "
      $separator
      <div id=div_mk__$id_kumk>
        <label class='label_mk $abu $blue '>
          <input class=check_mk type='checkbox' name='id_kumk[$id_kumk]' $checked $disabled> 
          $d[prodi]-SM$d[semester] - $d[nama_mk] - $d[sks] SKS $jumlah_kelas_info | $d[count_assigned] of $max_assigned assigned $sudah_teralokasi
        </label>
      </div>  
    ";
    $last_smt = $d['semester'];
  }

  $mk_available_info = $mk_available ? "<div class='mb1 mt2 blue bold'>Ceklis MK yang akan diberikan:</div>" : "
    <div class='mb1 mt2 red bold'>
      <a href='?st_ajar&id_kurikulum=$id_kurikulum'>$img_prev</a>
      Maaf, belum ada MK yang available | 
      <a href='?crud&tb=mk'>Manage MK</a>
    </div>
  ";

  # ============================================================
  # FINAL ECHO
  # ============================================================
  $list_mk = "

      <div id=pilih_mk class=hideita>
      $untuk_mengampu

      <div class=row id=list_mk>
        <div class='col-sm-12'>
          <div class='wadah gradasi-toska'>
            $mk_available_info
            $list_mk
          </div>
        </div>
      </div>
    </div>

  ";
} else {
  $pesan_error = alert("Belum ada satupun MK di prodi [$kurikulum[nama_prodi]] semester [$Gg]<hr><a href='?crud&tb=mk'>Buat Pilihan MK</a>", 'danger', '', false);
  $list_mk .= $pesan_error;
  $siap_assign = false;
}

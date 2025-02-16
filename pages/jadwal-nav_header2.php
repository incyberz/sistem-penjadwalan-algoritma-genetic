<?php




# ============================================================
# ARRAY KELAS PER SEMESTER PER SHIFT PADA FAKULTAS INI
# ============================================================
$rkelas = [];
$s = "SELECT
a.id, 
a.id_prodi, 
a.nama as nama_kelas,
b.singkatan,
a.semester,
a.counter 
FROM tb_kelas a 
join tb_prodi b ON a.id_prodi=b.id 
WHERE b.fakultas='$get_fakultas' 
AND a.semester = '$get_semester' 
AND a.id_shift = '$get_id_shift'
AND a.id_ta = '$ta_aktif' 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $pesan = "Belum ada Data Kelas untuk semester [$semester] kelas [$SHIFT] fakultas [$fakultas].";
  alert("$pesan | <a href='?crud&tb=kelas&note=$pesan'>Manage Kelas</a>", 'danger mt4');
  exit;
}

$nav_kelas = '';
$i = 0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $rkelas[$d['id']] = $d;
  $nav_aktif = '';
  $span_kumk_count = '';
  if ($get_id_kelas) {
    if ($get_id_kelas == $d['id']) {
      $nav_aktif = 'nav_aktif';
      if ($kumk_count) $span_kumk_count = "<span class=kumk_count>$kumk_count</span>";
    }
  } else {
    $get_id_kelas = $d['id']; // default id_kelas dengan loop pertama
    if ($i == 1) $nav_aktif = 'nav_aktif';
  }

  $nav_kelas .= "
    <div class=''>
      <a class='nav_jadwal $nav_aktif' href='?jadwal&fakultas=$get_fakultas&id_shift=$get_id_shift&semester=$get_semester&id_kelas=$d[id]'>
        <span>$d[singkatan]$d[semester]$d[counter]</span>
        $span_kumk_count
      </a>
    </div>
  ";
}


$nav_shift = '';
foreach ($rshift as $id_shift => $value) {
  if ($get_id_shift == $id_shift) {
    $nav_shift .= "<div><span class='nav_jadwal nav_aktif'>$id_shift</span></div>";
  } else {
    $nav_shift .= "<div><a class='nav_jadwal' href='?jadwal&fakultas=$get_fakultas&semester=$get_semester&id_shift=$id_shift'>$id_shift</a></div>";
  }
}

$nav_semester = '';
for ($semester = 1; $semester <= 8; $semester++) {
  if (($is_ganjil and $semester % 2 != 0) || (!$is_ganjil and $semester % 2 == 0)) {
    if ($get_semester == $semester) {
      $nav_semester .= "<div><span class='nav_jadwal nav_semester nav_aktif'>$semester</span></div>";
    } else {
      $nav_semester .= "<div><a class='nav_jadwal nav_semester' href='?jadwal&fakultas=$get_fakultas&id_shift=$get_id_shift&semester=$semester'>$semester</a></div>";
    }
  }
}

?>
<style>
  .nav_jadwal2 {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 46px;
    background: linear-gradient(#ffe, #ffa);
    padding: 10px;
    z-index: 999;
    box-shadow: 0 0 20px gray;
    /* border-top: solid 1px #eee; */
    gap: 60px;
  }

  .nav_active {
    background: darkblue;
    color: aliceblue;
    padding: 0 10px;
  }

  .nav_semester {
    border: solid 1px orange;
  }
</style>
<div class="nav_jadwal2 ">
  <div class="row">
    <div class="col-6">
      <div class="d-flex justify-content-end " style="gap: 60px">
        <div class="d-flex gap-2">
          <?= $nav_shift ?>
        </div>
        <div class="d-flex gap-2">
          <?= $nav_semester ?>
        </div>

      </div>

    </div>
    <div class="col-6">
      <div class="d-flex gap-2 ml4 pl4">
        <?= $nav_kelas ?>
      </div>

    </div>

  </div>
</div>
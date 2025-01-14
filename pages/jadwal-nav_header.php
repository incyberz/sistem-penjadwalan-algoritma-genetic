<?php
$nav_fakultas = '';
foreach ($rfakultas as $key => $value) {
  $nav_aktif = $fakultas == $key ? 'nav_aktif' : '';
  $nav_fakultas .= "<div><a class='nav_jadwal $nav_aktif' href='?jadwal&fakultas=$key'>$key</a></div>";
}

$nav_shift = '';
foreach ($rshift as $key => $value) {
  $nav_aktif = $shift == $key ? 'nav_aktif' : '';
  $nav_shift .= "<div><a class='nav_jadwal $nav_aktif' href='?jadwal&shift=$key'>Kelas $key</a></div>";
}

$nav_semester = '';
for ($i = 1; $i <= 8; $i++) {
  if (($is_ganjil and $i % 2 != 0) || (!$is_ganjil and $i % 2 == 0)) {
    $nav_aktif = $semester == $i ? 'nav_aktif' : '';
    $nav_semester .= "<div><a class='nav_jadwal $nav_aktif' href='?jadwal&semester=$i'>Semester $i</a></div>";
  }
}

$KAMPUS = strtoupper($nama_kampus);
$FAK = strtoupper($rfakultas[$fakultas]);
echo "
  <div class='tengah gradasi-toska p-3'>
    <h1>
      <span class='nav_hover btn_aksi' id=nav_fakultas__toggle>$FAK</span>
      $KAMPUS
    </h1>
    <h2>JADWAL MATA KULIAH $tahun_ta $GG</h2>
    <h3>
      <span class='nav_hover btn_aksi' id=nav_shift__toggle>KELAS $SHIFT</span>
      <span class='nav_hover btn_aksi' id=nav_semester__toggle>SEMESTER $semester</span>
       
    </h3>
  </div>
  <div class='flexy flex-center mt4 nav_hidden' id=nav_fakultas>$nav_fakultas</div>
  <div class='flexy flex-center mt4 nav_hidden' id=nav_shift>$nav_shift</div>
  <div class='flexy flex-center mt4 nav_hidden' id=nav_semester>$nav_semester</div>
";

# ============================================================
# ARRAY KELAS PER SEMESTER PER SHIFT
# ============================================================
$rkelas = [];
$s = "SELECT
a.id, 
a.id_prodi, 
a.nama as nama_kelas 
FROM tb_kelas a 
join tb_prodi b ON a.id_prodi=b.id 
WHERE b.fakultas='$fakultas' 
AND a.semester = '$semester' 
AND a.shift = '$shift'
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $pesan = "Belum ada Data Kelas untuk semester [$semester] kelas [$SHIFT] fakultas [$fakultas].";
  alert("$pesan | <a href='?crud&tb=kelas&note=$pesan'>Manage Kelas</a>", 'danger mt4');
  exit;
} else {
  echo "
    <script>
      $(function() {
        $('.nav_hidden').slideUp();
      })
    </script>
  ";
}

$nav_kelas = '';
$nav_kelas_active = 'nav_kelas_active';
$nav_kelas_pertama = null;
while ($d = mysqli_fetch_assoc($q)) {
  $rkelas[$d['id']] = $d;
  $nav_kelas_pertama = $nav_kelas_pertama ?? $d['nama_kelas'];
  $nav_kelas .= "
    <div class='nav_jadwal nav_kelas $nav_kelas_active' id=nav_kelas__$d[id]>
      <span>$d[nama_kelas]</span>
      <span id=kumk_count__$d[id] class='kumk_count'>0</span>
    </div>
  ";
  $nav_kelas_active = '';
}


echo "
  <div class='flexy flex-center mt4' id=nav_kelas>$nav_kelas</div>
";

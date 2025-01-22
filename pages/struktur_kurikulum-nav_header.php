<?php
$nav_prodi = '';
$s = "SELECT * FROM tb_prodi ORDER BY fakultas, nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $nav_aktif = $prodi == $d['id'] ? 'nav_aktif' : '';
  $nav_prodi .= "<div><a class='nav_st $nav_aktif' href='?struktur_kurikulum&id_prodi=$id_prodi&id_shift=$id_shift&id_prodi=$d[id]'>$d[singkatan]</a></div>";
}

$nav_shift = '';
foreach ($rshift as $key => $value) {
  $nav_aktif = $id_shift == $key ? 'nav_aktif' : '';
  $nav_shift .= "<div><a class='nav_st $nav_aktif' href='?struktur_kurikulum&id_prodi=$id_prodi&id_shift=$key&fakultas=$fakultas'>Kelas $key</a></div>";
}

// $nav_semester = '';
// for ($i = 1; $i <= 8; $i++) {
//   if (($is_ganjil and $i % 2 != 0) || (!$is_ganjil and $i % 2 == 0)) {
//     $nav_aktif = $semester == $i ? 'nav_aktif' : '';
//     $nav_semester .= "<div><a class='nav_st $nav_aktif' href='?jadwal&semester=$i'>Semester $i</a></div>";
//   }
// }

$nav_header = "
  <div class='tengah gradasi-toska p-3'>
    <h2>STRUKTUR KURIKULUM </h2>
    <h3>
      <span class='nav_hover btn_aksi' id=nav_prodi__toggle>$prodi[nama]</span>
      $tahun_ta $GG
    </h3>

    <h4>
      <span class='nav_hover btn_aksi' id=nav_shift__toggle>KELAS $SHIFT</span>
    </h4>
  </div>
  <div class='flexy flex-center mt4 nav_hidden' id=nav_prodi>$nav_prodi</div>
  <div class='flexy flex-center mt4 nav_hidden' id=nav_shift>$nav_shift</div>
";

echo "
  <script>
    $(function() {
      $('.nav_hidden').slideUp();
    })
  </script>
";

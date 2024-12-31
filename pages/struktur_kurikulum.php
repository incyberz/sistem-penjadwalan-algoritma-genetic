<?php
# ============================================================
# STRUKTUR KURIKULUM
# ============================================================
$id_prodi = $_GET['id_prodi'] ?? '';

$d_kur = [];
if (!$id_prodi) {
  $s = "SELECT * FROM tb_prodi ORDER BY jenjang, nama";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $list = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $list .= "<div class='bordered br5 p2 gradasi-hijau'><a href='?struktur_kurikulum&id_prodi=$d[id]'>$d[jenjang] - $d[nama]</a></div>";
  }
  echo "
    <div>Struktur Kurikulum untuk prodi:</div>
    <div class='flexy wadah mt2'>$list</div>
  ";
} else {
  $s = "SELECT 
  a.*,
  b.*,
  CONCAT(b.jenjang,'-',b.nama) as Prodi 
  FROM tb_kurikulum a 
  JOIN tb_prodi b ON a.id_prodi=b.id 
  WHERE a.id_prodi=$id_prodi";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $divs = '';
  $Prodi = '';
  while ($d = mysqli_fetch_assoc($q)) {

    $Prodi = $d['Prodi'];
    $Ganjil = $d['id_ta'] % 2 == 0 ? 'Genap' : 'Ganjil';
    $tahun = intval($d['id_ta'] / 10);

    $list_mk = "Belum ada MK <div class='f12 abu miring'>di Semester $tahun $Ganjil.</div>";
    $gradasi = 'merah';
    $buat_sk = "
      <div class='border-top mt2 pt2 kanan'>
        <a class='btn btn-sm btn-success' href='?st_ajar&id_kurikulum=$d[id]'>Buat Surat Tugas</a>
      </div>
    ";

    # ============================================================
    # CEK MK
    # ============================================================
    $jumlah_semester = $d['jumlah_semester'];

    $divs .= "
      <div class='col-md-6'>
        <div class='wadah gradasi-$gradasi'>
          $list_mk
          $buat_sk
        </div>
      </div>
    ";
  }

  set_h2('Struktur Kurikulum ', $Prodi);

  echo "
  <div class=row>
    $divs
  </div>
  ";
}

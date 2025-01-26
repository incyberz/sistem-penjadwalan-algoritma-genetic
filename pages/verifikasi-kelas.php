<?php
if ($get_id) {
  get_cookies();

  $s = "SELECT 
  a.id,
  a.id_prodi,
  a.label,
  a.nama,
  a.semester,
  a.id_kosma,
  a.whatsapp_kosma,
  a.id_dosen_wali,
  b.singkatan as prodi,
  (SELECT nama FROM tb_dosen WHERE id=a.id_dosen_wali) nama_dosen_wali, 
  (SELECT nama FROM tb_mhs WHERE id=a.id_kosma) nama_kosma, 
  (SELECT COUNT(1) FROM tb_peserta_kelas WHERE id_kelas=a.id) count_peserta 
  FROM tb_kelas a 
  JOIN tb_prodi b ON a.id_prodi=b.id 
  WHERE a.id='$get_id'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $kelas = mysqli_fetch_assoc($q);
  $angkatan = $tahun_ta - intval(($kelas['semester'] - 1) / 2);

  include 'verifikasi-kelas-processors.php';

  echo "<h3>Persyaratan untuk Kelas <b class=darkblue>$kelas[nama]</b></h3>";

  if ($syarat) {
    $li = '';
    $btn_save = "<div><button class='btn btn-primary'>Save</button></div>";
    foreach ($syarat['syarat'] as $item => $desc) {

      $input_syarat = null;
      $status_syarat = "<b>$item</b>, $desc";

      $item_file = strtolower(str_replace(' ', '_', $item));
      $file = "pages/verifikasi-kelas-$item_file.php";
      if (file_exists($file)) include $file; // input syarat akan direplace

      $li .= "
        <li>
          <div>$status_syarat</div>
          <div>$input_syarat</div>
        </li>
      ";
    }
    echo "
    <p>Agar Status $syarat[title] Terverifikasi harus memenuhi persyaratan:</p>
    <ol>$li</ol>  
  ";
  }
} else { // belum ada id_kelas

  $s = "SELECT 
  a.id,
  b.fakultas,
  b.singkatan as prodi,
  a.nama as kelas,
  a.label,
  a.kapasitas,
  a.semester as smt,
  a.id_shift as shift,
  a.counter as rombel,
  a.status
  
  FROM tb_kelas a 
  JOIN tb_prodi b ON a.id_prodi=b.id 
  WHERE id_ta=$ta_aktif";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  if (mysqli_num_rows($q)) {
    $i = 0;
    $th = '';
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $td = '';
      $td .= "<td>$i</td>";
      foreach ($d as $key => $value) {
        if (
          $key == 'ids'
          || $key == 'created_at'
        ) continue;
        if ($i == 1) {
          $kolom = key2kolom($key);
          $th .= "<th>$kolom</th>";
        }

        if ($key == 'rombel') {
          $value = $value ? $value : '-';
        } elseif ($key == 'status') {
          $value = $value ? $value : '<i class="f12 red bold">unverified</i>';
        } else {
        }
        $td .= "<td>$value</td>";
      }
      $tr .= "
        <tr>
          $td
          <td>
            <a class='btn btn-primary' href='?verifikasi&tb=kelas&id=$d[id]'>Verifikasi</button>
          </td>
        </tr>
      ";
    }
  }

  $tb = $tr ? "
    <p class='tengah blue'>Silahkan Search (opsional) lalu klik Verifikasi untuk memulai proses!</p>
    <table class=table id=tb_list_kelas>
      <thead>
        <th>No</th>
        $th
        <th>Aksi</th>
      </thead>
      $tr
    </table>
    <script>let table = new DataTable('#tb_list_kelas');</script>
  " : div_alert('danger', "Data kelas tidak ditemukan.");
  echo "$tb";
} // end belum ada id_kelas

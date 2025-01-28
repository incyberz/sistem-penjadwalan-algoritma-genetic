<?php
if ($get_id) {
  get_cookies();

  $s = "SELECT 
  a.id,
  a.id_prodi,
  a.id_shift,
  a.label,
  a.nama,
  a.semester,
  a.status,
  a.id_kosma,
  (
    SELECT o.whatsapp FROM tb_user o
    JOIN tb_mhs p ON o.id=p.id_user
    JOIN tb_kelas q ON p.id=q.id_kosma
    WHERE q.id=a.id) whatsapp_kosma,
  a.wa_grup,
  b.singkatan as prodi,
  b.status as status_prodi,
  (SELECT nama FROM tb_mhs WHERE id=a.id_kosma) nama_kosma, 
  (SELECT COUNT(1) FROM tb_peserta_kelas WHERE id_kelas=a.id) count_peserta 
  FROM tb_kelas a 
  JOIN tb_prodi b ON a.id_prodi=b.id 
  WHERE a.id='$get_id'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $kelas = mysqli_fetch_assoc($q);
  if ($kelas['status'] == 100) {
    alert("Selamat! Kelas ini sudah terverifikasi - status: $kelas[status] | <a href='?detail&tb=$get_tb&id=$kelas[id]'>Lihat Detail</a>", 'info tengah blue f20');
  } else {

    $angkatan = $tahun_ta - intval(($kelas['semester'] - 1) / 2);

    include 'verifikasi-kelas-processors.php';

    echo "<h3>Persyaratan untuk Kelas <b class=darkblue>$kelas[nama]</b></h3>";

    if ($syarat) {
      $li = '';
      $btn_save = "<div><button class='btn btn-primary'>Save</button></div>";
      $input_syarat_before = null;
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
        $input_syarat_before = $input_syarat;
      }
      echo "
        <p>Agar Status $syarat[title] Terverifikasi harus memenuhi persyaratan:</p>
        <ol>$li</ol>  
      ";

      # ============================================================
      # ALL VERIFIED
      # ============================================================
      if (
        $kelas['label']
        && $kelas['count_peserta']
        && $kelas['id_kosma']
        && $kelas['wa_grup']
        && $kelas['status_prodi']
      ) {
        if ($kelas['status'] == 100) {
          alert("Selamat! Kelas ini sudah terverifikasi.", 'success blue f24');
        } else {
          echo "
            <form method=post class='wadah gradasi-hijau'>
              <h4>Approve Verifikasi</h4>
              <p class=petunjuk>Semua Kelengkapan Verifikasi sudah lengkap, Anda boleh Approve atau Manage Kembali data-data diatas. $img_help</p>
              <button class='btn btn-primary' name=btn_approve>Approve</button>
            </form>
          ";
        }
      }
    } // end ada data array syarat
  } // end status kelas belum 100
} else { // belum ada id_kelas

  # ============================================================
  # SELECT LIST KELAS
  # ============================================================
  $s = "SELECT 
  a.id,
  b.fakultas,
  b.singkatan as prodi,
  a.nama as kelas,
  a.label,
  (SELECT COUNT(1) FROM tb_peserta_kelas WHERE id_kelas=a.id) peserta,
  -- a.kapasitas,
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
      $gradasi = '';
      $td .= "<td>$i</td>";
      foreach ($d as $key => $value) {
        if (
          $key == 'id'
          || $key == 'prodi'
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
          $link_verif = $value == 100 ? "
            <a class='btn btn-success btn-sm' href='?detail&tb=kelas&id=$d[id]'>Detail</button>
          " : "
            <a class='btn btn-primary btn-sm' href='?verifikasi&tb=kelas&id=$d[id]'>Verifikasi</button>
          ";
        } elseif ($key == 'peserta') {
          $gradasi = $value ? '' : 'merah';
          $value = $value ? $value : '<div class="f12 red bold ">0</div>';
        } else {
        }
        $td .= "<td>$value</td>";
      }
      $tr .= "
        <tr class='gradasi-$gradasi'>
          $td
          <td>
            $link_verif
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

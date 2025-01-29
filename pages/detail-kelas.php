<?php

$s = "SELECT 
a.id, 
a.label, 
a.semester, 
a.id_shift as shift, 
a.counter as rombel,
a.status,
a.id_kosma,
a.wa_grup  
FROM tb_kelas a 
WHERE id='$get_id'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $kelas = mysqli_fetch_assoc($q);
  foreach ($kelas as $key => $value) {
    $kolom = strtoupper(key2kolom($key));
    if ($key == 'id' || $key == 'id_kosma' || $key == 'wa_grup') {
      continue;
    } elseif ($key == 'label') {
      $value = $value ? $value : '<i class="f12 abu">( default )</i>';
    } elseif ($key == 'rombel') {
      $value = $value ? $value : '<i class="f12 abu">( none, satu rombel )</i>';
    } elseif ($key == 'status') {
      if ($value == 100) {
        $value = "<b class=green>100</b> <i>verified</i> $img_check";
      } elseif ($value > 0) {
        $value = "<b class=brown>$value $img_warning</b>";
      } else {
        $value = $unverified;
      }
    }
    $li .= "
      <li>
        <i class='bold f12'>$kolom:</i>
        <span id=$key class='darkblue'>$value</span>
      </li>
    ";
  }

  if ($kelas['status'] == 100) {
    $info_verified = "
      <form method=post>
        <p class=petunjuk>Jika ingin mengubah data kosma, dosen wali, atau data system lainnya silahkan Reset Status kemudian lakukan Verifikasi $get_tb kembali $img_help</p>
        <button class='btn btn-warning w-100 proper' name=btn_reset_status_kelas>Reset Status $get_tb</button>  
      </form>
    ";
  } else {
    $info_verified = "
      <p class=petunjuk>Lakukan Verifikasi Kelas agar semua fitur Manage Kelas dapat diakses $img_help</p>
      <a class='btn btn-primary w-100 proper' href='?verifikasi&tb=kelas&id=$kelas[id]'>Verifikasi $get_tb</a>  
    ";
  }
} else {
  die(alert("Data kelas tidak ditemukan."));
}

$properti = $li ? "<ul class=''>$li</ul>" : div_alert('danger', "Data kelas tidak ditemukan.");

# ============================================================
# ANGGOTA DAN KOSMA
# ============================================================
$s = "SELECT 
b.id as id_peserta,
b.nama as nama_peserta,
(SELECT whatsapp FROM tb_user WHERE id=b.id_user) whatsapp_peserta
FROM tb_peserta_kelas a 
JOIN tb_mhs b ON a.id_mhs = b.id
WHERE a.id_kelas=$kelas[id]";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_peserta = mysqli_num_rows($q);
$list_peserta = '';
$nama_kosma = $null;
$whatsapp_kosma = '';
$i = 0;
if ($jumlah_peserta) {
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $unique = '';
    if ($d['id_peserta'] == $kelas['id_kosma']) {
      $unique = "<span onclick='alert(`Mhs ini sebagai Kosma (Ketua Organisasi Mahasiswa; atau Ketua Murid)\n\nKosma mempunyai hak khusus untuk [ approve ] pada Fitur [ Barter Jadwal ].`)'>$img_unique</span>";
      $nama_kosma = $d['nama_peserta'];
      $whatsapp_kosma = $d['whatsapp_peserta'];
    }
    require_once 'includes/Waktu.php';
    $text_wa = urlencode("Hallo $d[nama_peserta], Selamat $Waktu! $text_wa_from");
    $whatsapp = $d['whatsapp_peserta'] ? "<a href='$https_api_wa?phone=$d[whatsapp_peserta]&text=$text_wa'>$img_wa</a>" : "<i onclick='alert(`Belum ada whatsapp untuk mhs ini.`)'>$img_wa_disabled</i>";
    $list_peserta .= "
      <tr>
        <td>$i</td>
        <td><a target=_blank href='?detail&tb=mhs&id=$d[id_peserta]'>$d[nama_peserta]</a> $unique</td>
        <td>$whatsapp</td>
      </tr>
    ";
  } // end while

  $info_peserta = "
    <div class='mb2'>
      <b>Peserta Kelas: $jumlah_peserta Mhs</b>
    </div>
    <table class='table'>
      <thead>
        <th>No</th>
        <th>Nama</th>
        <th>Whatsapp</th>
      </thead>
      $list_peserta
    </table>
    <a class='btn btn-success w-100 mb2' href='?daftar_hadir&id_kelas=$kelas[id]'>Daftar Hadir</a>
  ";
} else {
  $info_peserta = div_alert('danger', 'Belum ada info peserta untuk kelas ini.');
}


# ============================================================
# WA GRUP
# ============================================================
$info_wa_grup = $kelas['wa_grup'] ? "
  <div class='border-top border-bottom py-2 my-2'>
    <a href='$kelas[wa_grup]' target=_blank>$kelas[wa_grup] $img_wa</a>
  </div>
" : div_alert('danger', 'Belum ada data WA Grup.');



# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <div class='row'>
    <div class='col-4'>
      <div class='wadah gradasi-toska'>
        <h3 class=proper>Properti $get_tb <i class='consolas f14 abu'>(Fixed)</i></h3>
        $properti
        $info_verified
      </div>
    </div>
    <div class='col-4'>
      <div class='wadah gradasi-toska'>
        <h3>Peserta dan Kosma</h3>
        <div class='border-top border-bottom pt2 pb2'>
          <b>Kosma:</b> <span>$nama_kosma</span> $img_unique 
        </div>
        $info_peserta
        <p class=petunjuk>Manage Peserta dilakukan saat Verifikasi $get_tb $img_help</p>

      </div>
    </div>
    <div class='col-4'>
      <div class='wadah gradasi-toska'>
        <h3>Grup Whatsapp</h3>
        $info_wa_grup
        <p class=petunjuk>Manage Grup Whatsapp dilakukan saat Verifikasi $get_tb $img_help</p>  
      </div>
    </div>
  </div>
";

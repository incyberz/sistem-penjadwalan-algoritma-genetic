<?php
include 'includes/whatsapp_keyup.php';

# ============================================================
# PROPERTI MHS
# ============================================================
$s = "SELECT 
a.id, 
a.id as id_mhs, 
c.nama as kelas, 
b.singkatan as prodi, 
a.nim, 
a.nama, 
a.angkatan, 
a.whatsapp, 
(SELECT id FROM tb_user WHERE id=a.id_user) id_user, 
(SELECT whatsapp FROM tb_user WHERE id=a.id_user) whatsapp, 
(SELECT username FROM tb_user WHERE id=a.id_user) username, 
(SELECT image FROM tb_user WHERE id=a.id_user) image, 
(
  SELECT p.nama FROM tb_kelas p 
  JOIN tb_peserta_kelas q ON p.id=q.id_kelas 
  WHERE q.id_mhs=a.id 
  AND p.id_ta = $ta_aktif) kelas, 
(
  SELECT p.id_dosen_wali FROM tb_kelas p 
  JOIN tb_peserta_kelas q ON p.id=q.id_kelas 
  WHERE q.id_mhs=a.id 
  AND p.id_ta = $ta_aktif) id_dosen_wali, 
a.status 
FROM tb_mhs a 
JOIN tb_prodi b ON a.id_prodi=b.id 
JOIN tb_shift c ON a.id_shift=c.id 
WHERE a.id='$get_id'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
$image_mhs = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $mhs = mysqli_fetch_assoc($q);
  foreach ($mhs as $key => $value) {
    $kolom = strtoupper(key2kolom($key));
    if (
      $key == 'id'
      || $key == 'whatsapp'
      || $key == 'image'
      || $key == 'kelas'
      || $key == 'id_user'
      || $key == 'id_dosen_wali'
    ) {
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

  if ($mhs['status'] == 100) {
    $info_verified = "
      <form method=post>
        <p class=petunjuk>Jika ingin mengubah data kosma, dosen wali, atau data system lainnya silahkan Reset Status kemudian lakukan Verifikasi $get_tb kembali $img_help</p>
        <button class='btn btn-warning w-100 proper' name=btn_reset_status_mhs>Reset Status $get_tb</button>  
      </form>
    ";
  } else {
    $info_verified = "
      <p class=petunjuk>Lakukan Verifikasi Kelas agar semua fitur Manage Kelas dapat diakses $img_help</p>
      <a class='btn btn-primary w-100 proper' href='?verifikasi&tb=mhs&id=$mhs[id]'>Verifikasi $get_tb</a>  
    ";
  }
} else {
  die(alert("Data mhs tidak ditemukan."));
}

$properti = $li ? "<ul class=''>$li</ul>" : div_alert('danger', "Data mhs tidak ditemukan.");
$username = $mhs['username'] ?? strtolower(str_replace(' ', '', $mhs['nama']));



# ============================================================
# DOSEN WALI
# ============================================================
$info_dosen_wali = div_alert('danger', 'Belum ada info dosen wali.');
if ($mhs['id_dosen_wali']) {
  $id_dosen_wali = $mhs['id_dosen_wali'];
  include 'info_dosen_wali.php';
}

$form_upload = !$mhs['id_user'] ? div_alert('warning', "Belum bisa upload image.") : "
  <form method=post enctype='multipart/form-data'>
    <div class=row>
      <div class=col-8>
        <input type=file id=image name=image required accept='.jpg,.jpeg' class='form-control mb2' >
      </div>
      <div class=col-4>
        <button class='btn btn-primary w-100' name=btn_upload_image_mhs>Upload</button>
      </div>
    </div>
  </form>
  <p class='petunjuk mt2'>Upload gambar dengan ekstensi JPG $img_help</p>
";

if ($mhs['image']) {
  $form_upload = "
    <div class=py-2>
      <img src='assets/img/mhs/$mhs[image]' class='image image_mhs'>
    </div>
    <form method=post>
      <input name=image_file_name value='$mhs[image]' type=hidden />
      <button class='btn btn-danger btn-sm' name=btn_ganti_image value='$mhs[id]-$mhs[id_user]' onclick='return confirm(`Hapus dan Ganti Image ini?`)'>Hapus dan Ganti Image</button>
    </form>
  ";
}

# ============================================================
# DATA KELAS
# ============================================================
if ($mhs['kelas']) {
  $data_kelas = "<b>Kelas:</b> $mhs[kelas]";
} else {
  $data_kelas = div_alert('danger', 'No data kelas akademik');
}

$script_whatsapp = whatsapp_keyup('whatsapp');

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <div class='row'>
    <div class='col-4'>
      <div class='wadah gradasi-toska'>
        <h3 class=proper>Properti $get_tb</h3>
        $properti
        $info_verified
      </div>
    </div>
    <div class='col-4'>
      <div class='wadah gradasi-toska'>
        <h3>Username dan Whatsapp</h3>
        <form method=post>
          <div class=row>
            <div class=col-8>
              <div class='f12 abu miring'>Username atau NIM</div>
              <input id=username name=username required minlength=6 maxlength=20 class='form-control mb2' value='$username'>
              <div class='f12 abu miring'>Whatsapp</div>
              <input id=whatsapp name=whatsapp required minlength=10 maxlength=14 class='form-control mb2' value='$mhs[whatsapp]' placeholder='628...'>
              $script_whatsapp
            </div>
            <div class='col-4'>
              <div class='h-100' style='position:relative'>
                <div style='position:absolute; bottom:8px;left:0;right:0'>
                  <button class='btn btn-primary w-100'>Save</button>
                </div>
              </div>
            </div>
          </div>
        </form>
        <p class=petunjuk>Whatsapp untuk fitur Whatsapp Gateway $img_help</p>
        <hr>

        <h3>Image Profil</h3>
        $form_upload
      </div>
    </div>
    <div class='col-4'>
      <div class='wadah gradasi-toska'>
        <h3>Data Akademik</h3>
        $data_kelas
        <p class='petunjuk mt2'>Pada TA Aktif $tahun_ta $Gg $img_help</p>  
        <hr>
        <h3>Dosen Wali</h3>
        $info_dosen_wali
        <p class=petunjuk>Manage Dosen Wali dilakukan saat Verifikasi Kelas $img_help</p>  
      </div>
    </div>
  </div>
";

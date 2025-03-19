<h2 class="mb-4">Dashboard Bimbingan Mahasiswa</h2>
<style>
  .image-pembimbing {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #007bff;
  }
</style>
<?php
$allowed_ekstensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
$allowed_ekstensions_str = implode(', ', $allowed_ekstensions);
$path = "uploads/bimbingan/$id_mhs";

$s = "SELECT 
a.id as id_peserta_bimbingan,
c.id as id_pembimbing,
c.nama as pembimbing,
c.nidn,
d.whatsapp as whatsapp_pembimbing,
d.image as image_pembimbing 


FROM tb_peserta_bimbingan a 
JOIN tb_bimbingan b ON b.id=a.id_bimbingan 
JOIN tb_dosen c ON c.id=b.id_dosen 
JOIN tb_user d ON c.id_user=d.id 
WHERE a.id_mhs = $id_mhs";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  div_alert('danger', "Kamu belum punya data bimbingan.");
}
$bimbingan = mysqli_fetch_assoc($q);


# ============================================================
# COUNT LAPORAN
# ============================================================
$s = "SELECT *, a.id as id_laporan  
FROM tb_laporan_bimbingan a 
JOIN tb_status_laporan_bimbingan b ON b.id=a.id_status
WHERE a.id_peserta_bimbingan = '$bimbingan[id_peserta_bimbingan]'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$total_laporan = mysqli_num_rows($q);
$laporan_disetujui = 0;
$perlu_revisi = 0;
if (!$total_laporan) {
  $tr = "
    <tr>
      <td colspan=100%>
        <div class='alert alert-danger'>
          Kamu belum pernah bimbingan. Segera kirim laporan kamu!
        </div>
      </td>
    </tr>
  ";
} else {
  $tr = '';
  while ($d = mysqli_fetch_assoc($q)) {
    if ($d['id_status'] >= 5) { // disetujui || disahkan
      $laporan_disetujui++;
    } elseif ($d['id_status'] == 3) {
      $perlu_revisi++;
    }

    $tgl = date('d M Y', strtotime($d['tanggal']));
    $nama_file = str_replace("$id_mhs-", '', substr($d['file'], 0, -18));

    $reply = "<i class='f12 abu'>menunggu reply...</i>";
    # ============================================================
    # REPLY DARI DOSEN
    # ============================================================
    if ($d['reply_date']) {
      $at = date('d M, Y, H:i', strtotime($d['reply_date']));

      $link_file = !$d['reply_file'] ? '' : "<a target=_blank href='$path/$d[reply_file]'>$img_docx <span class=f12>$nama_file-replied</span></a>";

      $reply = "
        $link_file
        <div class=''>$d[komentar]</div>
        <div class='f10 abu'>at $at</div>
      ";
    }

    # ============================================================
    # MASIH BISA HAPUS JIKA BELUM ADA REPLY
    # ============================================================
    $form_hapus = '';
    if ($d['id_status'] < 3) {
      $form_hapus = "
        <form method=post class=d-inline>
          <button class='btn-transparan' onclick='return confirm(`Yakin hapus laporan ini?`)' name=btn_delete_laporan value='$bimbingan[id_peserta_bimbingan]-$d[id_laporan]'>$img_delete</button>
        </form>
      ";
    }




    $tr .= "
      <tr>
        <td>$tgl</td>
        <td>
          <a target=_blank href='$path/$d[file]'>
            $img_docx 
            <span class='f12'>$nama_file</span>
          </a>
          <div class='f12 abu'>$d[catatan]</div>
        </td>
        <td>
          $form_hapus
          $reply
        </td>
        <td>
          <span class='badge bg-$d[bg]'>$d[status]</span>
        </td>
      </tr>
    ";
  }
}




# ============================================================
# INFO PEMBIMBING
# ============================================================
$pesan = "Selamat $waktu, Bapak/Ibu $bimbingan[pembimbing]\n\n";
include 'hubungi.php';

$info_pembimbing = "
<div class='card mb-4'>
  <div class='card-header bg-primary text-white'>Dosen Pembimbing</div>
  <div class='card-body d-flex align-items-center'>
    <img src='assets/img/dosen/$bimbingan[image_pembimbing]' alt='Foto Dosen' class='image-pembimbing me-3'>
    <div>
      <h5 class='mb-1'>$bimbingan[pembimbing]</h5>
      <p class='mb-0 text-muted'>hubungi $hubungi</p>
    </div>
  </div>
</div>
";

$riwayat_laporan = "
  <div class='card mt-4'>
    <div class='card-header bg-primary text-white'>Riwayat Laporan Bimbingan</div>
    <div class='card-body'>
      <table class='table table-striped'>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Laporan</th>
            <th>Reply</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id='daftarLaporan'>
          $tr
        </tbody>
      </table>
    </div>
  </div>
";

$statistik = "
  <div class='row'>
    <div class='col-md-4'>
      <div class='card text-white bg-primary mb-3'>
        <div class='card-body'>
          <h5 class='card-title'>Total Laporan</h5>
          <p class='card-text' id='total_laporan'>$total_laporan</p>
        </div>
      </div>
    </div>
    <div class='col-md-4'>
      <div class='card text-white bg-success mb-3'>
        <div class='card-body'>
          <h5 class='card-title'>Laporan Disetujui</h5>
          <p class='card-text' id='laporan_disetujui'>$laporan_disetujui</p>
        </div>
      </div>
    </div>
    <div class='col-md-4'>
      <div class='card text-white bg-danger mb-3'>
        <div class='card-body'>
          <h5 class='card-title'>Perlu Revisi</h5>
          <p class='card-text' id='perlu_revisi'>$perlu_revisi</p>
        </div>
      </div>
    </div>
  </div>
";

$radio_bab = '';
for ($i = 1; $i <= 5; $i++) {
  $radio_bab .= "
    <div>
      <label>
        <input type=radio required name=bab value=$i> Bab $i
      </label>
    </div>
  ";
}
$radio_bab .= "
  <div>
    <label>
      <input type=radio required name=bab value=6> Lampiran
    </label>
  </div>
";


$form_upload = "
  <div class='card mt-4'>
    <div class='card-header bg-success text-white'>Kirim Laporan Bimbingan</div>
    <div class='card-body'>
      <form method=post enctype='multipart/form-data' id='formLaporan'>
        <div class='mb-3'>
          <label for='fileLaporan' class='form-label'>Unggah Laporan</label>
          <input type='file' class='form-control' name='fileLaporan' required accept=.docx>
        </div>
        <div class='mb-3'>
          <label for='catatan' class='form-label'>Catatan untuk Dosen</label>
          <textarea class='form-control' name='catatan' rows='3' required></textarea>
        </div>
        <div class='flexy mb2'>
          $radio_bab
        </div>
        <button type='submit' class='btn btn-primary' name=btn_kirim_laporan value='$bimbingan[id_peserta_bimbingan]'>Kirim Laporan</button>
      </form>
    </div>
  </div>
";

echo "
  $statistik 
  $info_pembimbing
  $riwayat_laporan
  $form_upload
";
?>
<!-- Profil Dosen Pembimbing -->


<!-- Progress Bimbingan -->



<!-- Statistik Progres Bimbingan -->


<!-- Daftar Laporan Bimbingan -->


<!-- Form Upload Laporan Baru -->
<h2 class="mb-4">Riwayat Laporan Bimbingan</h2>
<?php
// $allowed_ekstensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
// $allowed_ekstensions_str = implode(', ', $allowed_ekstensions);
$get_id_mhs = $_GET['id_mhs'] ?? die(erid('id_mhs'));
$path = "uploads/bimbingan/$get_id_mhs";

# ============================================================
# DATA BIMBINGAN
# ============================================================
$s = "SELECT 
a.id as id_peserta_bimbingan,
c.id as id_pembimbing,
c.nama as pembimbing,
c.nidn,
d.whatsapp as whatsapp_pembimbing,
d.image as image_pembimbing,
(
  SELECT p.whatsapp FROM tb_user p 
  JOIN tb_mhs q ON q.id_user=p.id 
  WHERE q.id=a.id_mhs 
  ) whatsapp_peserta


FROM tb_peserta_bimbingan a 
JOIN tb_bimbingan b ON b.id=a.id_bimbingan 
JOIN tb_dosen c ON c.id=b.id_dosen 
JOIN tb_user d ON c.id_user=d.id 
WHERE a.id_mhs = $get_id_mhs -- TARGET MHS
AND c.id = $id_dosen -- SAYA SENDIRI
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  die(div_alert('danger', "Data Bimbingan tidak ditemukan."));
}
$bimbingan = mysqli_fetch_assoc($q);


# ============================================================
# COUNT LAPORAN
# ============================================================
$s = "SELECT 
a.id as id_laporan,
a.*,
b.*  
FROM tb_laporan_bimbingan a 
JOIN tb_status_laporan_bimbingan b ON b.id=a.id_status
WHERE a.id_peserta_bimbingan = '$bimbingan[id_peserta_bimbingan]'
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$total_laporan = mysqli_num_rows($q);
$laporan_disetujui = 0;
$perlu_revisi = 0;
$perlu_review = 0;
$tr = '';
if (!mysqli_num_rows($q)) {
  $hubungi = '';
  if ($bimbingan['whatsapp_peserta']) {
    $pesan = "Segera Bimbingan!";
    $phone = $bimbingan['whatsapp_peserta'];
    include 'hubungi.php';
  }
  $tr = "
    <tr>
      <td colspan=100%>
        <div class='alert alert-danger'>belum ada riwayat laporan, silahkan hubungi mhs nya untuk segera bimbingan.  $hubungi</div>
      </td>
    </tr>
  ";
} else {
  while ($d = mysqli_fetch_assoc($q)) {
    $id_status = $d['id_status'];
    $nama_file = str_replace("$get_id_mhs-", '', substr($d['file'], 0, -18));
    if ($id_status >= 4) { // revised || disetujui || disahkan
      $laporan_disetujui++;
    } elseif ($id_status == 2) {
      $perlu_review++;
    } elseif ($id_status == 3) {
      $perlu_revisi++;
    }

    $tgl = date('d M', strtotime($d['tanggal']));

    // bg exception untuk dosen
    if ($role == 'DSN') {
      $d['status'] = $d['status4dosen'] ?? $d['status'];
      $d['bg'] = $d['bg4dosen'] ?? $d['bg'];
      $d['aksi'] = $d['aksi4dosen'] ?? $d['aksi'];
      $d['bg_aksi'] = $d['bg_aksi4dosen'] ?? $d['bg_aksi'];
    }

    # ============================================================
    # CEK EXISTS FILE DAN REPLY FILE
    # ============================================================
    $file = $d['file'] ?? 'file is null.';
    if ($file and file_exists("$path/$file")) {
      $file = "<a target=_blank href='$path/$d[file]'>$img_docx <span class=f12>$nama_file</span></a>";
    } else {
      $file = "<b class=red>file hilang.</b>";
    }

    if ($id_status == 2) {
      $reply = "
        <form method=post enctype=multipart/form-data class='card'>
          <div class='card-header bg-primary text-white f10 '>Form Reply Bimbingan</div>
          <div class='card-body'>
            <div class='f12 mb1'>Komentar Anda</div>
            <textarea class='form-control mb2' placeholder='Komentar Anda...' name=komentar required></textarea>

            <div class='row mb2'>
              <label class='d-block col-6 hover pointer'>
                <input type=radio class=radio_reply name=id_status_reply value=3> Mhs Perlu Revisi (3)
              </label> 

              <label class='d-block col-6 hover pointer'>
                <input type=radio class=radio_reply name=id_status_reply value=5> Laporan Anda Setujui (5)
              </label> 
            </div>

            <div id=blok_reply_file class=hideit>
            <div class='mt4 f12 mb1'>File Bimbingan (yang sudah Anda komentari)</div>
            <input class='form-control mb2' type=file name=reply_file id=reply_file required accept=.docx>
            </div>

            <button class='btn btn-primary w-100' name=btn_reply_bimbingan value='$d[id_peserta_bimbingan]-$d[id_laporan]'>Reply Bimbingan</button>
          </div>
        </form>
      ";
    } else {
      $reply = "$d[komentar] ";
      # ============================================================
      # REPLY DARI DOSEN
      # ============================================================
      if ($d['reply_date']) {
        $at = date('d M, Y, H:i', strtotime($d['reply_date']));

        $reply = "
        <a target=_blank href='$path/$d[reply_file]'>$img_docx <span class=f12>$nama_file-replied</span></a>
        <div class=''>$d[komentar]</div>
        <div class='f10 abu'>at $at</div>
      ";
      }
    }

    # ============================================================
    # FORM HAPUS
    # ============================================================
    $form_hapus = '';
    if ($role == 'DSN') {
      $form_hapus = "
        <form method=post class=d-inline>
          <button class='btn-transparan' onclick='return confirm(`Yakin hapus laporan ini?`)' name=btn_delete_laporan value='$bimbingan[id_peserta_bimbingan]-$d[id_laporan]'>$img_delete</button>
        </form>
      ";
    }


    # ============================================================
    # FINAL TR
    # ============================================================
    $tr .= "
      <tr>
        <td>$form_hapus$tgl</td>
        <td>
          <span class='badge bg-$d[bg]'>$d[id_status] - $d[status]</span>
        </td>
        <td>
          $file
          <div class='mt1 f12 abu'>$d[catatan]</div>
        </td>
        <td>$reply</td>
      </tr>
    ";
  }
}




# ============================================================
# RIWAYAT LAPORAN THIS GET MHS
# ============================================================
$Reply = $role == 'DSN' ? 'Reply Anda' : 'Komentar Dosen';
$riwayat_laporan = "
  <div class='card mt-4'>
    <div class='card-header bg-primary text-white'>Riwayat Laporan Bimbingan</div>
    <div class='card-body'>
      <table class='table table-striped'>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Status</th>
            <th>File dan Catatan</th>
            <th>$Reply</th>
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
    <div class='col-md-2'>
      <div class='card text-white bg-warning mb-3'>
        <div class='card-body'>
          <h5 class='card-title'>Perlu Revisi</h5>
          <p class='card-text' id='perlu_revisi'>$perlu_revisi</p>
        </div>
      </div>
    </div>
    <div class='col-md-2'>
      <div class='card text-white bg-danger mb-3'>
        <div class='card-body'>
          <h5 class='card-title'>Perlu Review</h5>
          <p class='card-text' id='perlu_revisi'>$perlu_review</p>
        </div>
      </div>
    </div>
  </div>
";


set_title("Riwayat Laporan");
echo "
  $statistik 
  $riwayat_laporan
";


?>
<script>
  $(function() {
    $('.radio_reply').click(function() {
      let id_status = parseInt($(this).val());
      if (id_status == 3) { // perlu revisi
        $('#blok_reply_file').slideDown();
        $('#reply_file').prop('required', true);
      } else {
        $('#blok_reply_file').slideUp();
        $('#reply_file').prop('required', false);
      }
    })
  })
</script>
<?php
petugas_only();
include '../includes/eta.php';
// include '../includes/key2kolom.php';
// include 'tahun_pmb.php';
// include 'gelombang_aktif.php';


// include 'info_hari_ini.php';
include 'petugas-dashboard-styles.php'; // for nav
include 'berkas-process.php'; // for nav
set_title('Berkas PMB');

$get_status = $_GET['status'] ?? 'all';
$get_username = $_GET['username'] ?? '';
$get_jenis_berkas = $_GET['jenis_berkas'] ?? '';

$filtered_info = $get_username ? "<div class='f14 '>Filtered by username: <span class=darkblue>$get_username</span> <a class='btn btn-sm btn-info ml4' href=?berkas>Clear</a></div>" : '';
$filtered_info = $get_jenis_berkas ? "<div class='f14 '>Filtered by jenis_berkas: <span class=darkblue>$get_jenis_berkas</span> <a class='btn btn-sm btn-info ml4' href=?berkas>Clear</a></div>" : $filtered_info;

$sql_username = $get_username ? "b.username = '$get_username'" : '1';
$sql_jenis_berkas = $get_jenis_berkas ? "a.jenis_berkas = '$get_jenis_berkas'" : '1';

# ============================================================
# ARRAY STATUS BERKAS
# ============================================================
$rstatus_berkas = [
  '1' => [
    'title' => 'Verified',
    'satuan' => 'files',
    'href' => '?berkas&status=1',
    'bg' => 'bg-success',
    'kondisi' => 'a.status=1',
  ],
  '-1' => [
    'title' => 'Rejected',
    'satuan' => 'files',
    'href' => '?berkas&status=-1',
    'bg' => 'bg-warning',
    'kondisi' => 'a.status=-1',
  ],
  'null' => [
    'title' => 'Perlu Review',
    'satuan' => 'files',
    'href' => '?berkas&status=null',
    'bg' => 'bg-danger',
    'kondisi' => 'a.status is null',
  ],
  'all' => [
    'title' => 'All Berkas',
    'satuan' => 'files',
    'href' => '?berkas',
    'bg' => 'bg-info',
    'kondisi' => '1',
  ],
];

$nav = '';
foreach ($rstatus_berkas as $status => $rv) {
  $s = "SELECT 1 FROM tb_berkas a 
  JOIN tb_akun b ON a.username=b.username
  WHERE b.tahun_pmb=$tahun_pmb
  AND $rv[kondisi]
  AND $sql_username 
  AND $sql_jenis_berkas 
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $count = mysqli_num_rows($q);

  $nav_active = $get_status == $status ? 'nav-active' : '';
  $nav .= "<a class='nav $nav_active hover' href=$rv[href]><span class=putih>$rv[title] ~ $count</span></a>";
}

$title = $rstatus_berkas[$get_status]['title'];
$satuan = $rstatus_berkas[$get_status]['satuan'];
$bg = $rstatus_berkas[$get_status]['bg'];
$kondisi = $rstatus_berkas[$get_status]['kondisi'];


# ============================================================
# MAIN SELECT BERKAS ALL
# ============================================================
$s = "SELECT a.*,
b.nama as nama_peserta,
c.* 
FROM tb_berkas a 
JOIN tb_akun b ON a.username=b.username 
JOIN tb_jenis_berkas c ON a.jenis_berkas=c.jenis_berkas 
WHERE $kondisi 
AND $sql_username 
AND $sql_jenis_berkas 
ORDER BY a.upload_at DESC, nama_peserta, c.nomor
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$i = 0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $title = $rstatus_berkas[$d['status']]['title'] ?? 'Perlu Review';
  $pengganti_info = $d['pengganti'] ? "dapat digantikan dengan $d[pengganti]" : '';

  $eta = eta2($d['upload_at']);
  $href = "uploads/berkas/$d[file]";

  $btn_undo = $d['status'] ? "
    <button class='btn btn-sm btn-secondary ' name=btn_undo_verif value=$d[id] onclick='return confirm(`Yakin Undo Verifikasi?`)'>Undo Verifikasi</button>
  " : "
    <label class=d-block>
      <input required type=radio name=status class=radio id=radio--$d[id]--accept value=1> <span class=text-success>Berkas ini OK</span>
    </label>
    <label class=d-block>
      <input required type=radio name=status class=radio id=radio--$d[id]--reject value=-1> <span class=text-danger>Berkas ini tidak memenuhi syarat</span>
    </label>

    <div id=blok_alasan--$d[id] class='mt3 mb2 hideit'>
      <div>Alasan Reject</div>
      <textarea id=alasan_reject--$d[id] name=alasan_reject class='form-control mt1' minlength=10></textarea>
      <div class='mt1 f12 abu miring'>minimal 10 karakter</div>
    </div>

    <label class='d-block mt4'>
      <input required type=checkbox /> Saya menyatakan verifikasi saya sudah benar.
    </label>
    <button class='btn btn-sm btn-primary mt2' name=btn_submit_verif value=$d[id]>Submit Verifikasi</button>
  ";

  $bg_badge = $rstatus_berkas[$d['status']]['bg'] ?? 'bg-danger';

  $tr .= "
    <tr id=tr__$d[id]>
      <td>$i</td>
      <td>
        <a class=hover href='?berkas&username=$d[username]'>
          <div>$d[nama_peserta]</div>
          <div class='f12 abu miring'>$d[username]</div>
        </a>
      </td>
      <td>
        <div>
          <a class=hover href='?berkas&jenis_berkas=$d[jenis_berkas]'>
            $d[nama_berkas]
          </a>
        </div>
        <div class='f12 abu miring'>$pengganti_info</div>
      </td>
      <td>
        <div>$d[upload_at]</div>
        <div class='f12 abu miring'>$eta</div>
      </td>
      <td>
        <div class=''><span class='badge $bg_badge'>$title</span></div>
        <div class=red>$d[alasan_reject]</div>
      </td>
      <td>
        <span class='btn btn-primary btn-sm show_file' id=show_file--$d[id]>Show File</span> 
      </td>
    </tr>
    <tr class='hideit tr_img ' id=tr_img--$d[id] style='border-bottom: solid 3px #ccc'>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan=100%>
        <div id=img_area--$d[id] class=mb2>$href</div>
        <form method=post id=form_verif--$d[id] class=mb4>$btn_undo</form>
      </td>
    </tr>
  ";
}

$tr = $tr ? $tr : "<tr><td colspan=100% class='text-info tengah'>-- data tidak ditemukan --</td></tr>";


echo "
  <a href=?petugas>Home Petugas</a>
  <h2>Verifikasi Berkas PMB</h2>
  $filtered_info

  <div class='card mt-4'>
    <div class='card-header $bg putih tengah' style='position:sticky;top:0'>$nav</div>
    <div class='card-body'>
      <table class=table>
        $tr
      </table>
    </div>
  </div>
";

?>





















<script>
  $(function() {



    $('.show_file').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      let href = $('#img_area--' + id).text();
      $('#img_area--' + id).html(`<a target=_blank href=${href}><img class='img-fluid' src='${href}'></a>`);
      $('#tr_img--' + id).fadeIn();
      $(this).fadeOut();
    });
    $('.radio').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      let val = rid[2];
      if (val == 'accept') {
        $('#blok_alasan--' + id).slideUp();
        $('#alasan_reject--' + id).prop('required', false);
      } else {
        $('#blok_alasan--' + id).slideDown();
        $('#alasan_reject--' + id).prop('required', true);
      }
    });

  });

  // $(document).on('click', '#btn_download_csv', function() {
  //   $('input').prop('disabled', true);
  //   $('select').prop('disabled', true);
  //   $('#info_reload').fadeIn();
  //   setTimeout(() => {
  //     location.reload();
  //   }, 5000);
  // })
</script>
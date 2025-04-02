<?php
petugas_only();
include '../includes/eta.php';
include 'petugas-dashboard-styles.php'; // for nav
include 'berkas-styles.php';
include 'berkas-process.php'; // for nav
set_title('Berkas PMB');


$batasan_row = 10; // hanya 10 row yang tampil

$get_status = $_GET['status'] ?? 'all';
$get_username = $_GET['username'] ?? '';
$get_jenis_berkas = $_GET['jenis_berkas'] ?? '';
$get_keyword = $_GET['keyword'] ?? '';

# ============================================================
# PROCESSORS BERKAS
# ============================================================
if (isset($_POST['btn_filter'])) {
  jsurl("?berkas&keyword=$_POST[keyword]");
}

$filtered_info = $get_username ? "<div class='f14 '>Filtered by username: <span class=darkblue>$get_username</span> <a class='btn btn-sm btn-info ml4' href=?berkas>Clear</a></div>" : '';
$filtered_info = $get_jenis_berkas ? "<div class='f14 '>Filtered by jenis_berkas: <span class=darkblue>$get_jenis_berkas</span> <a class='btn btn-sm btn-info ml4' href=?berkas>Clear</a></div>" : $filtered_info;

$sql_username = $get_username ? "b.username = '$get_username'" : '1';
$sql_jenis_berkas = $get_jenis_berkas ? "a.jenis_berkas = '$get_jenis_berkas'" : '1';

if ($get_keyword) {
  $sql_keyword = "(b.nama like '%$get_keyword%' OR c.nama_berkas like '%$get_keyword%') ";
  $clear_filter = "<a class='d-block btn btn-clear-filter btn-sm' href='?berkas'>Clear Filter</a>";
  $input_filtered = 'input-filtered';
} else {
  $sql_keyword = 1;
  $clear_filter = '';
  $input_filtered = '';
}

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
if (!$get_keyword) {
  foreach ($rstatus_berkas as $status => $rv) {
    $s = "SELECT 1 FROM tb_berkas a 
    JOIN tb_akun b ON a.username=b.username 
    JOIN tb_jenis_berkas c ON a.jenis_berkas=c.jenis_berkas 
    WHERE b.tahun_pmb=$tahun_pmb
    AND $rv[kondisi]
    AND $sql_username 
    AND $sql_jenis_berkas 
    AND $sql_keyword
    ";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $count = mysqli_num_rows($q);

    $nav_active = $get_status == $status ? 'nav-active' : '';
    $nav .= "<a class='nav $nav_active hover' href=$rv[href]><span class=putih>$rv[title] ~ $count</span></a>";
  }
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
AND $sql_keyword
ORDER BY a.status, -- dahulukan yang belum diverifikasi
a.upload_at DESC, -- dahulukan yang terbaru
nama_peserta, c.nomor
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$i = 0;
$total_berkas = mysqli_num_rows($q);
if ($total_berkas) {
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $title = $rstatus_berkas[$d['status']]['title'] ?? 'Perlu Review';
    $pengganti_info = $d['pengganti'] ? "dapat digantikan dengan $d[pengganti]" : '';

    $eta = eta2($d['upload_at']);
    $href = "uploads/berkas/$d[file]";

    $info_field_wajibs = '';
    if ($d['field_wajibs'] and !$d['status']) { // jika perlu review tampilkan field wajibs
      $field_wajibs = explode(';', $d['field_wajibs']);
      foreach ($field_wajibs as $field) {
        if ($field) {
          $value_show = $d[$field];
          if ($field == 'nomor_berkas') {
            $kolom = "Nomor $d[nama_berkas]";
          } elseif ($field == 'tanggal_berkas') {
            $kolom = "Tanggal $d[nama_berkas]";
            $value_show = date('d-M-Y', strtotime($d[$field]));
          } elseif ($field == 'nominal') {
            $kolom = "Nominal Transfer";
            $value_show = 'Rp ' . number_format($value_show) . ',-';
          } else {
            $kolom = $field;
          }

          if ($d[$field]) { // jika sudah ada datanya
            $info_field_wajibs .= "<li><b>$kolom</b>: $value_show</li>";
          }
        }
      }
      $info_field_wajibs = "<ul class='bg-danger putih mt1 pt1 pb1 br5 mb1'>$info_field_wajibs</ul>";
    }

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

    if ($i <= $batasan_row) {
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
            <div class=''>$info_field_wajibs</div>
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
            <div id=href--$d[id] class=hideit>$href</div>
            <div id=img_area--$d[id] class=mb2><b class=red>Loading image... mohon tunggu !</b></div>
            <form method=post id=form_verif--$d[id] class=mb4>$btn_undo</form>
          </td>
        </tr>
      ";
    }
  } // end while

  if ($i > $batasan_row) {
    $tr .= "<tr><td colspan=100% class='text-info gradasi-kuning'>-- <span class=text-danger>$batasan_row dari $total_berkas ditampilkan</span>. Untuk pencarian lebih spesifik, silahkan filter berkas atau klik pada nama pendaftar/jenis berkas. --</td></tr>";
  }
} else { // no data berkas
  $tr = "<tr><td colspan=100% class='tengah gradasi-merah'><div class='text-danger mb2 mt2'>-- data tidak ditemukan --</div>$clear_filter</td></tr>";
}



echo "
  <a href=?petugas>Home Petugas</a>
  <h2>Verifikasi Berkas PMB</h2>
  $filtered_info

  <div class='card mt-4'>
    <div class='card-header $bg putih tengah' style='position:sticky;top:0'>
      $nav
      <div style='display:inline-block; margin-left:30px'>
        <form method=post class='d-flex gap-2'>
          <div>
          <input class='form-control form-control-sm $input_filtered' name=keyword minlength=2 maxlength=10 value='$get_keyword'>
          </div>
          <button class='btn btn-sm btn-success' name=btn_filter style='margin-left: -56px'>Filter</button>
          $clear_filter
        </form>
      </div>
    </div>
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
      let href = $('#href--' + id).text();
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
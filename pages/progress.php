<?php
set_h2("System Progress", 'Progress (Gamification Technique) untuk monitoring validasi system secara keseluruhan ');

$detail_progress = true; // detail of at progress only
include 'progress-styles.php'; // style khusus di progress
include 'counts.php';
// include 'progress-functions.php';
function bg_persen($persen)
{
  $hex = round($persen * 255 / 100, 0);
  $green = '00';
  $red = 255 - $hex;
  $blue = $hex;
  return "background:rgb($red,$green,$blue)";
}

# ============================================================
# DIV COUNT INFO
# ============================================================
foreach ($rcount as $tb => $arr) {

  $count_filter = $rcount[$tb]['count_filter'] ?? null;

  $divs = "<div class='alert alert-danger mt2'>Belum ada konten progress untuk tabel [$tb]</div>";
  if ($count_filter !== null) {
    $persen = !$rcount[$tb]['count_total'] ? 0 : round(($count_filter / $rcount[$tb]['count_total']) * 100);

    # ============================================================
    # DEFAULT PROGRESS
    # ============================================================
    $bg_persen = bg_persen($persen);
    $divs =  "
      <div class='wadah gradasi-toska'>
        <!-- bootstrap progress -->
        <div class='progress'>
          <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='$persen' aria-valuemin='0' aria-valuemax='100' style='width:$persen%; $bg_persen'>
            $persen%
          </div>
  
        </div>
      </div>
    ";
  }

  $file = "pages/progress-$tb.php";
  if (file_exists($file)) include $file; // divs will be replaced
  $title2 = $rcount[$tb]['title2'] ?? null;
  $title = $title2 ? $title2 : $rcount[$tb]['title'];

  $satuan = $arr['satuan'] ?? null;
  if ($count_filter !== null) {
    $count_of = "
      <span class=count_filter>$count_filter</span> 
      of 
      <span class=count_total>$arr[count_total]</span> 
      <span class=satuan>$satuan</span>
    ";
  } else {
    $count_of = "<span class=count_total>$arr[count_total]</span>";
  }

  $deskripsi = $arr['deskripsi'] ?? '';
  if ($deskripsi) {
    $deskripsi .= " <a target=_blank href='?verifikasi&tb=$tb'>$img_next</a>";
  }

  $hideit = $tb == $get_tb ? '' : 'hideit';

  echo "
    <div class='$hideit wadah div_count_info' id=div_count_info__$tb>
      <h3>Progress $title</h3> 
      <p>$deskripsi</p>
      <div class=mb2><b>Count:</b> $count_of</div>
      $divs
    </div>
  ";
}


?>
<script>
  function show_div(tb) {
    $('.div_count').removeClass('div_count_active');
    $('#div_count__' + tb).addClass('div_count_active');
    $('.div_count_info').hide();
    $('#div_count_info__' + tb).slideDown();
  }
  $(function() {
    get_cookies();
    show_div(tb);

    $('.div_count').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let tb = rid[1];
      show_div(tb);
      document.cookie = `tb=${tb}`;
    })
  })
</script>
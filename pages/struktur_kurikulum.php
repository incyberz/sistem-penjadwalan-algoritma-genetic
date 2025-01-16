<?php
# ============================================================
# STRUKTUR KURIKULUM
# ============================================================
$id_prodi = $_GET['id_prodi'] ?? '';
$mode = $_GET['mode'] ?? 'view';
$mode_edit = $mode == 'edit' ? 1 : 0;
$img_drop = img_icon('drop');
$img_drop_disabled = img_icon('drop_disabled');
$MKDU_badge = "<span class=mkdu_badge>MKDU</span>";
$rmk = []; // array MK Struktur Kurikulum
$view_semester = $_GET['view_semester'] ?? ''; // untuk menyimpan session editing semester
echo "<span class=hideit id=view_semester>$view_semester</span>";

include 'struktur_kurikulum-styles.php';
include 'struktur_kurikulum-processors.php';

$not_mode = $mode_edit ? 'view' : 'edit';
$Not_Mode = $mode_edit ? 'Mode View' : 'Mode Editing';
$nav_mode = " <a href='?struktur_kurikulum&id_prodi=$id_prodi&mode=$not_mode&view_semester=$view_semester'>$img_prev $Not_Mode</a>";

$d_kur = [];
if (!$id_prodi) {
  # ============================================================
  # WAJIB PILIH PRODI
  # ============================================================
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

  # ============================================================
  # STRUKTUR KURIKULUM GANJIL GENAP
  # ============================================================
  $thead = "
    <thead>
      <th>No</th>
      <th>MK</th>
      <th>SKS</th>
      <th>Dosen</th>
    </thead>
  ";

  # ============================================================
  # PROPERTI PRODI
  # ============================================================
  $s = "SELECT a.*,
  b.id as id_kurikulum  
  FROM tb_prodi a 
  JOIN tb_kurikulum b ON a.id=b.id_prodi
  WHERE a.id=$id_prodi 
  AND b.id_ta = $ta_aktif
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $prodi = mysqli_fetch_assoc($q);
  $id_kurikulum = $prodi['id_kurikulum'];
  $singkatan_prodi = $prodi['singkatan'];
  set_title("$prodi[jenjang]-$prodi[singkatan] - Struktur Kurikulum");

  # ============================================================
  # MAIN LOOP FROM MAX SEMESTERS PRODI
  # ============================================================
  $tb_kurikulum = '';
  $total_sks = 0;
  $nav_semester = '';
  for ($semester = 1; $semester <= $prodi['jumlah_semester']; $semester++) {
    $nav_semester .= $mode_edit ? "<div class=nav_semester_item id=nav_semester_item__$semester>Semester $semester</div>" : '';
    # ============================================================
    # MAIN SELECT MK | THIS SEMESTER | THIS PRODI | GANJIL GENAP
    # ============================================================
    $tr_mk = '';
    $sum_sks = 0;
    include 'struktur_kurikulum-tr_mk.php';

    $col = 6; // akan berganti ke-12 saat mode edit
    $blok_add_mk = ''; // akan terisi saat mode edit
    if ($mode_edit) {
      include 'struktur_kurikulum-blok_assign_or_add.php';
    }

    # ============================================================
    # CREATE TABLE TB KURIKULUM
    # ============================================================
    $tb = "
      <h4 class='darkblue mt2'>Semester $semester</h4>
      <table class='table table-hover table-striped'>
        $thead
        $tr_mk
        <tfoot class='bold gradasi-kuning'>
          <td colspan=2 class=right>JUMLAH SKS</td>
          <td colspan=2>$sum_sks</td>
        </tfoot>
      </table>
    ";

    // jika mode edit, maka tambahkan blok edit semester
    if ($mode_edit) $tb = "
      <div class='hideit wadah gradasi-toska mt2 blok_edit_semester' id=blok_edit_semester__$semester>
        $tb
        $blok_add_mk
      </div>
    ";

    # ============================================================
    # FINAL UI LOOP | TB KURIKULUM
    # ============================================================
    $tb_kurikulum .= "
      <div class='col-$col'>
        $tb
      </div>
    ";
  }




  $blok_total_sks = $mode_edit ? '' : "
    <div class='border-top border-bottom gradasi-kuning mt-4 p2 tengah'>  
      <b>TOTAL : </b> <span id=total_sks class='f30'>$total_sks</span> SKS
    </div>  
  ";

  $nav_semester = !$nav_semester ? '' : "
    <div style='position:fixed; bottom:0;left:0;right:0; background:white; z-index:1000;border-top:solid 1px #ccc;'>
      <div class='flexy flex-center tengah mb2 mt2'>
        <div>$nav_mode</div>
        $nav_semester
      </div>
    </div>
  ";

  echo "
    <div class='tengah mb2'>
      $nav_mode
    </div>
    <div class='tengah gradasi-toska p-3'>
      <h2>STRUKTUR KURIKULUM</h2>
      <h3>$prodi[nama] $tahun_ta</h3>
    </div>
    $nav_semester
    <div class=row>  
      $tb_kurikulum
    </div>
    $blok_total_sks
  ";
}
?>
<script>
  $(function() {
    let view_semester = $('#view_semester').text();
    view_semester = view_semester ? view_semester : 1;
    $('#blok_edit_semester__' + view_semester).fadeIn();
    $('#nav_semester_item__' + view_semester).addClass('nav_semester_active');

    $('.nav_semester_item').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      $('.blok_edit_semester').hide();
      $('.nav_semester_item').removeClass('nav_semester_active');
      $(this).addClass('nav_semester_active');
      $('#blok_edit_semester__' + id).fadeIn();
    });
    $('.cbmk').click(function() {
      // cbmk_id = "cbmk-$d2[id_prodi]-$id_kurikulum-$semester-$d2[id_mk]";
      //            0     1             2            3            4

      let tid = $(this).prop('id');
      let rid = tid.split('-');
      let aksi = rid[0];
      let id_prodi = rid[1];
      let id_kurikulum = rid[2];
      let semester = rid[3];
      let id_mk = rid[4];
      let id_mks = '';
      // console.log(aksi, id_prodi, id_kurikulum, semester, id_mk);
      $('.cbmk-' + semester).each(function() {
        if ($(this).prop('checked')) {
          id_mks += $(this).prop('id').split('-')[4] + ';';
        }
      });
      if (id_mks) {
        $('#btn_assign_mk-' + semester).prop('disabled', 0);
        $('#btn_assign_mk-' + semester).val(
          id_prodi + '-' +
          id_kurikulum + '-' +
          semester + '-' +
          id_mks
        );
      } else {
        $('#btn_assign_mk-' + semester).prop('disabled', 1);
      }
    })
  })
</script>
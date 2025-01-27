<?php
# ============================================================
# STRUKTUR KURIKULUM
# ============================================================
$fakultas = $_GET['fakultas'] ?? 'FKOM';
$id_prodi = $_GET['id_prodi'] ?? '';
$id_shift = $_GET['id_shift'] ?? '';
$mode = $_GET['mode'] ?? 'view';
$mode_edit = $mode == 'edit' ? 1 : 0;
$img_drop = img_icon('drop');
$img_drop_disabled = img_icon('drop_disabled');
$MKDU_badge = "<span class=mkdu_badge>MKDU</span>";
$rmk = []; // array MK Struktur Kurikulum
$get_semester = ($_GET['semester'] ?? null) ? $_GET['semester'] : $default_semester; // untuk menyimpan session editing semester
echo "<span class=hideit id=semester>$get_semester</span>";
$img_next = img_icon('next');
$SHIFT = $id_shift == 'R' ? 'REGULER' : 'NON-REGULER';


include 'struktur_kurikulum-styles.php';
include 'struktur_kurikulum-processors.php';

$not_mode = $mode_edit ? 'view' : 'edit';
$Not_Mode = $mode_edit ? 'Mode View' : 'Mode Editing';
$Not_petunjuk = $mode_edit ? 'Anda berada pada Mode Editing, seluruh tombol dapat Anda akses' : 'Anda berada pada Mode View. Untuk mengakses Fitur Manage silahkan klik Mode Editing';
$nav_mode = " <a href='?struktur_kurikulum&id_prodi=$id_prodi&mode=$not_mode&semester=$get_semester&id_shift=$id_shift'>$img_prev $Not_Mode</a>";


if (!$id_prodi || !$id_shift) {
  # ============================================================
  # WAJIB PILIH PRODI
  # ============================================================
  include 'struktur_kurikulum-pilih_kurikulum.php';
} else {
  $shift = $rshift[$id_shift]['nama'];
  echo "
  <span class=hideit id=id_prodi>$id_prodi</span>
  <span class=hideit id=shift>$id_shift</span>
  ";

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
  set_title("$prodi[jenjang]-$prodi[singkatan]-$id_shift - Struktur Kurikulum");


  # ============================================================
  # NAV HEADER
  # ============================================================
  include 'struktur_kurikulum-nav_header.php';

  # ============================================================
  # MAIN LOOP FROM MAX SEMESTERS PRODI
  # ============================================================
  $tb_kurikulum = '';
  $total_sks = 0;
  $nav_semester = '';
  if ($mode_edit) {
    for ($semester = 1; $semester <= $prodi['jumlah_semester']; $semester++) {
      if ($ta_aktif % 2 != $semester % 2) continue;
      $nav_semester_active = $semester == $get_semester ? 'nav_semester_active' : '';
      $nav_semester .= "
        <div class='nav_semester_item $nav_semester_active' >
          <a href='?struktur_kurikulum&id_prodi=$id_prodi&mode=edit&id_shift=$id_shift&semester=$semester'>Semester $semester</a>
        </div>
      ";
    }
  }


  for ($semester = 1; $semester <= $prodi['jumlah_semester']; $semester++) {
    // hanya menampilkan semester ganjil | genap saja
    if ($ta_aktif % 2 != $semester % 2) continue;


    // semester lain tidak diproses saat mode edit
    if ($mode_edit and $get_semester != $semester) continue;

    # ============================================================
    # GET KELASS IN THIS SEMESTER | THIS PRODI | THIS TA
    # ============================================================
    $kelass = null;
    $id_kelass = null;
    $blok_kelass = null;
    $rkelas_aktif = [];
    if ($mode == 'edit') {
      $s = "SELECT 
      id as id_kelas, 
      nama as kelas 
      FROM tb_kelas 
      WHERE semester='$semester' 
      AND id_prodi=$id_prodi 
      AND id_ta=$ta_aktif 
      AND id_shift='$id_shift' 
      ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (!mysqli_num_rows($q)) {
        $pesan = "Belum bisa assign Dosen Pengampu karena belum ada kelas di: semester [$semester] prodi [$prodi[nama]] kelas [$shift] TA. [$ta_aktif].";
        $blok_kelass = "<div class='alert alert-danger'>$pesan | <a href='?crud&tb=kelas&id_prodi=$id_prodi&id_shift=$id_shift&semester=$semester&note=$pesan'>Tambah</a></div>";
      } else {
        while ($d = mysqli_fetch_assoc($q)) {
          $rkelas_aktif[$d['kelas']] = $d;
          $koma = $kelass ? ', ' : '';
          $kelass .= "$koma$d[kelas]";
          $id_kelass .= "$koma$d[id_kelas]";
        }
        $blok_kelass = "
          <div class='alert alert-info'>
            <b>Kelas Aktif:</b> 
            <span id=kelass>$kelass</span>
            <span id=id_kelass class=hideit>$id_kelass</span>
          </div>
        ";
      }
    }




    # ============================================================
    # MAIN SELECT MK | THIS SEMESTER | THIS PRODI | THIS TA
    # ============================================================
    $tr_mk = '';
    $sum_sks = 0;
    include 'struktur_kurikulum-tr_mk.php';

    $col = 6; // akan berganti ke-12 saat mode edit
    $col = 12; // new struktur ganjil | genap only
    $blok_add_mk = ''; // akan terisi saat mode edit
    if ($mode_edit) {
      include 'struktur_kurikulum-blok_assign_or_add.php';
    }

    # ============================================================
    # CREATE TABLE TB KURIKULUM
    # ============================================================
    $tb = "
      <h4 class='darkblue mt2'>Semester $semester</h4>
      $blok_kelass
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
      <div class=' wadah gradasi-toska mt2 blok_edit_semester' id=blok_edit_semester__$semester>
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

  $shift_title = $rshift[$id_shift]['nama'];
  $Ganjil = $ta_aktif % 2 == 0 ? 'Genap' : 'Ganjil';
  $editing_info = $mode_edit ? "<span class=brown>Editing Semester $Ganjil</span> <a href='?home&show_config=1' onclick='return confirm(`Ganti Tahun Ajar?`)'>$img_manage</a>" : '';
  echo "
    <div class='petunjuk tengah mb4'>$Not_petunjuk $img_help</div>
    <div class='tengah mb2'>
      $nav_mode
    </div>
    <div class='tengah gradasi-toska p-3'>
      <h4 class=upper>$editing_info</h4>

      $nav_header
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
    let id_prodi = $('#id_prodi').text();
    let id_user = $('#id_user').text();
    let shift = $('#shift').text();
    let semester = $('#semester').text();
    let kelass = $('#kelass').text(); // kelas2 aktif di semester tsb
    let id_kelass = $('#id_kelass').text(); // id_kelas2 aktif di semester tsb
    // semester = semester ? semester : 1;
    // $('#blok_edit_semester__' + semester).fadeIn();
    // $('#nav_semester_item__' + semester).addClass('nav_semester_active');

    // $('.nav_semester_item').click(function() {
    //   let tid = $(this).prop('id');
    //   let rid = tid.split('__');
    //   let aksi = rid[0];
    //   let id = rid[1];
    //   $('.blok_edit_semester').hide();
    //   $('.nav_semester_item').removeClass('nav_semester_active');
    //   $(this).addClass('nav_semester_active');
    //   $('#blok_edit_semester__' + id).fadeIn();
    // });
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
    });

    $('.keyword').keyup(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_kumk = rid[1];
      let id_dosen = rid[2]; // optional
      if ($(this).val().trim().length < 3) {
        $('#list_dosen__' + id_kumk).hide();

      } else {

        let keyword = $(this).val().trim();
        // console.log(aksi, id_kumk);

        $.ajax({
          url: 'pages/struktur_kurikulum-ajax_dosen.php?keyword=' + keyword +
            '&id_dosen=' + id_dosen +
            '&id_prodi=' + id_prodi +
            '&id_kumk=' + id_kumk,
          success: function(a) {
            $('#list_dosen__' + id_kumk).show();
            $('#list_dosen__' + id_kumk).html(a);
          }
        })
      }
    });

    $(document).on('click', '.item_list_dosen', function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_dosen = rid[1];
      let id_kumk = rid[2];
      let nama_dosen = $('#nama_dosen__' + id_dosen + '__' + id_kumk).text();
      // console.log(aksi, id_dosen, id_kumk, nama_dosen);

      console.log(id_dosen, id_kumk, id_user);
      if (id_dosen && id_kumk && id_user) {
        let y = confirm(`Set MK ini ke dosen: [ ${nama_dosen} ] ?`);
        if (y) {
          $.ajax({
            url: `pages/struktur_kurikulum-ajax_set_dosen.php?id_dosen=${id_dosen}&id_kumk=${id_kumk}&id_user=${id_user}&id_shift=${shift}&id_kelass=${id_kelass}`,
            success: function(a) {
              if (a == 'sukses') {
                location.reload();
                // $('#dosen_pengampu__' + id_kumk).text(nama_dosen);
                // $('#blok_edit_dosen_pengampu' + id_kumk).hide();

              } else {
                alert(a)
              }
            }
          })
        }

      } else {
        console.log(id_dosen, id_kumk, id_user);

      }
    });
  });
</script>
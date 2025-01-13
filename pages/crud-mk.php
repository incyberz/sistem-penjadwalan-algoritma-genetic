<style>
  .nav_active {
    background: green;
    color: white
  }

  .count_sm_badge {
    display: inline-block;
    padding: 5px;
    background: white;
    color: darkblue;
    border-radius: 50%;
    margin: 5px;
    width: 30px;
    text-align: center;
    font-weight: bold;
  }
</style>

<?php
set_title('Pilihan MK');

# ============================================================
# NAV PRODI
# ============================================================
$items = '';
$sub_select = "SELECT COUNT(1) FROM tb_kumk p 
JOIN tb_kurikulum q ON p.id_kurikulum=q.id 
JOIN tb_mk r ON p.id_mk=r.id 
WHERE q.id_ta = $ta_aktif AND q.id_prodi=a.id AND p.semester =";

$s = "SELECT a.*,
($sub_select '1') count_sm1, 
($sub_select '2') count_sm2, 
($sub_select '3') count_sm3, 
($sub_select '4') count_sm4, 
($sub_select '5') count_sm5, 
($sub_select '6') count_sm6, 
($sub_select '7') count_sm7, 
($sub_select '8') count_sm8
FROM tb_prodi a 
ORDER BY a.jenjang, a.nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// $rprodi = [];
$count_sm = [];
$nav_active = 'nav_active';
$id_prodi_pertama = '';
$prodi_pertama = '';
while ($d = mysqli_fetch_assoc($q)) {
  if (!$id_prodi_pertama) $id_prodi_pertama = $d['id'];
  if (!$prodi_pertama) $prodi_pertama = "$d[jenjang]-$d[singkatan]";
  // $rprodi[$d['id']] = $d;
  $count_sm[$d['id']][1] = $d['count_sm1'];
  $count_sm[$d['id']][2] = $d['count_sm2'];
  $count_sm[$d['id']][3] = $d['count_sm3'];
  $count_sm[$d['id']][4] = $d['count_sm4'];
  $count_sm[$d['id']][5] = $d['count_sm5'];
  $count_sm[$d['id']][6] = $d['count_sm6'];
  $count_sm[$d['id']][7] = $d['count_sm7'];
  $count_sm[$d['id']][8] = $d['count_sm8'];

  $items .= "
  <div 
    id=nav_items__id_prodi__$d[id]
    class='$nav_active bordered px-2 py-0 br5 pointer gradasi-toska nav_items nav_items__id_prodi' 
  >
    $d[jenjang]-$d[singkatan]
  </div>
";
  $nav_active = '';
}
$nav_prodi = "<div class='d-flex flex-center gap-4'>$items</div>";

# ============================================================
# NAV SEMESTER
# ============================================================
$items = '';

for ($i = 1; $i <= 8; $i++) {

  if ($is_ganjil and $i % 2 == 0) continue;
  if (!$is_ganjil and $i % 2 == 1) continue;

  $counts = '';
  foreach ($count_sm as $key_id_prodi => $arr_count) {
    $counts .= "
      <span class='hideit count_sm count_sm__$key_id_prodi'>
        <span class=count_sm_badge>$arr_count[$i]</span>
      </span>
    ";
  }

  $nav_active = $i == 1 ? 'nav_active' : '';
  $items .= "
  <div 
    id=nav_items__semester__$i
    class='$nav_active bordered px-2 py-0 br5 pointer gradasi-toska nav_items nav_items__semester' 
  >
    sm$i $counts
  </div>
";
}
$nav_semester = "<div class='d-flex flex-center gap-1 mt1'>$items</div>";

$note = $_GET['note'] ?? '';
$note_info = $note ? "<div class='red tengah'><b>Note:</b> $note</div>" : '';

echo "
  $note_info
  <div class='gradasi-toska f12 p1'>
    <h2 class='bold darkblue f12 tengah'>Pilihan MK TA. $tahun_ta $Gg</h2>
    <div id=navs class=''>
      $nav_prodi
      $nav_semester
    </div>
  </div>
";

# ============================================================
# MAIN SELECT MK
# ============================================================
$s = "SELECT 
a.*,
d.id as id_prodi,  
d.singkatan as prodi,
(SELECT COUNT(1) FROM tb_st_mk WHERE id_$tb=a.id) count_st_mk  
FROM tb_mk a 
JOIN tb_kumk b ON a.id=b.id_mk 
JOIN tb_kurikulum c ON b.id_kurikulum=c.id 
JOIN tb_prodi d ON c.id_prodi=d.id 
WHERE c.id_ta = $ta_aktif 
ORDER BY b.id_kurikulum, b.semester, a.nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$num_rows = mysqli_num_rows($q);
$tr_mk = '';
$last_semester = '';
if ($num_rows) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    if ($last_semester != $d['semester']) $i = 1;
    $last_semester = $d['semester'];

    $btn_delete = "<span class='btn-transparan btn_delete' id=btn_delete__$d[id]>$img_delete</span>";
    $btn_delete_disabled = "<span class='btn-transparan' onclick='alert(`MK ini sudah terpakai ( $d[count_st_mk] sub-data )`)'>$img_delete_disabled</span>";
    $btn = $d['count_st_mk'] ? $btn_delete_disabled : $btn_delete;

    $tr_mk .= "
    <tr 
      class='hideit tr_mk tr_mk__$d[prodi] tr_mk__$d[id_prodi]__$d[semester]' 
      id=tr_mk__$d[id]
    >
      <td>$i</td>
      <td>$d[nama]</td>
      <td>$d[semester]</td>
      <td>$d[sks]</td>
      <td>$d[count_st_mk]</td>
      <td>$btn</td>
    </tr>
  ";
  }
}

echo "  
<span class=hideit id=id_prodi_pertama>$id_prodi_pertama</span>
<table class='table table-hover table-striped' id=tb_mk>
  <thead>
    <th>No</th>
    <th>MK</th>
    <th>Semester</th>
    <th>SKS</th>
    <th><i class='f12 abu miring proper'>Sub Data</i></th>
    <th>Aksi</th>
  </thead>
  $tr_mk

  <tr 
    class='hideit tr_mk tengah ' 
    id=tr_mk__no_data
  >
    <td colspan=100%>
      <div class='alert alert-danger' id=no_data_info>No Data</div>
    </td>
  </tr>
</table>
<script>//let table = new DataTable('#tb_mk');</script>
";
?>
<script>
  $(function() {
    let prodi = $('#prodi_pertama').text();
    let id_prodi = $('#id_prodi_pertama').text();
    let semester = '1';
    $('.tr_mk__' + id_prodi + '__' + semester).show();
    $('.count_sm__' + id_prodi).show();

    $('.nav_items').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let kolom = rid[1];
      let id = rid[2];
      if (kolom == 'semester') semester = id;
      if (kolom == 'id_prodi') {
        id_prodi = id;
        prodi = $(this).text().trim();
      }

      $('.' + aksi + '__' + kolom).removeClass('nav_active');
      $(this).addClass('nav_active');

      $('.count_sm').hide();
      $('.count_sm__' + id_prodi).show();


      $('.tr_mk').hide();
      if ($('.tr_mk__' + id_prodi + '__' + semester).length) {
        $('.tr_mk__' + id_prodi + '__' + semester).show();
      } else {
        $('#no_data_info').text('No Data MK di semester ' + semester);
        $('#tr_mk__no_data').show();
      }


      // effect to form add
      $('#add_' + kolom).val(id);

      // effect to title
      $('title').text(prodi + '-Sm' + semester + ' - Pilihan MK');

      // console.log('.tr_mk__' + id_prodi + '__' + semester);
      // console.log(aksi, kolom, id, 'id_prodi:', id_prodi, 'semester:', semester);

    })
  })
</script>
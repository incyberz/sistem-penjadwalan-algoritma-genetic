<?php
# ============================================================
# SUB DATA HANDLER
# ============================================================
$th_subdata = '';
$sql_subdata = '';
$arr_subdata = [
  'kelas' => 'st_detail',
  // 'mk' => 'st_mk', // spesialisasi tampilan UI
  'dosen' => 'st',
  'ruang' => 'jadwal',
  'ta' => 'kurikulum',
  'prodi' => 'kurikulum',
  'kurikulum' => 'kumk',
  'petugas' => 'st',
];
if (key_exists($tb, $arr_subdata)) {
  $th_subdata = "
    <th class=f12>
      <div class='f10 abu'>sub data</div>
      <span id=span_sub_data class=proper>$arr_subdata[$tb]</span>
    </th>
  ";
  $sql_subdata = ",(SELECT COUNT(1) FROM tb_$arr_subdata[$tb] WHERE id_$tb=a.id) count";
}

# ============================================================
# MAIN SELECT
# ============================================================
$sql = "SELECT a.* $sql_subdata 
FROM tb_$tb a";

$result = $conn->query($sql);
$num_rows = $result->num_rows;
$tr = '';
$form_add = '';
$i = 0;
while ($row = $result->fetch_assoc()) {
  $cid_ta = $row['id_ta'] ?? null;
  if ($cid_ta and $cid_ta != $ta_aktif) continue; // skip data non curent TA 
  $i++;

  $td = '';
  foreach ($kolom['Field'] as $key => $field) {
    if (in_array($field, $hide_field)) continue;
    if ($field == 'nama') {
      $row[$field] = "<a href='?detail&tb=$tb&id=$row[id]'>$row[$field]</a>"; // manage detail
    }
    $td .= "<td>$row[$field]</td>";
  }

  $sub_count = $row['count'] ?? '';
  $td_count = $th_subdata ? "<td>$sub_count</td>" : '';
  if (!isset($row['id'])) $row['id'] = $row[$tb];

  $btn_delete = "<span class='btn-transparan btn_delete' id=btn_delete__$row[id]>$img_delete</span>";
  $btn_delete_disabled = !$th_subdata ? '' : "<span class='btn-transparan' onclick='alert(`Tidak bisa menghapus karena ada $row[count] sub-data.`)'>$img_delete_disabled</span>";
  $btn = $sub_count ? $btn_delete_disabled : $btn_delete;

  $tr .= "
    <tr id=tr__$row[id]>
      <td>$i</td>
      $td
      $td_count
      <td>
        $btn
      </td>
    </tr>
  ";
}




# ============================================================
# SUB HEADER
# ============================================================
$note = $_GET['note'] ?? '';
$note_info = $note ? "<span class='red'><b>Note:</b> $note</span>" : '';
set_h2("tampil $tb", $note_info);

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <table id=myTable class='table table-striped'>
    <thead>
      <th>No</th>
      $th
      $th_subdata
      <th>Aksi</th>
    </thead>
    $tr
  </table>
  <script>let table = new DataTable('#myTable');</script>
";

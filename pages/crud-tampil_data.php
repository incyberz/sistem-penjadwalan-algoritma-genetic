<?php
$sub_data = '<div class="f10 abu">sub data</div>';
$th_jadwal = "<th class=f12>$sub_data Jadwal</th>";
$sql_count = ",(SELECT COUNT(1) FROM tb_jadwal WHERE id_$tb=a.id) count";
if ($tb == 'prodi' || $tb == 'ta' || $tb == 'kurikulum') {
  $th_jadwal = '';
  $sql_count = '';
  $rule_join = 'id_prodi=a.id';
  $th_jadwal = "<th class=f12>$sub_data Kelas</th>";
  $sql_count = ",(SELECT COUNT(1) FROM tb_kelas WHERE $rule_join) count";
}

$sql = "SELECT a.* $sql_count 
FROM tb_$tb a";
echolog($sql);
$result = $conn->query($sql);
$num_rows = $result->num_rows;
$tr = '';
$form_add = '';
$i = 0;
while ($row = $result->fetch_assoc()) {
  $i++;

  $td = '';
  foreach ($kolom['Field'] as $key => $field) {
    if (in_array($field, $hide_field)) continue;
    if ($field == 'nama') {
      $row[$field] = "<a href='?detail&tb=$tb&id=$row[id]'>$row[$field]</a>"; // manage detail
    }
    $td .= "<td>$row[$field]</td>";
  }

  $td_count = "<td>$row[count]</td>";
  if (!isset($row['id'])) $row['id'] = $row[$tb];

  $btn_delete = "<span class='btn-transparan btn_delete' id=btn_delete__$row[id]>$img_delete</span>";
  $btn_delete_disabled = "<span class='btn-transparan' onclick='alert(`Tidak bisa menghapus karena ada $row[count] sub-data.`)'>$img_delete_disabled</span>";
  $btn = $row['count'] ? $btn_delete_disabled : $btn_delete;

  $tr .= "
    <tr>
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
set_h2("tampil $tb");

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <table id=myTable class='table table-striped'>
    <thead>
      <th>No</th>
      $th
      $th_jadwal
      <th>Aksi</th>
    </thead>
    $tr
  </table>
  <script>let table = new DataTable('#myTable');</script>
";

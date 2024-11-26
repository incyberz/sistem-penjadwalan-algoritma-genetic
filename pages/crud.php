<?php
$tb = $_GET['tb'] ?? 'prodi';
$required_icon = ' <b style=color:red>*</b> ';
$hide_field = ['id', 'created_at', 'updated_at'];

# ============================================================
# INFO TAMBAHAN UNTUK FIELD
# ============================================================
$arr_info_tambahan = [];
$arr_info_tambahan['whatsapp'] = 'awali dengan "628..."';

# ============================================================
# describe tabel
# ============================================================
$sql = "DESCRIBE tb_$tb";
$result = $conn->query($sql);

// while ($row = $result->fetch_assoc()) {
$i = 0;
$kolom = []; // inisialisasi
while ($row = $result->fetch_assoc()) {
  foreach ($row as $key => $value) {
    $kolom[$key][$i] = $value;
  }
  $i++;
}


# ============================================================
# TABLE HEADER | INPUT | SELECT
# ============================================================
$th = '';
$input_th = '';
foreach ($kolom['Field'] as $key => $field) {
  if (in_array($field, $hide_field)) continue;
  $th .= "<th>$field</th>";

  // info tambahan
  $info_tambahan = $arr_info_tambahan[$field] ?? null;

  $key_field = $kolom['Key'][$key];
  if ($key_field == 'MUL') { // select
    $sub_tb = str_replace('id_', '', $field);

    $sql = "SELECT * FROM tb_$sub_tb";
    $result = $conn->query($sql);
    if ($result->num_rows) {
    } else {
      die(alert("Tabel [ $sub_tb ] belum ada data. Silahkan tambahkan <a href='?crud&tb=$sub_tb'>data $sub_tb</a> terlebih dahulu."));
    }


    $input_th .= "
      <th>
        <select class='form-control' name='$field'>
          <option value=''>- pilih $sub_tb -</option>
        </select>
        <div class='left f10 abu miring normal ml1 mt1 mb2'>$info_tambahan</div>
      </th>
    ";
  } else { // input type 
    // required handler
    $required = $kolom['Null'][$key] == 'NO' ? 'required' : null;
    $req_info = $required ? "$required_icon required" : '- opsional';

    // field_type handler
    $field_type = $kolom['Type'][$key];
    $type = '';
    $maxlength = '';
    $length_info = '';
    $min = '';
    if (strpos("salt$field_type", 'varchar') || $field_type == 'text') {
      $type = 'type=text';
      if ($field_type != 'text') {
        $tmp = explode('(', $field_type);
        $tmp2 = explode(')', $tmp[1]);
        $maxlength = 'maxlength=' . $tmp2[0];
        $length_info = "(max $tmp2[0] char)";
      }
    } elseif (strpos("salt$field_type", 'int')) {
      $type = 'type=number';
      $min = "min=1";
    } else {
      die(alert("Belum ada handler untuk tipe data $field_type"));
    }

    $input_th .= "
      <th>
        <input $required $maxlength class='form-control' $type $min name='$field' placeholder='$field'>
        <div class='left f10 abu miring normal ml1 mt1 mb2'>$req_info $length_info $info_tambahan</div>
      </th>
    ";
  } // end if input type


}

# ============================================================
# MAIN SELECT
# ============================================================
$sql = "SELECT * FROM tb_$tb";
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
    $td .= "<td>$row[$field]</td>";
  }

  $tr .= "
    <tr>
      <td>$i</td>
      $td
      <td>
        <span class='btn-transparan'>$img_delete</span>
      </td>
    </tr>
  ";
}

# ============================================================
# FORM ADD DATA
# ============================================================
$no_baru = $i + 1;
$form_add = "
  <h2 class=proper>Tambah Data $tb</h2>
  <form method=post class='wadah gradasi-toska hideita' id=form_add>
    <table class='table'>
      <tr>
        <th><span class='f14 normal miring abu'>*$no_baru</span></th>
        $input_th
        <th>
          <button class='btn-transparan'>$img_save</button>
        </th>
      </tr>
    </table>
  </form>
  <div><span class='pointer btn_aksi' id=form_add__toggle>$img_add Add</span></div>
";

# ============================================================
# SUB HEADER
# ============================================================
set_h2("manage $tb");

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <table id=myTable class='table table-striped'>
    <thead>
      <th>No</th>
      $th
      <th>Aksi</th>
    </thead>
    $tr
  </table>
  <script>let table = new DataTable('#myTable');</script>
  $form_add
";

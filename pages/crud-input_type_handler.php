<?php
$info_tambahan = $arr_info_tambahan[$tb][$field] ?? null;

$field_key = $kolom['Key'][$key];
$field_type = $kolom['Type'][$key];

if ($field_key == 'MUL') { // select
  # ============================================================
  # PILIHAN DATA DARI TABEL LAIN
  # ============================================================
  $sub_tb = str_replace('id_', '', $field);
  $sql = "SELECT id,nama FROM tb_$sub_tb";
  $result = $conn->query($sql);
  $option = '';
  if ($result->num_rows) {
    while ($row = $result->fetch_assoc()) {
      $option .= "<option value='$row[id]'>$row[nama]</option>";
    }
  } else {
    die(alert("Tabel [ $sub_tb ] belum ada data. Silahkan tambahkan <a href='?crud&tb=$sub_tb'>data $sub_tb</a> terlebih dahulu."));
  }

  $input_th .= "
    <th>
      <select class='form-control' name='$field' id='add_$field' >
        <option value=''>- pilih $sub_tb -</option>
        $option
      </select>
      <div class='left f10 abu miring normal ml1 mt1 mb2'>$info_tambahan</div>
    </th>
  ";
} elseif (strpos("salt$field_type", 'set(')) {
  # ============================================================
  # ENUM | SET
  # ============================================================
  $tmp = explode('(', $field_type);
  $tmp2 = explode(')', $tmp[1]);
  $tmp3 = str_replace('\'', '', $tmp2[0]);
  $arr_pilihan = explode(',', $tmp3);
  $option = '';
  foreach ($arr_pilihan as $pilihan) {
    $option .= "<option value='$pilihan'>$field: $pilihan</option>";
  }

  $input_th .= "
    <th>
      <select class='form-control' name='$field' id='add_$field' >
        $option
      </select>
      <div class='left f10 abu miring normal ml1 mt1 mb2'>$required_icon pilih salah satu</div>
    </th>
  ";
} else {
  # ============================================================
  # INPUT BIASA DARI USER
  # ============================================================
  // required handler
  $required = $kolom['Null'][$key] == 'NO' ? 'required' : null;
  $req_info = $required ? "$required_icon required" : '- opsional';

  // field_type handler
  $type = '';
  $maxlength = '';
  $minlength = '';
  $length_info = '';
  $min = '';
  if (strpos("salt$field_type", 'varchar') || $field_type == 'text' || $field_type == 'timestamp') {
    $type = 'type=text';
    if (strpos("salt$field_type", 'varchar')) {
      $tmp = explode('(', $field_type);
      $tmp2 = explode(')', $tmp[1]);
      $maxlength = 'maxlength=' . $tmp2[0];
      $length_info = "(max $tmp2[0] char)";
    } elseif ($field_type == 'timestamp') {
      $maxlength = 'maxlength=19';
      $length_info = "(format YYYY-MM-DD HH:MM:SS)";
    }
  } elseif (strpos("salt$field_type", 'int')) {
    $type = 'type=number';
    $min = "min=1";
  } elseif ($field_type == 'date') {
    $type = 'type=date';
    $post_value[$field] = date('Y-m-d');
  } elseif ($field_type == 'time') {
    $type = 'type=time';
    $post_value[$field] = date('H:i');
  } else {
    die(alert("Belum ada handler untuk tipe data $field_type"));
  }

  $tb_info = $field == 'nama' ? $tb : '';
  $value = $post_value[$field] ?? null;

  $input_th .= "
    <th>
      <input $required $maxlength class='form-control upper' $type $min name='$field' id='add_$field' placeholder='$field $tb_info' value='$value' />
      <div class='left f10 abu miring normal ml1 mt1 mb2'>$req_info $length_info $info_tambahan</div>
    </th>
  ";
} // end if input type
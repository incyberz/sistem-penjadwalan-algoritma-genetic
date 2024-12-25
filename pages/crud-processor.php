<?php
$post_value = [];
if (isset($_POST['btn_save'])) {
  unset($_POST['btn_save']);
  $koloms = '';
  $isis = '';
  foreach ($_POST as $field => $value) {
    $value = addslashes(strtoupper(strip_tags($value)));
    $koma = $koloms ? ',' : null;
    $koloms .= "$koma$field";
    $isis .= "$koma'$value'";
    $post_value[$field] = $value;
  }

  // exception for tb_ta, id diisi oleh nama
  if ($tb == 'ta') {
    $koloms = "id,$koloms";
    $isis = "$_POST[nama],$isis";
  }

  try {
    // Eksekusi query SQL
    $sql = "INSERT INTO tb_$tb ($koloms) VALUES ($isis)";
    echolog($sql);
    $conn->query($sql);
    alert("Data berhasil disimpan.", 'success');
    jsurl();
  } catch (mysqli_sql_exception $e) {
    // Tangani kesalahan yang terjadi
    alert("Masih terdapat invalid data. <hr>Error: " . $e->getMessage());
  }
}

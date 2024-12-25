<?php
# ============================================================
# GET VARIABLE
# ============================================================
$tb = $_GET['tb'] ?? 'prodi';
echo "<span class=hideit id=tb>$tb</span>";

# ============================================================
# PROCESSORS
# ============================================================
include 'crud-processor.php';

# ============================================================
# GLOBAL VARIABLE
# ============================================================
$required_icon = ' <b style=color:red>*</b> ';
$hide_field = ['id', 'created_at', 'updated_at'];

# ============================================================
# INFO TAMBAHAN UNTUK FIELD
# ============================================================
include 'crud-info_tambahan.php';


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
  include 'crud-input_type_handler.php';
}

# ============================================================
# TAMPIL DATA
# ============================================================
include 'crud-tampil_data.php';

# ============================================================
# ADD DATA
# ============================================================
include 'crud-add_data.php';




























?>
<script>
  $(function() {
    let tb = $('#tb').text();

    $('.btn_delete').click(function() {

      if (!confirm('Hapus data ini?')) return;

      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      if (aksi == 'btn_delete') {
        let url = `pages/crud-delete.php?tb=${tb}&id=${id}`;
        console.log(aksi, id, tb, url);
        $.ajax({
          url: url,
          success: function(response) {
            alert(response);
            // location.reload();
          }
        })
      }
    })
  })
</script>
<?php
# ============================================================
# AUTO-INSERT KURIKULUM JIKA NUM_ROWS != COUNT_TA x COUNT_PRODI
# ============================================================
if ($tb == 'kurikulum') {
  include 'auto_insert_kurikulum.php';
  alert('Data Kurikulum telah auto-inserted sebanyak jumlah prodi x jumlah TA.', 'info');
} else {
  # ============================================================
  # FORM ADD
  # ============================================================
  $no_baru = $num_rows + 1;
  echo "
    <form method=post class='wadah gradasi-toska hideit' id=form_add>
      <h2 class='upper f18 border-bottom pb3 mb2'>Add Data $tb</h2>
      <table class='table'>
        <tr>
          <th><span class='f14 normal miring abu'>*$no_baru</span></th>
          $input_th
          <th>
            <button class='btn-transparan' name=btn_save>$img_save</button>
          </th>
        </tr>
      </table>
    </form>
    <div><span class='pointer btn_aksi' id=form_add__toggle>$img_add Add</span></div>
  ";
}

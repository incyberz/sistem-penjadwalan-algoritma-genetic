<?php
$post_id_shift = $post_id_shift ?? null;
$get_id_shift = $get_id_shift ?? null;
$opt_shift = '';
foreach ($rshift as $id_shift => $arr_shift) {
  $selected = ($post_id_shift == $id_shift || $get_id_shift == $id_shift) ? 'selected' : '';
  $opt_shift .= "<option $selected value=$id_shift>Kelas $arr_shift[nama]</option>";
}

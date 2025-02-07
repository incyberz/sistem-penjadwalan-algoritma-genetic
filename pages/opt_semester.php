<?php
$post_semester = $post_semester ?? null;
$get_semester = $get_semester ?? null;
$opt_semester = '';
for ($i = 1; $i <= 8; $i++) {
  if (($is_ganjil and $i % 2 != 0) || (!$is_ganjil and $i % 2 == 0)) {
    $selected = ($post_semester == $i || $get_semester == $i) ? 'selected' : '';
    $opt_semester .= "<option $selected value=$i>Semester $i</option>";
  }
}

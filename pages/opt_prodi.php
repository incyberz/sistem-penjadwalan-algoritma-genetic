<?php
$post_id_prodi = $post_id_prodi ?? null;
$get_id_prodi = $get_id_prodi ?? null;
$opt_prodi = '';
$s = "SELECT id,jenjang,singkatan FROM tb_prodi ORDER BY jenjang, singkatan";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $selected = ($post_id_prodi == $d['id'] || $get_id_prodi == $d['id']) ? 'selected' : '';
  $opt_prodi .= "<option $selected value=$d[id]>Prodi: $d[jenjang]-$d[singkatan]</option>";
}

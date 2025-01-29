<?php
$post_angkatan = $post_angkatan ?? null;
$get_angkatan = $get_angkatan ?? null;
$opt_angkatan = '';
for ($angkatan = 2020; $angkatan <= $tahun_ini; $angkatan++) {
  $selected = ($post_angkatan == $angkatan || $get_angkatan == $angkatan) ? 'selected' : '';
  $opt_angkatan .= "<option $selected value=$angkatan>Angkatan $angkatan</option>";
}

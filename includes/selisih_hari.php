<?php
function selisih_hari($tanggal_awal, $tanggal_sekarang = null)
{
  $tanggal_awal = new DateTime($tanggal_awal);
  $tanggal_sekarang = $tanggal_sekarang ?? new DateTime(); // Tanggal hari ini

  $selisih_hari = $tanggal_awal->diff($tanggal_sekarang)->days;
  return $selisih_hari;
}

<?php
function namaLengkapDosen($nama, $gelar_depan = null, $gelar_belakang = null, $ucwords = true)
{
  // Ubah nama menjadi huruf kapital setiap kata jika diminta
  if ($ucwords) {
    $nama = ucwords(strtolower($nama));
  }

  // Siapkan nama lengkap
  $namaLengkapDosen = '';

  // Tambahkan gelar depan jika ada
  if (!empty($gelar_depan)) {
    $namaLengkapDosen .= $gelar_depan . ' ';
  }

  // Tambahkan nama
  $namaLengkapDosen .= $nama;

  // Tambahkan gelar belakang jika ada
  if (!empty($gelar_belakang)) {
    $namaLengkapDosen .= ', ' . $gelar_belakang;
  }

  return $namaLengkapDosen;
}

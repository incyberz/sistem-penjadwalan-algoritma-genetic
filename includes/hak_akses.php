<?php
function hak_akses($fitur, $role)
{
  $role = strtolower($role);
  $rhak = [
    'delete_jadwal' => [
      'akd' => 1,
    ],
    'verifikasi_whatsapp' => [
      'akd' => 1,
    ],
  ];

  $punya_hak = $rhak[strtolower($fitur)][strtolower($role)] ?? null;
  return $punya_hak ? true : false;
}

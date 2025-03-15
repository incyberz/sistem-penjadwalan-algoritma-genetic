<?php
function badge_prodi($prodi)
{
  return "<span class='badge-prodi badge-prodi-$prodi'>$prodi</span>";
}

function dosen_only()
{
  if ($_SESSION['jadwal_role'] != 'DSN') {
    echo '<pre><b style=color:red>Fitur ini hanya dapat diakses dosen.</b></pre>';
    exit;
  }
}

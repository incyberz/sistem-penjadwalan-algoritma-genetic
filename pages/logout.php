<?php
unset($_SESSION['jadwal_username']);
unset($_SESSION['jadwal_role']);
unset($_SESSION['fakultas']);
include 'unset_session_akd.php';
if ($_SESSION) {
  echo '<pre>';
  var_dump($_SESSION);
  echo '<b style=color:red>DEBUGING: masih ada data SESSION yang belum clear</b></pre>';
  exit;
}
jsurl('?');

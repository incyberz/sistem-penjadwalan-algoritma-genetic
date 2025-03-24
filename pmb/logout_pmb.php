<?php
unset($_SESSION['pmb_username']);
if ($_SESSION) {
  echo '<pre>';
  var_dump($_SESSION);
  echo '<b style=color:red>DEBUGING: masih ada data SESSION yang belum clear</b></pre>';
  exit;
}
jsurl('?');

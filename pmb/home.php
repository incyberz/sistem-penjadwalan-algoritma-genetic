<?php
include "../conn.php";
$p = '';
foreach ($_GET as $key => $value) {
  $p = $key;
  break;
}

if ($p) {
  include "$p.php";
} else {
  include "welcome.php";
}

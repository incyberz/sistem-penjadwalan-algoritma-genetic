<?php
function petugas_only()
{
  $username = $_SESSION['pmb_username'] ?? null;
  $role = $_SESSION['pmb_role'] ?? null;

  if (!($username and strtolower($role) == 'petugas')) {
    unset($_SESSION['pmb_username']);
    echo "<b style=color:red>Maaf, page ini hanya dapat diakses dengan role [adm].</b>";
    jsurl('./?', 5000);
    exit;
  }
}

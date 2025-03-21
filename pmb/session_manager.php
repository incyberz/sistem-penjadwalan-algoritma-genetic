<?php
function adm_only()
{
  $username = $_SESSION['pmb_username'] ?? null;
  $role = $_SESSION['pmb_role'] ?? null;
  if (!($username and strtolower($role) == 'adm')) {
    die("<b style=color:red>Maaf, page ini hanya dapat diakses dengan role [adm].</b>");
  }
}

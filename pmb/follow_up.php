<?php
petugas_only();

$get_at = $_GET['at'] ?? kosong('at'); // wajib pilih
$get_username = $_GET['username'] ?? null; // boleh list all username
set_title("Follow Up - $get_at - $get_username");

include 'follow_up-process.php';
include 'petugas-dashboard-stuck.php';

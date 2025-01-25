<?php
# ============================================================
# GET VARIABLE
# ============================================================
$tb = $_GET['tb'] ?? 'prodi';
echo "<span class=hideit id=tb>$tb</span>";

set_h2("Verifikasi $tb");


alert("Belum ada informasi untuk verifikasi [$tb]<hr>This page on development.");

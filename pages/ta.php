<?php
$s = "SELECT * FROM tb_ta WHERE id = $ta_aktif";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$ta = mysqli_fetch_assoc($q);
$senin_pertama = $ta['senin_pertama'];
if (date('w', strtotime($senin_pertama)) != 1) die(alert("Weekday Senin Pertama harus bernilai 1 (hari Senin)."));

# ============================================================
# ARRAY HARI + AVAILABLE WEEKDAY UNTUK AWAL PENJADWALAN
# ============================================================
$weekday_start = $ta['weekday_start'] ?? 1; // senin
$weekday_end = $ta['weekday_end'] ?? 5; // jumat
$rhari = [];
for ($i = $weekday_start; $i <= $weekday_end; $i++) {
  $jeda = $i - 1;
  $date = date('Y-m-d', strtotime("+$jeda day", strtotime($senin_pertama)));
  $rhari[$date] = [
    'weekday' => date('w', strtotime($date)),
    'tanggal' => date('d', strtotime($date)),
    'bulan' => date('m', strtotime($date)),
    'tahun' => date('Y', strtotime($date))
  ];
}

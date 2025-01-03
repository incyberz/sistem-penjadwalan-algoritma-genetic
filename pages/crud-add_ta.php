<?php
# ============================================================
# AUTOMATIC ADD TA
# ============================================================
$s = "SELECT id FROM tb_ta";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_ta = [];
while ($d = mysqli_fetch_assoc($q)) {
  array_push($arr_ta, $d['id']);
}

for ($i = 2024; $i <= $max_ta; $i++) {
  $j = $i + 1; // tahun depan
  $arr_gg = [
    $i . '1' => [
      'awal' => "$i-9-1", // awal sep tahun ini
      'akhir' => "$j-3-1", // awal maret tahun depan
    ],
    $i . '2' => [
      'awal' => "$j-3-1",
      'akhir' => "$j-8-1",
    ]
  ];
  foreach ($arr_gg as $ta_gg => $arr) {
    if (!in_array($ta_gg, $arr_ta)) {
      $s = "INSERT INTO tb_ta (
        id, 
        nama, 
        awal, 
        akhir
      ) VALUES (
        $ta_gg,
        $ta_gg,
        '$arr[awal]',
        '$arr[akhir]'
      )";

      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  }
}
alert("Data TA telah auto-inserted hingga TA $max_ta-2.", 'info');

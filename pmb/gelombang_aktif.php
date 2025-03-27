<?php
$s = "SELECT * FROM tb_gelombang WHERE tahun_pmb=$tahun_pmb AND batas_akhir >= '$today' ORDER BY batas_akhir LIMIT 1";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  alert("Tidak ada Gelombang Pendaftaran yang aktif untuk saat ini. Segera lapor Petugas!");
  exit;
} else {
  $gelombang = mysqli_fetch_assoc($q);
  $batas_akhir_show = date('d M Y', strtotime($gelombang['batas_akhir']));
  $eta_gelombang = eta2($gelombang['batas_akhir']);
}

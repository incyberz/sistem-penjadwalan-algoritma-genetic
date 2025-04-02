<?php
$s = "SELECT * FROM tb_gelombang a 
JOIN tb_tahun_pmb b ON a.tahun_pmb=b.tahun_pmb
WHERE a.tahun_pmb=$tahun_pmb AND a.batas_akhir >= '$today' 
ORDER BY a.batas_akhir LIMIT 1";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  alert("Tidak ada Gelombang Pendaftaran yang aktif untuk saat ini. Segera lapor Petugas!");
  exit;
} else {
  $gelombang = mysqli_fetch_assoc($q);
  $batas_akhir_show = date('d M Y', strtotime($gelombang['batas_akhir']));
  $eta_gelombang = eta2(date('Y-m-d', strtotime('+1 day', strtotime($gelombang['batas_akhir']))));
  $gelombang_aktif = $gelombang['nomor'];
}

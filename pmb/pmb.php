<?php
if (!$username) jsurl('./?login_pmb');
$s = "SELECT 
a.*,
b.nama as nama_pendaftar,
b.whatsapp,
b.jeda_tahun_lulus,
b.active_status,
b.whatsapp_status,
c.*,
(SELECT nama FROM tb_prodi WHERE id=a.id_prodi) prodi_terpilih,
(SELECT nama_jalur FROM tb_jalur WHERE id=a.id_jalur) jalur_terpilih,
(
  SELECT COUNT(1) FROM tb_hasil_tes a 
  JOIN tb_tes_pmb b ON a.id_tes=b.id 
  WHERE a.username = '$username' 
  AND b.tahun_pmb = $tahun_pmb) pernah_tes

FROM tb_pmb a 
JOIN tb_akun b ON a.username=b.username 
JOIN tb_tahun_pmb c ON b.tahun_pmb=c.tahun_pmb 
WHERE a.username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $pmb = mysqli_fetch_assoc($q);
} else {
  $s = "INSERT INTO tb_pmb (username) VALUES ('$username')";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

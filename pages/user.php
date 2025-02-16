<?php
$s = "SELECT * FROM tb_user WHERE username = '$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$user = mysqli_fetch_assoc($q);
if (!$user) {
  // unset($_SESSION['jadwal_username']);
  // die("User [$username] tidak ada.");
}
$id_user = $user['id'];
$role = $user['role'];
echo "<span class=hideit id=id_user>$id_user</span>";
echo "<span class=hideit id=role>$role</span>";

if ($role == 'AKD') {
  $s = "SELECT a.*,
  b.gender,
  b.whatsapp,
  b.image 
  FROM tb_petugas a 
  JOIN tb_user b ON a.id_user=b.id 
  WHERE a.id_user=$id_user";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    echo ("<div class='wadah gradasi-kuning p2 m3 tengah red'>Data Petugas belum ada</div>");
    // include 'register-as_petugas.php'; // akun ada, data petugas belom
    exit;

    die(alert('Data User Petugas tidak ditemukan'));
  }
  $petugas = mysqli_fetch_assoc($q);
  $id_petugas = $petugas['id'];
  $user['nama'] = $petugas['nama'];
} elseif ($role == 'DSN') {
  $s = "SELECT a.*,
  b.gender,
  b.whatsapp,
  b.image,
  (SELECT id FROM tb_st WHERE id_dosen=a.id AND id_ta=$ta_aktif) id_st 
  FROM tb_dosen a 
  JOIN tb_user b ON a.id_user=b.id 
  WHERE a.id_user=$id_user";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    echo ("<div class='wadah gradasi-kuning p2 m3 tengah red'>Data Dosen belum ada</div>");
    include 'register-as_dosen.php'; // akun ada, data dosen belom
    exit;

    die(alert('Data User Dosen tidak ditemukan'));
  }
  $dosen = mysqli_fetch_assoc($q);
  $id_dosen = $dosen['id'];
  $user['nama'] = $dosen['nama'];
} elseif ($role == 'MHS') {
  $s = "SELECT a.*,
  b.gender,
  b.whatsapp,
  b.image,
  c.singkatan as prodi,
  c.jumlah_semester,
  d.nama as shift,
  (
    SELECT p.id FROM tb_kelas p 
    JOIN tb_peserta_kelas q ON p.id=q.id_kelas 
    WHERE p.id_ta = $ta_aktif 
    AND q.id_mhs=a.id) id_kelas, 
  (
    SELECT p.nama FROM tb_kelas p 
    JOIN tb_peserta_kelas q ON p.id=q.id_kelas 
    WHERE p.id_ta = $ta_aktif 
    AND q.id_mhs=a.id) kelas 
  FROM tb_mhs a 
  JOIN tb_user b ON a.id_user=b.id 
  JOIN tb_prodi c ON a.id_prodi=c.id 
  JOIN tb_shift d ON a.id_shift=d.id 
  WHERE a.id_user=$id_user";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    echo ("<div class='wadah gradasi-kuning p2 m3 tengah red'>Data Mhs belum ada</div>");
    include 'register-as_mhs.php'; // akun ada, data mhs belom
    exit;
  } else {
    $mhs = mysqli_fetch_assoc($q);
    $id_mhs = $mhs['id'];
    $angkatan = $mhs['angkatan'];
    $user['nama'] = $mhs['nama'];
  }
}

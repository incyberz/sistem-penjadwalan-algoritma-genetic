<?php
session_start();
$username = $_SESSION['pmb_username'] ?? 'username undefined.';
$keyword = $_GET['keyword'] ?? 'keyword undefined.';
if ($keyword === '') die('keyword is empty.');
include '../conn.php';
$s = "SELECT id,nama_sekolah FROM tb_sekolah WHERE nama_sekolah LIKE '%$keyword%' ORDER BY nama_sekolah";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$o = '';
if (!mysqli_num_rows($q)) {
  die("
    <div class='alert alert-danger f14 tengah'>Sekolah dengan keyword \"<b class=consolas>$keyword</b>\" tidak ditemukan.</div>
    <a href='./?daftar&step=4&id_sekolah=new' class='btn btn-primary w-100'>Enter Sekolah Baru</a>
  ");
} else {
  while ($d = mysqli_fetch_assoc($q)) {
    $o .= "
      <div>
        <a class='btn btn-primary w-100 mb2' href='./?daftar&step=4&id_sekolah=$d[id]'>$d[nama_sekolah]</a>
      </div>
    ";
  }
  echo $o;
}

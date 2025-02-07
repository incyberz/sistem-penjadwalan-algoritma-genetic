<?php
$last_aksi = $_GET['last_aksi'] ?? null;

$session_tb = $_SESSION['tb'] ?? null;
$get_tb = $_GET['tb'] ?? null;
echo "<i id=get_tb class=hideit>$get_tb</i>";
if ($get_tb) echo "<script>document.cookie='tb=$get_tb'</script>";

$session_id_fakultas = $_SESSION['id_fakultas'] ?? null;
$get_id_fakultas = $_GET['id_fakultas'] ?? null;
echo "<i id=get_id_fakultas class=hideit>$get_id_fakultas</i>";
if ($get_id_fakultas) echo "<script>document.cookie='id_fakultas=$get_id_fakultas'</script>";

$session_id_prodi = $_SESSION['id_prodi'] ?? null;
$get_id_prodi = $_GET['id_prodi'] ?? null;
echo "<i id=get_id_prodi class=hideit>$get_id_prodi</i>";
if ($get_id_prodi) echo "<script>document.cookie='id_prodi=$get_id_prodi'</script>";

$session_id_shift = $_SESSION['id_shift'] ?? null;
$get_id_shift = $_GET['id_shift'] ?? null;
echo "<i id=get_id_shift class=hideit>$get_id_shift</i>";
if ($get_id_shift) echo "<script>document.cookie='id_shift=$get_id_shift'</script>";

$session_semester = $_SESSION['semester'] ?? null;
$get_semester = $_GET['semester'] ?? null;
echo "<i id=get_semester class=hideit>$get_semester</i>";
if ($get_semester) echo "<script>document.cookie='semester=$get_semester'</script>";

$session_id_kelas = $_SESSION['id_kelas'] ?? null;
$get_id_kelas = $_GET['id_kelas'] ?? null;
echo "<i id=get_id_kelas class=hideit>$get_id_kelas</i>";
if ($get_id_kelas) echo "<script>document.cookie='id_kelas=$get_id_kelas'</script>";

function get_cookies()
{
  echo "<script>get_cookies();</script>";
}
?>
<script>
  let tb;
  let id_kelas;
  let id_prodi;
  let id_shift;
  let id_fakultas;

  function get_cookies() {
    let rc = document.cookie.split(';');
    rc.forEach((v) => {
      let t = v.trim().split('=');
      t.forEach((v2) => {
        if (v2 == 'tb') tb = t[1];
        if (v2 == 'id_kelas') id_kelas = t[1];
        if (v2 == 'id_prodi') id_prodi = t[1];
        if (v2 == 'id_shift') id_shift = t[1];
        if (v2 == 'id_fakultas') id_fakultas = t[1];
      });
    });
    console.log(`tb:${tb}\nid_prodi:${id_prodi}\nid_shift:${id_shift}\nid_fakultas:${id_fakultas}\nid_kelas:${id_kelas}\n`);

  }

  $(function() {
    $('.ondev').click(function() {
      alert(`Fitur ini masih dalam tahap pengembangan. Terimakasih sudah mencoba!\n\n\ninfo lanjut: silahkan hubungi developer!`)
    })
  })
</script>
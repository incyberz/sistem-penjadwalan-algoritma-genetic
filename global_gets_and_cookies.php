<?php
$last_aksi = $_GET['last_aksi'] ?? null;

# ============================================================
# TABEL
# ============================================================
$session_tb = $_SESSION['tb'] ?? null;
$get_tb = $_GET['tb'] ?? null;
echo "<i id=get_tb class=hideit>$get_tb</i>";
if ($get_tb) echo "<script>document.cookie='tb=$get_tb'</script>";

# ============================================================
# FAKULTAS
# ============================================================
$session_fakultas = $_SESSION['fakultas'] ?? $default_fakultas;
$get_fakultas = $_GET['fakultas'] ?? $default_fakultas;
echo "<i id=get_fakultas class=hideit>$get_fakultas</i>";
if ($get_fakultas) echo "<script>document.cookie='fakultas=$get_fakultas'</script>";

# ============================================================
# PRODI
# ============================================================
$session_id_prodi = $_SESSION['id_prodi'] ?? null;
$get_id_prodi = $_GET['id_prodi'] ?? null;
echo "<i id=get_id_prodi class=hideit>$get_id_prodi</i>";
if ($get_id_prodi) echo "<script>document.cookie='id_prodi=$get_id_prodi'</script>";

# ============================================================
# SHIFT
# ============================================================
$session_id_shift = $_SESSION['id_shift'] ?? null;
$get_id_shift = $_GET['id_shift'] ?? null;
echo "<i id=get_id_shift class=hideit>$get_id_shift</i>";
if ($get_id_shift) echo "<script>document.cookie='id_shift=$get_id_shift'</script>";

# ============================================================
# SEMESTER
# ============================================================
$session_semester = $_SESSION['semester'] ?? null;
$get_semester = $_GET['semester'] ?? null;
echo "<i id=get_semester class=hideit>$get_semester</i>";
if ($get_semester) echo "<script>document.cookie='semester=$get_semester'</script>";

# ============================================================
# COUNTER
# ============================================================
$session_counter = $_SESSION['counter'] ?? null;
$get_counter = $_GET['counter'] ?? null;
echo "<i id=get_counter class=hideit>$get_counter</i>";
if ($get_counter) echo "<script>document.cookie='counter=$get_counter'</script>";

# ============================================================
# KELAS
# ============================================================
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
  let fakultas;

  function get_cookies() {
    let rc = document.cookie.split(';');
    rc.forEach((v) => {
      let t = v.trim().split('=');
      t.forEach((v2) => {
        if (v2 == 'tb') tb = t[1];
        if (v2 == 'id_kelas') id_kelas = t[1];
        if (v2 == 'id_prodi') id_prodi = t[1];
        if (v2 == 'id_shift') id_shift = t[1];
        if (v2 == 'fakultas') fakultas = t[1];
      });
    });
    console.log(`tb:${tb}\nid_prodi:${id_prodi}\nid_shift:${id_shift}\nfakultas:${fakultas}\nid_kelas:${id_kelas}\n`);

  }

  $(function() {
    $('.ondev').click(function() {
      alert(`Fitur ini masih dalam tahap pengembangan. Terimakasih sudah mencoba!\n\n\ninfo lanjut: silahkan hubungi developer!`)
    })
  })
</script>
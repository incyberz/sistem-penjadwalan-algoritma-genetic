<?php
# ============================================================
# KALKULASI ANGKATAN
# ============================================================
if ($kelas['count_peserta']) {
  $status_syarat = "<b>Jumlah Peserta:</b> $kelas[count_peserta] Mhs $img_check <span class=btn_aksi id=blok_manage_peserta__toggle>$img_edit</span>";
  $hideit = $last_aksi == 'manage_peserta' ? '' : 'hideit';
} else {
  $hideit = '';
}
$count_peserta = $kelas['count_peserta'] ?? $kelas['nama'];

# ============================================================
# CHECK MHS SESUAI ANGKATAN
# ============================================================
$s = "SELECT a.id,a.nim,a.nama,
(
  SELECT 1 FROM tb_peserta_kelas 
  WHERE id_kelas=$kelas[id] 
  AND id_mhs=a.id) terdaftar 
FROM tb_mhs a 
WHERE a.id_prodi = $kelas[id_prodi] 
AND a.id_shift = '$kelas[id_shift]' 
AND a.angkatan = $angkatan 
AND (a.status > 0 or status is null)
ORDER BY a.nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$checks_mhs = '';
if (mysqli_num_rows($q)) {
  $opt_mhs = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $nim = $d['nim'] ? " - $d[nim]" : ' - <i class="f12 abu">No-NIM</i>';
    $checked = $d['terdaftar'] ? 'checked disabled' : '';
    $checks_mhs .= "
      <label class=label_check_mhs>
        <input type=checkbox name=id_mhs[$d[id]] value=$d[id] $checked> 
        $d[nama] $nim
      </label>
    ";
    $opt_mhs .= !$checked ? '' : "<option value=$d[id]>$d[nama] $nim</option>";
  }
  $checks_mhs .= "<button class='btn btn-info mt2' name=btn_assign>Assign</button>";
} else {
  $checks_mhs = div_alert('danger mt2', "Belum ada mhs prodi [ $kelas[prodi] ] kelas [ $kelas[id_shift] ] ");
}

if (!$count_peserta) {
  $list_peserta = "<div class='alert alert-danger'>Belum ada peserta di kelas ini.";
} else {
  # ============================================================
  # LIST PESERTA KELAS 
  # ============================================================
  $s = "SELECT a.id,a.nim,a.nama FROM tb_mhs a 
  JOIN tb_peserta_kelas b ON a.id=b.id_mhs 
  WHERE b.id_kelas = $kelas[id] ORDER BY a.nama";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $list_peserta = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $nim = $d['nim'] ? " - $d[nim]" : ' - <i class="f12 abu">No-NIM</i>';
    $list_peserta .= "
    <div class='flexy flex-between border-top pt1'>
      <div>
        $i. $d[nama] $nim
      </div>
      <div>
        <button class='btn btn-danger btn-sm mb1' name=btn_drop value=$d[id] onclick='return confirm(`Drop dari kelas ini?`)'>Drop</button>
      </div>
    </div>
  ";
  }
}

$input_syarat = "

  <div class='$hideit' id=blok_manage_peserta>
    <div class='row'>
      <div class=col-6>
        <form method=post class='wadah gradasi-kuning mt2'>
          <div><b>Assign Peserta prodi $kelas[prodi] angkatan $angkatan:</b></div>
          $checks_mhs
        </form>
      </div>
      <div class=col-6>
        <form method=post class='wadah gradasi-kuning mt2'>
          <div class=mb2><b>List Peserta kelas $kelas[nama]:</b></div>
          <div>
            $list_peserta
          </div>
        </form>
        <form method=post class=' wadah gradasi-kuning mt2'>
          <div><b>Add Mhs [ $kelas[id_shift] ] semester $kelas[semester] (angkatan $angkatan):</b></div>
          <div class='mt2 mb2'>
            <input class='form-control upper' name=nama_mhs_baru id=nama_mhs_baru required minlength=3 maxlength=50 placeholder='Nama Mhs ...'>
          </div>
          <button class='btn btn-success' name=btn_add>Add</button>
        </form>
        <script>
          $(function() {
            $('#nama_mhs_baru').keypress(function(e) {
              let charCode = String.fromCharCode(e.which);
              if (!charCode.match(/^[a-zA-Z ]$/i)) {
                return false;
              }
            })
          })
        </script>
      </div>
    </div>
  </div>
";

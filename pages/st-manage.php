<?php
$arr = explode('-', $id_st);
$id_ta = $arr[0];
$id_dosen = $arr[1];
if ($id_ta < 1 || $id_dosen < 1) die("Invalid value: id_ta: $id_ta, id_dosen: $id_dosen, ");


# ============================================================
# ST-MANAGE-PROCESSORS
# ============================================================
include 'st-manage-processors.php';

# ============================================================
# ST PROPERTIES 
# ============================================================
$s = "SELECT a.*,
b.id as id_dosen,
b.nama as nama_dosen,
b.nidn,
f.fakultas,
(
  SELECT nama FROM tb_prodi WHERE id=b.id_prodi) homebase,
(SELECT nama FROM tb_user WHERE id=a.id_user) verifikator
FROM tb_st a 
JOIN tb_dosen b ON a.id_dosen=b.id
JOIN tb_st_detail c ON a.id=c.id_st
JOIN tb_kumk d ON c.id_kumk=d.id
JOIN tb_kurikulum e ON d.id_kurikulum=e.id
JOIN tb_prodi f ON e.id_prodi=f.id
WHERE a.id = '$id_st'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$st = mysqli_fetch_assoc($q);
if (!$st) die(alert("Data Surat Tugas dengan id [$id_st] tidak ditemukan. | <a href='?st'>Back to Rekap Surat Tugas</a>"));
$verified = $st['verif_date'] ? 1 : 0;

if ($print) {

  if (!$st['verif_date']) die("Field [verif_date] is null.");
  if (!$st['verif_by']) die("Field [verif_by] is null.");

  $tahun = date('Y', strtotime($st['verif_date']));
  $num_digit = 4;
  $info_fakultas = $st['fakultas'];
  $info_kampus = 'E-UM';

  $rbulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
  $bulan_romawi = $rbulan_romawi[intval(date('m', strtotime($st['verif_date']))) - 1];

  if ($st['no_st']) {
    $no_st_zerofill = sprintf('%0' . $num_digit . 'd', $st['no_st']);
    $nomor_st = "$no_st_zerofill/$info_fakultas/$info_kampus/$bulan_romawi/$tahun";
  } else {

    # ============================================================
    # NEW NO SURAT TUGAS
    # ============================================================
    include 'st-manage-autocreate_no_st.php';

    exit;
  }
}


# ============================================================
# MAIN SELECT
# ============================================================
$s = "SELECT a.* ,
b.id_kelas,
b.id as id_st_detail,
d.id as id_kumk,
e.id as id_mk,
e.nama as nama_mk,
e.semester,
e.sks,
g.singkatan as prodi,
g.id as id_prodi,
h.nama as kelas,
i.id as shift,
(
  SELECT COUNT(1) FROM tb_jadwal p 
  WHERE p.id=b.id) count_jadwal  
FROM tb_st a 
JOIN tb_st_detail b ON a.id=b.id_st 
-- JOIN tb_dosen c ON a.id_dosen=c.id 
JOIN tb_kumk d ON d.id=b.id_kumk
JOIN tb_mk e ON d.id_mk=e.id
JOIN tb_kurikulum f ON d.id_kurikulum=f.id
JOIN tb_prodi g ON f.id_prodi=g.id
JOIN tb_kelas h ON b.id_kelas=h.id
JOIN tb_shift i ON h.id_shift=i.id
-- JOIN tb_prodi h ON c.id_prodi=h.id
WHERE a.id = '$id_st' 
ORDER BY 
  e.no, 
  e.nama, 
  i.id DESC, -- shift
  f.id, 
  e.semester 
";

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$num_rows = mysqli_num_rows($q);
$tr = '';
$i = 0;
$total_sks = 0;
$arr_print = []; // Matakuliah SKS Program SMT KLS Jml_Kelas jml_SKS
$rprogram = [];
$rshift = [];
$rkelas = [];
$sum_sks = 0;
while ($d = mysqli_fetch_assoc($q)) {

  $mk = $d['nama_mk'];

  $i++;
  $total_sks += $d['sks'];

  $btn_delete = '';
  if (!$verified and $role == 'AKD' and !$d['count_jadwal']) {
    $btn_delete = "<a onclick='return confirm(`Drop MK ini?`)' href='?st&aksi=drop_mk&id_st=$id_st&id_st_detail=$d[id_st_detail]'>$img_delete</a>";
  }

  $terjadwal = $d['count_jadwal'] ? "<a href='?jadwal&id_kelas=$d[id_kelas]'>terjadwal</a>" : '';
  // $btn_delete = $d['count_jadwal'] ? $btn_delete_disabled : $btn_delete;

  $tr .= "
    <tr>
      <td>$i</td>
      <td>
        $d[nama_mk]
        $btn_delete
      </td>
      <td>$d[prodi]</td>
      <td>$d[semester]</td>
      <td><span id=sks__$d[id_st_detail]>$d[sks]</span></td>
      <td>$d[shift] - $d[kelas]</td>
      <td>$terjadwal</td>
    </tr>
  ";

  if ($print) {

    if (!isset($rprogram[$mk])) $rprogram[$mk] = [];
    if (!in_array($d['prodi'], $rprogram[$mk])) array_push($rprogram[$mk], $d['prodi']);

    if (!isset($rshift[$mk])) $rshift[$mk] = [];
    if (!in_array($d['shift'], $rshift[$mk])) array_push($rshift[$mk], $d['shift']);

    if (!isset($rkelas[$mk])) $rkelas[$mk] = [];
    if (!in_array($d['kelas'], $rkelas[$mk])) array_push($rkelas[$mk], $d['kelas']);

    $programs = join(', ', $rprogram[$mk]);
    $shifts = join(', ', $rshift[$mk]);
    $kelass = join(', ', $rkelas[$mk]);
    $count_kelas = count($rkelas[$mk]);
    $sum_sks = $d['sks'] * $count_kelas;

    $arr_print[$mk] = [
      'sks' => $d['sks'],
      'programs' => $programs,
      'semester' => $d['semester'],
      'shifts' => $shifts,
      'count_kelas' => $count_kelas,
      'sum_sks' => $sum_sks,
    ];
  }
}



# ============================================================
# BTN VERIF
# ============================================================
$btn_verif = '';
$btn_rollback = '';
$all_users = "<span class='consolas darkred'>all-users | all-roles $img_warning</span> ~ 
          <span class='blue pointer' onclick='alert(`Ubah Rule Verifikasi sedang dalam tahap pengembangan. \n\nSilahkan hubungi developer untuk info lebih lanjut!`)'> Ubah Rule $img_edit</span>";

if ($verified) {
  $tanggal = tanggal($st['verif_date']);
  $eta = eta2($st['verif_date']);
  alert("Info: Surat Tugas Verified by <b>$st[verifikator]</b> tanggal <b>$tanggal</b> <i class=f12>$eta</i>", 'info no_print');

  if ($role == 'AKD' || $role == 'PIM') {
    $btn_rollback = "
      <button class='btn btn-danger w-100' name=btn_rollback_verif value='$id_st'>Rollback Verifikasi</button>
      <div class='mt1 abu miring'>
        Yang berhak Rollback Verifikasi Surat Tugas seharusnya Level Pimpinan (Rektor atau Kaprodi)
        <div class=mt2>Yang berhak Rollback saat ini:</div>
        $all_users
      </div>
    ";
  }
  $btn_print = "
    <a class='btn btn-success w-100 mt2 mb2' href='?st&aksi=manage&id_st=$id_st&print=1' >Print Surat Tugas</a>
    $btn_rollback
  ";

  $btn_verif = "$btn_print";
} else {

  $jabatan = $rrole[$user['role']];
  $tanggal = tanggal();


  $btn_verif = $role != 'AKD' ? "
    <div class='flexy flex-center'>
      <a class='d-block bordered p-2 br5' href='?struktur_kurikulum'>$img_prev Lihat Struktur Kurikulum </a>
      <div class='pointer abu bordered p-2 br5 hover' onclick='alert(`Unverified artinya belum diverifikasi oleh Petugas Akademik\n\n\nUnverified Status memungkinkan Item Surat Tugas Anda bertambah atau berkurang.`)'>
        Surat Tugas Unverified $img_help  
      </div>
    </div>
  " : "
    <!-- ================================================ -->
    <!-- AKD ONLY -->
    <!-- ================================================ -->
    <div class='flexy flex-center'>
      <a class='d-block bordered p-2 br5' href='?struktur_kurikulum'>$img_prev back to Struktur Kurikulum (Add MK)</a>
      <div class='pointer green btn_aksi bordered p-2 br5 hover' id=blok_verifikasi__toggle>
        Verifikasi Surat Tugas  $img_next  
      </div>
    </div>
    <div class='wadah gradasi-hijau mt4 hideit' id=blok_verifikasi>
      <h3>Verifikasi Dokumen Surat Tugas</h3>
      <p class='abu'>
        Verifikator (yang berhak verifikasi Surat Tugas) : $all_users
      </p>
      <p>Diverifikasi oleh:</p>
      <ul>
        <li><b>Petugas:</b> $user[nama]</li>
        <li><b>Jabatan:</b> $jabatan</li>
        <li><b>Di:</b> $lokasi_titimangsa</li>
        <li><b>Tanggal:</b> $tanggal</li>
      </ul>
      <div class=flexy>
        <input type=checkbox required class='d-block' id=cek_verif>
        <label for=cek_verif class='pointer d-block'>Saya menyatakan bahwa Surat Tugas diatas sudah benar</label>
      </div>
      <div class=flexy>
        <input type=checkbox required class='d-block' id=cek_verif2>
        <label for=cek_verif2 class='pointer d-block'>Saya faham bahwa setelah diverifikasi Surat Tugas dapat di print, namun tidak bisa diubah lagi.</label>
      </div>
      <button class='btn btn-success w-100 mt2' name=btn_verifikasi_st value='$id_st'>Verifikasi Surat Tugas</button>
      <div class='f14 abu mt1'>
        Yang dapat rollback verifikasi: $all_users
      </div>

    </div>
  ";
}

$fakultas = 'Fakultas Komputer'; // ZZZ

$menugaskan_kepada = "
  <p>
    Yang bertanda tangan di bawah ini Dekan $fakultas, menugaskan kepada:
  </p>
  <ul id=dosen_selected>
    <li><b>Nama:</b> <span id=nama_dosen_selected>$st[nama_dosen]</span></li>
    <li><b>NIDN:</b> <span id=nidn_dosen_selected>$st[nidn]</span></li>
    <li><b>Homebase:</b> <span id=homebase_dosen_selected>$st[homebase]</span></li>
  </ul>
  <p>Untuk mengampu Mata Kuliah di <b>TA. $tahun_ta $GG</b> sebagai berikut:</p>
";

if (!$print) {

  # ============================================================
  # FINAL ECHO
  # ============================================================
  set_h2("Manage Surat Tugas", "Tahun Ajar $tahun_ta $Gg ");
  set_title("$st[nama_dosen] - Surat Tugas");

  echo "
    $menugaskan_kepada
  
    <form method=post>
      <table class=table>
        <thead>
          <th>No</th>
          <th>MK</th>
          <th>Prodi</th>
          <th>Semester</th>
          <th>SKS</th>
          <th>Kelas</th>
          <th>Terjadwal</th>
        </thead>
        $tr
        <tr class='gradasi-toska bold'>
          <td colspan=4 class=kanan>TOTAL SKS</td>
          <td>$total_sks</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </form>
    <form method=post>
      $btn_verif
    </form>
  ";
} else {
  include 'st-manage-print.php';
}

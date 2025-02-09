<?php
set_h2('Master Kelas', "Kelas Mahasiswa pada Tahun Ajar $tahun_ta $Gg");

if (isset($_POST['btn_delete'])) {
  $t = explode('-', $_POST['btn_delete']);
  $id_prodi = $t[0];
  $id_shift = $t[1];
  $semester = $t[2];

  $s = "DELETE FROM tb_kelas 
  WHERE id_prodi=$id_prodi
  AND semester=$semester
  AND id_shift='$id_shift'
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_tambah']) || isset($_POST['btn_delete'])) {
  $t = explode('-', $_POST['btn_tambah']);
  $id_prodi = $t[0];
  $id_shift = $t[1];
  $semester = $t[2];
  $jumlah_rombel = $_POST['jumlah_rombel'];

  $s = "SELECT * FROM tb_prodi WHERE id=$id_prodi";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $prodi = mysqli_fetch_assoc($q);

  $arr_new_kelas = [];
  if ($jumlah_rombel == 1) {
    $new_kelas = "S1-SI-7-R-A-20241";
    $new_kelas = "$prodi[jenjang]-$prodi[singkatan]-$semester-$id_shift-$ta_aktif";
    $arr_new_kelas[$new_kelas] = null; // counter is null
  } else {
    $ascii = 65; // A
    for ($i = 1; $i <= $jumlah_rombel; $i++) {
      $ascii = 65 + $i - 1;
      $counter = chr($ascii);
      $new_kelas = "$prodi[jenjang]-$prodi[singkatan]-$semester-$id_shift-$counter-$ta_aktif";
      $arr_new_kelas[$new_kelas] = $counter;
    }
  }

  // insert new kelas
  foreach ($arr_new_kelas as $new_kelas => $counter) {
    $counter = $counter ? "'$counter'" : 'NULL';
    $s = "INSERT INTO tb_kelas (
      nama,
      id_prodi,
      id_ta,
      semester,
      id_shift,
      counter
    ) VALUES (
      '$new_kelas',
      $id_prodi,
      $ta_aktif,
      $semester,
      '$id_shift',
      $counter
    )";
    echolog($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }
  jsurl();
}


# ============================================================
# MAIN SELECT TAMPIL PRODI
# ============================================================
$s = "SELECT * FROM tb_prodi ORDER BY fakultas, nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$i = 0;
while ($prodi = mysqli_fetch_assoc($q)) {
  $i++;
  $kelass = '';
  foreach ($rshift as $id_shift => $arr_shift) {
    $shift = $arr_shift['nama'];
    $blok_add = "<div class='green hover tengah'>tambah</div>";

    // cek di tiap semester
    $kelas_smt = '';
    for ($semester = 1; $semester <= $prodi['jumlah_semester']; $semester++) {
      if (($is_ganjil and $semester % 2 != 0) || (!$is_ganjil and $semester % 2 == 0)) {

        $s2 = "SELECT a.id,a.nama,
        (SELECT COUNT(1) FROM tb_st_detail WHERE id_kelas=a.id) jumlah_st_detail 
        FROM tb_kelas a 
        WHERE a.id_ta=$ta_aktif 
        AND a.id_prodi=$prodi[id] 
        AND a.semester=$semester
        AND a.id_shift='$id_shift'
        ";
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
        $input_form = "
          <div class='col-5 red bold'>Belum ada kelas mhs.</div>
          <div class='col-3'>
            <input required type=number min=1 max=10 name=jumlah_rombel placeholder='rombel...' class='form-control form-control-sm'>
          </div>
          <div class='col-3'>
            <button class='btn btn-primary btn-sm mb2 w-100' name=btn_tambah value='$prodi[id]-$id_shift-$semester'>tambah</button>
          </div>
        ";
        $count_rombel = mysqli_num_rows($q2);
        if ($count_rombel) {
          $input_form = '';
          $total_st_detail = 0;
          while ($d2 = mysqli_fetch_assoc($q2)) {
            $total_st_detail += $d2['jumlah_st_detail'];
            $jumlah_st_detail = $d2['jumlah_st_detail'] ? "<a target=_blank href='?st&aksi=cari_st&id_st=all&id_kelas=$d2[id]'>$d2[jumlah_st_detail] ST</a>" : '-';
            $input_form .= "
              <div class='flexy'>
                <div>
                  <a target=_blank href='?verifikasi&tb=kelas&id=$d2[id]'>
                    $d2[nama]
                  </a>
                </div>
                <div>$jumlah_st_detail</div>
              </div>
            ";
          }

          if ($total_st_detail) {
            $btn_delete = "<span class='btn btn-secondary btn-sm mb2 w-100' onclick='alert(`Tidak dapat menghapus kelas ini karena sudah dipakai di $total_st_detail Surat Tugas. Klik pada Jumlah Surat Tugas nya untuk menghapus dahulu sub-data.`)'>delete</span>";
          } else {
            $btn_delete = "<button class='btn btn-warning btn-sm mb2 w-100' name=btn_delete value='$prodi[id]-$id_shift-$semester' onclick='return confirm(`Delete kelas?`)'>delete</button>";
          }

          $input_form = "
            <div class='col-6 green bold'>$input_form</div>
            <div class='col-2 abu miring f12'>$count_rombel rombel</div>
            <div class='col-3'>$btn_delete</div>
          ";
        }


        $kelas_smt .= "
          <form method=post class=' gap-1 border-top pt1'>
            <div class=row>
              <div class='col-1'>SM$semester</div>
              $input_form
            </div>
          </form>
        ";
      } // hanya ganjil | genap saja
    } // end for semester


    // default UI
    $gradasi = $id_shift == 'R' ? 'hijau' : 'kuning';
    $kelass .= "
      <div class=col-6>
        <div class='wadah gradasi-$gradasi'>
          <div class='gradasi-toska tengah p2 bold darkblue'>$prodi[fakultas] - $prodi[singkatan] - $shift</div>
          $kelas_smt
        </div>
      </div>
    ";
    // ZZZ

  }
  $tr .= "
    <tr id=tr__$prodi[id]>
      <td>$i</td>
      <td>
        <div class='row'>
          $kelass
        </div>
      </td>
    </tr>
  ";
}

echo "
  <table class=table>
    <thead>
      <th>No</th>
      <th class=tengah>Kelas mahasiswa</th>
    </thead>
    $tr
  </table>";

<?php
$id_st = $_GET['id_st'] ?? udef('id_st');
$arr = explode('-', $id_st);
$id_ta = $arr[0];
$id_dosen = $arr[1];
if ($id_ta < 1 || $id_dosen < 1) die("Invalid value: id_ta: $id_ta, id_dosen: $id_dosen, ");


# ============================================================
# ST-MANAGE-PROCESSORS
# ============================================================
include 'st_ajar-manage-processors.php';

# ============================================================
# SELECT ST-MK-KELAS IF EXISTS 
# ============================================================
$s = "SELECT id FROM tb_st_mk_kelas WHERE id LIKE '$id_st%'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_id_st_mk_kelas = [];
while ($d = mysqli_fetch_assoc($q)) {
  array_push($arr_id_st_mk_kelas, $d['id']);
}


# ============================================================
# MAIN SELECT
# ============================================================
$s = "SELECT a.* ,
b.id as id_st_mk,
d.id as id_mk,
d.nama as nama_mk,
d.semester,
d.sks,
c.nidn, 
c.id as id_dosen,
c.nama as nama_dosen,
f.singkatan as prodi,
f.id as id_prodi  
FROM tb_st a 
JOIN tb_st_mk b ON a.id=b.id_st 
JOIN tb_dosen c ON a.id_dosen=c.id 
JOIN tb_mk d ON b.id_mk=d.id
JOIN tb_kurikulum e ON d.id_kurikulum=e.id
JOIN tb_prodi f ON e.id_prodi=f.id
WHERE a.id = '$id_st' 
ORDER BY e.id, d.semester 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$num_rows = mysqli_num_rows($q);
$divs = '';
$nama_dosen = '-';
$nidn = '-';
$pernah_save_kelas = '';
$i = 0;
$arr_valid_check = [];
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $id_dosen = $d['id_dosen'];
  $nama_dosen = $d['nama_dosen'];
  $nidn = $d['nidn'];
  $pernah_save_kelas = $d['pernah_save_kelas'];

  $jumlah_check = 0;
  # ============================================================
  # LIST KELAS
  # ============================================================
  $list_kelas = '';
  $pra_unique_check = "$id_ta-$d[id_mk]-"; // TA-MK-

  $sub_select_nama_dosen = "SELECT p.nama FROM tb_dosen p
    JOIN tb_st_mk_kelas q ON p.id=q.id_dosen
    WHERE unique_check = CONCAT('$pra_unique_check',a.id)";
  $sub_select_id_dosen = "SELECT p.id FROM tb_dosen p
    JOIN tb_st_mk_kelas q ON p.id=q.id_dosen
    WHERE unique_check = CONCAT('$pra_unique_check',a.id)";

  # ============================================================
  $s2 = "SELECT a.*,
  ($sub_select_id_dosen) id_dosen_pengampu,
  ($sub_select_nama_dosen) dosen_pengampu 
  FROM tb_kelas a
  WHERE a.semester='$d[semester]' 
  AND a.id_prodi = $d[id_prodi]
  ";
  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  if (mysqli_num_rows($q2)) {
    while ($d2 = mysqli_fetch_assoc($q2)) {
      $id_st_mk_kelas = "$id_st-$d[id_mk]-$d2[id]";
      $checked = in_array($id_st_mk_kelas, $arr_id_st_mk_kelas) ? 'checked' : '';
      if ($checked) {
        $jumlah_check++;
        $arr_valid_check[$d['id_st_mk']] = 1;
      }

      if ($d2['id_dosen_pengampu'] and $d2['id_dosen_pengampu'] != $d['id_dosen']) {
        $disabled = 'disabled';
        $dosen_pengampu = " | <a class='darkred miring' href='?st_ajar&id_kurikulum=$id_kurikulum&aksi=manage&id_st=$id_ta-$d2[id_dosen_pengampu]' target=_blank onclick='return confirm(`Buka Surat Tugas untuk dosen ini?`)'>$d2[dosen_pengampu]</a>";
        $miring_abu = 'miring abu f12';
      } else {
        $disabled = '';
        $dosen_pengampu = '';
        $miring_abu = '';
      }

      $list_kelas .= "
        <div>
          <label class='$miring_abu'>
            <input class=kelas type=checkbox id=kelas__$d2[id]__$d[id_st_mk] name='$d[id_st_mk]-$d2[id]'  value='$d[id_st_mk]__$d2[id]' $checked $disabled /> $d2[nama] $dosen_pengampu
          </label>
        </div>
      ";
    }
  } else {
    $pesan_error = "Belum ada satupun kelas pada prodi [$d[prodi]]";
    $list_kelas = "<b class=red>$pesan_error</b> | <a href='?crud&tb=kelas'>Add</a>";
  }
  # ============================================================
  # END LIST KELAS


  $gradasi = $jumlah_check ? 'hijau' : 'merah';

  $btn_delete = $jumlah_check ? "<span onclick='alert(`Uncheck dahulu agar bisa di drop.`)' >$img_delete_disabled</span>" : "<a onclick='return confirm(`Drop MK ini?`)' href='?st_ajar&id_kurikulum=$id_kurikulum&aksi=drop_mk&id_st=$id_st&id_mk=$d[id_mk]'>$img_delete</a>";

  $divs .= "
  <div class='border-top p1 f14 gradasi-$gradasi'>
    <div class=row>
      <div class='col-md-4'>
        <div class=row>
          <div class=col-1>
            $i.
          </div>
          <div class=col-10>
            $d[nama_mk]
          </div>
        </div>
      </div>
      <div class='col-md-4 f12'>
        <div class=row>
          <div class=col-2>
            <div class=p1>
              $btn_delete
            </div>
          </div>
          <div class=col-10>
            <div><b>Prodi:</b> $d[prodi]</div>
            <div><b>Semester:</b> $d[semester]</div>
            <div><b>SKS:</b> <span id=sks__$d[id_st_mk]>$d[sks]</span></div>
          </div>
        </div>
      </div>
      <div class='col-md-4'>
        <div class='f12 bold'>Untuk kelas:</div>
        <div class='mt1 mb2'>
          $list_kelas
        </div>
      </div>
    </div>
  </div>
  ";
}

# ============================================================
# BTN SIMPAN
# ============================================================
$btn_simpan = $pesan_error ? "<div class='alert alert-danger'>Belum bisa simpan.</div>" : "<div id=div_btn class=hideit><button class='btn btn-primary w-100' name=btn_simpan_st>Simpan Surat Tugas</button></div>";

# ============================================================
# BTN VERIF
# ============================================================
$count = count($arr_valid_check);
$valid_info = "<i class=red>$count of $num_rows valid rows</i>";
$btn_verif = '';
if ($pernah_save_kelas) {
  $btn_verif = "<div class='f12 red mt4'>belum bisa verifikasi Surat Tugas. | $valid_info</div>";
  if ($count == $num_rows) {
    $btn_verif = "<div class='f12 green mt4'>ALL ROWS VALID $img_check</div>";
  }
}

# ============================================================
# FINAL ECHO
# ============================================================
set_h2("Manage Surat Tugas", "Tahun Ajar $tahun_ta $Gg ");
set_title("$nama_dosen - Surat Tugas");

echo "
  <p>
    Yang bertanda tangan di bawah ini Dekan Fakultas Komputer, menugaskan kepada:
  </p>
  <ul id=dosen_selected>
    <li><b>Nama:</b> <span id=nama_dosen_selected>$nama_dosen</span></li>
    <li><b>NIDN:</b> <span id=nidn_dosen_selected>$nidn</span></li>
  </ul>

  $untuk_mengampu

  <div class='row p1'>
    <div class='offset-md-8 col-md-4'>
      <label>
        <input type=checkbox id=cek_all_kelas /> Check All Kelas
      </label>
    </div>
  </div>

  <form method=post>
    $divs

    <div class='row border-top pt1'>
      <div class='offset-md-8 col-md-4 mt1 mb2'>
        <b class=f12>Total:</b>
        <span class='darkblue f24' id=total_sks>0</span> SKS 
        <input type=hidden id=total_sks_input name=total_sks>
      </div>
    </div>

    <div class='f12 abu mb2'>
      <a href='?st_ajar&id_kurikulum=$id_kurikulum&id_dosen=$id_dosen'>$img_add Tambah MK</a>
    </div>
    $btn_simpan
    $btn_verif
  </form>
";
?>
<script>
  function hitung_sks() {
    let total_sks = 0;
    $('.kelas').each(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_mk = rid[1];
      let id_st_mk = rid[2];
      let sks = parseInt($('#sks__' + id_st_mk).text());
      let checked = $(this).prop('checked');
      let disabled = $(this).prop('disabled');
      // console.log(aksi, id_mk, id_st_mk, sks, checked);
      if (checked && !disabled) total_sks += sks;
      $('#total_sks').text(total_sks);
      $('#total_sks_input').val(total_sks);
    });
    if (total_sks) {
      $('#div_btn').slideDown();
    } else {
      $('#div_btn').slideUp();

    }
  }

  $(function() {
    $('#cek_all_kelas').click(function() {
      let checked = $(this).prop('checked');
      console.log(checked);
      $('.kelas').prop('checked', checked);

      hitung_sks();
    });

    $('.kelas').click(function() {
      hitung_sks();
    });

    hitung_sks(); // form load


  })
</script>
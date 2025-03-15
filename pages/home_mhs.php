<?php

if (isset($_POST['btn_join_kelas'])) {
  $id_kelas = $_POST['btn_join_kelas'] ?? die(udef('[ id_kelas at btn_join_kelas ]'));
  $assign_by = $id_mhs ? $id_mhs : die(udef('id_mhs'));
  $s = "INSERT INTO tb_peserta_kelas (
    id, -- KLS - MHS
    id_kelas,
    id_mhs,
    assign_by
  ) VALUES (
    '$id_kelas-$id_mhs', -- KLS - MHS
    $id_kelas,
    $id_mhs,
    $id_user
  )";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

$kelas_show = $mhs['kelas'] ?? '<i class="bold red">belum punya</i>';

// kalkulasi posisi semester
$my_semester = ($tahun_ta - $angkatan) * 2 + substr($ta_aktif, 4, 1);

$form = '';
$Anda_dapat =  '';
$cocok_info = '';
$jadwal = '';
$info_posisi_smt = '';
if (!$mhs['kelas']) {
  # ============================================================
  # PILIH KELAS AKTIF JIKA BELUM PUNYA
  # ============================================================
  $s = "SELECT * FROM tb_kelas a WHERE a.id_ta = $ta_aktif
  AND semester <= $my_semester -- boleh masuk ke kelas lebih kecil
  AND id_shift = '$mhs[id_shift]' 
  AND id_prodi = $mhs[id_prodi] 
  ORDER BY a.semester DESC
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $cocok = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    if ($ta_aktif % 2 == $d['semester'] % 2) {
      if ($d['semester'] == $my_semester) {
        $cocok = 1;
        $btn_join = "<button class='btn btn-primary w-100' name=btn_join_kelas value='$d[id]'>Join Kelas</button>";
      } else {
        $btn_join = "<button class='btn btn-warning w-100' name=btn_join_kelas value='$d[id]'>Join sebagai Mahasiswa Susulan</button>";
      }

      $form .= "
        <form method=post class='wadah gradasi-toska flexy flex-between'>
          <div>
            <div>$d[nama] - Semester $d[semester]</div>
            <label class='f12 d-block mt1'>
              <input type=checkbox required> Pilihan Kelas saya diatas sudah benar
            </label>
  
          </div>
  
          <div>$btn_join</div>
  
        </form>
      ";
      if ($cocok) break; // tidak bisa Join sebagai Mhs Susulan jika semester cocok
    }
  }


  if (!$cocok and $form) {
    $link_verif = "$nama_server?crud&tb=kelas&note=Belum ada Grup Kelas untuk Prodi $mhs[prodi] $mhs[shift] Semester $my_semester TA. $ta_aktif";

    $text_asal = "```================================\nLAPORAN ERROR\n================================```\n\nYth. Petugas Akademik,\n\nGrup Kelas belum ada untuk:\n- *Prodi* : $mhs[prodi] - $mhs[shift]\n- *Semester* : $my_semester\n\nTerimakasih.\n\nLink:\n$link_verif$text_wa_from";
    $preview = str_replace("\n\n", '<br>.<br>', $text_asal);
    $preview = str_replace("\n", '<br>', $preview);
    $preview = str_replace('```', '', $preview);

    $link_wa = "$https_api_wa?phone=$petugas_default[whatsapp]&text=" . urlencode($text_asal);

    $cocok_info = div_alert('danger', "
      Posisi semester Anda tidak cocok dengan kelas semester yang tersedia. Segera laporkan hal ini ke Petugas agar dibuatkan Grup Kelas yang cocok untuk Anda.
      <ul>
        <li><b>Posisi semester Anda:</b> Semester $my_semester</li>
        <li><b>Kelas seharusnya:</b> $mhs[prodi]-$my_semester-$mhs[id_shift]-$ta_aktif (sesuai jumlah rombel)</li>
      </ul>
    
      <div class='card p-2 wa_preview' >$preview</div>
      <a target=_blank href='$link_wa' class='btn btn-primary w-100 mt-2'>Laporkan ke Petugas</a>
    ");
  }

  $Anda_dapat = $form ? "Anda dapat bergabung ke kelas berikut:" : '';
  $form = $form ? "<div class=wadah>$form</div>" : div_alert('danger', "Maaf, belum ada Grup Kelas tersedia di prodi [$mhs[prodi]] kelas [$mhs[id_shift]]. ");
  $info_posisi_smt = "<p>Di TA $tahun_ta $Gg, angkatan $mhs[angkatan] berada pada semester $my_semester. $Anda_dapat</p>";
} else { // punya grup kelas di TA aktif maka punya jadwal
  $jadwal = "<h3 class=darkblue>Jadwal Kuliah</h3>";

  $my_jadwal = [];
  $s = "SELECT *,
  d.gelar_depan,
  d.gelar_belakang,
  d.nama as nama_dosen,
  f.nama as nama_mk,
  f.sks,
  g.nama as nama_ruang,
  1

  FROM tb_jadwal a 
  JOIN tb_st_detail b ON a.id=b.id 
  JOIN tb_st c ON b.id_st=c.id 
  JOIN tb_dosen d ON c.id_dosen=d.id 
  JOIN tb_kumk e ON b.id_kumk=e.id 
  JOIN tb_mk f ON e.id_mk=f.id 
  JOIN tb_ruang g ON a.id_ruang=g.id 
  WHERE c.id_ta = $ta_aktif 
  AND  b.id_shift = '$mhs[id_shift]'
  AND  b.id_kelas = $mhs[id_kelas]
  ORDER BY a.weekday 
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  $i = 0;
  if (!mysqli_num_rows($q)) {
    $pesan = "Belum ada Jadwal Kuliah untuk kelas [$mhs[kelas]]";
    include 'hubungi.php';
    $jadwal .= div_alert('danger', "$pesan $hubungi");
  } else {
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $hari = $arr_hari[$d['weekday']];
      $mulai = date('H:i', strtotime($d['jam_mulai']));
      $selesai = date('H:i', strtotime($d['jam_selesai']));
      $tr .= "
        <tr id=tr__$d[id]>
          <td>$i</td>
          <td>
            $hari
            <div class='f14 abu miring'>
              $mulai-$selesai
            </div>
          </td>
          <td>
            <a target=_blank href='?detail&tb=mk&id=$d[id_mk]'>$d[nama_mk]</a>
            <div>
              <a target=_blank href='?detail&tb=dosen&id=$d[id_dosen]'>
                <span class='f14 abu miring'>$d[gelar_depan] $d[nama_dosen], $d[gelar_belakang]</span>
              </a>
            </div>
          </td>
          <td><a target=_blank href='?detail&tb=ruang&id=$d[id_ruang]'>$d[nama_ruang]</a></td>
        </tr>
      ";
    }

    $jadwal .= "
      <table class=table>
        <thead>
          <th>No</th>
          <th>Hari/Pukul</th>
          <th>MK/Dosen</th>
          <th>Ruang</th>
        </thead>
        $tr
      </table>";
  }
}

echo "
  <h1>Welcome <a href='?detail&tb=user&id=$user[id]' id='nama_user' class='proper'>$user[nama]</a>!!!</h1>
  <p class=petunjuk></p>
  <ul>
    <li><b>Prodi:</b> $mhs[prodi] $mhs[shift] </li>
    <li><b>Angkatan:</b> $mhs[angkatan] </li>
    <li><b>Semester:</b> $my_semester </li>
    <li><b>Kelas:</b> $kelas_show </li>
  </ul>

  $info_posisi_smt
  $form
  $cocok_info
  $jadwal
";




# ============================================================
# CEK JIKA PUNYA JADWAL BIMBINGAN 
# ============================================================
$s = "SELECT 1 FROM tb_peserta_bimbingan a WHERE a.id_mhs = $id_mhs";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  echo div_alert('info', 'Kamu belum punya Data Bimbingan');
} else {
  echo div_alert('info', 'Klik <a href=?bimbingan>Menu Bimbingan</a> untuk akses data bimbingan kamu.');
}

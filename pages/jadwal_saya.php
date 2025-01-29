<?php
$img_unlocked = img_icon('unlocked');
$img_locked = img_icon('locked');

$s = "SELECT 
b.id as id_st_detail,
b.id_kelas,
c.id as id_kumk,
d.nama as nama_mk,
d.sks,
e.label as label_kelas,
e.nama as nama_kelas,
e.id_shift,
e.semester,
g.fakultas,
(SELECT id FROM tb_jadwal WHERE id=b.id) id_jadwal, 
(SELECT weekday FROM tb_jadwal WHERE id=b.id) weekday,
(SELECT id_sesi_at_book FROM tb_jadwal WHERE id=b.id) id_sesi_at_book 
FROM tb_st a 
JOIN tb_st_detail b ON a.id=b.id_st 
JOIN tb_kumk c ON b.id_kumk=c.id
JOIN tb_mk d ON c.id_mk=d.id
JOIN tb_kelas e ON b.id_kelas=e.id 
JOIN tb_kurikulum f ON c.id_kurikulum=f.id 
JOIN tb_prodi g ON f.id_prodi=g.id 
WHERE a.id_ta = $ta_aktif 
AND a.id_dosen = $dosen[id] 
ORDER BY weekday, id_sesi_at_book, d.nama
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$i = 0;
$last_weekday = '';
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $info = '<i class="f12 red">belum terjadwal</i>';
  $info_ruang = '-';
  $gradasi = 'merah';
  $separator_top = ($last_weekday != $d['weekday'] and $i != 1) ? 'separator_top' : '';
  $last_weekday = $d['weekday'];
  $img_next2 = $img_next;
  $form_locked = '-';
  $nama_kelas = $d['label_kelas'] ?? str_replace('D3-', '', str_replace('S1-', '', str_replace("-$ta_aktif", '', $d['nama_kelas'])));

  if ($d['id_jadwal']) {
    $img_next2 = '';
    $gradasi = 'hijau';
    $s2 = "SELECT 
    a.*,
    a.id as id_jadwal,
    c.nama as nama_ruang 
    FROM tb_jadwal a 
    JOIN tb_pemakaian_ruang b ON a.id=b.id_st_detail 
    JOIN tb_ruang c ON a.id_ruang=c.id 
    WHERE a.id='$d[id_st_detail]'";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $d2 = mysqli_fetch_assoc($q2);
    $info = '<i class="f12 green">terjadwal</i>';
    $hari = $arr_hari[$d2['weekday']];
    $mulai = date('H:i', strtotime($d2['jam_mulai']));
    $selesai = date('H:i', strtotime($d2['jam_selesai']));
    $caption = $d2['is_locked'] ? "<span class='darkred f12'>$img_locked locked</span>" : "<span class='abu f12'>$img_unlocked dapat barter</span>";
    $pesan = $d2['is_locked'] ? "Unlock Jadwal?\n\nStatus Unlocked artinya memperbolehkan rekan dosen lain untuk Barter Jadwal dengan Anda." : "Lock (Kunci) Jadwal?\n\nJadwal Terkunci artinya rekan Dosen lain tidak bisa mengajukan Barter Jadwal kepada Anda.";
    $form_locked = "
      <form method=post class='m0 p0 inline '>
        <button class=transparan name=btn_locked_jadwal value=$d[id_st_detail] onclick='return confirm(`$pesan`)'>$caption</button>
      </form>
    ";

    $form_delete_jadwal = "
      <form method=post class='m0 p0 inline '>
        <button class=transparan name=btn_delete_jadwal value=$d[id_st_detail] onclick='return confirm(`Hapus Jadwal ini?`)'>$img_delete</button>
      </form>
    ";

    $info = "
      <div>
        $hari 
        <i class='f14 abu'>(sesi $d2[id_sesi_at_book])</i>
        $form_delete_jadwal
      </div>
      <div class='f12 abu consolas'>$mulai - $selesai</div>
    ";
    $info_ruang = $d2['nama_ruang'];
  }



  $tr .= "
    <tr id=tr__$d[id_st_detail] class='gradasi-$gradasi $separator_top'>
      <td>$i</td>
      <td>
        $d[nama_mk]
        <div class='f12 darkabu'>$d[sks] SKS</div>
      </td>
      <td><a href='?jadwal&fakultas=$d[fakultas]&id_shift=$d[id_shift]&semester=$d[semester]&id_kelas=$d[id_kelas]'>$nama_kelas $img_next2</a></td>
      <td>$info</td>
      <td>$info_ruang</td>
      <td>$form_locked</td>
    </tr>
  ";
}

echo "
  <div class='wadah gradasi-hijau'>
    <h3>Jadwal Saya</h3>
    <table class='table table-striped'>
      <thead>
        <th>No</th>
        <th>MK</th>
        <th>Kelas</th>
        <th>Hari</th>
        <th>Ruang</th>
        <th>Barter</th>
      </thead>
      $tr
    </table>

  </div>
";

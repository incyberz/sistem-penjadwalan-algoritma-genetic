<?php
set_title('Tes PMB');
$belum_tes = '<i class="f12 darkred">belum tes</i>';
$is_lulus = false;

$s = "SELECT a.*,
b.title,
b.ket,
(SELECT 1 FROM tb_jadwal_tes_pmb WHERE id_tes_pmb=a.id AND awal > '$now') ada_jadwal 
FROM tb_tes_pmb a 
JOIN tb_jenis_tes b ON a.jenis_tes=b.jenis_tes 
WHERE a.tahun_pmb = $tahun_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  alert('Belum ada Tes PMB untuk tahun ini. Segera laporkan ke Petugas!');
  exit;
} else {

  $tr = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $is_online_show = $d['is_online'] ? 'Online' : 'Offline';
    $title_show = str_replace('Tes Tes', 'Tes', "Tes $d[title]");

    $belum_ada_jadwal = "<i class=darkred>belum ada jadwal</i>";
    if ($d['ada_jadwal']) {
      $s2 = "SELECT * FROM tb_jadwal_tes_pmb WHERE id_tes_pmb = $d[id]";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $jadwal = mysqli_fetch_assoc($q2);
      $belum_ada_jadwal = date('d M Y', strtotime($jadwal['awal']));
      $belum_ada_jadwal .= "<br><b>Pukul</b>: " . date('H:i', strtotime($jadwal['awal']));
      $belum_ada_jadwal .= "<br><b>Lokasi</b>: $jadwal[lokasi]";
      $belum_ada_jadwal .= "<br><b>Durasi</b>: $jadwal[durasi] menit";
      $belum_ada_jadwal .= "<br><b>Info</b>: $d[ket]";
    }

    $tr .= "
      <tr id=tr__$d[id]>
        <td>$i</td>
        <td>
          $title_show
          <div class='f12 abu'>$is_online_show</div>
          <div class='f12 abu'><b>Jadwal</b>: $belum_ada_jadwal</div>
        </td>
        <td style=line-height:100%>$belum_tes</td>
      </tr>
    ";
  }

  $nomor_peserta_ujian = $pmb['no_peserta_ujian'] ? "
    <span class='green consolas f30'>$pmb[no_peserta_ujian]</span>
    <div class='green f14 mt2'>Tunjukan tampilan layar ini ke Petugas saat masuk Ruangan Tes.</div>
  " : "
    <i class='red f14 bold'>--belum punya--</i>
    <div class='darkblue f14 mt2'>silahkan cek berkas pembayaran dan status berkasnya, mohon tunggu jika belum diverifikasi oleh Petugas.</div>
    <a class='btn btn-info btn-sm mt1' href='?daftar&step=7#form_pembayaran'>Cek Pembayaran</a>
  ";

  $gradasi = $pmb['no_peserta_ujian'] ? 'hijau' : 'kuning';

  echo '<pre>';
  var_dump('is_lulus=1');
  echo '<b style=color:red>Developer SEDANG DEBUGING: exit(true)</b></pre>';
  $is_lulus = $akun['lulus_tes_pmb'];
  if ($is_lulus) {
    $hasil_ujian_show = "<span class=green>Selamat! Anda Lulus Tes PMB.</span>";
  } else {
    $hasil_ujian_show = "<i class=red>--Anda belum ujian--</i>";
  }


  echo "
    <div class='card mb3'>
      <div class='card-header bg-primary putih tengah'>Nomor Peserta Ujian</div>
      <div class='card-body tengah gradasi-$gradasi'>$nomor_peserta_ujian</div>
    </div>
    <table class='table th_toska'>
      <thead>
        <th>No</th>
        <th>Jenis Tes</th>
        <th>Hasil</th>
      </thead>
      $tr
    </table>

    <div class='card mb3'>
      <div class='card-header bg-primary putih tengah'>Hasil Akhir Ujian</div>
      <div class='card-body tengah gradasi-$gradasi'>$hasil_ujian_show</div>
    </div>
    
    ";
}


if ($is_lulus) {
  echo "
    <script>
      $(function() {
        $('#form_next_step').slideDown();
      })
    </script>
  ";
}

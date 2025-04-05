<?php
set_title('Tes PMB');
require_once '../includes/img_icon.php';
$belum_tes = '<i class="f12 darkred consolas">--belum tes--</i>';
$belum_ada_jadwal = "<i class=darkred>belum ada jadwal</i>";
$anda_belum_ujian = "<i class=red>--Anda belum ujian--</i>";
$sedang_proses_ujian = "<i class=red>--sedang proses ujian--</i>";
$lulus_ujian = "<i class='text-success'>--Lulus Ujian--</i> $img_check";
$lulus_tes = "<b class='text-success f14'>lulus</b> $img_check";
$mengulang_tes = "<i class='f14 text-danger'>mengulang.</i> $img_warning";

$is_lulus = false;
$sedang_tes = false;
$count_lulus = 0;
$count_tes = 0;

# ============================================================
# DATA TES ALL
# ============================================================
$s = "SELECT a.*,
b.title,
b.ket,
(SELECT 1 FROM tb_jadwal_tes WHERE id_tes=a.id AND awal > '$now') ada_jadwal, 
(SELECT 1 FROM tb_hasil_tes WHERE id_tes=a.id) ada_hasil 
FROM tb_tes_pmb a 
JOIN tb_jenis_tes b ON a.jenis_tes=b.jenis_tes 
WHERE a.tahun_pmb = $tahun_pmb";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_tes = mysqli_num_rows($q);
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

    if ($d['ada_jadwal']) {
      $s2 = "SELECT * FROM tb_jadwal_tes WHERE id_tes = $d[id]";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $jadwal = mysqli_fetch_assoc($q2);
      $durasi_show = $jadwal['durasi'] ? "($jadwal[durasi] menit)" : ' s.d selesai';
      $belum_ada_jadwal = date('d M Y', strtotime($jadwal['awal']));
      $belum_ada_jadwal .= "<br><b>Pukul</b>: " . date('H:i', strtotime($jadwal['awal'])) . $durasi_show;
      $belum_ada_jadwal .= "<br><b>Lokasi</b>: $jadwal[lokasi]";
      $belum_ada_jadwal .= "<br><b>Info</b>: $d[ket]";
    }

    $hasil_tes = $belum_tes;
    if ($d['ada_hasil']) {
      $sedang_tes = 1;
      $s2 = "SELECT * FROM tb_hasil_tes WHERE id_tes=$d[id] AND username='$username'";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $hasil = mysqli_fetch_assoc($q2);
      if ($hasil['nilai'] >= $d['nilai_lulus'] || $hasil['is_lulus']) {
        $hasil_tes = $lulus_tes;
        $count_lulus++;
      } elseif ($hasil['nilai'] < $d['nilai_gagal']) {
        $hasil_tes = $mengulang_tes;
        $hasil_tes .= "<div class='f12 abu'>silahkan tunggu jadwal berikutnya.</div>";
      } else {
        $hasil_tes = '<i class="f12 abu">sedang diproses oleh tim penilai</i>';
      }
    }


    $tr .= "
      <tr id=tr__$d[id]>
        <td>$i</td>
        <td>
          $title_show
          <div class='f12 abu'>$is_online_show</div>
          <div class='f12 abu'><b>Jadwal</b>: $belum_ada_jadwal</div>
        </td>
        <td>$hasil_tes</td>
      </tr>
    ";
  }

  $nomor_peserta_ujian = $pmb['nomor_peserta'] ? "
    <span class='green consolas f30'>$pmb[nomor_peserta]</span>
    <div class='green f14 mt2'>Tunjukan tampilan layar ini ke Petugas saat masuk Ruangan Tes.</div>
  " : "
    <i class='red f14 bold'>--belum punya--</i>
    <div class='darkblue f14 mt2'>silahkan cek berkas pembayaran dan status berkasnya, mohon tunggu jika belum diverifikasi oleh Petugas.</div>
    <a class='btn btn-info btn-sm mt1' href='?daftar&step=7#form_pembayaran'>Cek Pembayaran</a>
  ";

  $gradasi = $pmb['nomor_peserta'] ? 'hijau' : 'kuning';

  $is_lulus = $pmb['tanggal_lulus_tes'];
  if ($is_lulus) {
    $hasil_ujian_show = "<span class=green>Selamat! Anda Lulus Tes PMB.</span>";
  } else {
    $hasil_ujian_show = $sedang_tes ? $sedang_proses_ujian : $anda_belum_ujian;
    if ($count_tes and $count_lulus == $count_tes) {
      # ============================================================
      # AUTO SAVE LULUS
      # ============================================================
      $s = "UPDATE tb_pmb SET tanggal_lulus_tes = CURRENT_TIMESTAMP WHERE username='$username'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      jsurl();
    }
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
} // end if rows


if ($is_lulus) {
  echo "
    <script>
      $(function() {
        $('#form_next_step').slideDown();
      })
    </script>
  ";
}

<style>
  .img-profil {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: solid 5px white;
    box-shadow: 0 0 10px gray;
  }
</style>
<?php
set_title('Dashboard Pendaftar');
include '../includes/key2kolom.php';
include '../includes/eta.php';
include 'dashboard_pendaftar-styles.php';

$s = "SELECT 
b.nama,
f.nama as prodi,
g.nama_jalur as jalur,
a.nim_sementara,
a.tanggal_finish_registrasi as finish_registrasi,
b.created_at as mulai_pmb,
(
  SELECT last_update FROM tb_feedback_respon
  WHERE tahun_pmb=$tahun_pmb
  AND responden=a.username) last_update_feedback

FROM tb_pmb a 
JOIN tb_akun b ON a.username=b.username 
-- JOIN tb_biodata c ON a.username=c.username 
-- JOIN tb_data_sekolah d ON a.username=d.username 
-- JOIN tb_data_orangtua e ON a.username=e.username 
JOIN tb_prodi f ON a.id_prodi=f.id 
JOIN tb_jalur g ON a.id_prodi=g.id 
WHERE a.username = '$username' 
AND a.tanggal_finish_registrasi is not null
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(alert('Data Pendaftar tidak ditemukan'));
$pmb = mysqli_fetch_assoc($q);

# ============================================================
# FOTO
# ============================================================
$s = "SELECT a.file FROM tb_berkas a WHERE username = '$username' AND jenis_berkas='FOTO'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(alert('danger', 'Data Foto tidak ditemukan'));
$d = mysqli_fetch_assoc($q);
$file = $d['file'];

$eta = eta2($pmb['finish_registrasi']);
$mulai_pmb_show = date('d-M-Y H:i', strtotime($pmb['mulai_pmb']));
$finish_show = date('d-M-Y H:i', strtotime($pmb['finish_registrasi']));
// $finish_show .= " - <span class='miring f12 abu'>$eta</span>";
$finish_show .= " ($eta)";
$nama = ucwords(strtolower($pmb['nama']));

$durasi = strtotime($pmb['finish_registrasi']) - strtotime($pmb['mulai_pmb']);
$dd = ceil($durasi / (3600 * 24));
$jj = ceil(($durasi % (3600 * 24)) / 3600);
$mm = ceil($durasi % 3600 / 60);
$ss = $durasi % 60;

# ============================================================
# FEEDBACK
# ============================================================
if ($pmb['last_update_feedback']) {
  require_once '../includes/img_icon.php';
  $tgl = date('d-M-Y H:i', strtotime($pmb['last_update_feedback']));
  $eta = eta2($pmb['last_update_feedback']);
  $info_feedback = "
    <div class='darkabu '>
      Terimakasih!
      <div class=text-success>Anda sudah mengisi feedback $img_check </div>
      <div class=f12>
        at $tgl, $eta.
      </div> 
    </div>
    <a target=_blank class='btn btn-secondary w-100 mt2' href='?feedback'>Lihat Feedback</a>
  ";
} else {
  $info_feedback = "
    <div class='darkabu '>Sangat disarankan bagi Anda untuk mengisi Feedback PMB bagi kami agar Anda dapat ikut berpartisipasi dalam pengembangan sistem PMB ini menjadi lebih baik.</div>
    <a target=_blank class='btn btn-primary w-100 mt2' href='?feedback'>Mengisi Feedback</a>
  ";
}

echo "
  <div class='mx-auto' style='max-width: 500px; min-height:100vh'>
    <div class='gradasi-toska p-3' style='margin: 0 -10px'>
      <div class='tengah f24 pb-2 pt-2'>
        <div class=''>Mahasiswa Baru</div>
        <div class=''>Universitas Anda Bandung</div>
        <hr>
      </div>

      <div class='card mb4'>
        <div class='card-header bg-success putih tengah f24'>Selamat! Kepada:</div>
        <div class='card-body tengah gradasi-hijau'>
          <div class='mt2 mb4'><img src='uploads/berkas/$file' class=img-profil></div>
          <div class='' style='margin: 0 -15pxs'>
            <h3 >$nama</h3>
            <div ><b>NIM-S</b>: <span class='consolas f30'>$pmb[nim_sementara]</span></div>
          </div>
        </div>
      </div>
      <div class='text-success bold mb1'>Telah sukses terdaftar pada:</div>
      <ul class=text-success>
        <li><b>Program Studi</b>: $pmb[prodi]</li>
        <li><b>Jalur Daftar</b>: $pmb[jalur]</li>
        <li><b>Mulai PMB</b>: $mulai_pmb_show</li>
        <li><b>Finish Registrasi</b>: $finish_show</li>
        <li><b>Finish Dalam</b>: <span class=consolas>$dd<i>d</i>:$jj<i>h</i>:$mm<i>m</i>:$ss<i>s</i></span></li>
      </ul>
      <div class='mb3 tengah'>
        <a target=_blank class='btn btn-success w-100 mt2' href='cetak_pmb.php'>Cetak Hasil PMB</a>
      </div>
      <hr>
      <div class='mb3 tengah'>
        $info_feedback
      </div>
      <hr>
      <div class='mb3 tengah'>
        <div class='abu f14'>Untuk informasi Kuliah Umum, Orientasi Mahasiswa, dan Informasi Penting lainnya akan diposting via Grup Whatsapp PMB $tahun_pmb</div>
        <a target=_blank class='btn btn-info w-100 mt2' href='https://chat.whatsapp.com/ZZZ'>Gabung ke Whatsapp Grup</a>
      </div>
      <hr>
      <div class='mb3 tengah'>
        <a href='?logout_pmb'>Logout</a>
      </div>
    </div>
  </div>

";

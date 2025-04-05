<?php
if (isset($_POST['btn_submit_verif'])) {
  $link_info = null;
  $id_berkas = $_POST['btn_submit_verif'];
  # ============================================================
  # DATA BERKAS PENDAFTAR
  # ============================================================
  $s = "SELECT 
  a.id as id_berkas,
  a.jenis_berkas,
  c.nama_berkas,
  b.username, 
  b.whatsapp,
  b.nama,
  d.id_prodi,
  d.id_jalur 
  FROM tb_berkas a 
  JOIN tb_akun b ON a.username=b.username 
  JOIN tb_jenis_berkas c ON a.jenis_berkas=c.jenis_berkas 
  JOIN tb_pmb d ON b.username=d.username
  WHERE a.id='$id_berkas'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $berkas = mysqli_fetch_assoc($q);
  $nama_pendaftar = ucwords(strtolower($berkas['nama']));
  $nama_berkas = $berkas['nama_berkas'];
  $jenis_berkas = $berkas['jenis_berkas'];

  $is_reject = 0;
  if ($_POST['status'] == -1) {
    $is_reject = 1;
    $alasan_reject = "'$_POST[alasan_reject]'";
    $bg = 'danger';
    $pesan = "Mohon maaf, berkas Anda [ $nama_berkas ] belum memenuhi syarat dengan alasan [ $_POST[alasan_reject] ]. Silahkan Anda segera Replace/Upload ulang sesuai dengan ketentuan di website PMB.";
  } elseif ($_POST['status'] == 1) {
    $bg = 'success';
    $alasan_reject = 'NULL';
    $pesan = "Berkas Anda [ $nama_berkas ] telah berhasil kami verifikasi. Silahkan Anda lanjutkan proses pendaftaran PMB!";

    if ($jenis_berkas == 'FORMULIR' || $jenis_berkas == 'REGISTRASI') {
      include 'gelombang_aktif.php';
      # ============================================================
      # AUTO CREATE NOMOR PESERTA DAN SET ID GELOMBANG
      # ============================================================
      if ($jenis_berkas == 'FORMULIR') {
        $id = sprintf('%04d', $berkas['id_berkas']);
        $nomor_peserta = "$gelombang[id]-$berkas[id_prodi]-$berkas[id_jalur]-$id";

        # ============================================================
        # UPDATE PMB
        # ============================================================
        $s = "UPDATE tb_pmb SET id_gelombang='$gelombang[id]', nomor_peserta = '$nomor_peserta' WHERE username = '$berkas[username]'";
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

        // pesan whatsapp
        $pesan = "Bukti Pembayaran Formulir telah kami verifikasi.\n\nAnda terdaftar sebagai Peserta Tes pada:\n- *Gelombang*: $gelombang[id]\n- *Nomor Peserta*: $nomor_peserta \n\nUntuk selanjutnya silahkan Anda menuju Step 8 Tes PMB pada proses Pendaftaran.";

        $link_info = "$nama_server?daftar&step=8";
      }

      # ============================================================
      # AUTO CREATE NIM
      # ============================================================
      if ($jenis_berkas == 'REGISTRASI') {
        // zzz
        echo '<pre>';
        var_dump('AUTO CREATE NIM');
        echo '<b style=color:red>Developer SEDANG DEBUGING: exit(true)</b></pre>';
        exit;
      }
    }
  } else {
    die('Invalid value status berkas.');
  }

  # ============================================================
  # UPDATE BERKAS
  # ============================================================
  $s = "UPDATE tb_berkas SET status = $_POST[status], alasan_reject=$alasan_reject WHERE id=$_POST[btn_submit_verif] ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  alert("Update berkas sukses.", 'success');






  # ============================================================
  # WHATSAPP NOTIF KE PESERTA
  # ============================================================
  $dari = 'petugas';
  $nomor_tujuan = $berkas['whatsapp'];
  $nama_penerima = $nama_pendaftar;
  $link_info = $link_info ?? "$nama_server?daftar&step=7";
  include 'link_kirim_whatsapp.php';
  $text_preview = str_replace("\n", '<br>', $text_wa);
  $text_preview = str_replace('```', '', $text_preview);



  echo "
    <div class=card >
      <div class='card-header bg-$bg tengah putih'>Notifikasi ke Peserta PMB</div>
      <div class='card-body'>
        <div class='f14 abu miring mb1'>Preview Notif:</div>
        <div id='text_preview' class='form-control'>$text_preview</div>
        <a class='btn btn-$bg w-100 mt2' href=$link_wa>Kirim Notifikasi</a>
      </div>
    </div>
    <div class='tengah mt4'>
      <a href='?petugas'>Home Petugas</a> | <a href='?berkas'>Home Berkas</a>
    </div>
  ";


  exit;

  // jsurl();
} elseif (isset($_POST['btn_undo_verif'])) {
  $s = "UPDATE tb_berkas SET status = NULL, alasan_reject=NULL WHERE id=$_POST[btn_undo_verif] ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

<?php

$checks_hari = '';
for ($i = 1; $i < 6; $i++) {
  $checks_hari .= "
    <label class='d-block ' >
      <input class='checkbox_hari' id=checkbox_hari__$i type=checkbox value=$i /> $arr_hari[$i]
    </label>
  ";
}

# ============================================================
# MAIN SELECT MY BIMBINGAN
# ============================================================
$s = "SELECT a.*,
(SELECT COUNT(1) FROM tb_peserta_bimbingan WHERE id_bimbingan=a.id) jumlah_bimbingan 
FROM tb_bimbingan a 
WHERE id_ta=$ta_aktif 
AND id_dosen=$id_dosen
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$bimbingan = mysqli_fetch_assoc($q);
if ($bimbingan) {
  $wag = $bimbingan['wag'];
  $rhari = explode(',', $bimbingan['hari_availables']);
  $haris = '';
  foreach ($rhari as $key => $weekday) {
    $koma = $haris ? ', ' : '';
    $haris .= "$koma$arr_hari[$weekday]";
  }

  $jumlah_bimbingan = $bimbingan['jumlah_bimbingan'] ? "$bimbingan[jumlah_bimbingan] mhs" : "<b class=red>Belum ada mhs bimbingan. Silahkan Add Mhs Bimbingan</b>";

  $opsi_bimbingan = "
    <table class='table'>
      <tr>
        <td>Group Whatsapp Bimbingan</td>
        <td>
          <a target=_blank href='$bimbingan[wag]'>$img_wa</a>
        </td>
      </tr>
      <tr>
        <td>Hari Available</td>
        <td>$haris</td>
      </tr>
    </table>
  
  ";
} else {
  # ============================================================
  # BELUM ADA BIMBINGAN
  # ============================================================
  $opsi_bimbingan = "
  <form method=post class='wadah gradasi-hijau'>
    <div class='alert alert-info'>
      Anda belum mempunyai Mahasiswa Bimbingan pada Tahun Ajar $tahun_ta $Gg 
      <hr/>
      <p>Jika Anda sudah mempunyai SK Bimbingan, silahkan Add Bimbingan</p>
      <span class='btn btn-info btn_aksi' id=add_bimbingan__toggle>Add Bimbingan</span>
      <div class='hideita wadah mt2' id=add_bimbingan>
  
        <div class='mt2 mb1 f12'>Group Whatsapp Bimbingan</div>
        <input required minlength=40 name=wag placeholder='Group Whatsapp Bimbingan' class='form-control' />
        <div class='mt1 mb3 f12'>
          Contoh: https://chat.whatsapp.com/KwxEugfktM47ppcNJ8cPw7 - 
          <span class='hover darkblue btn_aksi' id=panduan_link_wag__toggle>panduan $img_help</span>
          <div class='hideit mt2 mb2 f16' id=panduan_link_wag>
            <ol>
              <li>Buat atau Buka Group Whatsapp Anda</li>
              <li>Klik Menu 3 titik di pojok kanan atas (Setting Group)</li>
              <li>Pilih 'Members'</li>
              <li>Pilih 'Invite to Group via Link'</li>
              <li>Click 'Copy Link'</li>
            </ol>
          </div>
  
        </div>
  
        <div class='mt2 mb1 f12'>Available Hari Bimbingan</div>
        $checks_hari
  
        <input type=hidden name=hari_availables id=hari_availables placeholder='hari_availables' />
        <button class='btn btn-primary mt2' name=btn_add_bimbingan id=btn_add_bimbingan disabled>Add Bimbingan</button>
      </div>
    </div>
  </form>
  ";
}


# ============================================================
# DAFTAR PESERTA BIMBINGAN
# ============================================================
if ($bimbingan) {
  # ============================================================
  # ARRAY STATUS 
  # ============================================================
  $s = "SELECT * FROM tb_status_bimbingan_mhs ";
  $q =  mysqli_query($cn, $s) or die(mysqli_error($cn));
  $rStatus = [];
  while ($d = mysqli_fetch_assoc($q)) {
    $rStatus[$d['id']] = $d['status'];
  }

  # ============================================================
  # MY BIMIBNGAN
  # ============================================================
  $s = "
    $select_peserta_bimbingan -- lihat di bimbingan.php
    AND d.id = $id_dosen -- saya sendiri
  ";
  $tr = '';
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $badge_prodi = badge_prodi($d['prodi']);

    # ============================================================
    # CREATE PROGRESS BAR
    # ============================================================
    $progress = "<div class='progress'>
    <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='75' aria-valuemin='0' aria-valuemax='100' style='width: 75%'>75%</div>
    </div>";

    $tr .= "
      <tr>
        <td>$i</td>
        <td>
          $d[nama_mhs] $badge_prodi
          <div class='f10 abu'>$d[nim]</div>
        </td>
        <td>$progress</td>
        <td class='text-success'>Sudah Update</td>
        <td><button class='btn btn-sm btn-info'>Lihat Detail</button></td>
      </tr>
    ";
  }
}

?>
<h3 class="mb-4">Dashboard Bimbingan Dosen</h3>

<!-- Statistik Bimbingan -->
<div class="row">
  <div class="col-md-3">
    <div class="card text-white bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Mahasiswa Bimbingan</h5>
        <p class="card-text" id="totalMahasiswa"><?= $jumlah_bimbingan ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Laporan Minggu Ini</h5>
        <p class="card-text" id="laporanMingguIni">0</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title">Perlu Ditinjau</h5>
        <p class="card-text" id="perluDitinjau">0</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-danger mb-3">
      <div class="card-body">
        <h5 class="card-title">Belum Update</h5>
        <p class="card-text" id="belumUpdate">0</p>
      </div>
    </div>
  </div>
</div>

<!-- Daftar Mahasiswa Bimbingan -->
<div class="card mt-4">
  <div class="card-header bg-primary text-white">Daftar Mahasiswa Bimbingan</div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>NO</th>
          <th>Mhs</th>
          <th>Progress</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="daftarMahasiswa">
        <?= $tr ?>
      </tbody>
    </table>
  </div>
</div>

<?php
// $s = "SELECT * FROM tb_status_bimbingan_mhs ";
// $q =  mysqli_query($cn, $s) or die(mysqli_error($cn));
// $tr = '';
// while ($d = mysqli_fetch_assoc($q)) {
//   $tr .= "
//     <tr>
//       <td>$d[id]</td>
//       <td>$d[status]</td>
//     </tr>
//   ";
// }

// echo "<table class=table>$tr</table>";


?>



<script>
  $(function() {
    $(".checkbox_hari").click(function() {
      let hari_availables = [];
      $(".checkbox_hari:checked").each(function() {
        hari_availables.push($(this).val());
      });
      $('#hari_availables').val(hari_availables);

      if ($(".checkbox_hari:checked").length > 0) {
        $('#btn_add_bimbingan').prop('disabled', false);
      } else {
        $('#btn_add_bimbingan').prop('disabled', true);
      }
    })
  })
</script>
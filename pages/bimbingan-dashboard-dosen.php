<?php
$perlu_review_count = 0;
$belum_update_count = 0;
$sudah_update_count = 0;
$laporan_mingguan_count = 0;
$jumlah_mhs_bimbingan = 0;

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

  $jumlah_bimbingan_show = $bimbingan['jumlah_bimbingan'] ? "$bimbingan[jumlah_bimbingan] mhs" : "<b class=red>Belum ada mhs bimbingan. Silahkan Add Mhs Bimbingan</b>";

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
# ARRAY STATUS 
# ============================================================
$s = "SELECT * FROM tb_status_bimbingan ";
$q =  mysqli_query($cn, $s) or die(mysqli_error($cn));
$rStatus = [];
$tr_status = '';
while ($d = mysqli_fetch_assoc($q)) {
  $rStatus[$d['id']] = $d['status'];
  $tr_status .= "
    <tr>
      <td>$d[id]</td>
      <td>$d[status]</td>
    </tr>
  ";
}

# ============================================================
# DAFTAR PESERTA BIMBINGAN
# ============================================================
if ($bimbingan) {
  # ============================================================
  # MY BIMIBNGAN
  # ============================================================
  $s = "
    $select_peserta_bimbingan -- lihat di bimbingan.php
    AND d.id = $id_dosen -- saya sendiri
  ";
  $tr_mhs = '';
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $jumlah_mhs_bimbingan = mysqli_num_rows($q);
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $badge_prodi = badge_prodi($d['prodi']);

    # ============================================================
    # PROGRESS TIAP MHS
    # ============================================================
    $id_status = $d['id_status_bimbingan'];
    $status = $rStatus[$id_status] ?? '<b class=text-danger>Belum Pernah Bimbingan<b>';
    $count = count($rStatus);
    $persen = $id_status ? round($id_status / $count * 100) : 0;

    $progress = "
      <div>Status: $status</div>
      <div class='progress'>
        <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='$persen' aria-valuemin='0' aria-valuemax='100' style='width: $persen%'>$persen%</div>
      </div>
    ";

    # ============================================================
    # CEK APAKAH SUDAH LAPORAN ATAU BELUM
    # ============================================================
    $td_status = "<td class='text-danger'>Belum Update</td>";
    if ($d['count_laporan_mingguan']) {
      $td_status = "<td class='text-success'>Sudah Update</td>";
      $laporan_mingguan_count += $d['count_laporan_mingguan'];
      $sudah_update_count++;
    }

    # ============================================================
    # APAKAH PERLU REVIEW DARI MHS INI?
    # ============================================================
    $Lihat_Detail = 'Lihat Detail';
    $btn_info = 'btn-info';
    if ($d['perlu_review']) {
      $perlu_review_count++;
      $Lihat_Detail = 'Review Laporan';
      $btn_info = 'btn-danger';
    }

    # ============================================================
    # FINAL TR MY MHS BIMBINGAN
    # ============================================================
    $tr_mhs .= "
      <tr>
        <td>$i</td>
        <td>
          $d[nama_mhs] $badge_prodi
          <div class='f10 abu'>$d[nim]</div>
        </td>
        <td>$progress</td>
        $td_status
        <td><a href='?bimbingan&p=riwayat_laporan&id_mhs=$d[id_mhs]' class='btn btn-sm $btn_info'>$Lihat_Detail</a></td>
      </tr>
    ";
  }
}

$belum_update_count = $jumlah_mhs_bimbingan - $sudah_update_count;


?>
<h3 class="mb-4">Dashboard Bimbingan Dosen</h3>

<!-- Statistik Bimbingan -->
<div class="row">
  <div class="col-md-3">
    <div class="card text-white bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Mahasiswa Bimbingan</h5>
        <p class="card-text" id="totalMahasiswa"><?= $jumlah_bimbingan_show ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Laporan Minggu Ini</h5>
        <p class="card-text" id="laporanMingguIni"><?= $laporan_mingguan_count ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title">Belum Update</h5>
        <p class="card-text" id="perluDitinjau"><?= $belum_update_count ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-danger mb-3">
      <div class="card-body">
        <h5 class="card-title">Perlu Review</h5>
        <p class="card-text" id="belumUpdate"><?= $perlu_review_count ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Daftar Mahasiswa Bimbingan -->
<div class="card mt-4">
  <div class="card-header bg-primary text-white">Daftar Mahasiswa Bimbingan</div>
  <div class="card-body">
    <p>Minggu saat ini: <?= date('d M', strtotime($ahad_acuan)) ?> s.d skg</p>
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
        <?= $tr_mhs ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header bg-primary text-white">Kode Status Bimbingan</div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>id</th>
          <th>status</th>
        </tr>
      </thead>
      <tbody>
        <?= $tr_status ?>
      </tbody>
    </table>
  </div>
</div>




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
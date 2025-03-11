<h2 class="mb-4">Dashboard Bimbingan Petugas Akademik</h2>

<!-- Statistik Bimbingan -->
<div class="row">
  <div class="col-md-4">
    <div class="card text-white bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Total Dosen Pembimbing</h5>
        <p class="card-text" id="totalDosen">0</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title">Dosen Belum Update Mingguan</h5>
        <p class="card-text" id="dosenBelumUpdate">0</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Laporan Mingguan Diperiksa</h5>
        <p class="card-text" id="laporanDiperiksa">0</p>
      </div>
    </div>
  </div>
</div>

<!-- Daftar Dosen dan Status Update -->
<div class="card mt-4">
  <div class="card-header bg-primary text-white">Daftar Dosen Pembimbing</div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>NIDN</th>
          <th>Nama Dosen</th>
          <th>Status Update</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="daftarDosen">
        <tr>
          <td>10012345</td>
          <td>Dr. Budi Santoso</td>
          <td class="text-success">Sudah Update</td>
          <td><button class="btn btn-sm btn-info">Lihat Detail</button></td>
        </tr>
        <tr>
          <td>10067890</td>
          <td>Prof. Siti Aminah</td>
          <td class="text-danger">Belum Update</td>
          <td><button class="btn btn-sm btn-warning">Kirim Pengingat</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<a href="?add-peserta-bimbingan" class="btn btn-success mt2">Add Peserta Bimbingan</a>
<a href="?add-dosen-pembimbing" class="btn btn-success mt2">Add Dosen Pembimbing</a>
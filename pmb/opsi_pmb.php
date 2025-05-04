<?php
petugas_only();
set_h2('Opsi PMB');

?>
<div class="container py-5">
  <h2 class="mb-4">Konfigurasi PMB</h2>

  <!-- Tab Navigation -->
  <ul class="nav nav-tabs" id="konfigurasiTab" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tahun">Tahun Akademik</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#gelombang">Gelombang</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#jalur">Jalur Pendaftaran</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#biaya">Biaya</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#dokumen">Dokumen</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pengumuman">Pengumuman</a></li>
  </ul>

  <!-- Tab Contents -->
  <div class="tab-content p-4 bg-white border border-top-0">
    <!-- Tahun Akademik -->
    <div class="tab-pane fade show active" id="tahun">
      <div class="d-flex justify-content-between mb-3">
        <h5>Tahun Akademik</h5>
        <button class="btn btn-sm btn-primary">+ Tambah Tahun</button>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Tahun</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>2025/2026</td>
            <td><span class="badge bg-success">Aktif</span></td>
            <td><button class="btn btn-sm btn-warning">Nonaktifkan</button></td>
          </tr>
          <tr>
            <td>2</td>
            <td>2024/2025</td>
            <td><span class="badge bg-secondary">Nonaktif</span></td>
            <td><button class="btn btn-sm btn-success">Aktifkan</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Gelombang -->
    <div class="tab-pane fade" id="gelombang">
      <div class="d-flex justify-content-between mb-3">
        <h5>Gelombang Pendaftaran</h5>
        <button class="btn btn-sm btn-primary">+ Tambah Gelombang</button>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Gelombang</th>
            <th>Tanggal</th>
            <th>Kuota</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Gelombang 1</td>
            <td>01 Jan - 31 Mar</td>
            <td>100</td>
            <td><span class="badge bg-success">Dibuka</span></td>
            <td><button class="btn btn-sm btn-warning">Tutup</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Jalur -->
    <div class="tab-pane fade" id="jalur">
      <div class="d-flex justify-content-between mb-3">
        <h5>Jalur Pendaftaran</h5>
        <button class="btn btn-sm btn-primary">+ Tambah Jalur</button>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Jalur</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Mandiri</td>
            <td>Pendaftaran langsung ke kampus</td>
            <td><button class="btn btn-sm btn-warning">Edit</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Biaya -->
    <div class="tab-pane fade" id="biaya">
      <div class="d-flex justify-content-between mb-3">
        <h5>Biaya Pendaftaran</h5>
        <button class="btn btn-sm btn-primary">+ Tambah Biaya</button>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Jalur</th>
            <th>Biaya</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Mandiri</td>
            <td>Rp 250.000</td>
            <td><button class="btn btn-sm btn-warning">Edit</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Dokumen -->
    <div class="tab-pane fade" id="dokumen">
      <div class="d-flex justify-content-between mb-3">
        <h5>Dokumen Wajib</h5>
        <button class="btn btn-sm btn-primary">+ Tambah Dokumen</button>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Dokumen</th>
            <th>Format</th>
            <th>Ukuran Maks.</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Foto Formal</td>
            <td>JPG/PNG</td>
            <td>1 MB</td>
            <td><button class="btn btn-sm btn-warning">Edit</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pengumuman -->
    <div class="tab-pane fade" id="pengumuman">
      <div class="d-flex justify-content-between mb-3">
        <h5>Pengaturan Pengumuman</h5>
        <button class="btn btn-sm btn-primary">+ Jadwal Pengumuman</button>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>01 Juli 2025</td>
            <td>Kelulusan</td>
            <td><span class="badge bg-success">Akan Datang</span></td>
            <td><button class="btn btn-sm btn-warning">Edit</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
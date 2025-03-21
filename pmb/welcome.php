<?php
include "../conn.php";
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran PMB - Kampus Impian</title>
  <?php if ($is_live) { ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <?php } else { ?>
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">

  <?php } ?>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Kampus Impian</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
          <li class="nav-item"><a class="nav-link" href="#prodi">Program Studi</a></li>
          <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="./?daftar">Daftar Sekarang</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="bg-primary text-white text-center py-5">
    <div class="container">
      <h1>Selamat Datang di Kampus Impian</h1>
      <p class="lead">Tempat terbaik untuk mewujudkan masa depan gemilang Anda!</p>
      <a href="./?daftar" class="btn btn-light btn-lg">Daftar Sekarang</a>
    </div>
  </header>

  <section id="about" class="py-5">
    <div class="container text-center">
      <h2>Mengapa Memilih Kampus Impian?</h2>
      <p>Kami menawarkan lingkungan akademik berkualitas, dosen berpengalaman, dan fasilitas lengkap untuk mendukung perjalanan pendidikan Anda.</p>
    </div>
  </section>

  <section id="prodi" class="bg-light py-5">
    <div class="container text-center">
      <h2>Pilih Program Studi Sesuai Passion Anda</h2>
      <p>Kami memiliki berbagai program studi unggulan yang siap membawa Anda menuju kesuksesan.</p>
      <a href="#" class="btn btn-primary">Lihat Program Studi</a>
    </div>
  </section>

  <section id="daftar" class="py-5 text-center">
    <div class="container">
      <h2>Jangan Tunda Lagi! Segera Daftar!</h2>
      <p>Jadilah bagian dari Kampus Impian dan raih masa depan cerah bersama kami.</p>
      <a href="./?daftar" class="btn btn-success btn-lg">Daftar Sekarang</a>
    </div>
  </section>

  <footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Kampus Impian. Semua Hak Dilindungi.</p>
  </footer>

  <?php if ($is_live) { ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <?php } else { ?>
    <script src="../../assets/vendor/bootstrap/js/bootstrap.min.js"></script>

  <?php } ?>

</body>

</html>
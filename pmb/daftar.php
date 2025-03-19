<?php
$bulan_skg = intval(date('m'));
$tahun_skg = $bulan_skg >= 9 ? date('Y') : date('Y') - 1;
$tahun_lalu = $tahun_skg - 1;
$ta_skg = "$tahun_skg-" . ($tahun_skg + 1);
$ta_lalu = "$tahun_lalu-$tahun_skg";

$post_nama = $_POST['nama'] ?? null;
$post_whatsapp = $_POST['whatsapp'] ?? null;
$post_username = $_POST['username'] ?? null;
$post_asal_sekolah = $_POST['asal_sekolah'] ?? null;
$post_tahun_lulus = $_POST['tahun_lulus'] ?? null;


include 'daftar-process.php';

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pendaftaran Akun</title>
  <?php if ($is_live) { ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <?php } else { ?>
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <script src="../../assets/vendor/jquery/jquery-3.7.1.min.js"></script>

  <?php } ?>

</head>

<body>
  <div class="container mt-5">
    stepper:
    <ol>
      <li>Pendaftaran Akun</li>
      <li>Verifikasi Akun</li>
      <li>Melengkapi Biodata</li>
      <li>Melengkapi Data Sekolah</li>
      <li>Melengkapi Orangtua</li>
      <li>Memilih Jurusan</li>
    </ol>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white text-center">
            <h3>Pendaftaran Akun</h3>
          </div>
          <div class="card-body">
            <form method="post">
              <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                  <input type="text" class="form-control" id="nama" name=nama placeholder="Masukkan Nama" required minlength="3" maxlength="30" value="<?= $post_nama ?>">
                </div>
              </div>
              <div class="mb-3">
                <label for="whatsapp" class="form-label">WhatsApp</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                  <input type="text" class="form-control" id="whatsapp" name=whatsapp placeholder="Masukkan No. WhatsApp" required minlength="11" maxlength="14" value="<?= $post_whatsapp ?>">
                </div>
              </div>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                  <input type="text" class="form-control" id="username" name=username placeholder="Masukkan Username" required minlength="3" maxlength="20" value="<?= $post_username ?>">
                </div>
              </div>

              <div class="mb-3">
                <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-building"></i></span>
                  <input type="text" class="form-control" id="asal_sekolah" name=asal_sekolah placeholder="Masukkan Asal Sekolah" required minlength="3" maxlength="30" value="<?= $post_asal_sekolah ?>">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Tahun Lulus</label>
                <div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tahun_lulus" id="tahun_sekarang" value="sekarang" required>
                    <label class="form-check-label" for="tahun_sekarang">Tahun Sekarang (<?= $ta_skg ?>)</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tahun_lulus" id="tahun_lalu" value="lalu">
                    <label class="form-check-label" for="tahun_lalu">Tahun Lalu (<?= $ta_lalu ?>)</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tahun_lulus" id="dua_tahun_lalu" value="dua_tahun_lalu">
                    <label class="form-check-label" for="dua_tahun_lalu">Dua Tahun Lalu atau lebih</label>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $("#nama").on("keyup", function() {
        let val = $(this).val();
        val = val.replace(/'/g, "`"); // Ubah tanda petik menjadi backtick
        val = val.replace(/[^a-zA-Z` ]/g, ""); // Hanya huruf, spasi, dan tanda backtick
        $(this).val(val.toUpperCase()); // Ubah ke uppercase
      });

      $("#asal_sekolah").on("keyup", function() {
        let val = $(this).val();
        $(this).val(val.toUpperCase()); // Ubah ke uppercase
      });

      $("#whatsapp").on("keyup", function() {
        let val = $(this).val();
        val = val.replace(/[^0-9]/g, ""); // Hanya angka
        if (val.startsWith("08")) {
          val = "628" + val.substring(2);
        } else if (!val.startsWith("628") && val.length >= 4) {
          val = "";
        }
        $(this).val(val);
      });

      $("#username").on("keyup", function() {
        let val = $(this).val();
        val = val.replace(/[^a-z0-9]/g, ""); // Hanya huruf kecil dan angka
        $(this).val(val.toLowerCase()); // Ubah ke lowercase
      });
    });
  </script>

</body>

</html>

<?php if ($is_live) { ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php } else { ?>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.min.js"></script>

<?php } ?>
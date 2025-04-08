<?php
if ($username) {
  alert("Anda sudah berada pada Step-$akun[last_step], redirecting...");
  jsurl("./?daftar&step=$akun[last_step]", 3000);
}
?>
<div class="card">
  <div class="card-header bg-primary text-white text-center">
    <h3>Pendaftaran Akun</h3>
  </div>
  <div class="card-body">
    <form method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
          <input type="text" class="form-control" id="username" name=username placeholder="Masukkan Username" required minlength="3" maxlength="20" value="<?= $post_username ?>">
        </div>
        <div class="pesan pesan-error" id=username_error></div>
      </div>
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
        <div class="pesan pesan-info pesan-hidden" id=whatsapp_info>mohon gunakan whatsapp aktif agar dapat melanjutkan pendaftaran. Semua info berkas, tes, dan kelulusan akan dikirimkan via whatsapp.</div>
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

        <link rel="stylesheet" href="../assets/css/radio-toolbar.css">

        <div class="row">
          <div class="col-lg-4">
            <div class='radio-toolbar'>
              <input type='radio' name='jeda_tahun_lulus' id='tahun_skg' class='opsi_radio' required value='0'>
              <label class='proper' for='tahun_skg'>Tahun Skg</label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class='radio-toolbar'>
              <input type='radio' name='jeda_tahun_lulus' id='tahun_lalu' class='opsi_radio' required value='1'>
              <label class='proper' for='tahun_lalu'>Tahun Lalu</label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class='radio-toolbar'>
              <input type='radio' name='jeda_tahun_lulus' id='tahun_lalu2' class='opsi_radio' required value='0'>
              <label class='proper' for='tahun_lalu2'>> Tahun Lalu</label>
            </div>
          </div>
        </div>

      </div>

      <button type="submit" class="btn btn-primary w-100" name=btn_daftar>Daftar</button>
    </form>
  </div>
</div>
<div class="tengah mt-3">
  Sudah punya akun? Silahkan <a href="?login_pmb">login</a>.
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

    $("#whatsapp").focus(function() {
      $('#whatsapp_info').slideDown();
    });

    $("#whatsapp").focusout(function() {
      $('#whatsapp_info').slideUp();
    });

    $("#username").on("keyup", function() {
      let val = $(this).val();
      val = val.replace(/[^a-zA-Z0-9]/g, ""); // Hanya huruf kecil dan angka
      $(this).val(val.toLowerCase()); // Ubah ke lowercase
    });
    $("#username").focusout(function() {
      let username = $(this).val();
      let link_ajax = "daftar-cek_available_username.php?username=" + username;
      $.ajax({
        url: link_ajax,
        success: function(a) {
          if (a.trim() == 'sukses') {
            $('#username_error').text('');
          } else {
            $('#username_error').text(a);
          }
        }
      })
    });
  });
</script>
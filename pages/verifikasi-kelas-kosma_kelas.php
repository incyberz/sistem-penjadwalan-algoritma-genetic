<?php
if ($kelas['id_kosma']) {
  $status_syarat = "<b>Kosma:</b> $kelas[nama_kosma] |  +$kelas[whatsapp_kosma] <span class=btn_aksi id=form_ubah_id_kosma__toggle>$img_edit</span>";
  $hideit = 'hideit';
} else {
  $hideit = '';
}
$id_kosma = $kelas['id_kosma'] ?? $kelas['nama'];
$input_syarat = "
  <form method=post class=$hideit id=form_ubah_id_kosma>
    <div class='flexy wadah gradasi-kuning mt1'>
      <div><b>Kosma:</b></div>
      <div>
        <select class='form-control' name=id_kosma>
          $opt_mhs
        </select>
      </div>
      <div>
        <input required class=form-control minlength=10 maxlength=14 name=whatsapp_kosma id=whatsapp_kosma placeholder='Whatsapp Kosma...' value='$kelas[whatsapp_kosma]'>
      </div>
      $btn_save
    </div>
  </form>
";
?>
<script>
  $(function() {
    $('#whatsapp_kosma').keyup(function() {
      let val = $(this).val();

      if (val.length > 2) {
        if (val.substring(0, 1) == '0') {
          $(this).val('62' + val.substring(1, 100));
        }
      }

      $(this).val(
        $(this).val().replace(/[^0-9]/g, '')
      );
    });
  });
</script>
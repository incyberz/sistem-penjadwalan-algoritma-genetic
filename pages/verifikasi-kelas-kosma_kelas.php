<?php
if (isset($opt_mhs) and $opt_mhs) {

  if ($kelas['id_kosma']) {
    $status_syarat = "<b>Kosma:</b> $kelas[nama_kosma] |  +$kelas[whatsapp_kosma] $img_check <span class=btn_aksi id=form_ubah_id_kosma__toggle>$img_edit</span>";
    $hideit = 'hideit';
  } else {
    $hideit = '';
  }
  $id_kosma = $kelas['id_kosma'] ?? $kelas['nama'];
  require_once 'includes/script_whatsapp.php';
  $script_whatsapp = script_whatsapp('whatsapp_kosma');
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
    $script_whatsapp
  ";
} // jika ada opsi mhs

<?php
if ($kelas['label']) {
  $status_syarat = "<b>Label:</b> $kelas[label] $img_check <span class=btn_aksi id=form_ubah_label__toggle>$img_edit</span>";
  $hideit = 'hideit';
} else {
  $hideit = '';
}
$label = $kelas['label'] ?? $kelas['nama'];
$input_syarat = "
  <form method=post class=$hideit id=form_ubah_label>
    <div class='flexy wadah gradasi-kuning mt1'>
      <div><b>Label:</b></div>
      <div>
        <input class='form-control' name=label value='$label'>
      </div>
      $btn_save
    </div>
  </form>
";

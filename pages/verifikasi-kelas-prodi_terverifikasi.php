<?php
if ($kelas['status_prodi']) {
  $status_syarat = "<b>Status Prodi:</b> $kelas[status_prodi] $img_check <span class=btn_aksi id=form_ubah_status_prodi__toggle>$img_edit</span>";
  $hideit = 'hideit';
} else {
  $hideit = '';
}
$status_prodi = $kelas['status_prodi'] ?? $kelas['nama'];
$status_prodi = $kelas['status_prodi'] ?? $unverified;

$input_syarat = "
  <form method=post class=$hideit id=form_ubah_status_prodi>
    <div class='flexy wadah gradasi-kuning mt1'>
      <div><b>Status prodi $kelas[prodi]:</b></div>
      <div>$status_prodi</div>
      $btn_save
    </div>
  </form>
";

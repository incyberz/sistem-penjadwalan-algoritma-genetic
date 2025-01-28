<?php
if ($kelas['wa_grup']) {
  $status_syarat = "<b>Wa Grup:</b> <a href='$kelas[wa_grup]' target=_blank>$kelas[wa_grup]</a> $img_check <span class=btn_aksi id=form_ubah_wa_grup__toggle>$img_edit</span>";
  $hideit = 'hideit';
} else {
  $hideit = '';
}
$input_syarat = "
  <form method=post class=$hideit id=form_ubah_wa_grup>
    <div class='flexy wadah gradasi-kuning mt1'>
      <div><b>Link WA Grup:</b></div>
      <div>
        <input required minlength=35 maxlength=100 class='form-control' name=wa_grup value='$kelas[wa_grup]' placeholder='link grup wa...'>
        <div class='mt1 f12 abu miring'>Copas dari Grup WA > Members > Invite to Group via Link > Copy Link</div>
      </div>
      $btn_save
    </div>
  </form>
";

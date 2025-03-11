<?php
echo "Login success...

  <div class='mdl-card mdl-shadow--2dp'>

    <div class='mdl-card__supporting-text'>

      <div class='mdl-stepper-horizontal-alternative'>
        <div class='mdl-stepper-step active-step step-done'>
          <div class='mdl-stepper-circle'><span>1</span></div>
          <div class='mdl-stepper-title'>Register</div>
          <div class='mdl-stepper-bar-left'></div>
          <div class='mdl-stepper-bar-right'></div>
        </div>
        <div class='mdl-stepper-step active-step editable-step'>
          <div class='mdl-stepper-circle'><span>2</span></div>
          <div class='mdl-stepper-title'>Verifikasi Akun</div>
          <div class='mdl-stepper-optional'>sedang...</div>
          <div class='mdl-stepper-bar-left'></div>
          <div class='mdl-stepper-bar-right'></div>
        </div>
        <div class='mdl-stepper-step'>
          <div class='mdl-stepper-circle'><span>3</span></div>
          <div class='mdl-stepper-title'>Ubah Password</div>
          <div class='mdl-stepper-optional'>...</div>
          <div class='mdl-stepper-bar-left'></div>
          <div class='mdl-stepper-bar-right'></div>
        </div>
        <div class='mdl-stepper-step'>
          <div class='mdl-stepper-circle'><span>4</span></div>
          <div class='mdl-stepper-title'>Ubah Profile</div>
          <div class='mdl-stepper-optional'>Optional</div>
          <div class='mdl-stepper-bar-left'></div>
          <div class='mdl-stepper-bar-right'></div>
        </div>
      </div>

    </div>

  </div>      

  <hr>
  <div class='alert alert-danger'>Status Akun Anda belum terverifikasi.</div>
";

$nama_nospace = str_replace(' ', '-', $d['nama']);
$link_verif = "$nama_server?verifikasi_whatsapp&nama=$nama_nospace&username=$d[username]&role=$d[role]&whatsapp=$d[whatsapp]";

$text_asal = "```================================\nREQUEST VERIFIKASI AKUN\nfrom: $d[whatsapp] | UNCHECKED!\n================================```\n\nYth. Petugas Akademik ($petugas_default[nama]),\n\nMohon verifikasi akun saya atas nama:\n- *nama:* $d[nama]\n- *username:* $d[username]\n- *role:* $d[role] \n\nTerimakasih.\n\nLink:\n$link_verif$text_wa_from";
$preview = str_replace("\n\n", '<br>.<br>', $text_asal);
$preview = str_replace("\n", '<br>', $preview);
$preview = str_replace('```', '', $preview);

$text_wa = urlencode($text_asal);

$link_wa = "$https_api_wa?phone=$petugas_default[whatsapp]&text=$text_wa";

echo "
  <div class='card p-2'>
    <ul>
      <li>
        <b>Nama:</b> $d[nama]
      </li>
      <li>
        <b>Username:</b> $d[username]
      </li>
      <li>
        <b>Password:</b> $d[username] <i>(default password)</i>
      </li>
      <li>
        <b>Role:</b> $d[role]
      </li>
      <li>
        <b>Status:</b> <i style='color:red'>unverified</i>
      </li>
    </ul>
    <div class='card p-2 wa_preview' >$preview</div>
    <a target=_blank href='$link_wa' class='btn btn-primary w-100 mt-2'>Kirim Link Verifikasi ke Petugas</a>
  </div>
  <div class='mt-2 text-center'><a href='?logout' onclick='return confirm(`Logout?`)'>Logout</a></div>

";

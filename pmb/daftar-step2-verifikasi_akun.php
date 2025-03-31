<?php
if ($pmb['whatsapp_status']) {
  $whatsapp_show = '<span class=consolas>628 ... ' . substr($akun['whatsapp'], -4) . '</span>';
  echo "
    <form method=post class='card'>
      <div class='card-header bg-success putih tengah'>Kontak Whatsapp sudah Terverifikasi</div>
      <div class='card-body'>
        <ul>
          <li>
            <b>Nama:</b> $akun[nama]
          </li>
          <li>
            <b>Username:</b> $akun[username]
          </li>
          <li>
            <b>Whatsapp:</b> $whatsapp_show
          </li>
        </ul>
        <button class='btn btn-primary w-100' name=btn_next_step value=$get_step>Next Step</button>
      </div>
    </form>
  ";
} else {
  alert('Status Akun Anda belum terverifikasi.');
  $unverified = "<i style='color:red'>unverified</i>";


  $nama_nospace = str_replace(' ', '-', $akun['nama']);
  $link_verif = "$nama_server?verifikasi_pmb&nama=$nama_nospace&username=$akun[username]&whatsapp=$akun[whatsapp]";

  $text_asal = "```===========================\nREQUEST VERIFIKASI AKUN PMB\nfrom: $akun[whatsapp] | UNCHECKED!\n===========================```\n\nYth. Petugas Akademik ($petugas_default[nama]),\n\nMohon verifikasi akun saya atas nama:\n- *nama:* $akun[nama]\n- *username:* $akun[username]\n- *asal-sekolah:* $akun[asal_sekolah] \n\nTerimakasih.\n\nLink:\n$link_verif$text_wa_from";
  $preview = str_replace("\n\n", '<br>.<br>', $text_asal);
  $preview = str_replace("\n", '<br>', $preview);
  $preview = str_replace('```', '', $preview);

  $text_wa = urlencode($text_asal);

  $link_wa = "$https_api_wa?phone=$petugas_default[whatsapp]&text=$text_wa";


  echo "
    <div class='card p-2'>
      <ul>
        <li>
          <b>Nama:</b> $akun[nama]
        </li>
        <li>
          <b>Username:</b> $akun[username]
        </li>
        <li>
          <b>Whatsapp Status:</b> $unverified
        </li>
      </ul>
      <div class='card p-2 wa_preview' >$preview</div>
      <a target=_blank href='$link_wa' class='btn btn-primary w-100 mt-2'>Kirim Link Verifikasi ke Petugas</a>
    </div>
    <div class='mt-2 text-center mb-4'>
      <a href='?daftar&step=3' onclick='return confirm(`Skip proses ini?`)'>Skip Verifikasi</a> 
    </div>
  
  ";
}

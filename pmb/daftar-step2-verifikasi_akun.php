<?php
if ($pmb['whatsapp_status']) {

  $s = "SELECT *,
  (
    SELECT COUNT(1) FROM tb_kanal
    WHERE jenis=a.jenis) jenis_ini_count,  
  (
    SELECT COUNT(1) FROM tb_kanal_jenis
    WHERE media=b.media) media_ini_count 
  FROM tb_kanal a 
  JOIN tb_kanal_jenis b ON a.jenis=b.jenis
  JOIN tb_kanal_media c ON b.media=c.media
  ORDER by c.nomor,b.nomor,a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $input_media = '';
  $rinput_jenis = [];
  $rinput_kanal = [];
  $last_media = '';
  $last_jenis = '';
  // $last_kanal = '';
  $rjumlah_jenis_per_media = [];
  $rjumlah_kanal_per_jenis = [];
  while ($d = mysqli_fetch_assoc($q)) {
    $rjumlah_jenis_per_media[$d['media']] = $d['media_ini_count'];
    $rjumlah_kanal_per_jenis[$d['jenis']] = $d['jenis_ini_count'];

    # ============================================================
    # RADIO MEDIA
    # ============================================================
    if ($last_media != $d['media']) {
      $rinput_jenis[$d['media']] = ''; // inisialisasi jenis
      $input_media .= "
        <input
          type='radio'
          class='btn-check input-media'
          name='input-media'
          id='input-media--$d[media]'
          value='$d[media]'
          required
        />
        <label class='btn btn-outline-primary' for='input-media--$d[media]'>$d[media]</label>
      ";
    }
    $last_media = $d['media'];

    # ============================================================
    # RADIO JENIS
    # ============================================================
    if ($last_jenis != $d['jenis']) {
      $rinput_kanal[$d['jenis']] = ''; // inisialisasi kanal
      if ($d['media_ini_count'] > 3) { // group radio tidak muat, radios pakai normal style
        $rinput_jenis[$d['media']] .= "
          <div class='mb1 '>
            <input
              type='radio'
              class='input-jenis input-jenis--$d[media]'
              name='input-jenis'
              id='input-jenis--$d[jenis]'
              value='$d[jenis]'
            />
            <label class='hover' for='input-jenis--$d[jenis]'>$d[jenis]</label>
          </div>
        ";
      } else { // tiga radio dibuat group
        $rinput_jenis[$d['media']] .= "
          <input
            type='radio'
            class='btn-check input-jenis input-jenis--$d[media]'
            name='input-jenis'
            id='input-jenis--$d[jenis]'
            value='$d[jenis]'
          />
          <label class='btn btn-outline-primary' for='input-jenis--$d[jenis]'>$d[jenis]</label>
        ";
      }
    }
    $last_jenis = $d['jenis'];



    # ============================================================
    # RADIO KANAL
    # ============================================================
    if ($d['jenis_ini_count'] > 3) { // group radio tidak muat, radios pakai normal style
      $rinput_kanal[$d['jenis']] .= "
        <div class='mb1 '>
          <input
            type='radio'
            class='input-kanal input-kanal--$d[jenis]'
            name='id_kanal'
            id='input-kanal--$d[kanal]'
            value='$d[id]'
          />
          <label class='hover' for='input-kanal--$d[kanal]'>$d[kanal]</label>
        </div>
      ";
    } else { // tiga radio dibuat group
      $rinput_kanal[$d['jenis']] .= "
        <input
          type='radio'
          class='btn-check input-kanal input-kanal--$d[jenis]'
          name='id_kanal'
          id='input-kanal--$d[kanal]'
          value='$d[id]'
        />
        <label class='btn btn-outline-primary' for='input-kanal--$d[kanal]'>$d[kanal]</label>
      ";
    }
  }


  # ============================================================
  # LEVEL 2 JENIS
  # ============================================================
  $input_jeniss = '';
  foreach ($rinput_jenis as $media => $jeniss) {
    $Media = ucwords(strtolower($media));
    $pertanyaan = "<b class=darkblue>Dari $Media</b> yang mana?";
    if ($rjumlah_jenis_per_media[$media] > 3) { // radio pakai style biasa
      $input_jeniss .= "
          <div class='hideit mb-4 blok-jenis' id=blok-jenis--$media>
            <label class='form-label'>$pertanyaan</label>
            <div class=''>
              $jeniss
            </div>
          </div>
      ";
    } else { // radio pakai style modern
      $input_jeniss .= "
          <div class='hideit mb-4 blok-jenis' id=blok-jenis--$media>
            <label class='form-label'>$pertanyaan</label>
            <div class='btn-group w-100' role='group'>
              $jeniss
            </div>
          </div>
      ";
    }
  }

  # ============================================================
  # LEVEL 3 KANAL
  # ============================================================
  $input_kanals = '';
  foreach ($rinput_kanal as $jenis => $kanals) {
    $Media = ucwords(strtolower($jenis));
    $pertanyaan = "<b class=darkblue>Dari $Media</b> yang mana?";
    if ($rjumlah_kanal_per_jenis[$jenis] > 3) { // radio pakai style biasa
      $input_kanals .= "
          <div class='hideit mb-4 blok-kanal' id=blok-kanal--$jenis>
            <label class='form-label'>$pertanyaan</label>
            <div class=''>
              $kanals
            </div>
          </div>
      ";
    } else { // radio pakai style modern
      $input_kanals .= "
          <div class='hideit mb-4 blok-kanal' id=blok-kanal--$jenis>
            <label class='form-label'>$pertanyaan</label>
            <div class='btn-group w-100' role='group'>
              $kanals
            </div>
          </div>
      ";
    }
  }


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
        <hr>
        
        <div class='mb-4'>
          <label class='form-label'>Darimana Anda mendapat info PMB ini?</label>
          <div class='btn-group w-100' role='group'>
            $input_media
          </div>
        </div>

        $input_jeniss
        $input_kanals

        <button class='btn btn-primary w-100' name=btn_next_step value=$get_step>Next Step</button>
      </div>
    </form>
  ";
} else {
  alert('Status Akun Anda belum terverifikasi.');
  $unverified = "<i style='color:red'>unverified</i>";


  $nama_nospace = str_replace(' ', '-', $akun['nama']);
  $link_verif = "$nama_server?verifikasi_pmb&nama=$nama_nospace&username=$akun[username]&whatsapp=$akun[whatsapp]";

  $now = date('Y-m-d H:i:s');
  $text_asal = "```===========================\nREQUEST VERIFIKASI AKUN PMB\nfrom: $akun[whatsapp] | UNCHECKED!\n===========================```\n\nYth. Petugas Akademik ($petugas_default[nama]),\n\nMohon verifikasi akun saya atas nama:\n- *nama:* $akun[nama]\n- *username:* $akun[username]\n- *asal-sekolah:* $akun[asal_sekolah] \n\nTerimakasih.\n\nLink:\n$link_verif\n\n```From: Smart PMB System \nat $now```";
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
?>
<script>
  $(function() {
    $('.input-media').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id, tid);

      $('.blok-kanal').slideUp();
      $('.blok-jenis').slideUp();
      $('#blok-jenis--' + id).slideDown();

      $('.input-jenis').prop('required', 0);
      $('.input-jenis--' + id).prop('required', 1);
    });

    $('.input-jenis').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id, tid);

      $('.blok-kanal').slideUp();
      $('#blok-kanal--' + id).slideDown();

      $('.input-kanal').prop('required', 0);
      $('.input-kanal--' + id).prop('required', 1);
    });
  })
</script>
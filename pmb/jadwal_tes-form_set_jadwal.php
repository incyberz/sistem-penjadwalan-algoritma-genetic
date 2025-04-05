<?php
$input_waktu_sama = $tanggal ? "
  <input class='hideit' name='waktu_sama' value=0>
" : "
  <label class='d-block mb3'>
    <input type='checkbox' name='waktu_sama' checked value=1>
    <span class='f14'>Waktu Tes lainnya di hari dan lokasi yang sama.</span>
  </label>
";

$form_set_jadwal = "
  <span class='d-inline-block ml4 btn btn-sm btn-info putih hover btn-aksi' id=form-set-$d[id]--toggle>$Set_Jadwal</span>
  <form method=post id='form-set-$d[id]' class='hideit bordered br5 gradasi-kuning my-3 p2'>
    <div class='mb3'>
      <div class='f12 mb1'>Tanggal Tes</div>
      <input type='date' name='tanggal' required min='$today' class='form-control' value='$tanggal'>
    </div>

    <div class='mb3'>
      <div class='f12 mb1'>Jam</div>
      <input type='time' name='jam' required min='07:30' class='form-control' value='$jam'>
    </div>

    <div class='mb3'>
      <div class='f12 mb1'>Durasi (menit)</div>
      <input type='number' name='durasi' required min='0' max='240' class='form-control' value='$durasi'>
      <div class='f12 mt1'>Isi 0 jika waktu flexible s.d selesai</div>
    </div>

    <div class='mb3'>
      <div class='f12 mb1'>Lokasi</div>
      <input type='text' name='lokasi' required minlength='2' maxlength='30' class='form-control' value='$lokasi'>
    </div>
    $input_waktu_sama
    <input class='hideit' name='id_jadwal_tes' value=$id_jadwal_tes>
    <button class='btn btn-primary w-100' name='btn_set_jadwal' value=$id_tes>$Set_Jadwal</button>
  </form>
";

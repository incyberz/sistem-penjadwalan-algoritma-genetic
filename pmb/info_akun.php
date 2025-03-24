<?php
$info_akun = "
  <div class='card mb2'>
    <div class='card-header bg-primary text-white text-center'>
      Info Akun
    </div>
    <div class='card-body'>
      <div class='mb-3'>
        <label for='username' class='form-label'>Username</label>
        <div class='input-group'>
          <span class='input-group-text'><i class='bi bi-person-circle'></i></span>
          <input type='text' class='form-control' id='username' value='$username' disabled>
        </div>
      </div>

      <div class='mb-3'>
        <label for='nama' class='form-label'>Nama</label>
        <div class='input-group'>
          <span class='input-group-text'><i class='bi bi-person-fill'></i></span>
          <input type='text' class='form-control input-editable' id='akun-nama' name=nama placeholder='Masukkan Nama' required minlength='3' maxlength='30' value='$akun[nama]'>
        </div>
      </div>
    </div>
  </div>
";

<?php
echo "
<div class='btn_aksi pointer' id='tambah_mk_aktif__toggle'>$img_add Tambah MK Aktif</div>
<div id='tambah_mk_aktif' class='hideit wadah mt2 gradasi-kuning'>
  <div class='mb2 darkblue f20'>Cara Menambahkan MK Aktif</div>
  <p>
    Penambahan (Assign) MK pada Kurikulum aktif dapat Anda lakukan pada
    <a href='?struktur_kurikulum'>Struktur Kurikulum</a>
  </p>
  <div class='row'>
    <div class='col-6'>
      <div class='bg-white p2 br5'>
        <div class='darkblue f18'>Cara 1 Assign dari MK Lama</div>
        <p>Untuk MKDU atau MK Khusus yang sudah ada di database dapat Anda Assign pada Struktur Kurikulum yang sedang aktif.</p>
      </div>
    </div>
    <div class='col-6'>
      <div class='bg-white p2 br5'>
        <div class='darkblue f18'>Cara 2 Tambah MK Baru</div>
        <p class='mb1'>
          Jika MK tersebut tidak ada pada database. Pastikan tidak ada duplikasi Nama Mata Kuliah, nama MK wajib unik. Sistem akan melakukan 2 proses:
        <ol>
          <li>Menambahkan MK baru; dan </li>
          <li>Assign MK tersebut ke Struktur Kurikulum yang Anda pilih.</li>
        </ol>
        </p>
      </div>
    </div>
  </div>
  <div class='mt4'>
    <a href='?struktur_kurikulum'>Go to Struktur Kurikulum</a>
  </div>
</div>
";

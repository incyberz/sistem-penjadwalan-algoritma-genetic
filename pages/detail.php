<style>
  .td_editable {
    color: green;
    cursor: pointer;
    background: white !important;
  }

  .td_editable:hover {
    color: blue;
    background: linear-gradient(#fef, #fcf) !important;
  }
</style>
<?php
$get_tb = $_GET['tb'] ?? udef('tb');
$get_id = $_GET['id'] ?? udef('id');
set_title("Detail $get_tb");

if (!key_exists($get_tb, $arr_sql)) udef("tb=$get_tb");

$editables = [
  'ta' => [
    'awal' => true,
    'akhir' => true,
  ],
  'prodi' => [
    'nama' => true,
    'singkatan' => true,
    'fakultas' => true,
  ],
  'kurikulum' => [
    'nama' => true,
  ],
  'mk' => [
    'kode' => true,
    'nama' => true,
    'sks' => true,
    'deskripsi' => true,
  ],
  'kelas' => [
    'nama' => true,
    'kapasitas' => true,
    'counter' => true,
  ],
  'dosen' => [
    'nama' => true,
    'nidn' => true,
    'gelar_depan' => true,
    'gelar_belakang' => true,
    'whatsapp' => true,
    'alamat' => true,
  ],
  'ruang' => [
    'nama' => true,
    'kapasitas' => true,
    'lokasi' => true,
    'jenis' => true,
  ],
  'petugas' => [
    'nama' => true,
    'whatsapp' => true,
  ],
];

$s = "SELECT a.* 
FROM tb_$get_tb a WHERE id='$get_id'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    foreach ($d as $key => $value) {
      // if (
      //   $key == 'id'
      //   || $key == 'date_created'
      // ) continue;

      $editable = ($editables[$get_tb][$key] ?? null) ? 'td_editable' : null;
      $kolom = key2kolom($key);
      $tr .= "
        <tr>
          <td>$kolom</td>
          <td class='$editable'>$value</td>
        </tr>
      ";
    }
  }
}

$tb = $tr ? "
  <div class='wadah gradasi-hijau'>
    <table class='table'>
      $tr
    </table>
  </div>
" : alert("Data [$get_tb] dengan id [$get_id] tidak ditemukan.");
echo "
  <div>
    <span onclick='history.go(-1)' class='inline-block mr2'>
      $img_prev
    </span>
    <i class=abu>detail of</i> <span class='upper darkblue f30 inline-block ml2'>$get_tb</span>
  </div>
  <hr>
  $tb
";

alert("Maaf, Fitur editing pada detail item belum diimplementasikan. Gunakan dahulu fitur hapus dan add!");

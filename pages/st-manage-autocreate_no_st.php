<?php
$s2 = "SELECT MAX(no_st) as max_no_st FROM tb_st 
WHERE verif_date is not null 
AND (verif_date >= '$tahun-1-1' AND verif_date <= '$tahun-12-31')
";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q2);
$max_no_st = $d['max_no_st'];
if ($st['no_st']) {
  $no_st_integer = $st['no_st'];
} else {
  $max_no_st++;
  $no_st_integer = $max_no_st;
}

$no_st_zerofill = sprintf('%0' . $num_digit . 'd', $no_st_integer);
$nomor_st = "$no_st_zerofill/$info_fakultas/$info_kampus/$bulan_romawi/$tahun";

# ============================================================
# AUTO CREATE NOMOR SURAT TUGAS
# ============================================================
$tahun = date('Y', strtotime($st['verif_date']));
alert("Sedang Auto-Created Nomor Surat untuk tahun $tahun", 'info');



$s = "UPDATE tb_st SET no_st = $max_no_st WHERE id='$id_st'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$pesan = '<b class=green>telah tersimpan</b>';


echo "
  <div class='wadah gradasi-toska'>
    <div>Rule Penamaan Nomor Surat Tugas yaitu: | <span class='pointer biru consolas' onclick='alert(`Rule Nomor Surat Tugas in development.`)'>Ubah Rule $img_edit</span></div>
    <ul>
      <li><b>Lebar Digit:</b> 4</li>
      <li><b>Separator:</b> garis miring \"/\"</li>
      <li><b>Reset Counter per:</b> tahun ini (aktual)</li>
      <li>
        <b>Elemen nomor</b> terdiri dari:
        <ol>
          <li><b>Counter:</b> $no_st_zerofill (unique, auto)</li>
          <li><b>Info Fakultas:</b> $st[fakultas]</li>
          <li><b>Info Kampus:</b> E-UM</li>
          <li><b>Bulan Romawi:</b> I</li>
          <li><b>Tahun Aktual:</b> $tahun</li>
        </ol>
      </li>
    </ul>

    <div class=tengah>Nomor Surat Tugas baru $pesan.</div>
    <input disabled class='form-control f30 tengah consolas mt1' style='font-size:30px' value='$nomor_st'>
    <button class='btn btn-info w-100 mt2' onclick=location.reload()>OK</button>

  </div>

";

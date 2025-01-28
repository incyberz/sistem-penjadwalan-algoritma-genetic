<?php
$s = "SELECT 
  a.*,
  (SELECT gender FROM tb_user WHERE id=a.id_user) gender, 
  (SELECT image FROM tb_user WHERE id=a.id_user) image 
  FROM tb_dosen a WHERE a.id=$id_dosen_wali";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$dosen_wali = mysqli_fetch_assoc($q);
if ($dosen_wali) {
  if ($dosen_wali['image']) {
    $image_dosen_wali = "<img class='image image_dosen' src='assets/img/dosen/$dosen_wali[image]' />";
  } else {
    $image_dosen_wali = '';
  }

  $Bapak = '';
  if ($dosen_wali['gender'] == 'L') $Bapak = 'Bapak';
  if ($dosen_wali['gender'] == 'P') $Bapak = 'Ibu';
  $gelar = $dosen_wali['gelar_belakang'] ? ", $dosen_wali[gelar_belakang]" : '';
  $gelar_depan = $dosen_wali['gelar_depan'] ? "$dosen_wali[gelar_depan] " : '';

  $nama = ucwords(strtolower($dosen_wali['nama']));
  $nama_dosen_wali = "$gelar_depan$nama$gelar";

  if ($dosen_wali['whatsapp']) {
    $text_wa = urlencode("Yth. Dosen Wali $Bapak $nama_dosen_wali,%0a%0a");
    $link_wa = "<a target=_blank href='$api_wa?phone=$dosen_wali[whatsapp]&text=$text_wa'>$img_wa</a>";
  } else {
    $link_wa = "<i onclick='alert(`Belum ada whatsapp Dosen Wali.`)'>$img_wa_disabled</a>";
  }
  $info_dosen_wali = "
      <div class='border-top border-bottom pt2 pb2 mb2'>  
        <div class=mb2>$image_dosen_wali</div>
        <a target=_blank href='?detail&tb=dosen&id=$dosen_wali[id]'>$nama_dosen_wali</a> $link_wa  
      </div>
    ";
} else {
  $info_dosen_wali = div_alert('danger', 'Belum ada info dosen wali.');
}

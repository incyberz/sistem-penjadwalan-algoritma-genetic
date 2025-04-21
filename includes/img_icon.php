<?php
function img_icon($nama, $w = 20, $h = null)
{
  $h = $h ?? $w;
  $me = "assets/img/icon/$nama.png";
  $width = $w . 'px';
  $height = $h . 'px';
  if (file_exists($me)) {
    return "<img class=img_icon src='$me' width=$width height=$height />";
  } else {
    // coba di parent folder htdocs 
    $me = "../assets/img/icon/$nama.png";
    if (file_exists($me)) return "<img class=img_icon src='$me' width=$width height=$height />";
    return "<span class='red f12'>icon $nama not-found</span>";
  }
}

$img_check = img_icon('check');
$img_edit = img_icon('edit');
$img_add = img_icon('add');
$img_detail = img_icon('detail');
$img_delete = img_icon('delete');
$img_delete_disabled = img_icon('delete_disabled');
$img_hapus = img_icon('delete');
$img_login_as = img_icon('login_as');
$img_prev = img_icon('prev');
$img_warning = img_icon('warning');
$img_save = img_icon('save');
$img_wa = img_icon('wa');

<?php
function set_title($text)
{
  echo '<script>$(function(){$("title").text("' . $text . '");})</script>';
}

function set_h2($judul, $sub_judul = '', $href_back = '')
{
  set_title(ucwords($judul));
  $link = !$href_back ? '' : "
    <div class='mt2'>
      <a href='$href_back'>
        <img src='assets/img/icon/prev.png' class=img_icon alt='Back'>
      </a>
    </div>
  ";

  echo "
    <div class='section-title' data-aos='fade'>
      <h2 id=judul>$judul</h2>
      <p id=sub_judul>$sub_judul$link</p>
    </div>
  ";
}

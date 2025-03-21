<?php
function alert($pesan, $class = 'danger', $style = '', $is_echo = true)
{
  $string = "<div class='mt2 mb2 alert alert-$class' style='$style'>$pesan</div>";
  if ($is_echo) {
    echo $string;
    return '';
  }
  return $string;
}

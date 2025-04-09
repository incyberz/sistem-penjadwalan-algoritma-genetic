<?php
function alert($pesan, $class = 'danger', $style = '', $is_echo = true)
{
  $string = "<div class='my-4 alert alert-$class' style='$style'>$pesan</div>";
  if ($is_echo) {
    echo $string;
    return '';
  }
  return $string;
}

function sukses($pesan, $class = 'success', $style = null)
{
  alert($pesan, $class, $style);
}

function stop($pesan, $exit = true)
{
  alert($pesan);
  if ($exit) exit;
}

# ===============================
# MATIKAN PROSES
# ===============================
function mati($field, $pesan = 'undefined.', $style = 'color:red; font-weight:bold')
{
  echo "<div style='$style'>field [ $field ] $pesan</div>";
  exit;
}

function kosong($field, $pesan = ' tidak boleh dikosongkan.')
{
  mati($field, $pesan);
}

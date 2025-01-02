<?php
function blok_jadwal($last_semester, $thead, $tr_mk)
{
  return "
    <div class='col-6'>
      <h4 class='darkblue mt2'>Semester $last_semester</h4>
      <table class='table table-hover table-striped'>
        $thead
        $tr_mk
      </table>
    </div>
  ";
}

function nama_hari($date)
{
  $arr = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  return $arr[date('w', strtotime($date))];
}
function nama_bulan($date)
{
  $arr = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  ];
  return $arr[intval(date('m', strtotime($date))) - 1];
}

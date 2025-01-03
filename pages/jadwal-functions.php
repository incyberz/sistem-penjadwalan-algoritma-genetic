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

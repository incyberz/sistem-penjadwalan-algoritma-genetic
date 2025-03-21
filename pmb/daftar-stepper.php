<?php
$steps = '';
$count_step_show = 0;
foreach ($rstep as $no => $step) {
  if ($get_step - $no >= 2) continue;
  $count_step_show++;
  if ($count_step_show > 3) continue;
  $active_step = $no <= $get_step ? 'active-step' : '';

  $no_and_title = "
    <div class='mdl-stepper-circle'><span>$no</span></div>
    <div class='mdl-stepper-title'>$step</div>
  ";
  $link = $get_step == $no ? $no_and_title : "<a href='./?daftar&step=$no'>$no_and_title</a>";

  $steps .= "
    <div class='mdl-stepper-step $active_step'>
      $link
      <div class='mdl-stepper-optional'></div>
      <div class='mdl-stepper-bar-left'></div>
      <div class='mdl-stepper-bar-right'></div>
    </div>
  ";
}
?>
<link rel="stylesheet" href="../assets/css/stepper.css">
<div class="step-of">Step <?= $get_step ?> of <?= count($rstep) ?></div>
<div class='mdl-card mdl-shadow--2dp'>
  <div class='mdl-card__supporting-text'>
    <div class='mdl-stepper-horizontal-alternative'>
      <?= $steps ?>
    </div>

  </div>

</div>
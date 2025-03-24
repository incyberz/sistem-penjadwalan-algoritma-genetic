<style>
  #form_next_step {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    padding: 10px 15px;
    background: white;
  }
</style>
<form method=post id="form_next_step" class="hideit">
  <button class="btn btn-primary" name=btn_set_next_step value='<?= $next_step ?>'>Set Next Step: <?= $nama_next_step ?></button>
</form>
<h5 class='alert alert-success'>Pendaftaran Sukses</h5>
<form method=post class=card>
  <p class='card-header bg-primary text-white text-center'>Silahkan Anda buat password:</p>
  <div class=card-body>
    <div class="mb-3">Username: <b><?= $post_username ?></b></div>
    <div class="mb-3">Password
      <input class='form-control d-inline input_password' name=password id=password type=password required minlength=3 maxlength=20>
    </div>
    <div class="mb-3">Confirm Password
      <input class='form-control d-inline input_password' name=cpassword id=cpassword type=password required minlength=3 maxlength=20>
    </div>
    <button class='btn btn-primary w-100' id=btn_set_password name=btn_set_password value=<?= $post_username ?> disabled>Set Password</button>
  </div>
</form>
<script>
  $(function() {
    $('.input_password').keyup(function() {
      let password = $('#password').val();
      let cpassword = $('#cpassword').val();
      if (password === cpassword) {
        $('#btn_set_password').prop('disabled', 0);
        $('#btn_set_password').text('Set Password');
      } else {
        $('#btn_set_password').prop('disabled', 1);
        $('#btn_set_password').text('Confirm Password belum sama.');
      }
    })
  })
</script>
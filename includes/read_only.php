<?php
function read_only()
{
  echo "
    <div class='alert alert-warning'>Mode Read Only</div>
    <script>
      $(function() {
        $('select').prop('disabled', 1);
        $('button').prop('disabled', 1);
        $('input').prop('disabled', 1);
        console.log('Mode Read Only');
      });
    </script>
  ";
}

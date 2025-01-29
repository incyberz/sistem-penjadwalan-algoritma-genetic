<?php
function script_whatsapp($id_html)
{
  return "
    <script>
      $(function() {
        $('#$id_html').keyup(function() {
          let val = $(this).val();

          if (val.length > 2) {
            if (val.substring(0, 2) != '62') {
              $(this).val('62' + val.substring(1, 100));
            }
          }

          $(this).val(
            $(this).val().replace(/[^0-9]/g, '')
          );
        });
      });
    </script>  
  ";
}

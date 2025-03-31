<div class="tengah abu f14 mt2" id=toggle_show>
  <span class="hover">Show Grafik Pendaftar Harian</span>
</div>
<div id="hasil_ajax"></div>

<script>
  $(function() {
    $('#toggle_show').click(function() {
      $('#toggle_show').slideUp();
      $('#grafik_pendaftar').slideDown();

      // $.ajax({
      //   url: 'grafik_pendaftar.php',
      //   success: function(a) {
      //     $('#hasil_ajax').html(a)
      //   }
      // })
    })
  })
</script>

<?php
include 'grafik_pendaftar.php';

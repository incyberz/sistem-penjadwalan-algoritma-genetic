<script>
  $(function() {
    $('.btn-aksi').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      if (id == 'toggle') {
        $('#' + aksi).slideToggle();
      } else {
        alert(`Belum ada handler untuk btn_aksi event [${id}]`);
      }
    });
  });
</script>
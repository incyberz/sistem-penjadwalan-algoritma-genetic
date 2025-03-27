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
    $('.btnSave').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('-');
      let aksi = rid[0];
      let tb = rid[1];
      let field = rid[2];
      let old_value = $('#old_value-' + tb + '-' + field).text();
      let show_value = $('#show_value-' + tb + '-' + field).text();
      let Null = $('#null-' + tb + '-' + field).text();
      let new_value = $('#' + tb + '-' + field).val().trim();
      console.log(aksi, tb, field, old_value, show_value, new_value, Null);

    });
  });
</script>
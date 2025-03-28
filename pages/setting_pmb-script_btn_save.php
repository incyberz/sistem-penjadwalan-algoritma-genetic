<script>
  $(function() {
    $('.btnSave').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('-');
      let aksi = rid[0];
      let tb = rid[1];
      let field = rid[2];
      let acuan = rid[3];
      let acuan_val = rid[4];
      let acuan_val2 = rid[5];
      if (acuan_val2) acuan_val += '-' + acuan_val2;
      let four_id = tb + '-' + field + '-' + acuan + '-' + acuan_val;
      let old_val = $('#old_val-' + four_id).text();
      let show_value = $('#show_value-' + four_id).text();
      let Null = $('#null-' + four_id).text();
      let new_val = $('#' + four_id).val().trim();

      console.log(`aksi:${aksi}`, `tb:${tb}`, `field:${field}`, `old_val:${old_val}`, `new_val:${new_val}`, `Null:${Null}`, `acuan:${acuan}`, `acuan_val:${acuan_val}`);
      if (old_val == new_val) {
        console.log('konten sama, aborted.');
        return;
      } else {
        if (Null && new_val === '') { // boleh null value
          new_val = 'NULL';
        } else if (new_val === '') {
          console.log('new_val is empty. aborted.');
          return;
        }

        console.log(new_val);


        $.ajax({
          url: `pages/setting_pmb-ajax_set_values.php?tb=${tb}&field=${field}&old_val=${old_val}&new_val=${new_val}&acuan=${acuan}&acuan_val=${acuan_val}`,
          success: function(a) {
            if (a == 'OK') {
              $('#show_value-' + four_id).text(new_val);
              $('#old_value-' + four_id).text(new_val);
              $('#blok_edit-' + four_id).slideUp();
            } else {
              alert(a)

            }
          }
        })

      } // end not konten sama



    });
  });
</script>
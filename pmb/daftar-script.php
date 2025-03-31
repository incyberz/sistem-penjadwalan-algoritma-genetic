<script>
  function hitung_usia(tanggal_lahir) {
    const tanggal_lahir_date = new Date(tanggal_lahir);
    const tanggal_sekarang = new Date();
    const selisih_tahun = tanggal_sekarang.getFullYear() - tanggal_lahir_date.getFullYear();
    const selisih_bulan = tanggal_sekarang.getMonth() - tanggal_lahir_date.getMonth();
    const selisih_hari = tanggal_sekarang.getDate() - tanggal_lahir_date.getDate();

    if (selisih_bulan < 0 || (selisih_bulan === 0 && selisih_hari < 0)) {
      return selisih_tahun - 1 + ' tahun';
    } else {
      return selisih_tahun + ' tahun';
    }
  }

  $(function() {
    let tmp_val = '';
    $('.input-editable').focus(function() {
      tmp_val = $(this).val();
    });
    $('.input-editable').focusout(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('-');
      let tb = rid[0];
      let field = rid[1];
      let new_val = $(this).val().trim();

      if (new_val === '') {
        console.log('empty string');
      } else if (tmp_val == new_val) {
        console.log('konten sama');
      } else {

        // ========================================
        // KONFIGURASI FIELDS
        // ========================================
        let go_save = false;
        let len = new_val.length;
        let nilai = parseInt(new_val);
        if (tb == 'biodata') {
          if (field == 'nomor_ktp' && len == 16) go_save = true;
          if ((
              field == 'tempat_lahir' ||
              field == 'desa' ||
              field == 'blok_dusun' ||
              field == 'suku' ||
              field == 'hobby_olahraga' ||
              field == 'hobby_lainnya' ||
              field == 'cita_cita'
            ) && len >= 3 && len <= 30) go_save = true;
          if ((
              field == 'kontak_whatsapp_lainnya'
            ) && ((len >= 11 && len <= 14 || new_val == '-'))) go_save = true;
          if ((
              field == 'anak_ke' ||
              field == 'total_saudara' ||
              field == 'rt' ||
              field == 'rw'
            ) && nilai >= 1 && nilai <= 99) go_save = true;
          if (field == 'tanggal_lahir') {
            let usia = hitung_usia(new_val);
            if (usia >= 18 && usia <= 60) go_save = true;
          }
        } else if (tb == 'data_sekolah') {
          if ((
              field == 'alamat_sekolah' ||
              field == 'nama_sekolah' ||
              field == 'kecamatan_sekolah' ||
              field == 'jurusan' ||
              field == 'nis'
            ) && len >= 2 && len <= 30) go_save = true;
          if ((
              field == 'tahun_lulus'
            ) && nilai >= 2020 && nilai <= 2030) go_save = true;
          if ((
              field == 'no_ijazah'
            ) && ((len >= 3 && len <= 20 || new_val == '-'))) go_save = true;

        } else if (tb == 'akun') {
          if (field == 'nama' && len >= 3 && len <= 30) go_save = true;
        } else if (tb == 'data_orangtua') {
          if ((
              field == 'pekerjaan_ibu' ||
              field == 'pekerjaan_ayah' ||
              field == 'pekerjaan_wali' ||
              field == 'hubungan_dg_wali' ||
              field == 'nama_wali' ||
              field == 'nama_ayah' ||
              field == 'nama_ibu'
            ) && len >= 3 && len <= 30) go_save = true;
          if ((
              field == 'whatsapp_wali' ||
              field == 'whatsapp_ibu' ||
              field == 'whatsapp_ayah'
            ) && ((len >= 11 && len <= 14 || new_val == '-'))) go_save = true;
        }

        console.log('go_save', go_save);


        if (go_save) {
          let link_ajax = `daftar-ajax_set_values.php?tb=${tb}&field=${field}&new_val=${new_val}`
          $.ajax({
            url: link_ajax,
            success: function(a) {
              if (a.substring(0, 2) == 'OK') {
                console.log('input text saved.s');

                $('#' + tid).addClass('gradasi-hijau');
                let t = a.split('--');
                if (tb != 'akun') { // exception, tidak ada progress untuk tb_akun
                  $('#lengkap-of').text(t[1]);
                  $('#total-of').text(t[2]);
                  let persen = t[3];
                  $('#progress-bar').attr('aria-valuenow', persen);
                  $('#progress-bar').prop('style', `width:${persen}%`);
                  $('#progress-bar').text(`${persen}%`);
                  if (persen == 100) {
                    $('#form_next_step').slideDown();
                    console.log("#form_next_step.slideDown");
                  }
                }
              } else if (field == 'nomor_ktp') {
                const obj = JSON.parse(a);
                if (obj) {
                  $('#biodata-kecamatan').val(obj.nama_kec);
                  $('#biodata-tempat_lahir').val(obj.nama_kab.replace('KAB. ', ''));
                  $('#biodata-kabupaten').val(obj.nama_kab);
                  $('#biodata-provinsi').val(obj.nama_prov);
                  $('#biodata-kode_pos').val(obj.kode_pos);
                  $('#biodata-tanggal_lahir').val(obj.tl);
                  $('#biodata-gender-' + obj.gender).prop('checked', true);

                  $('#biodata-kecamatan').prop('disabled', true);
                  $('#biodata-kabupaten').prop('disabled', true);
                  $('#biodata-provinsi').prop('disabled', true);
                  $('.biodata-gender').prop('disabled', true);
                }
              } else {
                alert(a);
              }
            }
          })

        } else {
          alert(`Data baru tidak sesuai ketentuan.\nSilahkan coba lagi.\n\n - field: ${field}\n - tabel: ${tb}`);
          $(this).val(tmp_val);
          return;

        }
      }
    });
    $('.radio').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('-');
      let tb = rid[0];
      let field = rid[1];
      let new_val = rid[2];
      let link_ajax = `daftar-ajax_set_values.php?tb=${tb}&field=${field}&new_val=${new_val}`;
      console.log(link_ajax);

      $.ajax({
        url: link_ajax,
        success: function(a) {
          if (a.substring(0, 2) == 'OK') {
            // $('#' + tid).addClass('gradasi-hijau');
            let t = a.split('--');
            $('#lengkap-of').text(t[1]);
            $('#total-of').text(t[2]);
            let persen = t[3];
            $('#progress-bar').attr('aria-valuenow', persen);
            $('#progress-bar').prop('style', `width:${persen}%`);
            $('#progress-bar').text(`${persen}%`);
            if (persen == 100) {
              $('#form_next_step').slideDown();
              console.log("#form_next_step.slideDown");
            }
          } else {
            alert(a);
          }
        }
      })
    })
  })
</script>
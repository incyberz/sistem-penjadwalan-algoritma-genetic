<?php
set_title('Melengkapi Biodata');
$tb = 'biodata';
include "$tb.php";
$data = $biodata;
$progress_h3 = 'Pengisian Biodata';
$petunjuk = 'Untuk kelancaran pengisian silahkan sediakan Kartu Keluarga atau KTP Anda!';

include 'daftar-blok_pengisian_data.php';
?>

<script>
  $(function() {
    $("#akun-nama").keyup(function() {
      let val = $(this).val();
      val = val.replace(/'/g, "`"); // Ubah tanda petik menjadi backtick
      val = val.replace(/[^a-zA-Z` ]/g, ""); // Hanya huruf, spasi, dan tanda backtick
      $(this).val(val.toUpperCase()); // Ubah ke uppercase
    });

    $("#biodata-desa").keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

    $("#biodata-blok_dusun").keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

    $("#biodata-suku").keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

    $("#biodata-cita_cita").keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

    $("#biodata-hobby_olahraga").keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

    $("#biodata-hobby_lainnya").keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

    $("#biodata-kontak_whatsapp_lainnya").keyup(function() {
      let tid = $(this).prop('id');
      $('#' + tid + '-info').text('whatsapp bisnis, whatsapp saudara serumah, atau masukan strip (-) jika tidak ada.');
      let val = $(this).val();
      if (val.length >= 4) {
        val = val.replace(/[^0-9]/g, ""); // Hanya angka
        if (val.startsWith("08")) {
          val = "628" + val.substring(2);
        } else if (!val.startsWith("628") && val.length >= 4) {
          val = "";
        }
        $(this).val(val);

      }
    });
    $("#biodata-kontak_whatsapp_lainnya").focus(function() {
      let tid = $(this).prop('id');
      $('#' + tid + '-info').text('whatsapp bisnis, whatsapp saudara serumah, atau masukan strip (-) jika tidak ada.');
    });
    $("#biodata-kontak_whatsapp_lainnya").focusout(function() {
      let tid = $(this).prop('id');
      $('#' + tid + '-info').text('');
    });


    $("#biodata-nomor_ktp").keyup(function() {
      $(this).val($(this).val().replace(/[^0-9]/g, "").substring(0, 16));
      let val = $(this).val();
      if (val.length == 16) {
        $('#biodata-nomor_ktp-info').text('');
      } else {
        let separated =
          val.substring(0, 4) + '-' +
          val.substring(4, 8) + '-' +
          val.substring(8, 12) + '-' +
          val.substring(12, 16);
        $('#biodata-nomor_ktp-info').html(`Anda mengetik <span class=f30>${val.length}</span> dari 16 digit<div class=consolas>${separated}</div>`);
      }
    });
  });
</script>
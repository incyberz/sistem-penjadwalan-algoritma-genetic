<link rel="stylesheet" href="../assets/css/radio-toolbar.css">
<?php
set_title('Melengkapi Data Orangtua');
$tb = 'data_orangtua';
include "$tb.php";
include "rfield_$tb.php"; // shared array fields
$data = $data_orangtua;

$progress_h3 = 'Pengisian Data Orangtua';
$petunjuk = '';

$rpertanyaan_awal = [
  'ayah_meninggal' => [
    'pertanyaan' => 'Apakah Ayah masih hidup?',
    'opsi' => $rfield[$tb]['ayah_meninggal']['fields'],
  ],
  'ibu_meninggal' => [
    'pertanyaan' => 'Apakah Ibu masih hidup?',
    'opsi' => $rfield[$tb]['ibu_meninggal']['fields'],
  ],
  'ortu_cerai' => [
    'pertanyaan' => 'Apakah Ayah dan Ibu masih berumahtangga?',
    'opsi' => $rfield[$tb]['ortu_cerai']['fields'],
  ],
  'tinggal_dengan' => [
    'pertanyaan' => 'Sekarang tinggal dengan siapa?',
    'opsi' => $rfield[$tb]['tinggal_dengan']['fields'],
  ],
  'punya_wali' => [
    'pertanyaan' => 'Apakah Anda punya Wali yang membiayai?',
    'opsi' => $rfield[$tb]['punya_wali']['fields'],
  ],
];

$ada_null = 0;
foreach ($rpertanyaan_awal as $field => $rv) {
  if ($data_orangtua[$field] === null) {
    $ada_null = 1;
    break;
  }
}

if ($ada_null) {
  $pertanyaan_awal = '';
  foreach ($rpertanyaan_awal as $field => $rv) {
    $opsis = '';
    foreach ($rv['opsi'] as $key => $opsi) {
      $opsis .= "
        <div class=col-sm-6 id=col-$field-$key>
          <div class='pt1 pb1'>
            <div class='radio-toolbar'>
              <input type='radio' name='$field' id='$field-$key' class='pertanyaan_awal' required value='$key'>
              <label for='$field-$key'>$opsi</label>
            </div>
          </div>
        </div>
      ";
    }
    $pertanyaan_awal .= "
      <div class='pt2 pb2 tengah'>
        $rv[pertanyaan]
        <div class=row>$opsis</div>
      </div>
    ";
  }

  echo "
    <form method=post class='card'>
      <div class='card-header bg-success putih tengah'>Pertanyaan Awal</div>
      <div class='card-body'>
        $pertanyaan_awal
      </div>
      <button class='btn btn-primary w-100' name=btn_submit_data_awal_ortu>Submit Data Awal Orangtua</button>
    </form>
  ";
?>
  <style>
    .disabled {
      background: gray !important;
      cursor: not-allowed;
      color: gray !important;
      font-style: italic;
    }
  </style>
  <script>
    $(function() {
      let ayah_meninggal = false;
      let ibu_meninggal = false;
      $('.pertanyaan_awal').click(function() {
        let tid = $(this).prop('id');
        let rid = tid.split('-');
        let field = rid[0];
        let opsi = rid[1];

        if (field == 'ayah_meninggal') {
          if (opsi == '1') { // meninggal 
            ayah_meninggal = true;
            $('#ortu_cerai-0').prop('disabled', 1);
            $('#ortu_cerai-0').prop('checked', 0);
            $('#ortu_cerai-1').prop('checked', 1);
            $('#col-ortu_cerai-0').addClass('disabled');

            $('#tinggal_dengan-0').prop('disabled', 1);
            $('#tinggal_dengan-0').prop('checked', 0);
            $('#col-tinggal_dengan-0').addClass('disabled');

            $('#tinggal_dengan-1').prop('disabled', 1);
            $('#tinggal_dengan-1').prop('checked', 0);
            $('#col-tinggal_dengan-1').addClass('disabled');

          } else { // ayah hidup
            ayah_meninggal = false;

            if (!ibu_meninggal) {
              $('#ortu_cerai-0').prop('disabled', 0);
              $('#col-ortu_cerai-0').removeClass('disabled');

              $('#tinggal_dengan-0').prop('disabled', 0);
              $('#col-tinggal_dengan-0').removeClass('disabled');
            }


            $('#tinggal_dengan-1').prop('disabled', 0);
            $('#col-tinggal_dengan-1').removeClass('disabled');

          }

        } else if (field == 'ibu_meninggal') {
          if (opsi == '1') { // ibu meninggal
            ibu_meninggal = true;

            $('#ortu_cerai-0').prop('disabled', 1);
            $('#ortu_cerai-0').prop('checked', 0);
            $('#ortu_cerai-1').prop('checked', 1);
            $('#col-ortu_cerai-0').addClass('disabled');

            $('#tinggal_dengan-0').prop('disabled', 1);
            $('#tinggal_dengan-0').prop('checked', 0);
            $('#col-tinggal_dengan-0').addClass('disabled');

            $('#tinggal_dengan-2').prop('disabled', 1);
            $('#tinggal_dengan-2').prop('checked', 0);
            $('#col-tinggal_dengan-2').addClass('disabled');


          } else { // ibu hidup
            ibu_meninggal = false;

            if (!ayah_meninggal) {
              $('#ortu_cerai-0').prop('disabled', 0);
              $('#col-ortu_cerai-0').removeClass('disabled');

              $('#tinggal_dengan-0').prop('disabled', 0);
              $('#col-tinggal_dengan-0').removeClass('disabled');
            }


            $('#tinggal_dengan-2').prop('disabled', 0);
            $('#col-tinggal_dengan-2').removeClass('disabled');

          }
        } else if (field == 'tinggal_dengan') {
          if (opsi == '3') { // wali
            $('#punya_wali-0').prop('disabled', 1);
            $('#col-punya_wali-0').addClass('disabled');

            $('#punya_wali-0').prop('checked', 0);
            $('#punya_wali-1').prop('checked', 1);
          } else {
            $('#punya_wali-0').prop('disabled', 0);
            $('#col-punya_wali-0').removeClass('disabled');

          }
        }

      })
    })
  </script>
<?php
} else { // pertanyaan awal lengkap
  $li_info = '';
  foreach ($rpertanyaan_awal as $field => $rv) {
    $value = $rv['opsi'][$data_orangtua[$field]];
    if ($field == 'ayah_meninggal' || $field == 'ibu_meninggal' || $field == 'punya_wali') {
      $info = $value;
    } elseif ($field == 'ortu_cerai') {
      $info = "Orangtua $value";
    } elseif ($field == 'tinggal_dengan') {
      $info = "Saya tinggal dengan $value";
    }
    $li_info .= "
      <li>$info</li>
    ";
  }

  echo "
    <form method=post class='card mb2'>
      <div class='card-header bg-secondary putih tengah'>Info Awal</div>
      <div class=card-body>
        <ul>$li_info</ul>
        <button class='btn btn-sm btn-secondary w-100' name=btn_reset_data_ortu onclick='return confirm(`Yakin untuk reset data orangtua?`)'>Reset Data Orangtua</button>
      </div>
    </form>
  ";

  include 'daftar-blok_pengisian_data.php';
?>
  <script>
    $(function() {
      $("#data_orangtua-nama_ayah").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_orangtua-nama_ibu").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_orangtua-nama_wali").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_orangtua-hubungan_dg_wali").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_orangtua-pekerjaan_wali").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_orangtua-pekerjaan_ayah").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_orangtua-pekerjaan_ibu").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });

      // $("#data_orangtua-no_ijazah").focus(function() {
      //   let tid = $(this).prop('id');
      //   $('#' + tid + '-info').text('jika belum ada, silahkan ganti dg Nomor Surat Keterangan Lulus.');
      // });
      // $("#data_orangtua-no_ijazah").focusout(function() {
      //   let tid = $(this).prop('id');
      //   $('#' + tid + '-info').text('');
      // });

    });
  </script>
<?php } ?>
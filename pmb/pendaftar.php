<?php
include '../includes/eta.php';
include '../includes/key2kolom.php';
include 'tahun_pmb.php';
include 'gelombang_aktif.php';
include 'info_hari_ini.php';
include 'pendaftar-styles.php';
petugas_only();
set_title('Pendaftar PMB');



echo "
  $info_hari_ini
  <div class='d-flex flex-between'>
    <div class=f12 style=min-width:200px>
      <a href=?petugas>Home Petugas</a>
    </div>
    <h2 class=' tengah'>Pendaftar PMB</h2>
    <div style=min-width:200px>&nbsp;</div>
  </div>
";

$awal_bulan_ini = date('Y-m') . '-01';
$bulan_ini_show = date('F');
echo "<span id=awal_bulan_ini class=hideit>$awal_bulan_ini</span>";
echo "<span id=today class=hideit>$today</span>";

$rget = [];
$rget['active_status'] = $_GET['active_status'] ?? 1;
$rget['whatsapp_status'] = $_GET['whatsapp_status'] ?? '';
$rget['sudah_bayar'] = $_GET['sudah_bayar'] ?? '';
$rget['lulus_tes_pmb'] = $_GET['lulus_tes_pmb'] ?? '';
$rget['sudah_registrasi'] = $_GET['sudah_registrasi'] ?? '';

$get_tanggal_awal = $_GET['tanggal_awal'] ?? '';
$get_tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$get_keyword = $_GET['keyword'] ?? '';

if ($rget['sudah_registrasi']) {
  $rget['lulus_tes_pmb'] = 1;
  $rget['sudah_bayar'] = 1;
  $rget['whatsapp_status'] = 1;
  $rget['active_status'] = 1;
}

if ($rget['lulus_tes_pmb']) {
  $rget['sudah_bayar'] = 1;
  $rget['whatsapp_status'] = 1;
  $rget['active_status'] = 1;
}

if ($rget['sudah_bayar']) {
  $rget['whatsapp_status'] = 1;
  $rget['active_status'] = 1;
}

if ($rget['whatsapp_status']) {
  $rget['active_status'] = 1;
}


$rswitch = [
  'active_status' => 'Peserta Aktif',
  'whatsapp_status' => 'WA Aktif',
  'sudah_bayar' => 'Sudah Bayar',
  'lulus_tes_pmb' => 'Lulus Tes',
  'sudah_registrasi' => 'Registrasi',
];

$form_switchs = '';
foreach ($rswitch as $key => $title) {
  $checked = $rget[$key] ? 'checked' : '';
  $form_switchs .= "
    <div class='form-check form-switch'>
      <label class='hover label-toggle' id=label-toggle--$key>
        <input type='checkbox' class='form-check-input toggle' $checked id=toggle--$key>
        $title
      </label>
    </div>
  ";
}

$value_tanggal_awal = '';
$hide_tanggal_awal = 'hideit';
if ($get_tanggal_awal) {
  $value_tanggal_awal = date('Y-m-d', strtotime($get_tanggal_awal));
  $hide_tanggal_awal = '';
}

$value_tanggal_akhir = '';
$hide_tanggal_akhir = 'hideit';
if ($get_tanggal_akhir) {
  $value_tanggal_akhir = date('Y-m-d', strtotime($get_tanggal_akhir));
  $hide_tanggal_akhir = '';
}


echo "
  <div class='card mt-4'>
    <div class='card-header ' style='position:sticky;top:0;background:white'>
      <div class='d-flex flex-between gap-4 '>
        <div class='d-flex gap-4 flex-wrap'>
          $form_switchs
          <div class='d-flex gap-1'>
            <div>
              <select class='form-control form-control-sm bg-primary text-white' id=select_time>
                <option>all time</option>
                <option>di bulan ini</option>
                <option>between</option>
              </select>
            </div>
            <div>
              <input type=date class='$hide_tanggal_awal form-control form-control-sm bg-primary text-white input-tanggal' id=tanggal_awal value='$value_tanggal_awal' />
            </div>
            <div>
              <input type=date class='$hide_tanggal_akhir form-control form-control-sm bg-primary text-white input-tanggal' id=tanggal_akhir value='$value_tanggal_akhir' />
            </div>
          </div>
          <div>
            <input class='form-control form-control-sm ' id=keyword placeholder='cari...' style='max-width:100px'>
          </div>
          <div>
            <button class='btn btn-success btn-sm' id=btn_export>Export</button>
          </div>
        </div>

        <div>
          <span id=num_rows>0</span> rows
        </div>
      </div>
    </div>
    <div class='card-body' id=hasil_ajax>hasil_ajax</div>
  </div>
";

?>





















<script>
  $(function() {

    let get_csv = 0;

    $('#keyword').keyup(function() {

      let keyword = $(this).val().trim();
      let active_status = $('#toggle--active_status').prop('checked') ? 1 : 0;
      let whatsapp_status = $('#toggle--whatsapp_status').prop('checked') ? 1 : 0;
      let sudah_bayar = $('#toggle--sudah_bayar').prop('checked') ? 1 : 0;
      let lulus_tes_pmb = $('#toggle--lulus_tes_pmb').prop('checked') ? 1 : 0;
      let sudah_registrasi = $('#toggle--sudah_registrasi').prop('checked') ? 1 : 0;

      let link_ajax = 'pendaftar-ajax_pendaftar.php?keyword=' + keyword +
        '&tanggal_awal=' + $('#tanggal_awal').val() +
        '&tanggal_akhir=' + $('#tanggal_akhir').val() +
        '&sudah_registrasi=' + sudah_registrasi +
        '&lulus_tes_pmb=' + lulus_tes_pmb +
        '&sudah_bayar=' + sudah_bayar +
        '&active_status=' + active_status +
        '&whatsapp_status=' + whatsapp_status +
        '&get_csv=' + get_csv;
      // console.log(link_ajax);


      $.ajax({
        url: link_ajax,
        success: function(a) {
          let t = a.split('---');
          $('#hasil_ajax').html(t[0]);
          let num_rows = t[1] ?? 0;
          $('#num_rows').text(num_rows);
        }
      })
    });

    // =================================================
    // FORM LOAD | MAIN TRIGGER
    // =================================================
    $('#keyword').keyup();
    // =================================================

    $('#label-toggle--active_status').click(function() {

      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      let checked = $('#toggle--' + id).prop('checked');
      console.log(aksi, id, checked);
      if (checked) {
        $('.sub-' + id).show();
      } else {
        $('.sub-' + id).hide();
        $('#toggle--whatsapp_status').prop('checked', false);
        $('#toggle--sudah_bayar').prop('checked', false);
        $('#toggle--lulus_tes_pmb').prop('checked', false);
        $('#toggle--sudah_registrasi').prop('checked', false);

      }
    });
    $('.label-toggle').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      let checked = $('#toggle--' + id).prop('checked');
      if (checked) {
        if (id == 'sudah_bayar') {
          $('#toggle--whatsapp_status').prop('checked', true);
        } else if (id == 'lulus_tes_pmb') {
          $('#toggle--whatsapp_status').prop('checked', true);
          $('#toggle--sudah_bayar').prop('checked', true);
        } else if (id == 'sudah_registrasi') {
          $('#toggle--whatsapp_status').prop('checked', true);
          $('#toggle--sudah_bayar').prop('checked', true);
          $('#toggle--lulus_tes_pmb').prop('checked', true);
        }
      } else {
        if (id == 'lulus_tes_pmb') {
          $('#toggle--sudah_registrasi').prop('checked', false);

        } else if (id == 'sudah_bayar') {
          $('#toggle--sudah_registrasi').prop('checked', false);
          $('#toggle--lulus_tes_pmb').prop('checked', false);
        } else if (id == 'whatsapp_status') {
          $('#toggle--sudah_registrasi').prop('checked', false);
          $('#toggle--lulus_tes_pmb').prop('checked', false);
          $('#toggle--sudah_bayar').prop('checked', false);

        }
      }
      $('#keyword').keyup();
    });

    $('.input-tanggal').change(function() {
      $('#keyword').keyup();
    });

    $('#select_time').change(function() {
      if ($(this).val() == 'between') {
        $('.input-tanggal').fadeIn();
        $('#tanggal_awal').val($('#awal_bulan_ini').text());
        $('#tanggal_akhir').val($('#today').text());
      } else {
        $('.input-tanggal').fadeOut();
        if ($(this).val() == 'all time') {
          $('.input-tanggal').val('');
        } else { // bulan ini
          $('#tanggal_awal').val($('#awal_bulan_ini').text());
          $('#tanggal_akhir').val('');
        }
      }
      $('#keyword').keyup();
    });

    $('#btn_export').click(function() {
      get_csv = 1;
      $('#keyword').keyup();
      $(this).fadeOut();
    });

  });

  $(document).on('click', '#btn_download_csv', function() {
    $('input').prop('disabled', true);
    $('select').prop('disabled', true);
    $('#info_reload').fadeIn();
    setTimeout(() => {
      location.reload();
    }, 5000);
  })
</script>
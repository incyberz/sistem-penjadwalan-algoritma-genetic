<?php
set_title('Melengkapi Data Sekolah');
$id_sekolah = $_GET['id_sekolah'] ?? '';
$is_new_sekolah = $id_sekolah == 'new' ? 1 : 0;

$tb = 'data_sekolah';
include "$tb.php";
include "rfield_$tb.php"; // shared array fields

$data = $data_sekolah;
if ($data['id_sekolah'] and !$id_sekolah) jsurl("./?daftar&step=4&id_sekolah=$data[id_sekolah]");
if (!$id_sekolah) {
  $sekolah = [];
  echo "
    <div class=card>
      <div class='card-header bg-info putih tengah'>Ketik dan Pilih Sekolah</div>
      <div class='card-body gradasi-kuning'>
        <input id=keyword class='form-control tengah'>
        <div id=list_sekolah class='mt1 bordered p2'></div>
      </div>
    </div>
  ";
?>
  <script>
    $(function() {
      $('#keyword').keyup(function() {
        let keyword = $(this).val();
        if (keyword.length < 3) {
          $('#list_sekolah').html("<p class='abu miring tengah'>Silahkan ketik 3 karakter keyword...</p>");
        } else {
          $.ajax({
            url: `daftar-ajax_cari_sekolah.php?keyword=${keyword}`,
            success: function(a) {
              $('#list_sekolah').html(a);
            }
          })

        }
      });
      $('#keyword').keyup();
      $('#keyword').focus();
    })
  </script>
<?php
} else { // jika ada id_sekolah || id_sekolah=='new'
  if ($is_new_sekolah) {
    $sekolah = [];
    $progress_h3 = 'Pengisian Data Sekolah Baru';
    if ($data_sekolah['id_sekolah']) {
      # ============================================================
      # AUTO-SAVE CLEAR DATA SEKOLAH
      # ============================================================
      $s = "UPDATE tb_data_sekolah SET 
        id_sekolah=NULL,
        jenis_sekolah=NULL, 
        sekolah_negeri=NULL, 
        nama_sekolah='$akun[asal_sekolah]', 
        alamat_sekolah=NULL, 
        kecamatan_sekolah=NULL, 
        jurusan=NULL 
      WHERE username='$username'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    } else {
      echo '<pre>';
      var_dump('id_sekolah telah null');
      echo '</pre>';
    }
  } else {
    $s = "SELECT * FROM tb_sekolah WHERE id=$id_sekolah";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $sekolah = mysqli_fetch_assoc($q);
    $progress_h3 = "Pengisian Data Sekolah
      <div class='f12 mt1'>$sekolah[nama_sekolah] | <a href='./?daftar&step=4'><span class=putih>Pilih Lainnya</span></a></div>
    ";

    # ============================================================
    # AUTO-SAVE DATA SEKOLAH
    # ============================================================
    $s = "UPDATE tb_data_sekolah SET 
      id_sekolah=$id_sekolah,
      jenis_sekolah='$sekolah[jenis_sekolah]', 
      sekolah_negeri='$sekolah[sekolah_negeri]', 
      nama_sekolah='$sekolah[nama_sekolah]', 
      alamat_sekolah='$sekolah[alamat_sekolah]', 
      kecamatan_sekolah='$sekolah[kecamatan]'

    WHERE username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  }
  //re -include
  include "$tb.php";
  $data = $data_sekolah;

  $petunjuk = 'Untuk kelancaran pengisian silahkan sediakan Kartu Keluarga atau KTP Anda!';
  $petunjuk = '';

  include 'daftar-blok_pengisian_data.php';
?>
  <script>
    $(function() {
      $("#data_sekolah-nama_sekolah").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_sekolah-alamat_sekolah").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_sekolah-kecamatan_sekolah").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_sekolah-jurusan").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });
      $("#data_sekolah-no_ijazah").keyup(function() {
        $(this).val($(this).val().toUpperCase());
      });

      $("#data_sekolah-no_ijazah").focus(function() {
        let tid = $(this).prop('id');
        $('#' + tid + '-info').text('jika belum ada, silahkan ganti dg Nomor Surat Keterangan Lulus.');
      });
      $("#data_sekolah-no_ijazah").focusout(function() {
        let tid = $(this).prop('id');
        $('#' + tid + '-info').text('');
      });

    });
  </script>

<?php
}

<?php
$post_id_prodi = $_POST['id_prodi'] ?? '';
$post_semester = $_POST['semester'] ?? '';
$post_shift = $_POST['id_shift'] ?? '';
$post_counter = $_POST['counter'] ?? '';

$get_id_prodi = $_GET['id_prodi'] ?? '';
$get_semester = $_GET['semester'] ?? '';
$get_shift = $_GET['id_shift'] ?? '';
$get_counter = $_GET['counter'] ?? '';

if (isset($_POST['btn_add_kelas'])) {
  if (!$_POST['id_prodi']) {
    alert('Silahkan pilih prodi.');
  } elseif (!$_POST['semester']) {
    alert('Silahkan pilih semester.');
  } else {

    $kapasitas = 40;

    // get data prodi
    $s = "SELECT id,jenjang,singkatan, jumlah_semester FROM tb_prodi WHERE id=$_POST[id_prodi]";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $prodi = mysqli_fetch_assoc($q);

    if ($_POST['semester'] > $prodi['jumlah_semester']) {
      die(alert("Input Semester melebihi jumlah semester pada prodi ini. | <a href='?crud&tb=kelas'>Coba lagi</a>"));
    }

    // $angkatan = intval($ta_aktif / 10) - intval($_POST['semester'] / 2);
    $_counter = $_POST['counter'] ? "-$_POST[counter]" : '';
    $counter_or_null = $_POST['counter'] ? "'$_POST[counter]'" : 'NULL';

    # ============================================================
    # RULE LABEL KELAS
    # ============================================================
    // D3-KA-5-R-A-20241
    // $nama = "$prodi[jenjang]-$prodi[singkatan]-$angkatan-SM$_POST[semester]-$_POST[id_shift]$_counter";
    $nama = "$prodi[jenjang]-$prodi[singkatan]-$_POST[semester]-$_POST[id_shift]$_counter-$ta_aktif";


    // add kelas tahun ganjil
    $s2 = "INSERT INTO tb_kelas (
      nama,
      id_prodi,
      id_ta,
      semester,
      id_shift,
      counter
    ) VALUES (
      '$nama',
      '$_POST[id_prodi]',
      '$ta_aktif',
      '$_POST[semester]',
      '$_POST[id_shift]',
      $counter_or_null
    ) ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP";
    echolog($s2);

    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    jsurl();
  }
}



$img_ontime = img_icon('ontime');
# ============================================================
# ADD KELAS
# ============================================================
$opt_prodi = '';
$s = "SELECT id,jenjang,singkatan FROM tb_prodi ORDER BY jenjang, singkatan";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $selected = ($post_id_prodi == $d['id'] || $get_id_prodi == $d['id']) ? 'selected' : '';
  $opt_prodi .= "<option $selected value=$d[id]>$d[jenjang]-$d[singkatan]</option>";
}

$opt_semester = '';
for ($i = 1; $i <= 8; $i++) {
  if (($is_ganjil and $i % 2 != 0) || (!$is_ganjil and $i % 2 == 0)) {
    $selected = ($post_semester == $i || $get_semester == $i) ? 'selected' : '';
    $opt_semester .= "<option $selected value=$i>Semester $i</option>";
  }
}

$opt_shift = '';
foreach ($rshift as $id_shift => $arr_shift) {
  $selected = ($post_shift == $id_shift || $get_shift == $id_shift) ? 'selected' : '';
  $opt_shift .= "<option $selected value=$id_shift>Kelas $id_shift</option>";
}



?>
<form action="" method="post">
  <div class="f12 abu miring mb1">Tambah Kelas Baru:</div>
  <div class="flexy">
    <div>
      <select name="id_prodi" id="id_prodi" class="form-control harus_memilih">
        <option value="">--pilih prodi--</option>
        <?= $opt_prodi ?>
      </select>
    </div>
    <!-- <div>
      <select name="" id="" class="form-control">
        <option value="">--angkatan--</option>
        <?= $opt_angkatan ?>
      </select>
    </div> -->
    <div>
      <select name="semester" id="semester" class="form-control harus_memilih">
        <?= $opt_semester ?>
      </select>
    </div>
    <div>
      <select name="id_shift" id="id_shift" class="form-control">
        <?= $opt_shift ?>
      </select>
    </div>
    <div>
      <select name="counter" id="counter" class="form-control">
        <option value="">(hanya 1 rombel)</option>
        <option value=A>Kelas A</option>
        <option value=B>Kelas B</option>
        <option value=C>Kelas C</option>
        <option value=D>Kelas D</option>
        <option value=E>Kelas E</option>
      </select>
    </div>
    <div id=div_btn>
      <button class="btn btn-primary" name=btn_add_kelas>Add Kelas</button>
      <span class="btn btn-success ondev" name=btn_add_kelas>Add All Kelas <?= $Gg ?></span>
    </div>

  </div>

  <!-- <div class="mt2">
    <span class="btn_aksi" id="btn_auto__toggle">
      <?= $img_ontime ?>
    </span>
  </div>
  <div id=btn_auto class="hideit mt2">
    <button class="btn btn-danger btn-sm " name=btn_autoadd_r onclick="return confirm(`Add All Kelas Reguler untuk TA sekarang?`)">Auto-Add Reguler</button>
    <button class="btn btn-danger btn-sm " name=btn_autoadd_nr onclick="return confirm(`Add All Kelas Non-Reguler(NR) untuk TA sekarang?`)">Auto-Add NR</button>
  </div> -->
</form>
<script>
  $(function() {
    $('.harus_memilih').change(function() {
      if ($('#id_prodi').val() && $('#semester').val()) {
        $('#div_btn').slideDown();
      } else {
        $('#div_btn').slideUp();

      }
    })
  })
</script>
<?php
$post_id_prodi = $_POST['id_prodi'] ?? '';
$post_semester = $_POST['semester'] ?? '';
$post_shift = $_POST['shift'] ?? '';
$post_counter = $_POST['counter'] ?? '';

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

    $angkatan = intval($ta_aktif / 10) - intval($_POST['semester'] / 2);
    $_counter = $_POST['counter'] ? "-$_POST[counter]" : '';
    $counter_or_null = $_POST['counter'] ? "'$_POST[counter]'" : 'NULL';

    $nama = "$prodi[jenjang]-$prodi[singkatan]-$angkatan-SM$_POST[semester]-$_POST[shift]$_counter";


    // add kelas tahun ganjil
    $s2 = "INSERT INTO tb_kelas (
      nama,
      id_prodi,
      angkatan,
      semester,
      shift,
      counter
    ) VALUES (
      '$nama',
      '$_POST[id_prodi]',
      '$angkatan',
      '$_POST[semester]',
      '$_POST[shift]',
      $counter_or_null
    ) ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP";

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
  $selected = $post_id_prodi == $d['id'] ? 'selected' : '';
  $opt_prodi .= "<option $selected value=$d[id]>$d[jenjang]-$d[singkatan]</option>";
}

$opt_semester = '';
for ($i = 1; $i <= 8; $i++) {
  if (($is_ganjil and $i % 2 != 0) || (!$is_ganjil and $i % 2 == 0)) {
    $selected = $post_semester == $i ? 'selected' : '';
    $opt_semester .= "<option $selected >$i</option>";
  }
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
        <option value="">--semester--</option>
        <?= $opt_semester ?>
      </select>
    </div>
    <div>
      <select name="shift" id="shift" class="form-control">
        <option>R</option>
        <option>NR</option>
      </select>
    </div>
    <div>
      <select name="counter" id="counter" class="form-control">
        <option value="">--</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
      </select>
    </div>
    <div id=div_btn class=hideit>
      <button class="btn btn-success" name=btn_add_kelas>Add Kelas</button>
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
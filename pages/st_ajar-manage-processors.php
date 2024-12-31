<?php
if (isset($_POST['btn_simpan_st'])) {
  $total_sks = $_POST['total_sks'];
  unset($_POST['btn_simpan_st']);
  unset($_POST['total_sks']);

  # ============================================================
  # DELETE FIRST
  # ============================================================
  $s = "DELETE FROM tb_st_mk_kelas WHERE id LIKE '$id_st-%'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));


  # ============================================================
  # RE-INSERTS
  # ============================================================
  $values = '';
  $id_st_mk = '';
  foreach ($_POST as $id => $v) {
    $d = explode('-', $id); // TA-DOSEN-MK-KELAS
    $TA = $d[0];
    $DOSEN = $d[1];
    $MK = $d[2];
    $KELAS = $d[3];
    $id_st_mk = "$TA-$DOSEN-$MK";
    $unique_check = "$TA-$MK-$KELAS"; // TA-MK-KELAS
    $id_kelas = $KELAS;
    $id_dosen = $DOSEN;
    $s = "INSERT INTO tb_st_mk_kelas (
      id,
      id_st_mk,
      id_kelas,
      unique_check,
      id_dosen
    ) VALUES (
      '$id',
      '$id_st_mk',
      '$id_kelas',
      '$unique_check',
      '$id_dosen'
    ) ON DUPLICATE KEY UPDATE id_kelas=$id_kelas";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    // echolog($s);
    // exit;
  }


  $s = "SELECT id_st FROM tb_st_mk WHERE id='$id_st_mk'";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $id_st = $d['id_st'];

  $s = "UPDATE tb_st SET pernah_save_kelas=1 WHERE id='$id_st'";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

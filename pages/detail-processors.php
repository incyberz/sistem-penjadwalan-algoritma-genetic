<?php
if ($_POST) {

  if (isset($_POST['btn_reset_status_kelas'])) {
    $s = "UPDATE tb_kelas SET status=null WHERE id=$get_id";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert("Reset Status Kelas sukses.", 'success');
  } elseif (isset($_POST['username'])) {
    $username = strtolower(str_replace(' ', '', $_POST['username'])); // ZZZ SQL Inject
    $whatsapp = str_replace('-', '', $_POST['whatsapp']);

    # ============================================================
    # GET ID USER
    # ============================================================
    $s = "SELECT id_user FROM tb_mhs WHERE id=$get_id AND id_user is not null";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) {
      # ============================================================
      # AUTO CREATE 
      # ============================================================
      $s = "SELECT MAX(id) as max_id FROM tb_user";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      $d = mysqli_fetch_assoc($q);
      $max_id = $d['max_id'];
      $id_user = $max_id + 1;

      $s = "INSERT INTO tb_user (
        id,
        username,
        whatsapp,
        role
      ) VALUES (
        $id_user,
        '$username',
        '$whatsapp',
        'MHS'
      )";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      alert("Insert username baru sukses. id: $id_user", 'success');
    } else {
      $d = mysqli_fetch_assoc($q);
      $id_user = $d['id_user'];
      // alert("Get id_user: [ $id_user ] ", 'success');
    }

    $s = "UPDATE tb_mhs SET id_user=$id_user WHERE id=$get_id";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $s = "UPDATE tb_user SET whatsapp='$whatsapp',username='$username' WHERE id=$id_user";
    echolog($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    alert("Update username dan whatsapp sukses. id_user: [ $id_user ]", 'success');
  } elseif (isset($_POST['btn_upload_image_mhs'])) {
    $s = "SELECT * FROM tb_mhs WHERE id=$get_id";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $mhs = mysqli_fetch_assoc($q);
    if ($mhs['id_user']) {
      $tmp_name = $_FILES['image']['tmp_name'];
      $type = $_FILES['image']['type'];
      $t = explode('/', $type);
      $ext = $t[1];
      $ext = $ext == 'jpeg' ? 'jpeg' : die("<b class=red>exstensi upload harus JPG</b>  <button class='btn btn-primary btn-sm' onclick='location.replace(`?detail&tb=mhs&id=$get_id`)'>OK</button>");
      $nama_mhs = preg_replace('/[^a-z]/i', '', strtolower($mhs['nama']));
      $time = date('ymdHis');
      $nama_file = "$mhs[id]-$nama_mhs-$time.$ext";
      $target = "assets/img/mhs/$nama_file";
      move_uploaded_file($tmp_name, $target);

      # ============================================================
      # RESIZE IMG
      # ============================================================
      $dotdot = $is_live ? '.' : '..';
      include "$dotdot/includes/resize_img.php";
      resize_img($target);

      # ============================================================
      # UPDATE DB
      # ============================================================
      $s = "UPDATE tb_user SET image='$nama_file' WHERE id=$mhs[id_user]";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  } elseif (isset($_POST['btn_ganti_image'])) {
    $t = explode('-', $_POST['btn_ganti_image']);
    $id_mhs = $t[0] ? $t[0] : udef('id_mhs');
    $id_user = $t[1] ? $t[1] : udef('id_user');
    $image_file_name = $_POST['image_file_name'] ?? udef('image_file_name');
    unlink("assets/img/mhs/$image_file_name");
    $s = "UPDATE tb_user SET image=null WHERE id=$id_user";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  } else {
    echo '<pre>';
    var_dump($_POST);
    echo '<b style=color:red>DEBUGING: belum ada handler untuk data POST ini</b></pre>';
    exit;
  }
  jsurl();
}

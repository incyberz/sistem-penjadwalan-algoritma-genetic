<?php
if (!$username) jsurl('./?login_pmb');
$s = "SELECT * FROM tb_biodata WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $biodata = mysqli_fetch_assoc($q);
} else {
  $s = "INSERT INTO tb_biodata (username) VALUES ('$username')";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

<?php
# ============================================================
# FAVICON
# ============================================================
$favicon = 'assets/img/favicon.png';
if (file_exists($favicon)) {
  echo "
    <link href='$favicon' rel='icon'>
    <link href='$favicon' rel='apple-touch-icon'>
  ";
} else {
  die(echolog("Favicon tidak ada."));
}

# ============================================================
# CDN | LOCAL :: CSS | JS
# ============================================================
if ($is_live) {
  # ============================================================
  # CDN CSS | JS 
  # ============================================================
?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<?php } else {
  # ============================================================
  # LOCAL CSS | JS
  # ============================================================
?>
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/vendor/datatables/datatables.min.css">
  <script src="../assets/vendor/jquery/jquery-3.7.1.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="../assets/vendor/datatables/datatables.min.js"></script>
<?php } ?>
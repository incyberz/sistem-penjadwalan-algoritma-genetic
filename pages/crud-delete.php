<?php
include '../conn.php';
$tb = $_GET['tb'] ?? die(erid('tb'));
$id = $_GET['id'] ?? die(erid('id'));

$kolom_id = $tb == 'ta' ? 'ta' : 'id';

$sql = "DELETE FROM tb_$tb WHERE $kolom_id=$id";
if ($conn->query($sql)) die('sukses');
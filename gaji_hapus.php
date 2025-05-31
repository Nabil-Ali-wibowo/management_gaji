<?php
include 'koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM gaji WHERE id = $id");

header("Location: gaji.php");
?>

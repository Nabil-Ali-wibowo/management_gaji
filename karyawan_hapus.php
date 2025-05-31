<?php
include 'koneksi.php';

$id = $_GET['id'];

// Hapus data rating yang terkait dengan karyawan ini
mysqli_query($conn, "DELETE FROM rating WHERE karyawan_id = $id");

// Baru hapus data karyawannya
mysqli_query($conn, "DELETE FROM karyawan WHERE id = $id");

header("Location: karyawan.php");
?>

<?php
include 'koneksi.php';

$id = $_GET['id'];

$hapus = mysqli_query($conn, "DELETE FROM rating WHERE id = $id");

if ($hapus) {
    header("Location: rating.php");
} else {
    echo "Gagal menghapus data.";
}
?>

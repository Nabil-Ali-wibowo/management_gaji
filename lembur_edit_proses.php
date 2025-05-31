<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama_jabatan = mysqli_real_escape_string($conn, $_POST['nama_jabatan']);
    $gaji_pokok = floatval($_POST['gaji_pokok']);

    // Validasi sederhana
    if (empty($nama_jabatan) || $gaji_pokok < 0) {
        // Bisa redirect atau tampilkan error
        echo "Data tidak valid";
        exit;
    }

    $sql = "UPDATE jabatan SET nama_jabatan = '$nama_jabatan', gaji_pokok = $gaji_pokok WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Redirect kembali ke daftar jabatan setelah sukses update
        header('Location: daftar_jabatan.php?status=success');
        exit;
    } else {
        echo "Error saat mengupdate data: " . mysqli_error($conn);
    }
} else {
    // Jika akses bukan POST, redirect ke halaman daftar
    header('Location: daftar_jabatan.php');
    exit;
}
?>

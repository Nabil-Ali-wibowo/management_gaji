<?php
session_start();
include 'koneksi.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'error';
    exit();
}

$id = intval($_GET['id']);

try {
    // Cek apakah jabatan sedang digunakan oleh karyawan
    $stmtCheck = $conn->prepare("SELECT COUNT(*) as total FROM karyawan WHERE jabatan_id = ?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $data = $result->fetch_assoc();

    if ($data['total'] > 0) {
        echo 'used'; // Karyawan masih menggunakan jabatan ini
        exit();
    }

    // Hapus jabatan
    $stmtDelete = $conn->prepare("DELETE FROM jabatan WHERE id = ?");
    $stmtDelete->bind_param("i", $id);

    if ($stmtDelete->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmtDelete->close();
    $stmtCheck->close();
    $conn->close();
} catch (Exception $e) {
    echo 'error';
}

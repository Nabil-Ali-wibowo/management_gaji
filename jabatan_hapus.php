<?php
session_start();
include 'koneksi.php';

// Validasi CSRF Token
if (!isset($_GET['token']) || !hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
    $_SESSION['error'] = "Token keamanan tidak valid";
    header("Location: jabatan.php");
    exit();
}

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID jabatan tidak valid";
    header("Location: jabatan.php");
    exit();
}

$id = intval($_GET['id']);

try {
    // 1. Cek apakah jabatan digunakan
    $check = $conn->prepare("SELECT COUNT(*) as total FROM karyawan WHERE jabatan_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result();
    $row = $result->fetch_assoc();

    if ($row['total'] > 0) {
        throw new Exception("Jabatan tidak dapat dihapus karena masih digunakan oleh karyawan");
    }

    // 2. Hapus jabatan
    $stmt = $conn->prepare("DELETE FROM jabatan WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if (!$stmt->execute()) {
        throw new Exception("Gagal menghapus jabatan dari database");
    }

    $_SESSION['success'] = "Jabatan berhasil dihapus";
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
} finally {
    $stmt->close();
    $check->close();
    $conn->close();
    header("Location: jabatan.php");
    exit();
}
?>
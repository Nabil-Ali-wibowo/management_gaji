<?php
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger'>ID tidak valid</div>");
}

$id = (int)$_GET['id'];

$query = "SELECT lembur.*, jabatan.nama_jabatan 
          FROM lembur 
          JOIN jabatan ON lembur.jabatan_id = jabatan.id 
          WHERE lembur.id = ?";
          
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$d = $result->fetch_assoc();

if (!$d) {
    die("<div class='alert alert-warning'>Data dengan ID $id tidak ditemukan</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lembur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container shadow p-5 mb-5 bg-body-tertiary rounded">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-cash-stack fs-1 me-3"></i>
            <h2>Detail Gaji</h2>
        </div>
        
        <p><strong>Jabatan:</strong> <?= htmlspecialchars($d['gaji.php'] ?? 'N/A') ?></p>
        <p><strong>Tarif lembur:</strong> Rp <?= isset($d['tarif_lembur']) ? number_format($d['tarif_lembur'], 0, ',', '.') : '0' ?></p>
        
        <a href="gaji.php" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</body>
</html>
<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT lembur.*, jabatan.nama_jabatan FROM lembur 
    JOIN jabatan ON lembur.jabatan_id = jabatan.id 
    WHERE lembur.id='$id'");
$d = mysqli_fetch_array($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lembur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container shadow p-5 mb-5 bg-body-tertiary rounded">
        <h2>Detail Tarif Lembur</h2>
            <p><strong>Jabatan:</strong> <?= $d['nama_jabatan'] ?></p>
            <p><strong>Tarif lembur:</strong> Rp <?= number_format($d['tarif_lembur']) ?></p>
         <a href="lembur.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container shadow p-5 mb-5 bg-body-tertiary rounded">
        <i class="bi bi-cash-stack"><h2>Detail Gaji</h2></i>
            <p><strong>Jabatan:</strong> <?= $d['nama_jabatan'] ?></p>
            <p><strong>Tarif per Jam:</strong> Rp <?= number_format($d['tarif_per_jam']) ?></p>
        <a href="gaji.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>

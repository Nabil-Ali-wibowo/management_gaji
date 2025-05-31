<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT rating.*, karyawan.nama FROM rating 
    JOIN karyawan ON rating.karyawan_id = karyawan.id 
    WHERE rating.id='$id'");
$d = mysqli_fetch_array($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rating</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container shadow p-5 mb-5 bg-body-tertiary rounded">
        <h2>Detail Rating Karyawan</h2>
            <p><strong>Nama Karyawan:</strong> <?= $d['nama'] ?></p>
            <p><strong>Bulan:</strong> <?= $d['bulan'] ?></p>
            <p><strong>Nilai Rating:</strong> <?= $d['nilai_rating'] ?></p>
         <a href="rating.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>
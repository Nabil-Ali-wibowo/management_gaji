<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>Document</title>
    <style>
        .icon {
            margin-right: 200px;
        }
    </style>
</head>
<body>
    
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-success"><i class="bi bi-cash-stack me-2"></i>Daftar Tarif Lembur</h3>
            <a href="gaji_tambah.php" class="btn btn-primary btn-icon">
                <i class="bi bi-plus-circle-fill"></i> Tambah Gaji
            </a>
        </div>
        <div class="table-responsive shadow rounded bg-white p-3">
            <table class="table table-striped table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Bulan</th>
                        <th>Total Gaji</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT gaji.id, karyawan.nama, gaji.bulan, gaji.total_gaji 
                                                        FROM gaji 
                                                        JOIN karyawan ON gaji.karyawan_id = karyawan.id 
                                                        ORDER BY gaji.bulan DESC");
                        while($data = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['nama'] ?></td>
                            <td><?= $data['bulan'] ?></td>
                            <td>Rp <?= number_format($data['total_gaji'], 0, ',', '.') ?></td>
                            <td>
                                <a href="gaji_edit.php?id=<?= $data['id'] ?>" class="btn btn-warning btn-sm">Edit</a> 
                                <a href="gaji_detail.php?id=<?= $data['id'] ?>" class="btn btn-info btn-sm">Detail</a>
                                <a href="gaji_hapus.php?id=<?= $data['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

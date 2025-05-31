<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tarif Lembur - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table thead th {
            vertical-align: middle;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-success"><i class="bi bi-clock-history me-2"></i>Daftar Tarif Lembur</h3>
            <a href="lembur_tambah.php" class="btn btn-primary btn-icon">
                <i class="bi bi-plus-circle-fill"></i> Tambah Tarif
            </a>
        </div>
        <div class="table-responsive shadow rounded bg-white p-3">
            <table class="table table-striped table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Jabatan</th>
                        <th>Tarif lembur</th>
                        <th>Jumlah Jam</th>
                        <th>Total Lembur</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT lembur.*, jabatan.nama_jabatan FROM lembur 
                                                  JOIN jabatan ON lembur.jabatan_id = jabatan.id 
                                                  ORDER BY lembur.id DESC");
                    while ($row = mysqli_fetch_assoc($query)) {
                        $total = $row['tarif_lembur'] * $row['jumlah_jam'];
                        echo '
                        <tr>
                            <td>' . $no++ . '</td>
                            <td>' . $row['nama_jabatan'] . '</td>
                            <td>Rp ' . number_format($row['tarif_lembur'], 0, ',', '.') . '</td>
                            <td>' . $row['jumlah_jam'] . ' jam</td>
                            <td>Rp ' . number_format($total, 0, ',', '.') . '</td>
                            <td class="text-center">
                                <a href="lembur_edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning btn-icon me-1">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <a href="lembur_detail.php?id=' . $row['id'] . '" class="btn btn-sm btn-info btn-icon text-white me-1">
                                    <i class="bi bi-eye-fill"></i> Detail
                                </a>
                                <a href="lembur_hapus.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger btn-icon" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </a>
                            </td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Opsional jika butuh komponen dinamis) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
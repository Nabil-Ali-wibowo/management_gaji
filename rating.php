<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Rating - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <h3>Daftar Rating</h3>
        <a href="rating_tambah.php" class="btn btn-primary mb-3">+ Tambah Rating</a>
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="table-dark">
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Bulan</th>
                    <th>Nilai Rating</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT rating.*, karyawan.nama FROM rating 
                                              JOIN karyawan ON rating.karyawan_id = karyawan.id 
                                              ORDER BY rating.id DESC");
                while ($row = mysqli_fetch_assoc($query)) {
                    echo '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['bulan'] . '</td>
                        <td>' . $row['nilai_rating'] . '</td>
                        <td>
                            <a href="rating_edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                            <a href="rating_detail.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary">detail</a>
                            <a href="rating_hapus.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</a>
                        </td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Karyawan - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .kartu-karyawan {
            width: 200px;
            margin: 10px;
        }
        .foto-karyawan {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <h3>Daftar Karyawan</h3>
        <a href="karyawan_tambah.php" class="btn btn-primary mb-3">+ Tambah Karyawan</a>
        <div class="d-flex flex-wrap">
            <?php
            $query = mysqli_query($conn, "SELECT karyawan.*, jabatan.nama_jabatan 
                                          FROM karyawan 
                                          JOIN jabatan ON karyawan.jabatan_id = jabatan.id 
                                          ORDER BY karyawan.id DESC");
            $bulan_ini = date('Y-m');
            while ($row = mysqli_fetch_assoc($query)) {
                // Ambil rating dari tabel rating untuk karyawan ini di bulan ini
                $id_karyawan = $row['id'];
                $rating_q = mysqli_query($conn, "SELECT nilai_rating FROM rating WHERE karyawan_id = $id_karyawan AND bulan = '$bulan_ini'");
                $data_rating = mysqli_fetch_assoc($rating_q);
                $nilai_rating = $data_rating['nilai_rating'] ?? '-';
                $bintang = is_numeric($nilai_rating) ? str_repeat('⭐', $nilai_rating) : '-';

                echo '
                <div class="card kartu-karyawan shadow-sm">
                    <img src="uploads/' . $row['foto'] . '" class="foto-karyawan card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-1">' . $row['nama'] . '</h5>
                        <div class="text-warning mb-1">Rating: ' . $bintang . '</div>
                        <p class="card-text"><strong>' . $row['nama_jabatan'] . '</strong></p>
                        <a href="karyawan_edit.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                        <a href="karyawan_detail.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary">Detail</a>
                        <a href="karyawan_hapus.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</a>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

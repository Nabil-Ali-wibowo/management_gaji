<?php 
include 'koneksi.php';
include 'includes/header.php'; 

// Ambil ID karyawan dari parameter URL
$id_karyawan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query data karyawan
$query_karyawan = mysqli_query($conn, "SELECT k.*, j.nama_jabatan, j.gaji_pokok 
                                      FROM karyawan k 
                                      JOIN jabatan j ON k.jabatan_id = j.id 
                                      WHERE k.id = $id_karyawan");
$karyawan = mysqli_fetch_assoc($query_karyawan);

if (!$karyawan) {
    echo "<script>alert('Data karyawan tidak ditemukan'); window.location='dashboard.php';</script>";
    exit();
}

// Query data rating
$query_rating = mysqli_query($conn, "SELECT bulan, nilai_rating, komentar 
                                    FROM rating 
                                    WHERE karyawan_id = $id_karyawan 
                                    ORDER BY bulan DESC");
$ratings = [];
while ($row = mysqli_fetch_assoc($query_rating)) {
    $ratings[] = $row;
}

// Hitung rata-rata rating
$rata_rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(nilai_rating) AS rata 
                                                     FROM rating 
                                                     WHERE karyawan_id = $id_karyawan"))['rata'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Karyawan - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 4px solid white;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .card-detail {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        
        .card-detail:hover {
            transform: translateY(-5px);
        }
        
        .card-title {
            font-weight: 600;
            color: #495057;
        }
        
        .badge-role {
            font-size: 14px;
            padding: 6px 12px;
        }
        
        .rating-star {
            color: #ffc107;
            font-size: 24px;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .info-value {
            font-size: 16px;
            color: #343a40;
        }
        
        .nav-tabs .nav-link {
            font-weight: 500;
            border: none;
            color: #6c757d;
        }
        
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background: transparent;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <div class="profile-header text-center">
            <img src="uploads/<?= $karyawan['foto'] ?>" class="profile-img mb-3" alt="Foto Profil">
            <h2><?= $karyawan['nama'] ?></h2>
            <span class="badge bg-light text-primary badge-role mb-2"><?= $karyawan['nama_jabatan'] ?></span>
            <div class="mt-2">
                <?php 
                $rating_stars = is_numeric($rata_rating) ? str_repeat('⭐', round($rata_rating)) : '-';
                echo '<span class="rating-star">' . $rating_stars . '</span>';
                ?>
                <span class="ms-2">(<?= number_format($rata_rating ?? 0, 1) ?>/5)</span>
            </div>
        </div>

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-4">
                <div class="card card-detail">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Pribadi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="info-label">NIK</div>
                            <div class="info-value"><?= $karyawan['nik'] ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Tanggal Lahir</div>
                            <div class="info-value"><?= date('d F Y', strtotime($karyawan['tanggal_lahir'])) ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Jenis Kelamin</div>
                            <div class="info-value"><?= $karyawan['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Alamat</div>
                            <div class="info-value"><?= $karyawan['alamat'] ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= $karyawan['email'] ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Telepon</div>
                            <div class="info-value"><?= $karyawan['telepon'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="card card-detail">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="bi bi-briefcase me-2"></i>Informasi Jabatan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="info-label">Jabatan</div>
                            <div class="info-value"><?= $karyawan['nama_jabatan'] ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Gaji Pokok</div>
                            <div class="info-value">Rp <?= number_format($karyawan['gaji_pokok'], 0, ',', '.') ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Tanggal Mulai Bekerja</div>
                            <div class="info-value"><?= date('d F Y', strtotime($karyawan['tanggal_mulai_kerja'])) ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="badge bg-<?= $karyawan['status'] == 'Aktif' ? 'success' : 'secondary' ?>">
                                    <?= $karyawan['status'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-8">
                <div class="card card-detail">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#rating">Rating Kinerja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#riwayat">Riwayat Gaji</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Tab Rating -->
                            <div class="tab-pane fade show active" id="rating">
                                <h5 class="mb-4">Riwayat Penilaian Kinerja</h5>
                                
                                <?php if (empty($ratings)): ?>
                                    <div class="alert alert-info">
                                        Belum ada data penilaian kinerja untuk karyawan ini.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Bulan</th>
                                                    <th>Rating</th>
                                                    <th>Komentar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ratings as $rating): ?>
                                                <tr>
                                                    <td><?= date('F Y', strtotime($rating['bulan'])) ?></td>
                                                    <td>
                                                        <?= str_repeat('⭐', $rating['nilai_rating']) ?>
                                                        (<?= $rating['nilai_rating'] ?>)
                                                    </td>
                                                    <td><?= $rating['komentar'] ?: '-' ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Tab Riwayat Gaji -->
                            <div class="tab-pane fade" id="riwayat">
                                <h5 class="mb-4">Riwayat Pembayaran Gaji</h5>
                                <div class="alert alert-warning">
                                    <i class="bi bi-info-circle"></i> Fitur riwayat gaji akan segera tersedia.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <a href="dashboard.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <div>
                        <a href="edit_karyawan.php?id=<?= $id_karyawan ?>" class="btn btn-primary me-2">
                            <i class="bi bi-pencil"></i> Edit Data
                        </a>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data karyawan <?= $karyawan['nama'] ?>? 
                Data yang sudah dihapus tidak dapat dikembalikan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="hapus_karyawan.php?id=<?= $id_karyawan ?>" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
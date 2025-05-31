<?php
include 'koneksi.php';

// Validasi ID
if(!isset($_GET['id'])) {
    header("Location: karyawan.php?error=ID karyawan tidak valid");
    exit();
}

$id = intval($_GET['id']);
$query = $conn->prepare("SELECT 
                        karyawan.id, 
                        karyawan.nama, 
                        karyawan.umur, 
                        karyawan.jenis_kelamin, 
                        karyawan.alamat, 
                        karyawan.no_hp, 
                        karyawan.foto,
                        jabatan.nama_jabatan 
                        FROM karyawan 
                        JOIN jabatan ON karyawan.jabatan_id = jabatan.id 
                        WHERE karyawan.id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$karyawan = $result->fetch_assoc();

if(!$karyawan) {
    header("Location: karyawan.php?error=Data karyawan tidak ditemukan");
    exit();
}

// Ambil data rating
$rating_query = $conn->prepare("SELECT * FROM rating WHERE karyawan_id = ? ORDER BY bulan DESC");
$rating_query->bind_param("i", $id);
$rating_query->execute();
$ratings = $rating_query->get_result()->fetch_all(MYSQLI_ASSOC);

// Hitung rata-rata rating
$avg_rating_query = $conn->prepare("SELECT AVG(nilai_rating) as rata_rata FROM rating WHERE karyawan_id = ?");
$avg_rating_query->bind_param("i", $id);
$avg_rating_query->execute();
$avg_rating = $avg_rating_query->get_result()->fetch_assoc()['rata_rata'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin-top: 30px;
        }
        .profile-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #dee2e6;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
            display: inline-block;
        }
        .rating-star {
            color: #ffc107;
        }
        .rating-card {
            margin-bottom: 10px;
            border-left: 4px solid #0d6efd;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container profile-container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="bi bi-person-badge"></i> Detail Karyawan</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="uploads/<?= htmlspecialchars($karyawan['foto'] ?? 'default.jpg') ?>" 
                         class="profile-img mb-3" 
                         alt="Foto Profil">
                </div>
                <div class="col-md-8">
                    <div class="mb-4">
                        <h3><?= htmlspecialchars($karyawan['nama']) ?></h3>
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($karyawan['nama_jabatan']) ?></span>
                        
                        <!-- Rata-rata Rating -->
                        <div class="mt-2">
                            <span class="detail-label">Rating Rata-rata:</span>
                            <?php if($avg_rating): ?>
                                <span class="rating-star">
                                    <?= str_repeat('★', round($avg_rating)) ?>
                                    (<?= number_format($avg_rating, 1) ?>/5)
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Belum ada rating</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Umur:</span>
                        <?= $karyawan['umur'] ? htmlspecialchars($karyawan['umur']) . ' tahun' : '-' ?>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Jenis Kelamin:</span>
                        <?php
                        if($karyawan['jenis_kelamin'] == 'L') echo 'Laki-laki';
                        elseif($karyawan['jenis_kelamin'] == 'P') echo 'Perempuan';
                        else echo '-';
                        ?>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Alamat:</span>
                        <?= $karyawan['alamat'] ? htmlspecialchars($karyawan['alamat']) : '-' ?>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">No. HP:</span>
                        <?= $karyawan['no_hp'] ? htmlspecialchars($karyawan['no_hp']) : '-' ?>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="mt-4">
                        <a href="karyawan.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <a href="karyawan_edit.php?id=<?= $id ?>" class="btn btn-warning ms-2">
                            <i class="bi bi-pencil"></i> Edit Data
                        </a>
                        <a href="karyawan_rating.php?id=<?= $id ?>" class="btn btn-success ms-2">
                            <i class="bi bi-star"></i> Beri Rating
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Daftar Rating -->
            <div class="mt-5">
                <h5><i class="bi bi-star-fill"></i> Riwayat Rating</h5>
                <?php if(count($ratings) > 0): ?>
                    <div class="list-group">
                        <?php foreach($ratings as $rating): ?>
                            <div class="list-group-item rating-card">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?= date('F Y', strtotime($rating['bulan'])) ?></strong>
                                        <div class="rating-star">
                                            <?= str_repeat('★', $rating['nilai_rating']) ?>
                                            (<?= $rating['nilai_rating'] ?>/5)
                                        </div>
                                        <?php if(!empty($rating['komentar'])): ?>
                                            <p class="mt-2 mb-0"><?= htmlspecialchars($rating['komentar']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <a href="karyawan_rating.php?id=<?= $id ?>&edit=<?= $rating['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Belum ada riwayat rating untuk karyawan ini.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
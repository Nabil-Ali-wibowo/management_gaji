<?php
include 'koneksi.php';

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $jabatan_id = $_POST['jabatan_id'];
    $rating = $_POST['rating'] ?? null; // Nilai rating dari form

    // Handle file upload
    $foto = $_POST['foto_lama'];
    if ($_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        $foto = $_FILES["foto"]["name"];
    }

    // Mulai transaction
    $conn->begin_transaction();

    try {
        // Update data karyawan
        $stmt = $conn->prepare("UPDATE karyawan SET 
                              nama = ?, 
                              umur = ?, 
                              jenis_kelamin = ?, 
                              alamat = ?, 
                              no_hp = ?, 
                              foto = ?, 
                              jabatan_id = ? 
                              WHERE id = ?");
        $stmt->bind_param("sissssii", $nama, $umur, $jenis_kelamin, $alamat, $no_hp, $foto, $jabatan_id, $id);
        $stmt->execute();
        
        // Jika ada rating, simpan/update rating
        if ($rating !== null) {
            $bulan_ini = date('Y-m-01');
            $cek_rating = $conn->prepare("SELECT id FROM rating WHERE karyawan_id = ? AND bulan = ?");
            $cek_rating->bind_param("is", $id, $bulan_ini);
            $cek_rating->execute();
            $hasil_cek = $cek_rating->get_result();
            
            if ($hasil_cek->num_rows > 0) {
                // Update rating yang sudah ada
                $row = $hasil_cek->fetch_assoc();
                $update_rating = $conn->prepare("UPDATE rating SET nilai_rating = ? WHERE id = ?");
                $update_rating->bind_param("ii", $rating, $row['id']);
                $update_rating->execute();
            } else {
                // Insert rating baru
                $insert_rating = $conn->prepare("INSERT INTO rating (karyawan_id, bulan, nilai_rating) VALUES (?, ?, ?)");
                $insert_rating->bind_param("isi", $id, $bulan_ini, $rating);
                $insert_rating->execute();
            }
        }
        
        $conn->commit();
        header("Location: karyawan.php?success=Data karyawan dan rating berhasil diupdate");
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: karyawan.php?error=Gagal update data: " . $e->getMessage());
    }
    exit;
}

// Ambil data karyawan untuk diedit
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT karyawan.*, jabatan.nama_jabatan 
                            FROM karyawan 
                            JOIN jabatan ON karyawan.jabatan_id = jabatan.id 
                            WHERE karyawan.id='$id'");
$karyawan = mysqli_fetch_assoc($query);

// Ambil rating bulan ini jika ada
$bulan_ini = date('Y-m-01');
$rating_query = mysqli_query($conn, "SELECT nilai_rating FROM rating WHERE karyawan_id='$id' AND bulan='$bulan_ini'");
$rating = mysqli_fetch_assoc($rating_query);
$nilai_rating = $rating['nilai_rating'] ?? 0;

// Ambil daftar jabatan untuk dropdown
$jabatan_query = mysqli_query($conn, "SELECT * FROM jabatan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .rating-stars {
            font-size: 24px;
            color: #ffc107;
            cursor: pointer;
        }
        .rating-container {
            margin: 15px 0;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="bi bi-person-lines-fill"></i> Edit Data Karyawan</h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $karyawan['id']; ?>">
                <input type="hidden" name="foto_lama" value="<?php echo $karyawan['foto']; ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" 
                                   value="<?php echo htmlspecialchars($karyawan['nama']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Umur</label>
                            <input type="number" class="form-control" name="umur" 
                                   value="<?php echo htmlspecialchars($karyawan['umur']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin">
                                <option value="L" <?php echo ($karyawan['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="P" <?php echo ($karyawan['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3"><?php echo htmlspecialchars($karyawan['alamat']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" class="form-control" name="no_hp" 
                                   value="<?php echo htmlspecialchars($karyawan['no_hp']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <select class="form-select" name="jabatan_id" required>
                                <?php 
                                mysqli_data_seek($jabatan_query, 0); // Reset pointer
                                while ($jabatan = mysqli_fetch_assoc($jabatan_query)): ?>
                                    <option value="<?php echo $jabatan['id']; ?>" 
                                        <?php echo ($jabatan['id'] == $karyawan['jabatan_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($jabatan['nama_jabatan']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" class="form-control" name="foto">
                            <?php if ($karyawan['foto']): ?>
                                <small class="text-muted">Foto saat ini: <?php echo $karyawan['foto']; ?></small>
                                <img src="uploads/<?php echo $karyawan['foto']; ?>" width="100" class="d-block mt-2">
                            <?php endif; ?>
                        </div>
                        
                        <div class="rating-container">
                            <label class="form-label">Rating Kinerja (Bulan Ini)</label>
                            <input type="hidden" name="rating" id="rating-value" value="<?php echo $nilai_rating; ?>">
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star<?= ($i <= $nilai_rating) ? '-fill' : '' ?>" 
                                       data-rating="<?php echo $i; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">Klik bintang untuk memberikan rating (1-5)</small>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="karyawan.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fungsi untuk rating bintang
document.querySelectorAll('.rating-stars i').forEach(star => {
    star.addEventListener('click', () => {
        const rating = star.getAttribute('data-rating');
        document.getElementById('rating-value').value = rating;
        
        // Update tampilan bintang
        document.querySelectorAll('.rating-stars i').forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('bi-star');
                s.classList.add('bi-star-fill');
            } else {
                s.classList.remove('bi-star-fill');
                s.classList.add('bi-star');
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
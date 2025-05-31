<?php
include 'koneksi.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: lembur_list.php?error=ID tidak valid");
    exit();
}

$id = intval($_GET['id']);

try {
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT * FROM tarif_lembur WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: lembur_list.php?error=Data tarif lembur tidak ditemukan");
        exit();
    }

    $data = $result->fetch_assoc();
} catch (Exception $e) {
    // Jika tabel tidak ada, tampilkan pesan error yang lebih jelas
    if (strpos($e->getMessage(), "doesn't exist") !== false) {
        die("Error: Tabel tarif_lembur belum dibuat. Silakan buat tabel terlebih dahulu.");
    }
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tarif Lembur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Tarif Lembur</h2>
        </div>
        <div class="card-body">
            <form action="lembur_update.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
                
                <div class="form-group">
                    <label for="nama_tarif" class="form-label">Nama Tarif</label>
                    <input type="text" class="form-control" id="nama_tarif" name="nama_tarif" 
                           value="<?= htmlspecialchars($data['nama_tarif']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="tarif_per_jam" class="form-label">Tarif Per Jam</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="tarif_per_jam" name="tarif_per_jam" 
                               value="<?= htmlspecialchars($data['tarif_per_jam']) ?>" min="0" step="1000" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="lembur_list.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
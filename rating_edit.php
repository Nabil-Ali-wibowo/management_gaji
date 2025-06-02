<?php
include 'koneksi.php';

// Ambil data rating berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM rating WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        echo "Data rating tidak ditemukan.";
        exit;
    }
}

// Ambil semua karyawan untuk dropdown
$karyawanList = mysqli_query($conn, "SELECT id, nama FROM karyawan");

// Proses update data jika form disubmit
if (isset($_POST['submit'])) {
    $karyawan_id = $_POST['karyawan_id'];
    $bulan = $_POST['bulan'];
    $nilai_rating = $_POST['nilai_rating'];

    $update = mysqli_query($conn, "UPDATE rating SET 
        karyawan_id = '$karyawan_id',
        bulan = '$bulan', 
        nilai_rating = '$nilai_rating' 
        WHERE id = '$id'");

    if ($update) {
        echo "<script>alert('Data rating berhasil diperbarui!'); window.location='rating.php';</script>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Rating</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Data Rating</h2>
    <form method="POST">
        <div class="form-group">
            <label>Nama Karyawan</label>
            <select name="karyawan_id" class="form-control" required>
                <option value="">-- Pilih Karyawan --</option>
                <?php while ($karyawan = mysqli_fetch_assoc($karyawanList)) { ?>
                    <option value="<?= $karyawan['id']; ?>" <?= ($karyawan['id'] == $data['karyawan_id']) ? 'selected' : ''; ?>>
                        <?= $karyawan['nama']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Bulan</label>
            <input type="text" name="bulan" class="form-control" value="<?= $data['bulan'] ?>" placeholder="Contoh: 2025-06" required>
        </div>
        <div class="form-group">
            <label>Nilai Rating</label>
            <input type="number" name="nilai_rating" class="form-control" value="<?= $data['nilai_rating'] ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="rating.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>

<?php
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: lembur.php");
    exit;
}

$id = (int)$_GET['id'];
$sql = "SELECT * FROM lembur WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Data tidak ditemukan!";
    exit;
}

$data = mysqli_fetch_assoc($result);
$jabatan = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY nama_jabatan ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tarif Lembur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Edit Tarif Lembur</h3>
    <form action="lembur_update.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <div class="form-group">
            <label for="jabatan_id">Jabatan</label>
            <select name="jabatan_id" class="form-control" required>
                <?php while ($row = mysqli_fetch_assoc($jabatan)) : ?>
                    <option value="<?= $row['id'] ?>" <?= $data['jabatan_id'] == $row['id'] ? 'selected' : '' ?>>
                        <?= $row['nama_jabatan'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tarif_lembur">Tarif Lembur</label>
            <input type="number" name="tarif_lembur" class="form-control" value="<?= $data['tarif_lembur'] ?>" required>
        </div>

        <div class="form-group">
            <label for="jumlah_jam">Jumlah Jam</label>
            <input type="number" name="jumlah_jam" class="form-control" value="<?= $data['jumlah_jam'] ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="lembur.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
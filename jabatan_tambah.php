<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jabatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <h2>Tambah Jabatan</h2>
        <form method="post">
            <div class="mb-3">
                <label>Nama Jabatan</label>
                <input type="text" name="nama_jabatan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Gaji Pokok</label>
                <input type="number" name="gaji_pokok" class="form-control" required>
            </div>
            <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            <a href="jabatan.php" class="btn btn-secondary">Kembali</a>
        </form>

        <?php
        if (isset($_POST['simpan'])) {
            $nama = $_POST['nama_jabatan'];
            $gaji = $_POST['gaji_pokok'];
            mysqli_query($conn, "INSERT INTO jabatan (nama_jabatan, gaji_pokok) VALUES ('$nama', '$gaji')");
            echo "<script>window.location='jabatan.php';</script>";
        }
        ?>
    </div>
</div>
</body>
</html>

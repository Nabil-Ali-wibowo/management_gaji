<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jenis_Kelamin = $_POST['jenis_Kelamin'];
    $jabatan_id = $_POST['jabatan_id'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $nilai_rating = $_POST['nilai_rating'];
    
    // Proses upload foto
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $upload_dir = 'uploads/';

    if ($foto != "") {
        move_uploaded_file($tmp, $upload_dir . $foto);
    }

    // Simpan data karyawan
    $query = mysqli_query($conn, "INSERT INTO karyawan (nama, umur, jenis_Kelamin, jabatan_id, alamat, no_hp, foto) 
                                  VALUES ('$nama', '$umur', '$jenis_Kelamin', '$jabatan_id', '$alamat', '$no_hp', '$foto')");
    if ($query) {
        $karyawan_id = mysqli_insert_id($conn);
        $bulan = date('Y-m');

        // Simpan data rating
        mysqli_query($conn, "INSERT INTO rating (karyawan_id, bulan, nilai_rating) 
                             VALUES ('$karyawan_id', '$bulan', '$nilai_rating')");

        header("Location: karyawan.php");
        exit;
    } else {
        echo "Gagal menambahkan data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <h3>Tambah Data Karyawan</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Umur</label>
                <input type="text" name="umur" class="form-control" required>
            </div>
              <div class="mb-3">
                <label class="form-label">Jenis_Kelamin</label>
                <select name="jenis_Kelamin" class="form-control" required>
                    <option value="">-- Pilih Gender --</option>
                    <option value="Laki-laki">Laki-laki</option>
                     <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan_id" class="form-select" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <?php
                    $jabatan = mysqli_query($conn, "SELECT * FROM jabatan");
                    while ($row = mysqli_fetch_assoc($jabatan)) {
                        echo '<option value="' . $row['id'] . '">' . $row['nama_jabatan'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rating Karyawan</label>
                <div class="rating-stars d-flex gap-1 fs-4 text-warning" style="cursor:pointer;">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<i class="bi bi-star" data-rating="' . $i . '" title="Rating ' . $i . '"></i>';
                    }
                    ?>
                </div>
                <input type="hidden" name="nilai_rating" id="rating-value" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="karyawan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

    <script>
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


</body>
</html>

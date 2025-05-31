<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $jabatan_id = $_POST['jabatan_id'];
    $tarif_lembur = $_POST['tarif_lembur'];
    $jumlah_jam = $_POST['jumlah_jam'];

    $query = "INSERT INTO lembur (jabatan_id, tarif_lembur, jumlah_jam) 
              VALUES ('$jabatan_id', '$tarif_lembur', '$jumlah_jam')";
              
    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>Data lembur berhasil disimpan.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan data: " . mysqli_error($conn) . "</div>";
    } 
    if (mysqli_query($conn, $query)) {
    header("Location: lembur.php");
    exit;
}

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tarif Lembur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card p-4">
                    <h4 class="mb-4">Tambah Tarif Lembur</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="jabatan_id" class="form-label">Jabatan</label>
                            <select name="jabatan_id" class="form-select" required>
                                <option value="">-- Pilih Jabatan --</option>
                                <?php
                                $jabatan = mysqli_query($conn, "SELECT * FROM jabatan");
                                while ($j = mysqli_fetch_assoc($jabatan)) {
                                    echo '<option value="' . $j['id'] . '">' . $j['nama_jabatan'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tarif lembur</label>
                            <input type="number" name="tarif_lembur" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Jam</label>
                            <input type="number" name="jumlah_jam" class="form-control" required>
                        </div>
                        <button  type="submit" name="simpan" class="btn btn-primary w-100"><a href="lembur.php"></a>Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Data Gaji</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
    }
    .main-content {
      margin-left: 250px; /* Sesuai lebar sidebar */
      padding: 2rem;
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
                      <h4 class="mb-4">Tambah Gaji </h4>
                      <form action="" method="post">
                        <div class="mb-3">
                          <label for="karyawan" class="form-label">Nama Karyawan</label>
                          <select name="karyawan_id" id="karyawan" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <?php
                            include 'config.php';
                            $res = mysqli_query($conn, "SELECT * FROM karyawan");
                            while ($k = mysqli_fetch_assoc($res)) {
                                echo "<option value='{$k['id']}'>{$k['nama']}</option>";
                            }
                            ?>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label for="bulan" class="form-label">Bulan</label>
                          <input type="text" name="bulan" id="bulan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                          <label for="total_gaji" class="form-label">Total Gaji</label>
                          <input type="number" name="total_gaji" id="total_gaji" class="form-control" required>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                      </form>

                    <?php
                      if (isset($_POST['simpan'])) {
                          $kid = $_POST['karyawan_id'];
                          $bulan = $_POST['bulan'];
                          $gaji = $_POST['total_gaji'];
                          mysqli_query($conn, "INSERT INTO gaji (karyawan_id, bulan, total_gaji) VALUES ('$kid', '$bulan', '$gaji')");
                          echo "<script>location.href='gaji.php';</script>";
                      }
                    ?>
                  </div>
              </div>
          </div>
      </div>
  </div>

</body>
<?php include 'includes/footer.php'; ?>
</html>

<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM gaji WHERE id = $id"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Gaji</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <h3>Edit Gaji</h3>
        <form action="" method="post">
          <div class="mb-3">
            <label>Bulan</label>
            <input type="text" name="bulan" class="form-control" value="<?= $data['bulan'] ?>" required>
          </div>
          <div class="mb-3">
            <label>Total Gaji</label>
            <input type="number" name="total_gaji" class="form-control" value="<?= $data['total_gaji'] ?>" required>
          </div>
          <button type="submit" name="update" class="btn btn-primary">Update</button>
          <button type="submit" name="update" class="btn btn-primary">Kembali</button>
        </form>
      <?php
        if (isset($_POST['update'])) {
          $bulan = $_POST['bulan'];
          $gaji = $_POST['total_gaji'];
          mysqli_query($conn, "UPDATE gaji SET bulan = '$bulan', total_gaji = '$gaji' WHERE id = $id");
          echo "<script>location.href='gaji.php';</script>";
        }
      ?>
    </div>
</body>
</html>

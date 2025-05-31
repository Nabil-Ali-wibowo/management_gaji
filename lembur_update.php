<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $jabatan_id = (int)$_POST['jabatan_id'];
    $tarif_lembur = (int)$_POST['tarif_lembur'];
    $jumlah_jam = (int)$_POST['jumlah_jam'];

    if ($id && $jabatan_id && $tarif_lembur >= 0 && $jumlah_jam >= 0) {
        $query = "UPDATE lembur SET 
                    jabatan_id = $jabatan_id,
                    tarif_lembur = $tarif_lembur,
                    jumlah_jam = $jumlah_jam 
                  WHERE id = $id";

        if (mysqli_query($conn, $query)) {
            header("Location: lembur.php?status=success");
            exit;
        } else {
            echo "Gagal update: " . mysqli_error($conn);
        }
    } else {
        echo "Data tidak valid.";
    }
} else {
    echo "Akses tidak diizinkan.";
}
?>

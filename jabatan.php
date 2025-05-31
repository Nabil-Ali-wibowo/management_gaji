<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Jabatan - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .table thead th {
            vertical-align: middle;
            background-color: #2c3e50;
            color: white;
            font-weight: 500;
        }
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .salary-column {
            font-weight: 600;
            color: #2e7d32;
        }
        .empty-state {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .empty-state i {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="p-4 w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 text-dark"><i class="bi bi-briefcase me-2"></i>Daftar Jabatan</h2>
            <a href="jabatan_tambah.php" class="btn btn-primary btn-icon">
                <i class="bi bi-plus-circle"></i> Tambah Jabatan
            </a>
        </div>

        <div class="table-container p-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Jabatan</th>
                        <th width="200" class="text-end">Gaji Pokok</th>
                        <th width="250" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY id DESC");
                    
                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            $gaji = number_format($row['gaji_pokok'], 0, ',', '.');
                            $namaJS = htmlspecialchars($row['nama_jabatan'], ENT_QUOTES);
                            echo "
                            <tr>
                                <td>$no</td>
                                <td>{$row['nama_jabatan']}</td>
                                <td class='text-end salary-column'>Rp $gaji</td>
                                <td class='text-center action-buttons'>
                                    <a href='jabatan_edit.php?id={$row['id']}' class='btn btn-warning btn-sm btn-icon me-1'>
                                        <i class='bi bi-pencil-square'></i> Edit
                                    </a>
                                    <a href='jabatan_detail.php?id={$row['id']}' class='btn btn-info btn-sm btn-icon me-1 text-white'>
                                        <i class='bi bi-eye'></i> Detail
                                    </a>
                                    <button class='btn btn-danger btn-sm btn-icon' 
                                        onclick='hapusJabatan({$row['id']}, \"{$namaJS}\")'>
                                        <i class='bi bi-trash'></i> Hapus
                                    </button>
                                </td>
                            </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr>
                            <td colspan='4' class='text-center'>
                                <div class='empty-state py-5'>
                                    <i class='bi bi-folder-x'></i>
                                    <p class='text-muted'>Tidak ada data jabatan</p>
                                </div>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function hapusJabatan(id, nama) {
    Swal.fire({
        title: 'Hapus Jabatan?',
        html: `Yakin ingin menghapus jabatan <b>${nama}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`jabatan_hapus.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.text();
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    );
                });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value === 'success') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data jabatan berhasil dihapus',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else if (result.value === 'used') {
                Swal.fire({
                    title: 'Tidak Dapat Dihapus!',
                    html: `Jabatan <b>${nama}</b> masih digunakan.<br>Harap ubah jabatan karyawan terlebih dahulu.`,
                    icon: 'warning'
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menghapus data',
                    icon: 'error'
                });
            }
        }
    });
}
</script>
</body>
</html>
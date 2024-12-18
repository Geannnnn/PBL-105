<!DOCTYPE html>
<html lang="en">
    <?php include "../koneksi.php"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: "Roboto Mono", monospace;
            background-color: #F8F8FC;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            height: 100vh;
            background-color: #f0e6f6;
            padding: 20px;
        }

        .content {
            margin-left: 200px;
            padding: 20px;
            background-color: #fff;
            min-height: 100vh;
            box-sizing: border-box;
            max-width: 90%;
        }

        .sidebar a {
            display: block;
            color: black;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background-color: #d1c4e9;
            border-radius: 20px;
        }

        .sidebar .logout {
            position: absolute;
            bottom: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-lg-2 col-xl-2 col-md-4 col-sm-3 sidebar">
                <a href="dashboard.php">Beranda</a>
                <a style="background-color: #d1c4e9; color: #333; border-radius: 20px;" href="#">Stok</a>
                <a href="st.php">+ Staff Gudang</a>
                <a href="../logout.php" style="width:80%" class="logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>

            
            <div class="col-12 col-lg-10 col-xl-10 col-md-8 col-sm-9 content">
                <table class="table table-bordered text-center mt-5">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stmt = $conn->prepare("SELECT * FROM barang JOIN user ON barang.id_user = user.id_user JOIN kategori ON barang.id_kategori = kategori.id_kategori JOIN satuan ON barang.id_satuan = satuan.id_satuan");
                        $stmt->execute();

                        foreach ($stmt as $index => $rawr) {
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($rawr['id_barang']) ?></td>
                            <td><?= htmlspecialchars($rawr['nama_barang']) ?></td>
                            <td><?= htmlspecialchars($rawr['nama_kategori']) ?></td>
                            <td><?= htmlspecialchars($rawr['nama_satuan']) ?></td>
                            <td><?= htmlspecialchars($rawr['nama']) ?></td>
                            <td>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#detailModal<?= $rawr['id_barang'] ?>" class="btn btn-sm btn-success">Detail</button>
                            </td>
                        </tr>

                       
                        <div class="modal fade" id="detailModal<?= $rawr['id_barang'] ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $rawr['id_barang'] ?>">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel<?= $rawr['id_barang'] ?>">Detail Barang: <?= htmlspecialchars($rawr['nama_barang']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                <img src="../gambar/<?= htmlspecialchars($rawr['gambar']) ?>" class="img-fluid img-thumbnail" alt="<?= htmlspecialchars($rawr['nama_barang']) ?>">
                                            </div>
                                            <div class="col-md-8">
                                                <p><strong>ID Barang:</strong> <?= htmlspecialchars($rawr['id_barang']) ?></p>
                                                <p><strong>Nama Barang:</strong> <?= htmlspecialchars($rawr['nama_barang']) ?></p>
                                                <p><strong>Stok:</strong> <?= htmlspecialchars($rawr['stok']) ?></p>
                                                <p><strong>Kategori:</strong> <?= htmlspecialchars($rawr['nama_kategori']) ?></p>
                                                <p><strong>Satuan:</strong> <?= htmlspecialchars($rawr['nama_satuan']) ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>Riwayat Stok</h5>
                                        <div class="row g-3">
                                            <?php
                                            $historyStmt = $conn->prepare("
                                                SELECT 
                                                    id_transaksi_masuk AS id_transaksi,
                                                    tanggal_masuk AS tanggal,
                                                    'Masuk' AS jenis_transaksi,
                                                    jumlah_masuk AS jumlah,
                                                    NULL AS catatan
                                                FROM transaksi_masuk
                                                WHERE id_barang = ?
                                                UNION ALL
                                                SELECT 
                                                    id_transaksi_keluar AS id_transaksi,
                                                    tanggal_keluar AS tanggal,
                                                    'Keluar' AS jenis_transaksi,
                                                    jumlah_keluar AS jumlah,
                                                    catatan
                                                FROM transaksi_keluar
                                                WHERE id_barang = ?
                                                ORDER BY tanggal ASC
                                            ");
                                            $historyStmt->execute([$rawr['id_barang'], $rawr['id_barang']]);
                                            $history = $historyStmt->fetchAll();

                                            if ($history) {
                                                foreach ($history as $no => $entry) {
                                                    echo "<div class='col-md-6'>
                                                            <div class='card shadow-sm'>
                                                                <div class='card-body'>
                                                                    <h6 class='card-title'>
                                                                        <span class='badge bg-primary'>" . htmlspecialchars($entry['jenis_transaksi']) . "</span>
                                                                        <small class='text-muted'>#" . ($no + 1) . "</small>
                                                                    </h6>
                                                                    <p class='card-text mb-1'><strong>Tanggal:</strong> " . htmlspecialchars($entry['tanggal']) . "</p>
                                                                    <p class='card-text mb-1'><strong>Jumlah:</strong> " . htmlspecialchars($entry['jumlah']) . "</p>
                                                                    <p class='card-text mb-1'><strong>Catatan:</strong> " . htmlspecialchars($entry['catatan'] ?? '-') . "</p>
                                                                </div>
                                                            </div>
                                                        </div>";
                                                }
                                            } else {
                                                echo "<p class='text-center'>Tidak ada riwayat stok</p>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

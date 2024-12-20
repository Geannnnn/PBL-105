<?php
error_reporting(0);
include "../koneksi.php";

// Mengecek apakah ada ID barang yang dikirimkan melalui query parameter
$id_barang = $_GET['id'] ?? null;

if ($id_barang) {
    // Ambil detail barang berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM barang JOIN kategori ON barang.id_kategori = kategori.id_kategori JOIN satuan ON barang.id_satuan = satuan.id_satuan WHERE id_barang = ?");
    $stmt->execute([$id_barang]);
    $barang = $stmt->fetch();

    // Ambil riwayat transaksi barang
    $historyStmt = $conn->prepare("SELECT 
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
                            ORDER BY tanggal DESC");
    $historyStmt->execute([$id_barang, $id_barang]);
    $history = $historyStmt->fetchAll();
} else {
    // Ambil daftar barang
    $stmt = $conn->prepare("SELECT * FROM barang JOIN user ON barang.id_user = user.id_user JOIN kategori ON barang.id_kategori = kategori.id_kategori JOIN satuan ON barang.id_satuan = satuan.id_satuan");
    $stmt->execute();
    $barangList = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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

        .modal-content img {
            max-width: 100%;
            height: auto;
        }

        .detail-header {
            background-color: #6c63ff;
            color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .detail-section {
            margin-top: 30px;
        }

        .detail-section .card {
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .history-table {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
        }

        .download-btn {
            background-color: #6c63ff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            position: fixed; /* Menggunakan position fixed untuk tombol selalu terlihat */
            bottom: 20px; /* Posisi tombol di bagian bawah */
            right: 20px; /* Posisi tombol di sebelah kanan */
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .download-btn:hover {
            background-color: #574bde;
            transform: scale(1.05);
        }

        .card-text p {
            font-size: 14px;
            margin-bottom: 5px;
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
                <?php if ($id_barang): ?>
                    <!-- Halaman Detail Barang -->
                    <div class="detail-header">
                        <h3>Detail Barang: <?= htmlspecialchars($barang['nama_barang']) ?></h3>
                    </div>
                    
                    <div class="row mt-4 detail-section">
                        <div class="col-md-4">
                            <div class="card">
                                <img src="../gambar/<?= htmlspecialchars($barang['gambar']) ?>" class="card-img-top" alt="Gambar Barang">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card p-3">
                                <h5><strong>ID Barang:</strong> <?= htmlspecialchars($barang['id_barang']) ?></h5>
                                <p><strong>Nama Barang:</strong> <?= htmlspecialchars($barang['nama_barang']) ?></p>
                                <p><strong>Stok:</strong> <?= htmlspecialchars($barang['stok']) ?></p>
                                <p><strong>Kategori:</strong> <?= htmlspecialchars($barang['nama_kategori']) ?></p>
                                <p><strong>Satuan:</strong> <?= htmlspecialchars($barang['nama_satuan']) ?></p>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="history-table">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><strong>Riwayat Stok</strong></h5>
                            <button class="download-btn" id="downloadPDF"><i class="fas fa-download"></i> Download PDF</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $no => $entry): ?>
                                    <tr>
                                        <td><?= $no + 1 ?></td>
                                        <td><?= htmlspecialchars($entry['tanggal']) ?></td>
                                        <td><?= htmlspecialchars($entry['jenis_transaksi']) ?></td>
                                        <td><?= htmlspecialchars($entry['jumlah']) ?></td>
                                        <td><?= htmlspecialchars($entry['catatan'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- Halaman Daftar Barang -->
                    <table class="table table-bordered text-center mt-5">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>User</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($barangList as $index => $rawr): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($rawr['id_barang']) ?></td>
                                    <td><?= htmlspecialchars($rawr['nama_barang']) ?></td>
                                    <td><?= htmlspecialchars($rawr['nama_kategori']) ?></td>
                                    <td><?= htmlspecialchars($rawr['nama_satuan']) ?></td>
                                    <td><?= htmlspecialchars($rawr['stok']) ?></td>
                                    <td><?= htmlspecialchars($rawr['nama']) ?></td>
                                    <td>
                                        <a href="?id=<?= $rawr['id_barang'] ?>" class="btn btn-sm btn-success">Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

   <script>
    document.getElementById('downloadPDF')?.addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Header: Menambahkan logo dan judul
    doc.setFontSize(16);
    doc.setFont("helvetica", "bold");
    doc.text("Laporan Stok Barang", 10, 20);
    
    // Menambahkan detail barang di bawah judul
    doc.setFontSize(12);
    doc.setFont("helvetica", "normal");
    doc.text("Detail Barang:", 10, 30);
    doc.text("Nama Barang: <?= htmlspecialchars($barang['nama_barang']) ?>", 10, 40);
    doc.text("ID Barang: <?= htmlspecialchars($barang['id_barang']) ?>", 10, 50);
    doc.text("Stok: <?= htmlspecialchars($barang['stok']) ?>", 10, 60);
    doc.text("Kategori: <?= htmlspecialchars($barang['nama_kategori']) ?>", 10, 70);
    doc.text("Satuan: <?= htmlspecialchars($barang['nama_satuan']) ?>", 10, 80);
    
    // Menambahkan garis pemisah
    doc.setLineWidth(0.5);
    doc.line(10, 85, 200, 85); // Garis horizontal

    // Menambahkan Tabel Riwayat Stok
    doc.autoTable({
        startY: 90,
        head: [['No', 'Tanggal', 'Jenis Transaksi', 'Jumlah', 'Catatan']],
        body: <?= json_encode($history) ?>.map((entry, index) => [
            index + 1, // No dimulai dari 1
            entry.tanggal, // Tanggal
            entry.jenis_transaksi, // Jenis Transaksi
            entry.jumlah, // Jumlah
            entry.catatan || '-' // Catatan
        ]),
        theme: 'grid', // Menggunakan grid untuk tabel
        headStyles: {
            fillColor: [100, 100, 255], // Warna latar belakang header
            textColor: [255, 255, 255], // Warna teks header
            fontSize: 10,
            fontStyle: 'bold',
            halign: 'center'
        },
        bodyStyles: {
            fontSize: 10,
            halign: 'center'
        },
        alternateRowStyles: {
            fillColor: [240, 240, 240] // Warna baris ganjil
        },
        margin: { top: 10, left: 10, right: 10 }
    });

    // Footer: Menambahkan catatan atau informasi tambahan di bawah PDF
    doc.setFontSize(10);
    doc.setFont("helvetica", "italic");
    doc.text("Generated by: Sistem Stok Barang", 10, doc.internal.pageSize.height - 10);
    
    // Menyimpan PDF
    doc.save('detail_barang_<?= htmlspecialchars($barang['id_barang']) ?>.pdf');
});

   </script>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>

</html>





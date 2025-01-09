<!DOCTYPE html>
<?php include"../koneksi.php"; 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin' || empty($_SESSION['role'])) {
    echo "<script>alert('Anda bukan Admin!'); window.location.href = '../logout.php';</script>";
    exit();
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: "Roboto Mono", monospace;
            margin: 0;
            padding: 0;
            background-color:hsla(0, 0.00%, 95.30%, 0.97);
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            background-color:rgb(218, 199, 228);
            height: 100vh;
            padding: 20px;
            border-radius: 5px;
        }
        .sidebar a {
            display: block;
            color: black;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background-color:rgb(252, 246, 255);
            width: 100%;
            border-radius: 20px;
        }
        .sidebar .logout {
            position: absolute;
            bottom: 20px;
            left: 20px;
        }
        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            border: none;
            position: relative;
        }
        .header i {
            margin-left: 20px;
            font-size: 20px;
            cursor: pointer;
        }
        #alertCount {
            position: absolute;
            top: -5px; /* Atur posisi vertikal */
            right: -10px; /* Atur posisi horizontal */
            background: red;
            color: white;
            border-radius: 50%;
            padding: 3px 6px;
            font-size: 12px;
            display: block; /* Pastikan ini tampil */
        }
        #bellIcon {
            position: relative;
            font-size: 24px;
            cursor: pointer;
        }
        #alertDropdown {
            position: absolute;
            top: 40px;
            right: 20px;
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 300px;
            z-index: 1000;
            font-size: 14px;
            display: none;
        }
        #alertBox {
            padding: 10px;
            display: none;
        }
        #alertHistory {
            list-style: none;
            padding: 10px;
            margin: 0;
            max-height: 200px;
            overflow-y: auto;
        }
        #alertHistory li {
            margin-bottom: 10px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .stat-box {
            background-color:rgb(218, 199, 228);
            padding: 20px 20px 0;
            border-radius: 10px;
            text-align: center;
            width: 200px;
            position: relative;
            overflow: hidden;
        }
        .statm {
            background-color:rgb(252, 246, 255);
            border-radius: 0 0 10px 10px;
            padding: 10px;
            position: absolute;
            top: 0;
            left: 20;
            width: 80%;
            text-align: center;
        }
        .stat-content {
            margin-top: 40px; 
        }
        .stat-box p {
            margin: 0;
            font-size: 16px;
        }
        .stat-box span {
            font-size: 30px;
            font-weight: bold;
        }
        .content h1, .content h2 {
            color: #333;
        }
        video {
            width: 100%;
            height: 400px;
            background-color: #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        
        .faq {
            margin: 20px 0;
        }
        .faq-item {
            background-color: #e0e0e0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .faq-item h3 {
            margin: 0;
            color: #007bff;
        }
        .faq-item p {
            margin: 5px 0 0 0;
        }
        .alert-notification {
            position: fixed;
            top: 50px;
            right: 10px;
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 999;
            font-size: 16px;
            display: none; 
        }
        .stok-title {
            font-size: 25px; 
            font-weight: bold; 
            text-align: center; 
            margin-bottom: 20px; 
        }
        
    </style>
    
</head>
<body>
    <?php
    $query = $conn->prepare("SELECT id_barang, nama_barang, stok FROM barang WHERE stok <= 10 ORDER BY stok ASC");
    $query->execute();
    $lowStockItems = $query->fetchAll(PDO::FETCH_ASSOC);

    $queryHistory = $conn->prepare("SELECT id_barang, nama_barang, stok FROM barang WHERE stok <= 10 LIMIT 50");
    $queryHistory->execute();
    $stockHistory = $queryHistory->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="container-fluid">
            <div class="row">
                <div class="col-2">
                    <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 sidebar">
                    <img src="../gambar/1.png" alt="Logo" width="140" height="80">
                    <h3 class="stok-title" style="color: #4a00e0;">
                        <span>STOK</span><span style="color: rgb(223, 37, 198);">STOK</span>
                    </h3>
                        <hr style="border: 1px solid rgb(159, 126, 177);; margin: 10px 0;">

                    <a style="background-color: rgb(252, 246, 255); color: #333; border-radius: 20px;" href="#">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <a href="stok.php">
                        <i class="fas fa-box"></i> Stok
                    </a>
                    <a href="st.php">
                        <i class="fas fa-users"></i> Staff Gudang
                    </a>
                    <a href="../logout.php" class="logout" style="width: 80%;">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>

                    </div>
                </div>
                <div class="col-10 content">
                    <div class="container">
                        <div class="header">
                            <div class="nama-user float-left" style="font-size:18px;margin-right:auto;">
                               Halo, <?= ucwords($_SESSION['nama']); ?>
                            </div>
                            <i id="bellIcon" class="fas fa-bell">
                                <span id="alertCount"><?= count($lowStockItems) ?></span>
                            </i>
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div id="alertDropdown">
                            <div id="alertBox"></div>
                            <hr>
                            <h5 style="margin: 10px;">Notifikasi</h5>
                        <ul id="alertHistory">
                            <?php foreach ($stockHistory as $item): ?>
                                <li><?= $item['nama_barang'] ?> (Stok: <?= $item['stok'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div id="alertNotification" class="alert-notification" style="display: none;">
                        <span id="alertMessage"></span>
                    </div>
                        <div class="stats">
                            <div class="stat-box">
                                <div class="statm">
                                    <p>Barang Masuk</p>
                                </div>
                                <?php $query = $conn->prepare("SELECT count(*) as barangmasuk from transaksi_masuk");
                                $query->execute();
                                $hasil = $query->fetch(PDO::FETCH_ASSOC); ?>
                                <div class="stat-content">
                                    <span><?= $hasil['barangmasuk'] ?></span>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div class="statm">
                                    <p>Barang Keluar</p>
                                </div>
                                <?php $query = $conn->prepare("SELECT count(*) as barangkeluar from transaksi_keluar");
                                $query->execute();
                                $hasil = $query->fetch(PDO::FETCH_ASSOC); ?>
                                <div class="stat-content">
                                    <span><?= $hasil['barangkeluar'] ?></span>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div class="statm">
                                    <p>Seluruh Barang</p>
                                </div>
                                <?php $query = $conn->prepare("SELECT count(*) as totalbarang from barang");
                                $query->execute();
                                $hasil = $query->fetch(PDO::FETCH_ASSOC); ?>
                                <div class="stat-content">
                                    <span><?= $hasil['totalbarang'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <h1>Tutorial Penggunaan</h1>
                            <video controls>
                                <source src="path_to_your_video_file.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <h2>FAQ</h2>
                            <div class="faq">
                                <div class="faq-item">
                                    <h3>Cara Penggunaan ?</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    
    <script>
    const alerts = <?= json_encode($lowStockItems) ?>;
    let currentAlertIndex = 0;

    function updateAlertCount() {
        const alertCount = document.getElementById('alertCount');
        alertCount.textContent = alerts.length;
        alertCount.style.display = alerts.length > 0 ? 'block' : 'none';
    }

    // Fungsi untuk menampilkan notifikasi bergantian
    function showAlert() {
        if (alerts.length > 0 && currentAlertIndex < alerts.length) {
            const alertNotification = document.getElementById('alertNotification');
            const alertMessage = document.getElementById('alertMessage');

            alertNotification.style.display = 'block';
            alertMessage.textContent = `Stok ${alerts[currentAlertIndex].nama_barang} Menipis`;

            setTimeout(() => {
                alertNotification.style.display = 'none';
                // Mengatur index notifikasi untuk barang berikutnya
                currentAlertIndex++;
                if (currentAlertIndex < alerts.length) {
                    setTimeout(showAlert, 1000); // Jeda antar notifikasi (1 detik)
                }
            }, 3000); // Durasi setiap notifikasi (3 detik)
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateAlertCount();
        if (alerts.length > 0) {
            showAlert(); // Menampilkan notifikasi secara bergantian
        }
    });

    document.getElementById('bellIcon').addEventListener('click', () => {
        const dropdown = document.getElementById('alertDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>

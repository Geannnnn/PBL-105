<!DOCTYPE html>
<?php include"../koneksi.php"; ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            background-color: #f0e6f6;
            height: 100vh;
            padding: 20px;
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
        }
        .header i {
            margin-left: 20px;
            font-size: 20px;
            cursor: pointer;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .stat-box {
            background-color: #f0e6f6;
            padding: 20px 20px 0;
            border-radius: 10px;
            text-align: center;
            width: 200px;
            position: relative;
            overflow: hidden;
        }
        .statm {
            background-color: #d1c4e9;
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
            font-size: 18px;
        }
        .stat-box span {
            font-size: 24px;
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
    </style>
    
</head>
<body>
    <div class="container-fluid">
            <div class="row">
                <div class="col-2">
                    <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 sidebar">
                        <a style="background-color: #d1c4e9;
                            color: #333; border-radius: 20px;" href="#">Beranda</a>
                        <a href="stok.php">Stok</a>
                        <a href="st.php">+ Staff Gudang</a>
                        <a href="../logout.php" class="logout" style="width: 80%;" ><i class="fas fa-sign-out-alt"></i> Keluar</a>
                    </div>
                </div>
                <div class="col-10 content">
                    <div class="container">
                        <div class="header">
                            <i class="fas fa-bell"></i>
                            <i class="fas fa-user-circle"></i>
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
    
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
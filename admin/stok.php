<!DOCTYPE html>
<html lang="en">
    <?php include"../s.php"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
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
            margin-right: 0; 
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
            <div class="col-md-3 col-lg-2 sidebar">
                <a href="dashboard.php">Beranda</a>
                <a style="background-color: #d1c4e9; color: #333; border-radius: 20px;" href="#">Stok</a>
                <a href="st.php">+ Staff Gudang</a>
                <a href="../l.php" class="logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
            
            <div class="content">
                <table class="table table-bordered text-center mt-5">
                    <thead>
                        
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>User</th>
                    </tr>
                    </thead>
                    <?php 
                    $stmt = $conn->prepare("SELECT * FROM barang JOIN user on barang.id_user = user.id_user JOIN kategori on barang.id_kategori=kategori.id_kategori JOIN satuan on barang.id_satuan=satuan.id_satuan");
                    $stmt->execute();
                    $no = 1;

                    foreach($stmt as $rawr) {
                    ?>
                    <tbody>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rawr['id_barang'] ?></td>
                        <td><?= $rawr['nama_barang'] ?></td>
                        <td><?= $rawr['nama_kategori'] ?></td>
                        <td><?= $rawr['nama_satuan'] ?></td>
                        <td><?= $rawr['nama'] ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

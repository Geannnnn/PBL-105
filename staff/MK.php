<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
    <style>
        .min-h-screen-extended {
            min-height: 150vh;
        }
        .sidebar {
            width: 200px;
            background-color: #f0e6f6;
            height: 100vh;
            padding: 20px;
            position: fixed;
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
    </style>
</head>
<body class="bg-gray-100 font-sans text-sm">
    <div class="container-fluid">
        <!-- Sidebar -->
        <div class="row">
            <div class="sidebar">
                <a href="dashboard.php">Beranda</a>
                <a href="stok.php">Stok</a>
                <a style="background-color: #d1c4e9;
                    color: #333; border-radius: 20px;" href="#">Barang Masuk & Keluar</a>
                <a href="../l.php" class="logout" style="width: 80%;" ><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        
        </div>
    </div>
</body>
</html>

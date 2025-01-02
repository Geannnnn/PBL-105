<?php include"../koneksi.php"; 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin' || empty($_SESSION['role'])) {
    echo "<script>alert('Anda bukan Admin!'); window.location.href = '../logout.php';</script>";
    exit();
}

?>
<!DOCTYPE html>


<?php if(isset($_POST['adduser'])) {
    $a = $_POST['nama'];
    $b = $_POST['username'];
    $c = $_POST['password'];

    $q = $conn->prepare("INSERT INTO user values(null,'$a','$b','$c','staff')");
    $q = $q->execute();

    header("location:st.php");
} ?>
<?php if(isset($_POST['hapususer'])) {
    $id = $_POST['id_user'];

    $q = $conn->prepare("DELETE FROM user WHERE id_user = '$id'");
    $q = $q->execute();
    header("location:st.php");
} ?>
<?php if(isset($_POST['edituser'])) {
    $id = $_POST['id_user'];
    $a = $_POST['nama'];
    $b = $_POST['username'];
    $c = $_POST['password'];

    $q = $conn->prepare("UPDATE user SET nama = '$a', username = '$b', password = '$c' WHERE id_user = '$id'");
    $q = $q->execute();
    header("location:st.php");
} ?>


<?php ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

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
            height: 100vh;
            background-color:rgb(218, 199, 228);
            padding: 20px;
            border-radius: 5px;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
            background-color: #fff;
            min-height: 100vh;
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
        .main-content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
        }
        .btn-tambah, .btn-reset{
            border-radius:15px; 
            background-color:#6600ff; 
            color:white;
        }
        .btn-tambah:hover, .btn-reset:hover{
            opacity: 80%;
            color: #333;
        }
        .stok-title {
            font-size: 25px; 
            font-weight: bold; 
            text-align: center; 
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .table thead {
            background-color: rgb(218, 199, 228); 
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #fff; 
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: rgb(218, 199, 228); 
        }
    </style>

<body>
    <div class="container-fluid">
        <div class="row">
        
            <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 sidebar">
            <img src="../gambar/1.png" alt="Logo" width="140" height="80">
                    <h3 class="stok-title" style="color: #4a00e0;">
                        <span>STOK</span><span style="color: rgb(223, 37, 198);">STOK</span>
                    </h3>
                <hr style="border: 1px solid rgb(159, 126, 177);; margin: 10px 0;">

                    <a href="dashboard.php">
                <i class="fas fa-home"></i> Beranda
            </a>
            <a href="stok.php">
                <i class="fas fa-box"></i> Stok
            </a>
            <a style="background-color: rgb(252, 246, 255); color: #333; border-radius: 20px;" href="#">
                <i class="fas fa-users"></i> Staff Gudang
            </a>
            <a href="../logout.php" class="logout" style="width: 80%;">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>

            </div>

            <div class="col-lg-10 col-xl-10 col-md-8 col-sm-6 content">
            <h2 class="mt-5" style="margin-left:10px;"><button type="button" data-bs-toggle="modal" data-bs-target="#addUser" style="border: none; background:none;" ><i class="fa-solid fa-circle-plus"></i></button>Akun Staff</h2>
            <div class="modal fade" id="addUser">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="border: none;">
                            <h5 class="modal-title">Tambah Staff</h5>
                            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="modal-add-account mb-3">
                                    <button type="submit" name="adduser" class="btn btn-tambah">Tambah</button>
                                    <button type="reset" class="btn btn-reset">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <table class="table table-striped" style="margin-left:10px; margin-top:5%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                <?php 
                    $stmt = $conn->prepare("SELECT * FROM user where role != 'admin'");
                    $stmt->execute();
                    $no = 1;

                    foreach($stmt as $rawr) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rawr['id_user'] ?></td>
                        <td><?= $rawr['nama'] ?></td>
                        <td><?= $rawr['username'] ?></td>
                        <td><?= $rawr['password'] ?></td>
                        <td><?= $rawr['role'] ?></td>
                        <td>
                            <div class="aksiuser">
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusUser<?= $rawr['id_user']; ?>">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUser<?= $no ?>">
                                 <i name="edits" class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="hapusUser<?= $rawr['id_user'];?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                        
                                <div class="modal-body">
                                    <p>Apakah anda yakin ingin menghapus akun ini?</p>
                                    <form action="" method="post">
                                        <input type="hidden" name="id_user" value="<?= $rawr['id_user'];?>">
                                        <button type="submit" name="hapususer" class="btn btn-danger">Hapus</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editUser<?= $no ?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Akun</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" style="border:none;"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <input type="hidden" name="id_user" value="<?= $rawr['id_user'] ?>">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $rawr['nama'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?= $rawr['username'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="text" class="form-control" id="password" name="password" value="<?= $rawr['password'] ?>" required>
                                    </div>
                                    <div class="modal-edit-account mb-3">
                                    <button type="submit" name="edituser" class="btn btn-tambah">Ubah</button>
                                    <button type="reset" class="btn btn-reset">Batal</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                    <?php } ?>
                </tbody>
            </table>
            <?php $no++ ?>
            
        </div>
    </div>      
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>

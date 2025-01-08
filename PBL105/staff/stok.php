<!DOCTYPE html>
<?php include"../koneksi.php"; 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff' || empty($_SESSION['role'])) {
    echo "<script>alert('Anda bukan staff!'); window.location.href = '../logout.php';</script>";
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: "Roboto Mono", monospace;
            font-optical-sizing: auto;
            background-color: hsla(0, 0.00%, 95.30%, 0.97);
            overflow-x: hidden;
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
            background-color:rgb(252, 246, 255);
            border-radius: 20px;
        }

        .sidebar .logout {
            position: absolute;
            bottom: 20px;
            left: 20px;
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 sidebar">
            <img src="../gambar/1.png" alt="Logo" width="140" height="80">
                    <h3 class="stok-title" style="color: #4a00e0;">
                        <span>STOK</span><span style="color: rgb(223, 37, 198);">STOK</span>
                    </h3>
                    
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <a style="background-color:rgb(252, 246, 255); color: #333; border-radius: 20px;" href="stok.php">
                        <i class="fas fa-box"></i> Stok
                    </a>
                    <a href="MK.php">
                    <i class="fas fa-exchange-alt"></i> Barang Masuk/Keluar
                </a>
                    <a href="../logout.php" style="width:80%" class="logout">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
            </div>
        </div>
            <div class="col-lg-10 col-xl-10 col-md-8 col-sm-6 content">
                <table class="table table-striped mt-5" style="border-collapse: collapse;">
                    <?php if(isset($_GET['kategori']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'staff' || empty($_SESSION['role'])) : ?>
                        
                        <?php if (isset($_POST['addkatego'])) {

                                $nama_kategori = $_POST['nama_kategori'];
                                $id = $_SESSION['id'];  

                                try {
                                    $s = $conn->prepare("INSERT INTO kategori values (null, '$id', '$nama_kategori')");
                                    $s->execute();
                                    echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'stok.php?kategori';</script>";
                                } catch (Exception $e) {
                                    echo "<script>alert('Gagal menambahkan data.'); window.location.href = 'stok.php?kategori';</script>";
                                }
                            }elseif (isset($_POST['hapuskate'])) {
                                $id_k = $_POST['id_kategori'];
                            
                                try {
                                    // Menghapus data yang terkait dengan kategori
                                    $qs = $conn->prepare("DELETE FROM barang WHERE id_kategori = '$id_k'");
                                    $qs->execute();
                            
                                    // Menghapus kategori
                                    $s = $conn->prepare("DELETE FROM kategori WHERE id_kategori = '$id_k'");
                                    $s->execute();
                            
                                    echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'stok.php?kategori';</script>";
                                } catch (Exception $e) {
                                    echo "<script>alert('Gagal menghapus data.'); window.location.href = 'stok.php?kategori';</script>";
                                }
                            }elseif (isset($_POST['editkate'])) {
                            $id_k = $_POST['id_kategori'];
                            $nama_k = $_POST['nama_kategori'];
                            $id = $_SESSION['id'];
                        
                            try {
                                $s = $conn->prepare("UPDATE kategori SET nama_kategori = '$nama_k', id_user = '$id' WHERE id_kategori = '$id_k'");
                                $s->execute();
                                echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'stok.php?kategori';</script>";
                            } catch (Exception $e) {
                                echo "<script>alert('Gagal memperbarui data.'); window.location.href = 'stok.php?kategori';</script>";
                            }
                        }
                        ?>
                        

                        <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <form action="" method="post" class="mb-5">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#addkate" class="btn btn-success mb-5 float-end me-5">Tambah</button>
                            </form>

                            <div class="modal fade" id="addkate">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                             <h4 class="modal-title">Tambah Kategori</h4>
                                             <button type="button" class="btn-close" data-bs-dismiss="modal" style="border:none;"></button>
                                        </div>
                                        <div class="modal-body">
                                             <form action="" method="post">
                                                 <div class="form-floating mb-3 mt-3">
                                                     <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama Kategori" required>
                                                     <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                                 </div>
                                                 <button type="submit" name="addkatego" class="btn btn-primary">Simpan</button>
                                             </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <tr>
                                    <th>No</th>
                                    <th>Id Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <?php 
                            $s = $conn->prepare("SELECT * FROM kategori join user on kategori.id_user = user.id_user "); // where user.id_user = $_SESSION[id] //
                            $s->execute();
                            $no = 1;
                            foreach($s as $rawr) {
                            ?>
                            <tbody>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $rawr['id_kategori']; ?></td>
                                    <td><?= $rawr['nama_kategori'] ?></td>
                                    <td><?= $rawr['nama'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusKate<?= $rawr['id_kategori']; ?>">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editKate<?= $rawr['id_kategori'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="hapusKate<?= $rawr['id_kategori'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                 <p>Apakah anda yakin ingin menghapus kategori <strong>'<?= $rawr['nama_kategori'];?>'</strong>?</p>
                                                 <form action="" method="post">
                                                     <input type="hidden" name="id_kategori" value="<?= $rawr['id_kategori'];?>">
                                                     <button type="submit" name="hapuskate" class="btn btn-danger">Hapus</button>
                                                 </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="editKate<?= $rawr['id_kategori'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                 <h4 class="modal-title">Edit Kategori</h4>
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                 <form action="" method="post">
                                                     <div class="form-floating mb-3">
                                                        <input type="hidden" name="id_kategori" value="<?= $rawr['id_kategori'];?>">
                                                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama Kategori" value="<?= $rawr['nama_kategori'];?>" required>
                                                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                                     </div>
                                                     <button type="submit" name="editkate" class="btn btn-primary mt-3">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>

                    <?php elseif(isset($_GET['satuan']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'staff' || empty($_SESSION['role'])) : ?>

                        <?php if(isset($_POST['tamSatuan'])){
                            $nama_satuan = $_POST['nama_satuan'];
                            $jumlah_satuan = $_POST['jumlah_satuan'];
                            $id = $_SESSION['id'];

                            $s = $conn->prepare("INSERT INTO satuan VALUES (null,'$id','$nama_satuan','$jumlah_satuan')");
                            $s->execute();
                            header("location: stok.php?satuan");
                            exit();

                        }elseif(isset($_POST['hapusSatuan'])){

                            $id_satuan = $_POST['id_satuan'];

                            $qw = $conn->prepare("DELETE from barang where id_satuan = '$id_satuan'");
                            $qw->execute();
                            $s = $conn->prepare("DELETE FROM satuan WHERE id_satuan = $id_satuan");
                            $s->execute();
                            header("location: stok.php?satuan");
                            exit();
                        }elseif(isset($_POST['editSatuan'])) {
                            $id_satuan = $_POST['id_satuan'];
                            $nama_satuan = $_POST['nama_satuan'];
                            $jumlah_satuan = $_POST['jumlah_satuan'];

                            $s = $conn->prepare("UPDATE satuan SET nama_satuan = '$nama_satuan', jumlah_satuan = '$jumlah_satuan' WHERE id_satuan = $id_satuan");
                            $s->execute();
                            header("location: stok.php?satuan");
                            exit();
                        } ?>


                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <form action="" method="post">
                                    <button type="button" class="btn btn-success float-end me-5" data-bs-toggle="modal" data-bs-target="#tamSatuan">Tambah</button>
                                </form>

                                <div class="modal fade" id="tamSatuan">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tambah Satuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" placeholder="Nama Satuan" required>
                                                    <label for="nama_satuan" class="form-label">Nama Satuan</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="jumlah_satuan" name="jumlah_satuan" placeholder="Jumlah Satuan" required>
                                                    <label for="jumlah_satuan" class="form-label">Jumlah Satuan</label>
                                                    </div>
                                                    <button type="submit" name="tamSatuan" class="btn btn-primary mt-3">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <th>No</th>
                                    <th>Id Satuan</th>
                                    <th>Nama Satuan</th>
                                    <th>Jumlah Satuan</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <?php 
                                $s = $conn->prepare("SELECT * FROM satuan join user on satuan.id_user = user.id_user "); // where user.id_user = $_SESSION[id] //
                                $s->execute();
                                $no = 1;
                                
                                foreach($s as $rawr) {
                                ?>


                                <tbody>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $rawr['id_satuan'] ?></td>
                                        <td><?= $rawr['nama_satuan'] ?></td>
                                        <td><?= $rawr['jumlah_satuan'] ?></td>
                                        <td><?= $rawr['nama'] ?></td>
                                        <td>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusSatuan<?= $rawr['id_satuan']; ?>">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSatuan<?= $rawr['id_satuan'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        </td>
                                    </tr>
                                </tbody>

                                <div class="modal fade" id="hapusSatuan<?= $rawr['id_satuan'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                 <h5 class="modal-title">Apakah anda yakin ingin menghapus data <strong><?= $rawr['nama_satuan'] ?></strong></h5>
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <input type="hidden" name="id_satuan" value="<?= $rawr['id_satuan'];?>">
                                                    <button type="submit" name="hapusSatuan" class="btn btn-danger mt-3">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="editSatuan<?= $rawr['id_satuan'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                 <h5 class="modal-title">Edit Satuan</h5>
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                <div class="form-floating mb-3 mt-3">
                                                    <input type="hidden" name="id_satuan" value="<?= $rawr['id_satuan'] ?>">
                                                    <input type="text" class="form-control" id="nama_satuan_edit" name="nama_satuan" value="<?= $rawr['nama_satuan']?>" required>
                                                    <label for="nama_satuan_edit" class="form-label">Nama Satuan</label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="jumlah_satuan_edit" name="jumlah_satuan" value="<?= $rawr['jumlah_satuan']?>" required>
                                                    <label for="jumlah_satuan_edit" class="form-label">Jumlah Satuan</label>
                                                </div>
                                                <button type="submit" name="editSatuan" class="btn btn-primary mt-3">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php }?>
                            </table>
                        </div>
                        


                    <?php else: ?>

                    <thead style="background-color: rgb(218, 199, 228);">
                        
                    <form action="" method="post">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#tambar" class="btn btn-success float-end">Tambah</button>
                    </form>

                    <div class="modal fade" id="tambar">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="id_barang" id="id_barang" placeholder="ID Barang" readonly>
                                            <label for="id_barang">ID Barang</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang" required onkeyup="generateIdBarang()">
                                            <label for="nama_barang" class="form-label">Nama Barang</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="barang_kategori" id="kat" required>
                                                <option value="" selected disabled>Pilih Kategori</option>
                                                <?php
                                                $s = $conn->prepare("SELECT * FROM kategori");
                                                $s->execute();
                                                foreach ($s as $wsw) : ?>
                                                    <option value="<?= $wsw['id_kategori'] ?>"><?= $wsw['nama_kategori']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label for="kat">Kategori</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="barang_satuan" id="sat" required>
                                                <option value="" selected disabled>Pilih Satuan</option>
                                                <?php
                                                $s = $conn->prepare("SELECT * FROM satuan");
                                                $s->execute();
                                                foreach ($s as $wasd) : ?>
                                                    <option value="<?= $wasd['id_satuan'] ?>"><?= $wasd['nama_satuan']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label for="sat">Satuan</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="stok_barang" id="stk" placeholder="Stok" readonly >
                                            <label for="stk">Stok</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="file" class="form-control" name="gambar" id="gam" required>
                                            <label for="gam">Gambar</label>
                                        </div>
                                        <button type="submit" name="addBarang" class="btn btn-primary mt-3">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                        <script>
                            function generateIdBarang() {
                                const namaBarang = document.getElementById('nama_barang').value;
                                if (namaBarang.trim() !== '') {
                                    const words = namaBarang.trim().toUpperCase().split(' ');
                                    let idBarang = '';
                                    words.forEach(word => {
                                        if (word.length > 0) idBarang += word[0]; 
                                    });
                                    document.getElementById('id_barang').value = idBarang;
                                } else {
                                    document.getElementById('id_barang').value = ''; 
                                }
                            }
                        </script>

                        
                        <?php if (isset($_POST['addBarang'])) {
                            $id_barang = $_POST['id_barang'];
                            $nama_barang = $_POST['nama_barang'];
                            $barang_kategori = $_POST['barang_kategori'];
                            $barang_satuan = $_POST['barang_satuan'];
                            $stok_barang = $_POST['stok_barang'] ?? null;
                            $gambar = $_FILES['gambar']['name'];
                            $gambar_tmp = $_FILES['gambar']['tmp_name'];
                            $id_user = $_SESSION['id'];


                            if (empty($id_barang) || empty($nama_barang) || empty($barang_kategori) || empty($barang_satuan)) {
                                echo "Kolom wajib tidak boleh kosong!";
                                exit;
                            }

                            if (empty($stok_barang) || !is_numeric($stok_barang)) {
                                $stok_barang = 0;
                            }

                            if (!empty($gambar)) {
                                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                                $file_type = mime_content_type($gambar_tmp);
                                if (!in_array($file_type, $allowed_types)) {
                                    echo "File harus berupa gambar (JPG, PNG, GIF)!";
                                    exit;
                            }

                            $max_size = 2 * 1024 * 1024; 
                            if (filesize($gambar_tmp) > $max_size) {
                                echo "Ukuran file terlalu besar! Maksimum 2MB.";
                                exit;
                            }

                            $gambar_nama_baru = uniqid() . "_" . basename($gambar);
                            $gambar_folder = "../gambar/" . $gambar_nama_baru;
                        }

                        $check = $conn->prepare("SELECT COUNT(*) FROM barang WHERE id_barang LIKE ?");
                        $check->execute([$id_barang . '%']);
                        $count = $check->fetchColumn();

                        $id_barang .= str_pad($count + 1, 2, '0', STR_PAD_LEFT);

                        $query = $conn->prepare("INSERT INTO barang (id_barang, nama_barang, id_user, id_kategori, id_satuan, stok, gambar) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $success = $query->execute([$id_barang, $nama_barang, $id_user, $barang_kategori, $barang_satuan, $stok_barang, $gambar_nama_baru ?? null]);

                        if ($success) {
                            if (!empty($gambar) && move_uploaded_file($gambar_tmp, $gambar_folder)) {
                                echo "<script>alert('Barang berhasil ditambahkan dengan gambar!'); window.location.href = 'stok.php';</script>";
                            } elseif (empty($gambar)) {
                                echo "<script>alert('Barang berhasil ditambahkan tanpa gambar!'); window.location.href = 'stok.php';</script>";
                            } else {
                                echo "<script>alert('Barang berhasil ditambahkan, tetapi gagal mengunggah gambar.'); window.location.href = 'stok.php';</script>";
                            }
                            error_reporting(0);
                        } else {
                            echo "<script>alert('Gagal menyimpan data ke database.'); window.location.href = 'stok.php';</script>";
                        }
                        
                    }
                    elseif (isset($_POST['deleteBarang'])) {
                        $id = $_POST['id'];
                    
                        try {
                            $s = $conn->prepare("DELETE FROM barang WHERE id_barang = '$id'");
                            $s->execute();
                    
                            echo "<script>alert('Barang berhasil dihapus!'); window.location.href = 'stok.php';</script>";
                        } catch (Exception $e) {
                            echo "<script>alert('Gagal menghapus barang.'); window.location.href = 'stok.php';</script>";
                        }
                    }elseif (isset($_POST['editBarang'])) {
                        $fo = ($_FILES['gambar']['name'] == '') ? $_POST['odd'] : $_FILES['gambar']['name'];
                    
                        $awd = $_POST['id_barang'];
                        $nb = $_POST['nama_barang'];
                        $k = $_POST['barang_kategori'];
                        $sat = $_POST['barang_satuan'];
                        $stok = $_POST['stok_barang'];
                        $gas = $_FILES['gambar']['tmp_name'];
                        $isd = $_SESSION['id'];
                    
                        try {
                            $query = "UPDATE barang 
                                      SET id_kategori = ?, id_satuan = ?, nama_barang = ?, stok = ?, gambar = ?, id_user = ?
                                      WHERE id_barang = ?";
                            $s = $conn->prepare($query);
                            $s->execute([$k, $sat, $nb, $stok, $fo, $isd, $awd]);
                    
                            if ($_FILES['gambar']['name'] != '') {
                                move_uploaded_file($gas, '../gambar/' . $fo);
                            }
                    
                            echo "<script>alert('Data barang berhasil diperbarui!'); window.location.href = 'stok.php';</script>";
                        } catch (Exception $e) {
                            echo "<script>alert('Gagal memperbarui data barang.'); window.location.href = 'stok.php';</script>";
                        }
                    }
                    
                    if (isset($_GET['id_barang'])) {
                        $barang = $conn->prepare("SELECT barang.*, satuan.id_satuan, kategori.id_kategori 
                                                  FROM barang 
                                                  LEFT JOIN satuan ON barang.id_satuan = satuan.id_satuan 
                                                  LEFT JOIN kategori ON barang.id_kategori = kategori.id_kategori 
                                                  WHERE id_barang = ?");
                        $barang->execute([$_GET['id_barang']]);
                        $rawr = $barang->fetch(PDO::FETCH_ASSOC);
                    }
                    ?>

                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>
                            <form action="" method="get" style="display: inline;">
                                <button type="submit" style="border:none; background:none; font-weight:bold;" name="kategori">+ Kategori</button>
                            </form>
                        </th>
                        <th>
                            <form action="" method="get" style="display: inline;">
                                <button type="submit" style="border:none; background:none; font-weight:bold;" name="satuan">+ Satuan</button>
                            </form>
                        </th>
                        <th>Stok</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $stmt = $conn->prepare("SELECT * FROM barang JOIN user on barang.id_user = user.id_user JOIN kategori on barang.id_kategori=kategori.id_kategori JOIN satuan on barang.id_satuan=satuan.id_satuan");
                    $stmt->execute();
                    $no = 1;

                    foreach($stmt as $rawr) {
                        $rowStyle = ($no % 2 == 1) ? 'background-color: #fff;' : 'background-color: rgb(218, 199, 228);';
                    ?>
                    <tr style="<?= $rowStyle ?>">
                        <td><?= $no++ ?></td>
                        <td><?= $rawr['id_barang'] ?></td>
                        <td><?= $rawr['nama_barang'] ?></td>
                        <td><?= $rawr['nama_kategori'] ?></td>
                        <td><?= $rawr['nama_satuan'] ?></td>
                        <td><?= $rawr['stok'] ?></td>
                        <td><?= $rawr['nama'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusBarang<?= $rawr['id_barang']; ?>">
                                <i class="fa-solid fa-trash-can"></i> Hapus
                            </button>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBarang<?= $rawr['id_barang'] ?>">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="hapusBarang<?= $rawr['id_barang'] ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="post">
                                <div class="modal-header">
                                    <input type="hidden" name="id" value="<?= $rawr['id_barang'] ?>">
                                    <h5 class="modal-title"><p><strong>Apakah anda yakin ingin menghapus data <?= $rawr['nama_barang']; ?></strong></p></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <button type="submit" name="deleteBarang" class="btn btn-danger">Hapus</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editBarang<?= $rawr['id_barang'] ?>">
                        <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-floating mb-3">
                                        <input type="hidden" name="id_barang" value="<?= $rawr['id_barang'] ?>">
                                        <input type="text" class="form-control" name="id_barang" id="id" value="<?= $rawr['id_barang'] ?>" readonly>
                                        <label for="id">Id Barang</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="nama_barang" id="nb" value="<?= $rawr['nama_barang'] ?>">
                                        <label for="nb">Nama Barang</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="barang_satuan" id="sat">
                                            <?php 
                                            $s = $conn->prepare("SELECT * FROM satuan");
                                            $s->execute();
                                            foreach ($s as $rawwr) {
                                                $selected = ($rawwr['id_satuan'] == $rawr['id_satuan']) ? 'selected' : '';
                                            ?>
                                            <option value="<?= $rawwr['id_satuan']; ?>" <?= $selected; ?>><?= $rawwr['nama_satuan']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <label for="sat">Satuan</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="barang_kategori" id="kat">
                                            <?php 
                                            $s = $conn->prepare("SELECT * FROM kategori"); 
                                            $s->execute();
                                            foreach ($s as $rawdr) { 
                                                $selected = ($rawdr['id_kategori'] == $rawr['id_kategori']) ? 'selected' : '';
                                            ?>
                                            <option value="<?= $rawdr['id_kategori']; ?>" <?= $selected; ?>><?= $rawdr['nama_kategori']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <label for="kat">Kategori</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="stok_barang" id="rors" placeholder="Stok" readonly value="<?= $rawr['stok'] ?>">
                                        <label for="rors">Stok</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="file" class="form-control" name="gambar" id="gam">
                                        <label for="gam">Gambar</label>
                                        <input type="hidden" name="odd" value="<?= $rawr['gambar'] ?>">
                                    </div>
                                    
                                    <button type="submit" name="editBarang" class="btn btn-primary">Simpan</button>
                                    </form>
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

<?php endif ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

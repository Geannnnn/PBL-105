
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
                <a style="background-color: #d1c4e9; color: #333; border-radius: 20px;" href="stok.php">Stok</a>
                <a href="mk.php">Barang Masuk & Keluar</a>
                <a href="../l.php" class="logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
            
            <div class="content">
                <table class="table table-bordered text-center mt-5">
                    <?php if(isset($_GET['kategori'])) : ?>
                        
                        <?php if(isset($_POST['addkatego'])) {
                            
                            $nama_kategori = $_POST['nama_kategori'];
                            $jenis_kategori = $_POST['jenis_kategori'];
                            $id = $_SESSION['id'];  
                            

                            $s = $conn->prepare("INSERT INTO kategori values (null,'$id','$nama_kategori','$jenis_kategori')");
                            $s->execute();
                            header("location:stok.php?kategori");
                        }elseif (isset($_POST['hapuskate'])) {
                            $id_k = $_POST['id_kategori'];


                            $qs = $conn->prepare("DELETE FROM barang WHERE id_kategori = '$id_k'");
                            $qs ->execute();
                            $s = $conn->prepare("Delete from kategori where id_kategori = '$id_k'");
                            $s->execute();

                            header("location:stok.php?kategori");
                        }elseif (isset($_POST['editkate'])) {
                            $id_k = $_POST['id_kategori'];
                            $nama_k = $_POST['nama_kategori'];
                            $jenis_k = $_POST['jenis_kategori'];
                            $id = $_SESSION['id'];

                            $s = $conn->prepare("UPDATE kategori SET nama_kategori = '$nama_k', id_user='$id' ,jenis_kategori = '$jenis_k' WHERE id_kategori = '$id_k'");
                            $s->execute();
                            header("location:stok.php?kategori");
                        } ?>
                        

                        <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <form action="" method="post" class="mb-5">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#addkate" class="btn btn-success mb-5 float-end me-5">Tambah</button>
                            </form>

                            <div class="modal" id="addkate">
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
                                                 <div class="form-floating mb-5">
                                                     <input type="text" class="form-control" id="jenis_kategori" name="jenis_kategori" placeholder="Jenis Kategori" required>
                                                     <label for="jenis_kategori" class="form-label">Jenis Kategori</label>
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
                                    <th>Jenis Kategori</th>
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
                                    <td><?= $rawr['jenis_kategori'] ?></td>
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

                                <div class="modal" id="hapusKate<?= $rawr['id_kategori'] ?>">
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

                                <div class="modal" id="editKate<?= $rawr['id_kategori'] ?>">
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
                                                     <div class="form-floating mb-3">
                                                        <input type="hidden" name="jenis_kategori" value="<?= $rawr['jenis_kategori'];?>">
                                                        <input type="text" class="form-control" id="jenis_kategori" name="jenis_kategori" placeholder="Jenis Kategori" value="<?= $rawr['jenis_kategori'];?>" required>
                                                        <label for="jenis_kategori" class="form-label">Jenis Kategori</label>
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

                    <?php elseif(isset($_GET['satuan'])) : ?>

                        <?php if(isset($_POST['tamSatuan'])){
                            $nama_satuan = $_POST['nama_satuan'];
                            $jumlah_satuan = $_POST['jumlah_satuan'];
                            $id = $_SESSION['id'];

                            $s = $conn->prepare("INSERT INTO satuan VALUES (null,'$id','$nama_satuan','$jumlah_satuan')");
                            $s->execute();
                            header("location: stok.php?satuan");

                        }elseif(isset($_POST['hapusSatuan'])){

                            $id_satuan = $_POST['id_satuan'];

                            $qw = $conn->prepare("DELETE from barang where id_satuan = '$id_satuan'");
                            $qw->execute();
                            $s = $conn->prepare("DELETE FROM satuan WHERE id_satuan = $id_satuan");
                            $s->execute();
                            header("location: stok.php?satuan");
                        }elseif(isset($_POST['editSatuan'])) {
                            $id_satuan = $_POST['id_satuan'];
                            $nama_satuan = $_POST['nama_satuan'];
                            $jumlah_satuan = $_POST['jumlah_satuan'];

                            $s = $conn->prepare("UPDATE satuan SET nama_satuan = '$nama_satuan', jumlah_satuan = '$jumlah_satuan' WHERE id_satuan = $id_satuan");
                            $s->execute();
                            header("location: stok.php?satuan");
                        } ?>


                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <form action="" method="post">
                                    <button type="button" class="btn btn-success float-end me-5" data-bs-toggle="modal" data-bs-target="#tamSatuan">Tambah</button>
                                </form>

                                <div class="modal" id="tamSatuan">
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

                                <div class="modal" id="hapusSatuan<?= $rawr['id_satuan'] ?>">
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

                                <div class="modal" id="editSatuan<?= $rawr['id_satuan'] ?>">
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

                    <thead>
                        
                    <form action="" method="post">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#tambar" class="btn btn-success">Tambah</button>
                    </form>

                    <div class="modal" id="tambar">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                             
                                <div class="modal-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="text" name="id_barang" id="">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang" required>
                                        <label for="nama_barang" class="form-label">Nama Barang</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="barang_kategori" id="kat">
                                            <option value="" selected disabled>Pilih Kategori</option>
                                                <?php $s = $conn->prepare("SELECT * FROM kategori"); 
                                                $s->execute();
                                                foreach ($s as $wsw) : ?>
                                            <option value="<?= $wsw['id_kategori'] ?>"><?= $wsw['nama_kategori']; ?></option>
                                         <?php  endforeach ?>
                                        </select>
                                        <label for="kat">Kategori</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="barang_satuan" id="sat">
                                            <option value="" selected disabled>Pilih Satuan</option>
                                            <?php $s = $conn->prepare("SELECT * FROM satuan"); 
                                            $s->execute();
                                            foreach ($s as $wasd) : ?>
                                            <option value="<?= $wasd['id_satuan'] ?>"><?= $wasd['nama_satuan']; ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <label for="sat">Satuan</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="stok_barang" id="stk" placeholder="Stok">
                                        <label for="stk">Stok</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="file" class="form-control" name="gambar" id="gam">
                                        <label for="gam">Gambar</label>
                                    </div>
                                    <button type="submit" name="addBarang" class="btn btn-primary mt-3">Simpan</button>           
                                    </form>
                                </div>
                            </div>
                        </div>                
                    </div>
                        
                    <?php if(isset($_POST['addBarang'])) {
                        $id = $_POST['id_barang'];
                        $s = $_POST['barang_kategori'];
                        $d = $_POST['nama_barang'];
                        $ids = $_SESSION['id'];
                        $sat = $_POST['barang_satuan'];
                        $stok = $_POST['stok_barang'];
                        $gambar = $_FILES['gambar']['name'];
                        $gas = $_FILES['gambar']['tmp_name'];


                        $s = $conn->prepare("insert into barang values ('$id','$s','$d','$ids','$sat','$stok','$gambar')");
                        $s->execute();
                        move_uploaded_file($gas, '../gambar/'. $gambar);
                        header("location: stok.php");

                    }elseif(isset($_POST['deleteBarang'])) {
                        $id = $_POST['id'];

                        $s = $conn->prepare("Delete from barang where id_barang = '$id'");
                        $s->execute();
                        error_reporting(0);
                        header("location:stok.php");
                    }elseif(isset($_POST['editBarang'])) {
                        $fo;
                        if ($_FILES['gambar']['name'] == '') {
                            $fo = $_POST['odd'];
                        }else{
                            $fo = $_FILES['gambar']['name'];
                        }

                        $awd = $_POST['id_barang'];
                        $nb = $_POST['nama_barang'];
                        $k = $_POST['barang_kategori'];
                        $sat = $_POST['barang_satuan'];
                        $stok = $_POST['stok_barang'];
                        $gas = $_FILES['gambar']['tmp_name'];
                        $isd = $_SESSION['id'];

                        $s = $conn->prepare("UPDATE barang SET id_kategori = '$k', nama_barang = '$nb', stok = '$stok', gambar ='$fo', id_user = '$isd' where id_barang = '$awd' ");
                        $s->execute();
                        move_uploaded_file($gas, '../gambar/'. $fo);
                        error_reporting(0);
                        header("location: refresh:0");
                    } 
                    ?>

                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th><form action="" method="get"><button type="submit" style="border:none; background:none; font-weight:bold;" name="kategori">+ Kategori</button></form></th>
                        <th><form action="" method="get"><button type="submit" style="border:none; background:none; font-weight:bold;" name="satuan">+ Satuan</button></form></th>
                        <th>Stok</th>
                        <th>User</th>
                        <th>Aksi</th>
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

                    <div class="modal" id="hapusBarang<?= $rawr['id_barang'] ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="post">
                                <div class="modal-header">
                                    <input type="hidden" name="id" value="<?= $rawr['id_barang'] ?>">
                                    <h5 class="modal-title"><p><strong>Apakah anda yakin ingin menhapus data <?= $rawr['nama_barang']; ?></strong></p></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <button type="submit" name="deleteBarang" class="btn btn-danger">Hapus</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal" id="editBarang<?= $rawr['id_barang'] ?>">
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
                                            <option value="<?= $rawr['id_satuan']; ?>" selected disabled><?= $rawr['nama_satuan']; ?></option>
                                                <?php $s = $conn->prepare("SELECT * FROM satuan"); 
                                                $s->execute();
                                                foreach ($s as $rawwr) { ?>
                                            <option value="<?= $rawwr['id_satuan']; ?>"><?= $rawwr['nama_satuan']; ?></option>
                                         <?php } ?>
                                        </select>
                                        <label for="sat">Satuan</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="barang_kategori" id="kat">
                                            <option value="<?= $rawr['id_kategori']; ?>" selected disabled><?= $rawr['nama_kategori']; ?></option>
                                                <?php $s = $conn->prepare("SELECT * FROM kategori"); 
                                                $s->execute();
                                                foreach ($s as $rawdr) { ?>
                                            <option value="<?= $rawdr['id_kategori']; ?>"><?= $rawdr['nama_kategori']; ?></option>
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

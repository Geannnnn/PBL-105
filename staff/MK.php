<!DOCTYPE html>
<?php include"../s.php"; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
            background-color: #F8F8FC;
            margin-left: 200px; 
            padding: 20px;
            min-height: 100vh;
            margin-right: 0; 
            box-sizing: border-box; 
            max-width: 100%;
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
            <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 sidebar">
                <a href="dashboard.php">Beranda</a>
                <a href="stok.php">Stok</a>
                <a style="background-color: #d1c4e9; color: #333; border-radius: 20px;" href="#">Barang Masuk & Keluar</a>
                <a href="../l.php" class="logout" style="width: 80%;" ><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        
            <div class="col-lg-10 col-xl-10 col-md-8 col-sm-6 content">
                <table class="table table-striped">
                    <thead>
                        <button type="button" name="tammk" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#tamtam" >Tambah</button>
                        
                        <?php
                        if (isset($_POST['addmk'])) {
                            $id_barang = $_POST['barang'];
                            $stok = $_POST['stok'];
                            $tanggal = $_POST['tanggalmk'];
                            $jenis = $_POST['barangmk'];
                            $catatan = $_POST['catatan'] ?? '';
                            $iduser = $_SESSION['id'];

                            if ($id_barang && $stok && $tanggal && $jenis) {
                                if ($jenis == "barangmasuk") {
                                    $query = $conn->prepare("INSERT INTO transaksi_masuk (id_barang, id_transaksi_masuk, id_user, jumlah_masuk, tanggal_masuk) VALUES (?, ?, ?, ?, ?)");
                                    $query->execute([$id_barang, null, $iduser, $stok, $tanggal]);
                                } elseif ($jenis == "barangkeluar") {
                                    $query = $conn->prepare("INSERT INTO transaksi_keluar (id_barang, id_transaksi_keluar, id_user,tanggal_keluar, jumlah_keluar,  catatan) VALUES (?, ?, ?, ?, ? ,?)");
                                    $query->execute([$id_barang, null, $iduser, $tanggal, $stok, $catatan]);
                                }
                                echo "<script>alert('Data berhasil ditambahkan!');</script>";
                            } else {
                                echo "<script>alert('Harap isi semua data!');</script>";
                            }
                        }
                        ?>

                        <div class="modal" id="tamtam">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="post">
                                    <div class="modal-header">
                                        <h2>Tambah data</h2>  
                                        <input type="button" class="btn-close" data-bs-dismiss="modal">                                      
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <select name="barang" id="bara" class="form-select" onchange="lihat_stok( this )">
                                                <option value="">Pilih Barang</option>
                                                <?php 
                                                $s = $conn->prepare("SElect * from barang");
                                                $s->execute();
                                                foreach ($s as $swad) :
                                                ?>
                                                <option value="<?= $swad['id_barang'] ?>" data-stok="<?= $swad['stok'] ?>"><?= $swad['nama_barang'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label for="bara">Pilih Barang</label>
                                        </div>

                                        <script>
                                            function lihat_stok(select) {
                                            var stok = select.options[select.selectedIndex].getAttribute("data-stok");
                                            
                                            document.getElementById('stok_barang').innerHTML = "(" + stok + ")";
                                            document.getElementById('stok').setAttribute('maxvalue', stok);
                                        }
                                        </script>

                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="stok" id="stok" maxvalue="">
                                            <label for="stok">Stok <span id="stok_barang">(Pilih Barang)</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="date" name="tanggalmk" class="form-control" placeholder="Pilih Tanggal">
                                            <label for="tmk">Pilih Tanggal</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                        <select name="barangmk" id="barangmk" class="form-control">
                                            <option value="" selected disabled>Barang Masuk/Barang Keluar</option>
                                            <option value="barangmasuk">Barang Masuk</option>
                                            <option value="barangkeluar">Barang Keluar</option>
                                        </select>
                                            <label for="barangmk">Pilih!</label>
                                        </div>
                                        <div id="catatan" class="form-floating mb-3" style="display: none;">
                                            <input type="text" class="form-control" name="catatan" placeholder="Catatan">
                                            <label for="alasan">Catatan</label>
                                        </div>
                                        <button type="submit" name="addmk" class="btn btn-primary">Simpan</button>
                                        <script>
                                            document.getElementById('barangmk').addEventListener('change', function () {
                                            const catatanDiv = document.getElementById('catatan');
                                            if (this.value === 'barangkeluar') {
                                                catatanDiv.style.display = 'block'; 
                                            } else {
                                                catatanDiv.style.display = 'none';  
                                            }
                                        });
                                        </script>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </thead>

                    <?php
                    // Ambil parameter jenis dari URL
                    $jenis = $_GET['jenis'] ?? 'all';

                    if ($jenis == 'masuk') {
                        $query = "SELECT id_transaksi_masuk AS id_transaksi, id_barang, jumlah_masuk AS jumlah, tanggal_masuk AS tanggal, 'Masuk' AS jenis_transaksi, id_user, NULL AS catatan FROM transaksi_masuk";
                    } elseif ($jenis == 'keluar') {
                        $query = "SELECT id_transaksi_keluar AS id_transaksi, id_barang, jumlah_keluar AS jumlah, tanggal_keluar AS tanggal, 'Keluar' AS jenis_transaksi, id_user, catatan FROM transaksi_keluar";
                    } else {
                        $query = "SELECT id_transaksi, id_barang, jumlah, tanggal, jenis_transaksi, id_user, catatan 
                                    FROM (
                                        SELECT id_transaksi_masuk AS id_transaksi, id_barang, jumlah_masuk AS jumlah, tanggal_masuk AS tanggal, 'Masuk' AS jenis_transaksi, id_user, NULL AS catatan FROM transaksi_masuk
                                        UNION
                                        SELECT id_transaksi_keluar AS id_transaksi, id_barang, jumlah_keluar AS jumlah, tanggal_keluar AS tanggal, 'Keluar' AS jenis_transaksi, id_user, catatan FROM transaksi_keluar
                                    ) AS all_transaksi";
                    }

                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $transaksi = $stmt->fetchAll();
                    ?>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>ID Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>
                                    <div class="dropdown">
                                    <button type="button" style="background:none;border:none;" class="fw-bold dropdown-toggle" data-bs-toggle="dropdown">
                                        Jenis Transaksi
                                    </button>
                                    <ul class="dropdown-menu fw-bold">
                                        <li><a class="dropdown-item" href="mk.php?jenis=masuk">Masuk</a></li>
                                        <li><a class="dropdown-item" href="mk.php?jenis=keluar">Keluar</a></li>
                                        <li><a class="dropdown-item" href="mk.php?jenis=all">Semua</a></li>
                                    </ul>
                                    </div>
                                </th>
                                <th>ID User</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($transaksi as $data) { ?>
                                 <tr>
                                        <td> <?= $data['id_transaksi']; ?> </td>
                                        <td><?= $data['id_barang']; ?>  </td>
                                        <td><?= $data['jumlah'] ?>  </td>
                                        <td><?= $data['tanggal'] ?>  </td>
                                        <td><?= $data['jenis_transaksi'] ?>  </td>
                                        <td><?= $data['id_user'] ?>  </td>
                                        <td><?= ($data['catatan'] ?? '') ?>  </td>
                                    </tr>
                           <?php }
                            ?>
                        </tbody>
                    </table>
                </table>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>

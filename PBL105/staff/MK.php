<!DOCTYPE html>
<?php include"../koneksi.php"; ?>

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
            font-family: "Roboto Mono", monospace;
            font-optical-sizing: auto;
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
                <a href="../logout.php" class="logout" style="width: 80%;" ><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        
            <div class="col-lg-10 col-xl-10 col-md-8 col-sm-6 content">
                <table class="table table-striped">
                    <thead>
                        <button type="button" name="tammk" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#tamtam" >Tambah</button>
                        
                        <?php
                        if (isset($_POST['addmk'])) {
                            $id_barang = $_POST['barang'];
                            $tanggal = $_POST['tanggalmk'];
                            $jenis = $_POST['barangmk'];
                            $catatan = $_POST['catatan'] ?? '';
                            $iduser = $_SESSION['id'];
                            $jumlah_stok = $_POST['jumlahstok'];

                            if ($id_barang && $jumlah_stok && $tanggal && $jenis) {
                                if ($jenis == "barangmasuk") {
                                    $query = $conn->prepare("INSERT INTO transaksi_masuk (id_barang, id_transaksi_masuk, id_user, jumlah_masuk, tanggal_masuk) VALUES (?, ?, ?, ?, ?)");
                                    $query->execute([$id_barang, null, $iduser, $jumlah_stok, $tanggal]);
                                } elseif ($jenis == "barangkeluar") {
                                    $query = $conn->prepare("INSERT INTO transaksi_keluar (id_barang, id_transaksi_keluar, id_user,tanggal_keluar, jumlah_keluar,  catatan) VALUES (?, ?, ?, ?, ? ,?)");
                                    $query->execute([$id_barang, null, $iduser, $tanggal, $jumlah_stok, $catatan]);
                                }
                                echo "<script>alert('Data berhasil ditambahkan!');</script>";
                            } else {
                                echo "<script>alert('Harap isi semua data!');</script>";
                            }
                        }
                        ?>

                    <div class="modal fade" id="tamtam">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header">
                                        <h2>Tambah Data</h2>
                                        <input type="button" class="btn-close" data-bs-dismiss="modal">
                                    </div>

                                    <div class="modal-body">

                                        <div class="form-floating mb-3">
                                            <select name="barangmk" id="barangmk" class="form-select">
                                                <option value="" selected disabled>Barang Masuk/Barang Keluar</option>
                                                <option value="barangmasuk">Barang Masuk</option>
                                                <option value="barangkeluar">Barang Keluar</option>
                                            </select>
                                            <label for="barangmk">Pilih!</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <select name="barang" id="bara" class="form-select" onchange="lihatBarang(this)">
                                                <option selected disabled value="">Pilih Barang</option>
                                                <?php 
                                                $s = $conn->prepare("
                                                    SELECT 
                                                        barang.id_barang, 
                                                        barang.nama_barang, 
                                                        barang.stok, 
                                                        satuan.nama_satuan, 
                                                        satuan.jumlah_satuan 
                                                    FROM barang 
                                                    JOIN satuan ON barang.id_satuan = satuan.id_satuan
                                                ");
                                                $s->execute();
                                                foreach ($s as $swad) :
                                                ?>
                                                <option 
                                                    value="<?= $swad['id_barang'] ?>" 
                                                    data-stok="<?= $swad['stok'] ?>" 
                                                    data-satuan="<?= $swad['nama_satuan'] ?>" 
                                                    data-jumlah_satuan="<?= $swad['jumlah_satuan'] ?>">
                                                    <?= $swad['nama_barang'] ?>
                                                </option>
                                                <?php endforeach ?>
                                            </select>
                                            <label for="bara">Pilih Barang</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="stok" id="stok" placeholder="Jumlah Stok" readonly>
                                            <label for="stok">Jumlah Stok</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="date" name="tanggalmk" id="tmk" class="form-control" placeholder="Pilih Tanggal">
                                            <label for="tmk">Pilih Tanggal</label>
                                        </div>
                                        
                                        <div class="form-floating mb-3">
                                            <input type="text" id="barangsatuan" name="barangsatuan" class="form-control" placeholder="Jenis Satuan" readonly>
                                            <label for="barangsatuan">Satuan</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="number" name="jumlahstok" id="jumlahstok" class="form-control" placeholder="Masukkan Jumlah">
                                            <label for="jumlahstok">Masukkan Jumlah</label>
                                        </div>


                                        <div id="catatan" class="form-floating mb-3" style="display: none;">
                                            <input type="text" class="form-control" name="catatan" placeholder="Catatan">
                                            <label for="catatan">Catatan</label>
                                        </div>

                                        <button type="submit" name="addmk" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        function lihatBarang(select) {
                            const selectedOption = select.options[select.selectedIndex];
                            const satuan = selectedOption.getAttribute('data-satuan') || 'Tidak Ada Satuan';
                            const jumlahSatuan = selectedOption.getAttribute('data-jumlah_satuan') || '0';
                            const stok = selectedOption.getAttribute('data-stok') || '0';

                            document.getElementById('barangsatuan').value = `${satuan} (${jumlahSatuan})`;

                            document.getElementById('stok').value = stok;

                        }

                        document.getElementById('jumlahstok').addEventListener('input', function() {
                            const maxStok = parseInt(document.getElementById('stok').value); 
                            let jumlahInput = parseInt(this.value); 
                            
                            const jenisTransaksi = document.getElementById('barangmk').value; 

                            if (jenisTransaksi === 'barangkeluar') {
                                if (jumlahInput > maxStok) {
                                    // alert("Jumlah yang dimasukkan melebihi stok yang tersedia.");
                                    this.value = maxStok; 
                                }
                            }
                        });

                        document.getElementById('barangmk').addEventListener('change', function() {
                            const jenisTransaksi = this.value;
                            const stok = parseInt(document.getElementById('stok').value); 

                            if (jenisTransaksi === 'barangkeluar') {
                                const jumlahInput = parseInt(document.getElementById('jumlahstok').value);

                                if (jumlahInput > stok) {
                                    document.getElementById('jumlahstok').value = stok;
                                }
                            }
                        });

                        document.getElementById('barangmk').addEventListener('change', function () {
                            const catatanDiv = document.getElementById('catatan');
                            catatanDiv.style.display = this.value === 'barangkeluar' ? 'block' : 'none';
                        });
                    </script>


                    </thead>

                    <?php
                $jenis = $_GET['jenis'] ?? 'all';

                if ($jenis == 'masuk') {
                $query = "SELECT 
                    tm.id_transaksi_masuk AS id_transaksi, 
                    tm.id_barang, 
                    tm.jumlah_masuk AS jumlah, 
                    tm.tanggal_masuk AS tanggal, 
                    'Masuk' AS jenis_transaksi, 
                    u.nama AS nama_user, 
                    NULL AS catatan 
                FROM transaksi_masuk tm
                JOIN user u ON tm.id_user = u.id_user";
                } elseif ($jenis == 'keluar') {
                $query = "SELECT 
                    tk.id_transaksi_keluar AS id_transaksi, 
                    tk.id_barang, 
                    tk.jumlah_keluar AS jumlah, 
                    tk.tanggal_keluar AS tanggal, 
                    'Keluar' AS jenis_transaksi, 
                    u.nama AS nama_user, 
                    tk.catatan 
                FROM transaksi_keluar tk
                JOIN user u ON tk.id_user = u.id_user";
                } else {
                $query = "SELECT 
                    id_transaksi, 
                    id_barang, 
                    jumlah, 
                    tanggal, 
                    jenis_transaksi, 
                    nama_user, 
                    catatan
                FROM (
                SELECT 
                    tm.id_transaksi_masuk AS id_transaksi, 
                    tm.id_barang, 
                    tm.jumlah_masuk AS jumlah, 
                    tm.tanggal_masuk AS tanggal, 
                    'Masuk' AS jenis_transaksi, 
                    u.nama AS nama_user, 
                    NULL AS catatan 
                FROM transaksi_masuk tm
                JOIN user u ON tm.id_user = u.id_user
                        
                UNION ALL
                
                SELECT 
                        tk.id_transaksi_keluar AS id_transaksi, 
                        tk.id_barang, 
                        tk.jumlah_keluar AS jumlah, 
                        tk.tanggal_keluar AS tanggal, 
                        'Keluar' AS jenis_transaksi, 
                        u.nama AS nama_user, 
                        tk.catatan 
                    FROM transaksi_keluar tk
                    JOIN user u ON tk.id_user = u.id_user
                ) AS all_transaksi";
                }

                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <script>
                        new DataTable('#example');
                    </script>

                    <table id="example" class="table table-striped table-bordered">
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
                                <th>Nama User</th>
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
                                        <td><?= $data['nama_user'] ?>  </td>
                                        <td><?= ($data['catatan'] ?? '-') ?>  </td>
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

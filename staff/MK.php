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
                <a style="background-color: #d1c4e9;
                    color: #333; border-radius: 20px;" href="#">Barang Masuk & Keluar</a>
                <a href="../l.php" class="logout" style="width: 80%;" ><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        
            <div class="col-lg-10 col-xl-10 col-md-8 col-sm-6 content">
                
                <table class="table table-striped">
                    <thead>
                        <button type="button" name="tammk" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#tamtam" >Tambah</button>
                        
                        

                        <div class="modal" id="tamtam">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="post">
                                    <div class="modal-header">
                                        <h2>Tambah data</h2>  
                                        <input type="button" class="btn-close" data-bs-dismiss="modal" >                                      
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                        <select name="barang" id="bara" class="form-select">
                                                <option value="">Pilih Barang</option>
                                                <?php 
                                                $s = $conn->prepare("SElect * from barang");
                                                $s ->execute();
                                                foreach ($s as $swad) :
                                                ?>
                                                <option value="<?= $swad['id_barang'] ?>"><?= $swad['nama_barang'] ?></option>
                                                <?php endforeach ?>
                                        </select>
                                        <label for="bara">Pilih Barang</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="stok" id="stok">
                                            <label for="stok">Stok()</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="date" name="tanggalmk" id="tmk" class="form-control" placeholder="Pilih Tanggal">
                                            <label for="tmk">Pilih Tanggal</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select name="barangmk" class="form-control" id="barangmk">
                                                <option value="" selected disabled>Barang Masuk/Barang Keluar</option>
                                                <option value="barangmasuk">Barang Masuk</option>
                                                <option value="barangkeluar">Barang Keluar</option>
                                            </select>
                                            <label for="barangmk">Pilih!</label>
                                        </div>
                                        <div id="catatan" class="form-floating mb-3" style="display: none;">
                                            <input type="text" class="form-control" name="catatan" id="alasan" placeholder="Catatan Barang Keluar">
                                            <label for="alasan">Catatan Barang Keluar</label>
                                        </div>
                                        <button type="submit" name="addmk" class="btn btn-primary" >Simpan</button>
                                        
                                    </div>
                                    </form>
                                </div>

                               

                                <script>
                                    const barangSelect = document.getElementById('bara');
                                    const stokInput = document.getElementById('stok');
                                    const alasanDiv = document.getElementById('catatan');
                                    const barangmkSelect = document.getElementById('barangmk');

                                    barangSelect.addEventListener('change', function() {
                                        const barangId = this.value;  

                                        if (barangId) {
                                            fetch('get_stok.php', {
                                                method: 'POST',
                                                body: new URLSearchParams({
                                                    'id_barang': barangId
                                                })
                                            })
                                            .then(response => response.json()) 
                                            .then(data => {
                                                if (data.stok) {
                                                    stokInput.value = data.stok;  
                                                } else {
                                                    stokInput.value = 'Stok tidak tersedia';  
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                stokInput.value = 'Error mengambil stok'; 
                                            });
                                        } else {
                                            stokInput.value = '';
                                        }
                                    });

                                    barangmkSelect.addEventListener('change', function() {
                                        if (this.value === 'barangkeluar') {
                                            alasanDiv.style.display = 'block';  
                                        } else {
                                            alasanDiv.style.display = 'none'; 
                                        }
                                    });
                                </script>

                            </div>
                        </div>

                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>

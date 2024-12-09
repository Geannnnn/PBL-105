<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Stok</title>
    <script>
        function pPrint() {
            document.getElementById('backk').style.display = 'none';
            

            window.print();

            setTimeout(function() {
                document.getElementById('backk').style.display = 'block';
                document.getElementById('printButton').style.display = 'block';
            }, 1000); 
        }

        
    </script>
    <style>
        @media print {
            #backk, #printButton {
                display: none;
            }
            
            body {
                font-size: 12px;
            }
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h2 id="itemName">Detail Stok</h2>
        <div class="card">
            <div class="card-body">
                <img id="itemImage" style="width: 250px; height: 250px;" src="" class="card-img-top mb-3" alt="Gambar Barang">
                
                <p><strong>Nama Barang:</strong> <span id="itemNameDetail"></span></p>
                <p><strong>Kategori:</strong> <span id="itemCategory"></span></p>
                <p><strong>Stok Masuk:</strong> <span id="itemMasuk"></span></p>
                <p><strong>Stok Keluar:</strong> <span id="itemKeluar"></span></p>
                <p><strong>Tanggal:</strong> <span id="itemDate"></span></p>

                <button id="printButton" onclick="pPrint()" style="float:right;" class="btn btn-sm btn-primary">Print</button>

            </div>
            
        </div>
        <a id="backk" href="ys.html" class="btn btn-primary mt-3">Kembali</a>
    </div>


    
</body>
</html>

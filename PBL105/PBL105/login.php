<!DOCTYPE html>
<?php include "koneksi.php"; ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php 
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Validasi input
        if (empty($username) || empty($password)) {
            echo "<script>alert('Username dan Password tidak boleh kosong.');</script>";
        } else {
            if (preg_match('/^[a-zA-Z0-9]+$/', $username) && preg_match('/^[a-zA-Z0-9]+$/', $password)) {
                // Query untuk mengambil data pengguna berdasarkan username
                $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($data) {
                    // Cek apakah user role-nya adalah admin atau staff
                    if ($data['role'] == 'admin') {
                        // Jika admin, password langsung dibandingkan tanpa hashing
                        if ($password === $data['password']) {
                            $_SESSION["nama"] = $data['nama'];
                            $_SESSION['role'] = $data['role'];
                            echo "<script>alert('Login berhasil sebagai Admin'); window.location='admin/dashboard.php';</script>";
                        } else {
                            echo "<script>alert('Username atau Password salah.');</script>";
                        }
                    } elseif ($data['role'] == 'staff') {
                        // Jika staff, verifikasi password dengan password_hash
                        if (password_verify($password, $data['password'])) {
                            $_SESSION["nama"] = $data['nama'];
                            $_SESSION['role'] = $data['role'];
                            $_SESSION['id'] = $data['id_user'];
                            echo "<script>alert('Login berhasil sebagai Staff'); window.location='staff/dashboard.php';</script>";
                        } else {
                            echo "<script>alert('Username atau Password salah.');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('Username atau Password salah.');</script>";
                }
            } else {
                echo "<script>alert('Username dan Password hanya boleh mengandung huruf dan angka.');</script>";
            }
        }
    }
?>

    <style>
        @keyframes rollInTogether {
            0% {
                transform: translateX(-100%) rotate(-360deg);
                opacity: 0;
            }
            100% {
                transform: translateX(0) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #e0c3fc, #8ec5fc);
            background-size: 200% 200%;
            animation: gradientAnimation 10s ease infinite;
            font-family: Arial, sans-serif;
            margin: 0; 
            padding: 0; 
            box-sizing: border-box;
            cursor: url('custom-cursor.png'), auto;
        }

        .login-container {
            position: relative;
            background-color: #fff;
            padding: 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            height: auto;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            z-index: 2;
        }

        .abstract-corner {
            position: absolute;
            width: 80px;
            height: 70px;
            background: linear-gradient(135deg, rgba(74, 0, 224, 0.8), rgba(142, 197, 252, 0.8));
            border-radius: 50%;
            z-index: 1;
        
        }
        
        
        .abstract-corner.top-left {
            top: -30px;
            left: -30px;
        }

        .abstract-corner.top-right {
            top: -30px;
            right: -30px;
        }

        .abstract-corner.bottom-left {
            bottom: -30px;
            left: -30px;
        }

        .abstract-corner.bottom-right {
            bottom: -30px;
            right: -30px;
        }

        .logo-title {
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: rollInTogether 1.5s ease-out forwards; 
            opacity: 0;
            animation-fill-mode: forwards; 
        }

        .logo-title img,
        .logo-title h3 {
            transform-origin: center; 
        }

        .logo-title h3 {
            margin-top: 10px;
            font-weight: bold;
        }

        .login-container h2 {
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        .form-label {
            transition: color 0.3s, transform 0.3s;
        }

        .form-control:focus + .form-label  {
            transform: scale(1.05);
            color: #8ec5fc;
            transform: translateY(-10px);
        }

        .form-control {
            border-radius: 20px; 
            padding: 10px 40px 10px 15px; 
            border: 1px solid #ddd; 
            box-sizing: border-box; 
        }

        .btn-login:hover {
            background-color: #8ec5fc;
            box-shadow: 0 4px 8px rgba(74, 0, 224, 0.2);
            background: linear-gradient(90deg, #4a00e0, #8ec5fc);
            transition: background 0.5s ease;
        }

        .btn-login {
            background-color: #4a00e0;
            color: #fff;
            border: none;
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            border-radius: 20px;
            font-weight: bold;
    
        }

        .input-group {
            position: relative; 
            border-radius: 20px; 
            overflow: visible; 
        }

        .input-icon {
            position: absolute;
            right: 15px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: #4a00e0; 
            pointer-events: none; 
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .form-control:focus ~ .input-icon {
            color: #8ec5fc;
            transform: scale(1.2);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="abstract-corner top-left"></div>
        <div class="abstract-corner top-right"></div>
        <div class="abstract-corner bottom-left"></div>
        <div class="abstract-corner bottom-right"></div>

        <div class="logo-title">
            <img alt="Logo" height="100" src="Gambar/ourlogo.png" width="200" />
            <h3 class="stok-title" style="color: #4a00e0;">
                <span>STOK</span><span style="color: rgb(223, 37, 198);">STOK</span>
            </h3>
        </div>

        <div style="height: 5px; background: linear-gradient(to right, #4a00e0, #8ec5fc); border-radius: 10px; margin: 20px 0;"></div>
        <h2 class="titles" style="margin-top: 20px; text-align: center;">Halo, Silahkan Login</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <div class="input-group">
                    <input class="form-control" id="username" name="username" placeholder="Username" type="text"/>
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <input class="form-control" id="password" name="password" placeholder="Password" type="password"/>
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>
            <button class="btn btn-login" name="login">Masuk</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
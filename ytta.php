<!DOCTYPE html>
    
<?php include"s.php"; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<?php 
     if (isset($_POST['ror'])) {
        $u1 = $_POST['u1'];
        $u2 = $_POST['u2'];

        $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $u1);
        $stmt->bindParam(':password', $u2);
        $stmt->execute();
        $data = $stmt->rowCount();

        if ($data > 0) {
            $rawr = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rawr['role'] == 'admin') {
                $_SESSION["nama"] = $rawr['nama'];
                $_SESSION['role'] = $rawr['role'];
                header("location:admin/ror.html");
            } elseif ($rawr['role'] == 'staff') {
                $_SESSION['nama'] = $rawr['nama'];
                $_SESSION['role'] = $rawr['role'];
                header("location:staff/");
            }
        }
    }

 ?>

<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f8f9fa;
    }
    .login-container h2 {
        margin-top: 20px;
        margin-bottom: 30px;
        font-size: 18px;
        color: #333;
    }
    .btn-login {
        background-color: #333;
        color: #fff;
        border: none;
        margin-top: 5px;
        padding: 10px 10px;
        width: 30%;
        border-radius: 9px;
    }
    .titles {
        text-align: center;
    }
    .input-group {
        position: relative;
    }
    .input-group .form-control {
        padding-right: 2.5rem;
    }
    .input-group .input-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #333;
    }
</style>
<body>
    <div class="login-container">
        <img alt="Logo" height="100" src="" width="100"/>
        <h2 class="titles">
            Halo, Silahkan Login
        </h2>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label" for="username">
                    Username
                </label>
                <div class="input-group">
                    <input class="form-control" id="username" name="u1" placeholder="Username" type="text"/>
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">
                    Password
                </label>
                <div class="input-group">
                    <input class="form-control" id="password"  name="u2" placeholder="Password" type="password"/>
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>
            <button class="btn btn-login" name="ror">
                Masuk
            </button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>

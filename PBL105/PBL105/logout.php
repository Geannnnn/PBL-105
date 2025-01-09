<?php 
session_start();

if (isset($_SESSION['role'])) {
    session_destroy();
    session_unset();

    echo "<script>alert('Anda telah logout!'); window.location.href = 'login.php';</script>";
} else {
    header("Location: login.php");
    exit();
}
?>

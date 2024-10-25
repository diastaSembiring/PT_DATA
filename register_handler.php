<?php
include 'koneksi.php';

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if(mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location='index.php';</script>";
    } else {
        // Simpan user baru
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if(mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!'); window.location='index.php';</script>";
        }
    }
}
?>

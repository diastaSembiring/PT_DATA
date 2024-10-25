<?php
$servername = "localhost";  // Biasanya localhost jika kamu menggunakan XAMPP
$username = "root";         // Username default XAMPP adalah 'root'
$password = "";             // Password default biasanya kosong
$dbname = "data_pc";  // Ganti dengan nama database kamu

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

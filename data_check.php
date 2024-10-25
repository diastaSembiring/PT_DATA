<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "data_pc");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data QR dari URL
$qrcode = $_GET['qrcode'];

// Query untuk mencari data di database berdasarkan hasil QR code
$sql = "SELECT * FROM pm_data WHERE qrcode = '$qrcode'";
$result = $conn->query($sql);

$response = [];
if ($result->num_rows > 0) {
    // Data ditemukan, kirim respons dengan data
    $row = $result->fetch_assoc();
    $response['found'] = true;
    $response['id'] = $row['id'];
    $response['nama'] = $row['nama'];
    $response['tanggal'] = $row['tanggal'];
    $response['deskripsi'] = $row['deskripsi'];
} else {
    // Data tidak ditemukan
    $response['found'] = false;
}

// Kembalikan respons dalam format JSON
echo json_encode($response);

$conn->close();
?>

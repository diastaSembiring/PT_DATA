<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "data_pc");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$id = $_POST['id'];
$nama = $_POST['nama'];
$tanggal = $_POST['tanggal'];
$deskripsi = $_POST['deskripsi'];

$response = []; // Array untuk menampung hasil

// Cek apakah ini update atau insert baru
if ($id) {
    // Update data
    $sql = "UPDATE pm_data SET nama='$nama', tanggal='$tanggal', deskripsi='$deskripsi' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        $response['status'] = 'success';
        $response['message'] = 'Data berhasil diperbarui!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error saat memperbarui data: ' . $conn->error;
    }
} else {
    // Insert data baru
    $sql = "INSERT INTO pm_data (nama, tanggal, deskripsi) VALUES ('$nama', '$tanggal', '$deskripsi')";
    if ($conn->query($sql) === TRUE) {
        $response['status'] = 'success';
        $response['message'] = 'Data baru berhasil ditambahkan!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error saat menambahkan data: ' . $conn->error;
    }
}

echo json_encode($response); // Kirim respons sebagai JSON

$conn->close();
?>

<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'data_pc');

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = ''; // Untuk menyimpan pesan sukses atau error

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM pm_tel WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Data berhasil dihapus!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Data PM</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f5f0; /* Warna latar belakang putih lembut */
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            background-color: #2a9d8f; /* Hijau elegan */
            color: #fff;
            padding: 20px;
            margin: 0;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .message {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            color: #2a9d8f; /* Hijau elegan */
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #2a9d8f; /* Hijau elegan */
            font-size: 16px;
        }

        .back-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Hapus Data PM</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <a href="data_pm.php" class="back-button">Kembali ke Data PM</a>
    <a href="index.php" class="back-button">Kembali ke Menu Utama</a>
</body>
</html>

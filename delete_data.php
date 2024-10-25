<?php
include 'koneksi.php';

$message = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM pc_tel WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Data berhasil dihapus!";
        // Redirect otomatis ke search_data.php setelah penghapusan
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location.href='search_data.php';
              </script>";
        exit(); // Menghentikan eksekusi lebih lanjut setelah redirect
    } else {
        $message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Data PC</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f5f0;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #2a9d8f;
            margin-bottom: 20px;
        }
        .message {
            margin: 20px 0;
            font-size: 18px;
            color: #2a9d8f;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2a9d8f;
            text-decoration: none;
            padding: 10px 20px;
            border: 1px solid #2a9d8f;
            border-radius: 5px;
        }
        .back-link:hover {
            background-color: #2a9d8f;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hapus Data PC</h2>
        <p class="message"><?php echo $message; ?></p>
        <a href="search_data.php" class="back-link">Kembali ke Pencarian</a>
    </div>
</body>
</html>

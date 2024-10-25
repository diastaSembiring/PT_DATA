<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pc_tel WHERE id='$id'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama_pc = $_POST['nama_pc'];
    $tanggal_input = $_POST['tanggal_input'];
    $kondisi_pc = $_POST['kondisi_pc'];
    $jenis_pc = $_POST['jenis_pc'];
    $lokasi_pc = $_POST['lokasi_pc'];

    $sql = "UPDATE pc_tel SET nama_pc='$nama_pc', tanggal_input='$tanggal_input', kondisi_pc='$kondisi_pc', jenis_pc='$jenis_pc', lokasi_pc='$lokasi_pc' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='search_data.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data PC</title>
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
        }
        h2 {
            color: #2a9d8f;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            color: #2a9d8f;
        }
        input[type="text"],
        input[type="datetime-local"] {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #2a9d8f;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            background-color: #2a9d8f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #248f7f;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2a9d8f;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data PC</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

            <label for="nama_pc">Nama PC:</label>
            <input type="text" id="nama_pc" name="nama_pc" value="<?php echo htmlspecialchars($data['nama_pc']); ?>" required>

            <label for="tanggal_input">Tanggal Input:</label>
            <input type="datetime-local" id="tanggal_input" name="tanggal_input" value="<?php echo date('Y-m-d\TH:i', strtotime($data['tanggal_input'])); ?>" required>

            <label for="kondisi_pc">Kondisi PC:</label>
            <input type="text" id="kondisi_pc" name="kondisi_pc" value="<?php echo htmlspecialchars($data['kondisi_pc']); ?>" required>

            <label for="jenis_pc">Jenis PC:</label>
            <input type="text" id="jenis_pc" name="jenis_pc" value="<?php echo htmlspecialchars($data['jenis_pc']); ?>" required>

            <label for="lokasi_pc">Lokasi PC:</label>
            <input type="text" id="lokasi_pc" name="lokasi_pc" value="<?php echo htmlspecialchars($data['lokasi_pc']); ?>" required>

            <input type="submit" value="Perbarui Data">
        </form>
        <a href="search_data.php" class="back-link">Kembali ke Pencarian Data</a>
    </div>
</body>
</html>
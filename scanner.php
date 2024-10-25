<?php
$conn = new mysqli('localhost', 'root', '', 'data_pc');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$scannedData = null;

// Proses pembaruan data saat menerima input dari QR code
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $kondisi_pc = $_POST['kondisi_pc'];  // Ambil kondisi setelah dari form
    $lokasi_pc = $_POST['lokasi_pc'];  // Ambil lokasi dari form
    $tanggal_setelah = $_POST['tanggal_input'];  // Ambil tanggal setelah dari form

    // Update data di tabel pc_tel
    $sql = "UPDATE pc_tel SET kondisi_pc='$kondisi_pc', lokasi_pc='$lokasi_pc' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Data berhasil diperbarui!"; // Pesan sukses
        
        // Simpan data ke tabel pm_tel
        $kondisi_sebelum = $_POST['kondisi_sebelum'];
        $tanggal_sebelum = $_POST['tanggal_sebelum'];
        $jenis_pc = $_POST['jenis_pc'];
        
        $sql_pm = "INSERT INTO pm_tel (pc_id, kondisi_sebelum, tanggal_sebelum, kondisi_setelah, tanggal_setelah, lokasi_pc, jenis_pc) 
                   VALUES ('$id', '$kondisi_sebelum', '$tanggal_sebelum', '$kondisi_pc', '$tanggal_setelah', '$lokasi_pc', '$jenis_pc')";
        
        if ($conn->query($sql_pm) === TRUE) {
            $success_message_pm = "Data berhasil disimpan ke PM!"; // Pesan sukses PM
        } else {
            $error_message = "Error: " . $sql_pm . "<br>" . $conn->error; // Pesan error
        }
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error; // Pesan error
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pc_tel WHERE id='$id'";
    $result = $conn->query($sql);
    $scannedData = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Data PC</title>
    <script src="jsQR.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <a href="index.php" class="back-button">Kembali ke Halaman Utama</a>

   <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        video {
            border: 1px solid black;
            display: block;
            margin: 0 auto;
        }

        #scannerForm {
            display: none; /* Sembunyikan form saat halaman pertama kali dimuat */
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        input[type="text"], input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .alert {
            color: green;
            text-align: center;
            margin: 15px 0;
        }

        .error {
            color: red;
            text-align: center;
            margin: 15px 0;
        }

        .back-button {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 15px;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
            margin-top: 10px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
        /* Style untuk video kamera */
video {
    display: block; /* Menampilkan video sebagai elemen blok */
    margin: 20px auto; /* Pusatkan video di halaman */
    width: 80%; /* Mengatur lebar video */
    max-width: 400px; /* Batas lebar maksimum video */
    border: 6px solid #4CAF50; /* Border hijau segar */
    border-radius: 15px; /* Sudut melengkung */
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3); /* Tambahkan bayangan untuk efek 3D */
    transition: transform 0.3s ease-in-out; /* Animasi transisi */
}

video:hover {
    transform: scale(1.05); /* Perbesar sedikit saat hover */
    box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.4); /* Tambahkan efek bayangan lebih kuat saat hover */
}

/* Style untuk form */
#scannerForm {
    display: none; /* Sembunyikan form saat halaman pertama kali dimuat */
    background: rgba(255, 255, 255, 0.9); /* Latar belakang putih dengan transparansi */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Bayangan untuk form */
    margin-top: 20px;
}

/* Style untuk tombol upload file */
input[type="file"] {
    display: block;
    margin: 20px auto;
    padding: 10px;
    border: 2px dashed #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

input[type="file"]:hover {
    border-color: #4CAF50; /* Warna border saat hover */
}

/* Tombol Kembali */
.back-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #0056b3;
}

/* Style untuk pesan hasil pemindaian */
#result {
    text-align: center;
    color: #333;
    margin-top: 10px;
    font-size: 18px;
}

    </style>
</head>
<body>
    <h2>Scanner Data PC</h2>
    <video id="video" width="300" height="200"></video>
    <p id="result">Pindai QR Code menggunakan kamera atau unggah file gambar QR code.</p>

    <input type="file" id="qrfile" accept="image/*"><br><br>

    <form id="scannerForm" method="POST" action="">
        <label for="id_pc">ID PC:</label>
        <input type="text" id="id_pc" name="id" required readonly><br>

        <label for="nama_pc">Nama PC:</label>
        <input type="text" id="nama_pc" name="nama_pc" required readonly><br>

        <label for="kondisi_sebelum">Kondisi Sebelum:</label>
        <input type="text" id="kondisi_sebelum" name="kondisi_sebelum" required readonly><br>

        <label for="tanggal_sebelum">Tanggal Sebelum:</label>
        <input type="datetime-local" id="tanggal_sebelum" name="tanggal_sebelum" required readonly><br>

        <label for="kondisi_pc">Kondisi Setelah:</label>
        <input type="text" id="kondisi_pc" name="kondisi_pc" required><br>

        <label for="tanggal_input">Tanggal Input:</label>
        <input type="datetime-local" id="tanggal_input" name="tanggal_input" required><br>

        <label for="lokasi_pc">Lokasi PC:</label>
        <input type="text" id="lokasi_pc" name="lokasi_pc" required><br>

        <label for="jenis_pc">Jenis PC:</label>
        <input type="text" id="jenis_pc" name="jenis_pc" required><br>

        <input type="submit" name="update" value="Perbarui Data">
        <a href="index.php" class="back-button">Kembali ke Halaman Utama</a>
    </form>

    <script>
        let isScanningFile = false; // Menentukan apakah file dipilih

        const video = document.getElementById("video");
        const result = document.getElementById("result");
        const canvasElement = document.createElement("canvas");
        const canvas = canvasElement.getContext("2d");

        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then((stream) => {
                video.srcObject = stream;
                video.setAttribute("playsinline", true);
                video.play();
                requestAnimationFrame(tick);
            })
            .catch(err => {
                console.error("Error accessing camera: ", err);
                result.innerText = "Gagal mengakses kamera. Silakan periksa izin.";
            });

        function formatDateString(dateString) {
            if (!dateString) {
                return ""; // Mengembalikan string kosong jika tidak ada data
            }

            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                console.error("Tanggal tidak valid:", dateString);
                return ""; // Mengembalikan string kosong jika tanggal tidak valid
            }

            return date.toISOString().slice(0, 16); // Mengambil yyyy-MM-ddThh:mm
        }

        function tick() {
            if (!isScanningFile && video.readyState === video.HAVE_ENOUGH_DATA) {
                // Hanya lanjutkan pemindaian dari video jika tidak sedang memindai file
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    const dataArray = code.data.split('|');
                    console.log("Data QR Code:", dataArray); // Log untuk memeriksa data yang dipindai

                    document.getElementById('id_pc').value = dataArray[0]; // ID PC dari QR code
                    document.getElementById('nama_pc').value = dataArray[1]; // Nama PC dari QR code
                    document.getElementById('kondisi_sebelum').value = dataArray[2]; // Kondisi Sebelum
                    document.getElementById('tanggal_sebelum').value = formatDateString(dataArray[3]); // Tanggal Sebelum
                    document.getElementById('lokasi_pc').value = dataArray[4]; // Lokasi PC
                    document.getElementById('jenis_pc').value = dataArray[5]; // Jenis PC
                    
                    // Tampilkan form setelah pemindaian berhasil
                    document.getElementById("scannerForm").style.display = "block"; // Tampilkan form
                } else {
                    result.innerText = "Scan a QR code";
                }
            }
            if (!isScanningFile) requestAnimationFrame(tick); // Lanjutkan hanya jika tidak sedang memindai file
        }

        document.getElementById("qrfile").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                isScanningFile = true; // Set agar berhenti memindai dari kamera
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        canvasElement.width = img.width;
                        canvasElement.height = img.height;
                        canvas.drawImage(img, 0, 0, img.width, img.height);
                        const imageData = canvas.getImageData(0, 0, img.width, img.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height);
                        
                        if (code) {
                            const dataArray = code.data.split('|');
                            console.log("Data QR Code:", dataArray); // Log untuk memeriksa data yang dipindai

                            document.getElementById('id_pc').value = dataArray[0]; // ID PC dari QR code
                            document.getElementById('nama_pc').value = dataArray[1]; // Nama PC dari QR code
                            document.getElementById('kondisi_sebelum').value = dataArray[2]; // Kondisi Sebelum
                            document.getElementById('tanggal_sebelum').value = formatDateString(dataArray[3]); // Tanggal Sebelum
                            document.getElementById('lokasi_pc').value = dataArray[4]; // Lokasi PC
                            document.getElementById('jenis_pc').value = dataArray[5]; // Jenis PC
                            
                            // Tampilkan form setelah pemindaian berhasil
                            document.getElementById("scannerForm").style.display = "block"; // Tampilkan form
                        } else {
                            result.innerText = "QR code tidak valid atau tidak ditemukan.";
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
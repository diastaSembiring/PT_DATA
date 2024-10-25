<?php
$conn = new mysqli('localhost', 'root', '', 'data_pc');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$scannedData = null;
$success_message = null;
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $kondisi_pc = $_POST['kondisi_pc'];
    $lokasi_pc = $_POST['lokasi_pc'];
    $tanggal_setelah = $_POST['tanggal_input'];

    $sql = "UPDATE pc_tel SET kondisi_pc=?, lokasi_pc=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $kondisi_pc, $lokasi_pc, $id);
    
    if ($stmt->execute()) {
        $success_message = "Data PC berhasil diperbarui!";
        
        $kondisi_sebelum = $_POST['kondisi_sebelum'];
        $tanggal_sebelum = $_POST['tanggal_sebelum'];
        $jenis_pc = $_POST['jenis_pc'];
        
        $sql_pm = "INSERT INTO pm_tel (pc_id, kondisi_sebelum, tanggal_sebelum, kondisi_setelah, tanggal_setelah, lokasi_pc, jenis_pc) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_pm = $conn->prepare($sql_pm);
        $stmt_pm->bind_param("sssssss", $id, $kondisi_sebelum, $tanggal_sebelum, $kondisi_pc, $tanggal_setelah, $lokasi_pc, $jenis_pc);
        
        if ($stmt_pm->execute()) {
            $success_message .= " Data PM berhasil disimpan!";
        } else {
            $error_message = "Error menyimpan data PM: " . $stmt_pm->error;
        }
        $stmt_pm->close();
    } else {
        $error_message = "Error memperbarui data PC: " . $stmt->error;
    }
    $stmt->close();
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pc_tel WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $scannedData = $result->fetch_assoc();
    $stmt->close();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2a9d8f;
            --primary-light: #3fb5a6;
            --primary-dark: #238579;
            --secondary-color: #264653;
            --accent-color: #e9c46a;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
            --border-radius: 12px;
            --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            padding: 2rem;
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
        }

        .header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .scanner-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        .video-wrapper {
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .video-container {
            position: relative;
            padding-top: 75%;
            background: var(--primary-color);
            border-radius: var(--border-radius);
        }

        video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: calc(var(--border-radius) - 4px);
            border: 3px solid var(--primary-color);
        }

        .file-upload {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            border: 2px dashed var(--primary-color);
            border-radius: var(--border-radius);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            background: linear-gradient(145deg, #f0f0f0, #ffffff);
            transform: translateY(-2px);
            border-color: var(--primary-light);
        }

        .file-upload i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .file-upload p {
            color: var(--text-color);
            font-size: 1.1rem;
        }

        #scannerForm {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group i {
            position: absolute;
            top: 42px;
            left: 12px;
            color: var(--primary-color);
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input[type="text"]:focus,
        input[type="datetime-local"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.1);
        }

        .readonly-field {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            width: 100%;
            margin-bottom: 1rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary {
            background: var(--primary-color);
            box-shadow: 0 4px 15px rgba(42, 157, 143, 0.3);
        }

        .btn-secondary {
            background: var(--secondary-color);
            box-shadow: 0 4px 15px rgba(38, 70, 83, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-light);
        }

        .btn-secondary:hover {
            background: #2d5468;
        }

        .back-btn {
            max-width: 300px;
            margin: 0 auto 2rem auto;
            display: block;
        }

        #result {
            text-align: center;
            color: var(--text-color);
            margin: 1.5rem 0;
            padding: 1rem;
            background: white;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            box-shadow: var(--box-shadow);
        }

        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: var(--border-radius);
            text-align: center;
            animation: slideIn 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .alert i {
            font-size: 1.2rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                padding: 1rem;
            }

            .header h2 {
                font-size: 2rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 1.5rem;
            }

            .btn {
                padding: 0.8rem 1.5rem;
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
      <!-- Tombol Kembali di bagian atas -->
      <a href="index.php" class="btn btn-secondary back-btn">
            <i class="fas fa-home"></i> Kembali ke Halaman Utama
        </a>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-qrcode"></i> Scanner Data PC</h2>
            <p>Scan QR Code atau upload gambar untuk memperbarui data PC</p>
        </div>

        <!-- Tombol Kembali di bagian atas -->
   

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="scanner-container">
                <div class="video-wrapper">
                    <div class="video-container">
                        <video id="video" playsinline></video>
                    </div>
                </div>

                <div class="file-upload" id="fileUploadArea">
                    <i class="fas fa-file-upload"></i>
                    <p>Klik untuk upload gambar QR Code</p>
                    <input type="file" id="qrfile" accept="image/*" style="display: none;">
                </div>

                <div id="result">
                    <i class="fas fa-camera"></i> Arahkan kamera ke QR Code atau upload gambar
                </div>
            </div>
        </div>

        <div class="card" id="scannerForm" style="display: none;">
            <form method="POST" action="">
                <div class="form-grid"><div class="form-group">
                        <label for="id_pc"><i class="fas fa-desktop"></i> ID PC</label>
                        <input type="text" id="id_pc" name="id" required readonly class="readonly-field">
                    </div>

                    <div class="form-group">
                        <label for="nama_pc"><i class="fas fa-tag"></i> Nama PC</label>
                        <input type="text" id="nama_pc" name="nama_pc" required readonly class="readonly-field">
                    </div>

                    <div class="form-group">
                        <label for="kondisi_sebelum"><i class="fas fa-history"></i> Kondisi Sebelum</label>
                        <input type="text" id="kondisi_sebelum" name="kondisi_sebelum" required readonly class="readonly-field">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_sebelum"><i class="fas fa-calendar-alt"></i> Tanggal Sebelum</label>
                        <input type="datetime-local" id="tanggal_sebelum" name="tanggal_sebelum" required readonly class="readonly-field">
                    </div>

                    <div class="form-group">
                        <label for="kondisi_pc"><i class="fas fa-clipboard-check"></i> Kondisi Setelah</label>
                        <input type="text" id="kondisi_pc" name="kondisi_pc" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_input"><i class="fas fa-calendar-plus"></i> Tanggal Input</label>
                        <input type="datetime-local" id="tanggal_input" name="tanggal_input" required>
                    </div>

                    <div class="form-group">
                        <label for="lokasi_pc"><i class="fas fa-map-marker-alt"></i> Lokasi PC</label>
                        <input type="text" id="lokasi_pc" name="lokasi_pc" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis_pc"><i class="fas fa-laptop"></i> Jenis PC</label>
                        <input type="text" id="jenis_pc" name="jenis_pc" required>
                    </div>
                </div>

                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fas fa-save"></i> Perbarui Data
                </button>

                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </form>
        </div>
    </div>

    <script>
        let isScanningFile = false;
        const video = document.getElementById("video");
        const result = document.getElementById("result");
        const canvasElement = document.createElement("canvas");
        const canvas = canvasElement.getContext("2d");
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('qrfile');

        // Trigger file input when clicking on the upload area
        fileUploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Initialize camera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then((stream) => {
                video.srcObject = stream;
                video.setAttribute("playsinline", true);
                video.play();
                requestAnimationFrame(tick);
            })
            .catch(err => {
                console.error("Error accessing camera: ", err);
                result.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal mengakses kamera. Silakan periksa izin.';
            });

        function formatDateString(dateString) {
            if (!dateString) return "";
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                console.error("Tanggal tidak valid:", dateString);
                return "";
            }
            return date.toISOString().slice(0, 16);
        }

        function handleQRCode(data) {
            try {
                const dataArray = data.split('|');
                console.log("Data QR Code:", dataArray);

                if (dataArray.length < 6) {
                    throw new Error("Format QR Code tidak valid");
                }

                document.getElementById('id_pc').value = dataArray[0];
                document.getElementById('nama_pc').value = dataArray[1];
                document.getElementById('kondisi_sebelum').value = dataArray[2];
                document.getElementById('tanggal_sebelum').value = formatDateString(dataArray[3]);
                document.getElementById('lokasi_pc').value = dataArray[4];
                document.getElementById('jenis_pc').value = dataArray[5];

                const scannerForm = document.getElementById("scannerForm");
                scannerForm.style.display = "block";
                
                result.innerHTML = '<i class="fas fa-check-circle" style="color: green;"></i> QR Code berhasil dipindai!';
                
                // Set current date and time for tanggal_input
                const now = new Date();
                document.getElementById('tanggal_input').value = now.toISOString().slice(0, 16);
            } catch (error) {
                console.error("Error processing QR code:", error);
                result.innerHTML = '<i class="fas fa-exclamation-triangle" style="color: red;"></i> ' + error.message;
            }
        }

        function tick() {
            if (!isScanningFile && video.readyState === video.HAVE_ENOUGH_DATA) {
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    handleQRCode(code.data);
                }
            }
            if (!isScanningFile) requestAnimationFrame(tick);
        }

        // Handle file upload
        fileInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                isScanningFile = true;
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
                            handleQRCode(code.data);
                        } else {
                            result.innerHTML = '<i class="fas fa-exclamation-triangle" style="color: orange;"></i> QR code tidak valid atau tidak ditemukan.';
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('input[required]:not([readonly])');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    e.preventDefault();
                    isValid = false;
                    field.style.borderColor = 'red';
                    field.style.backgroundColor = '#fff8f8';
                } else {
                    field.style.borderColor = '#e0e0e0';
                    field.style.backgroundColor = '#f8f9fa';
                }
            });

            if (!isValid) {
                result.innerHTML = '<i class="fas fa-exclamation-circle" style="color: red;"></i> Mohon lengkapi semua field yang diperlukan.';
                result.scrollIntoView({ behavior: 'smooth' });
            } else {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<div class="loading"></div>Memproses...';
                submitBtn.disabled = true;
            }
        });

        // Initialize current datetime
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            document.getElementById('tanggal_input').value = now.toISOString().slice(0, 16);
        });
    </script>
</body>
</html>
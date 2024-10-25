<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'data_pc');

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $nama_pc = $_POST['nama_pc'];
    $tanggal_input = $_POST['tanggal_input'];
    $kondisi_pc = $_POST['kondisi_pc'];
    $jenis_pc = $_POST['jenis_pc'];
    $lokasi_pc = $_POST['lokasi_pc'];

    $sql = "INSERT INTO pc_tel (nama_pc, tanggal_input, kondisi_pc, jenis_pc, lokasi_pc) 
            VALUES ('$nama_pc', '$tanggal_input', '$kondisi_pc', '$jenis_pc', '$lokasi_pc')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $qrData = "$last_id|$nama_pc|$kondisi_pc|$tanggal_input|$lokasi_pc|$jenis_pc";
        echo "<script>alert('Data berhasil disimpan!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data PC</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #e8f4f2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
            flex: 1;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: #2a9d8f;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .form-control:focus {
            border-color: #2a9d8f;
            outline: none;
            box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.15);
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232a9d8f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #264653 0%, #1a313b 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(42, 157, 143, 0.2);
        }

        #qrcode {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        #qrcode canvas {
            margin: 0 auto;
            display: block;
        }

        #downloadQR {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            margin: 1rem auto;
            display: block;
            transition: all 0.3s ease;
        }

        #downloadQR:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(42, 157, 143, 0.2);
        }

        footer {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h2 {
                font-size: 1.5rem;
            }

            .card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2><i class="fas fa-desktop"></i> Input Data PC</h2>
    </div>

    <div class="container">
        <div class="card">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama_pc">
                        <i class="fas fa-laptop"></i> Nama PC
                    </label>
                    <input type="text" id="nama_pc" name="nama_pc" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="kondisi_pc">
                        <i class="fas fa-check-circle"></i> Kondisi PC
                    </label>
                    <select id="kondisi_pc" name="kondisi_pc" class="form-control" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup Baik">Cukup Baik</option>
                        <option value="Kurang Baik">Kurang Baik</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tanggal_input">
                        <i class="fas fa-calendar-alt"></i> Tanggal Input
                    </label>
                    <input type="datetime-local" id="tanggal_input" name="tanggal_input" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="lokasi_pc">
                        <i class="fas fa-map-marker-alt"></i> Lokasi PC
                    </label>
                    <input type="text" id="lokasi_pc" name="lokasi_pc" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="jenis_pc">
                        <i class="fas fa-info-circle"></i> Jenis PC
                    </label>
                    <input type="text" id="jenis_pc" name="jenis_pc" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
                
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </form>
        </div>

        <?php if (isset($qrData)): ?>
            <div id="qrcode"></div>
            <button id="downloadQR">
                <i class="fas fa-download"></i> Unduh QR Code
            </button>
        <?php endif; ?>
    </div>

    <footer>
        <p>PT. Tanjung Enim Lestari &copy; 2024 | Sistem Pengelolaan Data PC</p>
    </footer>

    <script>
        $(document).ready(function() {
            <?php if (isset($qrData)): ?>
                $("#qrcode").qrcode({
                    text: "<?php echo $qrData; ?>",
                    width: 128,
                    height: 128
                });

                $('#downloadQR').on('click', function() {
                    html2canvas(document.querySelector('#qrcode')).then(canvas => {
                        let link = document.createElement('a');
                        link.href = canvas.toDataURL('image/png');
                        link.download = 'qr_code_pc.png';
                        link.click();
                    });
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
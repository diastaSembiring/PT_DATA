<?php
include 'koneksi.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $ids_to_delete = isset($_POST['delete_ids']) ? $_POST['delete_ids'] : [];
    if (!empty($ids_to_delete)) {
        $ids = implode(',', array_map('intval', $ids_to_delete));
        $delete_sql = "DELETE FROM pm_tel WHERE id IN ($ids)";
        if ($conn->query($delete_sql) === TRUE) {
            $message = "Data berhasil dihapus.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

$search_term = '';
if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];
}

if (isset($_POST['export_pdf_pm'])) {
    $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : 'data_pm';
    header("Location: export_pdf_pm.php?file_name=" . urlencode($file_name));
    exit();
}

$sql = "SELECT pm.id, tel.nama_pc, pm.kondisi_setelah, pm.kondisi_sebelum, pm.tanggal_sebelum, pm.tanggal_setelah, tel.lokasi_pc, pm.jenis_pc 
        FROM pm_tel pm
        JOIN pc_tel tel ON pm.pc_id = tel.id";

if (!empty($search_term)) {
    $search_term = $conn->real_escape_string($search_term);
    $sql .= " WHERE tel.nama_pc LIKE '%$search_term%' OR pm.kondisi_setelah LIKE '%$search_term%' OR pm.kondisi_sebelum LIKE '%$search_term%'";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Preventive Maintenance (PM)</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #e8f4f2 100%);
            color: #2d3748;
            line-height: 1.6;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            padding: 2rem;
            text-align: center;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem 2rem 1rem;
        }

        .action-panel {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
        }

        .search-form, .export-form {
            display: flex;
            gap: 1rem;
            flex: 1;
            min-width: 300px;
        }

        .input-group {
            position: relative;
            flex: 1;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #2a9d8f;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
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

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            animation: slideIn 0.5s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .table-container {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .checkbox-custom {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .qr-code {
            display: flex;
            justify-content: center;
        }

        .button-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .action-panel {
                flex-direction: column;
            }

            .search-form, .export-form {
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                margin: 0 -1rem;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2><i class="fas fa-tools"></i> Data Preventive Maintenance (PM)</h2>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="action-panel">
            <form method="post" class="search-form">
                <div class="input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search_term" class="form-control" 
                           placeholder="Cari berdasarkan Nama PC, Kondisi..." 
                           value="<?php echo htmlspecialchars($search_term); ?>">
                </div>
                <button type="submit" name="search" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>

            <form method="post" class="export-form">
                <div class="input-group">
                    <i class="fas fa-file-pdf"></i>
                    <input type="text" name="file_name" class="form-control" 
                           placeholder="Nama file PDF" required>
                </div>
                <button type="submit" name="export_pdf_pm" class="btn btn-primary">
                    <i class="fas fa-download"></i> Ekspor PDF
                </button>
            </form>
        </div>

        <form method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?');">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="checkbox-custom"></th>
                            <th>Nama PC</th>
                            <th>Kondisi Setelah</th>
                            <th>Kondisi Sebelum</th>
                            <th>Tanggal Sebelum</th>
                            <th>Tanggal Setelah</th>
                            <th>Lokasi PC</th>
                            <th>Jenis PC</th>
                            <th>QR Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><input type="checkbox" name="delete_ids[]" value="<?php echo $row['id']; ?>" class="checkbox-custom"></td>
                                    <td><?php echo htmlspecialchars($row['nama_pc']); ?></td>
                                    <td><?php echo htmlspecialchars($row['kondisi_setelah']); ?></td>
                                    <td><?php echo htmlspecialchars($row['kondisi_sebelum']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal_sebelum']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal_setelah']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lokasi_pc']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_pc']); ?></td>
                                    <td class="qr-code">
                                        <div id="qrcode-<?php echo $row['id']; ?>"></div>
                                        <script>
                                            $(document).ready(function() {
                                                $("#qrcode-<?php echo $row['id']; ?>").qrcode({
                                                    text: "<?php echo $row['id'] . '|' . $row['nama_pc']; ?>",
                                                    width: 64,
                                                    height: 64
                                                });
                                            });
                                        </script>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center;">
                                    <i class="fas fa-info-circle"></i> Tidak ada data ditemukan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="button-container">
                <button type="submit" name="delete" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Hapus Data Terpilih
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Kembali ke Menu Utama
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByName('delete_ids[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }

        // Highlight baris yang dipilih
        const checkboxes = document.getElementsByName('delete_ids[]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                this.closest('tr').classList.toggle('selected');
            });
        });
    </script>
</body>
</html>
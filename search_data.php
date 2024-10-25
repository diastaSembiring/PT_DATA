<?php
include 'koneksi.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['selected_ids'])) {
        $ids = implode(",", array_map('intval', $_POST['selected_ids']));
        $sql_delete = "DELETE FROM pc_tel WHERE id IN ($ids)";
        $conn->query($sql_delete);
    }
}

$sql = "SELECT * FROM pc_tel WHERE nama_pc LIKE '%$search%' OR kondisi_pc LIKE '%$search%' OR lokasi_pc LIKE '%$search%' OR jenis_pc LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Data PC</title>
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
            padding: 0 1rem;
        }

        .search-container {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .search-input:focus {
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

        .table-container {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
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

        .action-links {
            display: flex;
            gap: 0.5rem;
        }

        .action-links a {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .edit-link {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
        }

        .delete-link {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        }

        .qr-code {
            text-align: center;
        }

        .checkbox-custom {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .button-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                margin: 0 -1rem;
                border-radius: 0;
            }

            .action-links {
                flex-direction: column;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2><i class="fas fa-search"></i> Pencarian Data PC</h2>
    </div>

    <div class="container">
        <div class="search-container fade-in">
            <form method="POST" class="search-form">
                <input type="text" name="search" class="search-input" 
                       placeholder="Masukkan kata kunci pencarian..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
        </div>

        <form method="POST">
            <div class="table-container fade-in">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="checkbox-custom"></th>
                            <th>ID PC</th>
                            <th>Nama PC</th>
                            <th>Kondisi PC</th>
                            <th>Tanggal Input</th>
                            <th>Lokasi PC</th>
                            <th>Jenis PC</th>
                            <th>Aksi</th>
                            <th>QR Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_ids[]" 
                                               value="<?php echo $row['id']; ?>" 
                                               class="checkbox-custom">
                                    </td>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_pc']); ?></td>
                                    <td><?php echo htmlspecialchars($row['kondisi_pc']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal_input']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lokasi_pc']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_pc']); ?></td>
                                    <td class="action-links">
                                        <a href="edit_data.php?id=<?php echo $row['id']; ?>" 
                                           class="edit-link">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="delete_data.php?id=<?php echo $row['id']; ?>" 
                                           class="delete-link" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </a>
                                    </td>
                                    <td class="qr-code">
                                        <div id="qrcode-<?php echo $row['id']; ?>"></div>
                                        <script>
                                            $(document).ready(function() {
                                                $("#qrcode-<?php echo $row['id']; ?>").qrcode({
                                                    text: "<?php echo $row['id'] . '|' . $row['nama_pc'] . '|' . 
                                                        $row['kondisi_pc'] . '|' . $row['tanggal_input'] . '|' . 
                                                        $row['lokasi_pc'] . '|' . $row['jenis_pc']; ?>",
                                                    width: 100,
                                                    height: 100
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

            <div class="button-container fade-in">
                <button type="submit" name="delete_selected" class="btn btn-danger" 
                        onclick="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?');">
                    <i class="fas fa-trash-alt"></i> Hapus yang Dipilih
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Kembali ke Menu Utama
                </a>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#select-all').click(function() {
                $('input[name="selected_ids[]"]').prop('checked', this.checked);
            });

            // Highlight baris yang dipilih
            $('input[name="selected_ids[]"]').change(function() {
                $(this).closest('tr').toggleClass('selected');
            });
        });
    </script>
</body>
</html>
<?php
session_start();
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "data_pc";

// Membuat koneksi
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses Register
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Memeriksa apakah username sudah ada
    $sql_check = "SELECT * FROM users WHERE username='$username'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Username sudah ada, gunakan username lain.');</script>";
    } else {
        // Insert data ke tabel users
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registrasi berhasil!'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Website Pengelolaan Data PC</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Lora', serif;
            background: linear-gradient(135deg, #f0f5f0 0%, #e8f4f2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: linear-gradient(to right, #2a9d8f, #264653);
            padding: 20px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logo {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 80px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1 {
            color: #fff;
            font-size: 1.8em;
            margin: 0 80px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        main {
            flex: 1;
            padding: 40px 20px;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }

        .register-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            margin-top: 20px;
            transition: transform 0.3s ease;
        }

        .register-container:hover {
            transform: translateY(-5px);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #264653;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: #2a9d8f;
            outline: none;
            box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.1);
        }

        .btn {
            width: 100%;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(to right, #2a9d8f, #264653);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(to right, #264653, #1a1a1a);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn i {
            margin-right: 8px;
        }

        footer {
            background: linear-gradient(to right, #2a9d8f, #264653);
            color: #fff;
            text-align: center;
            padding: 15px;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .logo {
                position: static;
                display: block;
                margin: 0 auto 15px;
                transform: none;
            }

            h1 {
                font-size: 1.5em;
                margin: 0;
            }

            main {
                padding: 20px 15px;
            }

            .register-container {
                padding: 30px 20px;
            }
        }

        /* Tambahan animasi */
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

        .register-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <header>
        <img src="logo_tel.png" alt="Logo" class="logo">
        <h1>Register User Baru</h1>
    </header>

    <main>
        <div class="register-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required 
                           placeholder="Masukkan username">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required 
                           placeholder="Masukkan password">
                </div>
                <button type="submit" name="register" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Register
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Kembali ke Menu Utama
                </a>
            </form>
        </div>
    </main>

    <footer>
        <p>PT. Tanjung Enim Lestari &copy; 2024</p>
    </footer>
</body>
</html>
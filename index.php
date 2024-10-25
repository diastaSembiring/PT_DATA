<?php
session_start();
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "data_pc";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Pengelolaan Data PC</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #e8f4f2 100%);
            color: #2d3748;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            padding: 1.5rem;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 80px;
            height: auto;
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: translateY(-50%) scale(1.05);
        }

        h1 {
            color: #fff;
            font-size: 1.8rem;
            margin: 0 80px;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav {
            background: linear-gradient(to right, #238377, #2a9d8f);
            padding: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav ul li a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(42, 157, 143, 0.2);
            background: linear-gradient(135deg, #238377 0%, #1b6359 100%);
        }

        main {
            flex: 1;
            padding: 2rem;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        #catatan-form, #catatan-list {
            background: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        #catatan-form:hover, #catatan-list:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #2d3748;
            margin-bottom: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }

        h2::after, h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(to right, #2a9d8f, #238377);
            border-radius: 2px;
        }

        textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            resize: vertical;
            min-height: 120px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        textarea:focus {
            border-color: #2a9d8f;
            outline: none;
            box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.15);
        }

        button {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(42, 157, 143, 0.2);
            background: linear-gradient(135deg, #238377 0%, #1b6359 100%);
        }

        .delete-btn {
            background: linear-gradient(135deg, #ff4d4d 0%, #e60000 100%);
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #e60000 0%, #cc0000 100%);
        }

        #catatanDisplay {
            list-style: none;
        }

        #catatanDisplay li {
            background: #f8fafc;
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            border-left: 4px solid #2a9d8f;
        }

        #catatanDisplay li:hover {
            transform: translateX(5px);
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .user-info i {
            font-size: 1.2rem;
        }

        footer {
            background: linear-gradient(135deg, #2a9d8f 0%, #238377 100%);
            color: #fff;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
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

        main section {
            animation: fadeIn 0.6s ease-out;
        }

        @media (max-width: 768px) {
            header {
                padding: 1rem;
            }

            .logo {
                position: static;
                display: block;
                margin: 0 auto 1rem;
                transform: none;
            }

            .logo:hover {
                transform: scale(1.05);
            }

            h1 {
                font-size: 1.4rem;
                margin: 0;
            }

            nav ul {
                flex-direction: column;
                gap: 0.5rem;
            }

            nav ul li a {
                justify-content: center;
            }

            .user-info {
                position: static;
                transform: none;
                justify-content: center;
                margin-top: 1rem;
                margin-left: auto;
                margin-right: auto;
                width: fit-content;
            }

            main {
                padding: 1rem;
            }

            #catatan-form, #catatan-list {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="logo_tel.png" alt="Logo" class="logo">
        <h1>Selamat Datang di Website Pengelolaan Data PC</h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span>User: <?php echo $_SESSION['username']; ?></span>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="input_data.php"><i class="fas fa-plus-circle"></i> INPUT DATA</a></li>
            <li><a href="search_data.php"><i class="fas fa-search"></i> PENCARIAN DATA</a></li>
            <li><a href="scanner.php"><i class="fas fa-qrcode"></i> PEMINDAI</a></li>
            <li><a href="pm_data.php"><i class="fas fa-tools"></i> DATA PERAWATAN PENCEGAHAN (PM)</a></li>
            <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <main>
        <section id="catatan-form">
            <h2>Catatan</h2>
            <form onsubmit="simpanCatatan(); return false;">
                <textarea id="catatanText" placeholder="Tulis catatan di sini..."></textarea><br>
                <button type="submit"><i class="fas fa-save"></i> Simpan Catatan</button>
            </form>
        </section>

        <section id="catatan-list">
            <h3>Catatan yang Tersimpan</h3>
            <ul id="catatanDisplay"></ul>
        </section>
    </main>

    <footer>
        <p>PT. Tanjung Enim Lestari &copy; 2024 | Sistem Pengelolaan Data PC</p>
    </footer>

    <script>
        function simpanCatatan() {
            var catatanText = document.getElementById("catatanText").value;
            if (catatanText) {
                let catatanArray = JSON.parse(localStorage.getItem("catatanList")) || [];
                catatanArray.push(catatanText);
                localStorage.setItem("catatanList", JSON.stringify(catatanArray));
                tampilkanCatatan();
                document.getElementById("catatanText").value = "";
            } else {
                alert("Catatan tidak boleh kosong!");
            }
        }

        function tampilkanCatatan() {
            let catatanArray = JSON.parse(localStorage.getItem("catatanList")) || [];
            var catatanDisplay = document.getElementById("catatanDisplay");
            catatanDisplay.innerHTML = "";
            catatanArray.forEach(function(catatan, index) {
                var li = document.createElement("li");
                li.innerHTML = '<span>' + catatan + '</span> <button class="delete-btn" onclick="hapusCatatan(' + index + ')"><i class="fas fa-trash-alt"></i> Hapus</button>';
                catatanDisplay.appendChild(li);
            });
        }

        function hapusCatatan(index) {
            let catatanArray = JSON.parse(localStorage.getItem("catatanList"));
            catatanArray.splice(index, 1);
            localStorage.setItem("catatanList", JSON.stringify(catatanArray));
            tampilkanCatatan();
        }

        document.addEventListener("DOMContentLoaded", function() {
            tampilkanCatatan();
        });
    </script>
</body>
</html>
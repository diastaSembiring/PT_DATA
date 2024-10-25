<?php
session_start();
include 'koneksi.php';

if(isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if($row = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Website Pengelolaan Data PC</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f5f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        img {
            width: 100px;
            margin-bottom: 20px;
        }
        h2 {
            color: #2a9d8f;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #264653;
            font-weight: 600;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        .btn-login {
            background-color: #2a9d8f;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 30px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #21867a;
        }
        .register-link {
            margin-top: 20px;
            font-size: 14px;
        }
        .register-link a {
            color: #2a9d8f;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .show-password {
            display: flex;
            align-items: center;
        }
        .show-password input {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo_tel.png" alt="Logo">
        <h2>Login</h2>
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" id="password-field" required>
                <div class="show-password">
                    <input type="checkbox" id="show-password" onclick="togglePassword()">
                    <label for="show-password">Tampilkan Password</label>
                </div>
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
        <div class="register-link">
            
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password-field");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>

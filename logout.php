<?php
session_start();

function doLogout() {
    // Clear all session data
    $_SESSION = array();
    
    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Check if logout action is confirmed
if (isset($_POST['confirm_logout'])) {
    doLogout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #2a9d8f;
            --primary-light: #3fb5a6;
            --primary-dark: #238579;
            --secondary-color: #264653;
            --accent-color: #e9c46a;
            --danger-color: #e76f51;
            --danger-dark: #d65f41;
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .logout-container {
            background: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: var(--danger-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .icon-wrapper i {
            font-size: 2.5rem;
            color: white;
        }

        h2 {
            color: var(--text-color);
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: var(--danger-dark);
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #e0e0e0;
            color: var(--text-color);
        }

        .btn-cancel:hover {
            background: #d0d0d0;
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .logout-container {
                padding: 2rem;
            }

            .btn-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="icon-wrapper">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h2>Konfirmasi Logout</h2>
        <p>Apakah Anda yakin ingin keluar dari sistem? Semua sesi Anda akan berakhir.</p>
        
        <div class="btn-group">
            <form method="POST" style="width: 100%;">
                <button type="submit" name="confirm_logout" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
            <a href="index.php" class="btn btn-cancel">
                <i class="fas fa-times"></i>
                Batal
            </a>
        </div>
    </div>
</body>
</html>
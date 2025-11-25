<?php
// Sertakan file config.php untuk koneksi database
require_once 'config.php';

// Inisialisasi variabel untuk pesan error/sukses
$error = '';
$success = '';

// Periksa apakah form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($email) || empty($username) || empty($password)) {
        $error = 'Semua field harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        // Periksa apakah username atau email sudah ada
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = 'Username atau email sudah terdaftar.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert data ke database
            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $email);
            
            if ($stmt->execute()) {
                $success = 'Akun berhasil dibuat! Silakan <a href="login.php">masuk</a>.';
            } else {
                $error = 'Terjadi kesalahan saat mendaftar. Coba lagi.';
            }
        }
        $stmt->close();
    }
}

// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar ALIBRARY</title>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan&family=Poppins&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="loginn.css" />
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <!-- SVG curve background shape -->
            <svg class="curve-bg" viewBox="0 0 600 800" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <defs>
                    <linearGradient id="grad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#1FA2FF" />
                        <stop offset="100%" stop-color="#004C70" />
                    </linearGradient>
                </defs>
                <path fill="url(#grad)" d="
                    M 0 0 
                    L 350 0 
                    C 650 700, 500 1800, 1100 800 
                    L 0 800 
                    Z"/>
            </svg>

            <div class="content-left">
                <div class="logo-wrapper">
                    <img src="images/logobuku.png" alt="ALIBRARY Logo" />
                </div>
                <h1 class="brand-name">ALIBRARY</h1>
            </div>
        </div>
        <div class="right-panel">
            <form class="login-form" action="register.php" method="POST">
                <h2 class="title">Daftar</h2>
                <p class="subtitle">Silahkan buat akun anda</p>
                
                <!-- Tampilkan pesan error jika ada -->
                <?php if (!empty($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                
                <!-- Tampilkan pesan sukses jika ada -->
                <?php if (!empty($success)): ?>
                    <p style="color: green;"><?php echo $success; ?></p>
                <?php endif; ?>
                
                <label for="email">email</label>
                <input type="text" id="email" name="email" placeholder="Masukan email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" />
                
                <label for="username">username</label>
                <input type="text" id="username" name="username" placeholder="Masukan username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukan password (min. 6 karakter)" required />
                
                <button type="submit" class="btn-submit">DAFTAR</button>
            </form>
            <p class="register-text">Sudah buat akun? <a href="login.php" class="register-link">Masuk</a>!</p>
        </div>
    </div>
</body>
</html>

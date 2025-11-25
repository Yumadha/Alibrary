<?php
// Mulai session untuk mengelola status login
session_start();

// Sertakan file config.php untuk koneksi database
require_once 'config.php';

// Inisialisasi variabel untuk pesan error
$error = '';

// Periksa apakah form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi.';
    } else {
        // Query untuk mendapatkan data pengguna berdasarkan username
        $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Inisialisasi variabel untuk bind_result (mencegah error linter)
            $user_id = null;
            $hashed_password = null;
            
            // Bind hasil query
            $stmt->bind_result($user_id, $hashed_password, $role);
            $stmt->fetch();
            
            // Verifikasi password (pastikan hashed_password tidak null)
            if ($hashed_password && password_verify($password, $hashed_password)) {
                // Login berhasil: set session
                $_SESSION['isLogin'] = true;
                $_SESSION['user_id'] = $user_id;  // Opsional: simpan user_id untuk akses data pengguna
                $_SESSION['username'] = $username;  // Opsional: simpan username
                $_SESSION['role'] = $role;  // Tambah ini setelah login berhasil
                
                // Redirect ke menu.php
                header('Location: menu.php');
                exit;  // Pastikan script berhenti setelah redirect
            } else {
                $error = 'Password salah.';
            }
        } else {
            $error = 'Username tidak ditemukan.';
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
    <title>Login ALIBRARY</title>
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
            <form class="login-form" action="login.php" method="POST">
                <h2 class="title">Masuk</h2>
                <p class="subtitle">Silahkan masuk ke akun anda</p>
                
                <!-- Tampilkan pesan error jika ada -->
                <?php if (!empty($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                
                <label for="username">username</label>
                <input type="text" id="username" name="username" placeholder="Masukan username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukan password" />
                
                <button type="submit" class="btn-submit">MASUK</button>
            </form>
            <p class="register-text">Belum punya akun? <a href="register.php" class="register-link">Daftar</a>!</p>
        </div>
    </div>
</body>
</html>
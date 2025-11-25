<?php
// Mulai session untuk mengecek status login
session_start();

// Jika belum login, redirect ke login
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] !== true) {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALIBRARY - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan&family=Poppins&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="menuu.css" />
</head>
<body>
    <nav class="navbar">
        <div class="logo">ALIBRARY</div>
        <div class="nav-links">
            <?php if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true): ?>
                <!-- Jika sudah login, tampilkan nama user dan logout -->
                Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>! 
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- Jika belum login, tampilkan link masuk/daftar -->
                <a href="login.php">Masuk atau Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <h1 class="main-title">Perpustakaan Digital untuk semua kalangan!</h1>
        <form class="search-form" action="bookpage.php" method="GET">
            <div class="form-group">
                <label for="search-column">kolom pencarian</label>
                <select id="search-column" name="search-column" required>
                    <option value="" disabled selected>Pilih kolom pencarian</option>
                    <option value="judul">Judul</option>
                    <option value="penulis">Penulis</option>
                    <option value="tahun">Tahun Terbit</option>
                    <option value="kategori">Kategori</option>
                </select>
            </div>

            <div class="form-group keyword-group">
                <label for="keyword">kata kunci</label>
                <input type="text" id="keyword" name="keyword" placeholder="Masukan kata kunci" />
                <button type="submit" class="btn-search" aria-label="Cari"></button>
            </div>
        </form>
    </main>

    <script src="bookpage.js"></script>
</body>
</html>
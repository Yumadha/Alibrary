<?php

session_start();
// Ambil parameter dari URL
$searchColumn = isset($_GET['search-column']) ? $_GET['search-column'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALIBRARY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="bookpagestyle.css">

</head>
<body>
<!-- Navbar -->
<nav class="navbar">
        <!-- Kiri: Logo -->
        <div class="navbar-left">
            <h1 class="logo">ALIBRARY</h1>
        </div>
        
        <!-- Tengah: Search -->
        <div class="navbar-center">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Cari buku.." id="searchInput" value="<?php echo htmlspecialchars($keyword); ?>">
                <button class="search-btn">
                    <i class="bi bi-search"></i>
                </button>
                <button class="clear-btn" onclick="clearSearch()">✕</button>
            </div>
        </div>
        
        <!-- Kanan: Dropdown User -->
        <div class="navbar-right">
            <?php if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true): ?>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-2 me-2"></i>
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a class="dropdown-item" href="manage_books.php">CRUD / Tambah</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="filter-section">
                <h2 class="filter-title">Filter</h2>
                
                <div class="filter-group">
                    <h3 class="filter-heading">Kategori</h3>
                        <div class="filter-item">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="dropdownBuku" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-book me-2"></i> Buku
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="dropdownBuku">
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="radio" name="category" value="" checked onchange="filterBooks()" class="me-2"> Semua
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="radio" name="category" value="eBook" onchange="filterBooks()" class="me-2"> eBook
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="radio" name="category" value="Novel" onchange="filterBooks()" class="me-2"> Novel
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="radio" name="category" value="Teknologi" onchange="filterBooks()" class="me-2"> Teknologi
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="radio" name="category" value="Akademik" onchange="filterBooks()" class="me-2">Akademik
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="radio" name="category" value="Biografi" onchange="filterBooks()" class="me-2">Biografi
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                <div class="filter-group">
                    <h3 class="filter-heading">Stok</h3>
                    <div class="toggle-container">
                        <label class="toggle-switch">
                            <input type="checkbox" id="availableOnly" onchange="filterBooks()">
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label">hanya tersedia</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="content">
            <div id="loadingIndicator" class="loading">Memuat data buku...</div>
            <div class="books-grid" id="booksGrid">
                <!-- Book cards akan di-generate otomatis dari database -->
            </div>
            <div id="noResults" class="no-results" style="display: none;">
                Tidak ada buku yang ditemukan.
            </div>
        </main>
    </div>

    <!-- Modal Detail Buku -->
    <div id="bookModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeBookDetail()">&times;</span>
            <div class="modal-body">
                <div class="modal-cover">
                    <img id="modalCoverImg" src="" alt="Book Cover">
                </div>
                <div class="modal-info">
                    <h2 id="modalTitle">Judul Buku</h2>
                    <p class="modal-author">Penulis: <span id="modalAuthor"></span></p>
                    <p class="modal-category">Kategori: <span id="modalCategory"></span></p>
                    <p class="modal-status">Status: <span id="modalStatus"></span></p>
                    <div class="modal-description">
                        <h3>Deskripsi</h3>
                        <p id="modalDescription"></p>
                    </div>
                    <div class="modal-actions">
                        <button class="btn-primary" id="borrowBtn" onclick="borrowBook()">Pinjam Buku</button>
                        <button class="btn-secondary" onclick="closeBookDetail()">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="bookpage.js"></script>
</body>
</html>

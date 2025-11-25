<?php
// Mulai session
session_start();

// Cek login dan role admin
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Sertakan config
require_once 'config.php';

// Ambil semua buku dari database
$query = "SELECT * FROM books ORDER BY id DESC";
$result = $conn->query($query);
$books = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Buku - Alibrary</title>
    <link rel="stylesheet" href="manage_books.css" />
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kelola Buku</h1>
            <button class="btn-add" onclick="openAddModal()">
                <span>+</span> Tambah Buku Baru
            </button>
        </div>

        <!-- Tabel Buku -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cover</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">Belum ada buku</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['id']); ?></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($book['cover_image'] ?: 'default-cover.jpg'); ?>" 
                                         alt="Cover" class="mini-cover" />
                                </td>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['category']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $book['status'] === 'Tersedia' ? 'available' : 'borrowed'; ?>">
                                        <?php echo htmlspecialchars($book['status']); ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <button class="btn-view" onclick='viewBook(<?php echo json_encode($book); ?>)'>
                                        👁️
                                    </button>
                                    <button class="btn-edit" onclick='openEditModal(<?php echo json_encode($book); ?>)'>
                                        ✏️
                                    </button>
                                    <button class="btn-delete" onclick="confirmDelete(<?php echo $book['id']; ?>, '<?php echo addslashes($book['title']); ?>')">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <p class="back-link">
            <a href="bookpage.php">← Kembali ke Laman Buku</a>
        </p>
    </div>

    <!-- Modal Tambah/Edit Buku -->
    <div id="bookModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Tambah Buku</h2>
            
            <form id="bookForm" method="POST" action="book_action.php">
                <input type="hidden" id="action" name="action" value="add" />
                <input type="hidden" id="bookId" name="book_id" value="" />
                
                <div class="form-group">
                    <label for="judul">Judul Buku</label>
                    <input type="text" id="judul" name="judul" placeholder="Masukan judul buku" required />
                </div>

                <div class="form-group">
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" placeholder="Masukan nama penulis" required />
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" placeholder="Masukan kategori" required />
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Masukan deskripsi buku" required></textarea>
                </div>

                <div class="form-group">
                    <label for="cover_image">URL Cover Gambar</label>
                    <input type="url" id="cover_image" name="cover_image" placeholder="Masukan URL gambar" />
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Dipinjam">Dipinjam</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-submit" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lihat Detail Buku -->
    <div id="viewModal" class="modal">
        <div class="modal-content view-modal">
            <span class="close" onclick="closeViewModal()">&times;</span>
            <div class="view-content">
                <div class="view-cover">
                    <img id="viewCover" src="" alt="Cover" />
                </div>
                <div class="view-details">
                    <h2 id="viewTitle"></h2>
                    <p><strong>Penulis:</strong> <span id="viewAuthor"></span></p>
                    <p><strong>Kategori:</strong> <span id="viewCategory"></span></p>
                    <p><strong>Status:</strong> <span id="viewStatus" class="status-badge"></span></p>
                    <p><strong>Deskripsi:</strong></p>
                    <p id="viewDescription" class="description-text"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="modal">
        <div class="modal-content delete-modal">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <div class="delete-content">
                <div class="delete-icon">⚠️</div>
                <h2>Konfirmasi Hapus</h2>
                <p>Apakah Anda yakin ingin menghapus buku:</p>
                <p class="book-title-delete" id="deleteBookTitle"></p>
                <p style="color: #666; font-size: 0.9em;">Tindakan ini tidak dapat dibatalkan.</p>
                
                <form id="deleteForm" method="POST" action="book_action.php">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" id="deleteBookId" name="book_id" value="" />
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
                        <button type="submit" class="btn-delete-confirm">Hapus Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="manage_books.js"></script>
</body>
</html>
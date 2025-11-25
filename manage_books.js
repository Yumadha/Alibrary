// Fungsi untuk membuka modal tambah buku
function openAddModal() {
    const modal = document.getElementById('bookModal');
    const form = document.getElementById('bookForm');
    
    // Reset form
    form.reset();
    document.getElementById('action').value = 'add';
    document.getElementById('bookId').value = '';
    document.getElementById('modalTitle').textContent = 'Tambah Buku';
    document.getElementById('submitBtn').textContent = 'Tambah Buku';
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk membuka modal edit buku
function openEditModal(book) {
    const modal = document.getElementById('bookModal');
    
    // Isi form dengan data buku
    document.getElementById('action').value = 'edit';
    document.getElementById('bookId').value = book.id;
    document.getElementById('judul').value = book.title;
    document.getElementById('penulis').value = book.author;
    document.getElementById('kategori').value = book.category;
    document.getElementById('deskripsi').value = book.description;
    document.getElementById('cover_image').value = book.cover_image || '';
    document.getElementById('status').value = book.status;
    
    document.getElementById('modalTitle').textContent = 'Edit Buku';
    document.getElementById('submitBtn').textContent = 'Update Buku';
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal form
function closeModal() {
    const modal = document.getElementById('bookModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fungsi untuk melihat detail buku
function viewBook(book) {
    const modal = document.getElementById('viewModal');
    
    document.getElementById('viewTitle').textContent = book.title;
    document.getElementById('viewAuthor').textContent = book.author;
    document.getElementById('viewCategory').textContent = book.category;
    document.getElementById('viewDescription').textContent = book.description;
    document.getElementById('viewCover').src = book.cover_image || 'default-cover.jpg';
    
    const statusBadge = document.getElementById('viewStatus');
    statusBadge.textContent = book.status;
    statusBadge.className = 'status-badge ' + (book.status === 'Tersedia' ? 'available' : 'borrowed');
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal view
function closeViewModal() {
    const modal = document.getElementById('viewModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fungsi untuk konfirmasi hapus
function confirmDelete(bookId, bookTitle) {
    const modal = document.getElementById('deleteModal');
    
    document.getElementById('deleteBookId').value = bookId;
    document.getElementById('deleteBookTitle').textContent = bookTitle;
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal delete
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}



// Tampilkan pesan notifikasi jika ada
window.onload = function() {
    // Cek jika ada pesan dari session (akan ditampilkan via PHP)
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const type = urlParams.get('type');
    
    if (message) {
        showNotification(message, type);
        // Hapus parameter dari URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Tampilkan dengan animasi
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    // Hilangkan setelah 3 detik
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
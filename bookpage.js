// Variable untuk menyimpan semua data buku
let allBooks = [];
let currentBookId = null;

// Load buku saat halaman pertama kali dibuka
document.addEventListener('DOMContentLoaded', function() {
    loadBooks();
});

const input = document.getElementById("searchInput");
const clearBtn = document.querySelector(".clear-btn");

input.addEventListener("input", () => {
    if (input.value.trim() !== "") {
        clearBtn.style.display = "block";  
    } else {
        clearBtn.style.display = "none";   
    }
});

function clearSearch() {
    input.value = "";
    input.dispatchEvent(new Event("input")); // <- ini yang bikin X langsung hilang
    input.focus();
}



// Fungsi untuk load buku dari database
function loadBooks() {
    const category = document.querySelector('input[name="category"]:checked')?.value || '';
    const availableOnly = document.getElementById('availableOnly').checked;
    const search = document.getElementById('searchInput').value;
    
    // Tampilkan loading
    document.getElementById('loadingIndicator').style.display = 'block';
    document.getElementById('booksGrid').style.display = 'none';
    document.getElementById('noResults').style.display = 'none';
    
    // Build URL dengan parameter
    let url = 'get_books.php?';
    if (category) url += `category=${category}&`;
    if (availableOnly) url += 'available=true&';
    if (search) url += `search=${encodeURIComponent(search)}&`;
    
    // Fetch data dari server
    fetch(url)
        .then(response => response.json())
        .then(books => {
            allBooks = books;
            displayBooks(books);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingIndicator').innerHTML = 'Gagal memuat data. Pastikan server PHP berjalan.';
        });
}

// Fungsi untuk menampilkan buku di grid
function displayBooks(books) {
    const booksGrid = document.getElementById('booksGrid');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const noResults = document.getElementById('noResults');
    
    loadingIndicator.style.display = 'none';
    
    if (books.length === 0) {
        booksGrid.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }
    
    booksGrid.style.display = 'grid';
    noResults.style.display = 'none';
    booksGrid.innerHTML = '';
    
    books.forEach(book => {
        const bookCard = createBookCard(book);
        booksGrid.appendChild(bookCard);
    });
}

// Fungsi untuk membuat card buku
function createBookCard(book) {
    const card = document.createElement('div');
    card.className = 'book-card';
    card.onclick = () => openBookDetail(book);
    
    const statusClass = book.status === 'Tersedia' ? 'available' : 'unavailable';
    
    card.innerHTML = `
        <div class="book-cover">
            <img src="${book.cover_image}" alt="${book.title}" onerror="this.src='https://via.placeholder.com/200x280/CCCCCC/666666?text=No+Image'">
        </div>
        <div class="book-info">
            <h3 class="book-title">${book.title}</h3>
            <p class="book-author">${book.author}</p>
            <div class="book-meta">
                <span class="book-category">${book.category}</span>
                <span class="book-status ${statusClass}">${book.status}</span>
            </div>
        </div>
    `;
    
    return card;
}

// Fungsi untuk membuka detail buku
function openBookDetail(book) {
    currentBookId = book.id;
    const modal = document.getElementById('bookModal');
    
    document.getElementById('modalTitle').textContent = book.title;
    document.getElementById('modalAuthor').textContent = book.author;
    document.getElementById('modalCategory').textContent = book.category;
    document.getElementById('modalStatus').textContent = book.status;
    document.getElementById('modalDescription').textContent = book.description;
    document.getElementById('modalCoverImg').src = book.cover_image;
    
    // Disable tombol pinjam jika buku dipinjam
    const borrowBtn = document.getElementById('borrowBtn');
    if (book.status === 'Dipinjam') {
        borrowBtn.disabled = true;
        borrowBtn.textContent = 'Tidak Tersedia';
        borrowBtn.style.backgroundColor = '#ccc';
    } else {
        borrowBtn.disabled = false;
        borrowBtn.textContent = 'Pinjam Buku';
        borrowBtn.style.backgroundColor = '#333';
    }
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal
function closeBookDetail() {
    const modal = document.getElementById('bookModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    currentBookId = null;
}

// Fungsi untuk meminjam buku
function borrowBook() {
    if (!currentBookId) return;
    
    const title = document.getElementById('modalTitle').textContent;
    
    // Kirim request ke server untuk update status
    fetch('borrow_book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `book_id=${currentBookId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Berhasil meminjam buku: "${title}"\n\nSilakan ambil buku di meja perpustakaan.`);
            closeBookDetail();
            loadBooks(); // Reload data
        } else {
            alert('Gagal meminjam buku: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat meminjam buku.');
    });
}

// Auto search kalo ada parameter dari URL
window.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const keyword = urlParams.get('keyword');
    const searchColumn = urlParams.get('search-column');
    
    if (keyword && searchColumn) {
        // Set value input (udah di-set dari PHP tapi buat jaga-jaga)
        document.getElementById('searchInput').value = keyword;
        
        // Langsung jalankan search
        searchBooks();
    }
});

// Fungsi untuk filter buku
function filterBooks() {
    loadBooks();
}

// Fungsi untuk search buku
function searchBooks() {
    loadBooks();
}


// Fungsi untuk clear search
function clearSearch() {
    document.getElementById('searchInput').value = '';
    loadBooks();
}

// Close modal jika klik di luar modal content
window.onclick = function(event) {
    const modal = document.getElementById('bookModal');
    if (event.target == modal) {
        closeBookDetail();
    }
}

// Close modal dengan tombol ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeBookDetail();
    }
});

// Search ketika tekan Enter
document.getElementById('searchInput').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        searchBooks();
    }
});


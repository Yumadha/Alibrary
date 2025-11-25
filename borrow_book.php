<?php
header('Content-Type: application/json');
include 'config.php';

// Mulai session untuk cek login
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login untuk meminjam buku']);
    exit;
}

$user_id = $_SESSION['user_id'];  // Ambil user_id dari session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
    
    if ($book_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID buku tidak valid']);
        exit;
    }
    
    // Cek apakah buku tersedia (gunakan prepared statement)
    $check_stmt = $conn->prepare("SELECT status FROM books WHERE id = ?");
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Buku tidak ditemukan']);
        $check_stmt->close();
        exit;
    }
    
    $book = $check_result->fetch_assoc();
    $check_stmt->close();
    
    if ($book['status'] === 'Dipinjam') {
        echo json_encode(['success' => false, 'message' => 'Buku sedang dipinjam']);
        exit;
    }
    
    // Update status, borrowed_by, dan borrow_date
    $update_stmt = $conn->prepare("UPDATE books SET status = 'Dipinjam', borrowed_by = ?, borrow_date = NOW() WHERE id = ?");
    $update_stmt->bind_param("ii", $user_id, $book_id);
    
    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Buku berhasil dipinjam']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal meminjam buku']);
    }
    
    $update_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
}

$conn->close();
?>
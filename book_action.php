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

// Cek apakah request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_books.php');
    exit;
}

$action = $_POST['action'] ?? '';

// TAMBAH BUKU
if ($action === 'add') {
    $title = trim($_POST['judul']);
    $author = trim($_POST['penulis']);
    $category = trim($_POST['kategori']);
    $description = trim($_POST['deskripsi']);
    $cover_image = trim($_POST['cover_image'] ?? '');
    $status = trim($_POST['status'] ?? 'Tersedia');

    // Validasi input
    if (empty($title) || empty($author) || empty($category) || empty($description)) {
        $_SESSION['message'] = 'Semua field wajib diisi!';
        $_SESSION['message_type'] = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO books (title, author, category, cover_image, description, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $title, $author, $category, $cover_image, $description, $status);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Buku berhasil ditambahkan!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal menambahkan buku. Coba lagi.';
            $_SESSION['message_type'] = 'error';
        }
        $stmt->close();
    }
}

// EDIT BUKU
elseif ($action === 'edit') {
    $book_id = intval($_POST['book_id']);
    $title = trim($_POST['judul']);
    $author = trim($_POST['penulis']);
    $category = trim($_POST['kategori']);
    $description = trim($_POST['deskripsi']);
    $cover_image = trim($_POST['cover_image'] ?? '');
    $status = trim($_POST['status'] ?? 'Tersedia');

    // Validasi input
    if (empty($title) || empty($author) || empty($category) || empty($description)) {
        $_SESSION['message'] = 'Semua field wajib diisi!';
        $_SESSION['message_type'] = 'error';
    } else {
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, category=?, cover_image=?, description=?, status=? WHERE id=?");
        $stmt->bind_param("ssssssi", $title, $author, $category, $cover_image, $description, $status, $book_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Buku berhasil diupdate!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal mengupdate buku. Coba lagi.';
            $_SESSION['message_type'] = 'error';
        }
        $stmt->close();
    }
}

// HAPUS BUKU
elseif ($action === 'delete') {
    $book_id = intval($_POST['book_id']);
    
    $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
    $stmt->bind_param("i", $book_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Buku berhasil dihapus!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Gagal menghapus buku. Coba lagi.';
        $_SESSION['message_type'] = 'error';
    }
    $stmt->close();
}

$conn->close();
header('Location: manage_books.php');
exit;
?>
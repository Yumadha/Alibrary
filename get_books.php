<?php
header('Content-Type: application/json');
include 'config.php';

// Ambil parameter filter dari request
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$availableOnly = isset($_GET['available']) && $_GET['available'] == 'true';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build query
$query = "SELECT * FROM books WHERE 1=1";

// Filter kategori
if (!empty($category)) {
    $query .= " AND category = '$category'";
}

// Filter hanya yang tersedia
if ($availableOnly) {
    $query .= " AND status = 'Tersedia'";
}

// Filter pencarian
if (!empty($search)) {
    $query .= " AND (title LIKE '%$search%' OR author LIKE '%$search%')";
}

$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);

$books = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'author' => $row['author'],
            'category' => $row['category'],
            'cover_image' => $row['cover_image'],
            'description' => $row['description'],
            'status' => $row['status']
        );
    }
}

echo json_encode($books);

mysqli_close($conn);
?>
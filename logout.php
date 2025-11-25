<?php
// Mulai session
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke beranda atau login
header('Location: menu.php');  // Atau ganti ke 'login.php' jika ingin langsung ke login
exit;
?>
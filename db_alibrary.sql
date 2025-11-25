-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2025 at 06:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_alibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `cover_image` varchar(255) DEFAULT 'default-cover.jpg',
  `description` text DEFAULT NULL,
  `status` enum('Tersedia','Dipinjam') DEFAULT 'Tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `borrowed_by` int(11) DEFAULT NULL,
  `borrow_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `cover_image`, `description`, `status`, `created_at`, `borrowed_by`, `borrow_date`) VALUES
(13, 'Tutorial Masakan Padang', 'Mama Rita', 'Novel', 'https://res.cloudinary.com/ddobabnmq/image/upload/v1763870937/masakan_padang_dgxmwl.png', 'Resep masakan padang bikin yang makan nagih dan minta lagi sampai mati.', 'Tersedia', '2025-11-21 19:22:09', 6, '2025-11-23 04:24:12'),
(15, 'Hujan (Indonesia Edition)', 'Tere Liye', 'Novel', 'https://res.cloudinary.com/ddobabnmq/image/upload/v1763872093/hujan_mw1jmt.webp', 'Novel Hujan karya Tere Liye bercerita tentang Lail, seorang gadis yang kehilangan orang tua akibat bencana alam dahsyat dan terpaksa tinggal di panti asuhan. Di sana, ia bertemu Esok, yang menjadi teman dan sosok penting dalam hidupnya.', 'Tersedia', '2025-11-23 04:28:46', 2, '2025-11-23 04:29:27'),
(16, 'Ambasing', 'Amba', 'novel', 'https://res.cloudinary.com/ddobabnmq/image/upload/v1763870868/samples/man-portrait.jpg', 'amabamababamabaa', 'Tersedia', '2025-11-23 04:30:41', NULL, NULL),
(17, 'Laut Bercerita', 'Leila S. Chudori', 'Novel', 'https://res.cloudinary.com/ddobabnmq/image/upload/v1763872319/laut_bxuv6e.jpg', 'Novel Laut Bercerita karya Leila S. Chudori menceritakan tentang kisah para aktivis mahasiswa pada masa Orde Baru, yang diculik, disiksa, dan dihilangkan paksa.', 'Tersedia', '2025-11-23 04:32:16', NULL, NULL),
(18, 'SJAHRIR PERAN BESAR BUNG KECIL', 'Majalah Berita Mingguan Tempo', 'Biografi', 'https://res.cloudinary.com/ddobabnmq/image/upload/v1763873016/bungkecil_uwvkal.jpg', 'Sutan Sjahrir adalah seorang intelektual, perintis, dan revolusioner kemerdekaan Indonesia. Mendesak Bung Karno dan Bung Hatta untuk memproklamasikan kemerdekaan Indonesia, Sutan Sjahrir sendiri justru absen dari peristiwa besar itu. Dia memilih jalan elegan untuk menghalau penjajah: jalur diplomasi—cara yang ditentang tokoh lain yang lebih radikal. Ideologinya, antifasis, dan antimiliter, dikritik hanya untuk kaum terdidik. Ia dituduh elitis. Sjahrir mendirikan Partai Sosialis Indonesia pada tahun 1948. Sejatinya, Sutan Sjahrir juga turun ke gubuk-gubuk, berkeliling Tanah Air menghimpun kader Partai Sosialis Indonesia. Sejarah telah menyingkirkan peran besar Bung Kecil—begitu Sjahrir biasa disebut. Ia meninggal dalam pengasingan sebagai tawanan politik dan dimakamkan di TMP Kalibata, Jakarta. Sjahrir adalah revolusioner yang gugur dalam kesepian.', 'Dipinjam', '2025-11-23 04:44:18', 2, '2025-11-23 04:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` varchar(10) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`, `role`) VALUES
(1, 'testing', '$2y$10$xx715IjEtaYyk6XR4DAoMuAYwNoIoq1HVflQ7hv0PcdPUiAu.emwu', 'testing@gmail.com', '2025-11-22 01:11:34', 'user'),
(2, 'kulo', '$2y$10$XmBvF1QWwExqKG/3agbrwudjT9zhE67tjaqeVnEXfw7xQlNxMrXwa', 'anjing@gmail.com', '2025-11-22 01:21:19', 'admin'),
(3, 'cobageming', '$2y$10$YAGKZW22lfL4XoCxspZiu.q0iY07s7c5pRUx4pbu5sm4TIiqdPVG2', 'cobacoba@gmail.com', '2025-11-22 02:55:28', 'admin'),
(4, 'AWOKAWOK', '$2y$10$U/6YW3Oje4jFUGYC1KqIDuo42yDa3GS6YjQL705DyzIsEQtkuQTf2', 'aowkdaowko@gmail.com', '2025-11-23 10:06:10', 'user'),
(5, 'Kudanil', '$2y$10$2qUmlcf7OiXmL5DhSz0RkuXg8yM26.uwmHNjQ22OxIj1VZpzf1qkC', 'kudanil@gmail.com', '2025-11-23 10:47:14', 'user'),
(6, 'BabiKuda', '$2y$10$uGfDmSaWozQo61PTnm2nm.vh1MIU8xYbs2BJjFkppvAohwghpXT.2', 'asjdnaisf@gmail.com', '2025-11-23 11:23:04', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_borrowed_by` (`borrowed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_borrowed_by` FOREIGN KEY (`borrowed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

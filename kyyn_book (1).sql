-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jan 20, 2026 at 09:30 PM
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
-- Database: `kyyn_book`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul_buku` varchar(100) DEFAULT NULL,
  `pengarang` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `gambar` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul_buku`, `pengarang`, `penerbit`, `tahun_terbit`, `stok`, `gambar`) VALUES
(1, 'Belajar Otodidak MySQL', 'Budi Raharjo', 'Informatika', '2022', 100, '07012026165826Belajar Otodidak MySQL.jpg'),
(2, 'Filosofi Teras', 'Henry Manampiring', 'Kompas', '2018', 80, '07012026170012Filosofi Teras.jpeg'),
(3, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 50, '07012026170112Clean Code.jpg'),
(4, 'Algoritma dan Pemrograman', 'Rinaldi Munir', 'Informatika', '2011', 110, '07012026170209Algoritma dan Pemrograman.jpeg'),
(5, 'Jago Web Design HTML CSS', 'Andre Pratama', 'DuniaKom', '2023', 30, '07012026170502Jago Web Design HTML CSS.jfif'),
(6, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 150, '07012026170620Laskar Pelangi.jpg'),
(7, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', '1980', 300, '07012026170713Bumi Manusia.jpg'),
(8, 'Dilan: Dia adalah Dilanku Tahun 1990', 'Pidi Baiq', 'Pastel Books', '2014', 200, '07012026170822Dia adalah Dilanku Tahun 1990.jpg'),
(9, 'Atomic Habits', 'James Clear', 'Penguin Random House', '2018', 50, '20012026164326_Atomic Habitss.webp'),
(10, 'Filosofi Teras', 'Henry Manampiring', 'Kompas', '2019', 45, '20012026165046_OIP (1).webp'),
(11, 'Laut Bercerita', 'Leila S. Chudori', 'KPG', '2017', 30, '20012026165018_Laut Bercerita.webp'),
(12, 'The Psychology of Money', 'Morgan Housel', 'Harriman House', '2020', 40, '20012026164944_The Psychology of Money.webp'),
(13, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 15, '20012026164910_Clean Code.webp'),
(14, 'Sapiens: Riwayat Singkat Umat Manusia', 'Yuval Noah Harari', 'KPG', '2011', 25, '20012026164835_Sapiens Riwayat Singkat Umat Manusia.webp'),
(15, 'Harry Potter dan Batu Bertuah', 'J.K. Rowling', 'Gramedia', '2000', 60, '20012026164758_Harry Potter dan Batu Bertuah.webp'),
(16, 'Dunia Sophie', 'Jostein Gaarder', 'Mizan', '1991', 20, '20012026164728_Dunia Sophie.webp'),
(17, 'Rich Dad Poor Dad', 'Robert Kiyosaki', 'Gramedia', '1997', 55, '20012026164650_Rich Dad Poor Dad.webp'),
(18, 'Sebuah Seni untuk Bersikap Bodo Amat', 'Mark Manson', 'Grasindo', '2016', 50, '20012026164621_Sebuah Seni untuk Bersikap Bodo Amat.webp'),
(19, 'Pulang', 'Tere Liye', 'Republika', '2015', 35, '20012026164552_Pulang.webp'),
(20, 'Hujan', 'Tere Liye', 'Gramedia', '2016', 40, '20012026164524_Hujan.webp'),
(21, 'Anak Semua Bangsa', 'Pramoedya Ananta Toer', 'Lentera Dipantara', '1980', 25, '20012026164243_Anak Semua Bangsa.webp'),
(22, 'Gadis Kretek', 'Ratih Kumala', 'Gramedia', '2012', 30, '20012026162503_Gadis Kretek.webp'),
(23, 'Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia', '2002', 15, '20012026162439_Cantik Itu Luka.webp'),
(24, 'Bicara Itu Ada Seninya', 'Oh Su Hyang', 'Bhuana Ilmu', '2018', 45, '20012026162415_Bicara Itu Ada Seninya.webp'),
(25, 'JavaScript: The Good Parts', 'Douglas Crockford', 'O Reilly', '2008', 10, '20012026162340_111.jfif'),
(26, 'React JS untuk Pemula', 'Eko Kurniawan', 'Informatika', '2023', 20, '20012026162214_React JS untuk Pemula.webp'),
(27, 'Belajar Database MySQL', 'Budi Raharjo', 'Informatika', '2022', 25, '20012026162141_Belajar Database MySQL.webp'),
(28, 'Sistem Informasi Manajemen', 'Kenneth C. Laudon', 'Salemba Empat', '2021', 15, '20012026162052_OIP.webp');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_kembali_rencana` date DEFAULT NULL,
  `tgl_kembali_aktual` date DEFAULT NULL,
  `denda` int(11) DEFAULT 0,
  `status` enum('dipinjam','dikembalikan') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_pinjam`, `id_user`, `id_buku`, `tgl_pinjam`, `tgl_kembali_rencana`, `tgl_kembali_aktual`, `denda`, `status`) VALUES
(1, 2, 8, '2026-01-07', '2026-01-14', '2026-01-20', 6000, ''),
(2, 2, 8, '2026-01-07', '2026-01-14', '2026-01-20', 6000, ''),
(3, 2, 5, '2026-01-07', '2026-01-14', '2026-01-20', 6000, ''),
(4, 3, 6, '2026-01-08', '2026-01-15', '2026-01-08', 0, 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `foto_profil` varchar(100) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `alamat`, `no_telp`, `foto_profil`) VALUES
(1, 'Kyyn Admin', 'admin', 'admin123', 'admin', 'Bekasi', '08123456789', 'default.jpg'),
(2, 'naufal fajlani', 'budi', 'budi123', 'user', 'kp blokang', '08923323532523', '07012026175821_Filosofi Teras.jpeg'),
(3, 'naufal fajlani', 'naufal', 'naufal123', 'user', 'kk kandang', '012491249174', 'default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

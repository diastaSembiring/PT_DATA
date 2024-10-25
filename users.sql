-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Okt 2024 pada 05.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `data_pc`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ADMIN','VIEWER','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$89MzkWrmRgL.S0RSeg.DTOyuw3SMO9eO/FCKhLhCDL7RkalB1fLaC', 'ADMIN'),
(3, 'ahnaf', '$2y$10$kVi47l8CB8UbFwgOBi6wgOr.mzqwfgmKvPAlFdXWUA9i/zNZTc/YC', 'ADMIN'),
(4, 'fajar', '$2y$10$sxCTpxmtIc02/oGM1Zt4kOg7klY.VRou.sV.AAN6aZEkVsE3Gih4e', 'ADMIN'),
(5, 'diasta', '$2y$10$RQ/6IW9ux8gt9SJzJT1yF.2r94xIqyZQZKNga6jK/s73XMyFuKyqy', 'ADMIN'),
(6, 'zanu', '$2y$10$echEdGYhlmkV83844ynTWuXOZKKQfvKtrRB2JubOmMJmwkvkL3jim', 'ADMIN'),
(7, 'randi', '$2y$10$WjIKYRbsTS7SO9cm2DH5meQhGOBe2TXiAnVNOMz6XRYCfDadc5yRe', 'ADMIN'),
(8, 'ahnaf123', '$2y$10$wrvScL1B9TCHYGTnNBfL9umEzx/u8LE/R75hmArm54WFTE99AlirW', 'ADMIN'),
(9, 'fajar123', '$2y$10$HrfE7KohU9R.k0L7b9RTm.bQkVSdNuJEOGmiFCpUveu8pGbdE5LIW', 'VIEWER'),
(10, 'idul', '$2y$10$nlpBZOauX455ykK1Cfr0ueFs16WOTQjQdD07G08DG71umIQfLQxL.', 'ADMIN');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

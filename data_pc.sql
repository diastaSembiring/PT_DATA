-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 10:57 AM
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
-- Database: `data_pc`
--

-- --------------------------------------------------------

--
-- Table structure for table `pc_tel`
--

CREATE TABLE `pc_tel` (
  `id` int(11) NOT NULL,
  `nama_pc` varchar(100) NOT NULL,
  `tanggal_input` date NOT NULL,
  `kondisi_pc` varchar(50) NOT NULL,
  `jenis_pc` varchar(50) NOT NULL,
  `lokasi_pc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pc_tel`
--

INSERT INTO `pc_tel` (`id`, `nama_pc`, `tanggal_input`, `kondisi_pc`, `jenis_pc`, `lokasi_pc`) VALUES
(20, 'pc001', '2024-10-08', 'cukup baik', 'desktop', 'lantai 2'),
(21, 'pc002', '2024-10-08', 'cukup baik', 'desktop', 'lantai 2'),
(22, 'pc003', '2024-10-01', 'baik', 'desktop', 'pabrik'),
(23, 'pc004', '2024-10-02', 'baik', 'laptop', 'asoka'),
(24, 'pc005', '2024-10-03', 'buruk', 'laptop', 'asoka'),
(25, 'jaree', '2024-10-02', 'buruk', '111', '111'),
(26, 'pc006', '2024-10-02', 'cukup baik', 'desktop', 'asoka'),
(27, 'pc007', '2024-10-04', 'buruk', 'laptop', 'lantai 2'),
(28, 'jaree', '2024-10-02', 'buruk', '111', 'lantai 2'),
(29, 'naff', '2024-10-03', 'buruk', 'desktop', 'pabrik'),
(30, 'naff', '2024-10-09', 'baik', '333', 'asoka');

-- --------------------------------------------------------

--
-- Table structure for table `pm_tel`
--

CREATE TABLE `pm_tel` (
  `id` int(11) NOT NULL,
  `nama_pc` varchar(50) NOT NULL,
  `kondisi_sebelum` varchar(50) NOT NULL,
  `tanggal_sebelum` datetime NOT NULL,
  `kondisi_setelah` varchar(50) NOT NULL,
  `tanggal_setelah` datetime NOT NULL,
  `lokasi_pc` varchar(50) NOT NULL,
  `jenis_pc` varchar(50) NOT NULL,
  `catatan` text NOT NULL,
  `pc_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pm_tel`
--

INSERT INTO `pm_tel` (`id`, `nama_pc`, `kondisi_sebelum`, `tanggal_sebelum`, `kondisi_setelah`, `tanggal_setelah`, `lokasi_pc`, `jenis_pc`, `catatan`, `pc_id`) VALUES
(2, '', 'buruk', '2024-10-08 04:02:00', 'baik', '0000-00-00 00:00:00', 'lantai 2', 'desktop', '', 21),
(3, '', 'buruk', '2024-10-08 04:02:00', 'cukup baik', '0000-00-00 00:00:00', 'lantai 2', 'desktop', '', 21),
(4, '', 'buruk', '2024-10-08 04:02:00', 'cukup baik', '0000-00-00 00:00:00', 'lantai 2', 'desktop', '', 21),
(5, '', 'buruk', '2024-10-08 04:02:00', 'cukup baik', '2024-10-10 14:33:00', 'lantai 2', 'desktop', '', 21),
(6, '', 'buruk', '2024-10-01 07:48:00', 'baik', '2024-10-09 14:49:00', 'pabrik', '2024-10-01T14:48', '', 22),
(7, '', 'buruk', '2024-10-01 07:48:00', 'baik', '2024-10-09 14:50:00', 'pabrik', '2024-10-01T14:48', '', 22),
(8, '', 'baik', '2024-10-02 08:02:00', 'buruk', '2024-10-10 15:03:00', '111', 'undefined', '', 25),
(9, '', 'baik', '2024-10-02 08:41:00', 'buruk', '2024-10-09 15:44:00', 'lantai 2', '111', '', 28),
(10, '', 'buruk', '2024-10-09 08:54:00', 'baik', '2024-10-10 15:55:00', 'asoka', '3334', '', 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pc_tel`
--
ALTER TABLE `pc_tel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_tel`
--
ALTER TABLE `pm_tel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pm_tel_ibfk_1` (`pc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pc_tel`
--
ALTER TABLE `pc_tel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pm_tel`
--
ALTER TABLE `pm_tel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pm_tel`
--
ALTER TABLE `pm_tel`
  ADD CONSTRAINT `pm_tel_ibfk_1` FOREIGN KEY (`pc_id`) REFERENCES `pc_tel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

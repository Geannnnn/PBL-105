-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 08:00 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_stok`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` varchar(50) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_kategori`, `nama_barang`, `id_user`, `id_satuan`, `stok`, `gambar`) VALUES
('AM01', 1, 'Air Mineral', 8, 6, 1, '676013150caf5_sanford botol.png'),
('B01', 4, 'Burger', 8, 6, 9, '6760132218b68_burger kink.png'),
('FF01', 1, 'French Fries', 8, 1, 123, '675fb1d080d42_frenchfreis.png');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `id_user`, `nama_kategori`) VALUES
(1, 2, 'Air Mineral'),
(3, 2, 'Lasegar'),
(4, 2, 'Burger');

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id_satuan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_satuan` varchar(255) NOT NULL,
  `jumlah_satuan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id_satuan`, `id_user`, `nama_satuan`, `jumlah_satuan`) VALUES
(1, 2, 'Dus', 24),
(6, 8, 'Satuan', 1),
(7, 8, 'Lusin', 12),
(8, 8, 'Kodi', 20);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_keluar`
--

CREATE TABLE `transaksi_keluar` (
  `id_transaksi_keluar` int(11) NOT NULL,
  `id_barang` varchar(50) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `catatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi_keluar`
--

INSERT INTO `transaksi_keluar` (`id_transaksi_keluar`, `id_barang`, `id_user`, `tanggal_keluar`, `jumlah_keluar`, `catatan`) VALUES
(8, 'AM01', 8, '2024-12-16', 10, 'Dibeli'),
(9, 'B01', 8, '2024-12-16', 10, 'Dibeli'),
(12, 'FF01', 8, '2024-12-16', 10, 'Dibeli'),
(13, 'AM01', 8, '2024-12-18', 30, 'Salah Memasukkan data'),
(14, 'B01', 2, '2024-12-20', 9, 'Dibeli'),
(15, 'FF01', 8, '2024-12-20', 130, 'Dibeli'),
(16, 'AM01', 8, '2024-12-20', 134, 'Dibeli'),
(17, 'B01', 8, '2025-01-08', 1, 'dibeli'),
(18, 'B01', 8, '2025-01-02', 1, 'Dibeli'),
(19, 'FF01', 2, '2025-01-08', 4, 'Dibeli');

--
-- Triggers `transaksi_keluar`
--
DELIMITER $$
CREATE TRIGGER `after_barang_keluar` AFTER INSERT ON `transaksi_keluar` FOR EACH ROW BEGIN
    DECLARE satuan_value INT;
    DECLARE stok_tersedia INT;

    SELECT jumlah_satuan INTO satuan_value
    FROM barang
    JOIN satuan ON barang.id_satuan = satuan.id_satuan
    WHERE barang.id_barang = NEW.id_barang;

    SELECT stok INTO stok_tersedia
    FROM barang
    WHERE id_barang = NEW.id_barang;

    IF stok_tersedia >= NEW.jumlah_keluar THEN
        UPDATE barang
        SET stok = stok - (NEW.jumlah_keluar * satuan_value)
        WHERE id_barang = NEW.id_barang;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok barang tidak cukup untuk dikeluarkan';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_masuk`
--

CREATE TABLE `transaksi_masuk` (
  `id_barang` varchar(50) NOT NULL,
  `id_transaksi_masuk` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi_masuk`
--

INSERT INTO `transaksi_masuk` (`id_barang`, `id_transaksi_masuk`, `id_user`, `jumlah_masuk`, `tanggal_masuk`) VALUES
('AM01', 7, 8, 20, '2024-12-16'),
('B01', 8, 8, 20, '2024-12-16'),
('FF01', 9, 8, 20, '2024-12-16'),
('AM01', 10, 8, 24, '2024-12-18'),
('FF01', 11, 8, 123, '2024-12-18'),
('AM01', 12, 8, 123, '2024-12-18'),
('AM01', 13, 8, 1, '2024-12-18'),
('AM01', 14, 8, 1, '2024-12-18'),
('AM01', 15, 8, 1, '2024-12-18'),
('AM01', 16, 8, 1, '2024-12-18'),
('AM01', 17, 8, 1, '2024-12-18'),
('AM01', 18, 8, 1, '2024-12-18'),
('AM01', 19, 8, 1, '2024-12-18'),
('AM01', 20, 8, 1, '2024-12-18'),
('FF01', 21, 8, 8, '2024-12-20'),
('B01', 22, 8, 10, '2024-12-20'),
('FF01', 23, 2, 1, '2025-01-09');

--
-- Triggers `transaksi_masuk`
--
DELIMITER $$
CREATE TRIGGER `after_barang_masuk` AFTER INSERT ON `transaksi_masuk` FOR EACH ROW BEGIN
    DECLARE satuan_value INT;

    SELECT jumlah_satuan INTO satuan_value
    FROM barang
    JOIN satuan ON barang.id_satuan = satuan.id_satuan
    WHERE barang.id_barang = NEW.id_barang;

    UPDATE barang
    SET stok = stok + (NEW.jumlah_masuk * satuan_value)
    WHERE id_barang = NEW.id_barang;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin', 'admin'),
(2, 'staff', 'staff', 'staff', 'staff'),
(8, 'ali', 'ror', 'ror', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `barang_ibfk_5` (`id_satuan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id_satuan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `transaksi_keluar`
--
ALTER TABLE `transaksi_keluar`
  ADD PRIMARY KEY (`id_transaksi_keluar`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `transaksi_masuk`
--
ALTER TABLE `transaksi_masuk`
  ADD PRIMARY KEY (`id_transaksi_masuk`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `transaksi_masuk_ibfk_2` (`id_barang`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaksi_keluar`
--
ALTER TABLE `transaksi_keluar`
  MODIFY `id_transaksi_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transaksi_masuk`
--
ALTER TABLE `transaksi_masuk`
  MODIFY `id_transaksi_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_ibfk_4` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_ibfk_5` FOREIGN KEY (`id_satuan`) REFERENCES `satuan` (`id_satuan`) ON UPDATE CASCADE;

--
-- Constraints for table `kategori`
--
ALTER TABLE `kategori`
  ADD CONSTRAINT `kategori_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;

--
-- Constraints for table `satuan`
--
ALTER TABLE `satuan`
  ADD CONSTRAINT `satuan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_keluar`
--
ALTER TABLE `transaksi_keluar`
  ADD CONSTRAINT `transaksi_keluar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_keluar_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_masuk`
--
ALTER TABLE `transaksi_masuk`
  ADD CONSTRAINT `transaksi_masuk_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_masuk_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

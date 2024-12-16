-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 06:43 AM
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
('1', 1, 'Sanford', 8, 1, 0, 'sanford botol.png'),
('BK02', 4, 'Burger King', 8, 7, 45, '675fafdd0abeb_burger kink.png'),
('FF01', 3, 'French Fries', 8, 1, 0, '675fb16d06715_frenchfreis.png'),
('S01', 1, 'Sanford', 8, 6, 0, '675fae37dd5d0_sanford botol.png');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `jenis_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `id_user`, `nama_kategori`, `jenis_kategori`) VALUES
(1, 2, 'Air Mineral', 'Minuman'),
(3, 2, 'Lasegar', 'Minuman'),
(4, 2, 'Burger', 'Makanan');

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
(1, '1', 2, '2024-12-05', 2, 'Dibeli'),
(2, '1', 2, '2024-12-13', 2, ''),
(3, '1', 2, '2024-12-13', 2, ''),
(4, '1', 2, '2024-12-16', 4, 'Salah'),
(5, '1', 8, '2024-12-16', 20, 'dibeli'),
(6, '1', 8, '2024-12-16', 20, 'dibeli');

--
-- Triggers `transaksi_keluar`
--
DELIMITER $$
CREATE TRIGGER `log_alert` AFTER INSERT ON `transaksi_keluar` FOR EACH ROW IF (SELECT stok FROM barang WHERE id_barang = NEW.id_barang) < NEW.jumlah_keluar THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stok barang tidak mencukupi';
    ELSE
        UPDATE barang
        SET stok = stok - NEW.jumlah_keluar
        WHERE id_barang = NEW.id_barang;
    END IF
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
('1', 1, 2, 2, '2024-12-05'),
('1', 3, 2, 2, '2024-12-13'),
('1', 4, 2, 35, '2024-12-16'),
('BK02', 5, 8, 45, '2024-12-16');

--
-- Triggers `transaksi_masuk`
--
DELIMITER $$
CREATE TRIGGER `log_barang_masuk` AFTER INSERT ON `transaksi_masuk` FOR EACH ROW UPDATE barang set
stok = stok + new.jumlah_masuk
where id_barang = new.id_barang
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
(2, 'lucifer', 'staff', 'staff', 'staff'),
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
  ADD KEY `id_barang` (`id_barang`);

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
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaksi_keluar`
--
ALTER TABLE `transaksi_keluar`
  MODIFY `id_transaksi_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi_masuk`
--
ALTER TABLE `transaksi_masuk`
  MODIFY `id_transaksi_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `transaksi_masuk_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

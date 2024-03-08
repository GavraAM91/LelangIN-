-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2024 at 08:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_lelang`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_account`
--

CREATE TABLE `tb_account` (
  `id_user` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` varchar(25) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_account`
--

INSERT INTO `tb_account` (`id_user`, `username`, `password`, `email`, `role`) VALUES
('USR001', 'admin', '$2y$10$64IZV8D5Sh7gZm79JaXQ7OHF2HMWtQhYBTXL3DIy9h7m8x76b4VEm', 'admin@admin.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_address`
--

CREATE TABLE `tb_address` (
  `id_address` int(11) NOT NULL,
  `id_user` varchar(50) NOT NULL,
  `desa` varchar(30) NOT NULL,
  `kecamatan` varchar(30) NOT NULL,
  `kabupaten/kota` varchar(30) NOT NULL,
  `provinsi` varchar(30) NOT NULL,
  `negara` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_cart`
--

CREATE TABLE `tb_cart` (
  `id_cart` int(11) NOT NULL,
  `id_user` varchar(25) NOT NULL,
  `id_product` varchar(25) NOT NULL,
  `image` varchar(125) NOT NULL,
  `name` varchar(25) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_ordered_product`
--

CREATE TABLE `tb_ordered_product` (
  `id_order` int(11) NOT NULL,
  `id_user` varchar(50) NOT NULL,
  `id_product` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `random_code` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_product`
--

CREATE TABLE `tb_product` (
  `id_product` varchar(25) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` bigint(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_product`
--

INSERT INTO `tb_product` (`id_product`, `image`, `name`, `description`, `quantity`, `price`, `date_added`, `status`) VALUES
('PRD001', '65e994ef54c49.png', '2', '2', 2, 2, '2024-03-07 17:20:31', 'open'),
('PRD002', '65e995322e690.jpg', 'artemis', 'he has good weapon ', 1, 100000, '2024-03-07 17:21:38', 'open'),
('PRD003', '65e9971ef0748.png', 'tomato', 'el tomato from manador suqeal le purce', 1, 5000, '2024-03-07 17:29:50', 'open'),
('PRD004', '65e99803c6c6b.jpg', 'davin alan walker', 'as an alan walker davin always make a music every month', 1, 2000000, '2024-03-07 17:33:39', 'open'),
('PRD005', '65e9982adae23.jpg', 'Marsha Lenathea Lapian', 'as an singer and dancer in JKT48 im the white\'s people in the group', 1, 48000000, '2024-03-07 17:34:18', 'open'),
('PRD006', '65e998f96c788.jpg', 'Luhut Pandjaitan', 'He is in government right know, as an menteri', 1, 0, '2024-03-07 17:37:45', 'open'),
('PRD007', 'Ransum Polri', 'makanan limited dari kepo', '1', 190000, 0, '2024-03-07 18:22:59', 'open'),
('PRD008', '5', '5', '5', 5, 0, '2024-03-07 21:07:34', 'open');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_account`
--
ALTER TABLE `tb_account`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_address`
--
ALTER TABLE `tb_address`
  ADD PRIMARY KEY (`id_address`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_cart`
--
ALTER TABLE `tb_cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_ordered_product`
--
ALTER TABLE `tb_ordered_product`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `tb_product`
--
ALTER TABLE `tb_product`
  ADD PRIMARY KEY (`id_product`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_cart`
--
ALTER TABLE `tb_cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_ordered_product`
--
ALTER TABLE `tb_ordered_product`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_address`
--
ALTER TABLE `tb_address`
  ADD CONSTRAINT `tb_address_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_account` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_cart`
--
ALTER TABLE `tb_cart`
  ADD CONSTRAINT `tb_cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_account` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_ordered_product`
--
ALTER TABLE `tb_ordered_product`
  ADD CONSTRAINT `tb_ordered_product_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `tb_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_ordered_product_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_account` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

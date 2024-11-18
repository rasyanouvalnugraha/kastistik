-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 11, 2024 at 09:32 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kastistik`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `amount` double NOT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1 = pemasukan . 2 = pemasukan (non kas) . 3 = pengeluaran',
  `date` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `saldo` double NOT NULL,
  `approve` int DEFAULT '0' COMMENT '0 = belum diproses , 1 = disetujui , 2 = ditolak '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `id_user`, `amount`, `type`, `date`, `keterangan`, `saldo`, `approve`) VALUES
(149, 4, 20000, 3, '2024-11-06', 'Beli sabun', 20000, 1),
(153, 5, 100000, 1, '2024-11-06', '', 100000, 1),
(154, 6, 100000, 1, '2024-11-06', '', 100000, 1),
(155, 15, 100000, 1, '2024-11-06', '', 100000, 1),
(156, 16, 100000, 1, '2024-11-06', '', 100000, 1),
(157, 17, 100000, 1, '2024-11-06', '', 100000, 1),
(158, 18, 100000, 1, '2024-11-06', '', 100000, 1),
(159, 28, 250000, 3, '2024-11-21', 'sewa pakaian untuk fasion show', 250000, 1),
(161, 28, 100000, 1, '2024-01-26', '', 100000, 1),
(163, 26, 100000, 1, '2024-02-06', '', 100000, 1),
(164, 25, 200000, 1, '2024-02-17', '', 200000, 1),
(165, 24, 300000, 1, '2024-04-23', '', 300000, 1),
(166, 23, 100000, 1, '2024-03-21', '', 100000, 1),
(167, 22, 50000, 1, '2024-03-06', '', 50000, 1),
(169, 22, 100000, 1, '2024-11-08', '', 100000, 1),
(170, 25, 5000, 3, '2024-11-08', 'beli ps', 5000, 1),
(171, 4, 100000, 1, '2024-11-11', '', 100000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(2) NOT NULL DEFAULT '2' COMMENT '1 = admin , 2 = user',
  `premi` double DEFAULT NULL COMMENT 'jumlah yang harus dibayar perbulan tiap orang (berbeda beda)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `role`, `premi`) VALUES
(0, 'admin', 'admin', '123', '1', 0),
(4, 'YAYAT ROCHADIYAT', 'yayat', '001', '2', 100000),
(5, 'EVINA IRONIKA', 'evina', '002', '2', 75000),
(6, 'ABADI WIBOWO', 'abadi', '003', '2', 50000),
(15, 'IDA FARIANA', 'ida', '004', '2', 50000),
(16, 'AGUS MADIYONO', 'agus', '005', '2', 20000),
(17, 'APRILIA PUPUT NADEA', 'puput', '006', '2', 50000),
(18, 'ARGO NURCAHYO', 'argo', '007', '2', 30000),
(19, 'ARI PURNOMOSARI', 'ari', '008', '2', 20000),
(20, 'ARINI EKA PURWANTI', 'arini', '009', '2', 50000),
(21, 'BAIQ NURUL HAQIQI', 'kiki', '010', '2', 30000),
(22, 'BUDI ISTI HERLINI', 'isti', '011', '2', 25000),
(23, 'DESRIA WATI', 'desria', '012', '2', 25000),
(24, 'EMI YUNITA', 'emi', '013', '2', 30000),
(25, 'IRVAN RAHMAN SALEH', 'irvan', '014', '2', 50000),
(26, 'RAFI\'UL HAYUMI HARTIN', 'fiul', '015', '2', 30000),
(27, 'SLAMET B. WIEDE', 'wiede', '016', '2', 30000),
(28, 'WIWIK AKTA PIANTRI', 'wiwik', '017', '2', 50000),
(29, 'ARINA MANA SIKANA', 'arina', '018', '2', 30000),
(36, 'RASYA NOUVAL NUGRAHA', 'aca', '000', '2', 50000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_pegawai` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

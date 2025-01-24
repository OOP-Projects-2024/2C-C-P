-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 06:36 AM
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
-- Database: `taxi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_tbl`
--

CREATE TABLE `account_tbl` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Token` varchar(255) DEFAULT NULL,
  `isdeleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_tbl`
--

INSERT INTO `account_tbl` (`id`, `username`, `password`, `Token`, `isdeleted`) VALUES
(4, 'test1', '123', NULL, 0),
(5, 'test2', '123', NULL, 0),
(6, 'test7', '123', NULL, 0),
(7, 'test', '$2y$10$MOo2laLB34uSYv7FGvZrQetLT.g52xhOYyXLld7wRv/bAYKmv9SKm', NULL, NULL),
(8, 'test', '$2y$10$MgJlkiZPHwMKxpKkDettSekrRG/g7hSM8ME1k4xdCu.EXoIWhWm92', NULL, NULL),
(10, 'test', '$2y$10$amfcAWH7Z59Ivpif8BRDb.jd2wqZ292.6fcxAU5xtMXHwLIYFzWK.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `taxis_tbl`
--

CREATE TABLE `taxis_tbl` (
  `taxi_id` int(11) NOT NULL,
  `license_plate` varchar(20) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `status` enum('available','unavailable','','') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Token` varchar(255) DEFAULT NULL,
  `isdeleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taxis_tbl`
--

INSERT INTO `taxis_tbl` (`taxi_id`, `license_plate`, `driver_name`, `status`, `created_at`, `Token`, `isdeleted`) VALUES
(9, 'KLF123', 'John Wick', 'available', '2025-01-24 04:30:20', NULL, NULL),
(10, 'KLF123', 'John Wick', 'available', '2025-01-24 04:30:22', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_tbl`
--
ALTER TABLE `account_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxis_tbl`
--
ALTER TABLE `taxis_tbl`
  ADD PRIMARY KEY (`taxi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_tbl`
--
ALTER TABLE `account_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `taxis_tbl`
--
ALTER TABLE `taxis_tbl`
  MODIFY `taxi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

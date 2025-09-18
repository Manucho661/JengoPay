-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 07:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bt_jengopay`
--

-- --------------------------------------------------------

--
-- Table structure for table `single_unit_bills`
--

CREATE TABLE `single_unit_bills` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `bill` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `single_unit_bills`
--

INSERT INTO `single_unit_bills` (`id`, `unit_id`, `bill`, `qty`, `unit_price`) VALUES
(21, 3, 'Water', 1, 150.00),
(22, 3, 'Electricity', 1, 50.00),
(23, 3, 'Garbage', 1, 250.00),
(24, 3, 'Internet', 1, 3000.00),
(25, 3, 'Security', 1, 200.00),
(26, 3, 'Management Fee', 1, 200.00),
(27, 1, 'Water', 1, 180.00),
(28, 1, 'Electricity', 1, 80.00),
(29, 1, 'Garbage', 1, 350.00),
(30, 1, 'Internet', 1, 2500.00),
(31, 4, 'Water', 1, 150.00),
(32, 4, 'Electricity', 1, 200.00),
(33, 4, 'Garbage', 1, 200.00),
(34, 4, 'Internet', 1, 2000.00),
(39, 2, 'Water', 1, 150.00),
(40, 2, 'Electricity', 1, 50.00),
(41, 2, 'Garbage', 1, 200.00),
(42, 2, 'Internet', 1, 2500.00),
(43, 2, 'Security', 1, 200.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `single_unit_bills`
--
ALTER TABLE `single_unit_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `single_unit_bills`
--
ALTER TABLE `single_unit_bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `single_unit_bills`
--
ALTER TABLE `single_unit_bills`
  ADD CONSTRAINT `single_unit_bills_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `single_units` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

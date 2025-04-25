-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 04:22 PM
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
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_number` varchar(11) NOT NULL,
  `size` int(255) NOT NULL,
  `floor_number` int(255) NOT NULL,
  `rooms` varchar(255) NOT NULL,
  `room_type` int(255) NOT NULL,
  `bathrooms` varchar(255) NOT NULL,
  `kitchen` varchar(255) NOT NULL,
  `balcony` varchar(255) NOT NULL,
  `rent_amount` int(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `building_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_number`, `size`, `floor_number`, `rooms`, `room_type`, `bathrooms`, `kitchen`, `balcony`, `rent_amount`, `description`, `created_at`, `updated_at`, `building_id`) VALUES
(1, 'a', 7, 7, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 12000, 'WIDE ROOM ', '2025-04-24 09:56:06', '2025-04-24 09:56:06', NULL),
(2, 'a', 6, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 7000, 'WIDE ROOM ', '2025-04-24 09:56:26', '2025-04-24 09:56:26', NULL),
(3, 'a', 6, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 7000, 'WIDE ROOM ', '2025-04-24 09:57:08', '2025-04-24 09:57:08', NULL),
(4, 'a', 6, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 7000, 'WIDE ROOM ', '2025-04-24 09:57:53', '2025-04-24 09:57:53', NULL),
(5, 'A12', 5, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 8000, 'WIDE ROOM ', '2025-04-24 09:58:26', '2025-04-24 09:58:26', NULL),
(6, 'A12', 5, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 8000, 'WIDE ROOM ', '2025-04-24 09:59:23', '2025-04-24 09:59:23', NULL),
(7, 'B25', 5, 3, 'One bedroom', 0, 'One bedroom', 'open', 'one', 20000, 'wide room with a wide space ', '2025-04-25 07:14:56', '2025-04-25 07:14:56', NULL),
(8, 'B25', 5, 5433, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4500, 'soil', '2025-04-25 12:47:23', '2025-04-25 12:47:23', NULL),
(9, 'B25', 5, 5433, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4500, 'soil', '2025-04-25 12:57:57', '2025-04-25 12:57:57', NULL),
(10, 'B25', 5, 5433, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4500, 'soil', '2025-04-25 12:58:48', '2025-04-25 12:58:48', NULL),
(11, 'B25', 5, 5433, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4500, 'soil', '2025-04-25 12:59:53', '2025-04-25 12:59:53', NULL),
(12, 'B25', 5, 5433, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4500, 'soil', '2025-04-25 13:00:40', '2025-04-25 13:00:40', NULL),
(13, 'B25', 5, 5433, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4500, 'soil', '2025-04-25 13:01:04', '2025-04-25 13:01:04', NULL),
(14, 'B25', 5, 1, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 6700, 'soil', '2025-04-25 13:02:24', '2025-04-25 13:02:24', NULL),
(15, 'B25', 5, 1, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 6700, 'soil', '2025-04-25 13:04:09', '2025-04-25 13:04:09', NULL),
(16, 'B25', 5, 9, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 6800, 'Get to test Sandwich', '2025-04-25 13:04:39', '2025-04-25 13:04:39', NULL),
(17, 'B25', 5, 9, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 6800, 'Get to test Sandwich', '2025-04-25 13:06:37', '2025-04-25 13:06:37', NULL),
(18, 'B25', 5, 9, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 6800, 'Get to test Sandwich', '2025-04-25 13:07:15', '2025-04-25 13:07:15', NULL),
(19, 'B25', 5, 9, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 6800, 'Get to test Sandwich', '2025-04-25 13:10:18', '2025-04-25 13:10:18', NULL),
(20, 'B25', 5, 9, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 6800, 'Get to test Sandwich', '2025-04-25 13:16:18', '2025-04-25 13:16:18', NULL),
(21, 'C9', 5, 9, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 8900, 'Delicious Githeri for lovers', '2025-04-25 13:17:05', '2025-04-25 13:17:05', 64),
(22, 'C10', 4, 8, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4600, 'wider', '2025-04-25 13:29:21', '2025-04-25 13:29:21', 58),
(23, 'C10', 4, 8, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 4600, 'wider', '2025-04-25 13:32:19', '2025-04-25 13:32:19', 58),
(24, 'C9', 5, 9, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 8900, 'Delicious Githeri for lovers', '2025-04-25 13:32:38', '2025-04-25 13:32:38', 64),
(25, 'B25', 5, 6, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 6777, 'Best Hamburger for Family of three', '2025-04-25 14:12:05', '2025-04-25 14:12:05', 41),
(26, 'B25', 5, 6, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 6777, 'Best Hamburger for Family of three', '2025-04-25 14:13:11', '2025-04-25 14:13:11', 41);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `fk_building_id` (`building_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `fk_building_id` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

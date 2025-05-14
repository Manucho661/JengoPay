-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 03:35 PM
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
-- Database: `bt_jengopay`
--

-- --------------------------------------------------------

--
-- Table structure for table `meter_readings`
--

CREATE TABLE `meter_readings` (
  `id` int(11) NOT NULL,
  `reading_date` date NOT NULL,
  `unit_number` varchar(255) NOT NULL DEFAULT current_timestamp(),
  `meter_type` varchar(255) NOT NULL,
  `previous_reading` int(255) NOT NULL,
  `current_reading` int(255) NOT NULL,
  `consumption_units` int(255) NOT NULL,
  `building_id` int(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meter_readings`
--

INSERT INTO `meter_readings` (`id`, `reading_date`, `unit_number`, `meter_type`, `previous_reading`, `current_reading`, `consumption_units`, `building_id`, `created_at`, `updated_at`) VALUES
(18, '2025-04-24', 'C9', 'Water', 80, 100, 20, 0, '2025-04-25 14:09:21', '2025-04-25 14:09:21'),
(19, '2025-04-27', 'C40', 'Electrical', 12, 15, 3, 0, '2025-04-28 08:00:25', '2025-04-28 08:00:25'),
(21, '2025-04-29', 'D8', 'Electrical', 18, 25, 7, 0, '2025-04-30 07:32:24', '2025-04-30 07:32:24'),
(29, '2025-04-29', 'B30', 'Water', 15, 18, 3, 0, '2025-04-30 07:39:36', '2025-04-30 07:39:36'),
(37, '2025-04-28', 'C41', 'Water', 25, 27, 2, 0, '2025-04-30 07:43:30', '2025-04-30 07:43:30'),
(40, '2025-05-07', 'A12', 'Electrical', 56, 60, 4, 0, '2025-05-07 12:46:04', '2025-05-07 12:46:04'),
(41, '2025-05-08', 'A45', 'Water', 65, 70, 5, 0, '2025-05-07 12:49:22', '2025-05-07 12:49:22'),
(42, '2025-05-09', 'A12', 'Water', 76, 54, -22, 0, '2025-05-14 07:43:51', '2025-05-14 07:43:51'),
(43, '2025-05-01', 'A12', 'Water', 21, 40, 19, 0, '2025-05-14 07:57:29', '2025-05-14 07:57:29'),
(44, '2025-05-08', 'A12', 'Water', 25, 30, 5, 0, '2025-05-14 07:59:45', '2025-05-14 07:59:45'),
(45, '2025-05-01', 'A12', 'Water', 20, 29, 9, 0, '2025-05-14 08:02:28', '2025-05-14 08:02:28'),
(46, '2025-05-08', 'A12', 'Water', 25, 28, 3, 0, '2025-05-14 08:03:43', '2025-05-14 08:03:43'),
(47, '2025-05-08', 'A12', 'Water', 25, 35, 10, 0, '2025-05-14 08:15:07', '2025-05-14 08:15:07'),
(48, '2025-05-08', 'A12', 'Water', 25, 35, 10, 0, '2025-05-14 08:15:46', '2025-05-14 08:15:46'),
(49, '2025-05-08', 'A12', 'Water', 25, 35, 10, 0, '2025-05-14 08:16:37', '2025-05-14 08:16:37'),
(50, '2025-05-08', 'A12', 'Water', 25, 35, 10, 0, '2025-05-14 08:16:41', '2025-05-14 08:16:41'),
(51, '2025-05-08', 'A12', 'Water', 25, 35, 10, 105, '2025-05-14 08:17:52', '2025-05-14 08:17:52'),
(52, '2025-05-01', 'D88', 'Water', 28, 38, 10, 117, '2025-05-14 11:50:00', '2025-05-14 11:50:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meter_readings`
--
ALTER TABLE `meter_readings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

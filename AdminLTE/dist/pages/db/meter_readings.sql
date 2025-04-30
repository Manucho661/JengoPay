-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 04:33 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meter_readings`
--

INSERT INTO `meter_readings` (`id`, `reading_date`, `unit_number`, `meter_type`, `previous_reading`, `current_reading`, `consumption_units`, `created_at`, `updated_at`) VALUES
(18, '2025-04-24', 'C9', 'Water', 80, 100, 20, '2025-04-25 14:09:21', '2025-04-25 14:09:21'),
(19, '2025-04-27', 'C40', 'Electrical', 12, 15, 3, '2025-04-28 08:00:25', '2025-04-28 08:00:25'),
(21, '2025-04-29', 'D8', 'Electrical', 18, 25, 7, '2025-04-30 07:32:24', '2025-04-30 07:32:24'),
(29, '2025-04-29', 'B30', 'Water', 15, 18, 3, '2025-04-30 07:39:36', '2025-04-30 07:39:36'),
(37, '2025-04-28', 'C41', 'Water', 25, 27, 2, '2025-04-30 07:43:30', '2025-04-30 07:43:30');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

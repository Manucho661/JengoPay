-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 07:15 AM
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
-- Table structure for table `single_units`
--

CREATE TABLE `single_units` (
  `id` int(11) NOT NULL,
  `unit_number` varchar(50) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `building_link` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `monthly_rent` decimal(10,2) NOT NULL,
  `occupancy_status` enum('Occupied','Vacant','Under Maintenance') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `single_units`
--

INSERT INTO `single_units` (`id`, `unit_number`, `purpose`, `building_link`, `location`, `monthly_rent`, `occupancy_status`, `created_at`, `updated_at`) VALUES
(1, 'CH-210', 'Residential', 'The Angela Cresents', '2', 12000.00, 'Occupied', '2025-08-20 06:41:44', '2025-08-20 14:29:35'),
(2, 'CA-20', 'Business', 'Naths Pinacle', '1', 18000.00, 'Vacant', '2025-08-20 06:43:04', '2025-08-27 15:39:15'),
(3, 'A21', 'Business', 'The Angela Cresents', '3', 23000.00, 'Under Maintenance', '2025-08-20 06:45:11', '2025-08-27 14:26:17'),
(4, 'BA-122', 'Office', 'KICC', '2', 20000.00, 'Occupied', '2025-08-27 15:37:22', '2025-08-27 15:37:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `single_units`
--
ALTER TABLE `single_units`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `single_units`
--
ALTER TABLE `single_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 09:04 AM
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
-- Table structure for table `unit_information`
--

CREATE TABLE `unit_information` (
  `id` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_information`
--

INSERT INTO `unit_information` (`id`, `unit_number`, `size`, `floor_number`, `rooms`, `room_type`, `bathrooms`, `kitchen`, `balcony`, `rent_amount`, `description`, `created_at`, `updated_at`) VALUES
(1, 'a', 7, 7, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 12000, 'WIDE ROOM ', '2025-04-24 09:56:06', '2025-04-24 09:56:06'),
(2, 'a', 6, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 7000, 'WIDE ROOM ', '2025-04-24 09:56:26', '2025-04-24 09:56:26'),
(3, 'a', 6, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 7000, 'WIDE ROOM ', '2025-04-24 09:57:08', '2025-04-24 09:57:08'),
(4, 'a', 6, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 7000, 'WIDE ROOM ', '2025-04-24 09:57:53', '2025-04-24 09:57:53'),
(5, 'A12', 5, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 8000, 'WIDE ROOM ', '2025-04-24 09:58:26', '2025-04-24 09:58:26'),
(6, 'A12', 5, 5, 'Bedsitter', 0, 'One bedroom', 'open', 'one', 8000, 'WIDE ROOM ', '2025-04-24 09:59:23', '2025-04-24 09:59:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `unit_information`
--
ALTER TABLE `unit_information`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `unit_information`
--
ALTER TABLE `unit_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

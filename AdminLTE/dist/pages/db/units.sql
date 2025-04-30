-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 04:32 PM
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
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_number` varchar(11) NOT NULL,
  `size` varchar(255) NOT NULL,
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
(32, 'B30', '5 BY 5', 5, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 8500, 'WIDE ROOM WERE', '2025-04-30 07:29:25', '2025-04-30 07:29:25', 64),
(33, 'D8', '4 BY 5', 6, 'Bedsitter', 0, 'One bathroom', 'open', 'one', 9000, 'WIDE ROOM ', '2025-04-30 07:31:15', '2025-04-30 07:31:15', 64),
(38, 'C45', '5 BY 5', 9, 'Bedsitter', 0, 'One bathroom', 'closed', 'one', 8500, 'WIDE ROOM ', '2025-04-30 08:00:43', '2025-04-30 08:00:43', 64),
(39, 'C9', '4 BY 5', 8, 'One bedroom', 0, 'One bathroom', 'open', 'one', 15000, 'WIDE ROOM ', '2025-04-30 10:21:06', '2025-04-30 10:21:06', 76),
(42, 'B38', '3 BY 5', 6, 'One bedroom', 0, 'One bathroom', 'open', 'one', 16000, 'WIDE ROOM ', '2025-04-30 12:21:39', '2025-04-30 12:21:39', 77);

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
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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

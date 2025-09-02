-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 03:49 PM
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
  `unit_type` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `floor_number` int(255) NOT NULL,
  `rooms` varchar(255) NOT NULL,
  `room_type` varchar(255) NOT NULL,
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

INSERT INTO `units` (`unit_id`, `unit_number`, `unit_type`, `size`, `floor_number`, `rooms`, `room_type`, `bathrooms`, `kitchen`, `balcony`, `rent_amount`, `description`, `created_at`, `updated_at`, `building_id`) VALUES
(52, '44', 'Residential', '4 by 8', 8, 'Bedsitter', '0', 'Two bathroom', 'open', 'one', 20000, 'gkj', '2025-05-15 09:20:19', '2025-05-15 09:20:19', 128),
(53, 'g5', 'Residential', '5 by 5', 4, 'One bedroom', '0', 'Two bathroom', 'open', 'one', 5644, 'widde', '2025-05-15 09:22:59', '2025-05-15 09:22:59', 127),
(54, 'tgtg', 'Commercial', 'hlk', 77, 'Bedsitter', '0', 'Three bathroom', 'open', 'one', 8999, 'gk', '2025-05-15 09:31:58', '2025-05-15 09:31:58', 127),
(55, '6', 'Commercial', 'fck', 7, 'Bedsitter', '0', 'Two bathroom', 'open', 'one', 7544, 'xshn', '2025-05-15 09:34:26', '2025-05-15 09:34:26', 127),
(56, 'dv', 'Residential', 'gd', 4, 'One bedroom', '0', 'Two bathroom', 'open', 'one', 4533, 'sdewf', '2025-05-15 09:37:05', '2025-05-15 09:37:05', 127),
(57, '76', 'Commercial', '687', 6, 'One bedroom', '0', 'Two bathroom', 'closed', 'one', 6888, 'dchth', '2025-05-15 09:38:35', '2025-05-15 09:38:35', 127),
(58, 'fhn ', 'Commercial', 'gh', 5, 'One bedroom', '0', 'Two bathroom', 'closed', 'one', 43455, 'revgeg', '2025-05-15 09:41:18', '2025-05-15 09:41:18', 127),
(59, 'dxfsv', 'Commercial', 'dsc', 45, 'One bedroom', '0', 'Two bathroom', 'closed', 'two', 4533, 'rfdesgv', '2025-05-15 09:42:34', '2025-05-15 09:42:34', 128),
(60, 'jmfgvy', 'Commercial', 'fgvy', 6, 'Bedsitter', '0', 'Two bathroom', 'open', 'one', 4533, 'ayhhh ', '2025-05-15 09:45:41', '2025-05-15 09:45:41', 127),
(61, 'r5yh', 'Commercial', 'y', 6, 'One bedroom', '0', 'Two bathroom', 'open', 'one', 4333, 'dhyd', '2025-05-15 09:46:14', '2025-05-15 09:46:14', 127),
(62, '22d', 'Residential', 'rtg', 4, 'One bedroom', '0', 'One bathroom', 'closed', 'one', 4333, 'grtsg', '2025-05-15 09:46:53', '2025-05-15 09:46:53', 128),
(63, 'yhuj', 'Commercial', '5 by 7', 6, 'One bedroom', '0', 'One bathroom', 'open', 'one', 5433, 'jhvffv', '2025-05-15 09:51:22', '2025-05-15 09:51:22', 128),
(64, '5hth', 'Commercial', 'tynh', 4, 'One bedroom', '0', 'One bathroom', 'open', 'one', 54544, 'fcgnbfcn', '2025-05-15 09:54:55', '2025-05-15 09:54:55', 127),
(65, '6', 'Commercial', '67', 76, 'Bedsitter', '0', 'Three bathroom', 'open', 'two', 67655, 'thryh', '2025-05-15 09:57:11', '2025-05-15 09:57:11', 128),
(66, 'ghmg', 'Commercial', '5 by 5', 7, 'One bedroom', '0', 'One bathroom', 'open', 'two', 9900, 'spaciuos', '2025-05-15 10:00:13', '2025-05-15 10:00:13', 128),
(67, 'b5', 'Commercial', '3 by 5', 9, 'Bedsitter', 'Rental', 'Three bathroom', 'closed', 'one', 8766, 'dctgj', '2025-05-15 10:08:44', '2025-05-15 10:08:44', 128),
(68, 'sdvgg', 'Residential', '6 by 8', 67, 'Bedsitter', 'Rental', 'Two bathroom', 'open', 'one', 8500, 'dryhxj', '2025-05-21 10:57:15', '2025-05-21 10:57:15', 127);

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
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

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

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 02:26 PM
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
-- Table structure for table `communication`
--

CREATE TABLE `communication` (
  `thread_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `subject` varchar(100) NOT NULL,
  `files` varchar(100) NOT NULL,
  `unit_id` varchar(100) NOT NULL,
  `tenant` varchar(100) NOT NULL,
  `building_name` varchar(155) NOT NULL,
  `building_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communication`
--

INSERT INTO `communication` (`thread_id`, `sender_id`, `receiver_id`, `start_date`, `end_date`, `subject`, `files`, `unit_id`, `tenant`, `building_name`, `building_id`, `title`, `created_at`, `updated_at`) VALUES
(113, NULL, NULL, NULL, NULL, 'efsgrd', '[]', '', '', '78', 0, 'request to vacate room ', '2025-05-05 10:38:12', '2025-05-05 10:38:12'),
(138, NULL, NULL, NULL, NULL, 'efsgrd', '[]', '', '', '78', 0, 'woow', '2025-05-05 12:05:23', '2025-05-05 12:05:23'),
(141, NULL, NULL, NULL, NULL, 'efsgrd', '[]', '', '', '78', 0, 'Come for your contract', '2025-05-06 08:07:24', '2025-05-06 08:07:24'),
(142, NULL, NULL, NULL, NULL, 'wash your corridor', '[]', '', '', '78', 0, 'Corridor wash', '2025-05-06 09:11:02', '2025-05-06 09:11:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `communication`
--
ALTER TABLE `communication`
  ADD PRIMARY KEY (`thread_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `communication`
--
ALTER TABLE `communication`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 12:48 PM
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
  `files` varchar(100) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_data` longblob NOT NULL,
  `size` int(11) NOT NULL,
  `unit_id` varchar(100) NOT NULL,
  `tenant` varchar(100) NOT NULL,
  `building_name` varchar(155) NOT NULL,
  `building_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `message_id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communication`
--

INSERT INTO `communication` (`thread_id`, `files`, `file_name`, `mime_type`, `file_data`, `size`, `unit_id`, `tenant`, `building_name`, `building_id`, `title`, `message`, `message_id`, `email`, `recipient`, `created_at`, `updated_at`) VALUES
(281, '[\"uploads\\/6832240b9da20_Capture.PNG\"]', '', '', '', 0, '', '', '105', 0, 'hkjk', 'kjkj', 0, '', '', '2025-05-24 22:54:51', '2025-05-24 22:54:51'),
(290, '[]', '', '', '', 0, '', '', '105', 0, 'hju', 'njhiio', 0, '', '', '2025-05-26 12:58:36', '2025-05-26 12:58:36');

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
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 12:46 PM
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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `file_path` varchar(255) NOT NULL,
  `read_status` varchar(255) NOT NULL,
  `viewed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `thread_id`, `sender`, `sender_id`, `content`, `timestamp`, `is_read`, `file_path`, `read_status`, `viewed`) VALUES
(341, 281, 'landlord', 0, 'kjkj', '2025-05-24 19:54:51', 0, 'uploads/6832240b9da20_Capture.PNG', '', 0),
(350, 281, 'landlord', 0, 'KKLL', '2025-05-26 03:49:15', 0, '', '', 0),
(351, 281, 'landlord', 0, 'hk', '2025-05-26 04:11:02', 0, '', '', 0),
(352, 281, 'landlord', 0, 'better', '2025-05-26 04:23:45', 0, '', '', 0),
(353, 281, 'landlord', 0, 'becoming', '2025-05-26 04:26:41', 0, '', '', 0),
(354, 281, 'landlord', 0, '', '2025-05-26 07:41:52', 0, '', '', 0),
(356, 290, 'landlord', 0, 'njhiio', '2025-05-26 09:58:36', 0, '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=357;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

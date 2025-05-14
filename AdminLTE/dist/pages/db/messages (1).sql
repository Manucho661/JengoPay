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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `thread_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `content`, `thread_id`, `timestamp`, `is_read`) VALUES
(99, 'landlord', 'working code', 192, '2025-05-13 12:47:20', 1),
(100, 'landlord', 'done', 192, '2025-05-13 13:06:47', 1),
(101, 'landlord', 'yees', 192, '2025-05-13 13:07:56', 1),
(102, 'landlord', 'cfb', 192, '2025-05-13 13:19:38', 1),
(103, 'landlord', 'gnhy', 192, '2025-05-13 13:19:43', 1),
(104, 'landlord', 'remember', 192, '2025-05-13 13:20:54', 1),
(105, 'landlord', 'noiw', 192, '2025-05-13 13:25:32', 1),
(106, 'landlord', 'boom', 192, '2025-05-13 13:34:16', 1),
(107, 'landlord', 'bravo', 192, '2025-05-13 13:44:22', 1),
(108, 'landlord', 'gnhgfn', 193, '2025-05-13 13:52:59', 1),
(109, 'landlord', 'dv', 192, '2025-05-13 13:54:39', 1),
(110, 'landlord', 'dvc', 193, '2025-05-13 13:54:54', 1),
(111, 'landlord', 'dvcv', 193, '2025-05-13 13:54:59', 1),
(112, 'landlord', 'fbv', 194, '2025-05-13 13:55:47', 1),
(113, 'landlord', 'frhtgb', 192, '2025-05-13 14:25:45', 1),
(114, 'landlord', 'drgffg', 192, '2025-05-13 14:59:53', 1),
(115, 'landlord', 'dgbfr', 194, '2025-05-13 15:00:10', 1),
(116, 'landlord', 'frgbfgb', 193, '2025-05-13 15:00:16', 1),
(117, 'landlord', 'done ', 195, '2025-05-14 12:42:39', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

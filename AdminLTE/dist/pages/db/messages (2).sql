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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `sender_id` int(255) NOT NULL,
  `content` text DEFAULT NULL,
  `message_id` int(255) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `sender_id`, `content`, `message_id`, `thread_id`, `timestamp`, `is_read`, `file_path`) VALUES
(0, 'landlord', 0, 'fgnbg', 0, 247, '2025-05-21 11:32:53', 0, NULL),
(0, 'landlord', 0, 'fvcbfvb ', 0, 248, '2025-05-21 11:33:28', 0, NULL),
(0, 'landlord', 0, 'bhiklo', 0, 249, '2025-05-21 12:43:42', 0, NULL),
(0, 'landlord', 0, 'jioi', 0, 249, '2025-05-21 12:45:15', 0, NULL),
(0, 'landlord', 0, 'fvnn', 0, 250, '2025-05-21 12:50:47', 0, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

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
-- Table structure for table `message_files`
--

CREATE TABLE `message_files` (
  `thread_id` int(11) NOT NULL,
  `file_id` int(255) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `attachment_id` int(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `files` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_files`
--

INSERT INTO `message_files` (`thread_id`, `file_id`, `message_id`, `attachment_id`, `file_path`, `file_type`, `file_name`, `files`) VALUES
(192, 0, NULL, 0, 'uploads/68233f5886944_6821c8f3caad7_Tenants.pdf', '', '0', ''),
(193, 0, NULL, 0, 'uploads/68234ebb34bb5_AdminLTE  Dashboard v2 (23).pdf', '', '0', ''),
(194, 0, NULL, 0, 'uploads/68234f631ac80_6821e769bf9b6_AdminLTE  Dashboard v2 (13).pdf', '', '0', ''),
(195, 0, NULL, 0, 'uploads/68248fbf18080_Terry\'s Title Deed.pdf', '', '0', ''),
(196, 0, NULL, 0, 'uploads/6824a82eb4346_Terry\'s Title Deed.pdf', '', '0', ''),
(197, 0, NULL, 0, 'uploads/6825d33d053b9_Terry\'s National id.pdf', '', '0', ''),
(198, 0, NULL, 0, 'uploads/6825d46dcd72f_Terry\'s Title Deed.pdf', '', '0', ''),
(199, 0, NULL, 0, 'uploads/6825d655b699b_Terry\'s Title Deed.pdf', '', '0', ''),
(200, 0, 200, 0, 'uploads/6825db5a5cdd0_Terry\'s National id.pdf', '', '0', ''),
(201, 0, 201, 0, 'uploads/6825dc92ad988_Terry\'s National id.pdf', '', '0', ''),
(247, 0, 247, 0, 'uploads/682db9e580fe4_Terry\'s KRA Pin.pdf', '', '', ''),
(248, 0, 248, 0, 'uploads/682dba0894631_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', '', '', ''),
(249, 0, 249, 0, 'uploads/682dca7e94128_Dreamqeja NCA Certificate.pdf', '', '', ''),
(250, 0, 250, 0, 'uploads/682dcc2702309_Terry\'s Title Deed.pdf', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message_files`
--
ALTER TABLE `message_files`
  ADD PRIMARY KEY (`thread_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message_files`
--
ALTER TABLE `message_files`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

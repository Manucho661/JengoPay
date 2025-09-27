-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 04:49 PM
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
  `file_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `attachment_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `files` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_files`
--

INSERT INTO `message_files` (`file_id`, `thread_id`, `message_id`, `attachment_id`, `file_path`, `file_type`, `file_name`, `files`, `timestamp`) VALUES
(9, 0, 11, NULL, 'uploads/1747921141_zero rated.PNG', '', '', '', '2025-05-23 08:14:50'),
(10, 0, 12, NULL, 'uploads/1747921199_12.PNG', '', '', '', '2025-05-23 08:14:50'),
(11, 0, 13, NULL, 'uploads/1747921647_Terry\'s National id.pdf', '', '', '', '2025-05-23 08:14:50'),
(12, 0, 15, NULL, 'uploads/1747983279_page1.PNG', '', '', '', '2025-05-23 08:14:50'),
(13, 0, 17, NULL, 'uploads/1747983870_zero rated.PNG', '', '', '', '2025-05-23 08:14:50'),
(14, 0, 18, NULL, 'uploads/1747984262_12.PNG', '', '', '', '2025-05-23 08:14:50'),
(15, 0, 19, NULL, 'uploads/1747984423_exclusive.PNG', '', '', '', '2025-05-23 08:14:50'),
(16, 0, 21, NULL, 'uploads/1747984925_page 4.PNG', '', '', '', '2025-05-23 08:14:50'),
(17, 0, 22, NULL, 'uploads/1747985573_exclusive.PNG', '', '', '', '2025-05-23 08:14:50'),
(18, 0, 25, NULL, 'uploads/1747986717_zero rated.PNG', '', '', '', '2025-05-23 08:14:50'),
(19, 0, 26, NULL, 'uploads/1747986778_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', '', '', '', '2025-05-23 08:14:50'),
(20, 0, 27, NULL, 'uploads/1747987529_6821c8f3caad7_Tenants.pdf', '', '', '', '2025-05-23 08:14:50'),
(21, 0, 30, NULL, 'uploads/1747988148_Terry\'s National id.pdf', '', '', '', '2025-05-23 08:15:48'),
(22, 0, 32, NULL, 'uploads/1747988273_14.PNG', '', '', '', '2025-05-23 08:17:53'),
(23, 0, 33, NULL, 'uploads/1747988384_zero rated.PNG', '', '', '', '2025-05-23 08:19:44'),
(24, 0, 34, NULL, 'uploads/1747988494_zero rated.PNG', '', '', '', '2025-05-23 08:21:34'),
(26, 0, 38, NULL, 'uploads/1747990161_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', '', '', '', '2025-05-23 08:49:21'),
(27, 0, 268, NULL, 'uploads/1747991101_682daa3522da0_6821c8f3caad7_Tenants.pdf', '', '', '', '2025-05-23 09:05:01'),
(28, 0, 272, NULL, 'uploads/1747993120_exempted.PNG', '', '', '', '2025-05-23 09:38:40'),
(29, 0, 273, NULL, 'uploads/1747993217_14.PNG', '', '', '', '2025-05-23 09:40:17'),
(30, 0, 274, NULL, 'uploads/1747993228_12.PNG', '', '', '', '2025-05-23 09:40:28'),
(32, 0, 276, NULL, 'uploads/1747994889_exclusive.PNG', '', '', '', '2025-05-23 10:08:09'),
(33, 0, 277, NULL, 'uploads/1747995196_exempted.PNG', '', '', '', '2025-05-23 10:13:16'),
(34, 0, 278, NULL, 'uploads/1747995391_12.PNG', '', '', '', '2025-05-23 10:16:31'),
(35, 0, 280, NULL, 'uploads/1747997173_exclusive.PNG', '', '', '', '2025-05-23 10:46:13'),
(36, 0, 282, NULL, 'uploads/68305d9fdc364_Screenshot2.png', '', '', '', '2025-05-23 11:35:59'),
(39, 0, 285, NULL, 'uploads/1748001069_Capture.PNG', '', '', '', '2025-05-23 11:51:09'),
(40, 0, 286, NULL, 'uploads/1748001374_exclusive.PNG', '', '', '', '2025-05-23 11:56:14'),
(41, 0, 287, NULL, 'uploads/1748001477_exclusive.PNG', '', '', '', '2025-05-23 11:57:57'),
(42, 0, 288, NULL, 'uploads/1748001481_exclusive.PNG', '', '', '', '2025-05-23 11:58:01'),
(43, 0, 289, NULL, 'uploads/1748001507_12.PNG', '', '', '', '2025-05-23 11:58:27'),
(44, 0, 290, NULL, 'uploads/1748001523_12.PNG', '', '', '', '2025-05-23 11:58:43'),
(53, 0, 303, NULL, 'uploads/1748003909_6830650d0c5f8_13.PNG', '', '', '', '2025-05-23 12:38:29'),
(54, 0, 304, NULL, 'uploads/1748003912_6830650d0c5f8_13.PNG', '', '', '', '2025-05-23 12:38:32'),
(57, 0, 307, NULL, 'uploads/1748004204_683060e7d7f94_Screenshot1.png', '', '', '', '2025-05-23 12:43:24'),
(59, 0, 310, NULL, 'uploads/1748004790_8d0ba0375e.png', '', '', '', '2025-05-23 12:53:10'),
(61, 0, 312, NULL, 'uploads/1748005083_ffa91ea375.png', '', '', '', '2025-05-23 12:58:03'),
(62, 0, 313, NULL, 'uploads/1748005098_5b0e448a94.png', '', '', '', '2025-05-23 12:58:18'),
(63, 0, 316, NULL, 'uploads/1748005154_37ae2592f9.png', '', '', '', '2025-05-23 12:59:14'),
(64, 0, 317, NULL, 'uploads/1748005220_51efaef293.png', '', '', '', '2025-05-23 13:00:20'),
(65, 0, 318, NULL, 'uploads/1748005282_68cb0a95aa.pdf', '', '', '', '2025-05-23 13:01:22'),
(67, 0, 320, NULL, 'uploads/1748005618_b3bf5d07a4.pdf', '', '', '', '2025-05-23 13:06:58'),
(68, 0, 321, NULL, 'uploads/1748005646_ad0becd1ac.png', '', '', '', '2025-05-23 13:07:26'),
(69, 0, 322, NULL, 'uploads/1748005725_aa26eb5dac.png', '', '', '', '2025-05-23 13:08:45'),
(71, 0, 325, NULL, 'uploads/1748006173_09b51c87a5.pdf', '', '', '', '2025-05-23 13:16:13'),
(72, 0, 326, NULL, 'uploads/1748006937_3e76e8694c.pdf', '', '', '', '2025-05-23 13:28:57'),
(73, 0, 329, NULL, 'uploads/1748007798_e93ee36b88.jpg', '', '', '', '2025-05-23 13:43:18'),
(74, 0, 330, NULL, 'uploads/1748073049_68781937eb.jpg', '', '', '', '2025-05-24 07:50:49'),
(75, 0, 331, NULL, 'uploads/1748073490_a5a0f153a8.jpg', '', '', '', '2025-05-24 07:58:10'),
(76, 0, 332, NULL, 'uploads/1748073654_31c74334dd.jpg', '', '', '', '2025-05-24 08:00:54'),
(77, 0, 334, NULL, 'uploads/1748074802_a46bd126f2.pdf', '', '', '', '2025-05-24 08:20:02'),
(81, 0, 349, NULL, 'uploads/1748231249_5e4515b095.png', '', '', '', '2025-05-26 03:47:29'),
(82, 0, 350, NULL, 'uploads/1748231355_135553cadd.pdf', '', '', '', '2025-05-26 03:49:15'),
(83, 0, 352, NULL, 'uploads/1748233425_882bb4fe2c.jpg', '', '', '', '2025-05-26 04:23:45'),
(84, 0, 353, NULL, 'uploads/1748233601_ff7e208ce4.png', '', '', '', '2025-05-26 04:26:41'),
(85, 0, 354, NULL, 'uploads/1748245312_e80a335703.jpg', '', '', '', '2025-05-26 07:41:52'),
(87, 0, 357, NULL, 'uploads/1748272529_66c2cae87e.png', '', '', '', '2025-05-26 15:15:29'),
(88, 0, 359, NULL, 'uploads/1748351744_f14be82f1c.png', '', '', '', '2025-05-27 13:15:44'),
(89, 0, 363, NULL, 'uploads/1748357503_6dccb015c9.png', '', '', '', '2025-05-27 14:51:43'),
(90, 0, 364, NULL, 'uploads/1748357564_e730ebfb45.pdf', '', '', '', '2025-05-27 14:52:44'),
(92, 0, 367, NULL, 'uploads/1748357657_9c7bb5e867.png', '', '', '', '2025-05-27 14:54:17'),
(96, 0, 372, NULL, 'uploads/1749049245_cce181a8b5.pdf', '', '', '', '2025-06-04 15:00:45'),
(97, 0, 373, NULL, 'uploads/1749049536_5cd070394c.png', '', '', '', '2025-06-04 15:05:36'),
(99, 0, 375, NULL, 'uploads/1749542164_b0437dadb7.pdf', '', '', '', '2025-06-10 07:56:04'),
(100, 0, 377, NULL, 'uploads/1749653058_f553733a0b.xlsx', '', '', '', '2025-06-11 14:44:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message_files`
--
ALTER TABLE `message_files`
  ADD PRIMARY KEY (`file_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message_files`
--
ALTER TABLE `message_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

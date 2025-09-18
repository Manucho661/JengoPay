-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 07:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` int(11) NOT NULL,
  `building_name` text NOT NULL,
  `county` text NOT NULL,
  `constituency` text NOT NULL,
  `ward` text NOT NULL,
  `structure_type` text NOT NULL,
  `floors_no` text NOT NULL,
  `no_of_units` text NOT NULL,
  `building_type` text NOT NULL,
  `tax_rate` text NOT NULL,
  `ownership_info` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `id_number` text NOT NULL,
  `primary_contact` text NOT NULL,
  `other_contact` text NOT NULL,
  `owner_email` text NOT NULL,
  `postal_address` text NOT NULL,
  `entity_name` text NOT NULL,
  `entity_phone` text NOT NULL,
  `entity_phoneother` text NOT NULL,
  `entity_email` text NOT NULL,
  `entity_rep` text NOT NULL,
  `rep_role` text NOT NULL,
  `entity_postal` text NOT NULL,
  `ownership_proof` varchar(200) NOT NULL,
  `title_deed` varchar(200) NOT NULL,
  `legal_document` varchar(200) NOT NULL,
  `utilities` longtext NOT NULL,
  `photo_one` varchar(200) NOT NULL,
  `photo_two` varchar(200) NOT NULL,
  `photo_three` varchar(200) NOT NULL,
  `photo_four` varchar(200) NOT NULL,
  `added_on` text NOT NULL,
  `confirm` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `building_name`, `county`, `constituency`, `ward`, `structure_type`, `floors_no`, `no_of_units`, `building_type`, `tax_rate`, `ownership_info`, `first_name`, `last_name`, `id_number`, `primary_contact`, `other_contact`, `owner_email`, `postal_address`, `entity_name`, `entity_phone`, `entity_phoneother`, `entity_email`, `entity_rep`, `rep_role`, `entity_postal`, `ownership_proof`, `title_deed`, `legal_document`, `utilities`, `photo_one`, `photo_two`, `photo_three`, `photo_four`, `added_on`, `confirm`) VALUES
(1, 'Angela Heights', '', '228', '1118', 'High Rise', '7', '75', 'Residential', '16', 'Individual', 'Angela', 'Kamama', '83239299', '0912812888', '0900212999', 'info@angelacresent.com', '2100-00100', '', '', '', '', '', '', '', 'all_uploads/d7d31a1dfbb2c6fdcc077e9bcc4b26a3ownership_cert.jpeg', 'all_uploads/d7d31a1dfbb2c6fdcc077e9bcc4b26a3title_deed.png', 'all_uploads/d7d31a1dfbb2c6fdcc077e9bcc4b26a3other_legal_document.jpeg', '<ul><li>Motion Detectors Electric Fence</li><li>360 HD Panoramic Security Cameras</li><li>Access Control Systems</li><li>Underground and Ground Floor Parking Yards</li><li>High Speed Dual Powered Lifts</li><li>Facial and Body Recognition Cameras</li><li>Electric Gate</li><li>Green Ambience with Play ground</li></ul>', 'all_uploads/d7d31a1dfbb2c6fdcc077e9bcc4b26a3apartment1.PNG', 'all_uploads/d7d31a1dfbb2c6fdcc077e9bcc4b26a3apartment2.PNG', 'all_uploads/d7d31a1dfbb2c6fdcc077e9bcc4b26a3apartment3.PNG', 'all_uploads/d7d31a1dfbb2c6fdcc077e9bcc4b26a3apartment4.PNG', '2025, Aug 27 18:34:39', '<br />\r\n<b>Warning</b>:  Undefined variable $confirm in <b>C:\\xampp\\htdocs\\digi-homes\\edit_building_'),
(2, 'Nath Creches', '47', '274', '1345', 'High Rise', '10', '183', 'Commercial', '16', 'Entity', '', '', '', '', '', '', '', 'Nath Empire', '0812912888', '0900129199', 'info@nathempire.com', 'Nathaniel Kababa', 'Founder', '9212-0200', 'all_uploads/2069db07a1d15b011f132159ad4cecf5nath_cert_owner.jpeg', 'all_uploads/2069db07a1d15b011f132159ad4cecf5nath_deed.png', 'all_uploads/2069db07a1d15b011f132159ad4cecf5nath_legal.jpeg', '<ul><li>High Speed Dual Powered Lifts</li><li>310 KV Solar System</li><li>360 HD Panoramic Security Cameras</li><li>Facial and Body Recognition Cameras</li><li>Glass Balcony for greater views</li><li>Self rolling stair cases</li><li>Access Control Systems</li><li>Underground and First Floor Parking Lots</li><li>Gym</li></ul>', 'all_uploads/2069db07a1d15b011f132159ad4cecf5address.jpg', 'all_uploads/2069db07a1d15b011f132159ad4cecf5nath_img.jpeg', 'all_uploads/2069db07a1d15b011f132159ad4cecf5nath_image1.jpg', 'all_uploads/2069db07a1d15b011f132159ad4cecf5nath_img_1.jpeg', '2025, Aug 23 07:29:45', 'Confirmation'),
(3, 'The Great Habitat', '1', '4', '15', 'Low Structure', '', '21', 'Mixed-Use', '16', 'Entity', '', '', '', '', '', '', '', 'Pashan Holdings Ltd', '0921291299', '0900121288', 'info@phl.com', 'Pashan Pashan', 'Board Member', '0812-0200', 'all_uploads/008808109240d7e90fe30ca554ff8f3fnath_cert_owner.jpeg', 'all_uploads/008808109240d7e90fe30ca554ff8f3ftitle_deed.png', 'all_uploads/008808109240d7e90fe30ca554ff8f3fother_legal_document.jpeg', '<ul><li>Oceanic Views</li><li>24/7 Backup Generator</li><li>210 KV Solar System</li><li>Active Noise Cancellation Glass Windows and Doors</li></ul>', 'all_uploads/008808109240d7e90fe30ca554ff8f3fphoto_four.jpg', 'all_uploads/008808109240d7e90fe30ca554ff8f3fphoto_two.jpg', 'all_uploads/008808109240d7e90fe30ca554ff8f3fphoto_three.jpg', 'all_uploads/008808109240d7e90fe30ca554ff8f3fphoto_one.jpg', '2025, Aug 23 07:45:44', 'Confirmation'),
(4, 'KICC', '47', '289', '1418', 'High Rise', '20', '100', 'Mixed-Use', '16', 'Individual', 'Angela', 'Kamama', '92302990', '0712478500', '0724441200', 'info@angelakamam@gmail.com', '00200-10055', '', '', '', '', '', '', '', 'all_uploads/da65a6a4d9f57bd03fe990853593c1008d50d12d964e1e224e013e736ebac7e5ownership_cert.jpeg', 'all_uploads/da65a6a4d9f57bd03fe990853593c1008d50d12d964e1e224e013e736ebac7e5title_deed.png', 'all_uploads/da65a6a4d9f57bd03fe990853593c100212a9894b41f3c5f644b48fc9fd67db7nath_legal.jpeg', '<ul><li>Swimming Pool</li><li>Automatic Gate</li><li>High speed lifts</li><li>CCTV cameras</li></ul>', 'all_uploads/da65a6a4d9f57bd03fe990853593c1002069db07a1d15b011f132159ad4cecf5address.jpg', 'all_uploads/da65a6a4d9f57bd03fe990853593c100212a9894b41f3c5f644b48fc9fd67db7nath_img.jpeg', 'all_uploads/da65a6a4d9f57bd03fe990853593c1002069db07a1d15b011f132159ad4cecf5nath_img_1.jpeg', 'all_uploads/da65a6a4d9f57bd03fe990853593c1002069db07a1d15b011f132159ad4cecf5nath_img_1.jpeg', '2025, Aug 27 18:16:35', 'Confirmation');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

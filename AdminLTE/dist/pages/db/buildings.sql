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
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `building_id` int(255) NOT NULL,
  `building_name` varchar(255) NOT NULL,
  `county` varchar(255) NOT NULL,
  `constituency` varchar(255) NOT NULL,
  `ward` varchar(255) NOT NULL,
  `floor_number` int(255) NOT NULL,
  `units_number` int(255) NOT NULL,
  `building_type` varchar(11) NOT NULL,
  `ownership_info` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `country_code` varchar(10) NOT NULL,
  `phone_number` int(255) NOT NULL,
  `kra_pin` int(255) NOT NULL,
  `kra_attachment` varchar(11) NOT NULL,
  `identification_number` int(11) NOT NULL,
  `id_attachment` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `entity_name` varchar(255) NOT NULL,
  `entity_phone` int(255) NOT NULL,
  `entity_country_code` varchar(255) NOT NULL,
  `entity_email` varchar(255) NOT NULL,
  `bs_reg_no` varchar(255) NOT NULL,
  `attach_bs_reg_no` varchar(100) NOT NULL,
  `entity_kra_pin` int(255) NOT NULL,
  `entity_attach_kra_copy` varchar(100) NOT NULL,
  `entity_representative` varchar(255) NOT NULL,
  `entity_rep_role` varchar(255) NOT NULL,
  `title_deed_copy` varchar(100) DEFAULT NULL,
  `other_document_copy` varchar(100) DEFAULT NULL,
  `borehole_availability` varchar(10) DEFAULT NULL,
  `solar_availability` varchar(10) DEFAULT NULL,
  `solar_brand` varchar(255) DEFAULT NULL,
  `installation_company` varchar(255) DEFAULT NULL,
  `no_of_panels` int(11) DEFAULT NULL,
  `solar_primary_use` varchar(50) DEFAULT NULL,
  `parking_lot` varchar(10) DEFAULT NULL,
  `alarm_system` varchar(10) DEFAULT NULL,
  `elevators` varchar(10) DEFAULT NULL,
  `psds_accessibility` varchar(10) DEFAULT NULL,
  `cctv` varchar(10) DEFAULT NULL,
  `nca_approval` varchar(10) DEFAULT NULL,
  `nca_approval_no` varchar(50) DEFAULT NULL,
  `nca_approval_start_date` date DEFAULT NULL,
  `nca_approval_end_date` varchar(255) NOT NULL,
  `local_gov_approval` varchar(10) DEFAULT NULL,
  `local_gov_approval_no` varchar(50) DEFAULT NULL,
  `local_gov_approval_date` date DEFAULT NULL,
  `nema_approval` varchar(10) DEFAULT NULL,
  `nema_approval_no` varchar(50) DEFAULT NULL,
  `nema_approval_date` date DEFAULT NULL,
  `building_tax_pin` varchar(20) DEFAULT NULL,
  `insurance_cover` varchar(10) DEFAULT NULL,
  `insurance_policy` varchar(255) DEFAULT NULL,
  `insurance_provider` varchar(255) DEFAULT NULL,
  `policy_from_date` date DEFAULT NULL,
  `policy_until_date` date DEFAULT NULL,
  `front_view_photo` varchar(255) DEFAULT NULL,
  `rear_view_photo` varchar(255) DEFAULT NULL,
  `angle_view_photo` varchar(255) DEFAULT NULL,
  `interior_view_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `building_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`building_id`, `building_name`, `county`, `constituency`, `ward`, `floor_number`, `units_number`, `building_type`, `ownership_info`, `first_name`, `last_name`, `nationality`, `country_code`, `phone_number`, `kra_pin`, `kra_attachment`, `identification_number`, `id_attachment`, `email`, `entity_name`, `entity_phone`, `entity_country_code`, `entity_email`, `bs_reg_no`, `attach_bs_reg_no`, `entity_kra_pin`, `entity_attach_kra_copy`, `entity_representative`, `entity_rep_role`, `title_deed_copy`, `other_document_copy`, `borehole_availability`, `solar_availability`, `solar_brand`, `installation_company`, `no_of_panels`, `solar_primary_use`, `parking_lot`, `alarm_system`, `elevators`, `psds_accessibility`, `cctv`, `nca_approval`, `nca_approval_no`, `nca_approval_start_date`, `nca_approval_end_date`, `local_gov_approval`, `local_gov_approval_no`, `local_gov_approval_date`, `nema_approval`, `nema_approval_no`, `nema_approval_date`, `building_tax_pin`, `insurance_cover`, `insurance_policy`, `insurance_provider`, `policy_from_date`, `policy_until_date`, `front_view_photo`, `rear_view_photo`, `angle_view_photo`, `interior_view_photo`, `created_at`, `updated_at`, `building_number`) VALUES
(117, 'uwezo', 'Kericho', 'Belgut', 'Waldai', 4, 44, 'Residential', 'Individual', 'Terry', 'michael', 'Kenyan ', '+254', 0, 0, 'uploads/682', 37854789, 'uploads/682481b0ca1fe_Terry\'s National id.pdf', 'terry@gmail.com', '', 0, '', '', '', '', 0, '', '', '', NULL, NULL, 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/682481b0c9b55_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682481b0c9e50_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/682481b0c9f39_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682481b0ca074_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', '2025-05-14 11:42:40', '2025-05-14 11:42:40', 0),
(123, 'ebenezar', 'Kericho', 'Ainamoi', 'Kipchebor', 3, 43, 'Commercial', 'Individual', 'peter', 'mulamwa', 'kenyan', '+254', 0, 0, 'uploads/682', 37857885, 'uploads/6824879924691_Terry\'s National id.pdf', 'terry@gmail.com', '', 0, '', '', '', '', 0, '', '', '', NULL, NULL, 'No', 'No', '', '', 0, '', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/68248799240f1_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/6824879924354_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/6824879924443_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/68248799244ee_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', '2025-05-14 12:07:53', '2025-05-14 12:07:53', 0),
(124, 'Crown Z Towers', 'Narok', 'Kilgoris', 'Kilgoris Central', 9, 45, 'Residential', 'Entity', '', '', '', '', 0, 0, '', 0, '', '', 'fbxb', 0, '+254', 'fbxb@gmail.com', 'xdcvfv', 'uploads/682488801f36e_Terry\'s Title Deed.pdf', 0, 'uploads/682488801f408_Terry\'s KRA Pin.pdf', 'hi9', 'Board Member', NULL, NULL, 'Yes', 'No', '', '', 0, '', 'Yes', 'No', 'No', 'No', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/682488801ef01_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682488801f17c_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/682488801f22a_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682488801f2d9_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', '2025-05-14 12:11:44', '2025-05-14 12:11:44', 0),
(125, 'beba', 'Migori', 'Awendo', 'North Sakwa', 4, 54, 'Residential', 'Entity', '', '', '', '', 0, 0, '', 0, '', '', 'hrty', 0, '+254', 'ghdsc', 'xghf', 'uploads/682489fea4534_Terry\'s Title Deed.pdf', 0, 'uploads/682489fea45e0_Terry\'s KRA Pin.pdf', 'dgbxbh', 'Signatory', NULL, NULL, 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/682489fea4085_Terry\'s KRA Pin.pdf', 'uploads/682489fea431a_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682489fea43c8_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/682489fea4489_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', '2025-05-14 12:18:06', '2025-05-14 12:18:06', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

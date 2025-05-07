-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 03:21 PM
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
  `phone_number` int(255) NOT NULL,
  `kra_pin` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `entity_name` varchar(255) NOT NULL,
  `entity_phone` int(255) NOT NULL,
  `entity_email` varchar(255) NOT NULL,
  `entity_kra_pin` int(255) NOT NULL,
  `entity_representative` varchar(255) NOT NULL,
  `entity_rep_role` varchar(255) NOT NULL,
  `title_deed_copy` varchar(255) DEFAULT NULL,
  `other_document_copy` varchar(255) DEFAULT NULL,
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
  `nca_approval_date` date DEFAULT NULL,
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

INSERT INTO `buildings` (`building_id`, `building_name`, `county`, `constituency`, `ward`, `floor_number`, `units_number`, `building_type`, `ownership_info`, `first_name`, `last_name`, `phone_number`, `kra_pin`, `email`, `entity_name`, `entity_phone`, `entity_email`, `entity_kra_pin`, `entity_representative`, `entity_rep_role`, `title_deed_copy`, `other_document_copy`, `borehole_availability`, `solar_availability`, `solar_brand`, `installation_company`, `no_of_panels`, `solar_primary_use`, `parking_lot`, `alarm_system`, `elevators`, `psds_accessibility`, `cctv`, `nca_approval`, `nca_approval_no`, `nca_approval_date`, `local_gov_approval`, `local_gov_approval_no`, `local_gov_approval_date`, `nema_approval`, `nema_approval_no`, `nema_approval_date`, `building_tax_pin`, `insurance_cover`, `insurance_policy`, `insurance_provider`, `policy_from_date`, `policy_until_date`, `front_view_photo`, `rear_view_photo`, `angle_view_photo`, `interior_view_photo`, `created_at`, `updated_at`, `building_number`) VALUES
(86, 'DCF', 'Kitui', 'Kitui East', 'Mutito', 4, 33, 'Commercial', 'Individual', 'GVGB', 'DXDD', 752222222, 0, 'dff@gmail.com', '', 0, '', 0, '', '', NULL, NULL, 'Yes', 'No', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'PGBDUVFNVBNHF4', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/681b4979e6a2c_Capture.PNG', 'uploads/681b4979e6c81_page3.PNG', 'uploads/681b4979e6d17_zero rated.PNG', 'uploads/681b4979e6dc7_12.PNG', '2025-05-07 11:52:25', '2025-05-07 11:52:25', 0),
(87, 'HENJHE', 'Tana River', 'Bura', 'Chewele', 4, 44, 'Commercial', 'Individual', 'SGVFV', 'DVFB', 744444444, 0, 'fcgye@gmail.com', '', 0, '', 0, '', '', NULL, NULL, 'Yes', 'Yes', 'fvsdgbh', 'sxgdcg', 6, 'Water Heating', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'sfvgnhy', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/681b4acea9eb2_14.PNG', 'uploads/681b4aceaa078_page3.PNG', 'uploads/681b4aceaa109_exempted.PNG', 'uploads/681b4aceaa1c1_exclusive.PNG', '2025-05-07 11:58:06', '2025-05-07 11:58:06', 0),
(89, 'EBENEZER', 'Wajir', 'Wajir North', 'Gurar', 4, 6, 'Commercial', 'Individual', 'HYTH', 'THT', 744444444, 0, 'df@gmail.com', '', 0, '', 0, '', '', NULL, NULL, 'Yes', 'Yes', 'dcfgbv', 'ghbnj', 4, 'Lighting', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'NCA-1234567-2025', '0000-00-00', 'Yes', '4356', '2025-05-15', 'Yes', 'NEMA/EIA/PS/1234', '2025-04-29', 'P123456789B', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/681b4f3280088_14.PNG', 'uploads/681b4f32803b2_zero rated.PNG', 'uploads/681b4f328047b_page 4.PNG', 'uploads/681b4f328051c_page2.PNG', '2025-05-07 12:16:50', '2025-05-07 12:16:50', 0);

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
  MODIFY `building_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

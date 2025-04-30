-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 04:32 PM
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
(64, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/6809f48b378e3_14.PNG', 'uploads/6809f48b37af9_page 4.PNG', 'uploads/6809f48b37b82_Thy instructions..png', 'uploads/6809f48b37db5_page2.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09', 0),
(76, 'Crown Z Towers', 'Kiambu', 'Ruiru', 'Gatongora', 10, 60, 'Commercial', 'Individual', 'Maina', 'Mwangi', 745897896, 0, 'maina@gmail.com', '', 0, '', 0, '', '', NULL, NULL, 'Yes', 'Yes', 'Devki', 'Devki Installers', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '30589', '0000-00-00', 'Yes', '4545', '2025-04-29', 'Yes', '8986', '2025-04-28', 'P00124H', 'Yes', 'Fire Insurance', 'CIC GENERAL', '2024-04-30', '2025-04-30', 'uploads/6811f9423598f_14.PNG', 'uploads/6811f94235b9c_page 4.PNG', 'uploads/6811f94235c51_page1.PNG', 'uploads/6811f94235cda_Thy instructions..png', '2025-04-30 10:19:46', '2025-04-30 10:19:46', 0),
(77, 'CROWN M', 'Kiambu', 'Thika Town', 'Township', 6, 8, 'Residential', 'Entity', '', '', 0, 0, '', 'JENGA', 748968547, 'jenga@gmail.com', 0, '', 'Founder', NULL, NULL, 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, '2025-04-30 10:24:12', '2025-04-30 10:24:12', 0);

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
  MODIFY `building_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

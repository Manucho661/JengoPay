-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 03:59 PM
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
-- Table structure for table `building_identification`
--

CREATE TABLE `building_identification` (
  `id` int(255) NOT NULL,
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
  `interior_view_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `building_identification`
--

INSERT INTO `building_identification` (`id`, `building_name`, `county`, `constituency`, `ward`, `floor_number`, `units_number`, `building_type`, `ownership_info`, `first_name`, `last_name`, `phone_number`, `kra_pin`, `email`, `entity_name`, `entity_phone`, `entity_email`, `entity_kra_pin`, `entity_representative`, `entity_rep_role`, `title_deed_copy`, `other_document_copy`, `borehole_availability`, `solar_availability`, `solar_brand`, `installation_company`, `no_of_panels`, `solar_primary_use`, `parking_lot`, `alarm_system`, `elevators`, `psds_accessibility`, `cctv`, `nca_approval`, `nca_approval_no`, `nca_approval_date`, `local_gov_approval`, `local_gov_approval_no`, `local_gov_approval_date`, `nema_approval`, `nema_approval_no`, `nema_approval_date`, `building_tax_pin`, `insurance_cover`, `insurance_policy`, `insurance_provider`, `policy_from_date`, `policy_until_date`, `front_view_photo`, `rear_view_photo`, `angle_view_photo`, `interior_view_photo`) VALUES
(5, 'XYZ', 'Meru', 'Igembe South', 'Maua', 9, 2, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'XYZ', 'Meru', 'Igembe South', 'Maua', 9, 2, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'ty', 'Meru', 'Igembe South', 'Maua', 4, 6, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'ty', 'Meru', 'Igembe South', 'Maua', 4, 6, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'rty', 'Mombasa', 'Changamwe', 'Port Reitz', 5, 7, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'rty', 'Mombasa', 'Changamwe', 'Port Reitz', 5, 7, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '', 'Meru', 'Igembe South', 'Athiru Gaiti', 7, 9, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '', '', '', '', 0, 0, '', 'Individual', 'rrr', 'rrrrr', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, '', 'Meru', 'Igembe South', 'Athiru Gaiti', 7, 9, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, '', '', '', '', 0, 0, '', 'Individual', 'rrr', 'rrrrr', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'ttt', 'Isiolo', 'Isiolo North', 'Wabera', 4, 7, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, '', '', '', '', 0, 0, '', 'Individual', 'huty', 'wert', 745896852, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'cert', 'Kwale', 'Msambweni', 'Ukunda', 1, 2, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '', '', '', '', 0, 0, '', 'Individual', 'rere', 'wer', 714589878, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'cert', 'Kwale', 'Msambweni', 'Ukunda', 1, 2, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '', '', '', '', 0, 0, '', 'Individual', 'rere', 'wer', 714589878, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'ert', 'Nyeri', 'Tetu', 'Dedan Kimathi', 5, 6, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, '', '', '', '', 0, 0, '', 'Individual', 'fred', '', 745859858, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'kuyt', 'Lamu', 'Lamu East', 'Faza', 5, 7, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, '', '', '', '', 0, 0, '', 'Individual', 'jju', 'lki', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, '', 'Marsabit', 'Moyale', 'Sololo', 5, 2, 'Residential', '', '', '', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, '', '', '', '', 0, 0, '', 'Individual', 'jin', 'kin', 0, 0, 'kin@gmail.com', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, '', 'Marsabit', 'Moyale', 'Sololo', 5, 2, 'Residential', 'Individual', 'jin', 'kin', 0, 0, 'kin@gmail.com', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'tyu', 'Embu', 'Manyatta', 'Kithimu', 5, 5, 'Residential', 'Individual', 'gert', 'tern', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'tyu', 'Embu', 'Manyatta', 'Kithimu', 5, 5, 'Residential', 'Individual', 'gert', 'tern', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'tyu', 'Embu', 'Manyatta', 'Kithimu', 5, 5, 'Residential', 'Individual', 'gert', 'tern', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'yyt', 'Marsabit', 'Moyale', 'Obbu', 4, 0, '', 'Individual', 'yerrttt', 'yyyt', 0, 0, '', '', 0, '', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'yyt', 'Marsabit', 'Moyale', 'Obbu', 4, 0, '', 'Individual', 'yerrttt', 'yyyt', 0, 0, '', '', 0, '', 0, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'ttty', 'Isiolo', 'Isiolo South', 'Kinna', 4, 3, 'Commercial', 'Individual', 'gyyy', '', 0, 0, '', '', 0, '', 0, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'ttty', 'Isiolo', 'Isiolo South', 'Kinna', 4, 3, 'Commercial', 'Individual', 'gyyy', '', 0, 0, '', '', 0, '', 0, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'ttty', 'Isiolo', 'Isiolo South', 'Kinna', 4, 3, 'Commercial', 'Individual', 'gyyy', '', 0, 0, '', '', 0, '', 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'ttt', 'Embu', 'Runyenjes', 'Gaturi North', 5, 0, '', 'Individual', 'rttuuu', '', 745896325, 0, '', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'No', 'Yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'ttt', 'Embu', 'Runyenjes', 'Gaturi North', 5, 0, '', 'Individual', 'rttuuu', '', 745896325, 0, '', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'No', '', '0000-00-00', 'No', '', '0000-00-00', 'No', '', '0000-00-00', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'erw', 'Taita Taveta', 'Taveta', 'Chala', 2, 4, 'Commercial', 'Individual', 'rachy', 'best', 0, 0, 'best@gmail.com', '', 0, '', 0, '', '', '', '', 'No', 'Yes', 'yryjnj', 'mjyhug', 3, 'Lighting', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'erw', 'Taita Taveta', 'Taveta', 'Chala', 2, 4, 'Commercial', 'Individual', 'rachy', 'best', 0, 0, 'best@gmail.com', '', 0, '', 0, '', '', '', '', 'No', 'Yes', 'yryjnj', 'mjyhug', 3, 'Lighting', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL),
(46, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL),
(47, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL),
(48, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL),
(49, 'Ebenezer Apartment', 'Mandera', 'Banissa', 'Derkhale', 5, 40, 'Residential', 'Individual', 'mike', 'macharia', 745896878, 0, 'mikemach@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A128990HD908', 'No', '', '', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(50, 'Ebenezer Apartment', 'Mandera', 'Banissa', 'Derkhale', 5, 40, 'Residential', 'Individual', 'mike', 'macharia', 745896878, 0, 'mikemach@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A128990HD908', 'No', '', '', '0000-00-00', NULL, 'uploads/6808ed9b7a762_page 4.PNG', 'uploads/6808ed9b7aa2c_page1.PNG', 'uploads/6808ed9b7ab47_page3.PNG', 'uploads/6808ed9b7ad92_Thy instructions..png'),
(51, 'EBENY', 'Tharaka-Nithi', 'Tharaka', 'Marimanti', 4, 3, 'Industrial', 'Entity', '', '', 0, 0, '', 'RUITU', 745895896, 'RUITU@gmail.com', 0, '', 'CEO', '', '', 'No', 'No', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'DFUGUKYUK54466', 'No', '', '', '0000-00-00', NULL, 'uploads/6808ef2331c8c_Capture.PNG', 'uploads/6808ef2331e5f_page3.PNG', 'uploads/6808ef2331ee2_page3.PNG', 'uploads/6808ef2331f57_Capture.PNG'),
(52, 'EBENY', 'Tharaka-Nithi', 'Tharaka', 'Marimanti', 4, 3, 'Industrial', 'Entity', '', '', 0, 0, '', 'RUITU', 745895896, 'RUITU@gmail.com', 0, '', 'CEO', '', '', 'No', 'No', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'DFUGUKYUK54466', 'No', '', '', '0000-00-00', NULL, 'uploads/6808ef62da73a_Capture.PNG', 'uploads/6808ef62da90d_page3.PNG', 'uploads/6808ef62da9a6_page3.PNG', 'uploads/6808ef62daa3c_Capture.PNG'),
(53, 'ERRWEF', 'Kitui', 'Kitui East', 'Chuluni', 4, 4, 'Residential', 'Entity', '', '', 0, 0, '', 'ERWRG', 745896852, 'wer@gmail.com', 0, 'john', 'CEO', '', '', 'No', 'No', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'b45555856yt', 'No', '', '', '0000-00-00', NULL, 'uploads/6808f020851df_Capture.PNG', 'uploads/6808f020853e7_page1.PNG', 'uploads/6808f02085491_page1.PNG', 'uploads/6808f02085524_page2.PNG'),
(54, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f13c50808_14.PNG', 'uploads/6808f13c510e4_page 4.PNG', 'uploads/6808f13c51401_page2.PNG', 'uploads/6808f13c5162c_Thy instructions..png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `building_identification`
--
ALTER TABLE `building_identification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `building_identification`
--
ALTER TABLE `building_identification`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 09:05 AM
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
  `interior_view_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `building_identification`
--

INSERT INTO `building_identification` (`id`, `building_name`, `county`, `constituency`, `ward`, `floor_number`, `units_number`, `building_type`, `ownership_info`, `first_name`, `last_name`, `phone_number`, `kra_pin`, `email`, `entity_name`, `entity_phone`, `entity_email`, `entity_kra_pin`, `entity_representative`, `entity_rep_role`, `title_deed_copy`, `other_document_copy`, `borehole_availability`, `solar_availability`, `solar_brand`, `installation_company`, `no_of_panels`, `solar_primary_use`, `parking_lot`, `alarm_system`, `elevators`, `psds_accessibility`, `cctv`, `nca_approval`, `nca_approval_no`, `nca_approval_date`, `local_gov_approval`, `local_gov_approval_no`, `local_gov_approval_date`, `nema_approval`, `nema_approval_no`, `nema_approval_date`, `building_tax_pin`, `insurance_cover`, `insurance_policy`, `insurance_provider`, `policy_from_date`, `policy_until_date`, `front_view_photo`, `rear_view_photo`, `angle_view_photo`, `interior_view_photo`, `created_at`, `updated_at`) VALUES
(41, 'ttt', 'Embu', 'Runyenjes', 'Gaturi North', 5, 0, '', 'Individual', 'rttuuu', '', 745896325, 0, '', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'No', 'Yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(42, 'ttt', 'Embu', 'Runyenjes', 'Gaturi North', 5, 0, '', 'Individual', 'rttuuu', '', 745896325, 0, '', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'No', '', '0000-00-00', 'No', '', '0000-00-00', 'No', '', '0000-00-00', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(43, 'erw', 'Taita Taveta', 'Taveta', 'Chala', 2, 4, 'Commercial', 'Individual', 'rachy', 'best', 0, 0, 'best@gmail.com', '', 0, '', 0, '', '', '', '', 'No', 'Yes', 'yryjnj', 'mjyhug', 3, 'Lighting', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(44, 'erw', 'Taita Taveta', 'Taveta', 'Chala', 2, 4, 'Commercial', 'Individual', 'rachy', 'best', 0, 0, 'best@gmail.com', '', 0, '', 0, '', '', '', '', 'No', 'Yes', 'yryjnj', 'mjyhug', 3, 'Lighting', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', 'Yes', '', '0000-00-00', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(45, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(46, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(47, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(48, 'uuu', 'Busia', 'Butula', 'Marachi West', 7, 8, 'Residential', 'Individual', 'washua', '', 0, 0, 'washua@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '445558aa', 'Yes', 'cic', '', '2025-08-23', NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(49, 'Ebenezer Apartment', 'Mandera', 'Banissa', 'Derkhale', 5, 40, 'Residential', 'Individual', 'mike', 'macharia', 745896878, 0, 'mikemach@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A128990HD908', 'No', '', '', '0000-00-00', NULL, NULL, NULL, NULL, NULL, '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(50, 'Ebenezer Apartment', 'Mandera', 'Banissa', 'Derkhale', 5, 40, 'Residential', 'Individual', 'mike', 'macharia', 745896878, 0, 'mikemach@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A128990HD908', 'No', '', '', '0000-00-00', NULL, 'uploads/6808ed9b7a762_page 4.PNG', 'uploads/6808ed9b7aa2c_page1.PNG', 'uploads/6808ed9b7ab47_page3.PNG', 'uploads/6808ed9b7ad92_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(51, 'EBENY', 'Tharaka-Nithi', 'Tharaka', 'Marimanti', 4, 3, 'Industrial', 'Entity', '', '', 0, 0, '', 'RUITU', 745895896, 'RUITU@gmail.com', 0, '', 'CEO', '', '', 'No', 'No', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'DFUGUKYUK54466', 'No', '', '', '0000-00-00', NULL, 'uploads/6808ef2331c8c_Capture.PNG', 'uploads/6808ef2331e5f_page3.PNG', 'uploads/6808ef2331ee2_page3.PNG', 'uploads/6808ef2331f57_Capture.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(52, 'EBENY', 'Tharaka-Nithi', 'Tharaka', 'Marimanti', 4, 3, 'Industrial', 'Entity', '', '', 0, 0, '', 'RUITU', 745895896, 'RUITU@gmail.com', 0, '', 'CEO', '', '', 'No', 'No', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'DFUGUKYUK54466', 'No', '', '', '0000-00-00', NULL, 'uploads/6808ef62da73a_Capture.PNG', 'uploads/6808ef62da90d_page3.PNG', 'uploads/6808ef62da9a6_page3.PNG', 'uploads/6808ef62daa3c_Capture.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(53, 'ERRWEF', 'Kitui', 'Kitui East', 'Chuluni', 4, 4, 'Residential', 'Entity', '', '', 0, 0, '', 'ERWRG', 745896852, 'wer@gmail.com', 0, 'john', 'CEO', '', '', 'No', 'No', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'b45555856yt', 'No', '', '', '0000-00-00', NULL, 'uploads/6808f020851df_Capture.PNG', 'uploads/6808f020853e7_page1.PNG', 'uploads/6808f02085491_page1.PNG', 'uploads/6808f02085524_page2.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(54, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f13c50808_14.PNG', 'uploads/6808f13c510e4_page 4.PNG', 'uploads/6808f13c51401_page2.PNG', 'uploads/6808f13c5162c_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(55, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f63dd9b2a_14.PNG', 'uploads/6808f63dd9ce1_page 4.PNG', 'uploads/6808f63dd9d70_page2.PNG', 'uploads/6808f63dd9dee_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(56, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f66b05eba_14.PNG', 'uploads/6808f66b062c4_page 4.PNG', 'uploads/6808f66b06358_page2.PNG', 'uploads/6808f66b0643e_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(57, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f80c92886_14.PNG', 'uploads/6808f80c92a42_page 4.PNG', 'uploads/6808f80c92ad0_page2.PNG', 'uploads/6808f80c92b4a_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(58, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f83319e55_14.PNG', 'uploads/6808f8331a12e_page 4.PNG', 'uploads/6808f8331a305_page2.PNG', 'uploads/6808f8331a398_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(59, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f90e7d3ad_14.PNG', 'uploads/6808f90e7d915_page 4.PNG', 'uploads/6808f90e7dab1_page2.PNG', 'uploads/6808f90e7dc71_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(60, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f9304b962_14.PNG', 'uploads/6808f9304bb67_page 4.PNG', 'uploads/6808f9304be68_page2.PNG', 'uploads/6808f9304bf5e_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(61, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f94f481f6_14.PNG', 'uploads/6808f94f48352_page 4.PNG', 'uploads/6808f94f48421_page2.PNG', 'uploads/6808f94f484fb_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(62, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808f9f8e50eb_14.PNG', 'uploads/6808f9f8e52f9_page 4.PNG', 'uploads/6808f9f8e5723_page2.PNG', 'uploads/6808f9f8e58b0_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(63, 'bahati', 'Kwale', 'Msambweni', 'Gombato Bongwe', 5, 45, 'Residential', 'Individual', 'peter', 'mwangi', 748986858, 0, 'peter@gmail.com', '', 0, '', 0, '', '', '', '', 'Yes', 'Yes', 'devki', 'devki', 5, 'Water Heating', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '20', '0000-00-00', 'Yes', '40740', '2025-04-26', 'Yes', '58', '2025-04-20', 'AQWERTY5689', 'Yes', 'CIC', '', '2025-04-30', NULL, 'uploads/6808fa2bd7586_14.PNG', 'uploads/6808fa2bd77d5_page 4.PNG', 'uploads/6808fa2bd788c_page2.PNG', 'uploads/6808fa2bd7913_Thy instructions..png', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(64, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/6809f48b378e3_14.PNG', 'uploads/6809f48b37af9_page 4.PNG', 'uploads/6809f48b37b82_Thy instructions..png', 'uploads/6809f48b37db5_page2.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(65, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/6809f537ccf51_14.PNG', 'uploads/6809f537cfb8c_page 4.PNG', 'uploads/6809f537cfc22_Thy instructions..png', 'uploads/6809f537cfd3e_page2.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(66, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/6809f54de4ed6_14.PNG', 'uploads/6809f54de50e8_page 4.PNG', 'uploads/6809f54de5185_Thy instructions..png', 'uploads/6809f54de520d_page2.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(67, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/6809f8169807b_14.PNG', 'uploads/6809f8169836c_page 4.PNG', 'uploads/6809f81698447_Thy instructions..png', 'uploads/6809f81698505_page2.PNG', '2025-04-24 08:40:09', '2025-04-24 08:40:09'),
(68, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/680a047813526_14.PNG', 'uploads/680a0478138f1_page 4.PNG', 'uploads/680a047813a8e_Thy instructions..png', 'uploads/680a047813b46_page2.PNG', '2025-04-24 09:29:28', '2025-04-24 09:29:28'),
(69, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/680a04cbe90da_14.PNG', 'uploads/680a04cbe9331_page 4.PNG', 'uploads/680a04cbe93d0_Thy instructions..png', 'uploads/680a04cbe9460_page2.PNG', '2025-04-24 09:30:51', '2025-04-24 09:30:51'),
(70, 'RIBA APARTMENT', 'Meru', 'North Imenti', 'Municipality', 5, 5, 'Residential', 'Individual', 'michy', 'city', 745895896, 0, 'mich@gmail.com', '', 0, '', 0, '', '', '', '', 'No', '', '', '', 0, '', 'No', 'No', 'No', 'No', 'No', 'No', '', '0000-00-00', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', 'A099799B345', 'No', 'MEDICAL', 'CIC', '2025-03-22', '2025-04-22', 'uploads/680a1463a9967_14.PNG', 'uploads/680a1463a9cfc_page 4.PNG', 'uploads/680a1463a9dd3_Thy instructions..png', 'uploads/680a1463a9f1c_page2.PNG', '2025-04-24 10:37:23', '2025-04-24 10:37:23');

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

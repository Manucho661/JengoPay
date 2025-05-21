-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 03:48 PM
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
(127, 'wawezo', 'Tharaka-Nithi', 'Chuka/Igambang\'ombe', 'Igambang\'ombe', 5, 67, 'Residential', 'Individual', 'hyth', 'fvgn', 'kenyan', '+254', 0, 0, 'uploads/682', 54533333, 'uploads/6825ab5e1f851_Terry\'s National id.pdf', 'df@gmail.com', '', 0, '', '', '', '', 0, '', '', '', NULL, NULL, 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'ghmj', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/6825ab5e1f3dd_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/6825ab5e1f5a7_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/6825ab5e1f669_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/6825ab5e1f73c_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', '2025-05-15 08:52:46', '2025-05-15 08:52:46', 0),
(128, 'beba', 'Mandera', 'Mandera West', 'Takaba South', 5, 65, 'Residential', 'Individual', 'ffgnh', 'gfhfgv', 'kenyan', '+254', 0, 0, 'uploads/682', 2147483647, 'uploads/6825b14092e88_Terry\'s National id.pdf', 'fsd@gmail.com', '', 0, '', '', '', '', 0, '', '', '', 'uploads/6825b140926f1_Terry\'s Title Deed.pdf', 'uploads/6825b14092915_Terry\'s KRA Pin.pdf', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/6825b140929ad_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/6825b14092a44_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/6825b14092afa_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/6825b14092be0_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', '2025-05-15 09:17:52', '2025-05-15 09:17:52', 0),
(129, 'fbhg', 'Kwale', 'Msambweni', 'Ukunda', 8, 87, 'Residential', 'Individual', 'fvbf', 'hiki', 'ugandan', '+254', 0, 0, 'uploads/682', 35784587, 'uploads/682dbc149f29b_Terry\'s National id.pdf', 'gsuja@gmail.com', '', 0, '', '', '', '', 0, '', '', '', 'uploads/682dbc149a65a_Terry\'s Title Deed.pdf', 'uploads/682dbc149a82c_Dreamqeja NCA Certificate.pdf', 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'No', 'No', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/682dbc149a8bf_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682dbc149a947_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/682dbc149aa44_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682dbc149ab4e_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', '2025-05-21 11:42:12', '2025-05-21 11:42:12', 0),
(130, 'frbg', 'Kwale', 'Lunga Lunga', 'Pongwe/Kikoneni', 4, 43, 'Residential', 'Individual', 'dfvfgb', 'bh', 'fdbgbh', '+254', 0, 0, 'uploads/682', 2147483647, 'uploads/682dbebc208cd_Terry\'s National id.pdf', 'gsg@gmail.com', '', 0, '', '', '', '', 0, '', '', '', 'uploads/682dbebc1bc65_Terry\'s Title Deed.pdf', NULL, 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'No', 'No', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/682dbebc1bf32_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/682dbebc1bfc7_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/682dbebc1c052_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682dbebc20635_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', '2025-05-21 11:53:32', '2025-05-21 11:53:32', 0),
(131, 'fvhn', 'Laikipia', 'Laikipia East', 'Tigithi', 4, 43, 'Commercial', 'Individual', 'xdgvf', 'xfbxb', 'dfgb', '+254', 0, 0, 'uploads/682', 2147483647, 'uploads/682dbf95e953d_Terry\'s National id.pdf', 'hy@gmail.com', '', 0, '', '', '', '', 0, '', '', '', 'uploads/682dbf95e90a2_Terry\'s Title Deed.pdf', NULL, 'Yes', 'No', '', '', 0, '', 'Yes', 'No', 'No', 'No', 'Yes', 'No', '', '0000-00-00', '', 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/682dbf95e9274_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', 'uploads/682dbf95e9306_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682dbf95e93a1_WhatsApp Image 2025-05-12 at 13.33.25.jpeg', 'uploads/682dbf95e942b_WhatsApp Image 2025-05-12 at 13.33.26.jpeg', '2025-05-21 11:57:09', '2025-05-21 11:57:09', 0);

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
  MODIFY `building_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

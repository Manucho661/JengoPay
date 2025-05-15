-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 10:24 AM
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
  `id_attachment` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `entity_name` varchar(255) NOT NULL,
  `entity_phone` int(255) NOT NULL,
  `entity_country_code` varchar(255) NOT NULL,
  `entity_email` varchar(255) NOT NULL,
  `bs_reg_no` int(255) NOT NULL,
  `attach_bs_reg_no` varchar(100) NOT NULL,
  `entity_kra_pin` int(255) NOT NULL,
  `entity_attach_kra_copy` varchar(100) NOT NULL,
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
  `nca_approval_start_date` date NOT NULL,
  `nca_approval_end_date` date NOT NULL,
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

INSERT INTO `buildings` (`building_id`, `building_name`, `county`, `constituency`, `ward`, `floor_number`, `units_number`, `building_type`, `ownership_info`, `first_name`, `last_name`, `nationality`, `country_code`, `phone_number`, `kra_pin`, `kra_attachment`, `identification_number`, `id_attachment`, `email`, `entity_name`, `entity_phone`, `entity_country_code`, `entity_email`, `bs_reg_no`, `attach_bs_reg_no`, `entity_kra_pin`, `entity_attach_kra_copy`, `entity_representative`, `entity_rep_role`, `title_deed_copy`, `other_document_copy`, `borehole_availability`, `solar_availability`, `solar_brand`, `installation_company`, `no_of_panels`, `solar_primary_use`, `parking_lot`, `alarm_system`, `elevators`, `psds_accessibility`, `cctv`, `nca_approval`, `nca_approval_no`, `nca_approval_start_date`, `nca_approval_end_date`, `nca_approval_date`, `local_gov_approval`, `local_gov_approval_no`, `local_gov_approval_date`, `nema_approval`, `nema_approval_no`, `nema_approval_date`, `building_tax_pin`, `insurance_cover`, `insurance_policy`, `insurance_provider`, `policy_from_date`, `policy_until_date`, `front_view_photo`, `rear_view_photo`, `angle_view_photo`, `interior_view_photo`, `created_at`, `updated_at`, `building_number`) VALUES
(83, 'Amazing Towers', 'Garissa', 'Garissa Township', 'Waberi', 5, 40, 'Residential', 'Individual', 'Terry', 'Angel', 'Kenyan', '+254', 0, 0, 'uploads/682', 37863365, 0, 'terry@gmail.com', '', 0, '', '', 0, '', 0, '', '', '', NULL, NULL, 'Yes', 'No', '', '', 0, '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '', '0000-00-00', '0000-00-00', NULL, 'Yes', '', '0000-00-00', 'No', '', '0000-00-00', '', 'No', '', '', '0000-00-00', '0000-00-00', 'uploads/6824b6cd27bda_WhatsApp Image 2025-05-12 at 13.33.26_9f5ef45c.jpg', 'uploads/6824b6cd28300_WhatsApp Image 2025-05-12 at 13.33.26_51929f1e.jpg', 'uploads/6824b6cd2871b_WhatsApp Image 2025-05-12 at 13.33.25_28c6aa12.jpg', 'uploads/6824b6cd28c97_WhatsApp Image 2025-05-12 at 13.33.25_28c6aa12.jpg', '2025-05-14 15:29:17', '2025-05-14 15:29:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `communication`
--

CREATE TABLE `communication` (
  `thread_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `subject` varchar(100) NOT NULL,
  `files` varchar(100) NOT NULL,
  `unit_id` varchar(100) NOT NULL,
  `tenant` varchar(100) NOT NULL,
  `building_name` varchar(155) NOT NULL,
  `building_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communication`
--

INSERT INTO `communication` (`thread_id`, `sender_id`, `receiver_id`, `start_date`, `end_date`, `subject`, `files`, `unit_id`, `tenant`, `building_name`, `building_id`, `title`, `email`, `recipient`, `created_at`, `updated_at`) VALUES
(195, NULL, NULL, NULL, NULL, '', '[\"uploads\\/68248fbf18080_Terry\'s Title Deed.pdf\"]', '', '', '117', 0, 'yebo', '', '', '2025-05-14 15:42:39', '2025-05-14 15:42:39'),
(196, NULL, NULL, NULL, NULL, '', '[\"uploads\\/6824c337f25bb_Terry\'s National id[1].pdf\"]', '', '', '83', 0, 'Vacation By Tomorrow', '', '', '2025-05-14 19:22:15', '2025-05-14 19:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(100) NOT NULL DEFAULT 'ID COPY',
  `file_path` varchar(500) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `tenant_id`, `file_name`, `file_path`, `uploaded_at`) VALUES
(4, 39011839, 'ID COPY', 'id_copy_6824c09e935b9.pdf', '2025-05-14 16:11:10'),
(5, 39011840, 'ID COPY', 'id_copy_6824c3fc9ad30.png', '2025-05-14 16:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `inspections`
--

CREATE TABLE `inspections` (
  `id` int(11) NOT NULL,
  `inspection_number` varchar(50) NOT NULL,
  `building_name` varchar(100) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `inspection_type` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspections`
--

INSERT INTO `inspections` (`id`, `inspection_number`, `building_name`, `unit_name`, `inspection_type`, `date`, `created_at`) VALUES
(4, '86', 'Manucho', '', 'B14', '2025-05-13', '2025-05-07 13:06:43'),
(5, '4346', 'Manucho', '', 'B14', '2025-05-12', '2025-05-07 13:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `thread_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `content`, `thread_id`, `timestamp`, `is_read`) VALUES
(99, 'landlord', 'working code', 192, '2025-05-13 12:47:20', 1),
(100, 'landlord', 'done', 192, '2025-05-13 13:06:47', 1),
(101, 'landlord', 'yees', 192, '2025-05-13 13:07:56', 1),
(102, 'landlord', 'cfb', 192, '2025-05-13 13:19:38', 1),
(103, 'landlord', 'gnhy', 192, '2025-05-13 13:19:43', 1),
(104, 'landlord', 'remember', 192, '2025-05-13 13:20:54', 1),
(105, 'landlord', 'noiw', 192, '2025-05-13 13:25:32', 1),
(106, 'landlord', 'boom', 192, '2025-05-13 13:34:16', 1),
(107, 'landlord', 'bravo', 192, '2025-05-13 13:44:22', 1),
(108, 'landlord', 'gnhgfn', 193, '2025-05-13 13:52:59', 1),
(109, 'landlord', 'dv', 192, '2025-05-13 13:54:39', 1),
(110, 'landlord', 'dvc', 193, '2025-05-13 13:54:54', 1),
(111, 'landlord', 'dvcv', 193, '2025-05-13 13:54:59', 1),
(112, 'landlord', 'fbv', 194, '2025-05-13 13:55:47', 1),
(113, 'landlord', 'frhtgb', 192, '2025-05-13 14:25:45', 1),
(114, 'landlord', 'drgffg', 192, '2025-05-13 14:59:53', 1),
(115, 'landlord', 'dgbfr', 194, '2025-05-13 15:00:10', 1),
(116, 'landlord', 'frgbfgb', 193, '2025-05-13 15:00:16', 1),
(117, 'landlord', 'done ', 195, '2025-05-14 12:42:39', 1),
(118, 'landlord', 'Greetings,\r\nPlease ensure you vacate by tomorrow.\r\n\r\nThankyou.', 196, '2025-05-14 16:22:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `message_files`
--

CREATE TABLE `message_files` (
  `thread_id` int(11) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_files`
--

INSERT INTO `message_files` (`thread_id`, `message_id`, `file_path`) VALUES
(150, NULL, 'uploads/68249cf4298aa_kenyaNationalID-circle.png'),
(192, NULL, 'uploads/68233f5886944_6821c8f3caad7_Tenants.pdf'),
(193, NULL, 'uploads/68234ebb34bb5_AdminLTE  Dashboard v2 (23).pdf'),
(194, NULL, 'uploads/68234f631ac80_6821e769bf9b6_AdminLTE  Dashboard v2 (13).pdf'),
(195, NULL, 'uploads/68248fbf18080_Terry\'s Title Deed.pdf'),
(196, NULL, 'uploads/6824c337f25bb_Terry\'s National id[1].pdf');

-- --------------------------------------------------------

--
-- Table structure for table `meter_readings`
--

CREATE TABLE `meter_readings` (
  `id` int(11) NOT NULL,
  `reading_date` date NOT NULL,
  `unit_number` varchar(255) NOT NULL DEFAULT current_timestamp(),
  `meter_type` varchar(255) NOT NULL,
  `previous_reading` int(255) NOT NULL,
  `current_reading` int(255) NOT NULL,
  `consumption_units` int(255) NOT NULL,
  `building_id` int(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meter_readings`
--

INSERT INTO `meter_readings` (`id`, `reading_date`, `unit_number`, `meter_type`, `previous_reading`, `current_reading`, `consumption_units`, `building_id`, `created_at`, `updated_at`) VALUES
(37, '2025-04-28', 'C41', 'Water', 25, 27, 2, 0, '2025-04-30 07:43:30', '2025-04-30 07:43:30'),
(40, '2025-04-30', 'C9', 'Water', 30, 40, 10, 0, '2025-04-30 15:04:21', '2025-04-30 15:04:21'),
(41, '2025-05-08', 'B25', 'Water', 45, 55, 10, 0, '2025-05-07 13:33:26', '2025-05-07 13:33:26'),
(42, '2025-05-02', 'C9', 'Water', 40, 55, 15, 0, '2025-05-07 16:28:03', '2025-05-07 16:28:03'),
(43, '2025-05-01', 'B65', 'Water', 10, 30, 20, 83, '2025-05-14 15:36:02', '2025-05-14 15:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(10) UNSIGNED NOT NULL,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `license_number` varchar(50) DEFAULT 'N60782E',
  `weight` decimal(5,2) DEFAULT 60.20,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `tenant_id`, `pet_name`, `license_number`, `weight`, `created_at`, `updated_at`) VALUES
(42, 39011839, 'Cat', 'N60782E', 60.20, '2025-05-14 16:11:10', '2025-05-14 16:11:10'),
(43, 39011839, 'Parrot', 'N60782E', 60.20, '2025-05-14 16:11:10', '2025-05-14 16:11:10'),
(44, 39011840, 'Cat', 'N60782E', 60.20, '2025-05-14 16:25:32', '2025-05-14 16:25:32'),
(45, 39011840, 'Snake', 'N60782E', 60.20, '2025-05-14 16:25:32', '2025-05-14 16:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `residence` varchar(50) DEFAULT NULL,
  `id_no` int(11) DEFAULT NULL,
  `unit` varchar(11) DEFAULT NULL,
  `income_source` varchar(100) NOT NULL DEFAULT 'Business',
  `work_place` varchar(100) NOT NULL DEFAULT 'Business',
  `job_title` varchar(100) NOT NULL DEFAULT 'Business',
  `rent_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive','evicted') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `user_id`, `phone_number`, `residence`, `id_no`, `unit`, `income_source`, `work_place`, `job_title`, `rent_amount`, `status`, `created_at`, `updated_at`) VALUES
(39011839, 204, '0757414721', 'Pink House', 38011790, 'C219', 'Employment', 'Biccount', 'Software Engineer', NULL, 'active', '2025-05-14 16:11:10', '2025-05-14 16:11:10'),
(39011840, 205, '0757414721', 'Manucho', 38011790, 'B14', 'Employment', 'Biccount', 'Software Engineer', NULL, 'active', '2025-05-14 16:25:32', '2025-05-14 16:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_number` varchar(11) NOT NULL,
  `size` varchar(255) NOT NULL,
  `floor_number` int(255) NOT NULL,
  `rooms` varchar(255) NOT NULL,
  `room_type` int(255) NOT NULL,
  `bathrooms` varchar(255) NOT NULL,
  `kitchen` varchar(255) NOT NULL,
  `balcony` varchar(255) NOT NULL,
  `rent_amount` int(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `building_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_number`, `size`, `floor_number`, `rooms`, `room_type`, `bathrooms`, `kitchen`, `balcony`, `rent_amount`, `description`, `created_at`, `updated_at`, `building_id`) VALUES
(47, 'B65', '3 by 7', 4, 'One bedroom', 0, 'One bathroom', 'open', 'one', 20000, 'WIDE ONE BEDROOM WITH A SPACIOUS KITCHEN', '2025-05-14 15:33:28', '2025-05-14 15:33:28', 83);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `email_verified_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `email`, `password`, `phone`, `avatar`, `role`, `status`, `email_verified_at`, `last_login`, `created_at`, `updated_at`) VALUES
(204, 'Emmanuel', 'Sikuku', 'emmanuelwanyonyi@gmail.com', '', NULL, NULL, 'user', 'active', NULL, NULL, '2025-05-14 16:11:10', '2025-05-14 16:11:10'),
(205, 'Emmanuel', 'Wafula', 'wanyonyi@gmail.com', '', NULL, NULL, 'user', 'active', NULL, NULL, '2025-05-14 16:25:32', '2025-05-14 16:25:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`);

--
-- Indexes for table `communication`
--
ALTER TABLE `communication`
  ADD PRIMARY KEY (`thread_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `inspections`
--
ALTER TABLE `inspections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_files`
--
ALTER TABLE `message_files`
  ADD PRIMARY KEY (`thread_id`);

--
-- Indexes for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pets_tenant` (`tenant_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tenant_user` (`user_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `fk_building_id` (`building_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `communication`
--
ALTER TABLE `communication`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inspections`
--
ALTER TABLE `inspections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `message_files`
--
ALTER TABLE `message_files`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `meter_readings`
--
ALTER TABLE `meter_readings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39011841;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `fk_pets_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `fk_tenant_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `fk_building_id` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

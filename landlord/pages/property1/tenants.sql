-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 07:18 AM
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
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `main_contact` varchar(20) NOT NULL,
  `alt_contact` varchar(20) DEFAULT NULL,
  `email` varchar(190) NOT NULL,
  `idMode` enum('national','passport') NOT NULL,
  `id_no` varchar(30) DEFAULT NULL,
  `pass_no` varchar(30) DEFAULT NULL,
  `leasing_period` int(11) NOT NULL,
  `leasing_start_date` date NOT NULL,
  `leasing_end_date` date NOT NULL,
  `move_in_date` date NOT NULL,
  `move_out_date` date NOT NULL,
  `account_no` varchar(100) NOT NULL,
  `id_upload` varchar(100) NOT NULL,
  `tax_pin_copy` varchar(100) NOT NULL,
  `rental_agreement` varchar(100) NOT NULL,
  `income` varchar(100) NOT NULL,
  `job_title` varchar(150) DEFAULT NULL,
  `job_location` varchar(150) DEFAULT NULL,
  `casual_job` varchar(150) DEFAULT NULL,
  `business_name` varchar(150) DEFAULT NULL,
  `business_location` varchar(150) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `building` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`tenant_id`, `first_name`, `middle_name`, `last_name`, `main_contact`, `alt_contact`, `email`, `idMode`, `id_no`, `pass_no`, `leasing_period`, `leasing_start_date`, `leasing_end_date`, `move_in_date`, `move_out_date`, `account_no`, `id_upload`, `tax_pin_copy`, `rental_agreement`, `income`, `job_title`, `job_location`, `casual_job`, `business_name`, `business_location`, `status`, `building`, `created_at`) VALUES
(1, 'Joshua', 'Miguna', 'Miguna', '0781218818', '0700128199', 'joshua.miguna@gmail.com', 'national', '2790598', '', 12, '2025-09-12', '2026-09-12', '2025-09-12', '2026-09-12', 'CA-20', 'all_uploads/84847351bc9d0c9ca97caa64f8d7681amuguna_id.PNG', 'all_uploads/84847351bc9d0c9ca97caa64f8d7681amiguna_kra.jpg', 'all_uploads/84847351bc9d0c9ca97caa64f8d7681amiguna_agreement.png', 'formal', 'High Court Judge', 'High Court of Kenya', '', '', '', 'Active', 'Naths Pinacle', '2025-09-12 14:07:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`tenant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `tenant_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

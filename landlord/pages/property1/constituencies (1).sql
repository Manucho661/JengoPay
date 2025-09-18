-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 07:17 AM
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
-- Table structure for table `constituencies`
--

CREATE TABLE `constituencies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `county_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `constituencies`
--

INSERT INTO `constituencies` (`id`, `name`, `county_id`) VALUES
(1, 'Changamwe', 1),
(2, 'Jomvu', 1),
(3, 'Kisauni', 1),
(4, 'Nyali', 1),
(5, 'Likoni', 1),
(6, 'Mvita', 1),
(7, 'Msambweni', 2),
(8, 'Lunga Lunga', 2),
(9, 'Matuga', 2),
(10, 'Kinango', 2),
(11, 'Kilifi North', 3),
(12, 'Kilifi South', 3),
(13, 'Kaloleni', 3),
(14, 'Rabai', 3),
(15, 'Ganze', 3),
(16, 'Malindi', 3),
(17, 'Magarini', 3),
(18, 'Garsen', 4),
(19, 'Galole', 4),
(20, 'Bura', 4),
(21, 'Lamu East', 5),
(22, 'Lamu West', 5),
(23, 'Taveta', 6),
(24, 'Wundanyi', 6),
(25, 'Mwatate', 6),
(26, 'Voi', 6),
(27, 'Garissa Township', 7),
(28, 'Balambala', 7),
(29, 'Lagdera', 7),
(30, 'Dadaab', 7),
(31, 'Fafi', 7),
(32, 'Ijara', 7),
(33, 'Wajir North', 8),
(34, 'Wajir East', 8),
(35, 'Tarbaj', 8),
(36, 'Wajir West', 8),
(37, 'Eldas', 8),
(38, 'Wajir South', 8),
(39, 'Mandera West', 9),
(40, 'Banissa', 9),
(41, 'Mandera North', 9),
(42, 'Mandera South', 9),
(43, 'Mandera East', 9),
(44, 'Lafey', 9),
(45, 'Moyale', 10),
(46, 'North Horr', 10),
(47, 'Saku', 10),
(48, 'Laisamis', 10),
(49, 'Isiolo North', 11),
(50, 'Isiolo South', 11),
(51, 'Buuri', 12),
(52, 'Central Imenti', 12),
(53, 'Igembe Central', 12),
(54, 'Igembe North', 12),
(55, 'Igembe South', 12),
(56, 'North Imenti', 12),
(57, 'South Imenti', 12),
(58, 'Tigania East', 12),
(59, 'Tigania West', 12),
(60, 'Chuka/Igambang\'ombe', 13),
(61, 'Maara', 13),
(62, 'Manyatta', 14),
(63, 'Runyenjes', 14),
(64, 'Mbeere North', 14),
(65, 'Mbeere South', 14),
(66, 'Kitui Central', 15),
(67, 'Kitui Rural', 15),
(68, 'Kitui South', 15),
(69, 'Kitui West', 15),
(70, 'Mwingi Central', 15),
(71, 'Mwingi North', 15),
(72, 'Mwingi West', 15),
(73, 'Kitui East', 15),
(74, 'Kangundo', 16),
(75, 'Kathiani', 16),
(76, 'Machakos Town', 16),
(77, 'Masinga', 16),
(78, 'Matungulu', 16),
(79, 'Mavoko', 16),
(80, 'Mwala', 16),
(81, 'Yatta', 16),
(82, 'Makueni', 17),
(83, 'Mbooni', 17),
(84, 'Kaiti', 17),
(85, 'Kilome', 17),
(86, 'Kibwezi East', 17),
(87, 'Kibwezi West', 17),
(88, 'Kinangop', 18),
(89, 'Kipipiri', 18),
(90, 'Ol Kalou', 18),
(91, 'Ol Jorok', 18),
(92, 'Ndaragwa', 18),
(93, 'Tetu', 19),
(94, 'Kieni', 19),
(95, 'Mathira', 19),
(96, 'Othaya', 19),
(97, 'Mukurweini', 19),
(98, 'Nyeri Town', 19),
(99, 'Mwea', 20),
(100, 'Gichugu', 20),
(101, 'Ndia', 20),
(102, 'Kirinyaga Central', 20),
(103, 'Kangema', 21),
(104, 'Mathioya', 21),
(105, 'Kiharu', 21),
(106, 'Kigumo', 21),
(107, 'Maragwa', 21),
(108, 'Kandara', 21),
(109, 'Gatanga', 21),
(110, 'Gatundu South', 22),
(111, 'Gatundu North', 22),
(112, 'Juja', 22),
(113, 'Thika Town', 22),
(114, 'Ruiru', 22),
(115, 'Githunguri', 22),
(116, 'Kiambu', 22),
(117, 'Kiambaa', 22),
(118, 'Kabete', 22),
(119, 'Kikuyu', 22),
(120, 'Limuru', 22),
(121, 'Lari', 22),
(122, 'Turkana North', 23),
(123, 'Turkana West', 23),
(124, 'Turkana Central', 23),
(125, 'Loima', 23),
(126, 'Turkana South', 23),
(127, 'Turkana East', 23),
(128, 'Kapenguria', 24),
(129, 'Sigor', 24),
(130, 'Kacheliba', 24),
(131, 'Pokot South', 24),
(132, 'Samburu West', 25),
(133, 'Samburu North', 25),
(134, 'Samburu East', 25),
(135, 'Kwanza', 26),
(136, 'Endebess', 26),
(137, 'Saboti', 26),
(138, 'Kiminini', 26),
(139, 'Cherangany', 26),
(140, 'Soy', 27),
(141, 'Turbo', 27),
(142, 'Moiben', 27),
(143, 'Ainabkoi', 27),
(144, 'Kapseret', 27),
(145, 'Kesses', 27),
(146, 'Ziwa-Laikipia', 27),
(147, 'Marakwet East', 28),
(148, 'Marakwet West', 28),
(149, 'Keiyo North', 28),
(150, 'Keiyo South', 28),
(151, 'Tinderet', 29),
(152, 'Aldai', 29),
(153, 'Nandi Hills', 29),
(154, 'Chesumei', 29),
(155, 'Emgwen', 29),
(156, 'Mosop', 29),
(157, 'Tiaty', 30),
(158, 'Baringo North', 30),
(159, 'Baringo Central', 30),
(160, 'Eldama Ravine', 30),
(161, 'Mogotio', 30),
(162, 'Baringo South', 30),
(163, 'Laikipia West', 31),
(164, 'Laikipia East', 31),
(165, 'Laikipia North', 31),
(166, 'Molo', 32),
(167, 'Njoro', 32),
(168, 'Naivasha', 32),
(169, 'Gilgil', 32),
(170, 'Kuresoi South', 32),
(171, 'Kuresoi North', 32),
(172, 'Subukia', 32),
(173, 'Rongai', 32),
(174, 'Bahati', 32),
(175, 'Nakuru Town East', 32),
(176, 'Nakuru Town West', 32),
(177, 'Kilgoris', 33),
(178, 'Emurua Dikirr', 33),
(179, 'Narok North', 33),
(180, 'Narok East', 33),
(181, 'Narok South', 33),
(182, 'Narok West', 33),
(183, 'Kajiado North', 34),
(184, 'Kajiado Central', 34),
(185, 'Kajiado East', 34),
(186, 'Kajiado West', 34),
(187, 'Kajiado South', 34),
(188, 'Ainamoi', 35),
(189, 'Belgut', 35),
(190, 'Kipkelion East', 35),
(191, 'Kipkelion West', 35),
(192, 'Soin/Sigowet', 35),
(193, 'Bureti', 35),
(194, 'Bomet Central', 36),
(195, 'Bomet East', 36),
(196, 'Chepalungu', 36),
(197, 'Konoin', 36),
(198, 'Sotik', 36),
(199, 'Lugari', 37),
(200, 'Likuyani', 37),
(201, 'Malava', 37),
(202, 'Lurambi', 37),
(203, 'Navakholo', 37),
(204, 'Mumias West', 37),
(205, 'Mumias East', 37),
(206, 'Matungu', 37),
(207, 'Butere', 37),
(208, 'Khwisero', 37),
(209, 'Shinyalu', 37),
(210, 'Ikolomani', 37),
(211, 'Vihiga', 38),
(212, 'Sabatia', 38),
(213, 'Hamisi', 38),
(214, 'Luanda', 38),
(215, 'Emuhaya', 38),
(216, 'Mt. Elgon', 39),
(217, 'Sirisia', 39),
(218, 'Kabuchai', 39),
(219, 'Bumula', 39),
(220, 'Kanduyi', 39),
(221, 'Webuye East', 39),
(222, 'Webuye West', 39),
(223, 'Kimilili', 39),
(224, 'Tongaren', 39),
(225, 'Teso North', 40),
(226, 'Teso South', 40),
(227, 'Nambale', 40),
(228, 'Matayos', 40),
(229, 'Butula', 40),
(230, 'Funyula', 40),
(231, 'Budalangi', 40),
(232, 'Ugenya', 41),
(233, 'Ugunja', 41),
(234, 'Alego Usonga', 41),
(235, 'Gem', 41),
(236, 'Bondo', 41),
(237, 'Rarieda', 41),
(238, 'Kisumu East', 42),
(239, 'Kisumu West', 42),
(240, 'Kisumu Central', 42),
(241, 'Seme', 42),
(242, 'Nyando', 42),
(243, 'Muhoroni', 42),
(244, 'Nyakach', 42),
(245, 'Kasipul', 43),
(246, 'Kabondo Kasipul', 43),
(247, 'Karachuonyo', 43),
(248, 'Rangwe', 43),
(249, 'Homa Bay Town', 43),
(250, 'Ndhiwa', 43),
(251, 'Suba North', 43),
(252, 'Suba South', 43),
(253, 'Rongo', 44),
(254, 'Awendo', 44),
(255, 'Suna East', 44),
(256, 'Suna West', 44),
(257, 'Uriri', 44),
(258, 'Nyatike', 44),
(259, 'Kuria West', 44),
(260, 'Kuria East', 44),
(261, 'Kitutu Chache North', 45),
(262, 'Kitutu Chache South', 45),
(263, 'Nyaribari Masaba', 45),
(264, 'Nyaribari Chache', 45),
(265, 'Bonchari', 45),
(266, 'South Mugirango', 45),
(267, 'Bobasi', 45),
(268, 'Bomachoge Borabu', 45),
(269, 'Bomachoge Chache', 45),
(270, 'Kitutu Masaba', 46),
(271, 'West Mugirango', 46),
(272, 'North Mugirango', 46),
(273, 'Borabu', 46),
(274, 'Westlands', 47),
(275, 'Dagoretti North', 47),
(276, 'Dagoretti South', 47),
(277, 'Lang’ata', 47),
(278, 'Kibra', 47),
(279, 'Roysambu', 47),
(280, 'Kasarani', 47),
(281, 'Ruaraka', 47),
(282, 'Embakasi South', 47),
(283, 'Embakasi North', 47),
(284, 'Embakasi Central', 47),
(285, 'Embakasi East', 47),
(286, 'Embakasi West', 47),
(287, 'Makadara', 47),
(288, 'Kamukunji', 47),
(289, 'Starehe', 47),
(290, 'Mathare', 47);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `constituencies`
--
ALTER TABLE `constituencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_constituencies_county` (`county_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `constituencies`
--
ALTER TABLE `constituencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `constituencies`
--
ALTER TABLE `constituencies`
  ADD CONSTRAINT `fk_constituencies_county` FOREIGN KEY (`county_id`) REFERENCES `county` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

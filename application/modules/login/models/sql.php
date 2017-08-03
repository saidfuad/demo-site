-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2016 at 04:43 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itms_africa`
--

-- --------------------------------------------------------

--
-- Table structure for table `itms_landmarks`
--

CREATE TABLE `itms_landmarks` (
  `landmark_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `landmark_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `radius` float(10,2) NOT NULL DEFAULT '0.00',
  `distance_unit` varchar(45) DEFAULT NULL,
  `device_ids` varchar(255) DEFAULT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `landmark_circle_color` varchar(10) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `addressbook_ids` varchar(255) DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `comments` varchar(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `email_alert` tinyint(1) NOT NULL DEFAULT '0',
  `sms_alert` tinyint(1) NOT NULL DEFAULT '0',
  `alert_before_landmark` varchar(255) DEFAULT NULL,
  `in_alert` tinyint(1) NOT NULL DEFAULT '0',
  `out_alert` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `itms_landmarks`
--

INSERT INTO `itms_landmarks` (`landmark_id`, `company_id`, `landmark_name`, `address`, `radius`, `distance_unit`, `device_ids`, `icon_path`, `landmark_circle_color`, `latitude`, `longitude`, `addressbook_ids`, `add_uid`, `add_date`, `del_uid`, `del_date`, `status`, `comments`, `group_id`, `email_alert`, `sms_alert`, `alert_before_landmark`, `in_alert`, `out_alert`) VALUES
(1, 2, 'msa', 'mombasa', 0.50, 'KM', '98', 'assets/landmark_images/aicon12.png', '', -4.076007, 39.666544, NULL, 2, '2015-11-23 10:31:50', NULL, NULL, 1, '', NULL, 0, 1, '1', 1, 1),
(2, 2, 'Likoni Ferry', 'likony', 0.50, NULL, NULL, 'assets/landmark_images/flag.gif', '#808000', -4.0791388254601975, 39.66559886466712, NULL, 2, NULL, NULL, NULL, 1, NULL, NULL, 0, 1, '1', 1, 1),
(3, 2, 'Moi Avenue', 'Town', 0.50, NULL, NULL, 'assets/landmark_images/icon45.png', '#808000', -4.063153958903753, 39.67141032102518, NULL, 2, NULL, NULL, NULL, 1, NULL, NULL, 0, 1, '1', 1, 1),
(4, 2, 'Nyali bridge', 'Kongowea', 0.50, NULL, NULL, 'assets/landmark_images/icon47.png', '#808000', -4.042952736996562, 39.67216432036366, NULL, 2, NULL, NULL, NULL, 1, NULL, NULL, 1, 1, '1', 1, 1),
(5, 2, 'Vipingo Lights', 'Kongowea', 0.50, NULL, NULL, 'assets/landmark_images/icon48.png', '#808000', -4.037032648120594, 39.68131780522526, NULL, 2, NULL, NULL, NULL, 1, NULL, NULL, 0, 1, '1', 1, 1),
(6, 2, 'Mazeras', 'Mazeras', 0.50, NULL, NULL, 'assets/landmark_images/flag.gif', '#337ab7', -3.96546023509862, 39.55039021326229, NULL, 2, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '1', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `itms_landmarks`
--
ALTER TABLE `itms_landmarks`
  ADD PRIMARY KEY (`landmark_id`),
  ADD KEY `lat` (`latitude`),
  ADD KEY `lng` (`longitude`),
  ADD KEY `add_uid` (`add_uid`,`group_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `company_id_2` (`company_id`),
  ADD KEY `company_id_3` (`company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `itms_landmarks`
--
ALTER TABLE `itms_landmarks`
  MODIFY `landmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `itms_landmarks`
--
ALTER TABLE `itms_landmarks`
  ADD CONSTRAINT `itms_landmarks_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `itms_companies` (`company_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

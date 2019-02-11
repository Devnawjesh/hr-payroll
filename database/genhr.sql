-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2019 at 05:31 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `genhr`
--

-- --------------------------------------------------------

--
-- Table structure for table `addition`
--

CREATE TABLE `addition` (
  `addi_id` int(14) NOT NULL,
  `salary_id` int(14) NOT NULL,
  `basic` varchar(128) DEFAULT NULL,
  `medical` varchar(64) DEFAULT NULL,
  `house_rent` varchar(64) DEFAULT NULL,
  `conveyance` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `addition`
--

INSERT INTO `addition` (`addi_id`, `salary_id`, `basic`, `medical`, `house_rent`, `conveyance`) VALUES
(1, 1, '12500.00', '1250.00', '10000.00', '1250.00'),
(2, 2, '21000.00', '2100.00', '16800.00', '2100.00'),
(3, 3, '20000.00', '2000.00', '16000.00', '2000.00'),
(4, 4, '20040.00', '2005.00', '16030.00', '2005.00'),
(5, 5, '15890.00', '1589.00', '12712.00', '1589.00'),
(6, 6, '15050.00', '1505.00', '12040.00', '1505.00'),
(7, 7, '15250.00', '1525.00', '12200.00', '1525.00'),
(8, 8, '10750.00', '1075.00', '8600.00', '1075.00'),
(9, 9, '13419', '1341', '10734', '1342'),
(10, 10, '14065.00', '1407', '11250.00', '1408'),
(11, 11, '16585.00', '1659', '13268.00', '1658'),
(12, 12, '13750.00', '1375.00', '11000.00', '1375.00'),
(13, 13, '16750.00', '1675.00', '13400.00', '1675.00'),
(14, 14, '14000.00', '1400.00', '11200.00', '1400.00'),
(15, 15, '15150.00', '1515.00', '12120.00', '1515.00'),
(16, 16, '3750.00', '375.00', '3000.00', '375.00'),
(17, 17, '10000.00', '1000.00', '8000.00', '1000.00'),
(18, 18, '14766.00', '1476', '11813', '1477'),
(19, 19, '24750.00', '2475.00', '19800.00', '2475.00'),
(20, 20, '14100.00', '1410.00', '11280.00', '1410.00'),
(21, 21, '5000.00', '500.00', '4000.00', '500.00'),
(22, 22, '7500.00', '750.00', '6000.00', '750.00'),
(23, 23, '11000.00', '1100.00', '8800.00', '1100.00'),
(24, 24, '87500.00', '8750.00', '70000.00', '8750.00');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(14) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `country` varchar(128) DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `type` enum('Present','Permanent') DEFAULT 'Present'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `emp_id`, `city`, `country`, `address`, `type`) VALUES
(7, 'em123', 'Dhaka', 'Bangladesh', 'Muktobanglashoping Complex', 'Present'),
(8, 'em123', 'Dhaka', 'Bangladesh', 'Muktobanglashoping Complex', 'Permanent'),
(9, 'Ahm1738', ' Jhenaidah ', 'Bangladesh', 'Vill- South Arpara, P.O- Naldanga, P.S- Kaligonj', 'Permanent'),
(10, 'Ahm1738', 'Dhaka', 'Bangladesh', 'Address: 14/d, Road 5, House 2, Section 13, Mirpur ', 'Present'),
(11, 'Ash1121', 'Jamalpur', 'Bangladesh', 'West Noya Para, P.S-Jamalpur Sadar.', 'Permanent'),
(12, 'Ash1121', 'Dhaka. ', 'Bangladesh', '21/2 - A, 1st floor (front side), Tolarbag, Mirpur - 1', 'Present'),
(13, 'Ash1121', 'Dhaka. ', 'Bangladesh', '21/2 - A, 1st floor (front side), Tolarbag, Mirpur - 1', 'Present'),
(14, 'Aha1832', 'Dhaka-1216', 'Bangladesh', 'House # 04, Road # 18, Block # G/1, Section-2, Mirpur', 'Present'),
(15, 'Aha1832', 'Natore', 'Bangladesh', 'Village: Patikabari: P.O: Ishurdi; P.S: Lalpure\r\n', 'Permanent'),
(16, 'Has1022', 'Jhenaidah', 'Bangladesh', ' Vill: Panta Para, Post: G-Panta Para,    Upazilla: Moheshpur, ', 'Permanent'),
(17, 'Has1022', 'Dhaka-1216', 'Bangladesh', '3rd Floor, 39/7, Hazi Ali Hossain Road, Baishteki, Mirpur - 13', 'Present'),
(18, 'Has1022', 'Dhaka-1216', 'Bangladesh', '3rd Floor, 39/7, Hazi Ali Hossain Road, Baishteki, Mirpur - 13', 'Present'),
(19, 'Mon1862', 'Pabna', 'Bangladesh', 'Vill+P.O: Jantihar, P.S: Faridpur, ', 'Permanent'),
(20, 'Mon1862', 'Dhaka- 1216', 'Bangladesh', '39/7, West Baeeisteki (3th Floor)\r\nSection- 13, Mirpur\r\n', 'Present'),
(21, 'Aza1652', 'Dhaka - 1216', 'Bangladesh', '3rd floor, 39/7, Haji Ali Hossain Road, West Baishteki, Mirpur -13', 'Present'),
(22, 'Aza1652', 'Chittagong-4000', 'Bangladesh', '631, Kazem Ali Road, Ghatforhadbeg, Kotowali', 'Permanent'),
(23, 'Akt1660', 'Barguna ', 'Bangladesh ', 'Barguna KG School Road, Ward No- 4, P.O- Barguna, P.S- Barguna ', 'Permanent'),
(24, 'Akt1660', 'Dhaka', 'Bangladesh', 'House No- 105/Ka, West Agargoan, Ser-E-Banglanagar, Dhaka- 1207', 'Present'),
(25, 'edr1432', 'Dhaka', 'Bangladesh', '230 Free School Street, Flat- C1, Kanthalbagan, Dhaka- 1205', 'Present'),
(26, 'edr1432', 'Dhaka', 'Bangladesh ', '230 Free School Street, Flat- C1, Kanthalbagan, Dhaka- 1205', 'Permanent'),
(27, 'EMP1254478', 'dfgdf gdf', 'd gdfgd', 'dg fdgfd g', 'Present'),
(28, 'EMP1254478', 'd gdfgd ', 'd g dfgdf', 'g dfgdf', 'Permanent');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `ass_id` int(14) NOT NULL,
  `catid` varchar(14) NOT NULL,
  `ass_name` varchar(256) DEFAULT NULL,
  `ass_brand` varchar(128) DEFAULT NULL,
  `ass_model` varchar(256) DEFAULT NULL,
  `ass_code` varchar(256) DEFAULT NULL,
  `configuration` varchar(512) DEFAULT NULL,
  `purchasing_date` varchar(128) DEFAULT NULL,
  `ass_price` varchar(128) DEFAULT NULL,
  `ass_qty` varchar(64) DEFAULT NULL,
  `in_stock` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`ass_id`, `catid`, `ass_name`, `ass_brand`, `ass_model`, `ass_code`, `configuration`, `purchasing_date`, `ass_price`, `ass_qty`, `in_stock`) VALUES
(12, '2', 'pendrive', 'micro SD', 'usb-3', 'asasd', 'sdfds dsf dsf dsf sd fdsfsfsdfdsfsf dsf ds f dsfsd', '15/05/2015', '480', '12', '12'),
(13, '1', 'Table', 'Otobi ', 'Ot 5456', 'asasd', 'sdfds dsf dsf dsf sd fdsfsfsdfdsfsf dsf ds f dsfsd', '15/05/2015', '480', '13', '2'),
(14, '2', 'laptop', 'micro SD', 'usb-3', 'asasd', 'sdfds dsf dsf dsf sd fdsfsfsdfdsfsf dsf ds f dsfsd', '15/05/2015', '480', '12', '13'),
(15, '2', 'Microwave', 'sfdsfd', '324efewr', '2343242344', 'ewr3434 3453434 ', '02/08/2018', '3453', '1', '1'),
(16, '1', 'Nur', 'MD', '420', '024', 'dadfaadfadfadfa', '02/01/2018', '12000000', '1', '1'),
(17, '2', 'Sound Box', 'JBL', 'jbl 4422', 'jbl-4422', 'When you are executing an SQL command for any RDBMS, the system determines the best way to carry out your request and SQL engine figures out how to interpret the task.', '04/18/2018', '28000', '5', '5'),
(18, '4', 'Lesbian', 'JBL', 'asd123', '1h4j5', 'xgvxc vxcv cx vcx vcxv cx vx v xv x vxc xc', '04/30/2018', '25000', '5', '5');

-- --------------------------------------------------------

--
-- Table structure for table `assets_category`
--

CREATE TABLE `assets_category` (
  `cat_id` int(14) NOT NULL,
  `cat_status` enum('ASSETS','LOGISTIC') NOT NULL DEFAULT 'ASSETS',
  `cat_name` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assets_category`
--

INSERT INTO `assets_category` (`cat_id`, `cat_status`, `cat_name`) VALUES
(1, 'ASSETS', 'TAB'),
(2, 'ASSETS', 'Computer'),
(3, 'ASSETS', 'Laptop'),
(4, 'LOGISTIC', 'tab');

-- --------------------------------------------------------

--
-- Table structure for table `assign_leave`
--

CREATE TABLE `assign_leave` (
  `id` int(14) NOT NULL,
  `app_id` varchar(11) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `type_id` int(14) NOT NULL,
  `day` varchar(256) DEFAULT NULL,
  `hour` varchar(255) NOT NULL,
  `total_day` varchar(64) DEFAULT NULL,
  `dateyear` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assign_leave`
--

INSERT INTO `assign_leave` (`id`, `app_id`, `emp_id`, `type_id`, `day`, `hour`, `total_day`, `dateyear`) VALUES
(2, '', 'EMP1254478', 9, '1', '0', NULL, '2016'),
(3, '', 'EMP1254478', 6, '0', '4', '0', '2018'),
(4, '', 'EMP1254478', 7, '2', '0', '0', '2017'),
(7, '', 'Has1013', 4, '0', '6', '1', '2018'),
(9, '', 'Ahm1738', 5, '0', '6', '0', '2018'),
(10, '', 'Aza1652', 1, NULL, '2', NULL, '2018'),
(11, '', 'Aha1832', 8, NULL, '7', NULL, '2018'),
(12, '', 'Aza1652', 9, NULL, '1', NULL, '2018'),
(13, '', 'Aza1652', 8, NULL, '3', NULL, '2018'),
(14, '', 'Aza1652', 2, NULL, '24', NULL, '2018'),
(15, '', 'Dha1415', 5, NULL, '16', NULL, '2018'),
(16, '', 'Has1013', 2, NULL, '24', NULL, '2018'),
(17, '', 'Dha1415', 2, NULL, '64', NULL, '2018'),
(18, '', 'Aha1832', 5, NULL, '64', NULL, '2018'),
(19, '', 'Hos1156', 2, NULL, '24', NULL, '2018'),
(20, '', 'Hos1156', 5, NULL, '8', NULL, '2018'),
(21, '', 'EMP1254478', 5, NULL, '456', NULL, '2018');

-- --------------------------------------------------------

--
-- Table structure for table `assign_task`
--

CREATE TABLE `assign_task` (
  `id` int(14) NOT NULL,
  `task_id` int(14) NOT NULL,
  `project_id` int(14) NOT NULL,
  `assign_user` varchar(64) DEFAULT NULL,
  `user_type` enum('Team Head','Collaborators') NOT NULL DEFAULT 'Collaborators'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assign_task`
--

INSERT INTO `assign_task` (`id`, `task_id`, `project_id`, `assign_user`, `user_type`) VALUES
(14, 7, 4, 'Sah1804', 'Team Head'),
(15, 7, 4, 'Hos1156', 'Collaborators'),
(16, 7, 4, 'Aha1832', 'Collaborators'),
(17, 7, 4, 'Isl1385', 'Collaborators'),
(18, 8, 4, 'Aha1832', 'Team Head'),
(19, 8, 4, 'EMP1254478', 'Collaborators'),
(20, 8, 4, 'Aza1652', 'Collaborators'),
(21, 8, 4, 'Isl1385', 'Collaborators'),
(23, 10, 5, 'Hos1156', 'Team Head'),
(24, 10, 5, 'Aza1652', 'Collaborators'),
(25, 10, 5, 'Sah1804', 'Collaborators'),
(26, 11, 4, '', 'Team Head'),
(27, 12, 4, '', 'Team Head'),
(28, 13, 4, 'Hos1156', 'Team Head'),
(29, 13, 4, 'Aza1652', 'Collaborators'),
(30, 13, 4, 'Aha1832', 'Collaborators'),
(31, 14, 4, 'EMP1254478', 'Team Head'),
(32, 14, 4, 'Sah1804', 'Collaborators'),
(33, 14, 4, 'Aha1832', 'Collaborators');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(14) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `atten_date` varchar(64) DEFAULT NULL,
  `signin_time` time DEFAULT NULL,
  `signout_time` time DEFAULT NULL,
  `working_hour` varchar(64) DEFAULT NULL,
  `place` varchar(255) NOT NULL,
  `absence` varchar(128) DEFAULT NULL,
  `overtime` varchar(128) DEFAULT NULL,
  `earnleave` varchar(128) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES
(965, '2', '2018-05-22', '10:00:00', '17:00:00', '0 min', 'office', '429 min', '0 min', NULL, 'A'),
(966, '3', '2018-04-22', '09:00:00', '18:00:00', '0 min', 'office', '480 min', '0 min', NULL, 'A'),
(967, '4', '2018-05-22', '09:00:00', '19:00:00', '247 min', 'office', '232 min', '149 min', NULL, 'A'),
(968, '12', '2018-03-20', '01:00:00', '03:00:00', '396 min', 'office', '83 min', '142 min', NULL, 'A'),
(969, '13', '2018-03-20', '02:00:00', '04:00:00', '480 min', 'office', '0 min', '34 min', NULL, 'A'),
(970, '14', '2018-02-20', '03:00:00', '05:00:00', '399 min', 'office', '80 min', '152 min', NULL, 'A'),
(971, '15', '2018-02-20', '04:00:00', '06:00:00', '480 min', 'office', '0 min', '133 min', NULL, 'A'),
(972, '16', '2018-02-20', '05:00:00', '07:00:00', '0 min', 'office', '480 min', '0 min', NULL, 'A'),
(973, '17', '2018-02-20', '06:00:00', '08:00:00', '480 min', 'office', '0 min', '286 min', NULL, 'A'),
(974, '18', '2018-02-20', '07:00:00', '09:00:00', '431 min', 'office', '0 min', '140 min', NULL, 'A'),
(975, '19', '2018-02-20', '08:00:00', '10:00:00', '377 min', 'office', '102 min', '192 min', NULL, 'A'),
(976, '24', '2018-02-20', '01:00:00', '15:00:00', '433 min', 'office', '0 min', '140 min', NULL, 'A'),
(977, '25', '2018-02-20', '02:00:00', '16:00:00', '480 min', 'office', '0 min', '0 min', NULL, 'A'),
(978, '2', '2018-01-20', '09:00:00', '17:00:00', '339 min', 'office', '140 min', '178 min', NULL, 'A'),
(980, '2', '2018-04-01', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(981, '2', '2018-04-02', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(982, '2', '2018-04-03', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(985, '2', '2018-04-03', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(986, '2', '2018-05-04', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(987, '2', '2018-05-19', '09:00:00', '18:00:00', '09 h 0 m', 'office', NULL, NULL, NULL, 'A'),
(988, '2', '2018-05-04', '09:00:00', '06:30:00', '02 h 30 m', 'office', NULL, NULL, NULL, 'E'),
(989, '2', '2018-06-09', '09:00:00', '18:00:00', '09 h 0 m', 'office', NULL, NULL, NULL, 'A'),
(990, '2', '2018-07-20', '10:00:00', '17:00:00', '0 min', 'office', '429 min', '0 min', NULL, 'A'),
(991, '2', '2018-04-09', '09:00:00', '17:29:00', '450 min', 'office', '429 min', '0 min', NULL, 'A'),
(992, '3', '2018-04-09', '10:00:00', '18:29:00', '300 min', 'office', '480 min', '0 min', NULL, 'A'),
(993, '4', '2018-04-09', '11:00:00', '19:29:00', '247 min', 'office', '232 min', '149 min', NULL, 'A'),
(994, '20', '2018-04-12', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(995, '20', '2018-04-13', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(996, '20', '2018-04-14', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(997, '20', '2018-04-15', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E'),
(998, '2', '2018-02-20', '10:00:00', '17:00:00', '0 min', 'office', '429 min', '0 min', NULL, 'A'),
(999, '3', '2018-02-21', '09:00:00', '18:00:00', '0 min', 'office', '480 min', '0 min', NULL, 'A'),
(1000, '4', '2018-02-22', '09:00:00', '19:00:00', '247 min', 'office', '232 min', '149 min', NULL, 'A'),
(1001, '12', '2018-03-02', '01:00:00', '03:00:00', '396 min', 'office', '83 min', '142 min', NULL, 'A'),
(1002, '13', '2018-03-03', '02:00:00', '04:00:00', '480 min', 'office', '0 min', '34 min', NULL, 'A'),
(1003, '14', '2018-03-04', '03:00:00', '05:00:00', '399 min', 'office', '80 min', '152 min', NULL, 'A'),
(1004, '15', '2018-03-05', '04:00:00', '06:00:00', '480 min', 'office', '0 min', '133 min', NULL, 'A'),
(1005, '16', '2018-03-06', '05:00:00', '07:00:00', '0 min', 'office', '480 min', '0 min', NULL, 'A'),
(1006, '17', '2018-03-07', '06:00:00', '08:00:00', '480 min', 'office', '0 min', '286 min', NULL, 'A'),
(1007, '18', '2018-03-08', '07:00:00', '09:00:00', '431 min', 'office', '0 min', '140 min', NULL, 'A'),
(1008, '19', '2018-03-09', '08:00:00', '10:00:00', '377 min', 'office', '102 min', '192 min', NULL, 'A'),
(1009, '24', '2018-03-14', '01:00:00', '15:00:00', '433 min', 'office', '0 min', '140 min', NULL, 'A'),
(1010, '25', '2018-03-15', '02:00:00', '16:00:00', '480 min', 'office', '0 min', '0 min', NULL, 'A'),
(1011, '26', '2018-02-20', '03:00:00', '17:00:00', '339 min', 'office', '140 min', '178 min', NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `bank_info`
--

CREATE TABLE `bank_info` (
  `id` int(14) NOT NULL,
  `em_id` varchar(64) DEFAULT NULL,
  `holder_name` varchar(256) DEFAULT NULL,
  `bank_name` varchar(256) DEFAULT NULL,
  `branch_name` varchar(256) DEFAULT NULL,
  `account_number` varchar(256) DEFAULT NULL,
  `account_type` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bank_info`
--

INSERT INTO `bank_info` (`id`, `em_id`, `holder_name`, `bank_name`, `branch_name`, `account_number`, `account_type`) VALUES
(1, 'Ahm1738', 'Meraz Ahmed', 'Trust Bank Limited', 'Kafrul Branch', '0041-0316000093', 'Saving'),
(2, 'Akt1660', 'Md. Akteruzzaman ', 'Trust Bank', 'Kafrul', '0041-0316000039', 'Saving'),
(3, 'Ash1121', 'Afrin Sultana Asha', 'Trust Bank', 'Kochukhet Branch', '0041-0316000075', 'Saving'),
(4, 'Aha1832', 'Raju Ahammed', 'Trust Bank', 'Kafrul', '0041-0316000048', 'Saving'),
(5, 'Has1022', 'Md. Kamrul Hasan', 'Trust Bank', 'Kochukhet', '0041-0316000057', 'Saving'),
(6, 'Mon1862', 'Md. Moniruzzaman', 'Trust Bank', 'Kochuke', '0041-0316000066', 'Saving'),
(7, 'Rah1682', 'Md. Hafizur Rahman', 'Trust Bank', 'Kafrul Branch', '0041-0316000146', 'Saving'),
(8, 'Bar1085', 'Anindya Barai', 'Trust Bank Ltd.', 'Kachukhet', '111111', 'Savings'),
(9, 'EMP1254478', 'Mamun Ur Rashid', 'DUTCH BANGLA BANK', 'Dhanmondi', '0041-0316000148', 'Deposit');

-- --------------------------------------------------------

--
-- Table structure for table `deduction`
--

CREATE TABLE `deduction` (
  `de_id` int(14) NOT NULL,
  `salary_id` int(14) NOT NULL,
  `provident_fund` varchar(64) DEFAULT NULL,
  `bima` varchar(64) DEFAULT NULL,
  `tax` varchar(64) DEFAULT NULL,
  `others` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `deduction`
--

INSERT INTO `deduction` (`de_id`, `salary_id`, `provident_fund`, `bima`, `tax`, `others`) VALUES
(1, 1, '250', '250', '250', '3600'),
(2, 2, '', '', '', ''),
(3, 3, '', '', '', ''),
(4, 4, '', '', '', ''),
(5, 5, '', '', '', ''),
(6, 6, '', '', '', ''),
(7, 7, '', '', '', ''),
(8, 8, '', '', '', ''),
(9, 9, '', '', '', ''),
(10, 10, '', '', '', ''),
(11, 11, '', '', '', ''),
(12, 12, '', '', '', ''),
(13, 13, '', '', '', ''),
(14, 14, '', '', '', ''),
(15, 15, '', '', '', ''),
(16, 16, '', '', '', ''),
(17, 17, '', '', '', ''),
(18, 18, '', '', '', ''),
(19, 19, '', '', '', ''),
(20, 20, '', '', '', ''),
(21, 21, '', '', '', ''),
(22, 22, '', '', '', ''),
(23, 23, '', '', '', ''),
(24, 24, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `dep_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `dep_name`) VALUES
(2, 'Administration'),
(3, 'Finance, HR, & Admininstration'),
(4, 'Research'),
(5, 'Information Technology'),
(6, 'Support');

-- --------------------------------------------------------

--
-- Table structure for table `desciplinary`
--

CREATE TABLE `desciplinary` (
  `id` int(14) NOT NULL,
  `em_id` varchar(64) DEFAULT NULL,
  `action` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `desciplinary`
--

INSERT INTO `desciplinary` (`id`, `em_id`, `action`, `title`, `description`) VALUES
(1, 'Aza1652', 'Verbel Warning', 'Today has been a very memorable day for me', 'Today has been a very memorable day for meToday has been a very memorable day for meToday has been a very memorable day for me'),
(3, 'EMP1254478', 'Writing Warning', 'Today has been a very memorable day for me', 'Today has been a very memorable day for meToday has been a very memorable day for meToday has been a very memorable day for meToday has been a very memorable day for me');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `des_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `des_name`) VALUES
(2, 'Vice Chairman'),
(3, 'Chief Executive Officer (CEO)'),
(4, 'Chief Finance & Admin Officer'),
(5, 'Sr. Finance & Admin Officer - I'),
(6, 'Jr. Finance & Admin Officer'),
(7, 'Senior Research Associate-1'),
(8, 'Research Associate-1'),
(9, 'Junior Research Associate'),
(10, 'Research Assistant'),
(11, 'Sr. Office Assistant'),
(12, 'Office Assistant'),
(13, 'IT Analyst'),
(14, 'Cook');

-- --------------------------------------------------------

--
-- Table structure for table `earned_leave`
--

CREATE TABLE `earned_leave` (
  `id` int(14) NOT NULL,
  `em_id` varchar(64) DEFAULT NULL,
  `present_date` varchar(64) DEFAULT NULL,
  `hour` varchar(64) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `earned_leave`
--

INSERT INTO `earned_leave` (`id`, `em_id`, `present_date`, `hour`, `status`) VALUES
(1, 'EMP1254478', '9', '72', '1'),
(2, 'Hos1156', '2', '16', '1'),
(3, 'Soy1332', '0', '0', '1'),
(4, 'Aza1652', '0', '0', '1'),
(5, 'Sah1804', '5', '40', '1'),
(6, 'Aha1832', '0', '0', '1'),
(7, 'Isl1385', '0', '0', '1'),
(8, 'Has1013', '0', '0', '1'),
(9, 'Akt1660', '0', '0', '1'),
(10, 'Ahm1738', '0', '0', '1'),
(11, 'Sha1790', '0', '0', '1'),
(12, 'Ash1121', '1', '8', '1'),
(13, 'Bar1085', '0', '0', '1'),
(14, 'Dha1415', '0', '0', '1'),
(15, 'Has1448', '0', '0', '1'),
(16, 'Ala1118', '0', '0', '1'),
(17, 'Kha1210', '0', '0', '1'),
(18, 'Hos1757', '0', '0', '1'),
(19, 'Kha1297', '0', '0', '1'),
(20, 'Mal1316', '0', '0', '1'),
(21, 'Sid1871', '0', '0', '1'),
(22, 'Has1022', '0', '0', '1'),
(23, 'Mon1862', '0', '0', '1'),
(24, 'Hos1097', '2', '16.00', '1'),
(25, 'Isl1249', '0', '0', '1'),
(26, 'Mir1685', '0', '0', '1'),
(27, 'Rah1682', '0', '0', '1'),
(28, 'edr1432', '0', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(128) DEFAULT NULL,
  `edu_type` varchar(256) DEFAULT NULL,
  `institute` varchar(256) DEFAULT NULL,
  `result` varchar(64) DEFAULT NULL,
  `year` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `emp_id`, `edu_type`, `institute`, `result`, `year`) VALUES
(4, 'em123', 'BBA', 'Dhaka school', 'applicant ', '2017'),
(5, 'Has1013', 'frygfdfgfgf', 'gfgrtrtrte', '4.2', '2015'),
(6, 'Ash1121', 'M.S.S in Dept. of Anthropology ', 'Jahangirnagar University', '3.23', '2013'),
(7, 'Ash1121', 'B.S.S (Hons) in Dept. of Anthropology ', 'Jahangirnagar University  ', '3.41', '2012'),
(8, 'Aha1832', 'Masters of Arts in Islamic History and Culture ', 'Rajshahi University', '2nd class ', '2003'),
(9, 'Aha1832', 'Bachelor of Arts in Islamic History and Culture,  ', 'Rajshahi University', '2ndclass', '2002'),
(10, 'Has1022', 'M.S.S from Department of Anthropology', 'Jahangirnagar University  ', '3.29', '2013'),
(11, 'Has1022', 'B.S.S from Department of Anthropology', 'Jahangirnagar University  ', '3.24', '2012'),
(12, 'Mon1862', 'Masters of Arts in Bangla', 'National University', '2nd class', '2011'),
(13, 'Mon1862', 'BSS in Bangla', 'National University', '2nd class', '2010'),
(14, 'Aza1652', 'Higher Secondary Certificate', 'Chittagong Collegiate School', '4.1', '2011'),
(15, 'Aza1652', 'Secondary School Certificate', 'Lamabazar A.A.S. City Corp. High School', '5.00', '2009'),
(16, 'Ash1121', 'H.S.C.', 'Govt. Ashek Mahmud Collage ', '4.40', '2007'),
(17, 'Ash1121', 'S.S.C.', 'Jamalpur Govt. Girl’s High School', '4.06', '2005'),
(18, 'Ahm1738', 'MSc', 'Jahangirnagar University', '3.36', '2011'),
(19, 'Ahm1738', 'BSc', '	Jahangirnagar University', '2.84', '2010'),
(20, 'edr1432', 'MSS', 'University of Dhaka', '4.00', '2016'),
(21, 'edr1432', 'BSS', 'University of Dhaka', '3.92', '2015');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `em_id` varchar(64) DEFAULT NULL,
  `em_code` varchar(64) DEFAULT NULL,
  `des_id` int(11) DEFAULT NULL,
  `dep_id` int(11) DEFAULT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `em_email` varchar(64) DEFAULT NULL,
  `em_password` varchar(512) NOT NULL,
  `em_role` enum('ADMIN','EMPLOYEE','SUPER ADMIN') NOT NULL DEFAULT 'EMPLOYEE',
  `em_address` varchar(512) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `em_gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `em_phone` varchar(64) DEFAULT NULL,
  `em_birthday` varchar(128) DEFAULT NULL,
  `em_blood_group` enum('O+','O-','A+','A-','B+','B-','AB+','OB+') DEFAULT NULL,
  `em_joining_date` varchar(128) DEFAULT NULL,
  `em_contact_end` varchar(128) DEFAULT NULL,
  `em_image` varchar(128) DEFAULT NULL,
  `em_nid` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `em_id`, `em_code`, `des_id`, `dep_id`, `first_name`, `last_name`, `em_email`, `em_password`, `em_role`, `em_address`, `status`, `em_gender`, `em_phone`, `em_birthday`, `em_blood_group`, `em_joining_date`, `em_contact_end`, `em_image`, `em_nid`) VALUES
(1, 'EMP1254478', '2', 3, 2, 'Md.', 'Mamun-Ur-Rashid', 'mamunbangladesh@gmail.com', '6367c48dd193d56ea7b0baad25b19455e529f5ee', 'ADMIN', NULL, 'ACTIVE', 'Male', '01713504255', '2004-02-11', 'A-', '2015-10-01', '2018-12-31', 'EMP12545.jpg', '45635464646452'),
(9, 'Hos1156', '0001', 9, 4, 'Md. Naseer ', 'Haider', 'naseer152001@yahoo.com', '0a80b3b3626258635557d2e401b84d9543427de9', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '01713047311', '2017-12-29', 'O+', '2017-12-30', '2017-12-31', 'Hos1156.jpg', '132154566556'),
(10, 'Soy1332', '99', 0, 0, 'Dir', 'Soyeb', 'nawjeshff@gmail.com', '996a3778768a2c3ea7c5b586fdfc92051dfdd39c', 'SUPER ADMIN', NULL, 'ACTIVE', 'Female', '01723177901', '2017-12-26', 'B+', '2018-01-06', '2018-01-06', 'Soy1332.jpg', '132154566556'),
(11, 'Aza1652', '26', 5, 5, 'Md. Imran ', 'Azad', 'imranazad.likhon@gmail.com', '0ec9dc68bb9812a61c18554d1ac2796159721ec2', 'ADMIN', NULL, 'ACTIVE', 'Male', '+8801998355190', '1994-08-18', 'O+', '2018-01-01', '', 'Aza1652.jpg', '3333333333'),
(12, 'Sah1804', '5', 4, 3, 'Miron Kumar ', 'Saha', 'miron.saha@yahoo.com', '8cfe524fa909155bf8b1f5a0f6e2fbc8b3bd780d', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801827115587', '2001-01-01', '', '2015-07-01', '', NULL, '222222222222222'),
(13, 'Aha1832', '3', 5, 3, 'Raju ', 'Ahammed', 'rajuru94@gmail.com', '657d82f3734cda6494682d7f9bc159bd1e1a84ad', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801718970070', '1977-11-21', 'A+', '2015-09-19', '', 'Aha1832.JPG', '94194841711326'),
(14, 'Isl1385', '4', 3, 3, 'Jubaida', 'Islam', 'jubaidajune@gmail.com', '20a2463e0917d9d780a89e66e3682493725bcde6', '', NULL, 'ACTIVE', 'Female', '+8801674707751', '1990-09-28', 'A+', '2016-07-01', '', 'Isl1385.jpg', '454545454455545'),
(15, 'Has1013', '22', 0, 4, 'H. M. Shahid', 'Hassan', 'dip.ju38@gmail.com', '0c12de5b34dd0473017ef6df27ead95ff877f832', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801914101838', '2001-12-01', 'O+', '2016-07-01', '', NULL, '5555555555555555'),
(16, 'Akt1660', '15', 7, 4, 'Md.', 'Akteruzzaman', 'akteruzzaman1@gmail.com', '79645224e5b70fd8a2345d38d3d864548c1aaa09', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801724099859', '1979-11-12', 'B+', '2015-08-09', '', NULL, '0422804110338'),
(17, 'Ahm1738', '6', 8, 4, 'Meraz', 'Ahmed', 'merazeco@gmail.com', 'a1796a7cc667333b8aaf83aadf7a47c05042163f', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801720343301', '1988-12-10', 'A+', '2015-02-15', '', 'Ahm1738.JPG', '4423307426295'),
(18, 'Sha1790', '16', 4, 4, 'Shakila ', 'Sharmin', 'shakilantic@gmail.com', '90dbe713bfea9fa5ffe95ee21fad3ba6d0c4a58b', 'EMPLOYEE', NULL, 'ACTIVE', 'Female', '+8801717824652', '0026-05-15', 'O+', '2015-10-01', '', 'Sha1790.jpg', '135896321656889'),
(19, 'Ash1121', '24', 2, 6, 'Afrin Sultana', 'Asha', 'afrinasha.ju@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'EMPLOYEE', NULL, 'ACTIVE', 'Female', '+8801728262291', '1990-11-22', 'A+', '2015-03-10', '', NULL, '15259855545'),
(20, 'Bar1085', '19', 9, 4, 'Anindya', 'Barai', 'anindyabarai@gmail.com', '079126764acb6fecca6199136e527bf3e623c0f1', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801705172573', '0544-04-15', 'A+', '2016-05-12', '', NULL, '1578512158'),
(21, 'Dha1415', '7', 8, 4, 'Sabbir Ahmed', 'Dhali', 'sabbirahmeddhali@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801789577945', '0006-02-15', 'O+', '', '', NULL, '45498748515468'),
(22, 'Has1448', '9', 8, 4, 'SK Tanvir', 'Hassan', 'tanvirhassan4@gmail.com', '2c6b21c043ef1a0c417c724433134ab78b30e02a', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801912289092', '1988-12-25', 'O+', '2015-02-15', '', NULL, '587896512478'),
(23, 'Ala1118', '10', 9, 4, 'Md. Ashraful', 'Alam', 'ashrafulalamsaikat@gmail.com', '59bad8f3fe82ebf6733a098694e0c3105c1a291c', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801686258494', '0546-05-04', 'O+', '2014-11-01', '', NULL, '7845214579646'),
(24, 'Kha1210', '18', 8, 4, 'Md. Ali Newaz', 'Khan', 'newazshoeb@gmail.com', '38a08f970855bcf4ac03ce3a342c4791885e472a', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801729442790', '0561-04-05', 'O+', '2016-01-20', '', NULL, '1895648678546'),
(25, 'Hos1757', '21', 8, 4, 'MD. Faruq', 'Hossain', 'sfaruq.228@gmail.com', '1ac3d3441ce340ebde59532050883fd49ed0ce83', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801718810689', '0964-01-15', 'O+', '2016-07-01', '', NULL, '4569435879874'),
(26, 'Kha1297', '14', 7, 4, 'Shameem Reza', 'Khan', 'shameemrz@gmail.com', '46e81df1897d529c66b16620158b5df260675651', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801720538347', '0055-05-04', 'A+', '2015-07-01', '', NULL, '3484697544458'),
(27, 'Mal1316', '40', 4, 4, 'James Sujit ', 'Malo', 'sujitsocunivdu@gmail.com', 'de2983998d47281dbcb300cda84cfc7c9417897c', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801675527933', '0010-11-01', 'O+', '2017-06-01', '', 'Mal1316.JPG', '1111111111111111111'),
(28, 'Sid1871', '25', 10, 4, 'Dipanjan ', 'Sidhanta', 'dip.bsu@gmail.com', '70352f41061eda4ff3c322094af068ba70c3b38b', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801884574782', '0011-01-01', 'O+', '2017-06-01', '', 'Sid1871.JPG', '1222222222211'),
(29, 'Has1022', '13', 8, 4, 'Md. Kamrul ', 'Hasan ', 'hasankamrul.arman@gmail.com', '2395a446e7009d8652ef31bd100e790d91aab8ca', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801712531930', '1990-10-23', 'A+', '2015-05-20', '', 'Has1022.jpg', '222222212323'),
(30, 'Mon1862', '8', 8, 4, 'Md. ', 'Moniruzzaman', 'moni_bdi@yahoo.com', '1c6d01fc048ebd252c7f051f9d305d9b21f41b4c', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801729913991', '0011-01-01', 'B+', '2015-02-15', '', NULL, '4444444444444444444'),
(31, 'Hos1097', '20', 8, 4, 'Zabir ', 'Hossain', 'zabir38@gmail.com', '30af1806d99155eee4a6afc7e98858ccf60fcb27', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801740647513', '1991-02-01', 'B+', '2016-05-03', '', 'Hos1097.jpg', '4444444444444'),
(32, 'Isl1249', '23', 12, 6, 'Md. Ariful ', 'Islam ', 'arif@gmail.com', 'a63a1d8f4652c3f6925ea90810181843e661ab91', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801773642528', '0011-01-01', 'O+', '2016-03-05', '', 'Isl1249.jpg', '2121222222222222'),
(33, 'Mir1685', '17', 14, 6, 'Papia Khatun ', 'Mira ', 'mira@gmail.com', '89ef0256cb11ea3bf6d461583fead1cff50ac7a3', 'EMPLOYEE', NULL, 'ACTIVE', 'Female', '+8801736043832', '0011-01-01', 'A+', '2015-09-01', '', 'Mir1685.jpg', '434344444444'),
(34, 'Rah1682', '11', 11, 6, 'Md. Hafigur ', 'Rahman ', 'hafigur92@gmail.com', 'd262e09a91ccf711a6677dcce5081fe81ae92800', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801736789894', '0011-01-01', 'O+', '2014-02-02', '', 'Rah1682.jpg', '222222222222222222'),
(35, 'edr1432', '63', 10, 4, 'F. H. Yasin', 'Shafi', 'yasinshafikhan@gmail.com', '6367c48dd193d56ea7b0baad25b19455e529f5ee', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', '+8801716383548', '1994-05-25', 'A+', '2018-02-01', '2018-12-31', 'edr1432.jpg', '2820838544'),
(36, 'Doe1753', '123456', 12, 2, 'Jhon', 'Doe', 'admin@gmail.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'ADMIN', NULL, 'ACTIVE', 'Male', 'admin123456', '2019-02-13', 'O+', '2019-02-15', '2019-02-22', 'Doe1753.jpg', '01253568955555');

-- --------------------------------------------------------

--
-- Table structure for table `employee_file`
--

CREATE TABLE `employee_file` (
  `id` int(14) NOT NULL,
  `em_id` varchar(64) DEFAULT NULL,
  `file_title` varchar(512) DEFAULT NULL,
  `file_url` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee_file`
--

INSERT INTO `employee_file` (`id`, `em_id`, `file_title`, `file_url`) VALUES
(1, 'Aha1832', 'Curriculum Vita', 'CV_of_Raju_Ahammed_2017__doc.doc'),
(2, 'Ash1121', 'Curriculum Vita ', 'Afrin_Sultana_Asha.docx'),
(3, 'Mon1862', 'Curriculum Vita ', 'CV_of_Md__Moniruzzaman_017_-_copy.docx'),
(4, 'Aza1652', 'Curriculum Vita ', 'cv__imran_azad.doc'),
(5, 'Ahm1738', 'Curriculum Vita', 'Resume_of_Meraz_Ahmed.docx');

-- --------------------------------------------------------

--
-- Table structure for table `emp_assets`
--

CREATE TABLE `emp_assets` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `assets_id` int(11) NOT NULL,
  `given_date` date NOT NULL,
  `return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emp_experience`
--

CREATE TABLE `emp_experience` (
  `id` int(14) NOT NULL,
  `emp_id` varchar(256) DEFAULT NULL,
  `exp_company` varchar(128) DEFAULT NULL,
  `exp_com_position` varchar(128) DEFAULT NULL,
  `exp_com_address` varchar(128) DEFAULT NULL,
  `exp_workduration` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `emp_experience`
--

INSERT INTO `emp_experience` (`id`, `emp_id`, `exp_company`, `exp_com_position`, `exp_com_address`, `exp_workduration`) VALUES
(3, 'em123', 'Genitbd', 'Web Developer', 'Muktobanglashoping Complex', '12/02/2015'),
(4, 'Ash1121', 'BBC Media Action', 'Research Assistant', 'Collecting primary data, conducting FGD, KII and In-depth interviews as required. ', 'December 2010'),
(5, 'Ash1121', 'Edu-smart.info', 'Research Associate', 'R & D Department', 'March 2011'),
(6, 'Ash1121', 'Save the Children', 'Research Assistant', 'READ project', '18 March 2014'),
(7, 'Ash1121', 'BRAC University ', 'Research Assistant', 'Project “IWUSA” ', 'November 2014'),
(8, 'Aha1832', 'BRAC University ', 'Branch Manager', 'Targeting the Ultra poor Program (TUP)', 'September 2004'),
(9, 'Aha1832', 'Development Research Initiative (dRi)', 'Researcher', 'Collecting primary data, conducting FGD, KII and In-depth interviews as required. ', '2009'),
(10, 'Has1022', 'Data Analysis and Technical Assistance (DATA)', 'Research Assistant ', ' Data collection from field ', 'May 2014'),
(11, 'Mon1862', 'BRAC University ', ':  Junior Research Associate', 'Qualitative and quantitative data collection, 	Assisting in developing questionnaire and checklists for research', 'September 2011'),
(12, 'Aza1652', 'Hatil BD Ltd.', 'Brand Promotion Officer', 'Product promotion', 'February 2017'),
(13, 'Aza1652', 'Data Exchange', 'Data Entry Operator ', 'Data entry', 'December 2016'),
(14, 'Aza1652', 'Get Web Inc', 'Expert Content Writer', 'Writing web content', 'May 2017'),
(15, 'Aza1652', 'Gennext Communication', 'Content Writer & Editor ', 'Writing and editing web content, script,  promotional writings, proposal and other  files', 'October 2017'),
(16, 'Aza1652', 'ATN Times', 'Campus Correspondent', 'Report and new writing', 'June 2014'),
(17, 'Ahm1738', 'BRAC University', 'Research Assistant ', '66, Mohakhali, Dhaka ', '11 Months'),
(18, 'Ahm1738', 'Young Consultant ', 'Research Officer   ', 'House 357, Lane 27, New DOHS, Mohakhali, Dhaka ', '5 Months'),
(19, 'Ahm1738', 'IPA (Innovation For Poverty Action) ', 'Research Enumerator   ', 'Apt 6B, House 35, Road 7,Block G, Banani, Dhaka ', '12 Months'),
(20, 'Ahm1738', 'Plan Bangladesh ', 'Research Assistant ', 'House CWN (B) 14, Road 35, Gulshan 2, 1212  ', '8 Months');

-- --------------------------------------------------------

--
-- Table structure for table `emp_leave`
--

CREATE TABLE `emp_leave` (
  `id` int(11) NOT NULL,
  `em_id` varchar(64) DEFAULT NULL,
  `typeid` int(14) NOT NULL,
  `leave_type` varchar(64) DEFAULT NULL,
  `start_date` varchar(64) DEFAULT NULL,
  `end_date` varchar(64) DEFAULT NULL,
  `leave_duration` varchar(128) DEFAULT NULL,
  `apply_date` varchar(64) DEFAULT NULL,
  `reason` varchar(1024) DEFAULT NULL,
  `leave_status` enum('Approve','Not Approve','Rejected') NOT NULL DEFAULT 'Not Approve'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emp_leave`
--

INSERT INTO `emp_leave` (`id`, `em_id`, `typeid`, `leave_type`, `start_date`, `end_date`, `leave_duration`, `apply_date`, `reason`, `leave_status`) VALUES
(13, 'Aha1832', 8, 'More than One day', '2018-02-14', '2018-02-22', '7', '02/09/18', 'to attend University programs ', 'Approve'),
(14, 'Aza1652', 1, 'Half Day', '2018-02-12', '', '2', '02/12/18', 'giugigtgjkyuiygbkg', 'Approve'),
(15, 'Sha1790', 1, 'More than One day', '2018-02-12', '2018-02-15', '24', '02/12/18', 'gibgknthggvgvkk', 'Rejected'),
(16, 'Aza1652', 9, 'Half Day', '2018-02-12', '', '1', '02/12/18', 'asafasdfadfadf', 'Approve'),
(17, 'Aza1652', 8, 'Half Day', '2018-02-12', '', '3', '02/12/18', 'adfadfadfadf', 'Approve'),
(18, 'edr1432', 8, 'More than One day', '2018-02-12', '2018-02-15', '24', '02/12/18', 'dfasdfadsfa', 'Rejected'),
(19, 'Aha1832', 5, 'More than One day', '2018-02-14', '2018-02-22', '64', '02/20/18', 'personal Leave', 'Approve'),
(20, 'EMP1254478', 5, 'More than One day', '2018-02-15', '2018-02-22', '56', '02/15/18', 'lefedfdtrtjefodlfdgklfkfgdfd/fkd', 'Approve'),
(21, 'Aza1652', 2, 'Full Day', '2018-11-13', '', '8', '02/19/18', 'jhjyutgfgj', 'Approve'),
(22, 'Dha1415', 5, 'More than One day', '2018-02-01', '2018-02-03', '16', '02/19/18', 'nghutyjtrhth', 'Approve'),
(23, 'Has1013', 2, 'More than One day', '2018-02-05', '2018-07-08', '24', '02/20/18', 'dgfdgfgfgfdfdfdfdfdfd', 'Approve'),
(24, 'Ash1121', 1, 'Full Day', '2018-02-19', '', '8', '02/20/18', 'gfgfggfdsdd', 'Not Approve'),
(25, 'Dha1415', 2, 'More than One day', '2018-02-20', '2018-02-28', '64', '02/20/18', 'adfadfawefawfd', 'Approve'),
(26, 'Aza1652', 2, 'More than One day', '2018-02-07', '2018-02-08', '8', '02/20/18', ' vbvvgfhghgh', 'Approve'),
(27, 'Hos1156', 2, 'Full Day', '2018-03-27', '', '8', '03/28/18', 'asdfadfadfadf', 'Approve'),
(28, 'Hos1156', 5, 'Full Day', '2018-03-26', '', '8', '03/28/18', 'sdfftghsdfhgdf', 'Approve'),
(29, 'Hos1156', 2, 'More than One day', '2018-04-09', '2018-04-11', '16', '04/08/18', 'sick hazard', 'Approve'),
(30, 'EMP1254478', 5, 'More than One day', '2018-04-04', '2018-04-07', '24', '2018-04-09', 'earn leave', 'Approve'),
(31, 'EMP1254478', 5, 'More than One day', '2018-04-08', '2018-04-21', '104', '2018-04-09', 'dgdfgdfgdfgfd g fdgdf', 'Approve'),
(32, 'EMP1254478', 5, 'More than One day', '2018-04-08', '2018-04-21', '104', '2018-04-09', 'dgdfgdfgdfgfd g fdgdf', 'Approve'),
(33, 'EMP1254478', 5, 'More than One day', '2018-04-09', '2018-04-14', '40', '2018-04-09', 's fsdf dsf ds fds fds', 'Approve'),
(34, 'EMP1254478', 5, 'More than One day', '2018-04-10', '2018-04-21', '88', '2018-04-09', 'dh df gdfg fd gdf gfd', 'Approve'),
(35, 'EMP1254478', 5, 'More than One day', '2018-04-13', '2018-04-15', '16', '2018-04-09', 'fgd fgdg dfgd', 'Approve'),
(36, 'EMP1254478', 5, 'More than One day', '2018-04-11', '2018-04-14', '24', '2018-04-10', 'Due to Important Task!!!', 'Approve');

-- --------------------------------------------------------

--
-- Table structure for table `emp_penalty`
--

CREATE TABLE `emp_penalty` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `penalty_id` int(11) NOT NULL,
  `penalty_desc` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emp_salary`
--

CREATE TABLE `emp_salary` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `total` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `emp_salary`
--

INSERT INTO `emp_salary` (`id`, `emp_id`, `type_id`, `total`) VALUES
(1, 'Hos1156', 2, '25000'),
(2, 'Aha1832', 2, '42000'),
(3, 'Ahm1738', 1, '40000'),
(4, 'Akt1660', 2, '40080'),
(5, 'Ash1121', 2, '31780'),
(6, 'Has1056', 1, '30100'),
(7, 'Mon1862', 2, '30500'),
(8, 'Aza1652', 2, '21500'),
(9, 'Ala1118', 1, '26836'),
(10, 'Bar1085', 1, '28130'),
(11, 'Dha1415', 1, '33170'),
(12, 'Has1013', 1, '27500'),
(13, 'Has1448', 1, '33500'),
(14, 'Hos1097', 1, '28000'),
(15, 'Hos1757', 1, '30300'),
(16, 'Isl1249', 1, '7500'),
(17, 'Isl1385', 1, '20000'),
(18, 'Kha1210', 1, '29532'),
(19, 'Kha1297', 1, '49500'),
(20, 'Mal1316', 1, '28200'),
(21, 'Mir1685', 1, '10000'),
(22, 'Rah1682', 1, '15000'),
(23, 'Sid1871', 1, '22000'),
(24, 'EMP1254478', 3, '175000');

-- --------------------------------------------------------

--
-- Table structure for table `emp_training`
--

CREATE TABLE `emp_training` (
  `id` int(11) NOT NULL,
  `trainig_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `field_visit`
--

CREATE TABLE `field_visit` (
  `id` int(14) NOT NULL,
  `project_id` varchar(256) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `field_location` varchar(512) NOT NULL,
  `start_date` varchar(64) DEFAULT NULL,
  `approx_end_date` varchar(28) NOT NULL,
  `total_days` varchar(64) DEFAULT NULL,
  `notes` varchar(500) NOT NULL,
  `actual_return_date` varchar(28) NOT NULL,
  `status` enum('Approved','Not Approve','Rejected') NOT NULL DEFAULT 'Not Approve',
  `attendance_updated` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `field_visit`
--

INSERT INTO `field_visit` (`id`, `project_id`, `emp_id`, `field_location`, `start_date`, `approx_end_date`, `total_days`, `notes`, `actual_return_date`, `status`, `attendance_updated`) VALUES
(7, '2', 'Sah1804', 'Dhaka', '2018-02-15', '2018-02-21', '1', '', '2018-02-21', 'Rejected', ''),
(8, '2', 'Has1013', 'Dhaka', '2018-02-21', '2018-02-28', '7', '', '2018-02-28', 'Approved', 'done'),
(9, '3', 'Aza1652', 'hfhfgfgf', '2018-02-21', '2018-02-22', '1', 'vbvnhgmhggfgf', '', 'Approved', 'done'),
(10, '4', 'Dha1415', 'chittagong', '2018-02-23', '2018-02-28', '5', 'gkhfdhkfdfio', '2018-02-28', 'Approved', 'done'),
(11, '4', 'Dha1415', 'chittagong', '2018-02-23', '2018-02-28', '5', 'ffgkoipioi', '2018-02-28', 'Approved', 'done'),
(12, '3', 'Aha1832', 'dssds', '2018-02-08', '2018-02-11', '3', 'ddss', '2018-02-20', 'Approved', 'done'),
(13, '1', 'Aza1652', 'Dhaka', '2018-02-21', '2018-02-28', '7', 'adfadfadf', '2018-02-28', 'Approved', 'done'),
(14, '1', 'Aza1652', 'Dhaka', '2018-02-21', '2018-02-28', '7', 'adfadfadf', '', 'Approved', 'done'),
(15, '2', 'Aza1652', 'ewrtwyertwre', '2018-02-13', '2018-02-21', '8', 'rergwergtwertwrt', '', 'Approved', 'done'),
(16, '2', 'Isl1385', '', '2018-12-09', '', '', '', '', 'Approved', 'done'),
(17, '3', 'Ala1118', 'wewetwt', '2018-02-24', '2018-02-28', '4', 'ewwet wetewtetetewtwet', '2018-04-20', 'Approved', 'done'),
(18, '1', 'Hos1156', 'dhaka', '2018-03-28', '2018-03-29', '1', 'adsfadfadf', '', 'Approved', 'done'),
(19, '3', 'Sha1790', 'Dhaka', '2018-04-13', '2018-04-28', '15', 'Here ', '', 'Approved', 'done'),
(20, '1', 'Ash1121', 'Ctg', '2018-04-05', '2018-04-08', '3', 'Three days', '2018-04-06', 'Approved', 'done'),
(21, '1', 'Sah1804', 'khulna', '2018-04-09', '2018-04-14', '5', 'field visit', '2018-04-14', 'Approved', 'done'),
(22, '2', 'Ash1121', 'khulna', '2018-04-10', '2018-04-14', '4', 'fhf f df g dgfdgd', '2018-04-14', 'Approved', 'done'),
(23, '5', 'Sah1804', 'khulna', '2018-04-25', '2018-04-29', '4', 'When you are executing an SQL command for any RDBMS, the system determines the best way to carry out your request and SQL engine figures out how to interpret the task.', '', 'Approved', 'done');

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `id` int(11) NOT NULL,
  `holiday_name` varchar(256) DEFAULT NULL,
  `from_date` varchar(64) DEFAULT NULL,
  `to_date` varchar(64) DEFAULT NULL,
  `number_of_days` varchar(64) DEFAULT NULL,
  `year` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `holiday`
--

INSERT INTO `holiday` (`id`, `holiday_name`, `from_date`, `to_date`, `number_of_days`, `year`) VALUES
(1, 'Aids Day', '2017-12-21', '2017-12-29', '2', '2017'),
(3, 'Language Day', '2018-02-21', '2018-02-21', '1', '02-2018'),
(4, 'independent day', '2018-03-26', '', '1', '03-2018');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `type_id` int(14) NOT NULL,
  `name` varchar(64) NOT NULL,
  `leave_day` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES
(1, 'Casual Leave', '21', 1),
(2, 'Sick Leave', '15', 1),
(3, 'Maternity Leave', '90', 1),
(4, 'Paternal Leave', '7', 1),
(5, 'Earned leave', '', 1),
(7, 'Public Holiday', '', 1),
(8, 'Optional Leave', '', 1),
(9, 'Leave without Pay', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `id` int(14) NOT NULL,
  `emp_id` varchar(256) DEFAULT NULL,
  `amount` varchar(256) DEFAULT NULL,
  `interest_percentage` varchar(256) DEFAULT NULL,
  `total_amount` varchar(64) DEFAULT NULL,
  `total_pay` varchar(64) DEFAULT NULL,
  `total_due` varchar(64) DEFAULT NULL,
  `installment` varchar(256) DEFAULT NULL,
  `loan_number` varchar(256) DEFAULT NULL,
  `loan_details` varchar(256) DEFAULT NULL,
  `approve_date` varchar(256) DEFAULT NULL,
  `install_period` varchar(256) DEFAULT NULL,
  `status` enum('Granted','Deny','Pause','Done') NOT NULL DEFAULT 'Pause'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`id`, `emp_id`, `amount`, `interest_percentage`, `total_amount`, `total_pay`, `total_due`, `installment`, `loan_number`, `loan_details`, `approve_date`, `install_period`, `status`) VALUES
(26, 'Hos1156', '36000', NULL, NULL, '0', '0', '2400', '51105680', 'vbn vnbc vnvbn cvcncgnb gcbcvbcv', '2018-01-11', '15', 'Granted'),
(27, 'Soy1332', '250000', NULL, NULL, '0', '0', '15625', '33273566', 'fgg fdhgdfdh gfh gfhddfhfdhgfh f', '2018-02-02', '16', 'Granted'),
(28, 'Aza1652', '50000', NULL, NULL, '0', '0', '5000', '6224537', 'gutuiuiyfgdfguyiyr', '2018-02-12', '9', 'Granted'),
(29, 'Hos1156', '1200000', NULL, NULL, '0', '0', '100000', '9485568', 'dsg ddgdgdgddgd dgdgddg ddg dd dddgdfgdf dg gdgdfdfg.', '2018-02-23', '10', 'Done'),
(30, 'Dha1415', '50000', NULL, NULL, '0', '0', '5000', '6026791', 'medical loan', '2018-01-31', '9', 'Granted'),
(31, 'Isl1385', '12000', NULL, NULL, '1000', '11000', '1000', '11661289', 'fhjdfgjdfgjhfghdfgh', '2018-03-28', '10', 'Done'),
(32, 'Sah1804', '12000', NULL, NULL, '0', '0', '1000', '12958095', 'sefrgsdfgsdfgsgs', '2018-02-28', '12', 'Granted'),
(33, 'Isl1385', '25000', NULL, NULL, '25000', '0', '5000', '4008291', 'fdgdf gdf gdf gdf gdf gfd gdfg df gdf', '2018-04-03', '0', 'Done'),
(34, 'EMP1254478', '25000', NULL, NULL, '15000', '10000', '5000', '18194827', 'Akbor Ali Sir', '2018-04-19', '2', 'Granted');

-- --------------------------------------------------------

--
-- Table structure for table `loan_installment`
--

CREATE TABLE `loan_installment` (
  `id` int(14) NOT NULL,
  `loan_id` int(14) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `loan_number` varchar(256) DEFAULT NULL,
  `install_amount` varchar(256) DEFAULT NULL,
  `pay_amount` varchar(64) DEFAULT NULL,
  `app_date` varchar(256) DEFAULT NULL,
  `receiver` varchar(256) DEFAULT NULL,
  `install_no` varchar(256) DEFAULT NULL,
  `notes` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loan_installment`
--

INSERT INTO `loan_installment` (`id`, `loan_id`, `emp_id`, `loan_number`, `install_amount`, `pay_amount`, `app_date`, `receiver`, `install_no`, `notes`) VALUES
(1, 26, 'Hos1156', '51105680', '3000', NULL, '2018-01-26', 'fg gfhdhfgdhf', '11', 'gfhf fgdg hdgdgfhf hdfg'),
(2, 26, 'Hos1156', '51105680', '3000', NULL, '2018-01-04', NULL, '10', NULL),
(3, 26, 'Hos1156', '51105680', '3000', NULL, '2018-01-04', NULL, '9', NULL),
(4, 26, 'Hos1156', '51105680', '3000', NULL, '2018-01-25', 'fg gfhdhfgdhf', '8', 'dfg dfgbgfdg gfhfghgfg'),
(5, 26, 'Hos1156', '51105680', '3000', NULL, '2018-01-26', 'fg gfhdhfgdhf', '7', 'dfd gdfgfdgdf bdffdgdf'),
(6, 26, 'Hos1156', '51105680', '3000', NULL, '2018-02-03', 'fg gfhdhfgdhf', '6', 'fdg hdfgh gfhgf'),
(7, 26, 'Hos1156', '51105680', '3000', NULL, '2018-02-02', 'fg gfhdhfgdhf', '5', 'fdg dgdfgdfhgfgnhgfngh mjgmgng'),
(8, 26, 'Hos1156', '51105680', '3000', NULL, '2018-07-26', 'fg gfhdhfgdhf', '4', 'fh dfhgfh ftghngfhnfghghnghn gh ngf'),
(9, 26, 'Hos1156', '51105680', '3000', NULL, '2018-02-03', 'fg gfhdhfgdhf', '3', ' fdhfgnhgg mghn fnt bdfbgf  bggf'),
(10, 26, 'Hos1156', '51105680', '3000', NULL, '2018-02-03', 'fg gfhdhfgdhf', '2', 'df dgbdf bdfbg fgbbgfnfhn gfngf'),
(11, 26, 'Hos1156', '51105680', '3000', NULL, '2018-12-26', 'fg gfhdhfgdhf', '1', 'df gd gbdfh f gngfmgjmghnghh'),
(13, 26, 'Hos1156', '51105680', '3000', NULL, '2018-02-01', 'fg gfhdhfgdhf', '0', 'dfg d gdfgfdg fdg fd'),
(14, 28, 'Aza1652', '6224537', '5000', NULL, '2018-03-01', 'klkloiouyiyuiui', '9', 'yiiyiuoipoiol'),
(15, 29, 'Hos1156', '9485568', '100000', NULL, '2018-02-23', 'EMP2324', '11', 'dfg ddfgdgdgdfgfdfgfd dg dfgf.'),
(16, 29, 'Hos1156', '9485568', '100000', NULL, '2018-03-23', '345677', '10', 'gdfg g dfgfddg dgdfggdfggf'),
(17, 30, 'Dha1415', '6026791', '5000', NULL, '2018-03-01', 'accounts', '9', 'cut from salary'),
(18, 30, 'Dha1415', '6026791', '5000', NULL, '2018-02-21', NULL, '8', NULL),
(19, 30, 'Dha1415', '6026791', '5000', NULL, '2018-03-22', NULL, '8', NULL),
(20, 30, 'Dha1415', '6026791', '5000', NULL, '2018-03-22', NULL, '8', NULL),
(21, 31, 'Isl1385', '11661289', '12', NULL, '2018-04-04', NULL, '999', NULL),
(22, 31, 'Isl1385', '11661289', '1000', NULL, '2018-06-14', NULL, '11', NULL),
(23, 31, 'Isl1385', '11661289', '1000', NULL, '2018-03-28', NULL, '10', NULL),
(24, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-11', 'bsfsdff sfs', '4', ' f hfg dfg dff df gdf'),
(25, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-11', 'fhgfhf', '3', 'ff ghgfhgfhgf'),
(26, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-07', 'sftff', '2', ' fdg dfgdfgdfg df '),
(27, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-21', 'dsf dsf ds fds fdsf ds', '1', 'sf ds fsd fds fsd fdsf ds fsd'),
(28, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-04', 'dsdsff dsf ds f', '0', 'f dsfdsf dsfs'),
(29, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-04-05', NULL, '4', NULL),
(30, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-06-05', NULL, '3', NULL),
(31, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-06-07', NULL, '2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logistic_asset`
--

CREATE TABLE `logistic_asset` (
  `log_id` int(14) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `qty` varchar(64) DEFAULT NULL,
  `entry_date` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logistic_asset`
--

INSERT INTO `logistic_asset` (`log_id`, `name`, `qty`, `entry_date`) VALUES
(1, 'Lubricant', '30', '12/25/17');

-- --------------------------------------------------------

--
-- Table structure for table `logistic_assign`
--

CREATE TABLE `logistic_assign` (
  `ass_id` int(14) NOT NULL,
  `asset_id` int(14) NOT NULL,
  `assign_id` varchar(64) DEFAULT NULL,
  `project_id` int(14) NOT NULL,
  `task_id` int(14) NOT NULL,
  `log_qty` varchar(64) DEFAULT NULL,
  `start_date` varchar(64) DEFAULT NULL,
  `end_date` varchar(64) DEFAULT NULL,
  `back_date` varchar(64) DEFAULT NULL,
  `back_qty` varchar(64) DEFAULT NULL,
  `remarks` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logistic_assign`
--

INSERT INTO `logistic_assign` (`ass_id`, `asset_id`, `assign_id`, `project_id`, `task_id`, `log_qty`, `start_date`, `end_date`, `back_date`, `back_qty`, `remarks`) VALUES
(9, 13, 'Aza1652', 4, 6, '1', '2018-04-19', '2018-04-27', '2018-04-27', '1', 'gfd gdf gfdgdgd'),
(10, 14, 'Hos1156', 5, 10, '1', '2018-04-26', '2018-05-01', NULL, NULL, 'dfg fdg dfg fdg fdg fdg d');

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_url` varchar(256) DEFAULT NULL,
  `date` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`id`, `title`, `file_url`, `date`) VALUES
(1, 'gfghfg hfg dhg fdhdf df ghdfhfg hdfgh d fgh fghd rh gh dfg hgfh dfhfdh', 'nawjesh_jahan_soyeb.pdf', '2017-12-22'),
(2, 'fdgd gdsf gdfsgdfsg dfshfgjhgk hlyujhes wa ff sgdfn ghj ukjk jhmbn nvn', '13th_Dec.docx', '2017-12-23'),
(3, 'cgf gdfh fdhgfd hdfh dg bdffd', 'Thank-You-PNG-Clipart.png', '2017-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(64) NOT NULL,
  `owner_position` varchar(64) DEFAULT NULL,
  `note` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pay_salary`
--

CREATE TABLE `pay_salary` (
  `pay_id` int(14) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `type_id` int(14) NOT NULL,
  `month` varchar(64) DEFAULT NULL,
  `year` varchar(64) DEFAULT NULL,
  `paid_date` varchar(64) DEFAULT NULL,
  `total_days` varchar(64) DEFAULT NULL,
  `basic` varchar(64) DEFAULT NULL,
  `medical` varchar(64) DEFAULT NULL,
  `house_rent` varchar(64) DEFAULT NULL,
  `bonus` varchar(64) DEFAULT NULL,
  `bima` varchar(64) DEFAULT NULL,
  `tax` varchar(64) DEFAULT NULL,
  `provident_fund` varchar(64) DEFAULT NULL,
  `loan` varchar(64) DEFAULT NULL,
  `total_pay` varchar(128) DEFAULT NULL,
  `addition` int(128) NOT NULL,
  `diduction` int(128) NOT NULL,
  `status` enum('Paid','Process') DEFAULT 'Process',
  `paid_type` enum('Hand Cash','Bank') NOT NULL DEFAULT 'Bank'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pay_salary`
--

INSERT INTO `pay_salary` (`pay_id`, `emp_id`, `type_id`, `month`, `year`, `paid_date`, `total_days`, `basic`, `medical`, `house_rent`, `bonus`, `bima`, `tax`, `provident_fund`, `loan`, `total_pay`, `addition`, `diduction`, `status`, `paid_type`) VALUES
(25, 'EMP1254478', 0, 'February', '2018', '2018-03-28', '161.8', '175000', NULL, NULL, NULL, NULL, NULL, NULL, '0', '150000', 0, 25000, 'Paid', 'Hand Cash'),
(27, 'EMP1254478', 0, 'April', '2018', '2018-04-05', '80.4', '175000', NULL, NULL, NULL, NULL, NULL, NULL, '5000', '62644.23', 0, 107356, 'Paid', 'Hand Cash'),
(28, 'EMP1254478', 0, 'May', '2018', '2018-06-05', '26.5', '175000', NULL, NULL, NULL, NULL, NULL, NULL, '5000', '16469.91', 0, 153530, 'Paid', 'Hand Cash'),
(29, 'Aha1832', 0, 'April', '2018', '2018-05-05', '17.4', '42000', NULL, NULL, NULL, NULL, NULL, NULL, '0', '3513.46', 0, 38487, 'Paid', 'Bank'),
(30, 'Hos1156', 0, 'April', '2018', '2018-04-25', '40', '25000', NULL, NULL, NULL, NULL, NULL, NULL, '2400', '2407.60', 0, 22592, 'Process', 'Bank'),
(31, 'EMP1254478', 0, 'June', '2018', '2018-06-07', '50', '175000', NULL, NULL, NULL, NULL, NULL, NULL, '5000', '38750.00', 0, 136250, 'Paid', 'Hand Cash');

-- --------------------------------------------------------

--
-- Table structure for table `penalty`
--

CREATE TABLE `penalty` (
  `id` int(11) NOT NULL,
  `penalty_name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(14) NOT NULL,
  `pro_name` varchar(128) DEFAULT NULL,
  `pro_start_date` varchar(128) DEFAULT NULL,
  `pro_end_date` varchar(128) DEFAULT NULL,
  `pro_description` varchar(1024) DEFAULT NULL,
  `pro_summary` varchar(512) DEFAULT NULL,
  `pro_status` enum('upcoming','complete','running') NOT NULL DEFAULT 'running',
  `progress` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `pro_name`, `pro_start_date`, `pro_end_date`, `pro_description`, `pro_summary`, `pro_status`, `progress`) VALUES
(4, 'Endline Evaluation of the Girl’s Education Challenge Project – BRAC Maendeleo Tanzania', '2016-03-01', '2017-08-13', ' BRAC Maendeleo Tanzania (BRACMT) has been implementing the Girls Education Challenge (GEC) project since 2013 to address the barriers to girls’ education and improve the life chances of marginalized girls in Tanzania. This study aims to find out the impacts of girls’ study clubs on outcomes of interest (i.e. outcomes outlined in the log-frame) amongst eligible girls in targeted communities and amongst the club participant, the variation in participation and impact if the households of these girls make voluntary financial contributions to the clubs, if there is a trade-off between ensuring sustainability of the program and its take-up (and effectiveness) and whether the program have any unintended consequences on, for example, participating girls\' peer groups (including boys) or teachers’ efforts in the classroom. The deadline study is supposed to be conducted through learning assessment and household survey in the project’s operation areas: DSM, Tabora, Singida, Shinyanga, and Mwanza.', 'Endline Evaluation of the Girl’s Education Challenge Project – BRAC Maendeleo TanzaniaEndline Evaluation of the Girl’s Education Challenge Project – BRAC Maendeleo TanzaniaEndline Evaluation of the Girl’s Education Challenge Project – BRAC Maendeleo Tanzania', 'complete', NULL),
(5, 'What is SQL?', '2018-04-26', '2018-05-05', ' Why SQL?\r\nSQL is widely popular because it offers the following advantages ?\r\nAllows users to access data in the relational database management systems.\r\nAllows users to describe the data.\r\nAllows users to define the data in a database and manipulate that data.\r\n\r\nAllows to embed within other languages using SQL modules, libraries & pre-compilers.\r\nAllows users to create and drop databases and tables.\r\nAllows users to create view, stored procedure, functions in a database.\r\nAllows users to set permissions on tables, procedures and views.', 'SQL is Structured Query Language, which is a computer language for storing, manipulating and retrieving data stored in a relational database.', 'running', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_file`
--

CREATE TABLE `project_file` (
  `id` int(14) NOT NULL,
  `pro_id` int(14) NOT NULL,
  `file_details` varchar(1028) DEFAULT NULL,
  `file_url` varchar(256) DEFAULT NULL,
  `assigned_to` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project_file`
--

INSERT INTO `project_file` (`id`, `pro_id`, `file_details`, `file_url`, `assigned_to`) VALUES
(2, 5, 'fgdg dfg dfs gdfs gdfssg dsgsdddfg fds gdsfg gdgsdg dsgdsfg dsfgdsf gdsgsd gs', 'microwaveass3.docx', '456345');

-- --------------------------------------------------------

--
-- Table structure for table `pro_expenses`
--

CREATE TABLE `pro_expenses` (
  `id` int(14) NOT NULL,
  `pro_id` int(14) NOT NULL,
  `assign_to` varchar(64) DEFAULT NULL,
  `details` varchar(512) DEFAULT NULL,
  `amount` varchar(256) DEFAULT NULL,
  `date` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pro_expenses`
--

INSERT INTO `pro_expenses` (`id`, `pro_id`, `assign_to`, `details`, `amount`, `date`) VALUES
(1, 4, 'EMP1254478', 'fdgdf gfd gd', '2500', '2018-04-26'),
(3, 5, 'Hos1156', 'Traveling to Chattragram', '2200', '2018-04-25');

-- --------------------------------------------------------

--
-- Table structure for table `pro_notes`
--

CREATE TABLE `pro_notes` (
  `id` int(14) NOT NULL,
  `assign_to` varchar(64) DEFAULT NULL,
  `pro_id` int(14) NOT NULL,
  `details` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pro_notes`
--

INSERT INTO `pro_notes` (`id`, `assign_to`, `pro_id`, `details`) VALUES
(1, 'EMP1254478', 4, 'dddddddddgggggggggggggg'),
(2, 'EMP1254478', 4, 'dddddddd dddddddddddddd'),
(4, 'Hos1156', 5, 'The standard SQL commands to interact with relational databases are CREATE, SELECT, INSERT, UPDATE, DELETE and DROP.');

-- --------------------------------------------------------

--
-- Table structure for table `pro_task`
--

CREATE TABLE `pro_task` (
  `id` int(14) NOT NULL,
  `pro_id` int(14) NOT NULL,
  `task_title` varchar(256) DEFAULT NULL,
  `start_date` varchar(128) DEFAULT NULL,
  `end_date` varchar(128) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `task_type` enum('Office','Field') NOT NULL DEFAULT 'Office',
  `status` enum('running','complete','cancel') DEFAULT 'running',
  `location` varchar(512) DEFAULT NULL,
  `return_date` varchar(128) DEFAULT NULL,
  `total_days` varchar(128) DEFAULT NULL,
  `create_date` varchar(128) DEFAULT NULL,
  `approve_status` enum('Approved','Not Approve','Rejected') NOT NULL DEFAULT 'Not Approve'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pro_task`
--

INSERT INTO `pro_task` (`id`, `pro_id`, `task_title`, `start_date`, `end_date`, `image`, `description`, `task_type`, `status`, `location`, `return_date`, `total_days`, `create_date`, `approve_status`) VALUES
(6, 4, 'test for demo', '01/25/2018', '02/10/2018', NULL, 'test for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demotest for demo', 'Field', 'running', 'Uganda', NULL, NULL, '22:01:18', 'Not Approve'),
(7, 4, 'dgfdgdf gdf gdf', '04/17/2018', '05/03/2018', NULL, 'x vsdfds fdsfsd', 'Office', 'running', NULL, NULL, NULL, '09:04:18', ''),
(8, 4, 'dgdf gdf gdfg dfg dfg df', '04/10/2018', '04/21/2018', NULL, 'dfgvfd gfdg dfgdfgdfg df', 'Office', 'running', NULL, NULL, NULL, '09:04:18', ''),
(10, 5, 'A Brief History of SQL', '04/27/2018', '04/29/2018', NULL, '1970 − Dr. Edgar F. \"Ted\" Codd of IBM is known as the father of relational databases. He described a relational model for databases.\r\n1974 − Structured Query Language appeared.\r\n1978 − IBM worked to develop Codd\'s ideas and released a product named System/R.\r\n1986 − IBM developed the first prototype of relational database and standardized by ANSI. The first relational database was released by Relational Software which later came to be known as Oracle.', 'Office', 'running', NULL, NULL, NULL, '10:04:18', ''),
(11, 4, 'A Brief History of SQL', '04/28/2018', '04/30/2018', NULL, 'When you are executing an SQL command for any RDBMS, the system determines the best way to carry out your request and SQL engine figures out how to interpret the task.', 'Office', 'running', NULL, NULL, NULL, '2018-04-10', ''),
(12, 4, 'A Brief History of SQL', '04/27/2018', '04/29/2018', NULL, 'When you are executing an SQL command for any RDBMS, the system determines the best way to carry out your request and SQL engine figures out how to interpret the task.', 'Office', 'running', NULL, NULL, NULL, '2018-04-10', ''),
(13, 4, 'A Brief History of SQL', '09/20/2016', '09/23/2016', NULL, 'When you are executing an SQL command for any RDBMS, the system determines the best way to carry out your request and SQL engine figures out how to interpret the task.', 'Office', 'running', NULL, NULL, NULL, '2018-04-10', ''),
(14, 4, 'A Brief History of SQL', '2018-04-28', '2018-05-02', NULL, 'When you are executing an SQL command for any RDBMS, the system determines the best way to carry out your request and SQL engine figures out how to interpret the task.', 'Office', 'running', NULL, NULL, NULL, '2018-04-10', '');

-- --------------------------------------------------------

--
-- Table structure for table `pro_task_assets`
--

CREATE TABLE `pro_task_assets` (
  `id` int(11) NOT NULL,
  `pro_task_id` int(11) NOT NULL,
  `assign_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salary_type`
--

CREATE TABLE `salary_type` (
  `id` int(14) NOT NULL,
  `salary_type` varchar(256) DEFAULT NULL,
  `create_date` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salary_type`
--

INSERT INTO `salary_type` (`id`, `salary_type`, `create_date`) VALUES
(1, 'Hourly', '2017-11-22'),
(2, 'Monthly', '2017-12-30'),
(3, 'hhfgf', '2017-12-29'),
(4, 'Hourly', '2018-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `sitelogo` varchar(128) DEFAULT NULL,
  `sitetitle` varchar(256) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `copyright` varchar(128) DEFAULT NULL,
  `contact` varchar(128) DEFAULT NULL,
  `currency` varchar(128) DEFAULT NULL,
  `symbol` varchar(64) DEFAULT NULL,
  `system_email` varchar(128) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `address2` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `sitelogo`, `sitetitle`, `description`, `copyright`, `contact`, `currency`, `symbol`, `system_email`, `address`, `address2`) VALUES
(1, 'HRPAYROLL11.png', 'Development Research Initiative (dRi)', 'Prochesta Foundation aims at the upliftment & betterment of people living below the poverty line.', 'GenIT Bangladesh', '017112233445', 'BDT', '$', 'contact@dri-int.org', 'House 39/7 (First Floor) Hazi Ali Hossain Road', 'Dhaka');

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` int(14) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `facebook` varchar(256) DEFAULT NULL,
  `twitter` varchar(256) DEFAULT NULL,
  `google_plus` varchar(512) DEFAULT NULL,
  `skype_id` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `social_media`
--

INSERT INTO `social_media` (`id`, `emp_id`, `facebook`, `twitter`, `google_plus`, `skype_id`) VALUES
(1, 'Hos1156', 'https://www.facebook.com', 'twitter', 'https://mail.google.com', 'njsoyeb'),
(4, 'Mal1316', 'https://www.facebook.com/james.sujitm', 'https://twitter.com/JamesSujit2', 'https://plus.google.com/u/0/106542495496624037234', '');

-- --------------------------------------------------------

--
-- Table structure for table `to-do_list`
--

CREATE TABLE `to-do_list` (
  `id` int(14) NOT NULL,
  `user_id` varchar(64) DEFAULT NULL,
  `to_dodata` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(128) DEFAULT NULL,
  `value` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `to-do_list`
--

INSERT INTO `to-do_list` (`id`, `user_id`, `to_dodata`, `date`, `value`) VALUES
(20, 'em123', 'cf bdf dfs gfdg df gdf', '2017-12-22 07:00:40pm', '1'),
(21, 'em123', 'dghdgdfhdfhdhgd dfgh dfhfd', '2017-12-22 08:24:35pm', '0'),
(22, 'em123', 'We\'ve got a Christmas favour to ask', '2017-12-22 08:25:38pm', '1'),
(23, 'em123', 'ভাইয়ের মৃত্যুর খবর পড়লেন উপস্থাপক', '2017-12-22 08:28:44pm', '1'),
(24, 'em123', 'ঢাকা - বগুড়া - রংপুর - ঢাকা রুটে যাবেন', '2017-12-22 08:29:20pm', '1'),
(25, 'em123', 'নিজের পছন্দের বাস, সিট, এবং সময়মত', '2017-12-22 08:31:27pm', '1'),
(26, 'em123', 'নিজের পছন্দের বাস, সিট, এবং সময়মত', '2017-12-22 08:31:36pm', '1'),
(27, 'em123', 'নিজের পছন্দের বাস, সিট, এবং সময়মত', '2017-12-22 08:31:46pm', '1'),
(28, 'em123', 'নিজের পছন্দের বাস, সিট, এবং সময়মত', '2017-12-22 08:31:46pm', '0'),
(29, 'em123', 'dghdgdfhdfhdhgd dfgh dfhfd', '2017-12-24 05:05:54am', '1'),
(30, 'EMP1254478', 'fghf hfgh fdhdf', '2018-01-01 04:50:28pm', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addition`
--
ALTER TABLE `addition`
  ADD PRIMARY KEY (`addi_id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`ass_id`);

--
-- Indexes for table `assets_category`
--
ALTER TABLE `assets_category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `assign_leave`
--
ALTER TABLE `assign_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assign_task`
--
ALTER TABLE `assign_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_info`
--
ALTER TABLE `bank_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deduction`
--
ALTER TABLE `deduction`
  ADD PRIMARY KEY (`de_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `desciplinary`
--
ALTER TABLE `desciplinary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `earned_leave`
--
ALTER TABLE `earned_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_file`
--
ALTER TABLE `employee_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_assets`
--
ALTER TABLE `emp_assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_experience`
--
ALTER TABLE `emp_experience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_leave`
--
ALTER TABLE `emp_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_penalty`
--
ALTER TABLE `emp_penalty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_salary`
--
ALTER TABLE `emp_salary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `field_visit`
--
ALTER TABLE `field_visit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_installment`
--
ALTER TABLE `loan_installment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logistic_asset`
--
ALTER TABLE `logistic_asset`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logistic_assign`
--
ALTER TABLE `logistic_assign`
  ADD PRIMARY KEY (`ass_id`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_salary`
--
ALTER TABLE `pay_salary`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_file`
--
ALTER TABLE `project_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pro_expenses`
--
ALTER TABLE `pro_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pro_notes`
--
ALTER TABLE `pro_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pro_task`
--
ALTER TABLE `pro_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pro_task_assets`
--
ALTER TABLE `pro_task_assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_type`
--
ALTER TABLE `salary_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `to-do_list`
--
ALTER TABLE `to-do_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addition`
--
ALTER TABLE `addition`
  MODIFY `addi_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `ass_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `assets_category`
--
ALTER TABLE `assets_category`
  MODIFY `cat_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `assign_leave`
--
ALTER TABLE `assign_leave`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `assign_task`
--
ALTER TABLE `assign_task`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- AUTO_INCREMENT for table `bank_info`
--
ALTER TABLE `bank_info`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `deduction`
--
ALTER TABLE `deduction`
  MODIFY `de_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `desciplinary`
--
ALTER TABLE `desciplinary`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `earned_leave`
--
ALTER TABLE `earned_leave`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `employee_file`
--
ALTER TABLE `employee_file`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `emp_assets`
--
ALTER TABLE `emp_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_experience`
--
ALTER TABLE `emp_experience`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `emp_leave`
--
ALTER TABLE `emp_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `emp_penalty`
--
ALTER TABLE `emp_penalty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_salary`
--
ALTER TABLE `emp_salary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `field_visit`
--
ALTER TABLE `field_visit`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `holiday`
--
ALTER TABLE `holiday`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `type_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `loan_installment`
--
ALTER TABLE `loan_installment`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `logistic_asset`
--
ALTER TABLE `logistic_asset`
  MODIFY `log_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logistic_assign`
--
ALTER TABLE `logistic_assign`
  MODIFY `ass_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pay_salary`
--
ALTER TABLE `pay_salary`
  MODIFY `pay_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_file`
--
ALTER TABLE `project_file`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pro_expenses`
--
ALTER TABLE `pro_expenses`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pro_notes`
--
ALTER TABLE `pro_notes`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pro_task`
--
ALTER TABLE `pro_task`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pro_task_assets`
--
ALTER TABLE `pro_task_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_type`
--
ALTER TABLE `salary_type`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `to-do_list`
--
ALTER TABLE `to-do_list`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

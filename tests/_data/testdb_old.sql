-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 28, 2021 at 04:46 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT current_timestamp(),
  `end_date` date DEFAULT NULL,
  `buying_price` float DEFAULT 1,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `start_date`, `end_date`, `buying_price`, `token`) VALUES
(1, 'Lars Monsen', '2021-03-15', NULL, 0, '2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85'),
(2, 'Snowy Plains Inc.', '2005-07-11', NULL, 0.65, '99f72d7e511685bae6517db832f1ee328538d8414470974ad53b94612fa7aa1e'),
(3, 'Snowy Plains Asker', '2012-01-12', NULL, 0.65, '61973af54a323dd2d702219b86b494b0da247839eb1937ccff1e06e59e0934c3'),
(4, 'Snegutta', '2018-09-19', NULL, 0.8, '03b936e1b6f4bf1399253dbd4b2ddae49170572f107d8c13304dca880e689545');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `department` enum('customer-rep','production-planner','storekeeper') NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `department`, `token`) VALUES
(1, 'Sylvester', 'Sølvtunge', 'customer-rep', '839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261'),
(2, 'Njalle', 'Nøysom', 'production-planner', '022224c9a11805494a77796d671bec4c5bae495af78e906694018dbbc39bf2cd'),
(3, 'Didrik', 'Disk', 'storekeeper', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');

-- --------------------------------------------------------

--
-- Table structure for table `franchises`
--

DROP TABLE IF EXISTS `franchises`;
CREATE TABLE IF NOT EXISTS `franchises` (
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `franchises`
--

INSERT INTO `franchises` (`customer_id`, `shipping_address`) VALUES
(2, 'Bakgata 32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_nr` int(11) NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL,
  `state` enum('new','open','skis-available','ready-for-shipping','shipped') DEFAULT 'new',
  `customer_id` int(11) NOT NULL,
  `date_placed` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_aggregate` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_nr`),
  KEY `orders_customers_fk` (`customer_id`),
  KEY `orders_aggregates_fk` (`order_aggregate`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_nr`, `price`, `state`, `customer_id`, `date_placed`, `order_aggregate`) VALUES
(1, 208000, 'new', 2, '2021-03-21 23:00:00', 1),
(2, 58500, 'new', 2, '2021-03-21 23:00:00', 1),
(3, 32175, 'open', 3, '2021-03-18 23:00:00', NULL),
(4, 7200, 'skis-available', 4, '2021-03-14 23:00:00', NULL),
(5, 9600, 'skis-available', 1, '2021-03-16 23:00:00', NULL),
(6, 5330, 'new', 2, '2021-03-16 23:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_aggregates`
--

DROP TABLE IF EXISTS `order_aggregates`;
CREATE TABLE IF NOT EXISTS `order_aggregates` (
  `aggregate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`aggregate_id`),
  KEY `order_aggregates_customer_fk` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_aggregates`
--

INSERT INTO `order_aggregates` (`aggregate_id`, `customer_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

DROP TABLE IF EXISTS `order_history`;
CREATE TABLE IF NOT EXISTS `order_history` (
  `order_nr` int(11) NOT NULL AUTO_INCREMENT,
  `state` enum('open','skis-available','shipped') NOT NULL,
  `customer_rep` int(11) NOT NULL,
  `changed_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_nr`,`state`),
  KEY `order_history_employees_fk` (`customer_rep`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`order_nr`, `state`, `customer_rep`, `changed_date`) VALUES
(3, 'open', 1, '2021-03-11 23:00:00'),
(4, 'open', 1, '2021-03-18 23:00:00'),
(4, 'skis-available', 1, '2021-03-19 23:00:00'),
(5, 'open', 1, '2021-03-21 23:00:00'),
(5, 'skis-available', 1, '2021-03-21 23:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `production_plans`
--

DROP TABLE IF EXISTS `production_plans`;
CREATE TABLE IF NOT EXISTS `production_plans` (
  `ski_type` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`ski_type`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `production_plans`
--

INSERT INTO `production_plans` (`ski_type`, `day`, `quantity`) VALUES
(2, '2021-04-28', 100),
(2, '2021-03-28', 100),
(2, '2021-02-28', 100),
(2, '2021-01-28', 100),
(2, '2020-01-28', 100),
(2, '2021-05-28', 100);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
CREATE TABLE IF NOT EXISTS `shipments` (
  `shipment_nr` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `scheduled_pickup` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `state` enum('ready','picked-up') DEFAULT 'ready',
  `order_nr` int(11) NOT NULL,
  `transporter` varchar(255) NOT NULL,
  `driver_id` int(11) NOT NULL,
  PRIMARY KEY (`shipment_nr`),
  KEY `shipments_orders_fk` (`order_nr`),
  KEY `shipments_transporters_fk` (`transporter`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`shipment_nr`, `customer_id`, `shipping_address`, `scheduled_pickup`, `state`, `order_nr`, `transporter`, `driver_id`) VALUES
(1, 4, 'Gaten 41', '2021-03-26 23:00:00', 'picked-up', 4, 'Reposisjoneringspatruljen', 123167),
(2, 1, 'Monsensgate 1', '2021-04-04 22:00:00', 'ready', 5, 'Flyttegutta A/S', 120943);

-- --------------------------------------------------------

--
-- Table structure for table `skis`
--

DROP TABLE IF EXISTS `skis`;
CREATE TABLE IF NOT EXISTS `skis` (
  `serial_nr` int(11) NOT NULL AUTO_INCREMENT,
  `ski_type` int(11) NOT NULL,
  `manufactured_date` date DEFAULT current_timestamp(),
  `order_assigned` int(11) DEFAULT NULL,
  PRIMARY KEY (`serial_nr`),
  KEY `skis_skitypes_fk` (`ski_type`),
  KEY `skis_orders_fk` (`order_assigned`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `skis`
--

INSERT INTO `skis` (`serial_nr`, `ski_type`, `manufactured_date`, `order_assigned`) VALUES
(1, 1, '2021-02-21', 5),
(2, 1, '2021-02-21', 5),
(3, 1, '2021-02-21', 5),
(4, 1, '2021-02-21', NULL),
(5, 1, '2021-02-21', NULL),
(6, 1, '2021-02-21', NULL),
(7, 1, '2021-02-21', NULL),
(8, 1, '2021-02-21', NULL),
(9, 1, '2021-02-21', NULL),
(10, 2, '2021-01-29', 4),
(11, 2, '2021-01-29', 4),
(12, 2, '2021-01-29', 4),
(13, 2, '2021-01-29', 4),
(14, 2, '2021-01-29', 4),
(15, 2, '2021-01-29', NULL),
(16, 2, '2021-01-29', NULL),
(17, 2, '2021-01-29', NULL),
(18, 2, '2021-01-29', NULL),
(19, 3, '2021-03-02', NULL),
(20, 3, '2021-03-02', NULL),
(21, 3, '2021-03-02', NULL),
(22, 3, '2021-03-02', NULL),
(23, 3, '2021-03-02', NULL),
(24, 3, '2021-03-02', NULL),
(25, 3, '2021-03-02', NULL),
(26, 3, '2021-03-02', NULL),
(27, 3, '2021-03-02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ski_types`
--

DROP TABLE IF EXISTS `ski_types`;
CREATE TABLE IF NOT EXISTS `ski_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `temperature` enum('cold','warm') NOT NULL,
  `grip` varchar(255) NOT NULL,
  `size` enum('142','147','152','157','162','167','172','177','182','187','192','197','202','207') NOT NULL,
  `weight_class` enum('20-30','30-40','40-50','50-60','60-70','70-80','80-90','90+') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `historical` tinyint(1) DEFAULT 0,
  `photo_url` varchar(255) DEFAULT 'photo-url',
  `msrp` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ski_types`
--

INSERT INTO `ski_types` (`type_id`, `model`, `type`, `temperature`, `grip`, `size`, `weight_class`, `description`, `historical`, `photo_url`, `msrp`) VALUES
(1, 'Active Pro', 'Skate', 'cold', 'IntelliWax', '182', '50-60', 'Good skis.', 0, 'photo-url', 3200),
(2, 'Redline', 'Classic', 'warm', 'Grippers', '167', '40-50', 'Slightly small skis.', 0, 'photo-url', 1800),
(3, 'Active Plain', 'Skate', 'cold', 'Handles', '197', '80-90', 'For big boys.', 1, 'photo-url', 1650);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
CREATE TABLE IF NOT EXISTS `stores` (
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `franchise_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  KEY `stores_franchises_fk` (`franchise_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`customer_id`, `shipping_address`, `franchise_id`) VALUES
(3, 'Askervegen 2', 2),
(4, 'Gaten 41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_orders`
--

DROP TABLE IF EXISTS `sub_orders`;
CREATE TABLE IF NOT EXISTS `sub_orders` (
  `order_nr` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `ski_quantity` int(11) NOT NULL,
  PRIMARY KEY (`order_nr`,`type_id`),
  KEY `sub_orders_ski_types_fk` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_orders`
--

INSERT INTO `sub_orders` (`order_nr`, `type_id`, `ski_quantity`) VALUES
(1, 1, 100),
(2, 2, 50),
(3, 3, 30),
(4, 2, 5),
(5, 1, 3),
(6, 1, 2),
(6, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_skiers`
--

DROP TABLE IF EXISTS `team_skiers`;
CREATE TABLE IF NOT EXISTS `team_skiers` (
  `customer_id` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `club` varchar(255) NOT NULL,
  `skis_per_year` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team_skiers`
--

INSERT INTO `team_skiers` (`customer_id`, `birthdate`, `club`, `skis_per_year`) VALUES
(1, '1963-04-21', 'Uteklubben', 5);

-- --------------------------------------------------------

--
-- Table structure for table `transporters`
--

DROP TABLE IF EXISTS `transporters`;
CREATE TABLE IF NOT EXISTS `transporters` (
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transporters`
--

INSERT INTO `transporters` (`name`) VALUES
('Flyttegutta A/S'),
('Reposisjoneringspatruljen');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

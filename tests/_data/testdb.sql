-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2021 at 12:18 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ski_manufacturer`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT current_timestamp(),
  `end_date` date DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `start_date`, `end_date`, `token`) VALUES
(1, 'Lars Monsen', '2021-03-15', NULL, NULL),
(2, 'Snowy Plains Inc.', '2005-07-11', NULL, NULL),
(3, 'Snowy Plains Asker', '2012-01-12', NULL, NULL),
(4, 'Snegutta', '2018-09-19', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `department` enum('customer_rep','production-planner','storekeeper') NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `department`, `token`) VALUES
(1, 'Sylvester', 'Sølvtunge', '', NULL),
(2, 'Njalle', 'Nøysom', '', NULL),
(3, 'Didrik', 'Disk', 'storekeeper', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `franchises`
--

CREATE TABLE `franchises` (
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `buying_price` float DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `franchises`
--

INSERT INTO `franchises` (`customer_id`, `shipping_address`, `buying_price`) VALUES
(2, 'Bakgata 32', 0.65);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_nr` int(11) NOT NULL,
  `ski_type` int(11) NOT NULL,
  `ski_quantity` int(11) DEFAULT 1,
  `price` int(11) NOT NULL,
  `state` enum('new','open','skis-available') DEFAULT 'new',
  `customer_id` int(11) NOT NULL,
  `date_placed` date DEFAULT current_timestamp(),
  `order_aggregate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_nr`, `ski_type`, `ski_quantity`, `price`, `state`, `customer_id`, `date_placed`, `order_aggregate`) VALUES
(1, 1, 100, 208000, 'new', 2, '2021-03-22', 1),
(2, 2, 50, 58500, 'new', 2, '2021-03-22', 1),
(3, 3, 30, 32175, 'open', 3, '2021-03-19', NULL),
(4, 2, 5, 7200, 'skis-available', 4, '2021-03-15', NULL),
(5, 1, 3, 9600, 'skis-available', 1, '2021-03-17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_aggregates`
--

CREATE TABLE `order_aggregates` (
  `aggregate_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_aggregates`
--

INSERT INTO `order_aggregates` (`aggregate_id`, `customer_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `order_nr` int(11) NOT NULL,
  `state` enum('open','skis-available') NOT NULL,
  `customer_rep` int(11) NOT NULL,
  `changed_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`order_nr`, `state`, `customer_rep`, `changed_date`) VALUES
(3, 'open', 1, '2021-03-12 00:00:00'),
(4, 'open', 1, '2021-03-19 00:00:00'),
(4, 'skis-available', 1, '2021-03-20 00:00:00'),
(5, 'open', 1, '2021-03-22 00:00:00'),
(5, 'skis-available', 1, '2021-03-22 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `production_plans`
--

CREATE TABLE `production_plans` (
  `ski_type` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `shipment_nr` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `scheduled_pickup` datetime NOT NULL,
  `state` enum('ready','picked-up') DEFAULT 'ready',
  `order_nr` int(11) NOT NULL,
  `transporter` varchar(255) NOT NULL,
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`shipment_nr`, `customer_id`, `shipping_address`, `scheduled_pickup`, `state`, `order_nr`, `transporter`, `driver_id`) VALUES
(1, 4, 'Gaten 41', '2021-03-27 00:00:00', 'picked-up', 4, 'Reposisjoneringspatruljen', 123167),
(2, 1, 'Monsensgate 1', '2021-04-05 00:00:00', 'ready', 5, 'Flyttegutta A/S', 120943);

-- --------------------------------------------------------

--
-- Table structure for table `skis`
--

CREATE TABLE `skis` (
  `serial_nr` int(11) NOT NULL,
  `ski_type` int(11) NOT NULL,
  `manufactured_date` date DEFAULT current_timestamp(),
  `order_assigned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `ski_types` (
  `type_id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `temperature` enum('cold','warm') NOT NULL,
  `grip` varchar(255) NOT NULL,
  `size` enum('142','147','152','157','162','167','172','177','182','187','192','197','202','207') NOT NULL,
  `weight_class` enum('20-30','30-40','40-50','50-60','60-70','70-80','80-90','90+') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `historical` tinyint(1) DEFAULT 0,
  `photo_url` varchar(255) DEFAULT 'photo-url',
  `msrp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `stores` (
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `buying_price` float NOT NULL,
  `franchise_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`customer_id`, `shipping_address`, `buying_price`, `franchise_id`) VALUES
(3, 'Askervegen 2', 0.65, 2),
(4, 'Gaten 41', 0.8, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `team_skiers`
--

CREATE TABLE `team_skiers` (
  `customer_id` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `club` varchar(255) NOT NULL,
  `skis_per_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `team_skiers`
--

INSERT INTO `team_skiers` (`customer_id`, `birthdate`, `club`, `skis_per_year`) VALUES
(1, '1963-04-21', 'Uteklubben', 5);

-- --------------------------------------------------------

--
-- Table structure for table `transporters`
--

CREATE TABLE `transporters` (
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transporters`
--

INSERT INTO `transporters` (`name`) VALUES
('Flyttegutta A/S'),
('Reposisjoneringspatruljen');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `franchises`
--
ALTER TABLE `franchises`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_nr`),
  ADD KEY `orders_ski_types_fk` (`ski_type`),
  ADD KEY `orders_customers_fk` (`customer_id`),
  ADD KEY `orders_aggregates_fk` (`order_aggregate`);

--
-- Indexes for table `order_aggregates`
--
ALTER TABLE `order_aggregates`
  ADD PRIMARY KEY (`aggregate_id`),
  ADD KEY `order_aggregates_customer_fk` (`customer_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`order_nr`,`state`),
  ADD KEY `order_history_employees_fk` (`customer_rep`);

--
-- Indexes for table `production_plans`
--
ALTER TABLE `production_plans`
  ADD PRIMARY KEY (`ski_type`,`day`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`shipment_nr`),
  ADD KEY `shipments_orders_fk` (`order_nr`),
  ADD KEY `shipments_transporters_fk` (`transporter`);

--
-- Indexes for table `skis`
--
ALTER TABLE `skis`
  ADD PRIMARY KEY (`serial_nr`),
  ADD KEY `skis_skitypes_fk` (`ski_type`),
  ADD KEY `skis_orders_fk` (`order_assigned`);

--
-- Indexes for table `ski_types`
--
ALTER TABLE `ski_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `stores_franchises_fk` (`franchise_id`);

--
-- Indexes for table `team_skiers`
--
ALTER TABLE `team_skiers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `transporters`
--
ALTER TABLE `transporters`
  ADD PRIMARY KEY (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_nr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_aggregates`
--
ALTER TABLE `order_aggregates`
  MODIFY `aggregate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `order_nr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `shipment_nr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skis`
--
ALTER TABLE `skis`
  MODIFY `serial_nr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `ski_types`
--
ALTER TABLE `ski_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `franchises`
--
ALTER TABLE `franchises`
  ADD CONSTRAINT `franchises_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_aggregates_fk` FOREIGN KEY (`order_aggregate`) REFERENCES `order_aggregates` (`aggregate_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_customers_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ski_types_fk` FOREIGN KEY (`ski_type`) REFERENCES `ski_types` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_aggregates`
--
ALTER TABLE `order_aggregates`
  ADD CONSTRAINT `order_aggregates_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_employees_fk` FOREIGN KEY (`customer_rep`) REFERENCES `employees` (`employee_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_history_orders_fk` FOREIGN KEY (`order_nr`) REFERENCES `orders` (`order_nr`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `production_plans`
--
ALTER TABLE `production_plans`
  ADD CONSTRAINT `production_plans_ski_types_fk` FOREIGN KEY (`ski_type`) REFERENCES `ski_types` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_orders_fk` FOREIGN KEY (`order_nr`) REFERENCES `orders` (`order_nr`) ON UPDATE CASCADE,
  ADD CONSTRAINT `shipments_transporters_fk` FOREIGN KEY (`transporter`) REFERENCES `transporters` (`name`) ON UPDATE CASCADE;

--
-- Constraints for table `skis`
--
ALTER TABLE `skis`
  ADD CONSTRAINT `skis_orders_fk` FOREIGN KEY (`order_assigned`) REFERENCES `orders` (`order_nr`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `skis_skitypes_fk` FOREIGN KEY (`ski_type`) REFERENCES `ski_types` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stores_franchises_fk` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `team_skiers`
--
ALTER TABLE `team_skiers`
  ADD CONSTRAINT `team_skiers_customers_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2021 at 01:13 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prosjektv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `department` enum('customer_rep','production_planner','storekeeper') COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchises`
--

CREATE TABLE `franchises` (
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `buying_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_nr` int(11) NOT NULL,
  `ski_type` int(11) DEFAULT NULL,
  `ski_quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `state` enum('new','open','skis-available') COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `placed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_aggregates`
--

CREATE TABLE `order_aggregates` (
  `aggregate_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `order_nr` int(11) NOT NULL,
  `state` enum('new','open','skis-available') COLLATE utf8mb4_danish_ci NOT NULL,
  `customer_rep` int(11) DEFAULT NULL,
  `changed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_plans`
--

CREATE TABLE `production_plans` (
  `ski_type` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `shipment_nr` int(11) NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `scheduled_pickup` datetime DEFAULT NULL,
  `state` enum('ready','picked-up') COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `order_nr` int(11) DEFAULT NULL,
  `transporter` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skis`
--

CREATE TABLE `skis` (
  `serial_nr` int(11) NOT NULL,
  `ski_type` int(11) NOT NULL,
  `manufactured_date` date DEFAULT NULL,
  `order_assigned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_types`
--

CREATE TABLE `ski_types` (
  `type_id` int(11) NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `temperature` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `grip` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `weight_class` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `historical` tinyint(1) DEFAULT 0,
  `photo_url` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `msrp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `customer_id` int(11) NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `buying_price` int(11) DEFAULT NULL,
  `franchise_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_skiers`
--

CREATE TABLE `team_skiers` (
  `customer_id` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `club` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `skis_per_year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transporters`
--

CREATE TABLE `transporters` (
  `name` varchar(255) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

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
  ADD KEY `orders_customers_fk` (`customer_id`);

--
-- Indexes for table `order_aggregates`
--
ALTER TABLE `order_aggregates`
  ADD PRIMARY KEY (`aggregate_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`order_nr`,`state`),
  ADD KEY `order_historyemployee_fk` (`customer_rep`);

--
-- Indexes for table `production_plans`
--
ALTER TABLE `production_plans`
  ADD PRIMARY KEY (`ski_type`,`day`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`shipment_nr`);

--
-- Indexes for table `skis`
--
ALTER TABLE `skis`
  ADD PRIMARY KEY (`serial_nr`),
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
-- AUTO_INCREMENT for table `order_aggregates`
--
ALTER TABLE `order_aggregates`
  MODIFY `aggregate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `shipment_nr` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `orders_customers_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ski_types_fk` FOREIGN KEY (`ski_type`) REFERENCES `ski_types` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_orders_fk` FOREIGN KEY (`order_nr`) REFERENCES `orders` (`order_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_historyemployee_fk` FOREIGN KEY (`customer_rep`) REFERENCES `employees` (`employee_id`) ON UPDATE CASCADE;

--
-- Constraints for table `skis`
--
ALTER TABLE `skis`
  ADD CONSTRAINT `skis_orders_fk` FOREIGN KEY (`order_assigned`) REFERENCES `orders` (`order_nr`) ON UPDATE CASCADE,
  ADD CONSTRAINT `skis_skitypes_fk` FOREIGN KEY (`serial_nr`) REFERENCES `ski_types` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stores_franchises_fk` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`customer_id`) ON UPDATE CASCADE;

--
-- Constraints for table `team_skiers`
--
ALTER TABLE `team_skiers`
  ADD CONSTRAINT `team_skiers_customers_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

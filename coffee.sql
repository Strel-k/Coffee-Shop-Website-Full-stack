-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2024 at 02:48 PM
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
-- Database: `coffee`
--

-- --------------------------------------------------------

--
-- Table structure for table `customeraccounts`
--

CREATE TABLE `customeraccounts` (
  `AccountID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `IsAdmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customeraccounts`
--

INSERT INTO `customeraccounts` (`AccountID`, `CustomerID`, `Username`, `Password`, `IsAdmin`) VALUES
(7, 5, 'admin', '$2y$10$MVt/h.faFYxOIvtRalpzaus0uoaj5p1S1Dpe6EygojI3VBtziDnhi', 1),
(8, 6, 'fdavedamond@gmail.com', '$2y$10$0wpAnRD/asRc76tSmLWVxe0shvZXZ63.tc007oOoNAvzmk26ppFCa', 0),
(9, 7, 'Mark Andrew Baliguat', '$2y$10$9/qARhS69nGi/P8nkbKVU.5g5x9T3Czb4SJB01pInNTm14MsxUHEu', 0),
(11, 9, 'Irish John Jacinto', '$2y$10$NUyatbFZZniM50eXPOpXmeFpRsczaZBdwGEMY4eytzOji6NGAU5wq', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `IsAdmin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `Name`, `Email`, `Phone`, `IsAdmin`) VALUES
(5, 'admin', 'admin@gmail.com', '09982297800', 1),
(6, 'Dave', 'fdavedamond@gmail.com', '09982297800', NULL),
(7, 'Mark Andrew Baliguat', 'mark@gmail.com', '09982297800', NULL),
(9, 'Irish John Jacinto', 'irish@gmail.com', '09982297800', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`OrderItemID`, `OrderID`, `ProductID`, `Quantity`) VALUES
(90, 124, 18, 1),
(91, 125, 18, 12),
(92, 126, 25, 1),
(93, 127, 29, 1),
(94, 128, 33, 12),
(97, 131, 25, 1),
(99, 133, 25, 1),
(100, 134, 25, 1),
(101, 135, 25, 1),
(104, 138, 25, 1),
(107, 141, 25, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderDate`, `user_id`) VALUES
(124, 6, '2024-04-03 03:32:35', NULL),
(125, 7, '2024-04-03 04:11:14', NULL),
(126, 7, '2024-04-03 04:12:02', NULL),
(127, 7, '2024-04-03 04:18:09', NULL),
(128, 7, '2024-04-03 04:18:22', NULL),
(131, 7, '2024-04-03 04:21:37', NULL),
(133, 5, '2024-04-03 04:27:51', NULL),
(134, 5, '2024-04-03 04:28:14', NULL),
(135, 5, '2024-04-03 04:28:32', NULL),
(138, 5, '2024-04-03 04:34:53', NULL),
(141, 9, '2024-04-03 04:44:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `image_url` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `Name`, `Price`, `image_url`) VALUES
(18, 'Jade Coffee233', 50.00, 'uploads/checkout.png'),
(25, 'Popo', 222.00, 'uploads/Time-Image.png'),
(27, 'Rerere', 22.00, 'uploads/Phone_icon.png'),
(28, 'Trawe', 22.00, 'uploads/Coffee-Logo.png'),
(29, 'Rekta', 66.00, 'uploads/Delivery-Image.png'),
(30, 'Teraaw', 22.00, 'uploads/Phone_icon.png'),
(31, 'degawd', 22.00, 'uploads/Email.png'),
(32, 'www', 222.00, 'uploads/Schedule-image.png'),
(33, 'Terrwww', 2222.00, 'uploads/Schedule-image.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customeraccounts`
--
ALTER TABLE `customeraccounts`
  ADD PRIMARY KEY (`AccountID`),
  ADD UNIQUE KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `orderitems_ibfk_1` (`OrderID`),
  ADD KEY `orderitems_ibfk_2` (`ProductID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `FK_user_id` (`user_id`),
  ADD KEY `orders_ibfk_1` (`CustomerID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customeraccounts`
--
ALTER TABLE `customeraccounts`
  MODIFY `AccountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customeraccounts`
--
ALTER TABLE `customeraccounts`
  ADD CONSTRAINT `customeraccounts_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`);

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `customeraccounts` (`AccountID`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2020 at 08:58 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakery1`
--
DROP DATABASE IF EXISTS `bakery1`;
CREATE DATABASE IF NOT EXISTS `bakery1` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bakery1`;

-- --------------------------------------------------------

--
-- Table structure for table `employeedetails`
--

CREATE TABLE IF NOT EXISTS `employeedetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `yearsWorked` int(11) NOT NULL,
  `employeeId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employeeId` (`employeeId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeedetails`
--

INSERT INTO `employeedetails` (`id`, `email`, `yearsWorked`, `employeeId`) VALUES
(1, 'jvoorhees@gmail.com', 7, 1),
(2, 'fkruger@gmail.com', 4, 2),
(3, 'mikemeyers@gmail.com', 4, 3),
(4, 'jchan@gmail.com', 2, 4),
(5, 'ctucker@gmail.com', 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `role` enum('owner','employee','','') NOT NULL,
  `password` varchar(64) NOT NULL,
  `apikey` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `username`, `firstName`, `role`, `password`, `apikey`) VALUES
(1, 'jasonv', 'Jason Voorhees', 'owner', '$2y$10$cxiBvv2uRutamEGC2FbsOOCZSDGPshdJb2wAad9ZHGcVosrOn7cRi', 'abcdefg'),
(2, 'freddyk', 'Freddy Kruger', 'employee', '$2y$10$jx3zJV0rH2r1x..dS1sUm.j244tayFsudCU1gsn0gLxLT.blyO9kK', 'abcdefg'),
(3, 'michaelm', 'Michael Meyers', 'employee', '$2y$10$xqv1iLsSvBm9wvfZ.G23MOh.Kd7is10n3hA0DcOXqX/qgHo/LcN7O', 'abcdefg'),
(4, 'jackiec', 'Jackie Chan', 'employee', '$2y$10$wPcHzaO0GRYZ26rR2Do//.QvobIdgQ4XivFoJxlqHLkOrYqMh3ugC', 'abcdefg'),
(5, 'christ', 'Chris Tucker', 'employee', '$2y$10$PVcg0bQMYZudMWnnK8xVDuz3.JNleTDnWFIEj24asTaKL7UHxGdOa', 'abcdefg');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `origin` varchar(100) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `origin`, `price`) VALUES
(1, 'Sticky Rice Balls', 'Vietnam', '3.25'),
(2, 'Churro', 'Mexico', '3.50'),
(3, 'Macadamia Nut Cookies', 'USA', '1.50'),
(4, 'Mochi', 'Japan', '4.25'),
(5, 'Moon Cakes', 'China', '9.50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerName` varchar(100) NOT NULL,
  `itemId` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customerName`, `itemId`, `amount`) VALUES
(1, 'Steve Jobs', 1, 4),
(2, 'Ricky Tan', 2, 10),
(3, 'Connor Shomaker', 3, 2),
(4, 'Andre Genier', 4, 1),
(5, 'Asher Roth', 5, 3),
(6, 'Jack Black', 1, 5),
(7, 'Gordan Ramsay', 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `storelocation`
--

CREATE TABLE IF NOT EXISTS `storelocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `street` varchar(100) NOT NULL,
  `state` varchar(2) NOT NULL,
  `yearsOpen` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storelocation`
--

INSERT INTO `storelocation` (`id`, `street`, `state`, `yearsOpen`) VALUES
(1, '5231 Southport Road', 'IN', 7),
(2, '444 Sun Devil Road', 'AZ', 4),
(3, '2521 Liberty Street', 'OH', 2),
(4, '2101 Westminister Street', 'CA', 2),
(5, '9212 Michelin Street', 'IL', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `value` int(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `user`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 612, '2020-12-08 00:55:52', '2020-12-09 00:29:53'),
(2, 5, 0, '2020-12-08 23:34:24', '2020-12-08 23:34:24');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employeedetails`
--
ALTER TABLE `employeedetails`
  ADD CONSTRAINT `employeedetails_ibfk_1` FOREIGN KEY (`employeeId`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`itemId`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

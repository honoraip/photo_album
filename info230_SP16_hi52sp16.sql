-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 24, 2017 at 04:52 PM
-- Server version: 5.6.33
-- PHP Version: 7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `info230_SP16_hi52sp16`
--

-- --------------------------------------------------------

--
-- Table structure for table `Albums`
--

CREATE TABLE `Albums` (
  `aID` int(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `date_created` date NOT NULL,
  `date_modified` date NOT NULL,
  `aCover` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Albums`
--

INSERT INTO `Albums` (`aID`, `title`, `date_created`, `date_modified`, `aCover`) VALUES
(2, 'Travel', '2016-03-23', '2016-04-12', '../images/seaside.jpg'),
(19, 'Neko Atsume', '2016-04-12', '2016-04-12', '../images/cat6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Images`
--

CREATE TABLE `Images` (
  `pID` int(50) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `file_path` varchar(50) NOT NULL,
  `credit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Images`
--

INSERT INTO `Images` (`pID`, `caption`, `file_path`, `credit`) VALUES
(2, 'Mochi Desert', '../images/mochi.jpg', 'Site creator'),
(3, 'Chicken Stew', '../images/stew.jpg', 'Site creator'),
(5, 'Atami Seaside', '../images/seaside.jpg', 'Site Creator'),
(7, 'Default', '../images/default.jpg', 'Edited from http://tinyurl.com/ja6wvsv'),
(20, 'Macau Water Show', '../images/watershow.jpg', 'Site Creator'),
(21, 'Disney- Small World', '../images/disney.jpg', 'Site Creator'),
(22, 'Yellowstone Park', '../images/yellowstone.jpg', 'http://tinyurl.com/hrc7m62'),
(23, 'Mount Fuji', '../images/fuji.jpg', 'http://tinyurl.com/hqj4u7m'),
(24, 'Lavender Ice Cream', '../images/lavender.jpg', 'http://tinyurl.com/h9gl9vm'),
(25, 'Mango Dessert', '../images/mango.jpg', 'http://tinyurl.com/hdf9ced'),
(26, 'Apple Pie', '../images/applepie.jpg', 'http://tinyurl.com/z79boxo'),
(27, 'Salad', '../images/salad.jpg', 'http://tinyurl.com/gurbu47'),
(28, 'Barbecue Steak', '../images/steak.jpg', 'http://tinyurl.com/gplnhbc'),
(30, 'Cat 2', '../images/cat2.jpg', 'http://tinyurl.com/hll9lr9'),
(31, 'Cat 3', '../images/cat3.jpg', 'http://tinyurl.com/js5zmfz'),
(32, 'Cat 4', '../images/cat4.jpg', 'http://tinyurl.com/zdgjnfl'),
(33, 'Cat 5', '../images/cat5.jpg', 'http://tinyurl.com/jkagfnm'),
(34, 'Cat 6', '../images/cat6.jpg', 'http://tinyurl.com/hr7exhb'),
(35, 'Cat 7', '../images/cat7.jpg', 'http://tinyurl.com/jhhwhmy'),
(36, 'Cat 8', '../images/cat8.jpg', 'http://tinyurl.com/jdnjex5'),
(37, 'Test', '../images/cat-1005489_960_720.jpg', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `Images_Albums`
--

CREATE TABLE `Images_Albums` (
  `sID` int(50) NOT NULL,
  `pID` int(50) NOT NULL,
  `aID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Images_Albums`
--

INSERT INTO `Images_Albums` (`sID`, `pID`, `aID`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(5, 5, 2),
(27, 4, 2),
(28, 2, 2),
(29, 20, 2),
(30, 1, 2),
(31, 3, 2),
(32, 21, 2),
(33, 22, 2),
(34, 23, 2),
(35, 24, 1),
(36, 25, 1),
(37, 26, 1),
(38, 27, 1),
(39, 28, 1),
(40, 29, 19),
(41, 30, 19),
(42, 31, 19),
(43, 32, 19),
(44, 33, 19),
(45, 34, 19),
(46, 35, 19),
(47, 36, 19),
(48, 29, 1),
(49, 37, 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `hashpassword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `hashpassword`, `username`, `name`) VALUES
(1, '$2y$10$WqzoTJM86ZYtWH5/67qkJe3Q5kZIpzBMy.R3URWXIptrKJdPMSgre', 'hi52', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Albums`
--
ALTER TABLE `Albums`
  ADD PRIMARY KEY (`aID`);

--
-- Indexes for table `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`pID`);

--
-- Indexes for table `Images_Albums`
--
ALTER TABLE `Images_Albums`
  ADD PRIMARY KEY (`sID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `idx_unique_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Albums`
--
ALTER TABLE `Albums`
  MODIFY `aID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `Images`
--
ALTER TABLE `Images`
  MODIFY `pID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `Images_Albums`
--
ALTER TABLE `Images_Albums`
  MODIFY `sID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
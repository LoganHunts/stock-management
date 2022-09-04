-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2022 at 09:52 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kemuri`
--

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(100) NOT NULL,
  `stock_name` varchar(100) NOT NULL,
  `history` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `stock_name`, `history`) VALUES
(8, 'GOOGL', 'a:7:{s:10:\"12-02-2020\";s:4:\"1518\";s:10:\"16-02-2020\";s:4:\"1530\";s:10:\"21-02-2020\";s:4:\"1483\";s:10:\"14-02-2020\";s:4:\"1520\";s:10:\"22-02-2020\";s:4:\"1485\";s:10:\"15-02-2020\";s:4:\"1523\";s:10:\"11-02-2020\";s:4:\"1510\";}'),
(9, 'AAPL', 'a:7:{s:10:\"15-02-2020\";s:3:\"319\";s:10:\"23-02-2020\";s:3:\"320\";s:10:\"19-02-2020\";s:3:\"323\";s:10:\"18-02-2020\";s:3:\"319\";s:10:\"13-02-2020\";s:3:\"324\";s:10:\"21-02-2020\";s:3:\"313\";s:10:\"11-02-2020\";s:3:\"320\";}'),
(10, 'MSFT', 'a:6:{s:10:\"22-02-2020\";s:3:\"180\";s:10:\"12-02-2020\";s:3:\"184\";s:10:\"11-02-2020\";s:3:\"185\";s:10:\"15-02-2020\";s:3:\"189\";s:10:\"18-02-2020\";s:3:\"187\";s:10:\"21-02-2020\";s:3:\"178\";}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 24, 2020 at 12:22 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdbdorg_rp_admin_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `authentication`
--

CREATE TABLE `authentication` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(150) NOT NULL,
  `loginattempt` int(11) NOT NULL,
  `welcome_message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authentication`
--

INSERT INTO `authentication` (`id`, `username`, `password`, `loginattempt`, `welcome_message`) VALUES
(2, 'admin', 'ada69928564b637bf159bf144740fa8f886e785cacb4a37727f6919d2e1db946af14b1096a04661e8976fbeb361517225666a87dcddb058abd52fa5ba1e22053', 0, 'Demo School');

-- --------------------------------------------------------

--
-- Table structure for table `ipcheck`
--

CREATE TABLE `ipcheck` (
  `id` int(11) NOT NULL,
  `loggedip` varchar(20) NOT NULL,
  `failedattempts` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ipcheck`
--

INSERT INTO `ipcheck` (`id`, `loggedip`, `failedattempts`) VALUES
(45, '192.168.3.121', 2),
(44, '192.168.3.141', 0),
(43, '192.168.3.126', 0),
(42, '::1', 0),
(40, '192.168.3.128', 0),
(39, '192.168.3.12', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authentication`
--
ALTER TABLE `authentication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ipcheck`
--
ALTER TABLE `ipcheck`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authentication`
--
ALTER TABLE `authentication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ipcheck`
--
ALTER TABLE `ipcheck`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

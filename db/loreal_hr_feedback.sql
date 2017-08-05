-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2017 at 12:38 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `loreal_hr_feedback`
--

-- --------------------------------------------------------

--
-- Table structure for table `emp_feedback`
--

CREATE TABLE IF NOT EXISTS `emp_feedback` (
  `review_count` int(11) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `reviewer` varchar(50) NOT NULL,
  `competency1` float NOT NULL,
  `competency2` float NOT NULL,
  `competency3` float NOT NULL,
  `competency4` float NOT NULL,
  `competency5` float NOT NULL,
  `competency_agg` float NOT NULL,
  PRIMARY KEY (`review_count`,`designation`,`reviewer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emp_info`
--

CREATE TABLE IF NOT EXISTS `emp_info` (
  `designation` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `manager` varchar(50) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`designation`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `review_cycle`
--

CREATE TABLE IF NOT EXISTS `review_cycle` (
  `date` date NOT NULL,
  `review_count` int(11) NOT NULL,
  PRIMARY KEY (`review_count`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

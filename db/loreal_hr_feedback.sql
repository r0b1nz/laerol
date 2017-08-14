-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2017 at 06:28 PM
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

--
-- Dumping data for table `emp_feedback`
--

INSERT INTO `emp_feedback` (`review_count`, `designation`, `reviewer`, `competency1`, `competency2`, `competency3`, `competency4`, `competency5`, `competency_agg`) VALUES
(2, 'BIO', 'ceo', 3.2, 1.8, 3, 5, 4.2, 3.44),
(6, 'BIO', 'ceo', 4.6, 1, 2, 3, 4, 2.92),
(6, 'CEO', 'ceo', 3.2, 3, 3.4, 3.2, 3.4, 3.24),
(8, 'BIO', 'ceo', 5, 3, 2.2, 3, 1, 2.84),
(8, 'BIO', 'TM', 4.4, 1, 2, 3, 4.4, 2.96),
(8, 'TM', 'TM', 1.8, 5, 3, 3.8, 3, 3.32),
(11, 'BIO', 'ceo', 4.4, 3.4, 4.4, 3, 3, 3.64),
(11, 'CEO', 'ceo', 5, 4, 3, 2, 1, 3);

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

--
-- Dumping data for table `emp_info`
--

INSERT INTO `emp_info` (`designation`, `password`, `manager`, `level`) VALUES
('BIO', 'BIO', 'CEO', 1),
('BIO1', 'dd875c7e', 'BIO', 2),
('BIO2', 'dd875dc9', 'BIO', 2),
('CEO', 'CEO', '', 0),
('HR', 'HR', '', -2),
('PM', 'dd875ea1', 'BIO', 2),
('RM', 'dd875ecb', 'TM', 3),
('TIO', 'dd875ef2', 'CEO', 1),
('TM', 'dd875f15', 'BIO', 2);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `review_count` int(11) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `reviewer` varchar(50) NOT NULL,
  `competency` int(11) NOT NULL COMMENT 'Like 1,2,3,4 ot 5',
  `section1` varchar(50) NOT NULL COMMENT 'value;min;max',
  `section2` varchar(50) NOT NULL COMMENT 'value;min;max',
  `section3` varchar(50) NOT NULL COMMENT 'value;min;max',
  `section4` varchar(50) NOT NULL COMMENT 'value;min;max',
  `competency_agg` varchar(50) NOT NULL COMMENT 'value;min;max',
  `min` float NOT NULL COMMENT 'min section val',
  `max` float NOT NULL COMMENT 'max section val',
  PRIMARY KEY (`review_count`,`designation`,`reviewer`,`competency`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`review_count`, `designation`, `reviewer`, `competency`, `section1`, `section2`, `section3`, `section4`, `competency_agg`, `min`, `max`) VALUES
(11, 'BIO', 'bio', 1, '3', '4.5', '4', '3', '3.63', 1, 5),
(11, 'BIO', 'bio', 2, '3.25', '4.67', '1', '3.5', '3.11', 2, 5),
(11, 'BIO', 'bio', 3, '4.33', '3.75', '2.5', '3', '3.4', 2, 4),
(11, 'BIO', 'bio', 4, '3.33', '3', '5', '3.5', '3.71', 3, 4),
(11, 'BIO', 'bio', 5, '3', '5', '4.67', '5', '4.42', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `review_cycle`
--

CREATE TABLE IF NOT EXISTS `review_cycle` (
  `date` date NOT NULL,
  `review_count` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`review_count`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `review_cycle`
--

INSERT INTO `review_cycle` (`date`, `review_count`) VALUES
('2017-08-05', 1),
('2017-08-05', 2),
('2017-08-07', 3),
('2017-08-07', 4),
('2017-08-08', 5),
('2017-08-08', 6),
('2017-08-09', 7),
('2017-08-09', 8),
('2017-08-09', 9),
('2017-08-09', 10),
('2017-08-09', 11);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

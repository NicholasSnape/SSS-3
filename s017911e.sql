-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 08, 2017 at 01:22 PM
-- Server version: 5.5.8
-- PHP Version: 5.6.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s017911e`
--

-- --------------------------------------------------------

--
-- Table structure for table `sss3_orders`
--

CREATE TABLE IF NOT EXISTS `sss3_orders` (
  `o_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `o_pizzas` varchar(100) NOT NULL,
  `o_totalcost` decimal(4,2) NOT NULL,
  `o_method` tinyint(1) NOT NULL,
  `o_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sss3_pizzas`
--

CREATE TABLE IF NOT EXISTS `sss3_pizzas` (
  `p_id` int(11) NOT NULL,
  `p_name` varchar(20) NOT NULL,
  `p_toppings` varchar(100) NOT NULL,
  `p_small` decimal(10,0) DEFAULT NULL,
  `p_medium` decimal(10,0) DEFAULT NULL,
  `p_large` decimal(10,0) DEFAULT NULL,
  `p_customizable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sss3_pizzas`
--

INSERT INTO `sss3_pizzas` (`p_id`, `p_name`, `p_toppings`, `p_small`, `p_medium`, `p_large`, `p_customizable`) VALUES
(1, 'Original', '[1,2]', '8', '9', '11', 0),
(2, 'Gimme the Meat', '[1,2,3,4,5,6,7,8]', '11', '15', '17', 0),
(3, 'Veggie Delight', '[1,2,9,10,11,12]', '10', '13', '15', 0),
(4, 'Make Mine Hot', '[1,2,5,9,10,13]', '11', '13', '15', 0),
(5, 'Create Your Own', '[]', '8', '9', '11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sss3_toppings`
--

CREATE TABLE IF NOT EXISTS `sss3_toppings` (
  `t_id` int(11) NOT NULL,
  `t_name` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sss3_toppings`
--

INSERT INTO `sss3_toppings` (`t_id`, `t_name`) VALUES
(1, 'Cheese'),
(2, 'Tomato Sauce'),
(3, 'Pepperoni'),
(4, 'Ham'),
(5, 'Chicken'),
(6, 'Minced Beef'),
(7, 'Sausage'),
(8, 'Bacon'),
(9, 'Onions'),
(10, 'Green Peppers'),
(11, 'Mushrooms'),
(12, 'Sweetcorn'),
(13, 'Jalapeno Peppers');

-- --------------------------------------------------------

--
-- Table structure for table `sss3_users`
--

CREATE TABLE IF NOT EXISTS `sss3_users` (
  `u_id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sss3_orders`
--
ALTER TABLE `sss3_orders`
  ADD PRIMARY KEY (`o_id`);

--
-- Indexes for table `sss3_pizzas`
--
ALTER TABLE `sss3_pizzas`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `sss3_toppings`
--
ALTER TABLE `sss3_toppings`
  ADD PRIMARY KEY (`t_id`);

--
-- Indexes for table `sss3_users`
--
ALTER TABLE `sss3_users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sss3_orders`
--
ALTER TABLE `sss3_orders`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sss3_pizzas`
--
ALTER TABLE `sss3_pizzas`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sss3_toppings`
--
ALTER TABLE `sss3_toppings`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `sss3_users`
--
ALTER TABLE `sss3_users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

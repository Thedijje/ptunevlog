-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2019 at 07:48 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `codeigniter_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `admin_mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL,
  `password_token` varchar(255) NOT NULL,
  `front_user` int(10) NOT NULL COMMENT 'this will have a front end user id which would be replica of admin, used for chat purpose.',
  `last_login` varchar(15) NOT NULL,
  `admin_last_activity` varchar(15) NOT NULL,
  `added_on` varchar(15) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `admin_mobile`, `password`, `role`, `password_token`, `front_user`, `last_login`, `admin_last_activity`, `added_on`, `status`) VALUES
(1, 'Admin', 'admin@admin.com', '8882222622', '$2y$10$AWlDhIQH3mQaSH9wbpGcTeRPjXvzS3yMkR6hNu7sxSMkz.EM2sZEC', 1, '', 1, '1553843677', '1553843781', '1538029629', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

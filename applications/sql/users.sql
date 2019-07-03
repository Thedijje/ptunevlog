-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2019 at 12:50 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `dev_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `user_first_name` varchar(100) NOT NULL,
  `user_last_name` varchar(100) NOT NULL,
  `user_display_pic` varchar(255) NOT NULL DEFAULT 'static/images/placeholder/user_default.png',
  `user_verified_tag` enum('0','1') NOT NULL DEFAULT '0',
  `user_email` varchar(100) NOT NULL,
  `username` varchar(15) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_document_remark` text NOT NULL,
  `user_created_at` varchar(15) NOT NULL,
  `user_updated_at` varchar(15) NOT NULL,
  `user_approved_at` varchar(15) NOT NULL,
  `user_approved_by` int(10) NOT NULL,
  `user_mobile` varchar(13) NOT NULL,
  `user_company` varchar(100) NOT NULL,
  `company_id` int(10) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_city` int(10) NOT NULL,
  `user_country` int(10) NOT NULL,
  `user_zip` varchar(15) NOT NULL,
  `user_is_verified` enum('0','1') NOT NULL,
  `user_is_premium` enum('0','1') NOT NULL DEFAULT '0',
  `user_membership` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1 verified, 3 public user',
  `user_created_by` int(10) NOT NULL,
  `user_last_active` varchar(15) NOT NULL,
  `last_password_update` varchar(15) NOT NULL,
  `pass_reset_token` varchar(255) DEFAULT NULL,
  `user_device_type` enum('android','ios','web') NOT NULL DEFAULT 'web',
  `user_notifcation_token` varchar(255) NOT NULL,
  `user_timezone` varchar(50) NOT NULL,
  `user_is_inactive` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'admin will mark user as inactive in that case user has to correct information on profile.',
  `user_status` tinyint(2) NOT NULL,
  `last_post_remind_time` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD UNIQUE KEY `user_mobile` (`user_mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

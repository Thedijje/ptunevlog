-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2019 at 11:07 AM
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
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(10) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'site',
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='configuration settings of site';

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `type`, `name`, `value`) VALUES
(1, 'site', 'sitename', 'Site-name'),
(2, 'site', 'title', 'Welcome to Site-name'),
(3, 'site', 'logo', 'static/images/uploads/site-name.png'),
(4, 'site', 'default_user_status', '1'),
(5, 'site', 'phone', '+1 123 456 7890'),
(7, 'site', 'email', 'info@site-name.com'),
(8, 'site', 'meta_title', ''),
(9, 'site', 'meta_description', ''),
(10, 'site', 'date_format', 'D d-m-Y'),
(11, 'site', 'time_format', 'h:i A'),
(12, 'site', 'date_time_format', 'M/d/Y h:i A'),
(13, 'site', 'sending_email', 'No-reply@Site-name.com'),
(14, 'site', 'sending_email_name', 'Site-name'),
(15, 'site', 'user_dp_image_path', 'static/uploads/user_dp/'),
(16, 'site', 'max_table_row_display', '20'),
(17, 'site', 'device_auth_key', 'user_auth_key'),
(18, 'site', 'soft_delete', '1'),
(19, 'site', 'user_default_avatar', 'static/images/placeholder/user_default.png'),
(20, 'site', 'auth_key', 'User-Auth-Token'),
(21, 'site', 'post_placeholder', 'static/images/placeholder/post_placeholder.jpg'),
(22, 'site', 'placeholder_sm', 'static/images/placeholder/bg_image_small.jpg'),
(23, 'site', 'linkedin', 'https://www.linkedin.com/'),
(24, 'site', 'facebook', 'https://www.facebook.com/'),
(25, 'site', 'twitter', 'https://twitter.com/'),
(26, 'site', 'instagram', 'https://www.instagram.com/'),
(27, 'site', 'google_plus', 'http://google.com/mobi_hub'),
(28, 'site', 'address', 'Address, City, State, Country'),
(29, 'site', 'logo-app', 'static/front/images/logo/logo-app.png'),
(30, 'site', 'logo-wide', 'static/front/images/logo/logo-site.png'),
(31, 'site', 'max_image_upload', '3'),
(32, 'site', 'invoice_reference_key', 'SN-'),
(33, 'site', 'post_expire_days', '1'),
(34, 'site', 'post_reminder_days', '1'),
(35, 'site', 'url_apple_store', ''),
(36, 'site', 'url_google_play', '');
(37, 'site', 'color_scheme', 'default');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

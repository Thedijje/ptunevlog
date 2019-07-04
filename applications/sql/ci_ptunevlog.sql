-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 04, 2019 at 06:04 PM
-- Server version: 5.7.25
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci_ptunevlog`
--
CREATE DATABASE IF NOT EXISTS `ci_ptunevlog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ci_ptunevlog`;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 1, '123123', 1, 0, 0, NULL, 1562169881);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `name`) VALUES
(1, 'marvel'),
(2, 'spider man'),
(3, 'science fiction');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(5) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'user@example.com', '4297f44b13955235245b2497399d7a93');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` int(5) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `upload_date` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `description`, `url`, `thumbnail`, `lang`, `upload_date`) VALUES
(1, 'SPIDER-MAN: FAR FROM HOME - Official Trailer', 'https://www.youtube.com/watch?v=Nt9L1jCKGnE', 'https://i.ytimg.com/vi/Nt9L1jCKGnE/hqdefault.jpg', 'EN', '2019-07-03 00:00:00.000000'),
(2, 'Captain Marvel Final Battle Scene 1080p', 'https://www.youtube.com/watch?v=k2d812JrSQ0', 'https://i.ytimg.com/vi/k2d812JrSQ0/hqdefault.jpg', 'HI', '2019-07-03 00:00:00.000000'),
(3, 'A Happy Couple | Short Film', 'https://www.youtube.com/watch?v=BGpjsSLiXTc', 'https://i.ytimg.com/vi/BGpjsSLiXTc/hqdefault.jpg?sqp=-oaymwEYCNIBEHZIVfKriqkDCwgBFQAAiEIYAXAB&rs=AOn4CLDzvyPnHw7DlV69r69oJuEKqK-5lQ', 'HI', '2019-07-01 00:00:00.000000'),
(4, 'Rich, Richer, Ambani | Stand-up Comedy by Rohit Swain', 'https://www.youtube.com/watch?v=ImuG-_NI0uE', 'https://i.ytimg.com/vi/ImuG-_NI0uE/hqdefault.jpg?sqp=-oaymwEYCNIBEHZIVfKriqkDCwgBFQAAiEIYAXAB&rs=AOn4CLD59RgTQBTxdBUnUG-9unLG-fxA-g', 'HI', '2019-04-16 00:00:00.000000'),
(5, 'Why Jumia Is Beating Amazon And Alibaba In Africa', 'https://www.youtube.com/watch?v=BLxyJagWhYw', 'https://i.ytimg.com/vi/BLxyJagWhYw/hqdefault.jpg?sqp=-oaymwEZCPYBEIoBSFXyq4qpAwsIARUAAIhCGAFwAQ==&rs=AOn4CLD3gb-orLKrZzw9WGF0cob05ST1nQ', 'FR', '2019-07-01 00:00:00.000000'),
(6, '15 Books Bill Gates Thinks Everyone Should Read', 'https://www.youtube.com/watch?v=a47dqygseGo', 'https://i.ytimg.com/vi/a47dqygseGo/hqdefault.jpg?sqp=-oaymwEYCNIBEHZIVfKriqkDCwgBFQAAiEIYAXAB&rs=AOn4CLCVSQWiiQ1CB6sSD78T2wFfBwQkZA', 'EN', '2019-04-08 00:00:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `video_topics`
--

DROP TABLE IF EXISTS `video_topics`;
CREATE TABLE `video_topics` (
  `video_id` int(5) NOT NULL,
  `topic_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `video_topics`
--

INSERT INTO `video_topics` (`video_id`, `topic_id`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 1),
(2, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

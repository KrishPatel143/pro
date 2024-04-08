-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 11:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event system`
--
CREATE DATABASE IF NOT EXISTS `event system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `event system`;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) NOT NULL,
  `device_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `device_id`, `user_id`, `name`, `description`) VALUES
(1, 1, 41178128, 'sdcs', 'scscsc'),
(2, 2, 41178128, 'sdcs', 'scscsc'),
(3, 3, 41178128, 'krish', 'paguisdvbkjdsl'),
(4, 0, 41178128, 'krish', 'paguisdvbkjdsl'),
(7, 0, 41178128, 'krish', 'vjfovhpsvj'),
(8, 0, 41178128, 'qwe', 'qwsa'),
(9, 0, 41178128, 'qwe', 'qwsa'),
(10, 0, 41178128, 'qwe', 'qwsa'),
(11, 0, 41178128, 'qwe', 'qwsa');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `location` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `url` varchar(2083) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'unpublished'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_id`, `title`, `date`, `time`, `location`, `description`, `url`, `status`) VALUES
(1, 1, 'Sample Event 1', '2024-04-07', '2024-04-08 17:32:37', 'Conference Hall A', 'This is a sample event description for Event 1.', 'images/g-2.jpg', 'published'),
(2, 2, 'Sample Event 2', '2024-04-08', '2024-04-08 16:56:37', 'Seminar Room B', 'This is a sample event description for Event 2.', 'images/g-1.jpg', 'published'),
(13, 6, 'kroshj', '0000-00-00', '2024-04-08 21:17:44', 'nvsjldfkfah', 'fngdslnglkwj;', 'images/g-2.jpg', 'published'),
(19, 19, 'krish', '2020-10-02', '2024-04-08 21:17:50', 'wdqqw', 'dwdwqdwqdqwdqw', 'images/g-1.jpg', 'published');

-- --------------------------------------------------------

--
-- Table structure for table `event_device`
--

CREATE TABLE `event_device` (
  `event_id` bigint(20) DEFAULT NULL,
  `device_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_device`
--

INSERT INTO `event_device` (`event_id`, `device_id`) VALUES
(19, 1),
(19, 1),
(1, 7),
(2, 7),
(19, 7),
(2, 8),
(13, 8),
(19, 8),
(2, 9),
(13, 9),
(19, 9),
(2, 10),
(13, 10),
(19, 10),
(2, 11),
(13, 11),
(19, 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(100) NOT NULL DEFAULT 'Participant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `user_name`, `user_email`, `password`, `date`, `role`) VALUES
(9, 1480555204265977164, 'k', 'k@g.com', '$2y$10$5aYkWPaI1mEYbcajuuxKM.mKWt2zoAK5/oAFjNc2MfjlhCd9t4Vzy', '2024-04-06 12:05:02', 'organizer'),
(10, 41178128, 'q', 'q@g.c', '$2y$10$HAiiBGwYJc.vjVHM.iTpP.slQhrJPp64fNQx2CpKyLWRqJtrpN0Yi', '2024-04-06 12:43:16', 'organizer'),
(11, 714374701024463, 'q', 'q@g.ac', '$2y$10$e2VXsDh3AX1TdLfIjlz4uugcmuIp3wLga6JFa/h2QGBr/4HeVE.Aq', '2024-04-08 20:14:38', 'participant');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_device`
--
ALTER TABLE `event_device`
  ADD KEY `event_id` (`event_id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`user_name`,`date`,`role`),
  ADD KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_device`
--
ALTER TABLE `event_device`
  ADD CONSTRAINT `event_device_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `event_device_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

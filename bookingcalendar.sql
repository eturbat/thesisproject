-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2023 at 04:43 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookingcalendar`
--

-- --------------------------------------------------------

--
-- Table structure for table `available_rooms`
--

CREATE TABLE `available_rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `name` text NOT NULL,
  `email` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `timeslot` varchar(25) NOT NULL,
  `room` varchar(255) NOT NULL,
  `reader_one` varchar(255) NOT NULL,
  `reader_two` varchar(255) NOT NULL,
  `thesis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`name`, `email`, `date`, `timeslot`, `room`, `reader_one`, `reader_two`, `thesis`) VALUES
('Turbat Enkhtur', 'tenkhtur23@wooster.edu', '2024-04-12', '13:00-13:50', 'room200', 'Dr. Visa', 'Dr. Montelione', 'Oral Defense Scheduling Full-Stack Web App');

-- --------------------------------------------------------

--
-- Table structure for table `defense_schedule`
--

CREATE TABLE `defense_schedule` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `defense_schedule`
--

INSERT INTO `defense_schedule` (`id`, `start_date`, `end_date`) VALUES
(13, '2024-04-03', '2024-04-22');

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `timeslot` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `professors`
--

INSERT INTO `professors` (`id`, `name`, `date`, `timeslot`) VALUES
(299, 'Dr. Visa', '2024-04-08', '09:00-09:50'),
(300, 'Dr. Visa', '2024-04-08', '10:00-10:50'),
(301, 'Dr. Visa', '2024-04-08', '11:00-11:50'),
(302, 'Dr. Visa', '2024-04-08', '13:00-13:50'),
(303, 'Dr. Visa', '2024-04-08', '15:00-15:50'),
(304, 'Dr. Visa', '2024-04-08', '16:00-16:50'),
(305, 'Dr. Visa', '2024-04-12', '13:00-13:50'),
(306, 'Dr. Visa', '2024-04-12', '14:00-14:50'),
(307, 'Dr. Visa', '2024-04-12', '15:00-15:50'),
(308, 'Dr. Visa', '2024-04-12', '16:00-16:50'),
(309, 'Dr. Visa', '2024-04-18', '09:00-09:50'),
(310, 'Dr. Visa', '2024-04-18', '10:00-10:50'),
(311, 'Dr. Visa', '2024-04-18', '11:00-11:50'),
(312, 'Dr. Visa', '2024-04-18', '12:00-12:50'),
(313, 'Dr. Visa', '2024-04-18', '13:00-13:50'),
(314, 'Dr. Visa', '2024-04-18', '15:00-15:50'),
(315, 'Dr. Visa', '2024-04-18', '16:00-16:50'),
(316, 'Dr. Montelione', '2024-04-08', '09:00-09:50'),
(317, 'Dr. Montelione', '2024-04-08', '10:00-10:50'),
(318, 'Dr. Montelione', '2024-04-08', '15:00-15:50'),
(319, 'Dr. Montelione', '2024-04-08', '16:00-16:50'),
(320, 'Dr. Montelione', '2024-04-12', '13:00-13:50'),
(321, 'Dr. Montelione', '2024-04-12', '15:00-15:50'),
(322, 'Dr. Montelione', '2024-04-12', '16:00-16:50'),
(323, 'Dr. Montelione', '2024-04-18', '09:00-09:50'),
(324, 'Dr. Montelione', '2024-04-18', '12:00-12:50'),
(325, 'Dr. Montelione', '2024-04-18', '13:00-13:50'),
(326, 'Dr. Montelione', '2024-04-18', '14:00-14:50'),
(327, 'Dr. Montelione', '2024-04-18', '16:00-16:50');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `timeslot` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `available_rooms`
--
ALTER TABLE `available_rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `defense_schedule`
--
ALTER TABLE `defense_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `available_rooms`
--
ALTER TABLE `available_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `defense_schedule`
--
ALTER TABLE `defense_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=328;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

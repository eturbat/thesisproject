-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 02, 2023 at 03:49 AM
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
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `name` text NOT NULL,
  `email` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `timeslot` varchar(25) NOT NULL,
  `room` varchar(255) NOT NULL,
  `reader_one` varchar(255) NOT NULL,
  `reader_two` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(138, 'Dr. Visa', '2024-04-01', '09:00-09:50'),
(139, 'Dr. Visa', '2024-04-01', '10:00-10:50'),
(140, 'Dr. Visa', '2024-04-01', '11:00-11:50'),
(141, 'Dr. Visa', '2024-04-01', '12:00-12:50'),
(142, 'Dr. Visa', '2024-04-01', '13:00-13:50'),
(143, 'Dr. Visa', '2024-04-02', '11:00-11:50'),
(144, 'Dr. Visa', '2024-04-02', '14:00-14:50'),
(145, 'Dr. Visa', '2024-04-02', '15:00-15:50'),
(146, 'Dr. Visa', '2024-04-02', '16:00-16:50'),
(147, 'Dr. Visa', '2024-04-03', '09:00-09:50'),
(148, 'Dr. Visa', '2024-04-03', '11:00-11:50'),
(149, 'Dr. Visa', '2024-04-03', '14:00-14:50'),
(150, 'Dr. Visa', '2024-04-03', '15:00-15:50'),
(151, 'Dr. Visa', '2024-04-03', '16:00-16:50'),
(152, 'Dr. Visa', '2024-04-04', '09:00-09:50'),
(153, 'Dr. Visa', '2024-04-04', '10:00-10:50'),
(154, 'Dr. Visa', '2024-04-04', '12:00-12:50'),
(155, 'Dr. Visa', '2024-04-04', '14:00-14:50'),
(156, 'Dr. Visa', '2024-04-04', '16:00-16:50'),
(157, 'Dr. Visa', '2024-04-05', '10:00-10:50'),
(158, 'Dr. Visa', '2024-04-05', '12:00-12:50'),
(159, 'Dr. Visa', '2024-04-05', '13:00-13:50'),
(160, 'Dr. Visa', '2024-04-05', '14:00-14:50'),
(161, 'Dr. Visa', '2024-04-05', '16:00-16:50'),
(162, 'Dr. D. Guarnera', '2024-04-01', '11:00-11:50'),
(163, 'Dr. D. Guarnera', '2024-04-01', '13:00-13:50'),
(164, 'Dr. D. Guarnera', '2024-04-01', '14:00-14:50'),
(165, 'Dr. D. Guarnera', '2024-04-01', '15:00-15:50'),
(166, 'Dr. D. Guarnera', '2024-04-02', '10:00-10:50'),
(167, 'Dr. D. Guarnera', '2024-04-02', '11:00-11:50'),
(168, 'Dr. D. Guarnera', '2024-04-02', '16:00-16:50'),
(169, 'Dr. D. Guarnera', '2024-04-03', '11:00-11:50'),
(170, 'Dr. D. Guarnera', '2024-04-03', '12:00-12:50'),
(171, 'Dr. D. Guarnera', '2024-04-03', '13:00-13:50'),
(172, 'Dr. D. Guarnera', '2024-04-03', '15:00-15:50'),
(173, 'Dr. D. Guarnera', '2024-04-04', '10:00-10:50'),
(174, 'Dr. D. Guarnera', '2024-04-04', '12:00-12:50'),
(175, 'Dr. D. Guarnera', '2024-04-04', '14:00-14:50'),
(176, 'Dr. D. Guarnera', '2024-04-04', '16:00-16:50'),
(177, 'Dr. D. Guarnera', '2024-04-05', '11:00-11:50'),
(178, 'Dr. D. Guarnera', '2024-04-05', '13:00-13:50'),
(179, 'Dr. D. Guarnera', '2024-04-05', '15:00-15:50'),
(180, 'Dr. Musgrave', '2024-04-01', '13:00-13:50'),
(181, 'Dr. Musgrave', '2024-04-01', '14:00-14:50'),
(182, 'Dr. Musgrave', '2024-04-02', '13:00-13:50'),
(183, 'Dr. Musgrave', '2024-04-02', '14:00-14:50'),
(184, 'Dr. Musgrave', '2024-04-03', '13:00-13:50'),
(185, 'Dr. Musgrave', '2024-04-03', '14:00-14:50'),
(186, 'Dr. Musgrave', '2024-04-04', '11:00-11:50'),
(187, 'Dr. Musgrave', '2024-04-05', '15:00-15:50');

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
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `date`, `timeslot`) VALUES
(8, 'room200', '2024-04-01', '09:00-09:50'),
(9, 'room200', '2024-04-01', '10:00-10:50'),
(10, 'room200', '2024-04-01', '11:00-11:50'),
(11, 'room200', '2024-04-01', '12:00-12:50'),
(12, 'room200', '2024-04-01', '13:00-13:50'),
(13, 'room200', '2024-04-02', '12:00-12:50'),
(14, 'room200', '2024-04-02', '14:00-14:50'),
(15, 'room200', '2024-04-02', '15:00-15:50'),
(16, 'room200', '2024-04-03', '12:00-12:50'),
(17, 'room200', '2024-04-03', '13:00-13:50'),
(18, 'room200', '2024-04-03', '14:00-14:50'),
(19, 'room200', '2024-04-03', '15:00-15:50'),
(20, 'room200', '2024-04-03', '16:00-16:50'),
(21, 'room200', '2024-04-04', '10:00-10:50'),
(22, 'room200', '2024-04-04', '11:00-11:50'),
(23, 'room200', '2024-04-04', '12:00-12:50'),
(24, 'room200', '2024-04-04', '15:00-15:50'),
(25, 'room200', '2024-04-05', '09:00-09:50'),
(26, 'room200', '2024-04-05', '10:00-10:50'),
(27, 'room200', '2024-04-05', '11:00-11:50'),
(28, 'room200', '2024-04-05', '12:00-12:50'),
(29, 'room200', '2024-04-05', '13:00-13:50'),
(30, 'room200', '2024-04-05', '14:00-14:50'),
(31, 'room205', '2024-04-01', '09:00-09:50'),
(32, 'room205', '2024-04-01', '10:00-10:50'),
(33, 'room205', '2024-04-02', '09:00-09:50'),
(34, 'room205', '2024-04-02', '10:00-10:50'),
(35, 'room205', '2024-04-03', '09:00-09:50'),
(36, 'room205', '2024-04-03', '13:00-13:50'),
(37, 'room205', '2024-04-03', '14:00-14:50'),
(38, 'room205', '2024-04-03', '15:00-15:50'),
(39, 'room205', '2024-04-04', '11:00-11:50'),
(40, 'room205', '2024-04-04', '12:00-12:50'),
(41, 'room205', '2024-04-05', '16:00-16:50');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 03, 2024 at 04:31 AM
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

--
-- Dumping data for table `available_rooms`
--

INSERT INTO `available_rooms` (`room_id`, `room_name`) VALUES
(18, 'Taylor Hall 200'),
(19, 'Taylor Hall 300'),
(20, 'Taylor Hall 205');

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
('test', 'test@gmail.com', '2024-04-04', '12:00-12:50', 'Taylor Hall 300', 'Dr. Guarnera', 'Dr. Heather', 'test'),
('test2', 'test2@gmail.com', '2024-04-04', '16:00-16:50', 'Taylor Hall 200', 'Dr. Montelione', 'Dr. Heather', 'test2'),
('test3', 'test3@gmail.com', '2024-04-04', '12:00-12:50', 'Taylor Hall 200', 'Dr. Montelione', 'Dr. Visa', 'test3'),
('test4', 'test4@gmail.com', '2024-04-04', '16:00-16:50', 'Taylor Hall 300', 'Dr. Visa', 'Dr. Palmer', 'test4'),
('Turbat Enkhtur', 'enkhturbat6@gmail.com', '2024-04-01', '09:00-09:50', 'Taylor Hall 205', 'Dr. Visa', 'Dr. Heather', 'thesis');

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
(20, '2024-04-01', '2024-04-26');

-- --------------------------------------------------------

--
-- Table structure for table `panel_passwords`
--

CREATE TABLE `panel_passwords` (
  `panel` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `panel_passwords`
--

INSERT INTO `panel_passwords` (`panel`, `password`) VALUES
('professor', '12'),
('student', '123');

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
(14, 'Dr. Montelione', '2024-04-01', '12:00-12:50'),
(15, 'Dr. Montelione', '2024-04-01', '15:00-15:50'),
(16, 'Dr. Montelione', '2024-04-01', '16:00-16:50'),
(17, 'Dr. Montelione', '2024-04-01', '09:00-09:50'),
(18, 'Dr. Montelione', '2024-04-02', '11:00-11:50'),
(19, 'Dr. Montelione', '2024-04-02', '12:00-12:50'),
(20, 'Dr. Montelione', '2024-04-02', '15:00-15:50'),
(21, 'Dr. Montelione', '2024-04-02', '16:00-16:50'),
(22, 'Dr. Montelione', '2024-04-03', '12:00-12:50'),
(23, 'Dr. Montelione', '2024-04-03', '15:00-15:50'),
(24, 'Dr. Montelione', '2024-04-03', '16:00-16:50'),
(25, 'Dr. Montelione', '2024-04-03', '09:00-09:50'),
(26, 'Dr. Montelione', '2024-04-04', '11:00-11:50'),
(27, 'Dr. Montelione', '2024-04-04', '12:00-12:50'),
(28, 'Dr. Montelione', '2024-04-04', '15:00-15:50'),
(29, 'Dr. Montelione', '2024-04-04', '16:00-16:50'),
(30, 'Dr. Montelione', '2024-04-05', '12:00-12:50'),
(31, 'Dr. Montelione', '2024-04-05', '15:00-15:50'),
(32, 'Dr. Montelione', '2024-04-05', '16:00-16:50'),
(33, 'Dr. Montelione', '2024-04-05', '09:00-09:50'),
(34, 'Dr. Visa', '2024-04-01', '15:00-15:50'),
(35, 'Dr. Visa', '2024-04-01', '09:00-09:50'),
(36, 'Dr. Visa', '2024-04-02', '12:00-12:50'),
(37, 'Dr. Visa', '2024-04-02', '16:00-16:50'),
(38, 'Dr. Visa', '2024-04-02', '14:00-14:50'),
(39, 'Dr. Visa', '2024-04-02', '09:00-09:50'),
(40, 'Dr. Visa', '2024-04-03', '12:00-12:50'),
(41, 'Dr. Visa', '2024-04-03', '09:00-09:50'),
(42, 'Dr. Visa', '2024-04-04', '11:00-11:50'),
(43, 'Dr. Visa', '2024-04-04', '12:00-12:50'),
(44, 'Dr. Visa', '2024-04-04', '15:00-15:50'),
(45, 'Dr. Visa', '2024-04-04', '16:00-16:50'),
(46, 'Dr. Visa', '2024-04-05', '16:00-16:50'),
(47, 'Dr. Guarnera', '2024-04-01', '12:00-12:50'),
(48, 'Dr. Guarnera', '2024-04-01', '15:00-15:50'),
(49, 'Dr. Guarnera', '2024-04-01', '16:00-16:50'),
(50, 'Dr. Guarnera', '2024-04-01', '09:00-09:50'),
(51, 'Dr. Guarnera', '2024-04-01', '10:00-10:50'),
(52, 'Dr. Guarnera', '2024-04-02', '11:00-11:50'),
(53, 'Dr. Guarnera', '2024-04-02', '14:00-14:50'),
(54, 'Dr. Guarnera', '2024-04-02', '09:00-09:50'),
(55, 'Dr. Guarnera', '2024-04-03', '12:00-12:50'),
(56, 'Dr. Guarnera', '2024-04-03', '15:00-15:50'),
(57, 'Dr. Guarnera', '2024-04-03', '16:00-16:50'),
(58, 'Dr. Guarnera', '2024-04-03', '09:00-09:50'),
(59, 'Dr. Guarnera', '2024-04-03', '11:00-11:50'),
(60, 'Dr. Guarnera', '2024-04-03', '13:00-13:50'),
(61, 'Dr. Guarnera', '2024-04-03', '10:00-10:50'),
(62, 'Dr. Guarnera', '2024-04-04', '11:00-11:50'),
(63, 'Dr. Guarnera', '2024-04-04', '12:00-12:50'),
(64, 'Dr. Guarnera', '2024-04-04', '15:00-15:50'),
(65, 'Dr. Guarnera', '2024-04-04', '16:00-16:50'),
(66, 'Dr. Guarnera', '2024-04-04', '09:00-09:50'),
(67, 'Dr. Guarnera', '2024-04-05', '12:00-12:50'),
(68, 'Dr. Guarnera', '2024-04-05', '16:00-16:50'),
(69, 'Dr. Guarnera', '2024-04-05', '11:00-11:50'),
(70, 'Dr. Guarnera', '2024-04-05', '10:00-10:50'),
(71, 'Dr. Heather', '2024-04-01', '09:00-09:50'),
(72, 'Dr. Heather', '2024-04-01', '10:00-10:50'),
(73, 'Dr. Heather', '2024-04-02', '11:00-11:50'),
(74, 'Dr. Heather', '2024-04-02', '12:00-12:50'),
(75, 'Dr. Heather', '2024-04-02', '15:00-15:50'),
(76, 'Dr. Heather', '2024-04-02', '16:00-16:50'),
(77, 'Dr. Heather', '2024-04-02', '10:00-10:50'),
(78, 'Dr. Heather', '2024-04-02', '14:00-14:50'),
(79, 'Dr. Heather', '2024-04-02', '09:00-09:50'),
(80, 'Dr. Heather', '2024-04-03', '16:00-16:50'),
(81, 'Dr. Heather', '2024-04-03', '11:00-11:50'),
(82, 'Dr. Heather', '2024-04-03', '10:00-10:50'),
(83, 'Dr. Heather', '2024-04-04', '11:00-11:50'),
(84, 'Dr. Heather', '2024-04-04', '12:00-12:50'),
(85, 'Dr. Heather', '2024-04-04', '15:00-15:50'),
(86, 'Dr. Heather', '2024-04-04', '16:00-16:50'),
(87, 'Dr. Heather', '2024-04-04', '10:00-10:50'),
(88, 'Dr. Heather', '2024-04-04', '14:00-14:50'),
(89, 'Dr. Heather', '2024-04-04', '09:00-09:50'),
(90, 'Dr. Palmer', '2024-04-04', '11:00-11:50'),
(91, 'Dr. Palmer', '2024-04-04', '12:00-12:50'),
(92, 'Dr. Palmer', '2024-04-04', '15:00-15:50'),
(93, 'Dr. Palmer', '2024-04-04', '16:00-16:50'),
(94, 'Dr. Palmer', '2024-04-04', '14:00-14:50');

-- --------------------------------------------------------

--
-- Table structure for table `professor_list`
--

CREATE TABLE `professor_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `professor_list`
--

INSERT INTO `professor_list` (`id`, `name`) VALUES
(15, 'Dr. Montelione'),
(16, 'Dr. Visa'),
(17, 'Dr. Guarnera'),
(18, 'Dr. Heather'),
(19, 'Dr. Palmer');

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
(282, 'Taylor Hall 200', '2024-04-01', '12:00-12:50'),
(283, 'Taylor Hall 200', '2024-04-01', '15:00-15:50'),
(284, 'Taylor Hall 200', '2024-04-01', '16:00-16:50'),
(285, 'Taylor Hall 200', '2024-04-02', '11:00-11:50'),
(286, 'Taylor Hall 200', '2024-04-02', '12:00-12:50'),
(287, 'Taylor Hall 200', '2024-04-02', '15:00-15:50'),
(288, 'Taylor Hall 200', '2024-04-02', '16:00-16:50'),
(289, 'Taylor Hall 200', '2024-04-03', '12:00-12:50'),
(290, 'Taylor Hall 200', '2024-04-03', '15:00-15:50'),
(291, 'Taylor Hall 200', '2024-04-03', '16:00-16:50'),
(292, 'Taylor Hall 200', '2024-04-04', '11:00-11:50'),
(293, 'Taylor Hall 200', '2024-04-04', '12:00-12:50'),
(294, 'Taylor Hall 200', '2024-04-04', '15:00-15:50'),
(295, 'Taylor Hall 200', '2024-04-04', '16:00-16:50'),
(296, 'Taylor Hall 200', '2024-04-05', '12:00-12:50'),
(297, 'Taylor Hall 200', '2024-04-05', '15:00-15:50'),
(298, 'Taylor Hall 200', '2024-04-05', '16:00-16:50'),
(299, 'Taylor Hall 200', '2024-04-08', '12:00-12:50'),
(300, 'Taylor Hall 200', '2024-04-08', '15:00-15:50'),
(301, 'Taylor Hall 200', '2024-04-08', '16:00-16:50'),
(302, 'Taylor Hall 200', '2024-04-09', '11:00-11:50'),
(303, 'Taylor Hall 200', '2024-04-09', '12:00-12:50'),
(304, 'Taylor Hall 200', '2024-04-09', '15:00-15:50'),
(305, 'Taylor Hall 200', '2024-04-09', '16:00-16:50'),
(306, 'Taylor Hall 200', '2024-04-10', '12:00-12:50'),
(307, 'Taylor Hall 200', '2024-04-10', '15:00-15:50'),
(308, 'Taylor Hall 200', '2024-04-10', '16:00-16:50'),
(309, 'Taylor Hall 200', '2024-04-11', '11:00-11:50'),
(310, 'Taylor Hall 200', '2024-04-11', '12:00-12:50'),
(311, 'Taylor Hall 200', '2024-04-11', '15:00-15:50'),
(312, 'Taylor Hall 200', '2024-04-11', '16:00-16:50'),
(313, 'Taylor Hall 200', '2024-04-12', '12:00-12:50'),
(314, 'Taylor Hall 200', '2024-04-12', '15:00-15:50'),
(315, 'Taylor Hall 200', '2024-04-12', '16:00-16:50'),
(316, 'Taylor Hall 200', '2024-04-15', '09:00-09:50'),
(317, 'Taylor Hall 200', '2024-04-15', '10:00-10:50'),
(318, 'Taylor Hall 200', '2024-04-15', '11:00-11:50'),
(319, 'Taylor Hall 200', '2024-04-15', '12:00-12:50'),
(320, 'Taylor Hall 200', '2024-04-15', '13:00-13:50'),
(321, 'Taylor Hall 200', '2024-04-16', '11:00-11:50'),
(322, 'Taylor Hall 200', '2024-04-16', '12:00-12:50'),
(323, 'Taylor Hall 200', '2024-04-16', '14:00-14:50'),
(324, 'Taylor Hall 200', '2024-04-16', '15:00-15:50'),
(325, 'Taylor Hall 200', '2024-04-16', '16:00-16:50'),
(326, 'Taylor Hall 200', '2024-04-17', '09:00-09:50'),
(327, 'Taylor Hall 200', '2024-04-17', '10:00-10:50'),
(328, 'Taylor Hall 200', '2024-04-17', '11:00-11:50'),
(329, 'Taylor Hall 200', '2024-04-17', '12:00-12:50'),
(330, 'Taylor Hall 200', '2024-04-17', '13:00-13:50'),
(331, 'Taylor Hall 200', '2024-04-18', '11:00-11:50'),
(332, 'Taylor Hall 200', '2024-04-18', '12:00-12:50'),
(333, 'Taylor Hall 200', '2024-04-18', '14:00-14:50'),
(334, 'Taylor Hall 200', '2024-04-18', '15:00-15:50'),
(335, 'Taylor Hall 200', '2024-04-18', '16:00-16:50'),
(336, 'Taylor Hall 200', '2024-04-19', '09:00-09:50'),
(337, 'Taylor Hall 200', '2024-04-19', '10:00-10:50'),
(338, 'Taylor Hall 200', '2024-04-19', '11:00-11:50'),
(339, 'Taylor Hall 200', '2024-04-19', '12:00-12:50'),
(340, 'Taylor Hall 200', '2024-04-19', '13:00-13:50'),
(341, 'Taylor Hall 200', '2024-04-22', '13:00-13:50'),
(342, 'Taylor Hall 200', '2024-04-22', '14:00-14:50'),
(343, 'Taylor Hall 200', '2024-04-22', '15:00-15:50'),
(344, 'Taylor Hall 200', '2024-04-22', '16:00-16:50'),
(345, 'Taylor Hall 200', '2024-04-23', '10:00-10:50'),
(346, 'Taylor Hall 200', '2024-04-23', '11:00-11:50'),
(347, 'Taylor Hall 200', '2024-04-23', '14:00-14:50'),
(348, 'Taylor Hall 200', '2024-04-23', '15:00-15:50'),
(349, 'Taylor Hall 200', '2024-04-23', '16:00-16:50'),
(350, 'Taylor Hall 200', '2024-04-24', '13:00-13:50'),
(351, 'Taylor Hall 200', '2024-04-24', '14:00-14:50'),
(352, 'Taylor Hall 200', '2024-04-24', '15:00-15:50'),
(353, 'Taylor Hall 200', '2024-04-24', '16:00-16:50'),
(354, 'Taylor Hall 200', '2024-04-25', '10:00-10:50'),
(355, 'Taylor Hall 200', '2024-04-25', '11:00-11:50'),
(356, 'Taylor Hall 200', '2024-04-25', '14:00-14:50'),
(357, 'Taylor Hall 200', '2024-04-25', '15:00-15:50'),
(358, 'Taylor Hall 200', '2024-04-25', '16:00-16:50'),
(359, 'Taylor Hall 200', '2024-04-26', '13:00-13:50'),
(360, 'Taylor Hall 200', '2024-04-26', '14:00-14:50'),
(361, 'Taylor Hall 200', '2024-04-26', '15:00-15:50'),
(362, 'Taylor Hall 200', '2024-04-26', '16:00-16:50'),
(363, 'Taylor Hall 300', '2024-04-01', '09:00-09:50'),
(364, 'Taylor Hall 300', '2024-04-01', '11:00-11:50'),
(365, 'Taylor Hall 300', '2024-04-01', '13:00-13:50'),
(366, 'Taylor Hall 300', '2024-04-01', '15:00-15:50'),
(367, 'Taylor Hall 300', '2024-04-02', '10:00-10:50'),
(368, 'Taylor Hall 300', '2024-04-02', '12:00-12:50'),
(369, 'Taylor Hall 300', '2024-04-02', '14:00-14:50'),
(370, 'Taylor Hall 300', '2024-04-02', '16:00-16:50'),
(371, 'Taylor Hall 300', '2024-04-03', '09:00-09:50'),
(372, 'Taylor Hall 300', '2024-04-03', '11:00-11:50'),
(373, 'Taylor Hall 300', '2024-04-03', '13:00-13:50'),
(374, 'Taylor Hall 300', '2024-04-03', '15:00-15:50'),
(375, 'Taylor Hall 300', '2024-04-04', '10:00-10:50'),
(376, 'Taylor Hall 300', '2024-04-04', '12:00-12:50'),
(377, 'Taylor Hall 300', '2024-04-04', '14:00-14:50'),
(378, 'Taylor Hall 300', '2024-04-04', '16:00-16:50'),
(379, 'Taylor Hall 300', '2024-04-05', '09:00-09:50'),
(380, 'Taylor Hall 300', '2024-04-05', '11:00-11:50'),
(381, 'Taylor Hall 300', '2024-04-05', '13:00-13:50'),
(382, 'Taylor Hall 300', '2024-04-05', '15:00-15:50'),
(383, 'Taylor Hall 300', '2024-04-08', '09:00-09:50'),
(384, 'Taylor Hall 300', '2024-04-08', '10:00-10:50'),
(385, 'Taylor Hall 300', '2024-04-08', '11:00-11:50'),
(386, 'Taylor Hall 300', '2024-04-08', '14:00-14:50'),
(387, 'Taylor Hall 300', '2024-04-08', '15:00-15:50'),
(388, 'Taylor Hall 300', '2024-04-08', '16:00-16:50'),
(389, 'Taylor Hall 300', '2024-04-09', '09:00-09:50'),
(390, 'Taylor Hall 300', '2024-04-09', '10:00-10:50'),
(391, 'Taylor Hall 300', '2024-04-09', '11:00-11:50'),
(392, 'Taylor Hall 300', '2024-04-09', '14:00-14:50'),
(393, 'Taylor Hall 300', '2024-04-09', '15:00-15:50'),
(394, 'Taylor Hall 300', '2024-04-09', '16:00-16:50'),
(395, 'Taylor Hall 300', '2024-04-10', '09:00-09:50'),
(396, 'Taylor Hall 300', '2024-04-10', '10:00-10:50'),
(397, 'Taylor Hall 300', '2024-04-10', '11:00-11:50'),
(398, 'Taylor Hall 300', '2024-04-10', '14:00-14:50'),
(399, 'Taylor Hall 300', '2024-04-10', '15:00-15:50'),
(400, 'Taylor Hall 300', '2024-04-10', '16:00-16:50'),
(401, 'Taylor Hall 300', '2024-04-11', '09:00-09:50'),
(402, 'Taylor Hall 300', '2024-04-11', '10:00-10:50'),
(403, 'Taylor Hall 300', '2024-04-11', '11:00-11:50'),
(404, 'Taylor Hall 300', '2024-04-11', '14:00-14:50'),
(405, 'Taylor Hall 300', '2024-04-11', '15:00-15:50'),
(406, 'Taylor Hall 300', '2024-04-11', '16:00-16:50'),
(407, 'Taylor Hall 300', '2024-04-12', '09:00-09:50'),
(408, 'Taylor Hall 300', '2024-04-12', '10:00-10:50'),
(409, 'Taylor Hall 300', '2024-04-12', '11:00-11:50'),
(410, 'Taylor Hall 300', '2024-04-12', '14:00-14:50'),
(411, 'Taylor Hall 300', '2024-04-12', '15:00-15:50'),
(412, 'Taylor Hall 300', '2024-04-12', '16:00-16:50'),
(413, 'Taylor Hall 300', '2024-04-15', '09:00-09:50'),
(414, 'Taylor Hall 300', '2024-04-15', '10:00-10:50'),
(415, 'Taylor Hall 300', '2024-04-15', '12:00-12:50'),
(416, 'Taylor Hall 300', '2024-04-15', '13:00-13:50'),
(417, 'Taylor Hall 300', '2024-04-15', '15:00-15:50'),
(418, 'Taylor Hall 300', '2024-04-15', '16:00-16:50'),
(419, 'Taylor Hall 300', '2024-04-16', '09:00-09:50'),
(420, 'Taylor Hall 300', '2024-04-16', '10:00-10:50'),
(421, 'Taylor Hall 300', '2024-04-16', '12:00-12:50'),
(422, 'Taylor Hall 300', '2024-04-16', '13:00-13:50'),
(423, 'Taylor Hall 300', '2024-04-16', '15:00-15:50'),
(424, 'Taylor Hall 300', '2024-04-16', '16:00-16:50'),
(425, 'Taylor Hall 300', '2024-04-17', '09:00-09:50'),
(426, 'Taylor Hall 300', '2024-04-17', '10:00-10:50'),
(427, 'Taylor Hall 300', '2024-04-17', '12:00-12:50'),
(428, 'Taylor Hall 300', '2024-04-17', '13:00-13:50'),
(429, 'Taylor Hall 300', '2024-04-17', '15:00-15:50'),
(430, 'Taylor Hall 300', '2024-04-17', '16:00-16:50'),
(431, 'Taylor Hall 300', '2024-04-18', '09:00-09:50'),
(432, 'Taylor Hall 300', '2024-04-18', '10:00-10:50'),
(433, 'Taylor Hall 300', '2024-04-18', '12:00-12:50'),
(434, 'Taylor Hall 300', '2024-04-18', '13:00-13:50'),
(435, 'Taylor Hall 300', '2024-04-18', '15:00-15:50'),
(436, 'Taylor Hall 300', '2024-04-18', '16:00-16:50'),
(437, 'Taylor Hall 300', '2024-04-19', '09:00-09:50'),
(438, 'Taylor Hall 300', '2024-04-19', '10:00-10:50'),
(439, 'Taylor Hall 300', '2024-04-19', '12:00-12:50'),
(440, 'Taylor Hall 300', '2024-04-19', '13:00-13:50'),
(441, 'Taylor Hall 300', '2024-04-19', '15:00-15:50'),
(442, 'Taylor Hall 300', '2024-04-19', '16:00-16:50'),
(443, 'Taylor Hall 300', '2024-04-22', '10:00-10:50'),
(444, 'Taylor Hall 300', '2024-04-22', '11:00-11:50'),
(445, 'Taylor Hall 300', '2024-04-22', '13:00-13:50'),
(446, 'Taylor Hall 300', '2024-04-22', '15:00-15:50'),
(447, 'Taylor Hall 300', '2024-04-23', '10:00-10:50'),
(448, 'Taylor Hall 300', '2024-04-23', '11:00-11:50'),
(449, 'Taylor Hall 300', '2024-04-23', '13:00-13:50'),
(450, 'Taylor Hall 300', '2024-04-23', '15:00-15:50'),
(451, 'Taylor Hall 300', '2024-04-24', '10:00-10:50'),
(452, 'Taylor Hall 300', '2024-04-24', '11:00-11:50'),
(453, 'Taylor Hall 300', '2024-04-24', '13:00-13:50'),
(454, 'Taylor Hall 300', '2024-04-24', '15:00-15:50'),
(455, 'Taylor Hall 300', '2024-04-25', '10:00-10:50'),
(456, 'Taylor Hall 300', '2024-04-25', '11:00-11:50'),
(457, 'Taylor Hall 300', '2024-04-25', '13:00-13:50'),
(458, 'Taylor Hall 300', '2024-04-25', '15:00-15:50'),
(459, 'Taylor Hall 300', '2024-04-26', '10:00-10:50'),
(460, 'Taylor Hall 300', '2024-04-26', '11:00-11:50'),
(461, 'Taylor Hall 300', '2024-04-26', '13:00-13:50'),
(462, 'Taylor Hall 300', '2024-04-26', '15:00-15:50'),
(463, 'Taylor Hall 205', '2024-04-01', '09:00-09:50'),
(464, 'Taylor Hall 205', '2024-04-01', '10:00-10:50'),
(465, 'Taylor Hall 205', '2024-04-01', '11:00-11:50'),
(466, 'Taylor Hall 205', '2024-04-01', '12:00-12:50'),
(467, 'Taylor Hall 205', '2024-04-02', '09:00-09:50'),
(468, 'Taylor Hall 205', '2024-04-02', '10:00-10:50'),
(469, 'Taylor Hall 205', '2024-04-02', '11:00-11:50'),
(470, 'Taylor Hall 205', '2024-04-02', '12:00-12:50'),
(471, 'Taylor Hall 205', '2024-04-03', '09:00-09:50'),
(472, 'Taylor Hall 205', '2024-04-03', '10:00-10:50'),
(473, 'Taylor Hall 205', '2024-04-03', '11:00-11:50'),
(474, 'Taylor Hall 205', '2024-04-03', '12:00-12:50'),
(475, 'Taylor Hall 205', '2024-04-04', '09:00-09:50'),
(476, 'Taylor Hall 205', '2024-04-04', '10:00-10:50'),
(477, 'Taylor Hall 205', '2024-04-04', '11:00-11:50'),
(478, 'Taylor Hall 205', '2024-04-04', '12:00-12:50'),
(479, 'Taylor Hall 205', '2024-04-05', '09:00-09:50'),
(480, 'Taylor Hall 205', '2024-04-05', '10:00-10:50'),
(481, 'Taylor Hall 205', '2024-04-05', '11:00-11:50'),
(482, 'Taylor Hall 205', '2024-04-05', '12:00-12:50'),
(483, 'Taylor Hall 205', '2024-04-15', '09:00-09:50'),
(484, 'Taylor Hall 205', '2024-04-15', '10:00-10:50'),
(485, 'Taylor Hall 205', '2024-04-15', '11:00-11:50'),
(486, 'Taylor Hall 205', '2024-04-15', '12:00-12:50'),
(487, 'Taylor Hall 205', '2024-04-15', '13:00-13:50'),
(488, 'Taylor Hall 205', '2024-04-16', '09:00-09:50'),
(489, 'Taylor Hall 205', '2024-04-16', '10:00-10:50'),
(490, 'Taylor Hall 205', '2024-04-16', '11:00-11:50'),
(491, 'Taylor Hall 205', '2024-04-16', '12:00-12:50'),
(492, 'Taylor Hall 205', '2024-04-16', '13:00-13:50'),
(493, 'Taylor Hall 205', '2024-04-17', '09:00-09:50'),
(494, 'Taylor Hall 205', '2024-04-17', '10:00-10:50'),
(495, 'Taylor Hall 205', '2024-04-17', '11:00-11:50'),
(496, 'Taylor Hall 205', '2024-04-17', '12:00-12:50'),
(497, 'Taylor Hall 205', '2024-04-17', '13:00-13:50'),
(498, 'Taylor Hall 205', '2024-04-18', '09:00-09:50'),
(499, 'Taylor Hall 205', '2024-04-18', '10:00-10:50'),
(500, 'Taylor Hall 205', '2024-04-18', '11:00-11:50'),
(501, 'Taylor Hall 205', '2024-04-18', '12:00-12:50'),
(502, 'Taylor Hall 205', '2024-04-18', '13:00-13:50'),
(503, 'Taylor Hall 205', '2024-04-19', '09:00-09:50'),
(504, 'Taylor Hall 205', '2024-04-19', '10:00-10:50'),
(505, 'Taylor Hall 205', '2024-04-19', '11:00-11:50'),
(506, 'Taylor Hall 205', '2024-04-19', '12:00-12:50'),
(507, 'Taylor Hall 205', '2024-04-19', '13:00-13:50');

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
-- Indexes for table `panel_passwords`
--
ALTER TABLE `panel_passwords`
  ADD PRIMARY KEY (`panel`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professor_list`
--
ALTER TABLE `professor_list`
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
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `defense_schedule`
--
ALTER TABLE `defense_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `professor_list`
--
ALTER TABLE `professor_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=508;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

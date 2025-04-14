-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 14, 2025 at 05:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `students`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(9) NOT NULL,
  `admin_id` int(20) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `midname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_id`, `lastname`, `firstname`, `midname`, `username`, `password`) VALUES
(1, 1, 'Bahan', 'Aldrian', 'Orillosa', 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `description`, `created_at`) VALUES
(4, 'dasdad', 'asda', '2025-03-13 03:15:24'),
(5, 'CSS Days!', 'No class today', '2025-03-13 03:15:24'),
(6, 'CSS Days!', 'No class today', '2025-03-13 03:15:24'),
(7, 'UC days!', 'No class today', '2025-03-13 03:30:41'),
(8, 'Ben Teo', 'bayot', '2025-03-13 03:32:04'),
(9, 'Joshua', 'bayoooooooooooot', '2025-03-13 03:53:50'),
(10, 'Intramurals 2025!', 'Everyone should complete the tatak form.', '2025-03-13 04:10:40'),
(11, 'Renzo', 'bayot', '2025-03-13 04:40:51'),
(12, 'Brah bayot', 'brah bayot\r\n', '2025-03-20 04:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `idno` int(11) DEFAULT NULL,
  `feedback_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `idno`, `feedback_text`, `created_at`) VALUES
(1, 22668339, 'asdasd', '2025-04-10 18:23:59'),
(2, 22668339, 'asdadasdasd', '2025-04-13 12:13:55'),
(3, 22668339, 'asdddsadasdasda', '2025-04-13 14:46:38'),
(4, 22668339, 'asasddasdd', '2025-04-13 16:11:52'),
(5, 22668339, 'asdadas it was godo and awse om buta asndakdndak nsadkanskdandpnaspdnaskpdnsapkdskadaksndakndakddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd', '2025-04-13 19:43:16'),
(6, 22668336, 'it was nice\r\n', '2025-04-14 06:02:38'),
(7, 22668336, 'adada bati', '2025-04-14 06:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `idno` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('reserved','cancelled','completed') DEFAULT 'reserved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` enum('available','occupied','maintenance') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sit_in`
--

CREATE TABLE `sit_in` (
  `id` int(50) NOT NULL,
  `idno` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `midname` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `sitin_purpose` enum('Programming','Research','Networking') NOT NULL,
  `lab` enum('524','526','528') NOT NULL,
  `time_in` timestamp NULL DEFAULT NULL,
  `time_out` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sit_in`
--

INSERT INTO `sit_in` (`id`, `idno`, `lastname`, `firstname`, `midname`, `course`, `year`, `sitin_purpose`, `lab`, `time_in`, `time_out`) VALUES
(51, 22668339, 'Bahan', 'Aldrians', 'Orillosa', 'BSCS', 1, 'Programming', '524', '2025-04-13 18:37:13', '2025-04-13 18:50:15'),
(52, 22668339, 'Bahan', 'Aldrians', 'Orillosa', 'BSCS', 1, 'Programming', '524', '2025-04-13 19:02:13', '2025-04-13 19:26:51'),
(53, 22668336, 'Abadiano', 'Mary Rose', 'Casue', 'BSIT', 2, 'Programming', '524', '2025-04-14 04:51:14', '2025-04-14 04:51:19'),
(54, 22668336, 'Abadiano', 'Mary Rose', 'Casue', 'BSIT', 2, 'Programming', '524', '2025-04-14 04:51:25', '2025-04-14 04:52:44'),
(55, 22668339, 'Bahan', 'Aldrians', 'Orillosa', 'BSCS', 1, 'Programming', '524', '2025-04-14 04:51:46', '2025-04-14 04:52:43'),
(56, 22668339, 'Bahan', 'Aldrians', 'Orillosa', 'BSCS', 1, 'Programming', '524', '2025-04-14 05:07:38', '2025-04-14 05:07:47'),
(57, 22668336, 'Abadiano', 'Mary Rose', 'Casue', 'BSIT', 2, 'Programming', '524', '2025-04-14 05:07:43', '2025-04-14 05:07:48'),
(58, 22668339, 'Bahan', 'Aldrians', 'Orillosa', 'BSCS', 1, 'Research', '528', '2025-04-14 06:18:33', '2025-04-14 06:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(20) NOT NULL,
  `idno` int(20) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `midname` varchar(50) NOT NULL,
  `course` enum('BSIT','BSCS') NOT NULL,
  `year` int(9) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `profile` varchar(225) NOT NULL,
  `session` int(30) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `idno`, `lastname`, `firstname`, `midname`, `course`, `year`, `username`, `password`, `profile`, `session`) VALUES
(1, 22668339, 'Bahan', 'Aldrians', 'Orillosa', 'BSCS', 1, 'aldrian123123', '123', '67fb5dbc15c92_profile.png', 29),
(3, 22668336, 'Abadiano', 'Mary Rose', 'Casue', 'BSIT', 2, 'abads', '123', '', 30),
(4, 1111, 'Bahan', 'asadas', 'asdasd', 'BSIT', 1, '123123', '123', '', 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_id` (`admin_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `fk_room` (`room_id`),
  ADD KEY `fk_idno` (`idno`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `sit_in`
--
ALTER TABLE `sit_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idno` (`idno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sit_in`
--
ALTER TABLE `sit_in`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_idno` FOREIGN KEY (`idno`) REFERENCES `students` (`idno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

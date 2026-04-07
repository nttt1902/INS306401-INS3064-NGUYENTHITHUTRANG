-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 03:16 AM
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
-- Database: `student_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `room` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `subject_name`, `semester`, `academic_year`, `room`, `created_at`) VALUES
(1, 'SE0601', 'Web Programming', '2025-1', '2024-2025', 'A101', '2026-03-31 00:41:20'),
(2, 'SE0602', 'Database Systems', '2025-1', '2024-2025', 'B202', '2026-03-31 00:41:20'),
(3, 'AI0101', 'Artificial Intelligence', '2025-1', '2024-2025', 'C303', '2026-03-31 00:41:20');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `student_code` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `class_id`, `student_code`, `full_name`, `date_of_birth`, `email`, `gender`, `created_at`) VALUES
(1, 1, '20128573', 'Nguyen Van An', '2003-05-15', 'an.nv@school.edu.vn', 'Male', '2026-03-31 00:41:20'),
(2, 1, '20128574', 'Le Thi Binh', '2003-06-20', 'binh.lt@school.edu.vn', 'Female', '2026-03-31 00:41:20'),
(3, 1, '20128575', 'Tran Van Cuong', '2003-01-10', 'cuong.tv@school.edu.vn', 'Male', '2026-03-31 00:41:20'),
(4, 2, '20128576', 'Pham Minh Duc', '2003-12-05', 'duc.pm@school.edu.vn', 'Male', '2026-03-31 00:41:20'),
(5, 2, '20128577', 'Hoang Thanh E', '2003-03-25', 'e.ht@school.edu.vn', 'Female', '2026-03-31 00:41:20'),
(6, 2, '20128578', 'Vu Hoang Giang', '2003-08-30', 'giang.vh@school.edu.vn', 'Male', '2026-03-31 00:41:20'),
(7, 3, '20128579', 'Dang Thu Ha', '2003-09-12', 'ha.dt@school.edu.vn', 'Female', '2026-03-31 00:41:20'),
(8, 3, '20128580', 'Bui Quang Huy', '2003-11-22', 'huy.bq@school.edu.vn', 'Male', '2026-03-31 00:41:20'),
(9, 3, '20128581', 'Do Kim Khanh', '2003-02-14', 'khanh.dk@school.edu.vn', 'Female', '2026-03-31 00:41:20'),
(11, 1, '23070493', 'Trịnh Như Long', '2005-03-04', '23070493@vnu.edu.vn', 'Male', '2026-03-31 01:15:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_code` (`student_code`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

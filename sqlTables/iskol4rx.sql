-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 02:43 PM
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
-- Database: `iskol4rx`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('admin','tutor','learner') NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_initial` char(5) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `first_name`, `middle_initial`, `last_name`, `email`, `password`, `profile_picture`, `status`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'John Chester', 'A.', 'De Guzman', 'chesdeguzman05@gmail.com', '$2y$10$OglX4hGf8OLIx1iwbwvGF.5OM9C4ew6PJ.WqqBTJatBk1MZ0DvFbm', NULL, 'Active', NULL, NULL, '2025-03-26 15:36:52', '2025-04-03 06:43:42'),
(2, 'Jon Zeph', 'R.', 'Glodoviza', 'jonzeph2005@gmail.com', '$2y$10$djUQn3Yyx6VTeAIitojKW.v3DbLGe9txAs0nooGJPXToR3SOTlZE.', NULL, 'Active', NULL, NULL, '2025-03-27 07:50:57', '2025-03-27 07:53:42'),
(3, 'Juan', 'D.', 'Appdev', 'juandomappdev@gmail.com', '$2y$10$aSIkaxtVboFv74AN4Cj45OH3FFZGnemGuX/U4Dx5U/msYe/eUcFiC', NULL, 'Active', NULL, NULL, '2025-04-04 07:03:06', '2025-04-04 07:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `learner_id` int(11) NOT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `offer` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `status` enum('pending','ongoing','for review','completed','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reviewed` tinyint(1) NOT NULL DEFAULT 0,
  `rating` decimal(2,1) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `learner_id`, `tutor_id`, `address`, `date`, `start_time`, `end_time`, `offer`, `subject`, `status`, `created_at`, `updated_at`, `reviewed`, `rating`, `review_text`, `is_deleted`) VALUES
(1, 1, 1, '123 Elm St, Springfield, IL', '2025-05-01', '10:00:00', '11:00:00', '1000', 'Mathematics', 'rejected', '2025-04-28 10:27:01', '2025-05-13 12:36:53', 0, NULL, NULL, 1),
(2, 15, 5, '456 Oak St, Chicago, IL', '2025-05-02', '11:00:00', '12:00:00', '1200', 'Science', 'completed', '2025-04-28 10:27:01', '2025-04-28 10:54:46', 1, 4.0, 'Great experience! The tutor was patient, and the explanations were very detailed. I learned a lot during the session.\"', 0),
(3, 16, 6, '789 Pine St, Urbana, IL', '2025-05-03', '14:00:00', '15:00:00', '1100', 'English', 'pending', '2025-04-28 10:27:01', '2025-05-10 10:58:54', 0, NULL, 'I appreciate the tutor’s effort to ensure I understood each concept. They were kind, approachable, and professional.,', 0),
(4, 17, 7, '101 Maple St, Peoria, IL', '2025-05-04', '15:00:00', '16:00:00', '1300', 'Social Studies', 'completed', '2025-04-28 10:27:01', '2025-04-28 10:54:48', 1, 3.5, 'The session was helpful, but I felt like we could have gone over a few more examples. Overall, it was a good learning experience.', 0),
(5, 18, 8, '202 Birch St, Decatur, IL', '2025-05-05', '09:00:00', '10:00:00', '900', 'Filipino', 'pending', '2025-04-28 10:27:01', '2025-04-28 10:54:57', 0, NULL, 'I didn’t quite understand everything during the lesson, but I believe with more practice, I will improve. The tutor was encouraging and supportive.', 0),
(6, 19, 9, '123 Elm St, Springfield, IL', '2025-05-06', '12:00:00', '13:00:00', '1000', 'TLE', 'completed', '2025-04-28 10:27:01', '2025-04-28 10:55:00', 1, 4.5, 'Excellent tutor! Very knowledgeable and friendly. I learned a lot in a short amount of time. Highly recommend!', 0),
(74, 15, 16, 'WQEDSDFSF1', '2025-05-10', '10:00:00', '04:00:00', '2000', 'English', 'cancelled', '2025-05-05 11:43:03', '2025-05-13 00:55:54', 0, NULL, NULL, 0),
(75, 1, 1, 'Gordon College', '2025-05-06', '19:30:00', '22:30:00', '1500', 'English', 'cancelled', '2025-05-06 11:44:25', '2025-05-13 12:37:09', 0, NULL, '', 0),
(77, 1, 1, 'Gordon College', '2025-10-05', '10:30:00', '02:30:00', '1500', 'Research', 'completed', '2025-05-07 04:23:59', '2025-05-13 01:04:43', 1, 5.0, 'THANK YOU', 0),
(78, 1, 1, 'Gordon College', '2025-05-10', '06:00:00', '10:00:00', '1500', 'ICT', 'for review', '2025-05-07 04:24:32', '2025-05-13 01:04:44', 0, NULL, NULL, 0),
(79, 1, 5, 'Sto. Tomas', '2025-05-12', '07:00:00', '10:00:00', '1500', 'English', 'ongoing', '2025-05-11 03:15:48', '2025-05-13 12:37:11', 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `learner`
--

CREATE TABLE `learner` (
  `learner_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `school_affiliation` varchar(255) DEFAULT NULL,
  `grade_level` enum('N/A','G7','G8','G9','G10','G11','G12') NOT NULL,
  `strand` enum('N/A','STEM','ABM','HUMSS','GAS') NOT NULL DEFAULT 'N/A',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learner`
--

INSERT INTO `learner` (`learner_id`, `email`, `password`, `first_name`, `middle_initial`, `last_name`, `birthdate`, `profile_picture`, `school_affiliation`, `grade_level`, `strand`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'luiseflorenzdiaz@gmail.com', '$2y$10$uY8Ba3ZE0gTnxQcVWKjSVu830B7HLmYA3lLLLsLha41psXr2tYH0G', 'Luise Florenz', 'J', 'Diaz', '2004-12-31', NULL, 'Columban College Inc. - Asinan Campus', 'G12', 'STEM', NULL, NULL, '2025-04-03 08:05:39', '2025-04-29 04:06:41'),
(15, 'alice.johnson@example.com', '$2y$10$sdA5PTiQcfEYZ2PMPMfHO.zYzMcIFgpzm8H1gApQL.vy/zEgWhOBG', 'Alice', 'J', 'Johnson', '2006-03-15', 'alice.jpg', 'Springfield High School', 'G10', 'STEM', NULL, NULL, '2025-04-28 10:22:21', '2025-05-10 11:00:15'),
(16, 'bob.smith@example.com', 'password123', 'Bob', 'S', 'Smith', '2007-08-22', 'bob.jpg', 'Central High School', 'G9', 'ABM', NULL, NULL, '2025-04-28 10:22:21', '2025-04-28 10:22:21'),
(17, 'charlie.brown@example.com', 'password123', 'Charlie', 'B', 'Brown', '2005-11-11', 'charlie.jpg', 'North High School', 'G11', 'HUMSS', NULL, NULL, '2025-04-28 10:22:21', '2025-04-28 10:22:21'),
(18, 'dana.white@example.com', 'password123', 'Dana', 'W', 'White', '2006-05-30', 'dana.jpg', 'Westside Academy', 'G12', 'GAS', NULL, NULL, '2025-04-28 10:22:21', '2025-04-28 10:22:21'),
(19, 'eva.green@example.com', 'password123', 'Eva', 'G', 'Green', '2008-01-17', 'eva.jpg', 'Green Valley School', 'G7', 'STEM', NULL, NULL, '2025-04-28 10:22:21', '2025-04-28 10:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `specialization_id` int(11) NOT NULL,
  `specialization_name` varchar(255) DEFAULT NULL,
  `category` enum('JHS','SHS') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`specialization_id`, `specialization_name`, `category`) VALUES
(1, 'Mother Tongue', 'JHS'),
(2, 'Filipino', 'JHS'),
(3, 'English', 'JHS'),
(4, 'Mathematics', 'JHS'),
(5, 'Science', 'JHS'),
(6, 'Social Studies', 'JHS'),
(7, 'Values Education', 'JHS'),
(8, 'MAPEH', 'JHS'),
(9, 'TLE', 'JHS'),
(10, 'Research', 'SHS'),
(11, 'SHS Math', 'SHS'),
(12, 'SHS Eng', 'SHS'),
(13, 'SHS Sci', 'SHS'),
(14, 'Humanities and Social Science', 'SHS'),
(15, 'Business and Accounting', 'SHS'),
(16, 'ICT', 'SHS'),
(17, 'SHS Filipino', 'SHS');

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE `tutor` (
  `tutor_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `educational_attainment` enum('Junior High School Graduate','Senior High School Graduate','College Undergraduate','Associate''s Degree','Bachelor''s Degree','Master''s Degree','Doctoral Degree') DEFAULT NULL,
  `years_of_experience` enum('Less than 1 year','1-3 years','4-6 years','7+ years') DEFAULT 'Less than 1 year',
  `diploma` varchar(255) DEFAULT NULL,
  `other_certificates` varchar(255) DEFAULT NULL,
  `rate_per_hour` decimal(10,2) DEFAULT NULL,
  `rate_per_session` decimal(10,2) DEFAULT NULL,
  `num_bookings` int(11) DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT NULL,
  `status` enum('N/A','For Verification','Verified','Unverified') NOT NULL DEFAULT 'N/A',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`tutor_id`, `email`, `password`, `first_name`, `middle_initial`, `last_name`, `birthdate`, `profile_picture`, `educational_attainment`, `years_of_experience`, `diploma`, `other_certificates`, `rate_per_hour`, `rate_per_session`, `num_bookings`, `average_rating`, `status`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'jeilorelopez@gmail.com', '$2y$10$eSIqwlMBfxOAffo23dvKiuUFG/CPLHo7wrPXpDTqW3NECNm0cTKSS', 'Thomen Jeilo', 'R', 'Relopez', '2004-11-25', NULL, 'College Undergraduate', '4-6 years', NULL, NULL, 150.00, 900.00, 12, 4.08, 'For Verification', NULL, NULL, '2025-04-03 07:42:53', '2025-05-13 00:57:23'),
(5, 'jonzeph2005@gmail.COM', '$2y$10$vdals2LNTIPZ5kwCej.bj.IjBVsUirABOBbls/ELKHEl3kJXCj82u', 'Jon Zeph', 'R', 'Glodoviza', '2004-04-12', NULL, 'College Undergraduate', '1-3 years', NULL, NULL, 400.00, 1500.00, 50, 5.00, 'Verified', NULL, NULL, '2025-04-12 04:31:53', '2025-05-11 10:29:33'),
(6, 'oliviajohnson@gmail.com', '', 'Olivia', 'A', 'Johnson', '2025-04-16', NULL, 'Doctoral Degree', '7+ years', NULL, NULL, 200.00, 1000.00, 30, 2.85, 'Unverified', NULL, NULL, '2025-04-23 11:48:16', '2025-05-11 10:29:37'),
(7, 'lebronjames@gmail.com', '', 'Lebron', 'R', 'James', '2025-04-13', NULL, 'Doctoral Degree', '4-6 years', NULL, NULL, 500.00, 1500.00, 20, 1.00, 'Unverified', NULL, NULL, '2025-04-23 11:48:16', '2025-05-11 10:29:40'),
(8, 'lukadoncic@gmail.com', '', 'Luka', 'L', 'Doncic', '2025-04-05', NULL, 'Master\'s Degree', '1-3 years', NULL, NULL, 600.00, 2000.00, 150, 4.75, 'Verified', NULL, NULL, '2025-04-23 11:48:16', '2025-04-26 09:06:53'),
(9, 'tutor1@example.com', '1', 'John', 'A', 'Doe', '1990-05-15', NULL, 'Associate\'s Degree', '1-3 years', 'High School Diploma', NULL, 20.00, 100.00, 5, 4.50, 'Verified', NULL, NULL, '2025-04-26 12:39:03', '2025-04-26 12:57:37'),
(10, 'tutor2@example.com', '1', 'Jane', 'B', 'Smith', '1985-08-20', NULL, 'Master\'s Degree', '4-6 years', 'Bachelor\'s Degree', NULL, 25.00, 120.00, 8, 4.70, 'Verified', NULL, NULL, '2025-04-26 12:39:03', '2025-04-26 12:39:03'),
(11, 'tutor3@example.com', '1', 'Michael', 'C', 'Johnson', '1992-11-10', NULL, 'Bachelor\'s Degree', '1-3 years', 'Associate\'s Degree', NULL, 18.00, 90.00, 3, 4.20, 'For Verification', NULL, NULL, '2025-04-26 12:39:03', '2025-05-11 10:29:46'),
(12, 'tutor4@example.com', '1', 'Emily', 'D', 'Brown', '1988-02-25', NULL, 'Doctoral Degree', '7+ years', 'Master\'s Degree', 'Certified in Physics', 30.00, 150.00, 10, 4.80, 'Verified', NULL, NULL, '2025-04-26 12:39:03', '2025-04-26 12:39:03'),
(13, 'tutor5@example.com', '1', 'David', 'E', 'Wilson', '1995-07-14', NULL, 'Associate\'s Degree', 'Less than 1 year', NULL, 'Certified in English', 15.00, 80.00, 2, 3.90, 'For Verification', NULL, NULL, '2025-04-26 12:39:03', '2025-05-11 10:29:52'),
(14, 'tutor6@example.com', '1', 'Sarah', 'F', 'Lee', '1993-03-05', NULL, 'Senior High School Graduate', '1-3 years', 'High School Diploma', NULL, 12.00, 60.00, 4, 4.10, 'Verified', NULL, NULL, '2025-04-26 12:39:03', '2025-04-26 12:39:03'),
(15, 'tutor7@example.com', '1', 'James', 'G', 'Miller', '1982-12-18', NULL, 'Master\'s Degree', '4-6 years', 'Bachelor\'s Degree', 'Certified in Mathematics', 28.00, 130.00, 6, 4.60, 'Verified', NULL, NULL, '2025-04-26 12:39:03', '2025-04-26 12:39:03'),
(16, 'tutor8@example.com', '1', 'Olivia', 'H', 'Taylor', '1997-04-22', NULL, 'Bachelor\'s Degree', '1-3 years', 'High School Diploma', NULL, 20.00, 95.00, 7, 4.30, 'For Verification', NULL, NULL, '2025-04-26 12:39:03', '2025-05-11 10:29:56'),
(17, 'tutor9@example.com', '1', 'Lucas', 'I', 'Anderson', '1987-09-30', NULL, 'Doctoral Degree', '7+ years', 'Master\'s Degree', 'Certified in Chemistry', 35.00, 180.00, 12, 4.90, 'Verified', NULL, NULL, '2025-04-26 12:39:03', '2025-04-26 12:39:03'),
(18, 'tutor10@example.com', '1', 'Ava', 'J', 'Thomas', '1990-06-12', NULL, 'Bachelor\'s Degree', '1-3 years', 'Associate\'s Degree', 'Certified in Biology', 22.00, 110.00, 9, 4.40, 'Verified', NULL, NULL, '2025-04-26 12:39:03', '2025-04-26 12:39:03');

-- --------------------------------------------------------

--
-- Table structure for table `tutor_specializations`
--

CREATE TABLE `tutor_specializations` (
  `tutor_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_specializations`
--

INSERT INTO `tutor_specializations` (`tutor_id`, `specialization_id`) VALUES
(1, 1),
(1, 3),
(1, 5),
(1, 6),
(1, 11),
(1, 12),
(1, 14),
(5, 1),
(5, 3),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 15),
(6, 1),
(6, 5),
(6, 6),
(6, 7),
(6, 8),
(7, 1),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(8, 1),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(9, 1),
(9, 3),
(9, 6),
(9, 7),
(9, 10),
(10, 2),
(10, 4),
(10, 5),
(10, 8),
(10, 9),
(11, 1),
(11, 4),
(11, 6),
(11, 7),
(11, 10),
(12, 3),
(12, 5),
(12, 6),
(12, 8),
(12, 9),
(13, 2),
(13, 4),
(13, 6),
(13, 7),
(13, 8),
(14, 1),
(14, 3),
(14, 5),
(14, 7),
(14, 9),
(15, 1),
(15, 2),
(15, 6),
(15, 8),
(15, 10),
(16, 3),
(16, 4),
(16, 5),
(16, 8),
(16, 9),
(17, 2),
(17, 3),
(17, 6),
(17, 7),
(17, 10),
(18, 1),
(18, 4),
(18, 5),
(18, 7),
(18, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_learner` (`learner_id`),
  ADD KEY `fk_tutor_id` (`tutor_id`);

--
-- Indexes for table `learner`
--
ALTER TABLE `learner`
  ADD PRIMARY KEY (`learner_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`specialization_id`);

--
-- Indexes for table `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`tutor_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `educational_attainment` (`educational_attainment`);

--
-- Indexes for table `tutor_specializations`
--
ALTER TABLE `tutor_specializations`
  ADD PRIMARY KEY (`tutor_id`,`specialization_id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `learner`
--
ALTER TABLE `learner`
  MODIFY `learner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tutor`
--
ALTER TABLE `tutor`
  MODIFY `tutor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_learner` FOREIGN KEY (`learner_id`) REFERENCES `learner` (`learner_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tutor_id` FOREIGN KEY (`tutor_id`) REFERENCES `tutor` (`tutor_id`) ON DELETE CASCADE;

--
-- Constraints for table `tutor_specializations`
--
ALTER TABLE `tutor_specializations`
  ADD CONSTRAINT `tutor_specializations_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutor` (`tutor_id`),
  ADD CONSTRAINT `tutor_specializations_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`specialization_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

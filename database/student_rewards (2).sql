-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 06:37 PM
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
-- Database: `student_rewards`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `badge_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `earned_badges`
--

CREATE TABLE `earned_badges` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `student_id`, `token`, `expires`) VALUES
(1, 30, '6de779e9696c1e7ab3781db9e86455295d4dd31ebbcb33364369e699d32d658a12ed961f9da194ee44bb36191a676f8331c6', 1731517752),
(2, 34, 'df131930063f8540cb5ab33cb5a59e84d3344020661069c46b3360d7abd3a937bf9811e12929cb2878098192b5274cb6fc91', 1731517803),
(3, 36, 'ea4e8ca3171eee579a776933b405284df06d673c29a8a0b5c4ee270ccb74252063959fd2723a21ad9074af874488b110d52d', 1731517864),
(4, 36, '2b79331239074906f78a7ae35cba5d23671e4b93e18dd7b8fa8a3ed3de588d3b63ad3345c4425ed72debfaeb48bef7278122', 1731517897),
(5, 36, 'caf6a6c5230cf3128bb64090e360366a0c85bb569ba18acefaa4ed0ec4a286642c6d0a1ae46e32a6a24fdc0d95b4bc8ef5b1', 1731517900);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(29, 'EARN POINTS TO WIN!', 'If you can reach 20points today SAO will give a 1balloon paper', 'uploads/23.jpg', '2024-11-10 17:55:04'),
(92, 'Announcement!', 'If your reach 10 points today SAO will give 5pcs of ballpen', 'uploads/23.jpg', '2024-11-13 17:18:02');

-- --------------------------------------------------------

--
-- Table structure for table `reward_codes`
--

CREATE TABLE `reward_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `is_redeemed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward_codes`
--

INSERT INTO `reward_codes` (`id`, `code`, `is_redeemed`) VALUES
(211, 'xjPEgu', 1),
(212, 'IYIXWm', 1),
(213, 'RlOffI', 1),
(214, 'HIzhWQ', 1),
(215, 'eJeIaH', 1),
(216, 'ToAXlL', 1),
(217, '7jhFGG', 1),
(218, 'Wc6fmg', 1),
(219, 'pQn0zW', 1),
(220, 'ZQC5xJ', 1),
(221, 'fwZsvD', 1),
(222, 'zH3cqZ', 1),
(223, 'CwJFDb', 1),
(224, '2JKCtf', 1),
(225, 'l6jXlA', 1),
(226, 'VmWbZf', 1),
(227, 'iVRoQw', 1),
(228, 'YkMGaV', 0),
(229, '1Ao8Yj', 0),
(230, '6CeWO6', 0),
(231, 'HR5gK6', 0),
(232, 'oKKA1s', 0),
(233, 'T6UONw', 0),
(234, 'ETDeyo', 0),
(235, '3GBIVv', 0),
(236, '3sANAP', 0),
(237, 'M3nzzg', 0),
(238, 'yphawe', 0),
(239, 'YzfCRT', 0),
(240, 'NGJ6gP', 0),
(241, 'x9yK5K', 0),
(242, 'BUWxAy', 0),
(243, 'PuUz9L', 0),
(244, 'VSJz1P', 0),
(245, 'EkaMUr', 0),
(246, 'ySFHMt', 0),
(247, 'hfY0oJ', 0),
(248, 'd9qse5', 0),
(249, 'ZHLbSm', 0),
(250, 'pKLGAD', 0),
(251, 'UX8dfU', 0),
(252, 'iu1Dyh', 0),
(253, 'T03GdW', 0),
(254, 'ehe7P1', 0),
(255, '59s108', 0),
(256, 't4SASi', 0),
(257, 'B9T8q4', 0),
(258, '6ruzA5', 0),
(259, 'qrqFjS', 0),
(260, 'LS6j5p', 0),
(261, 'E6Ssvw', 0),
(262, 'wD95Aw', 0),
(263, 'WKJUlU', 0),
(264, 'IHnV3K', 0),
(265, 'mxdrVR', 0),
(266, 'usZIBr', 0),
(267, 'pA0GHN', 0),
(268, 'EG3tW6', 0),
(269, 'bsPTBp', 0),
(270, 'FiXYYA', 0),
(271, 'Een3WJ', 0),
(272, 'BxyZLh', 0),
(273, 'l0SLmS', 0),
(274, 'rrqt2x', 0),
(275, 'W2D898', 0),
(276, 'PFrd5B', 0),
(277, 'ucmEkD', 0),
(278, '2JYPAZ', 0),
(279, 'JtCUjE', 0),
(280, 'iTLIfE', 0),
(281, 'S6qfxF', 0),
(282, 'p8fI1d', 0),
(283, '1ZDxLa', 0),
(284, 'copgdJ', 0),
(285, 'h9rs8I', 0),
(286, '8Le5Q5', 0),
(287, 'S0il6M', 0),
(288, 'eQSSjb', 0),
(289, 'MWep7d', 0),
(290, 'PFEgNV', 0),
(291, 'pzc1Ae', 0),
(292, 'GSAp0S', 0),
(293, 'gRFo83', 0),
(294, 'MkLQ0m', 0),
(295, 'nMq5j6', 0),
(296, 'qegx85', 0),
(297, 'hKEIIL', 0),
(298, 'lnBQkD', 0),
(299, 'LMYAo0', 0),
(300, 'ui8O7E', 0),
(301, 'IqnoMP', 0),
(302, 'SrWUbC', 0),
(303, '1iMT0D', 0),
(304, 'LfhCbV', 0),
(305, 'dvU7Mk', 0),
(306, 'SW3iWm', 0),
(307, '6Il4t8', 0),
(308, 'af0yya', 0),
(309, '1DNmj4', 0),
(310, 'Gw7nFW', 0),
(311, 'pjjGna', 0),
(312, 'HTPCvz', 0),
(313, 'tWn5V6', 0),
(314, 'FRxG2r', 0),
(315, 'FfP084', 0),
(316, 'NxO9qT', 0),
(317, 'QVgnqa', 0),
(318, 'NIwIuv', 0),
(319, 'CeYiBM', 0),
(320, 'M33eAl', 0),
(321, 'vthmRn', 0),
(322, '7Hlufo', 0),
(323, 'Adcfxl', 0),
(324, 'ypu9Cc', 0),
(325, 'eCuKCo', 0),
(326, 'AtNYL0', 0),
(327, '0emYBX', 0),
(328, 'wBIXlw', 0),
(329, 'zlVXkd', 0),
(330, 'q79QWw', 0),
(331, 'LoGBoI', 0),
(332, 'FHrzrT', 0),
(333, 'kXuxmN', 0),
(334, 'cSSKx2', 0),
(335, 'iH1fLT', 0),
(336, 'EYe1Mj', 0),
(337, 'Qh2vho', 0),
(338, 'FZinsH', 0),
(339, 'gOwHUW', 0),
(340, '82wZqT', 0),
(341, 'nlz8gt', 0),
(342, 'EdFk7F', 0),
(343, 'hG3OcE', 0),
(344, 'E1hJlF', 0),
(345, 'O9xWQR', 0),
(346, 'ExC4AJ', 0),
(347, 'AR9E6B', 0),
(348, 'N4G4nS', 0),
(349, 'lMu7Kt', 0),
(350, 'RHqjHC', 0),
(351, 'XllT3m', 0),
(352, 'GZTPeE', 0),
(353, 'nBW5bh', 0),
(354, 'aIqgpE', 0),
(355, '8P393u', 0),
(356, 'nuo874', 0),
(357, 'jYJQfJ', 0),
(358, '47c18m', 0),
(359, 'fRu7Wm', 0),
(360, 'jzrUu3', 0),
(361, 'RsgmGj', 0);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `school_id_number` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 0,
  `user_type` enum('student','teacher') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `register_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved') DEFAULT 'pending',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `birthdate`, `school_id_number`, `username`, `password`, `points`, `user_type`, `profile_picture`, `created_at`, `register_date`, `status`, `registration_date`, `email`) VALUES
(27, 'francis', '2024-11-01', 'SCC-19-000234245', 'francis', '$2y$10$8ESLB27X8/.eTMzSe6H3JufmP9PdEZ66ZLnqKAe2R.kmOWK9bpDA6', 101, 'student', 'uploads/8-4-2024 9;39;27 PM.JPG', '2024-11-10 17:50:10', '2024-11-11 01:50:10', 'approved', '2024-11-10 17:50:10', ''),
(28, 'mike', '2024-11-01', 'SCC-19-0002342454', 'mike', '$2y$10$4/KssXA6NnzPrblbr1XaBuoQDDJHnKBhjGIOBrDmIAHtkQo5jpPyO', 0, 'student', 'uploads/8-4-2024 9;39;27 PM.JPG', '2024-11-10 18:26:06', '2024-11-11 02:26:06', 'approved', '2024-11-10 18:26:06', ''),
(30, 'Francis Vincent M Martinez', '2024-11-06', 'SCC-19-000234245423', 'francis2', '$2y$10$/HRmcEmlKrB/5YR1leHvUOs4nxLpoXotj48N27aEPAyIQTFeVnZ56', 3, 'student', 'uploads/8-4-2024 9;39;27 PM.JPG', '2024-11-12 12:29:20', '2024-11-12 20:29:20', 'approved', '2024-11-12 12:29:20', 'afemzmisa23@gmail.com'),
(33, 'Leonora Cantos', '2024-11-01', 'SCC-19-2342342', 'cantos', '$2y$10$h/bhBjuUDiRWpZoVBVJyJeJ3RhtMOq2aY.NomimKZlxzkvLka/Akm', 0, 'student', 'uploads/1.jpg', '2024-11-13 07:57:31', '2024-11-13 07:57:31', 'approved', '2024-11-13 07:57:31', 'cantosleonara010@gmail.com'),
(34, 'Ernnest James', '2024-11-01', 'SCC-19-234234223', 'ernest', '$2y$10$JE/w.vZdcXNOsALAvcSsE.mrCuCSqjqBPhvu0PWMDbqNlLDMzIw2.', 1, 'student', 'uploads/1.jpg', '2024-11-13 08:02:32', '2024-11-13 08:02:32', 'approved', '2024-11-13 08:02:32', 'quinesernestjames@gmail.com'),
(35, 'Angelo Rafayla', '2024-11-01', 'SCC-19-2342342233', 'angelo', '$2y$10$TNM4vZ5JMU7bqSwgLaxNlOTcJABo0O1s0BKVeR0zPpYYcU4DXa2Ii', 1, 'student', 'uploads/1.jpg', '2024-11-13 08:05:39', '2024-11-13 08:05:39', 'approved', '2024-11-13 08:05:39', 'rafaylaangelo21@gmail.com'),
(36, 'Leo Lariego', '2024-11-01', 'SCC-19-00023424856', 'leo', '$2y$10$MFcaKwt3n7Qz/4ti58bE3OD7gv1G4gsPujhG8ZQd.NqU7nNxAj.JS', 1, 'student', 'uploads/8-4-2024 9;39;27 PM.JPG', '2024-11-13 14:44:10', '2024-11-13 22:44:10', 'approved', '2024-11-13 14:44:10', 'leomangubat42@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `student_actions`
--

CREATE TABLE `student_actions` (
  `student_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `points_used` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `voucher_code` varchar(255) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `duration` int(11) DEFAULT 0,
  `duration_unit` varchar(10) DEFAULT 'minutes',
  `reward_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`voucher_code`, `time_created`, `duration`, `duration_unit`, `reward_amount`) VALUES
('SCC', '2024-11-13 15:45:02', 4, 'minutes', 0),
('SCC123', '2024-11-13 06:46:55', 2, 'hours', NULL),
('SCC1234', '2024-11-13 06:49:47', 1, 'weeks', NULL),
('SCC23', '2024-11-13 17:21:47', 1, 'minutes', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `earned_badges`
--
ALTER TABLE `earned_badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_badge` (`student_id`,`badge_name`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reward_codes`
--
ALTER TABLE `reward_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_id_number` (`school_id_number`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `student_actions`
--
ALTER TABLE `student_actions`
  ADD PRIMARY KEY (`student_id`,`action_type`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`voucher_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `earned_badges`
--
ALTER TABLE `earned_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `reward_codes`
--
ALTER TABLE `reward_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=362;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `badges`
--
ALTER TABLE `badges`
  ADD CONSTRAINT `badges_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `earned_badges`
--
ALTER TABLE `earned_badges`
  ADD CONSTRAINT `earned_badges_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_actions`
--
ALTER TABLE `student_actions`
  ADD CONSTRAINT `student_actions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

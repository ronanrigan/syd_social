-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 12:45 PM
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
-- Database: `sydney_social_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `activity_date` datetime NOT NULL,
  `capacity` int(11) NOT NULL,
  `current_slots` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `title`, `description`, `location`, `activity_date`, `capacity`, `current_slots`, `category`, `image_path`) VALUES
(1, 'Bondi to Coogee Walk', 'A beautiful coastal walk with stunning views.', 'Bondi Beach', '2026-02-15 10:00:00', 20, 19, 'Social', 'walk.jpg'),
(2, 'Sunrise Yoga', 'Start your day with peace at the Royal Botanic Garden.', 'Botanic Gardens', '2026-02-16 06:30:00', 15, 15, 'Wellness', 'yoga.png'),
(3, 'Museum of Contemporary Art Tour', 'Guided tour of the latest exhibitions.', 'The Rocks', '2026-02-17 14:00:00', 10, 9, 'Culture', 'museum.png'),
(4, 'Hurstville Festival', 'Shop, Dine and Enjoy the local market and culture', 'Hurstville', '2026-02-20 10:30:00', 100, 99, 'Culture', '1768905043_hurstville.jpg'),
(7, 'dsf', 'asdf', 'afds', '2026-01-17 22:27:00', 114, 114, 'Social', '1768908447_hero.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `activity_id`, `booking_date`) VALUES
(3, 1, 1, '2026-01-20 10:12:00'),
(4, 3, 4, '2026-01-20 10:32:50'),
(7, 7, 3, '2026-01-20 11:19:40');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Trial', 'admin@login.com', '$2y$10$rpLTVxHCdXdwxDqrKdfmY.d4/g3B7w1JnKrz0KNjBAwPmS/5RBLNS', 'admin', '2026-01-17 01:16:57'),
(3, 'Roman Karki', 'ronanrigan@gmail.com', '$2y$10$Vo3iivIU4kFDe3gwZDVHuemjoGaPH9yuu7XLshA0MCGzLoL57GLz2', 'admin', '2026-01-20 10:32:30'),
(4, 'asd', 'asd@asd.asd', '$2y$10$dZQ2yOXkSgZasVOF3tWM1.V2siO/op08326Oyjdm8LL5gtr9gTR.a', 'user', '2026-01-20 10:45:36'),
(5, 'Roman Karki', 'asf@asf.com', '$2y$10$jyz4F/gSuUn863A1BSm9ye2pZmXswmsFBgGo3ibmLHucL9csmH/ie', 'user', '2026-01-20 10:49:46'),
(6, 'asdfgh', 'dsaff@adsf.sfda', '$2y$10$1hvOObp1bTslzVpp6y2nweS38fUPNUdpuEF9mkoKMgExp.BpPQSJO', 'user', '2026-01-20 10:52:50'),
(7, 'Dat', 'dat@gmail.com', '$2y$10$jpaBjA9800/8tYMlpecsKuh.f4abanCMZAPtAEeZxbanNcXQIwYga', 'user', '2026-01-20 11:07:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

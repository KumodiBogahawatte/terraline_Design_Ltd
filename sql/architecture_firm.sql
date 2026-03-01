-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2026 at 06:41 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `architecture_firm`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Residential', 'residential', '2026-02-27 14:05:33'),
(2, 'Commercial', 'commercial', '2026-02-27 14:05:33'),
(3, 'Interior', 'interior', '2026-02-27 14:05:33'),
(4, 'Landscape', 'landscape', '2026-02-27 14:05:33');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `message`, `is_read`, `created_at`) VALUES
(1, 'Kumodi Bogahawatte', 'kumodib@gmail.com', 'hi', 1, '2026-02-28 08:56:03'),
(3, 'Kumodi Bogahawatte', 'kumodib@gmail.com', 'hi', 1, '2026-02-28 08:56:05'),
(5, 'Kumodi Bogahawatte', 'kumodib@gmail.com', 'hello', 0, '2026-02-28 13:49:17'),
(6, 'Kumodi Bogahawatte', 'kumodib@gmail.com', 'hello', 0, '2026-02-28 13:49:55');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `client` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `featured_img` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `slug`, `category_id`, `location`, `year`, `area`, `client`, `description`, `featured_img`, `created_at`, `is_featured`) VALUES
(1, 'Crystal Waters Villa', 'crystal-waters-villa', 1, 'Malibu, California', 2023, '8500 sq ft', 'Private Client', 'A stunning oceanfront residence that seamlessly integrates with its natural surroundings...', 'crystal-waters.jpg', '2026-02-27 14:05:33', 1),
(2, 'Horizon Tower', 'horizon-tower', 2, 'New York City', 2022, '250000 sq ft', 'Metro Development', 'A landmark commercial tower redefining the city skyline with sustainable design...', 'horizon-tower.jpg', '2026-02-27 14:05:33', 1),
(3, 'Minimalist Loft', 'minimalist-loft', 3, 'Tokyo, Japan', 2023, '2200 sq ft', 'Design Collective', 'An exploration of space, light, and materiality in urban living...', 'minimalist-loft.jpg', '2026-02-27 14:05:33', 1),
(4, 'Coastal Garden', 'coastal-garden', 4, 'Santorini, Greece', 2022, '15000 sq ft', 'Luxury Resorts', 'Landscape architecture that celebrates the Mediterranean coastline...', 'coastal-garden.jpg', '2026-02-27 14:05:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_images`
--

CREATE TABLE `project_images` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_images`
--

INSERT INTO `project_images` (`id`, `project_id`, `img_url`, `caption`, `sort_order`) VALUES
(4, 2, 'horizon-tower-1.jpg', 'Building exterior', 1),
(5, 2, 'horizon-tower-2.jpg', 'Lobby design', 2),
(8, 4, 'coastal-garden-1.jpg', 'Garden pathway', 1),
(10, 1, '69a28ba8bd0e8_1772260264.jpg', '', 2),
(11, 3, '69a2ef530c1dc_1772285779.jpg', '', 0),
(12, 3, '69a2ef530cdb3_1772285779.jpg', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `role`, `bio`, `image`, `sort_order`, `created_at`) VALUES
(1, 'John Smith', 'Principal Architect', 'With over 20 years of experience in luxury residential architecture.', '69a2a9c0c219e_1772267968.jpg', 1, '2026-02-27 14:05:33'),
(2, 'Sarah Chen', 'Senior Designer', 'Sarah brings innovative design solutions with a focus on sustainable luxury...', NULL, 2, '2026-02-27 14:05:33'),
(3, 'Michael Roberts', 'Project Manager', 'Michael ensures every project meets the highest standards of excellence...', NULL, 3, '2026-02-27 14:05:33'),
(4, 'Elena Rodriguez', 'Interior Architect', 'Specializing in creating harmonious interior spaces that blend with architecture...', NULL, 4, '2026-02-27 14:05:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','editor') DEFAULT 'editor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin@architecture.com', '$2y$10$IR0bvQBfzKOslPEcea4Hf.jd.CEFVoSuwUQSPDjaT89EZK94RtQAG', 'admin', '2026-02-27 14:05:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `project_images`
--
ALTER TABLE `project_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_images`
--
ALTER TABLE `project_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_images`
--
ALTER TABLE `project_images`
  ADD CONSTRAINT `project_images_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

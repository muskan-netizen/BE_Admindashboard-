-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: royoorders-2-db-staging-cluster.cvgfslznkneq.us-west-2.rds.amazonaws.com
-- Generation Time: Dec 21, 2021 at 06:05 AM
-- Server version: 8.0.23
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `royo_movingwheelsdelivery`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addon_options`
--

CREATE TABLE `addon_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addon_id` bigint UNSIGNED NOT NULL,
  `position` smallint NOT NULL DEFAULT '1',
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addon_options`
--

INSERT INTO `addon_options` (`id`, `title`, `addon_id`, `position`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, '100.00', NULL, NULL),
(2, '1 Bedroom', 2, 1, '10.00', '2021-10-04 20:53:54', '2021-10-04 20:53:54'),
(3, '2 Bedrooms', 2, 2, '12.00', '2021-10-04 20:53:54', '2021-10-04 20:53:54'),
(4, '3 Bedrooms', 2, 3, '13.00', '2021-10-04 20:53:54', '2021-10-04 20:53:54'),
(5, 'Interior Clean Up', 3, 1, '12.00', '2021-10-04 20:54:22', '2021-10-04 20:54:22'),
(6, 'Exterior Clean up', 3, 2, '14.00', '2021-10-04 20:54:22', '2021-10-04 20:54:22'),
(7, '1 Bathroom', 4, 1, '8.00', '2021-10-04 20:54:50', '2021-10-04 20:54:50'),
(8, '2 Bathrooms', 4, 2, '10.00', '2021-10-04 20:54:50', '2021-10-04 20:54:50'),
(9, 'medium', 5, 1, '7.00', '2021-10-11 09:21:30', '2021-10-11 09:21:30'),
(10, 'large', 5, 2, '14.00', '2021-10-11 09:21:30', '2021-10-11 09:21:30'),
(11, 'Short', 6, 1, '10.00', '2021-10-11 09:21:58', '2021-10-11 09:21:58'),
(12, 'Long', 6, 2, '15.00', '2021-10-11 09:21:58', '2021-10-11 09:21:58'),
(13, 'medium', 7, 1, '8.00', '2021-10-11 09:22:25', '2021-10-11 09:22:25'),
(14, 'large', 7, 2, '16.00', '2021-10-11 09:22:25', '2021-10-11 09:22:25'),
(15, 'medium', 8, 1, '7.00', '2021-10-11 09:22:54', '2021-10-11 09:22:54'),
(16, 'large', 8, 2, '14.00', '2021-10-11 09:22:54', '2021-10-11 09:22:54'),
(17, 'xl', 9, 1, '7.00', '2021-10-11 09:23:37', '2021-10-11 09:23:37'),
(18, 'xxl', 9, 2, '15.00', '2021-10-11 09:23:37', '2021-10-11 09:23:37'),
(19, 'xl', 10, 1, '8.00', '2021-10-11 09:24:23', '2021-10-11 09:24:23'),
(20, 'xxl', 10, 2, '16.00', '2021-10-11 09:24:23', '2021-10-11 09:24:23'),
(21, 'xl', 11, 1, '7.00', '2021-10-11 09:25:19', '2021-10-11 09:25:19'),
(22, 'xxl', 11, 2, '15.00', '2021-10-11 09:25:19', '2021-10-11 09:25:19'),
(23, 'xl', 12, 1, '5.00', '2021-10-11 09:25:49', '2021-10-11 09:25:49'),
(24, 'xxl', 12, 2, '10.00', '2021-10-11 09:25:49', '2021-10-11 09:25:49'),
(25, 'xl', 13, 1, '5.00', '2021-10-11 09:26:35', '2021-10-11 09:26:35'),
(26, 'xxl', 13, 2, '10.00', '2021-10-11 09:26:35', '2021-10-11 09:26:35'),
(27, 'xl', 14, 1, '7.00', '2021-10-11 09:27:25', '2021-10-11 09:27:25'),
(28, 'xxl', 14, 2, '14.00', '2021-10-11 09:27:25', '2021-10-11 09:27:25'),
(29, 'Half', 15, 1, '7.00', '2021-10-11 09:27:59', '2021-10-11 09:27:59'),
(30, 'full', 15, 2, '10.00', '2021-10-11 09:27:59', '2021-10-11 09:27:59'),
(31, 'xl', 16, 1, '7.00', '2021-10-11 09:28:29', '2021-10-11 09:28:29'),
(32, 'xxl', 16, 2, '14.00', '2021-10-11 09:28:29', '2021-10-11 09:28:29'),
(33, '8 gb', 17, 1, '40.00', '2021-10-11 09:37:27', '2021-10-11 09:41:43'),
(34, '12 gb', 17, 2, '45.00', '2021-10-11 09:37:27', '2021-10-11 09:41:43'),
(35, '16 inch screen', 18, 1, '45.00', '2021-10-11 09:39:11', '2021-10-11 09:41:58'),
(36, '14 inch screen', 18, 2, '50.00', '2021-10-11 09:39:11', '2021-10-11 09:41:58'),
(37, '8 GB RAM', 19, 1, '50.00', '2021-10-11 09:41:13', '2021-10-11 09:41:13'),
(38, '16 GB RAM', 19, 2, '55.00', '2021-10-11 09:41:13', '2021-10-11 09:41:13'),
(39, '12gb RAM', 20, 1, '52.00', '2021-10-11 09:43:03', '2021-10-11 09:43:03'),
(40, '16gb RAM', 20, 2, '55.00', '2021-10-11 09:43:03', '2021-10-11 09:43:03'),
(41, '8gb', 21, 1, '45.00', '2021-10-11 09:45:03', '2021-10-11 09:45:03'),
(42, '16gb', 21, 2, '50.00', '2021-10-11 09:45:03', '2021-10-11 09:45:03'),
(43, '8gb', 22, 1, '46.00', '2021-10-11 09:46:29', '2021-10-11 09:46:47'),
(44, '16gb', 22, 2, '50.00', '2021-10-11 09:46:29', '2021-10-11 09:46:47'),
(45, '8 GB', 23, 1, '20.00', '2021-10-11 09:48:06', '2021-10-11 09:48:06'),
(46, '16 GB', 23, 2, '25.00', '2021-10-11 09:48:06', '2021-10-11 09:48:06'),
(47, '8gb', 24, 1, '20.00', '2021-10-11 09:50:12', '2021-10-11 09:50:12'),
(48, '16gb', 24, 2, '23.00', '2021-10-11 09:50:12', '2021-10-11 09:50:12'),
(49, '8gb', 25, 1, '19.00', '2021-10-11 09:51:00', '2021-10-11 09:51:00'),
(50, '16gb', 25, 2, '23.00', '2021-10-11 09:51:00', '2021-10-11 09:51:00'),
(51, '8gb', 26, 1, '23.00', '2021-10-11 09:51:50', '2021-10-11 09:51:50'),
(52, '16gb', 26, 2, '25.00', '2021-10-11 09:51:50', '2021-10-11 09:51:50'),
(53, '8gb', 27, 1, '16.00', '2021-10-11 09:52:22', '2021-10-11 09:52:22'),
(54, '16gb', 27, 2, '20.00', '2021-10-11 09:52:22', '2021-10-11 09:52:22'),
(55, '8gb', 28, 1, '18.00', '2021-10-11 09:52:56', '2021-10-11 09:52:56'),
(56, '16gb', 28, 2, '20.00', '2021-10-11 09:52:56', '2021-10-11 09:52:56'),
(57, '8gb', 29, 1, '12.00', '2021-10-11 09:54:07', '2021-10-11 09:54:07'),
(58, '16gb', 29, 2, '20.00', '2021-10-11 09:54:07', '2021-10-11 09:54:07'),
(59, '1', 30, 1, '10.00', '2021-10-11 09:57:12', '2021-10-11 09:57:12'),
(60, '2', 30, 2, '20.00', '2021-10-11 09:57:12', '2021-10-11 09:57:12'),
(61, '1', 31, 1, '11.00', '2021-10-11 09:57:47', '2021-10-11 09:57:47'),
(62, '2', 31, 2, '21.00', '2021-10-11 09:57:47', '2021-10-11 09:57:47'),
(63, '1', 32, 1, '16.00', '2021-10-11 09:58:20', '2021-10-11 09:58:20'),
(64, '2', 32, 2, '32.00', '2021-10-11 09:58:20', '2021-10-11 09:58:20'),
(65, '1', 33, 1, '15.00', '2021-10-11 09:58:47', '2021-10-11 09:58:47'),
(66, '2', 33, 2, '30.00', '2021-10-11 09:58:47', '2021-10-11 09:58:47'),
(67, '1', 34, 1, '14.00', '2021-10-11 10:00:14', '2021-10-11 10:00:52'),
(68, '2', 34, 2, '28.00', '2021-10-11 10:00:14', '2021-10-11 10:00:52'),
(69, '1', 35, 1, '18.00', '2021-10-11 10:01:42', '2021-10-11 10:01:42'),
(70, '2', 35, 2, '36.00', '2021-10-11 10:01:42', '2021-10-11 10:01:42'),
(71, '1', 36, 1, '16.00', '2021-10-11 10:02:29', '2021-10-11 10:02:29'),
(72, '2', 36, 2, '32.00', '2021-10-11 10:02:29', '2021-10-11 10:02:29'),
(73, '1', 37, 1, '14.00', '2021-10-11 10:02:55', '2021-10-11 10:02:55'),
(74, '2', 37, 2, '28.00', '2021-10-11 10:02:55', '2021-10-11 10:02:55'),
(75, '1', 38, 1, '13.00', '2021-10-11 10:03:31', '2021-10-11 10:03:31'),
(76, '2', 38, 2, '26.00', '2021-10-11 10:03:31', '2021-10-11 10:03:31'),
(77, '1', 39, 1, '12.00', '2021-10-11 10:04:24', '2021-10-11 10:04:24'),
(78, '2', 39, 2, '24.00', '2021-10-11 10:04:24', '2021-10-11 10:04:24'),
(79, '1', 40, 1, '12.00', '2021-10-11 10:04:25', '2021-10-11 10:04:25'),
(80, '2', 40, 2, '24.00', '2021-10-11 10:04:25', '2021-10-11 10:04:25'),
(81, '1', 41, 1, '12.00', '2021-10-11 10:05:18', '2021-10-11 10:05:18'),
(82, '2', 41, 2, '24.00', '2021-10-11 10:05:18', '2021-10-11 10:05:18'),
(83, '2Kg', 42, 1, '12.00', '2021-10-11 10:07:30', '2021-10-11 10:07:30'),
(84, '3Kg', 42, 2, '18.00', '2021-10-11 10:07:30', '2021-10-11 10:07:30'),
(85, '2Kg', 43, 1, '16.00', '2021-10-11 10:08:04', '2021-10-11 10:08:04'),
(86, '3Kg', 43, 2, '24.00', '2021-10-11 10:08:04', '2021-10-11 10:08:04'),
(87, '1Kg', 44, 1, '14.00', '2021-10-11 10:08:42', '2021-10-11 10:08:42'),
(88, '2Kg', 44, 2, '21.00', '2021-10-11 10:08:42', '2021-10-11 10:08:42'),
(89, '2Kg', 45, 1, '20.00', '2021-10-11 10:09:29', '2021-10-11 10:09:29'),
(90, '3Kg', 45, 2, '30.00', '2021-10-11 10:09:30', '2021-10-11 10:09:30'),
(91, '2Kg', 46, 1, '24.00', '2021-10-11 10:10:35', '2021-10-11 10:10:35'),
(92, '3Kg', 46, 2, '36.00', '2021-10-11 10:10:35', '2021-10-11 10:10:35'),
(93, '2Kg', 47, 1, '18.00', '2021-10-11 10:11:10', '2021-10-11 10:11:10'),
(94, '3Kg', 47, 2, '27.00', '2021-10-11 10:11:10', '2021-10-11 10:11:10'),
(95, '1kg', 48, 1, '14.00', '2021-10-11 10:12:12', '2021-10-11 10:13:20'),
(96, '2kg', 48, 2, '21.00', '2021-10-11 10:12:12', '2021-10-11 10:13:20'),
(97, '1Kg', 49, 1, '32.00', '2021-10-11 10:14:24', '2021-10-11 10:14:24'),
(98, '2Kg', 49, 2, '40.00', '2021-10-11 10:14:24', '2021-10-11 10:14:24'),
(99, '12', 50, 1, '11.00', '2021-10-11 10:15:04', '2021-10-11 10:15:04'),
(100, '24', 50, 2, '21.00', '2021-10-11 10:15:04', '2021-10-11 10:15:04'),
(101, '2Kg', 51, 1, '24.00', '2021-10-11 10:15:46', '2021-10-11 10:15:46'),
(102, '3Kg', 51, 2, '36.00', '2021-10-11 10:15:46', '2021-10-11 10:15:46'),
(103, '2Kg', 52, 1, '24.00', '2021-10-11 10:16:50', '2021-10-11 10:16:50'),
(104, '3Kg', 52, 2, '36.00', '2021-10-11 10:16:50', '2021-10-11 10:16:50'),
(105, '2Kg', 53, 1, '24.00', '2021-10-11 10:17:26', '2021-10-11 10:17:26'),
(106, '3Kg', 53, 2, '30.00', '2021-10-11 10:17:26', '2021-10-11 10:17:26'),
(107, '2Kg', 54, 1, '30.00', '2021-10-11 10:18:02', '2021-10-11 10:18:02'),
(108, '3Kg', 54, 2, '35.00', '2021-10-11 10:18:02', '2021-10-11 10:18:02'),
(109, '2', 55, 1, '28.00', '2021-10-11 10:18:59', '2021-10-11 10:19:22'),
(110, '3', 55, 2, '31.00', '2021-10-11 10:18:59', '2021-10-11 10:19:22'),
(111, '2', 56, 1, '25.00', '2021-10-11 10:20:22', '2021-10-11 10:20:22'),
(112, '3', 56, 2, '30.00', '2021-10-11 10:20:22', '2021-10-11 10:20:22'),
(113, 'Half', 57, 1, '9.00', '2021-10-11 10:23:29', '2021-10-11 10:23:29'),
(114, 'Full', 57, 2, '18.00', '2021-10-11 10:23:29', '2021-10-11 10:23:29'),
(115, 'Half', 58, 1, '10.00', '2021-10-11 10:26:19', '2021-10-11 10:26:19'),
(116, 'Full', 58, 2, '20.00', '2021-10-11 10:26:19', '2021-10-11 10:26:19'),
(117, 'Half', 59, 1, '14.00', '2021-10-11 10:26:47', '2021-10-11 10:26:47'),
(118, 'Full', 59, 2, '28.00', '2021-10-11 10:26:47', '2021-10-11 10:26:47'),
(119, 'Half', 60, 1, '13.00', '2021-10-11 10:27:32', '2021-10-11 10:27:32'),
(120, 'Full', 60, 2, '26.00', '2021-10-11 10:27:32', '2021-10-11 10:27:32'),
(121, 'Half', 61, 1, '12.00', '2021-10-11 10:28:28', '2021-10-11 10:28:28'),
(122, 'Full', 61, 2, '24.00', '2021-10-11 10:28:28', '2021-10-11 10:28:28'),
(123, 'Half', 62, 1, '12.00', '2021-10-11 10:29:07', '2021-10-11 10:29:07'),
(124, 'Full', 62, 2, '24.00', '2021-10-11 10:29:07', '2021-10-11 10:29:07'),
(125, 'medium', 63, 1, '9.00', '2021-10-11 10:30:10', '2021-10-11 10:30:10'),
(126, 'Large', 63, 2, '18.00', '2021-10-11 10:30:10', '2021-10-11 10:30:10'),
(127, 'Half', 64, 1, '8.00', '2021-10-11 10:30:42', '2021-10-11 10:30:42'),
(128, 'Full', 64, 2, '16.00', '2021-10-11 10:30:42', '2021-10-11 10:30:42'),
(129, 'Half', 65, 1, '12.00', '2021-10-11 10:31:25', '2021-10-11 10:31:25'),
(130, 'Full', 65, 2, '24.00', '2021-10-11 10:31:25', '2021-10-11 10:31:25'),
(131, 'Half', 66, 1, '11.00', '2021-10-11 10:32:11', '2021-10-11 10:32:11'),
(132, 'Full', 66, 2, '21.00', '2021-10-11 10:32:11', '2021-10-11 10:32:11'),
(133, 'Half', 67, 1, '8.00', '2021-10-11 10:32:53', '2021-10-11 10:32:53'),
(134, 'Full', 67, 2, '16.00', '2021-10-11 10:32:53', '2021-10-11 10:32:53'),
(135, 'Half', 68, 1, '10.00', '2021-10-11 10:33:27', '2021-10-11 10:33:27'),
(136, 'Full', 68, 2, '20.00', '2021-10-11 10:33:27', '2021-10-11 10:33:27'),
(137, 'Half', 69, 1, '7.00', '2021-10-11 10:34:13', '2021-10-11 10:34:13'),
(138, 'Full', 69, 2, '14.00', '2021-10-11 10:34:13', '2021-10-11 10:34:13'),
(139, 'Half', 70, 1, '12.00', '2021-10-11 10:34:58', '2021-10-11 10:34:58'),
(140, 'Full', 70, 2, '24.00', '2021-10-11 10:34:58', '2021-10-11 10:34:58'),
(141, 'Half', 71, 1, '17.00', '2021-10-11 10:35:41', '2021-10-11 10:35:41'),
(142, 'Full', 71, 2, '24.00', '2021-10-11 10:35:41', '2021-10-11 10:35:41'),
(143, 'Half', 72, 1, '15.00', '2021-10-11 10:36:51', '2021-10-11 10:36:51'),
(144, 'Full', 72, 2, '30.00', '2021-10-11 10:36:51', '2021-10-11 10:36:51'),
(145, 'Half', 73, 1, '12.00', '2021-10-11 10:37:12', '2021-10-11 10:37:12'),
(146, 'Full', 73, 2, '24.00', '2021-10-11 10:37:12', '2021-10-11 10:37:12'),
(147, 'Half', 74, 1, '17.00', '2021-10-11 10:38:05', '2021-10-11 10:38:05'),
(148, 'Full', 74, 2, '25.00', '2021-10-11 10:38:05', '2021-10-11 10:38:05'),
(149, '8gb', 75, 1, '23.00', '2021-10-11 10:49:14', '2021-10-11 10:49:14'),
(150, '16gb', 75, 2, '30.00', '2021-10-11 10:49:14', '2021-10-11 10:49:14');

-- --------------------------------------------------------

--
-- Table structure for table `addon_option_translations`
--

CREATE TABLE `addon_option_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addon_opt_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addon_option_translations`
--

INSERT INTO `addon_option_translations` (`id`, `title`, `addon_opt_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, NULL, '2021-10-06 09:46:51'),
(2, '1 Bedroom', 2, 1, NULL, '2021-10-06 09:46:51'),
(3, '2 Bedrooms', 3, 1, NULL, '2021-10-06 09:46:51'),
(4, '3 Bedrooms', 4, 1, NULL, '2021-10-06 09:46:51'),
(5, 'Interior Clean Up', 5, 1, NULL, '2021-10-06 09:46:51'),
(6, 'Exterior Clean up', 6, 1, NULL, '2021-10-06 09:46:51'),
(7, '1 Bathroom', 7, 1, NULL, '2021-10-06 09:46:51'),
(8, '2 Bathrooms', 8, 1, NULL, '2021-10-06 09:46:51'),
(9, 'medium', 9, 1, NULL, NULL),
(10, 'large', 10, 1, NULL, NULL),
(11, 'Short', 11, 1, NULL, NULL),
(12, 'Long', 12, 1, NULL, NULL),
(13, 'medium', 13, 1, NULL, NULL),
(14, 'large', 14, 1, NULL, NULL),
(15, 'medium', 15, 1, NULL, NULL),
(16, 'large', 16, 1, NULL, NULL),
(17, 'xl', 17, 1, NULL, NULL),
(18, 'xxl', 18, 1, NULL, NULL),
(19, 'xl', 19, 1, NULL, NULL),
(20, 'xxl', 20, 1, NULL, NULL),
(21, 'xl', 21, 1, NULL, NULL),
(22, 'xxl', 22, 1, NULL, NULL),
(23, 'xl', 23, 1, NULL, NULL),
(24, 'xxl', 24, 1, NULL, NULL),
(25, 'xl', 25, 1, NULL, NULL),
(26, 'xxl', 26, 1, NULL, NULL),
(27, 'xl', 27, 1, NULL, NULL),
(28, 'xxl', 28, 1, NULL, NULL),
(29, 'Half', 29, 1, NULL, NULL),
(30, 'full', 30, 1, NULL, NULL),
(31, 'xl', 31, 1, NULL, NULL),
(32, 'xxl', 32, 1, NULL, NULL),
(33, '8 gb', 33, 1, NULL, NULL),
(34, '12 gb', 34, 1, NULL, NULL),
(35, '16 inch screen', 35, 1, NULL, NULL),
(36, '14 inch screen', 36, 1, NULL, NULL),
(37, '8 GB RAM', 37, 1, NULL, NULL),
(38, '16 GB RAM', 38, 1, NULL, NULL),
(39, '12gb RAM', 39, 1, NULL, NULL),
(40, '16gb RAM', 40, 1, NULL, NULL),
(41, '8gb', 41, 1, NULL, NULL),
(42, '16gb', 42, 1, NULL, NULL),
(43, '8gb', 43, 1, NULL, NULL),
(44, '16gb', 44, 1, NULL, NULL),
(45, '8 GB', 45, 1, NULL, NULL),
(46, '16 GB', 46, 1, NULL, NULL),
(47, '8gb', 47, 1, NULL, NULL),
(48, '16gb', 48, 1, NULL, NULL),
(49, '8gb', 49, 1, NULL, NULL),
(50, '16gb', 50, 1, NULL, NULL),
(51, '8gb', 51, 1, NULL, NULL),
(52, '16gb', 52, 1, NULL, NULL),
(53, '8gb', 53, 1, NULL, NULL),
(54, '16gb', 54, 1, NULL, NULL),
(55, '8gb', 55, 1, NULL, NULL),
(56, '16gb', 56, 1, NULL, NULL),
(57, '8gb', 57, 1, NULL, NULL),
(58, '16gb', 58, 1, NULL, NULL),
(59, '1', 59, 1, NULL, NULL),
(60, '2', 60, 1, NULL, NULL),
(61, '1', 61, 1, NULL, NULL),
(62, '2', 62, 1, NULL, NULL),
(63, '1', 63, 1, NULL, NULL),
(64, '2', 64, 1, NULL, NULL),
(65, '1', 65, 1, NULL, NULL),
(66, '2', 66, 1, NULL, NULL),
(67, '1', 67, 1, NULL, NULL),
(68, '2', 68, 1, NULL, NULL),
(69, '1', 69, 1, NULL, NULL),
(70, '2', 70, 1, NULL, NULL),
(71, '1', 71, 1, NULL, NULL),
(72, '2', 72, 1, NULL, NULL),
(73, '1', 73, 1, NULL, NULL),
(74, '2', 74, 1, NULL, NULL),
(75, '1', 75, 1, NULL, NULL),
(76, '2', 76, 1, NULL, NULL),
(77, '1', 77, 1, NULL, NULL),
(78, '2', 78, 1, NULL, NULL),
(79, '1', 79, 1, NULL, NULL),
(80, '2', 80, 1, NULL, NULL),
(81, '1', 81, 1, NULL, NULL),
(82, '2', 82, 1, NULL, NULL),
(83, '2Kg', 83, 1, NULL, NULL),
(84, '3Kg', 84, 1, NULL, NULL),
(85, '2Kg', 85, 1, NULL, NULL),
(86, '3Kg', 86, 1, NULL, NULL),
(87, '1Kg', 87, 1, NULL, NULL),
(88, '2Kg', 88, 1, NULL, NULL),
(89, '2Kg', 89, 1, NULL, NULL),
(90, '3Kg', 90, 1, NULL, NULL),
(91, '2Kg', 91, 1, NULL, NULL),
(92, '3Kg', 92, 1, NULL, NULL),
(93, '2Kg', 93, 1, NULL, NULL),
(94, '3Kg', 94, 1, NULL, NULL),
(95, '1kg', 95, 1, NULL, '2021-10-11 10:13:20'),
(96, '2kg', 96, 1, NULL, '2021-10-11 10:13:20'),
(97, '1Kg', 97, 1, NULL, NULL),
(98, '2Kg', 98, 1, NULL, NULL),
(99, '12', 99, 1, NULL, NULL),
(100, '24', 100, 1, NULL, NULL),
(101, '2Kg', 101, 1, NULL, NULL),
(102, '3Kg', 102, 1, NULL, NULL),
(103, '2Kg', 103, 1, NULL, NULL),
(104, '3Kg', 104, 1, NULL, NULL),
(105, '2Kg', 105, 1, NULL, NULL),
(106, '3Kg', 106, 1, NULL, NULL),
(107, '2Kg', 107, 1, NULL, NULL),
(108, '3Kg', 108, 1, NULL, NULL),
(109, '2', 109, 1, NULL, '2021-10-11 10:19:22'),
(110, '3', 110, 1, NULL, '2021-10-11 10:19:22'),
(111, '2', 111, 1, NULL, NULL),
(112, '3', 112, 1, NULL, NULL),
(113, 'Half', 113, 1, NULL, NULL),
(114, 'Full', 114, 1, NULL, NULL),
(115, 'Half', 115, 1, NULL, NULL),
(116, 'Full', 116, 1, NULL, NULL),
(117, 'Half', 117, 1, NULL, NULL),
(118, 'Full', 118, 1, NULL, NULL),
(119, 'Half', 119, 1, NULL, NULL),
(120, 'Full', 120, 1, NULL, NULL),
(121, 'Half', 121, 1, NULL, NULL),
(122, 'Full', 122, 1, NULL, NULL),
(123, 'Half', 123, 1, NULL, NULL),
(124, 'Full', 124, 1, NULL, NULL),
(125, 'medium', 125, 1, NULL, NULL),
(126, 'Large', 126, 1, NULL, NULL),
(127, 'Half', 127, 1, NULL, NULL),
(128, 'Full', 128, 1, NULL, NULL),
(129, 'Half', 129, 1, NULL, NULL),
(130, 'Full', 130, 1, NULL, NULL),
(131, 'Half', 131, 1, NULL, NULL),
(132, 'Full', 132, 1, NULL, NULL),
(133, 'Half', 133, 1, NULL, NULL),
(134, 'Full', 134, 1, NULL, NULL),
(135, 'Half', 135, 1, NULL, NULL),
(136, 'Full', 136, 1, NULL, NULL),
(137, 'Half', 137, 1, NULL, NULL),
(138, 'Full', 138, 1, NULL, NULL),
(139, 'Half', 139, 1, NULL, NULL),
(140, 'Full', 140, 1, NULL, NULL),
(141, 'Half', 141, 1, NULL, NULL),
(142, 'Full', 142, 1, NULL, NULL),
(143, 'Half', 143, 1, NULL, NULL),
(144, 'Full', 144, 1, NULL, NULL),
(145, 'Half', 145, 1, NULL, NULL),
(146, 'Full', 146, 1, NULL, NULL),
(147, 'Half', 147, 1, NULL, NULL),
(148, 'Full', 148, 1, NULL, NULL),
(149, '8gb', 149, 1, NULL, NULL),
(150, '16gb', 150, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `addon_sets`
--

CREATE TABLE `addon_sets` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_select` tinyint NOT NULL DEFAULT '1',
  `max_select` tinyint NOT NULL DEFAULT '1',
  `position` smallint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `is_core` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addon_sets`
--

INSERT INTO `addon_sets` (`id`, `title`, `min_select`, `max_select`, `position`, `status`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, 1, 1, 1, NULL, NULL, NULL),
(2, 'Number of bedrooms?', 1, 1, 1, 1, 1, 9, '2021-10-04 20:53:53', '2021-10-04 20:53:53'),
(3, 'What kind of car services you require ?', 1, 1, 1, 1, 1, 9, '2021-10-04 20:54:22', '2021-10-04 20:54:22'),
(4, 'Number of Bathrooms ?', 1, 1, 1, 1, 1, 9, '2021-10-04 20:54:50', '2021-10-04 20:54:50'),
(5, 'Jackets', 1, 1, 1, 1, 1, 8, '2021-10-11 09:21:30', '2021-10-11 09:21:30'),
(6, 'Coats', 1, 1, 1, 1, 1, 8, '2021-10-11 09:21:58', '2021-10-11 09:21:58'),
(7, 'Denim', 1, 1, 1, 1, 1, 8, '2021-10-11 09:22:25', '2021-10-11 09:22:25'),
(8, 'Fluffy jackets', 1, 1, 1, 1, 1, 8, '2021-10-11 09:22:54', '2021-10-11 09:22:54'),
(9, 'partywear', 1, 1, 1, 1, 1, 8, '2021-10-11 09:23:37', '2021-10-11 09:23:37'),
(10, 'Printed dress', 1, 1, 1, 1, 1, 8, '2021-10-11 09:24:23', '2021-10-11 09:24:23'),
(11, 'Shirt', 1, 1, 1, 1, 1, 8, '2021-10-11 09:25:19', '2021-10-11 09:25:19'),
(12, 'causal t shirt', 1, 1, 1, 1, 1, 8, '2021-10-11 09:25:49', '2021-10-11 09:25:49'),
(13, 'caprii', 1, 1, 1, 1, 1, 8, '2021-10-11 09:26:35', '2021-10-11 09:26:35'),
(14, 'short', 1, 1, 1, 1, 1, 8, '2021-10-11 09:27:25', '2021-10-11 09:27:38'),
(15, 'scirt', 1, 1, 1, 1, 1, 8, '2021-10-11 09:27:59', '2021-10-11 09:27:59'),
(16, 'sweaters', 1, 1, 1, 1, 1, 8, '2021-10-11 09:28:29', '2021-10-11 09:28:29'),
(17, 'Asus Zenbook 14', 1, 1, 1, 1, 1, 7, '2021-10-11 09:37:27', '2021-10-11 09:37:27'),
(18, 'HP Pavilion 15', 1, 1, 1, 1, 1, 7, '2021-10-11 09:39:11', '2021-10-11 09:39:11'),
(19, 'Macbook Pro M1 Chip', 1, 1, 1, 1, 1, 7, '2021-10-11 09:41:13', '2021-10-11 09:41:13'),
(20, 'Iphone 12 Pro Max', 1, 1, 1, 1, 1, 7, '2021-10-11 09:43:03', '2021-10-11 09:43:03'),
(21, 'Oneplus 9Pro', 1, 1, 1, 1, 1, 7, '2021-10-11 09:45:03', '2021-10-11 09:45:03'),
(22, 'Samsung Note 20 Ultra', 1, 1, 1, 1, 1, 7, '2021-10-11 09:46:29', '2021-10-11 09:46:29'),
(23, 'Inspiron 14 Laptop', 1, 1, 1, 2, 1, 8, '2021-10-11 09:48:06', '2021-10-11 09:49:06'),
(24, 'Inspiron14Laptop', 1, 1, 1, 1, 1, 7, '2021-10-11 09:50:12', '2021-10-11 09:50:12'),
(25, 'Lenovo IdeaPad Slim', 1, 1, 1, 1, 1, 7, '2021-10-11 09:51:00', '2021-10-11 09:51:00'),
(26, 'Acer Nitro 5 Gaming Laptop', 1, 1, 1, 1, 1, 7, '2021-10-11 09:51:50', '2021-10-11 09:51:50'),
(27, 'OnePlus 9R 5G', 1, 1, 1, 1, 1, 7, '2021-10-11 09:52:22', '2021-10-11 09:52:22'),
(28, 'Samsung Galaxy S20 FE 5G', 1, 1, 1, 1, 1, 7, '2021-10-11 09:52:56', '2021-10-11 09:52:56'),
(29, 'OPPO F19 Pro', 1, 1, 1, 1, 1, 7, '2021-10-11 09:54:07', '2021-10-11 09:54:07'),
(30, 'Vitamin C + Zinc', 1, 1, 1, 1, 1, 6, '2021-10-11 09:57:12', '2021-10-11 09:57:12'),
(31, 'Folic B9', 1, 1, 1, 1, 1, 6, '2021-10-11 09:57:47', '2021-10-11 09:57:47'),
(32, 'Casein Protein', 1, 1, 1, 1, 1, 6, '2021-10-11 09:58:20', '2021-10-11 09:58:20'),
(33, 'Thermometer', 1, 1, 1, 1, 1, 6, '2021-10-11 09:58:47', '2021-10-11 09:58:47'),
(34, 'Pulse Oximeter', 1, 1, 1, 1, 1, 6, '2021-10-11 10:00:14', '2021-10-11 10:00:14'),
(35, 'Breathe Free Vaporizer', 1, 1, 1, 1, 1, 6, '2021-10-11 10:01:42', '2021-10-11 10:01:42'),
(36, 'Vitamin B-12', 1, 1, 1, 1, 1, 6, '2021-10-11 10:02:29', '2021-10-11 10:02:29'),
(37, 'Hemp Protein', 1, 1, 1, 1, 1, 6, '2021-10-11 10:02:55', '2021-10-11 10:02:55'),
(38, 'Brown Rice', 1, 1, 1, 2, 1, 6, '2021-10-11 10:03:31', '2021-10-11 11:35:10'),
(39, 'Blood Glucose Monitors', 1, 1, 1, 1, 1, 6, '2021-10-11 10:04:24', '2021-10-11 10:04:24'),
(40, 'Blood Glucose Monitors', 1, 1, 1, 2, 1, 6, '2021-10-11 10:04:25', '2021-10-11 10:04:50'),
(41, 'Pedometers And Weighing Scales', 1, 1, 1, 1, 1, 6, '2021-10-11 10:05:18', '2021-10-11 10:05:18'),
(42, 'Bananas', 1, 1, 1, 1, 1, 5, '2021-10-11 10:07:30', '2021-10-11 10:07:30'),
(43, 'Raspberries Mexico', 1, 1, 1, 1, 1, 5, '2021-10-11 10:08:04', '2021-10-11 10:08:04'),
(44, 'Pears Forelle', 1, 1, 1, 1, 1, 5, '2021-10-11 10:08:42', '2021-10-11 10:08:42'),
(45, 'Tomato', 1, 1, 1, 1, 1, 5, '2021-10-11 10:09:29', '2021-10-11 10:09:29'),
(46, 'Sweet Potato Australia', 1, 1, 1, 1, 1, 5, '2021-10-11 10:10:34', '2021-10-11 10:10:34'),
(47, 'Cauliflower', 1, 1, 1, 1, 1, 5, '2021-10-11 10:11:10', '2021-10-11 10:11:10'),
(48, 'Choco lava cake', 1, 1, 1, 1, 1, 5, '2021-10-11 10:12:12', '2021-10-11 10:12:12'),
(49, 'Black Forest', 1, 1, 1, 1, 1, 5, '2021-10-11 10:14:24', '2021-10-11 10:14:24'),
(50, 'Brown Eggs', 1, 1, 1, 1, 1, 5, '2021-10-11 10:15:04', '2021-10-11 10:15:04'),
(51, 'Apple', 1, 1, 1, 1, 1, 5, '2021-10-11 10:15:46', '2021-10-11 10:15:46'),
(52, 'Watermelon', 1, 1, 1, 1, 1, 5, '2021-10-11 10:16:50', '2021-10-11 10:16:50'),
(53, 'cabbage', 1, 1, 1, 1, 1, 5, '2021-10-11 10:17:26', '2021-10-11 10:17:26'),
(54, 'broccoli', 1, 1, 1, 1, 1, 5, '2021-10-11 10:18:02', '2021-10-11 10:18:02'),
(55, 'cheese', 1, 1, 1, 1, 1, 5, '2021-10-11 10:18:59', '2021-10-11 10:18:59'),
(56, 'Milk shake', 1, 1, 1, 1, 1, 5, '2021-10-11 10:20:22', '2021-10-11 10:20:22'),
(57, 'Prawn Pie', 1, 1, 1, 1, 1, 3, '2021-10-11 10:23:29', '2021-10-11 10:23:29'),
(58, 'Crispy Calamari Rings', 1, 1, 1, 1, 1, 3, '2021-10-11 10:26:19', '2021-10-11 10:26:19'),
(59, 'Yorkshire Lamb Patties', 1, 1, 1, 1, 1, 3, '2021-10-11 10:26:47', '2021-10-11 10:26:47'),
(60, 'Roesti And Salad', 1, 1, 1, 1, 1, 3, '2021-10-11 10:27:32', '2021-10-11 10:27:32'),
(61, 'Paneer Steak', 1, 1, 1, 1, 1, 3, '2021-10-11 10:28:28', '2021-10-11 10:28:28'),
(62, 'Apple Sausage Plait.', 1, 1, 1, 1, 1, 3, '2021-10-11 10:29:07', '2021-10-11 10:29:07'),
(63, 'Pizza Fritta', 1, 1, 1, 1, 1, 2, '2021-10-11 10:30:10', '2021-10-11 10:30:10'),
(64, 'Tomato Bacon Pasta', 1, 1, 1, 1, 1, 2, '2021-10-11 10:30:42', '2021-10-11 10:30:42'),
(65, 'Pasta Carbonara', 1, 1, 1, 1, 1, 2, '2021-10-11 10:31:25', '2021-10-11 10:31:25'),
(66, 'Focaccia Bread', 1, 1, 1, 1, 1, 2, '2021-10-11 10:32:11', '2021-10-11 10:32:11'),
(67, 'Dim Sums', 1, 1, 1, 1, 1, 2, '2021-10-11 10:32:53', '2021-10-11 10:32:53'),
(68, 'Chicken with Chestnuts', 1, 1, 1, 1, 1, 2, '2021-10-11 10:33:27', '2021-10-11 10:33:27'),
(69, 'Spring Rolls', 1, 1, 1, 1, 1, 2, '2021-10-11 10:34:13', '2021-10-11 10:34:13'),
(70, 'Stir Fried Tofu with Rice', 1, 1, 1, 1, 1, 2, '2021-10-11 10:34:58', '2021-10-11 10:34:58'),
(71, 'Polenta', 1, 1, 1, 1, 1, 2, '2021-10-11 10:35:41', '2021-10-11 10:35:41'),
(72, 'Osso buco', 1, 1, 1, 1, 1, 2, '2021-10-11 10:36:51', '2021-10-11 10:36:51'),
(73, 'Quick Noodles', 1, 1, 1, 1, 1, 2, '2021-10-11 10:37:12', '2021-10-11 10:37:12'),
(74, 'SzechwanChilliChicken', 1, 1, 1, 1, 1, 2, '2021-10-11 10:38:05', '2021-10-11 10:38:05'),
(75, 'Acer Nitro 5 Gaming Laptop', 1, 1, 1, 1, 1, 7, '2021-10-11 10:49:14', '2021-10-11 10:49:14');

-- --------------------------------------------------------

--
-- Table structure for table `addon_set_translations`
--

CREATE TABLE `addon_set_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addon_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addon_set_translations`
--

INSERT INTO `addon_set_translations` (`id`, `title`, `addon_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, NULL, NULL),
(2, 'Number of bedrooms?', 2, 1, NULL, NULL),
(3, 'What kind of car services you require ?', 3, 1, NULL, NULL),
(4, 'Number of Bathrooms ?', 4, 1, NULL, NULL),
(5, 'Jackets', 5, 1, NULL, NULL),
(6, 'Coats', 6, 1, NULL, NULL),
(7, 'Denim', 7, 1, NULL, NULL),
(8, 'Fluffy jackets', 8, 1, NULL, NULL),
(9, 'partywear', 9, 1, NULL, NULL),
(10, 'Printed dress', 10, 1, NULL, NULL),
(11, 'Shirt', 11, 1, NULL, NULL),
(12, 'causal t shirt', 12, 1, NULL, NULL),
(13, 'caprii', 13, 1, NULL, NULL),
(14, 'short', 14, 1, NULL, '2021-10-11 09:27:38'),
(15, 'scirt', 15, 1, NULL, NULL),
(16, 'sweaters', 16, 1, NULL, NULL),
(17, 'Asus Zenbook 14', 17, 1, NULL, NULL),
(18, 'HP Pavilion 15', 18, 1, NULL, NULL),
(19, 'Macbook Pro M1 Chip', 19, 1, NULL, NULL),
(20, 'Iphone 12 Pro Max', 20, 1, NULL, NULL),
(21, 'Oneplus 9Pro', 21, 1, NULL, NULL),
(22, 'Samsung Note 20 Ultra', 22, 1, NULL, NULL),
(23, 'Inspiron 14 Laptop', 23, 1, NULL, NULL),
(24, 'Inspiron14Laptop', 24, 1, NULL, NULL),
(25, 'Lenovo IdeaPad Slim', 25, 1, NULL, NULL),
(26, 'Acer Nitro 5 Gaming Laptop', 26, 1, NULL, NULL),
(27, 'OnePlus 9R 5G', 27, 1, NULL, NULL),
(28, 'Samsung Galaxy S20 FE 5G', 28, 1, NULL, NULL),
(29, 'OPPO F19 Pro', 29, 1, NULL, NULL),
(30, 'Vitamin C + Zinc', 30, 1, NULL, NULL),
(31, 'Folic B9', 31, 1, NULL, NULL),
(32, 'Casein Protein', 32, 1, NULL, NULL),
(33, 'Thermometer', 33, 1, NULL, NULL),
(34, 'Pulse Oximeter', 34, 1, NULL, NULL),
(35, 'Breathe Free Vaporizer', 35, 1, NULL, NULL),
(36, 'Vitamin B-12', 36, 1, NULL, NULL),
(37, 'Hemp Protein', 37, 1, NULL, NULL),
(38, 'Brown Rice', 38, 1, NULL, NULL),
(39, 'Blood Glucose Monitors', 39, 1, NULL, NULL),
(40, 'Blood Glucose Monitors', 40, 1, NULL, NULL),
(41, 'Pedometers And Weighing Scales', 41, 1, NULL, NULL),
(42, 'Bananas', 42, 1, NULL, NULL),
(43, 'Raspberries Mexico', 43, 1, NULL, NULL),
(44, 'Pears Forelle', 44, 1, NULL, NULL),
(45, 'Tomato', 45, 1, NULL, NULL),
(46, 'Sweet Potato Australia', 46, 1, NULL, NULL),
(47, 'Cauliflower', 47, 1, NULL, NULL),
(48, 'Choco lava cake', 48, 1, NULL, NULL),
(49, 'Black Forest', 49, 1, NULL, NULL),
(50, 'Brown Eggs', 50, 1, NULL, NULL),
(51, 'Apple', 51, 1, NULL, NULL),
(52, 'Watermelon', 52, 1, NULL, NULL),
(53, 'cabbage', 53, 1, NULL, NULL),
(54, 'broccoli', 54, 1, NULL, NULL),
(55, 'cheese', 55, 1, NULL, NULL),
(56, 'Milk shake', 56, 1, NULL, NULL),
(57, 'Prawn Pie', 57, 1, NULL, NULL),
(58, 'Crispy Calamari Rings', 58, 1, NULL, NULL),
(59, 'Yorkshire Lamb Patties', 59, 1, NULL, NULL),
(60, 'Roesti And Salad', 60, 1, NULL, NULL),
(61, 'Paneer Steak', 61, 1, NULL, NULL),
(62, 'Apple Sausage Plait.', 62, 1, NULL, NULL),
(63, 'Pizza Fritta', 63, 1, NULL, NULL),
(64, 'Tomato Bacon Pasta', 64, 1, NULL, NULL),
(65, 'Pasta Carbonara', 65, 1, NULL, NULL),
(66, 'Focaccia Bread', 66, 1, NULL, NULL),
(67, 'Dim Sums', 67, 1, NULL, NULL),
(68, 'Chicken with Chestnuts', 68, 1, NULL, NULL),
(69, 'Spring Rolls', 69, 1, NULL, NULL),
(70, 'Stir Fried Tofu with Rice', 70, 1, NULL, NULL),
(71, 'Polenta', 71, 1, NULL, NULL),
(72, 'Osso buco', 72, 1, NULL, NULL),
(73, 'Quick Noodles', 73, 1, NULL, NULL),
(74, 'SzechwanChilliChicken', 74, 1, NULL, NULL),
(75, 'Acer Nitro 5 Gaming Laptop', 75, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `models` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_dynamic_tutorials`
--

CREATE TABLE `app_dynamic_tutorials` (
  `id` bigint UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` int DEFAULT '1',
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'image' COMMENT 'image/video',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_stylings`
--

CREATE TABLE `app_stylings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint DEFAULT NULL COMMENT '1-Text, 2-Option, 3-Option Images, 4-Color',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_stylings`
--

INSERT INTO `app_stylings` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Regular Font', 2, NULL, NULL),
(2, 'Medium Font', 2, NULL, NULL),
(3, 'Bold Font', 2, NULL, NULL),
(4, 'Primary Color', 4, NULL, NULL),
(5, 'Secondary Color', 4, NULL, NULL),
(6, 'Tertiary Color', 4, NULL, NULL),
(7, 'Tab Bar Style', 3, NULL, NULL),
(8, 'Home Page Style', 3, NULL, NULL),
(9, 'Home Tag Line', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_styling_options`
--

CREATE TABLE `app_styling_options` (
  `id` bigint UNSIGNED NOT NULL,
  `app_styling_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_selected` tinyint NOT NULL DEFAULT '1' COMMENT '1-yes, 2-no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `template_id` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_styling_options`
--

INSERT INTO `app_styling_options` (`id`, `app_styling_id`, `name`, `image`, `is_selected`, `created_at`, `updated_at`, `template_id`) VALUES
(1, 1, 'CircularStd-Book', NULL, 1, NULL, NULL, NULL),
(2, 1, 'SFProText-Regular', NULL, 0, NULL, NULL, NULL),
(3, 1, 'Futura-Normal', NULL, 0, NULL, NULL, NULL),
(4, 1, 'Eina02-Regular', NULL, 0, NULL, NULL, NULL),
(5, 2, 'CircularStd-Medium', NULL, 1, NULL, NULL, NULL),
(6, 2, 'SFProText-Medium', NULL, 0, NULL, NULL, NULL),
(7, 2, 'Futura-Medium', NULL, 0, NULL, NULL, NULL),
(8, 2, 'Eina02-SemiBold', NULL, 0, NULL, NULL, NULL),
(9, 3, 'CircularStd-Bold', NULL, 1, NULL, NULL, NULL),
(10, 3, 'SFProText-Bold', NULL, 0, NULL, NULL, NULL),
(11, 3, 'FuturaBT-Heavy', NULL, 0, NULL, NULL, NULL),
(12, 3, 'Eina02-Bold', NULL, 0, NULL, NULL, NULL),
(13, 4, '#41A2E6', NULL, 1, NULL, NULL, NULL),
(14, 5, '#fff', NULL, 1, NULL, NULL, NULL),
(15, 6, '#fff', NULL, 1, NULL, NULL, NULL),
(16, 7, 'Tab 1', 'bar.png', 0, NULL, '2021-10-06 09:37:01', 1),
(17, 7, 'Tab 2', 'bar_two.png', 0, NULL, '2021-10-06 09:37:01', 2),
(18, 7, 'Tab 3', 'bar_three.png', 0, NULL, '2021-10-06 09:37:01', 3),
(19, 7, 'Tab 4', 'bar_four.png', 1, NULL, '2021-10-06 09:37:01', 4),
(20, 7, 'Tab 5', 'bar_five.png', 0, NULL, '2021-10-06 09:37:01', 5),
(21, 8, 'Home Page 1', 'home.png', 0, NULL, '2021-11-07 06:08:03', 1),
(22, 8, 'Home Page 4', 'home_four.png', 0, NULL, '2021-11-07 06:08:03', 2),
(23, 8, 'Home Page 5', 'home_five.png', 1, NULL, '2021-11-07 06:08:03', 3),
(24, 9, 'Create a free account and join us!', NULL, 1, NULL, NULL, NULL),
(25, 8, 'Home Page 6', 'home_six.png', 0, '2021-10-12 14:10:14', '2021-11-07 06:08:03', 4);

-- --------------------------------------------------------

--
-- Table structure for table `auto_reject_orders_cron`
--

CREATE TABLE `auto_reject_orders_cron` (
  `id` bigint UNSIGNED NOT NULL,
  `database_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_vendor_id` int DEFAULT NULL,
  `auto_reject_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validity_on` tinyint NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
  `sorting` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - active, 0 - pending, 2 - blocked',
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `redirect_category_id` bigint UNSIGNED DEFAULT NULL,
  `redirect_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_mobile` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`, `image_mobile`) VALUES
(1, 'Electronics', NULL, 'banner/WuzCZXWQqt4dkrtGox6rQ7zIZPWiZnq4cJJ3pLEO.png', 1, 1, 1, '2021-10-05 01:06:00', '2021-12-31 12:00:00', 19, NULL, 'category', NULL, '2021-10-11 10:22:17', 'banner/Ly9d7sR5ZdVhDy2fhKcvq2OIbipwXPaAfyTg8HzN.png'),
(2, 'Vegetables', NULL, 'banner/hg4qAjmf6SzObNE6lSgGRjIjMbKYW1QA8GtaIDmX.png', 1, 2, 1, '2021-10-05 01:07:00', '2021-12-31 12:00:00', 8, NULL, 'category', NULL, '2021-10-11 10:22:06', 'banner/GFtkCmAbjXXBEUgFyX0d4PwaOYYQFQTwZK9tPj2a.png'),
(3, 'Pharmacy', NULL, 'banner/EfrnNn6R6QxonjB9tdVDhLZCBuje1XxUw1gjXvFR.jpg', 0, 3, 1, '2021-10-05 01:07:00', '2021-12-31 12:00:00', 5, NULL, 'category', NULL, '2021-10-08 06:23:46', 'banner/AZ7QmHWxvodad5TR4A2Xjx3O5QNjZ4lcDez2Wcgm.png'),
(4, 'Fashion', NULL, 'banner/r1e1x91RGF3M2xG8iXxxYSOyOqSs6qtHHG0EGx3n.png', 1, 4, 1, '2021-10-05 01:07:00', '2021-12-31 12:00:00', 26, NULL, 'category', '2021-10-04 18:49:39', '2021-10-11 10:21:53', 'banner/sqjzaAEHcquIlbwFn34B6lHmoGDAcNajMUYdl6SR.png');

-- --------------------------------------------------------

--
-- Table structure for table `blocked_tokens`
--

CREATE TABLE `blocked_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired` tinyint NOT NULL DEFAULT '0' COMMENT '1 yes, 0 no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_banner` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` smallint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `title`, `image`, `image_banner`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dia\'s Secret', 'brand/9aQWMpFc5Gw5jjs3pPutTs90ig6Y8HqPFvWeobSs.jpg', NULL, 4, 1, NULL, '2021-10-05 04:52:26'),
(2, 'Lahela jane', 'brand/9jMdTBAiPUjq7b4ffuKFtL3PRkAhe7ZQTZHAjXZV.png', NULL, 3, 1, NULL, '2021-10-05 04:52:26'),
(3, 'Horsewaver', 'brand/Pf1X9NWjYKCgNTCUhyb0SY3mbhGlPbiPAQLz7zre.jpg', NULL, 2, 1, NULL, '2021-10-05 04:52:26'),
(4, 'Foodeer', 'brand/DKv8wbdhBbYN8Sqd0riEOvZqrHbRxhsRiHpOpZT3.png', NULL, 1, 1, NULL, '2021-10-05 04:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `brand_categories`
--

CREATE TABLE `brand_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brand_categories`
--

INSERT INTO `brand_categories` (`id`, `brand_id`, `category_id`, `created_at`, `updated_at`) VALUES
(9, 4, 16, NULL, NULL),
(10, 4, 14, NULL, NULL),
(11, 4, 15, NULL, NULL),
(12, 3, 26, NULL, NULL),
(13, 3, 27, NULL, NULL),
(14, 3, 28, NULL, NULL),
(15, 2, 26, NULL, NULL),
(16, 2, 16, NULL, NULL),
(17, 2, 9, NULL, NULL),
(18, 2, 8, NULL, NULL),
(19, 2, 10, NULL, NULL),
(20, 1, 26, NULL, NULL),
(21, 1, 17, NULL, NULL),
(22, 1, 18, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brand_translations`
--

CREATE TABLE `brand_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_types`
--

CREATE TABLE `business_types` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cab_booking_layouts`
--

CREATE TABLE `cab_booking_layouts` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_by` tinyint DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '0-No, 1-Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cab_booking_layouts`
--

INSERT INTO `cab_booking_layouts` (`id`, `title`, `slug`, `order_by`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vendors', 'vendors', 1, NULL, 1, NULL, NULL),
(2, 'Featured Products', 'featured_products', 2, NULL, 1, NULL, NULL),
(3, 'New Products', 'new_products', 3, NULL, 1, NULL, NULL),
(4, 'On Sale', 'on_sale', 4, NULL, 1, NULL, NULL),
(5, 'Best Sellers', 'best_sellers', 5, NULL, 1, NULL, NULL),
(6, 'Brands', 'brands', 6, NULL, 1, NULL, NULL),
(7, 'Pickup Delivery', 'pickup_delivery', 7, NULL, 1, '2021-10-04 18:42:14', '2021-10-04 18:42:14');

-- --------------------------------------------------------

--
-- Table structure for table `cab_booking_layout_categories`
--

CREATE TABLE `cab_booking_layout_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `cab_booking_layout_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cab_booking_layout_categories`
--

INSERT INTO `cab_booking_layout_categories` (`id`, `cab_booking_layout_id`, `category_id`, `created_at`, `updated_at`) VALUES
(3, 7, 29, '2021-11-15 10:42:34', '2021-11-15 10:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `cab_booking_layout_transaltions`
--

CREATE TABLE `cab_booking_layout_transaltions` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cab_booking_layout_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `body_html` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cab_booking_layout_transaltions`
--

INSERT INTO `cab_booking_layout_transaltions` (`id`, `title`, `cab_booking_layout_id`, `language_id`, `created_at`, `updated_at`, `body_html`) VALUES
(1, NULL, 1, 1, '2021-11-15 10:42:25', '2021-11-15 10:42:25', NULL),
(2, NULL, 2, 1, '2021-11-15 10:42:25', '2021-11-15 10:42:25', NULL),
(3, NULL, 3, 1, '2021-11-15 10:42:25', '2021-11-15 10:42:25', NULL),
(4, NULL, 4, 1, '2021-11-15 10:42:25', '2021-11-15 10:42:25', NULL),
(5, NULL, 5, 1, '2021-11-15 10:42:25', '2021-11-15 10:42:25', NULL),
(6, NULL, 6, 1, '2021-11-15 10:42:25', '2021-11-15 10:42:25', NULL),
(7, 'Pickup and Delivery', 7, 1, '2021-11-15 10:42:25', '2021-11-15 10:42:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `unique_identifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Active, 1-Blocked, 2-Deleted',
  `is_gift` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Yes, 1-No',
  `item_count` int DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `schedule_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_date_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `specific_instructions` text COLLATE utf8mb4_unicode_ci,
  `comment_for_pickup_driver` mediumtext COLLATE utf8mb4_unicode_ci,
  `comment_for_dropoff_driver` mediumtext COLLATE utf8mb4_unicode_ci,
  `comment_for_vendor` mediumtext COLLATE utf8mb4_unicode_ci,
  `schedule_pickup` datetime DEFAULT NULL,
  `schedule_dropoff` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_addons`
--

CREATE TABLE `cart_addons` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `cart_product_id` bigint UNSIGNED NOT NULL,
  `addon_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_coupons`
--

CREATE TABLE `cart_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_products`
--

CREATE TABLE `cart_products` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_dinein_table_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL COMMENT '0-Active, 1-Blocked, 2-Deleted',
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `is_tax_applied` tinyint NOT NULL COMMENT '0-Yes, 1-No',
  `tax_rate_id` bigint UNSIGNED DEFAULT NULL,
  `schedule_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_date_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `luxury_option_id` bigint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_product_prescriptions`
--

CREATE TABLE `cart_product_prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `prescription` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_visible` tinyint DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `position` smallint NOT NULL DEFAULT '1' COMMENT 'for same position, display asc order',
  `is_core` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `can_add_products` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'only products name, product with description',
  `warning_page_id` bigint DEFAULT NULL,
  `template_type_id` bigint DEFAULT NULL,
  `warning_page_design` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `show_wishlist` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `icon`, `slug`, `type_id`, `image`, `is_visible`, `status`, `position`, `is_core`, `can_add_products`, `parent_id`, `vendor_id`, `client_code`, `display_mode`, `warning_page_id`, `template_type_id`, `warning_page_design`, `created_at`, `updated_at`, `deleted_at`, `show_wishlist`) VALUES
(1, NULL, 'Root', 3, NULL, 0, 1, 1, 1, 0, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 'category/icon/RWMrSUmootTCFHz8npUZ8226QNwIPElNjpi6yRxn.svg', 'Delivery', 7, 'category/image/YCKm5Wg6gOzGLcJzvBWrqFUFAedTO2UyxjpXW2Ji.jpg', 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-04 18:16:21', NULL, 1),
(3, 'category/icon/dV6s6hp3N5wjaBecDTh0rsX215mAmJlZxsTqxOUX.svg', 'Restaurant', 6, 'category/image/so6wbch80ReRj6v98nDoAsdQmvWexcr5TBNMKrVh.jpg', 1, 1, 5, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-11-15 10:43:09', NULL, 1),
(4, 'category/icon/aEYyg9aS0dudM8MSsNM9fxSdAx4rr5fG3D4wQVGX.svg', 'Supermarket', 6, 'category/image/MHOXYu4zK3nftnwI4R3wThKEhhg9vYgEau903Y2K.jpg', 1, 1, 6, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-11-15 10:43:22', NULL, 1),
(5, 'category/icon/MWCp0xZXXgbQaQvVC3Kg2XTedLWI8bqc8bYJnegS.svg', 'Pharmacy', 6, 'category/image/YQo8N8MSqBxp8Btv5c5MnGIPCNK8IZGUsMo7kAbL.jpg', 1, 1, 7, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-11-15 10:43:35', NULL, 1),
(6, NULL, 'Send something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-04 17:43:24', '2021-10-04 17:43:24', 1),
(7, NULL, 'Buy something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-04 17:43:29', '2021-10-04 17:43:29', 1),
(8, 'category/icon/t1CwbeuA1FoGnW4vmpfWAO4zrVPB6KiOITEXLoxU.png', 'Vegetables', 1, 'category/image/uP3FdUkice6kKhFiNDY8hn9ZxGbyIgKGCIyT5mkn.jpg', 1, 1, 2, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-04 18:26:40', NULL, 1),
(9, 'category/icon/uXRj5nfITgRWQnyxb6ou0AhAivZ8kGrZwKh7gTuE.png', 'Fruits', 1, 'category/image/PBLNwITUYuHil4feqe4eXGoWJRufICuAkmukdfpB.jpg', 1, 1, 1, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-04 18:25:27', NULL, 1),
(10, 'category/icon/Rdy4E0oeDX1nbrKbszDTRb8hAG5l02biG3ZQmN4W.png', 'Dairy and Eggs', 1, 'category/image/1bgiM0KT23xGZP7115HI0aoojrnty5ilz2g4MeJU.jpg', 1, 1, 3, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-04 18:28:47', NULL, 1),
(11, NULL, 'E-Commerce', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-04 17:50:55', '2021-10-04 17:50:55', 1),
(12, NULL, 'Cloth', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-04 17:50:58', '2021-10-04 17:50:58', 1),
(13, NULL, 'Dispatcher', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-04 17:51:01', '2021-10-04 17:51:01', 1),
(14, 'category/icon/qXoD1zc6nhpTYzNRoEMUl9BqeJESdf6GyvBNltmd.png', 'italian', 1, 'category/image/G4ePl9eipTDm8huheFJUUEfbyN45bYkavrCs2D49.jpg', 1, 1, 1, 1, 1, 3, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 17:58:35', '2021-10-04 18:20:36', NULL, 1),
(15, 'category/icon/hbiwMvyTzksdpjX6OaPJHFGAwiXZryjpNpYoSt2b.png', 'continental', 1, 'category/image/29q6MhYCSljobFJpmlVfN8niQLJo47kv7ePDiyRc.jpg', 1, 1, 3, 1, 1, 3, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 17:58:54', '2021-10-04 18:23:59', NULL, 1),
(16, 'category/icon/yaYZA4gFCVPg1VXh72PUPwhxPxXf7hfht2Ro1E53.png', 'chinese', 1, 'category/image/K4zuQnKlpl9h5C9m29hX9v4PYolArpTugTPFKSNY.jpg', 1, 1, 2, 1, 1, 3, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 17:59:17', '2021-10-04 18:21:57', NULL, 1),
(17, 'category/icon/dGTJPXadYhQ7pUKIFhequRdwZVBKE8ybxmfg9P0M.png', 'fitness', 1, 'category/image/QrxrOTqtIsX3PbWP1Lo4Sa7ee5J4XxbFOMX9bYW5.jpg', 1, 1, 1, 1, 1, 5, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:01:29', '2021-10-04 18:30:06', NULL, 1),
(18, 'category/icon/QbOZTj6r3WWqMWAat2oUlc4xujhUa2lYe9S7SSbL.png', 'healthcare', 1, 'category/image/P83sey2SwBY3t1KjeDTHTy69y7MwlErlRSwKgw8K.png', 1, 1, 2, 1, 1, 5, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:01:57', '2021-10-04 18:31:38', NULL, 1),
(19, 'category/icon/mIzK7P6TBemvw9JAiI7JdfXQQG65z8bDLDvCFToV.png', 'electronics', 6, 'category/image/YMb6SgE7KzRMBtT1ZIZU0YWQsraaWg3pyb000Rb7.jpg', 1, 1, 8, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:04:26', '2021-11-15 10:44:05', NULL, 1),
(20, 'category/icon/LWbscqM2Jn4oHtflsO7LuqMm1zaMrB2Y5HwtzCwf.png', 'mobiles', 1, 'category/image/jbZ84GfGMvMwA4m3Ps3yBwzNverV38W1L2yvVKaH.png', 1, 1, 1, 1, 1, 19, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:05:16', '2021-10-04 18:33:07', NULL, 1),
(21, 'category/icon/DNJafCwN6Lz4oUyiVtakniTuKUMCCsZ3Ty6b0fpC.png', 'laptops', 1, 'category/image/7VgnbZoJ4kBPK9Rub3Y57GYk44UyQcmqY3IHdfJn.jpg', 1, 1, 2, 1, 1, 19, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:09:04', '2021-10-11 11:06:10', NULL, 1),
(22, 'category/icon/G33Uu0pDIE8w2IGPI27JrjRtMV5EPswjWpq0Ks08.svg', 'homecare', 8, 'category/image/u4wvFNI6Gb9S2FsVNwxE7I9dgg0JOSTrJOAboyxa.jpg', 1, 1, 10, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:09:38', '2021-10-04 18:38:08', NULL, 1),
(23, 'category/icon/iA09rFDNXHtDTbvUoamASX4SsKAWWWwpnkL5hKx9.png', 'acservices', 8, 'category/image/DXOqWWe00Rj7CElolyRe5H59srn0R4CA2b9dgx63.jpg', 1, 1, 1, 1, 0, 22, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:10:06', '2021-10-04 18:39:18', NULL, 1),
(24, 'category/icon/XI6Nh00c9xkt3tWoE7fT9JWKPt42Cl8Fyrv40XR4.png', 'cleaningservice', 8, 'category/image/KT2lsCWZBccTkod06SohnYa3maydEJS6f7ADwlkH.png', 1, 1, 2, 1, 0, 22, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:10:50', '2021-10-04 18:40:17', NULL, 1),
(25, 'category/icon/dSPNHq7gC58jsXBLrOXoNGM58W7A5SlkgFH5oOSn.png', 'pest-control', 8, 'category/image/6yUdxG582TyzcAL75ib36jd0x0yvzXj7wLlgj5WB.jpg', 1, 1, 3, 1, 0, 22, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:11:13', '2021-10-04 18:41:12', NULL, 1),
(26, 'category/icon/Rpp2mHmWkHdsDTSEnXyb31bFXdiK53XgymVj8swI.svg', 'fashion', 6, 'category/image/e0K99aCMDkWAzkOKCMusDQYsfNjtBhTWQ55Kk8as.jpg', 1, 1, 9, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:11:50', '2021-11-15 10:44:13', NULL, 1),
(27, 'category/icon/pXlcKR5tO4Viq4KOfL29L4Qs3O7moy0567pTPyL8.png', 'men', 1, 'category/image/caikV2y1rYWD0OIg9djkgL3xG00R2l4CIcm2kobx.png', 1, 1, 1, 1, 1, 26, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:13:09', '2021-10-04 18:36:03', NULL, 1),
(28, 'category/icon/l48XdoJCbOJ46jJcoXGr0cOIfDqdX3MHzPrCf0x9.png', 'women', 1, 'category/image/A3SPk9e1NfMdgcKIhVtP5z3M2aAVAxCqWdjNaOk5.jpg', 1, 1, 2, 1, 1, 26, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:13:32', '2021-10-04 18:37:46', NULL, 1),
(29, 'category/icon/ZHuoAWmRWMpISGLoggN50fCgm4WoMOr6oXpY56PD.svg', 'cabservice', 7, 'category/image/dYZ1Ddb7ctgBsvGkAW04yKltqDAw4lrnvEY8tmZ1.jpg', 1, 1, 2, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:13:53', '2021-10-04 18:18:23', NULL, 1),
(30, 'category/icon/RoqHcb594WAwFtv2M45NqtwMiuI9IYsX8Jn2BpVL.svg', 'motoservice', 7, 'category/image/cDjiDNDCMM2kd014UA7oDZ5ZRlPmGgNPuWDIsvQM.jpg', 1, 1, 3, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:14:25', '2021-10-04 18:18:38', NULL, 1),
(31, 'category/icon/gu0xfHqLjvqwuFitNYHLxJOHirhUZ7wLJhHpg4QQ.svg', 'autoservice', 7, 'category/image/RLHNmawkzB9TlIkMODSSJiasmYG09jk1jAQxnqDE.jpg', 1, 1, 4, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-10-04 18:15:11', '2021-10-04 18:18:50', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_histories`
--

CREATE TABLE `category_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `action` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'add' COMMENT 'add, update, delete, block, active',
  `updater_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SuperAdmin' COMMENT 'SuperAdmin, Admin, Seller',
  `update_id` bigint UNSIGNED DEFAULT NULL,
  `client_code` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_tags`
--

CREATE TABLE `category_tags` (
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `tag` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_translations`
--

CREATE TABLE `category_translations` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trans-slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_translations`
--

INSERT INTO `category_translations` (`id`, `name`, `trans-slug`, `meta_title`, `meta_description`, `meta_keywords`, `category_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'root', '', 'root', '', '', 1, 1, NULL, '2021-10-06 09:46:51'),
(2, 'Delivery', '', 'Delivery', NULL, NULL, 2, 1, NULL, '2021-10-06 09:46:51'),
(3, 'Restaurant', '', 'Restaurant', NULL, NULL, 3, 1, NULL, '2021-10-06 09:46:51'),
(4, 'Supermarket', '', 'Supermarket', NULL, NULL, 4, 1, NULL, '2021-10-06 09:46:51'),
(5, 'Pharmacy', '', 'Pharmacy', NULL, NULL, 5, 1, NULL, '2021-10-06 09:46:51'),
(6, 'Send something', '', 'Send something', '', '', 6, 1, NULL, '2021-10-06 09:46:51'),
(7, 'Buy something', '', 'Buy something', '', '', 7, 1, NULL, '2021-10-06 09:46:51'),
(8, 'Vegetables', '', 'Vegetables', NULL, NULL, 8, 1, NULL, '2021-10-06 09:46:51'),
(9, 'Fruits', '', 'Fruits', NULL, NULL, 9, 1, NULL, '2021-10-06 09:46:51'),
(10, 'Dairy and Eggs', '', 'Dairy and Eggs', NULL, NULL, 10, 1, NULL, '2021-10-06 09:46:51'),
(11, 'E-Commerce', '', 'E-Commerce', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(12, 'Cloth', '', 'Cloth', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(13, 'Dispatcher', '', 'Dispatcher', '', '', 13, 1, NULL, '2021-10-06 09:46:51'),
(14, 'Italian', NULL, 'Italian', NULL, NULL, 14, 1, '2021-10-04 17:58:35', '2021-10-06 09:46:51'),
(15, 'Continental', NULL, 'Continental', NULL, NULL, 15, 1, '2021-10-04 17:58:54', '2021-10-06 09:46:51'),
(16, 'Chinese', NULL, 'Chinese', NULL, NULL, 16, 1, '2021-10-04 17:59:17', '2021-10-06 09:46:51'),
(17, 'Fitness & Supplement', NULL, 'Fitness & Supplement', NULL, NULL, 17, 1, '2021-10-04 18:01:29', '2021-10-06 09:46:51'),
(18, 'Healthcare Device', NULL, 'Healthcare Device', NULL, NULL, 18, 1, '2021-10-04 18:01:57', '2021-10-06 09:46:51'),
(19, 'Electronics', NULL, 'Electronics', NULL, NULL, 19, 1, '2021-10-04 18:04:26', '2021-10-06 09:46:51'),
(20, 'Mobiles Phones', NULL, 'Mobiles Phones', NULL, NULL, 20, 1, '2021-10-04 18:05:16', '2021-10-06 09:46:51'),
(21, 'Laptops', NULL, 'Laptops', NULL, NULL, 21, 1, '2021-10-04 18:09:04', '2021-10-06 09:46:51'),
(22, 'Home Care', NULL, 'Home Care', NULL, NULL, 22, 1, '2021-10-04 18:09:38', '2021-10-06 09:46:51'),
(23, 'AC Service & Repair', NULL, 'AC Service & Repair', NULL, NULL, 23, 1, '2021-10-04 18:10:06', '2021-10-06 09:46:51'),
(24, 'Cleaning Service', NULL, 'Cleaning Service', NULL, NULL, 24, 1, '2021-10-04 18:10:50', '2021-10-06 09:46:51'),
(25, 'Pest Control', NULL, 'Pest Control', NULL, NULL, 25, 1, '2021-10-04 18:11:13', '2021-10-06 09:46:51'),
(26, 'Fashion', NULL, 'Fashion', NULL, NULL, 26, 1, '2021-10-04 18:11:50', '2021-10-06 09:46:51'),
(27, 'Men', NULL, 'Men', NULL, NULL, 27, 1, '2021-10-04 18:13:09', '2021-10-06 09:46:51'),
(28, 'Women', NULL, 'Women', NULL, NULL, 28, 1, '2021-10-04 18:13:32', '2021-10-06 09:46:51'),
(29, 'Cab Service', NULL, 'Cab Service', NULL, NULL, 29, 1, '2021-10-04 18:13:53', '2021-10-06 09:46:51'),
(30, 'Moto Service', NULL, 'Moto Service', NULL, NULL, 30, 1, '2021-10-04 18:14:25', '2021-10-06 09:46:51'),
(31, 'Auto Service', NULL, 'Auto Service', NULL, NULL, 31, 1, '2021-10-04 18:15:11', '2021-10-06 09:46:51');

-- --------------------------------------------------------

--
-- Table structure for table `celebrities`
--

CREATE TABLE `celebrities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - pending, 1 - active, 2 - inactive, 3 - deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `celebrity_brands`
--

CREATE TABLE `celebrity_brands` (
  `celebrity_id` bigint UNSIGNED DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encpass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_domain` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_domain` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `is_blocked` tinyint NOT NULL DEFAULT '0',
  `database_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 for active, 0 for pending, 2 for blocked, 3 for inactive',
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `database_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_port` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_superadmin` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone_number`, `password`, `encpass`, `country_id`, `timezone`, `custom_domain`, `sub_domain`, `is_deleted`, `is_blocked`, `database_path`, `database_name`, `database_username`, `database_password`, `logo`, `company_name`, `company_address`, `language_id`, `status`, `code`, `created_at`, `updated_at`, `database_host`, `database_port`, `is_superadmin`) VALUES
(1, 'Moving Wheels Delivery', 'admin@movingwheelsdelivery.com', '6138936373', '$2y$10$vbOUut0ELEpa2SGoZsU4KOhuFuzBlU52MHFbcye7v.JB5BQlmSS7u', NULL, 1, 'Africa/Abidjan', NULL, 'movingwheelsdelivery', 0, 0, '', 'movingwheelsdelivery', 'cbladmin', 'aQ2hvKYLH4LKWmrA', 'Clientlogo/61c068e7e62d2.png', 'Moving Wheels Delivery', 'Canada', NULL, 1, '46ee7c', NULL, '2021-12-20 11:28:40', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `client_currencies`
--

CREATE TABLE `client_currencies` (
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `is_primary` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `doller_compare` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_currencies`
--

INSERT INTO `client_currencies` (`client_code`, `currency_id`, `is_primary`, `doller_compare`, `created_at`, `updated_at`) VALUES
('46ee7c', 147, 1, '1.0000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_languages`
--

CREATE TABLE `client_languages` (
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `is_primary` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `is_active` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_languages`
--

INSERT INTO `client_languages` (`client_code`, `language_id`, `is_primary`, `is_active`, `created_at`, `updated_at`) VALUES
('46ee7c', 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_preferences`
--

CREATE TABLE `client_preferences` (
  `id` bigint UNSIGNED NOT NULL,
  `business_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cab_booking',
  `rating_check` tinyint DEFAULT '0',
  `enquire_mode` tinyint DEFAULT '0',
  `hide_nav_bar` tinyint NOT NULL DEFAULT '0',
  `show_dark_mode` tinyint NOT NULL DEFAULT '2',
  `show_payment_icons` tinyint NOT NULL DEFAULT '0',
  `loyalty_check` tinyint NOT NULL DEFAULT '0' COMMENT '0-Active, 1-Inactive',
  `show_icons` tinyint NOT NULL DEFAULT '0',
  `show_wishlist` tinyint NOT NULL DEFAULT '0',
  `show_contact_us` tinyint NOT NULL DEFAULT '0',
  `age_restriction` tinyint NOT NULL DEFAULT '0',
  `age_restriction_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_mode` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `cart_enable` tinyint DEFAULT '0',
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_admin` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light' COMMENT 'Light, Dark',
  `distance_unit` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'metric' COMMENT 'metric, imperial',
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `date_format` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y-m-d',
  `time_format` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H:i',
  `fb_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `fb_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `twitter_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `google_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `apple_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Default_location_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Default_latitude` decimal(15,12) NOT NULL DEFAULT '0.000000000000',
  `Default_longitude` decimal(16,12) NOT NULL DEFAULT '0.000000000000',
  `map_provider` bigint UNSIGNED DEFAULT NULL,
  `map_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_provider` bigint UNSIGNED DEFAULT NULL,
  `sms_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_email` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `verify_phone` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `web_template_id` bigint UNSIGNED DEFAULT NULL,
  `app_template_id` bigint UNSIGNED DEFAULT NULL,
  `personal_access_token_v1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_access_token_v2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'smtp',
  `mail_driver` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_host` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_port` smallint DEFAULT NULL,
  `mail_username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_encryption` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_hyperlocal` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `need_delivery_service` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `need_dispacher_ride` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `delivery_service_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_top_header_color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '#4c4c4c',
  `dispatcher_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `celebrity_check` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `delivery_service_key_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_service_key_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reffered_by_amount` decimal(8,2) DEFAULT NULL,
  `reffered_to_amount` decimal(8,2) DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pharmacy_check` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `dinein_check` tinyint DEFAULT '1' COMMENT '0-No, 1-Yes',
  `takeaway_check` tinyint DEFAULT '1' COMMENT '0-No, 1-Yes',
  `delivery_check` tinyint DEFAULT '1' COMMENT '0-No, 1-Yes',
  `pickup_delivery_service_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_delivery_service_key_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_delivery_service_key_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `need_dispacher_home_other_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispacher_home_other_service_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispacher_home_other_service_key_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispacher_home_other_service_key_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_mile_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tip_before_order` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `tip_after_order` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `off_scheduling_at_cart` tinyint(1) DEFAULT '0' COMMENT '0-No, 1-Yes',
  `isolate_single_vendor_order` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `fcm_server_key` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_auth_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_project_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_storage_bucket` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_messaging_sender_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_app_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_measurement_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distance_unit_for_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distance_to_time_multiplier` int UNSIGNED DEFAULT '0',
  `android_app_link` mediumtext COLLATE utf8mb4_unicode_ci,
  `ios_link` mediumtext COLLATE utf8mb4_unicode_ci,
  `single_vendor` tinyint NOT NULL DEFAULT '0',
  `stripe_connect` tinyint NOT NULL DEFAULT '0',
  `need_laundry_service` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `laundry_service_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `laundry_service_key_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `laundry_service_key_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `laundry_pickup_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `laundry_dropoff_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mainly used for orders and place in cc',
  `delay_order` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `gifting` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `product_order_form` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `sms_credentials` json DEFAULT NULL COMMENT 'sms credentials in json format',
  `pickup_delivery_service_area` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `customer_support` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_support_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_support_application_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_mode` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_preferences`
--

INSERT INTO `client_preferences` (`id`, `business_type`, `rating_check`, `enquire_mode`, `hide_nav_bar`, `show_dark_mode`, `show_payment_icons`, `loyalty_check`, `show_icons`, `show_wishlist`, `show_contact_us`, `age_restriction`, `age_restriction_title`, `subscription_mode`, `cart_enable`, `client_code`, `theme_admin`, `distance_unit`, `currency_id`, `language_id`, `date_format`, `time_format`, `fb_login`, `fb_client_id`, `fb_client_secret`, `fb_client_url`, `twitter_login`, `twitter_client_id`, `twitter_client_secret`, `twitter_client_url`, `google_login`, `google_client_id`, `google_client_secret`, `google_client_url`, `apple_login`, `apple_client_id`, `apple_client_secret`, `apple_client_url`, `Default_location_name`, `Default_latitude`, `Default_longitude`, `map_provider`, `map_key`, `map_secret`, `sms_provider`, `sms_key`, `sms_secret`, `sms_from`, `verify_email`, `verify_phone`, `web_template_id`, `app_template_id`, `personal_access_token_v1`, `personal_access_token_v2`, `mail_type`, `mail_driver`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from`, `is_hyperlocal`, `need_delivery_service`, `need_dispacher_ride`, `delivery_service_key`, `primary_color`, `secondary_color`, `site_top_header_color`, `dispatcher_key`, `created_at`, `updated_at`, `celebrity_check`, `delivery_service_key_url`, `delivery_service_key_code`, `reffered_by_amount`, `reffered_to_amount`, `favicon`, `web_color`, `pharmacy_check`, `dinein_check`, `takeaway_check`, `delivery_check`, `pickup_delivery_service_key`, `pickup_delivery_service_key_url`, `pickup_delivery_service_key_code`, `need_dispacher_home_other_service`, `dispacher_home_other_service_key`, `dispacher_home_other_service_key_url`, `dispacher_home_other_service_key_code`, `last_mile_team`, `tip_before_order`, `tip_after_order`, `off_scheduling_at_cart`, `isolate_single_vendor_order`, `fcm_server_key`, `fcm_api_key`, `fcm_auth_domain`, `fcm_project_id`, `fcm_storage_bucket`, `fcm_messaging_sender_id`, `fcm_app_id`, `fcm_measurement_id`, `distance_unit_for_time`, `distance_to_time_multiplier`, `android_app_link`, `ios_link`, `single_vendor`, `stripe_connect`, `need_laundry_service`, `laundry_service_key`, `laundry_service_key_url`, `laundry_service_key_code`, `laundry_pickup_team`, `laundry_dropoff_team`, `admin_email`, `delay_order`, `gifting`, `product_order_form`, `sms_credentials`, `pickup_delivery_service_area`, `customer_support`, `customer_support_key`, `customer_support_application_id`, `shipping_mode`) VALUES
(1, 'super_app', 1, 0, 0, 2, 1, 0, 1, 1, 1, 0, NULL, 0, 1, '46ee7c', 'light', 'metric', NULL, NULL, 'YYYY-MM-DD', '24', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'Chandigarh, Punjab, India', '30.538994400000', '75.955032900000', 1, 'AIzaSyCwLPzcHGY93MND4Qm6ShNn2KOGZGoiSrM', NULL, 1, 'AC2d20ec147884c2bce6e926bdbe5fd963', '1c649b9207c16c58cd610654ac81025f', '+17206132646', 0, 0, 1, 2, NULL, NULL, 'smtp', 'smtp', 'smtp.mailgun.org', 587, 'noreply@royoorders2.com', '18e25a65e96e41e66a211b685559e3ac-aff8aa95-1bafdc0a', 'tls', 'sales@royoorders.com', 0, 1, 1, '96vQasLRhsNeBILYWa8KOUs7ICm0OH', '#32B5FC', '#41A2E6', '#4B7025', NULL, NULL, '2021-12-20 11:30:50', 0, 'https://movingwheelsdelivery.rdstaging.com', '4d7b01', NULL, NULL, 'favicon/OABoE0zQ5V6rjWyUybM6rVbpWJg3pBEzlXMS9MXi.ico', '#669933', 0, 1, 1, 1, '96vQasLRhsNeBILYWa8KOUs7ICm0OH', 'https://movingwheelsdelivery.rdstaging.com', '4d7b01', '1', '96vQasLRhsNeBILYWa8KOUs7ICm0OH', 'https://movingwheelsdelivery.rdstaging.com', '4d7b01', NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '{\"sms_key\": \"AC2d20ec147884c2bce6e926bdbe5fd963\", \"sms_from\": \"+17206132646\", \"sms_secret\": \"1c649b9207c16c58cd610654ac81025f\"}', 0, 'zen_desk', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms`
--

CREATE TABLE `cms` (
  `id` bigint UNSIGNED NOT NULL,
  `sorting` smallint NOT NULL DEFAULT '1',
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html_content` longtext COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` longtext COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext COLLATE utf8mb4_unicode_ci,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - active, 0 - pending, 2 - blocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(56) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nicename` varchar(56) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso3` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numcode` int DEFAULT NULL,
  `phonecode` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`, `created_at`, `updated_at`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93, NULL, NULL),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355, NULL, NULL),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213, NULL, NULL),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684, NULL, NULL),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376, NULL, NULL),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244, NULL, NULL),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264, NULL, NULL),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0, NULL, NULL),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268, NULL, NULL),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54, NULL, NULL),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374, NULL, NULL),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297, NULL, NULL),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61, NULL, NULL),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43, NULL, NULL),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994, NULL, NULL),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242, NULL, NULL),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973, NULL, NULL),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880, NULL, NULL),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246, NULL, NULL),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375, NULL, NULL),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32, NULL, NULL),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501, NULL, NULL),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229, NULL, NULL),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441, NULL, NULL),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975, NULL, NULL),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591, NULL, NULL),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387, NULL, NULL),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267, NULL, NULL),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0, NULL, NULL),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55, NULL, NULL),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246, NULL, NULL),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673, NULL, NULL),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359, NULL, NULL),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226, NULL, NULL),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257, NULL, NULL),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855, NULL, NULL),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237, NULL, NULL),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1, NULL, NULL),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238, NULL, NULL),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345, NULL, NULL),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236, NULL, NULL),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235, NULL, NULL),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56, NULL, NULL),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86, NULL, NULL),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61, NULL, NULL),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672, NULL, NULL),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57, NULL, NULL),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269, NULL, NULL),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242, NULL, NULL),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242, NULL, NULL),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682, NULL, NULL),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506, NULL, NULL),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225, NULL, NULL),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385, NULL, NULL),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53, NULL, NULL),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357, NULL, NULL),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420, NULL, NULL),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45, NULL, NULL),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253, NULL, NULL),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767, NULL, NULL),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809, NULL, NULL),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593, NULL, NULL),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20, NULL, NULL),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503, NULL, NULL),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240, NULL, NULL),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291, NULL, NULL),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372, NULL, NULL),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251, NULL, NULL),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500, NULL, NULL),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298, NULL, NULL),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679, NULL, NULL),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358, NULL, NULL),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33, NULL, NULL),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594, NULL, NULL),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689, NULL, NULL),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0, NULL, NULL),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241, NULL, NULL),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220, NULL, NULL),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995, NULL, NULL),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49, NULL, NULL),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233, NULL, NULL),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350, NULL, NULL),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30, NULL, NULL),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299, NULL, NULL),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473, NULL, NULL),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590, NULL, NULL),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671, NULL, NULL),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502, NULL, NULL),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224, NULL, NULL),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245, NULL, NULL),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592, NULL, NULL),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509, NULL, NULL),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0, NULL, NULL),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39, NULL, NULL),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504, NULL, NULL),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852, NULL, NULL),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36, NULL, NULL),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354, NULL, NULL),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91, NULL, NULL),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62, NULL, NULL),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98, NULL, NULL),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964, NULL, NULL),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353, NULL, NULL),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972, NULL, NULL),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39, NULL, NULL),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876, NULL, NULL),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81, NULL, NULL),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962, NULL, NULL),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7, NULL, NULL),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254, NULL, NULL),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686, NULL, NULL),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850, NULL, NULL),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82, NULL, NULL),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965, NULL, NULL),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996, NULL, NULL),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856, NULL, NULL),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371, NULL, NULL),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961, NULL, NULL),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266, NULL, NULL),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231, NULL, NULL),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218, NULL, NULL),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423, NULL, NULL),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370, NULL, NULL),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352, NULL, NULL),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853, NULL, NULL),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389, NULL, NULL),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261, NULL, NULL),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265, NULL, NULL),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60, NULL, NULL),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960, NULL, NULL),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223, NULL, NULL),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356, NULL, NULL),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692, NULL, NULL),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596, NULL, NULL),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222, NULL, NULL),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230, NULL, NULL),
(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269, NULL, NULL),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52, NULL, NULL),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691, NULL, NULL),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373, NULL, NULL),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377, NULL, NULL),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976, NULL, NULL),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664, NULL, NULL),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212, NULL, NULL),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258, NULL, NULL),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95, NULL, NULL),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264, NULL, NULL),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674, NULL, NULL),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977, NULL, NULL),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31, NULL, NULL),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599, NULL, NULL),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687, NULL, NULL),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64, NULL, NULL),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505, NULL, NULL),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227, NULL, NULL),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234, NULL, NULL),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683, NULL, NULL),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672, NULL, NULL),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670, NULL, NULL),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47, NULL, NULL),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968, NULL, NULL),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92, NULL, NULL),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680, NULL, NULL),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970, NULL, NULL),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507, NULL, NULL),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675, NULL, NULL),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595, NULL, NULL),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51, NULL, NULL),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63, NULL, NULL),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0, NULL, NULL),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48, NULL, NULL),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351, NULL, NULL),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787, NULL, NULL),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974, NULL, NULL),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262, NULL, NULL),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40, NULL, NULL),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70, NULL, NULL),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250, NULL, NULL),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290, NULL, NULL),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869, NULL, NULL),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758, NULL, NULL),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508, NULL, NULL),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784, NULL, NULL),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684, NULL, NULL),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378, NULL, NULL),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239, NULL, NULL),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966, NULL, NULL),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221, NULL, NULL),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, 381, NULL, NULL),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248, NULL, NULL),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232, NULL, NULL),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65, NULL, NULL),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421, NULL, NULL),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386, NULL, NULL),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677, NULL, NULL),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252, NULL, NULL),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27, NULL, NULL),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0, NULL, NULL),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34, NULL, NULL),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94, NULL, NULL),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249, NULL, NULL),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597, NULL, NULL),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47, NULL, NULL),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268, NULL, NULL),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46, NULL, NULL),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41, NULL, NULL),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963, NULL, NULL),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886, NULL, NULL),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992, NULL, NULL),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255, NULL, NULL),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66, NULL, NULL),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670, NULL, NULL),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228, NULL, NULL),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690, NULL, NULL),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676, NULL, NULL),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868, NULL, NULL),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216, NULL, NULL),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90, NULL, NULL),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370, NULL, NULL),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649, NULL, NULL),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688, NULL, NULL),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256, NULL, NULL),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380, NULL, NULL),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971, NULL, NULL),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44, NULL, NULL),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1, NULL, NULL),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL, 1, NULL, NULL),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598, NULL, NULL),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998, NULL, NULL),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678, NULL, NULL),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58, NULL, NULL),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84, NULL, NULL),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284, NULL, NULL),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340, NULL, NULL),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681, NULL, NULL),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212, NULL, NULL),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967, NULL, NULL),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260, NULL, NULL),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263, NULL, NULL),
(240, 'RS', 'SERBIA', 'Serbia', 'SRB', 688, 381, NULL, NULL),
(241, 'AP', 'ASIA PACIFIC REGION', 'Asia / Pacific Region', '0', 0, 0, NULL, NULL),
(242, 'ME', 'MONTENEGRO', 'Montenegro', 'MNE', 499, 382, NULL, NULL),
(243, 'AX', 'ALAND ISLANDS', 'Aland Islands', 'ALA', 248, 358, NULL, NULL),
(244, 'BQ', 'BONAIRE, SINT EUSTATIUS AND SABA', 'Bonaire, Sint Eustatius and Saba', 'BES', 535, 599, NULL, NULL),
(245, 'CW', 'CURACAO', 'Curacao', 'CUW', 531, 599, NULL, NULL),
(246, 'GG', 'GUERNSEY', 'Guernsey', 'GGY', 831, 44, NULL, NULL),
(247, 'IM', 'ISLE OF MAN', 'Isle of Man', 'IMN', 833, 44, NULL, NULL),
(248, 'JE', 'JERSEY', 'Jersey', 'JEY', 832, 44, NULL, NULL),
(249, 'XK', 'KOSOVO', 'Kosovo', '---', 0, 381, NULL, NULL),
(250, 'BL', 'SAINT BARTHELEMY', 'Saint Barthelemy', 'BLM', 652, 590, NULL, NULL),
(251, 'MF', 'SAINT MARTIN', 'Saint Martin', 'MAF', 663, 590, NULL, NULL),
(252, 'SX', 'SINT MAARTEN', 'Sint Maarten', 'SXM', 534, 1, NULL, NULL),
(253, 'SS', 'SOUTH SUDAN', 'South Sudan', 'SSD', 728, 211, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `csv_product_imports`
--

CREATE TABLE `csv_product_imports` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `type` tinyint DEFAULT '0' COMMENT '0 for csv, 1 for woocommerce',
  `status` tinyint DEFAULT NULL COMMENT '1-Pending, 2-Success, 3-Failed, 4-In-progress',
  `raw_data` json DEFAULT NULL,
  `error` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `csv_vendor_imports`
--

CREATE TABLE `csv_vendor_imports` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint DEFAULT NULL COMMENT '1-Pending, 2-Success, 3-Failed, 4-In-progress',
  `error` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `iso_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subunit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subunit_to_unit` int NOT NULL,
  `symbol_first` tinyint NOT NULL,
  `html_entity` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_mark` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thousands_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_numeric` smallint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `priority`, `iso_code`, `symbol`, `subunit`, `subunit_to_unit`, `symbol_first`, `html_entity`, `decimal_mark`, `thousands_separator`, `iso_numeric`, `created_at`, `updated_at`) VALUES
(1, 'United Arab Emirates Dirham', 100, 'AED', 'د.إ', 'Fils', 100, 1, '', '.', ',', 784, NULL, NULL),
(2, 'Afghan Afghani', 100, 'AFN', '؋', 'Pul', 100, 0, '', '.', ',', 971, NULL, NULL),
(3, 'Albanian Lek', 100, 'ALL', 'L', 'Qintar', 100, 0, '', '.', ',', 8, NULL, NULL),
(4, 'Armenian Dram', 100, 'AMD', 'դր.', 'Luma', 100, 0, '', '.', ',', 51, NULL, NULL),
(5, 'Netherlands Antillean Gulden', 100, 'ANG', 'ƒ', 'Cent', 100, 1, '&#x0192;', ',', '.', 532, NULL, NULL),
(6, 'Angolan Kwanza', 100, 'AOA', 'Kz', 'Cêntimo', 100, 0, '', '.', ',', 973, NULL, NULL),
(7, 'Argentine Peso', 100, 'ARS', '$', 'Centavo', 100, 1, '&#x20B1;', ',', '.', 32, NULL, NULL),
(8, 'Australian Dollar', 4, 'AUD', '$', 'Cent', 100, 1, '$', '.', ',', 36, NULL, NULL),
(9, 'Aruban Florin', 100, 'AWG', 'ƒ', 'Cent', 100, 0, '&#x0192;', '.', ',', 533, NULL, NULL),
(10, 'Azerbaijani Manat', 100, 'AZN', 'null', 'Qəpik', 100, 1, '', '.', ',', 944, NULL, NULL),
(11, 'Bosnia and Herzegovina Convertible Mark', 100, 'BAM', 'КМ', 'Fening', 100, 1, '', '.', ',', 977, NULL, NULL),
(12, 'Barbadian Dollar', 100, 'BBD', '$', 'Cent', 100, 0, '$', '.', ',', 52, NULL, NULL),
(13, 'Bangladeshi Taka', 100, 'BDT', '৳', 'Paisa', 100, 1, '', '.', ',', 50, NULL, NULL),
(14, 'Bulgarian Lev', 100, 'BGN', 'лв', 'Stotinka', 100, 0, '', '.', ',', 975, NULL, NULL),
(15, 'Bahraini Dinar', 100, 'BHD', 'ب.د', 'Fils', 1000, 1, '', '.', ',', 48, NULL, NULL),
(16, 'Burundian Franc', 100, 'BIF', 'Fr', 'Centime', 100, 0, '', '.', ',', 108, NULL, NULL),
(17, 'Bermudian Dollar', 100, 'BMD', '$', 'Cent', 100, 1, '$', '.', ',', 60, NULL, NULL),
(18, 'Brunei Dollar', 100, 'BND', '$', 'Sen', 100, 1, '$', '.', ',', 96, NULL, NULL),
(19, 'Bolivian Boliviano', 100, 'BOB', 'Bs.', 'Centavo', 100, 1, '', '.', ',', 68, NULL, NULL),
(20, 'Brazilian Real', 100, 'BRL', 'R$', 'Centavo', 100, 1, 'R$', ',', '.', 986, NULL, NULL),
(21, 'Bahamian Dollar', 100, 'BSD', '$', 'Cent', 100, 1, '$', '.', ',', 44, NULL, NULL),
(22, 'Bhutanese Ngultrum', 100, 'BTN', 'Nu.', 'Chertrum', 100, 0, '', '.', ',', 64, NULL, NULL),
(23, 'Botswana Pula', 100, 'BWP', 'P', 'Thebe', 100, 1, '', '.', ',', 72, NULL, NULL),
(24, 'Belarusian Ruble', 100, 'BYR', 'Br', 'Kapyeyka', 100, 0, '', '.', ',', 974, NULL, NULL),
(25, 'Belize Dollar', 100, 'BZD', '$', 'Cent', 100, 1, '$', '.', ',', 84, NULL, NULL),
(26, 'Canadian Dollar', 5, 'CAD', '$', 'Cent', 100, 1, '$', '.', ',', 124, NULL, NULL),
(27, 'Congolese Franc', 100, 'CDF', 'Fr', 'Centime', 100, 0, '', '.', ',', 976, NULL, NULL),
(28, 'Swiss Franc', 100, 'CHF', 'Fr', 'Rappen', 100, 1, '', '.', ',', 756, NULL, NULL),
(29, 'Unidad de Fomento', 100, 'CLF', 'UF', 'Peso', 1, 1, '&#x20B1;', ',', '.', 990, NULL, NULL),
(30, 'Chilean Peso', 100, 'CLP', '$', 'Peso', 1, 1, '&#36;', ',', '.', 152, NULL, NULL),
(31, 'Chinese Renminbi Yuan', 100, 'CNY', '¥', 'Fen', 100, 1, '&#20803;', '.', ',', 156, NULL, NULL),
(32, 'Colombian Peso', 100, 'COP', '$', 'Centavo', 100, 1, '&#x20B1;', ',', '.', 170, NULL, NULL),
(33, 'Costa Rican Colón', 100, 'CRC', '₡', 'Céntimo', 100, 1, '&#x20A1;', ',', '.', 188, NULL, NULL),
(34, 'Cuban Convertible Peso', 100, 'CUC', '$', 'Centavo', 100, 0, '', '.', ',', 931, NULL, NULL),
(35, 'Cuban Peso', 100, 'CUP', '$', 'Centavo', 100, 1, '&#x20B1;', '.', ',', 192, NULL, NULL),
(36, 'Cape Verdean Escudo', 100, 'CVE', '$', 'Centavo', 100, 0, '', '.', ',', 132, NULL, NULL),
(37, 'Czech Koruna', 100, 'CZK', 'Kč', 'Haléř', 100, 1, '', ',', '.', 203, NULL, NULL),
(38, 'Djiboutian Franc', 100, 'DJF', 'Fdj', 'Centime', 100, 0, '', '.', ',', 262, NULL, NULL),
(39, 'Danish Krone', 100, 'DKK', 'kr', 'Øre', 100, 0, '', ',', '.', 208, NULL, NULL),
(40, 'Dominican Peso', 100, 'DOP', '$', 'Centavo', 100, 1, '&#x20B1;', '.', ',', 214, NULL, NULL),
(41, 'Algerian Dinar', 100, 'DZD', 'د.ج', 'Centime', 100, 0, '', '.', ',', 12, NULL, NULL),
(42, 'Egyptian Pound', 100, 'EGP', 'ج.م', 'Piastre', 100, 1, '&#x00A3;', '.', ',', 818, NULL, NULL),
(43, 'Eritrean Nakfa', 100, 'ERN', 'Nfk', 'Cent', 100, 0, '', '.', ',', 232, NULL, NULL),
(44, 'Ethiopian Birr', 100, 'ETB', 'Br', 'Santim', 100, 0, '', '.', ',', 230, NULL, NULL),
(45, 'Euro', 2, 'EUR', '€', 'Cent', 100, 1, '&#x20AC;', ',', '.', 978, NULL, NULL),
(46, 'Fijian Dollar', 100, 'FJD', '$', 'Cent', 100, 0, '$', '.', ',', 242, NULL, NULL),
(47, 'Falkland Pound', 100, 'FKP', '£', 'Penny', 100, 0, '&#x00A3;', '.', ',', 238, NULL, NULL),
(48, 'British Pound', 3, 'GBP', '£', 'Penny', 100, 1, '&#x00A3;', '.', ',', 826, NULL, NULL),
(49, 'Georgian Lari', 100, 'GEL', 'ლ', 'Tetri', 100, 0, '', '.', ',', 981, NULL, NULL),
(50, 'Ghanaian Cedi', 100, 'GHS', '₵', 'Pesewa', 100, 1, '&#x20B5;', '.', ',', 936, NULL, NULL),
(51, 'Gibraltar Pound', 100, 'GIP', '£', 'Penny', 100, 1, '&#x00A3;', '.', ',', 292, NULL, NULL),
(52, 'Gambian Dalasi', 100, 'GMD', 'D', 'Butut', 100, 0, '', '.', ',', 270, NULL, NULL),
(53, 'Guinean Franc', 100, 'GNF', 'Fr', 'Centime', 100, 0, '', '.', ',', 324, NULL, NULL),
(54, 'Guatemalan Quetzal', 100, 'GTQ', 'Q', 'Centavo', 100, 1, '', '.', ',', 320, NULL, NULL),
(55, 'Guyanese Dollar', 100, 'GYD', '$', 'Cent', 100, 0, '$', '.', ',', 328, NULL, NULL),
(56, 'Hong Kong Dollar', 100, 'HKD', '$', 'Cent', 100, 1, '$', '.', ',', 344, NULL, NULL),
(57, 'Honduran Lempira', 100, 'HNL', 'L', 'Centavo', 100, 1, '', '.', ',', 340, NULL, NULL),
(58, 'Croatian Kuna', 100, 'HRK', 'kn', 'Lipa', 100, 1, '', ',', '.', 191, NULL, NULL),
(59, 'Haitian Gourde', 100, 'HTG', 'G', 'Centime', 100, 0, '', '.', ',', 332, NULL, NULL),
(60, 'Hungarian Forint', 100, 'HUF', 'Ft', 'Fillér', 100, 0, '', ',', '.', 348, NULL, NULL),
(61, 'Indonesian Rupiah', 100, 'IDR', 'Rp', 'Sen', 100, 1, '', ',', '.', 360, NULL, NULL),
(62, 'Israeli New Sheqel', 100, 'ILS', '₪', 'Agora', 100, 1, '&#x20AA;', '.', ',', 376, NULL, NULL),
(63, 'Indian Rupee', 100, 'INR', '₹', 'Paisa', 100, 1, '&#x20b9;', '.', ',', 356, NULL, NULL),
(64, 'Iraqi Dinar', 100, 'IQD', 'ع.د', 'Fils', 1000, 0, '', '.', ',', 368, NULL, NULL),
(65, 'Iranian Rial', 100, 'IRR', '﷼', 'Dinar', 100, 1, '&#xFDFC;', '.', ',', 364, NULL, NULL),
(66, 'Icelandic Króna', 100, 'ISK', 'kr', 'Eyrir', 100, 1, '', ',', '.', 352, NULL, NULL),
(67, 'Jamaican Dollar', 100, 'JMD', '$', 'Cent', 100, 1, '$', '.', ',', 388, NULL, NULL),
(68, 'Jordanian Dinar', 100, 'JOD', 'د.ا', 'Piastre', 100, 1, '', '.', ',', 400, NULL, NULL),
(69, 'Japanese Yen', 6, 'JPY', '¥', 'null', 1, 1, '&#x00A5;', '.', ',', 392, NULL, NULL),
(70, 'Kenyan Shilling', 100, 'KES', 'KSh', 'Cent', 100, 1, '', '.', ',', 404, NULL, NULL),
(71, 'Kyrgyzstani Som', 100, 'KGS', 'som', 'Tyiyn', 100, 0, '', '.', ',', 417, NULL, NULL),
(72, 'Cambodian Riel', 100, 'KHR', '៛', 'Sen', 100, 0, '&#x17\\DB;', '.', ',', 116, NULL, NULL),
(73, 'Comorian Franc', 100, 'KMF', 'Fr', 'Centime', 100, 0, '', '.', ',', 174, NULL, NULL),
(74, 'North Korean Won', 100, 'KPW', '₩', 'Chŏn', 100, 0, '&#x20A9;', '.', ',', 408, NULL, NULL),
(75, 'South Korean Won', 100, 'KRW', '₩', 'null', 1, 1, '&#x20A9;', '.', ',', 410, NULL, NULL),
(76, 'Kuwaiti Dinar', 100, 'KWD', 'د.ك', 'Fils', 1000, 1, '', '.', ',', 414, NULL, NULL),
(77, 'Cayman Islands Dollar', 100, 'KYD', '$', 'Cent', 100, 1, '$', '.', ',', 136, NULL, NULL),
(78, 'Kazakhstani Tenge', 100, 'KZT', '〒', 'Tiyn', 100, 0, '', '.', ',', 398, NULL, NULL),
(79, 'Lao Kip', 100, 'LAK', '₭', 'Att', 100, 0, '&#x20AD;', '.', ',', 418, NULL, NULL),
(80, 'Lebanese Pound', 100, 'LBP', 'ل.ل', 'Piastre', 100, 1, '&#x00A3;', '.', ',', 422, NULL, NULL),
(81, 'Sri Lankan Rupee', 100, 'LKR', '₨', 'Cent', 100, 0, '&#x0BF9;', '.', ',', 144, NULL, NULL),
(82, 'Liberian Dollar', 100, 'LRD', '$', 'Cent', 100, 0, '$', '.', ',', 430, NULL, NULL),
(83, 'Lesotho Loti', 100, 'LSL', 'L', 'Sente', 100, 0, '', '.', ',', 426, NULL, NULL),
(84, 'Lithuanian Litas', 100, 'LTL', 'Lt', 'Centas', 100, 0, '', '.', ',', 440, NULL, NULL),
(85, 'Latvian Lats', 100, 'LVL', 'Ls', 'Santīms', 100, 1, '', '.', ',', 428, NULL, NULL),
(86, 'Libyan Dinar', 100, 'LYD', 'ل.د', 'Dirham', 1000, 0, '', '.', ',', 434, NULL, NULL),
(87, 'Moroccan Dirham', 100, 'MAD', 'د.م.', 'Centime', 100, 0, '', '.', ',', 504, NULL, NULL),
(88, 'Moldovan Leu', 100, 'MDL', 'L', 'Ban', 100, 0, '', '.', ',', 498, NULL, NULL),
(89, 'Malagasy Ariary', 100, 'MGA', 'Ar', 'Iraimbilanja', 5, 1, '', '.', ',', 969, NULL, NULL),
(90, 'Macedonian Denar', 100, 'MKD', 'ден', 'Deni', 100, 0, '', '.', ',', 807, NULL, NULL),
(91, 'Myanmar Kyat', 100, 'MMK', 'K', 'Pya', 100, 0, '', '.', ',', 104, NULL, NULL),
(92, 'Mongolian Tögrög', 100, 'MNT', '₮', 'Möngö', 100, 0, '&#x20AE;', '.', ',', 496, NULL, NULL),
(93, 'Macanese Pataca', 100, 'MOP', 'P', 'Avo', 100, 0, '', '.', ',', 446, NULL, NULL),
(94, 'Mauritanian Ouguiya', 100, 'MRO', 'UM', 'Khoums', 5, 0, '', '.', ',', 478, NULL, NULL),
(95, 'Mauritian Rupee', 100, 'MUR', '₨', 'Cent', 100, 1, '&#x20A8;', '.', ',', 480, NULL, NULL),
(96, 'Maldivian Rufiyaa', 100, 'MVR', 'MVR', 'Laari', 100, 0, '', '.', ',', 462, NULL, NULL),
(97, 'Malawian Kwacha', 100, 'MWK', 'MK', 'Tambala', 100, 0, '', '.', ',', 454, NULL, NULL),
(98, 'Mexican Peso', 100, 'MXN', '$', 'Centavo', 100, 1, '$', '.', ',', 484, NULL, NULL),
(99, 'Malaysian Ringgit', 100, 'MYR', 'RM', 'Sen', 100, 1, '', '.', ',', 458, NULL, NULL),
(100, 'Mozambican Metical', 100, 'MZN', 'MTn', 'Centavo', 100, 1, '', ',', '.', 943, NULL, NULL),
(101, 'Namibian Dollar', 100, 'NAD', '$', 'Cent', 100, 0, '$', '.', ',', 516, NULL, NULL),
(102, 'Nigerian Naira', 100, 'NGN', '₦', 'Kobo', 100, 0, '&#x20A6;', '.', ',', 566, NULL, NULL),
(103, 'Nicaraguan Córdoba', 100, 'NIO', 'C$', 'Centavo', 100, 0, '', '.', ',', 558, NULL, NULL),
(104, 'Norwegian Krone', 100, 'NOK', 'kr', 'Øre', 100, 1, 'kr', ',', '.', 578, NULL, NULL),
(105, 'Nepalese Rupee', 100, 'NPR', '₨', 'Paisa', 100, 1, '&#x20A8;', '.', ',', 524, NULL, NULL),
(106, 'New Zealand Dollar', 100, 'NZD', '$', 'Cent', 100, 1, '$', '.', ',', 554, NULL, NULL),
(107, 'Omani Rial', 100, 'OMR', 'ر.ع.', 'Baisa', 1000, 1, '&#xFDFC;', '.', ',', 512, NULL, NULL),
(108, 'Panamanian Balboa', 100, 'PAB', 'B/.', 'Centésimo', 100, 0, '', '.', ',', 590, NULL, NULL),
(109, 'Peruvian Nuevo Sol', 100, 'PEN', 'S/.', 'Céntimo', 100, 1, 'S/.', '.', ',', 604, NULL, NULL),
(110, 'Papua New Guinean Kina', 100, 'PGK', 'K', 'Toea', 100, 0, '', '.', ',', 598, NULL, NULL),
(111, 'Philippine Peso', 100, 'PHP', '₱', 'Centavo', 100, 1, '&#x20B1;', '.', ',', 608, NULL, NULL),
(112, 'Pakistani Rupee', 100, 'PKR', '₨', 'Paisa', 100, 1, '&#x20A8;', '.', ',', 586, NULL, NULL),
(113, 'Polish Złoty', 100, 'PLN', 'zł', 'Grosz', 100, 0, 'z&#322;', ',', '', 985, NULL, NULL),
(114, 'Paraguayan Guaraní', 100, 'PYG', '₲', 'Céntimo', 100, 1, '&#x20B2;', '.', ',', 600, NULL, NULL),
(115, 'Qatari Riyal', 100, 'QAR', 'ر.ق', 'Dirham', 100, 0, '&#xFDFC;', '.', ',', 634, NULL, NULL),
(116, 'Romanian Leu', 100, 'RON', 'Lei', 'Bani', 100, 1, '', ',', '.', 946, NULL, NULL),
(117, 'Serbian Dinar', 100, 'RSD', 'РСД', 'Para', 100, 1, '', '.', ',', 941, NULL, NULL),
(118, 'Russian Ruble', 100, 'RUB', 'р.', 'Kopek', 100, 0, '&#x0440;&#x0443;&#x0431;', ',', '.', 643, NULL, NULL),
(119, 'Rwandan Franc', 100, 'RWF', 'FRw', 'Centime', 100, 0, '', '.', ',', 646, NULL, NULL),
(120, 'Saudi Riyal', 100, 'SAR', 'ر.س', 'Hallallah', 100, 1, '&#xFDFC;', '.', ',', 682, NULL, NULL),
(121, 'Solomon Islands Dollar', 100, 'SBD', '$', 'Cent', 100, 0, '$', '.', ',', 90, NULL, NULL),
(122, 'Seychellois Rupee', 100, 'SCR', '₨', 'Cent', 100, 0, '&#x20A8;', '.', ',', 690, NULL, NULL),
(123, 'Sudanese Pound', 100, 'SDG', '£', 'Piastre', 100, 1, '', '.', ',', 938, NULL, NULL),
(124, 'Swedish Krona', 100, 'SEK', 'kr', 'Öre', 100, 0, '', ',', '', 752, NULL, NULL),
(125, 'Singapore Dollar', 100, 'SGD', '$', 'Cent', 100, 1, '$', '.', ',', 702, NULL, NULL),
(126, 'Saint Helenian Pound', 100, 'SHP', '£', 'Penny', 100, 0, '&#x00A3;', '.', ',', 654, NULL, NULL),
(127, 'Slovak Koruna', 100, 'SKK', 'Sk', 'Halier', 100, 1, '', '.', ',', 703, NULL, NULL),
(128, 'Sierra Leonean Leone', 100, 'SLL', 'Le', 'Cent', 100, 0, '', '.', ',', 694, NULL, NULL),
(129, 'Somali Shilling', 100, 'SOS', 'Sh', 'Cent', 100, 0, '', '.', ',', 706, NULL, NULL),
(130, 'Surinamese Dollar', 100, 'SRD', '$', 'Cent', 100, 0, '', '.', ',', 968, NULL, NULL),
(131, 'South Sudanese Pound', 100, 'SSP', '£', 'piaster', 100, 0, '&#x00A3;', '.', ',', 728, NULL, NULL),
(132, 'São Tomé and Príncipe Dobra', 100, 'STD', '\\DB', 'Cêntimo', 100, 0, '', '.', ',', 678, NULL, NULL),
(133, 'Salvadoran Colón', 100, 'SVC', '₡', 'Centavo', 100, 1, '&#x20A1;', '.', ',', 222, NULL, NULL),
(134, 'Syrian Pound', 100, 'SYP', '£S', 'Piastre', 100, 0, '&#x00A3;', '.', ',', 760, NULL, NULL),
(135, 'Swazi Lilangeni', 100, 'SZL', 'L', 'Cent', 100, 1, '', '.', ',', 748, NULL, NULL),
(136, 'Thai Baht', 100, 'THB', '฿', 'Satang', 100, 1, '&#x0E3F;', '.', ',', 764, NULL, NULL),
(137, 'Tajikistani Somoni', 100, 'TJS', 'ЅМ', 'Diram', 100, 0, '', '.', ',', 972, NULL, NULL),
(138, 'Turkmenistani Manat', 100, 'TMT', 'T', 'Tenge', 100, 0, '', '.', ',', 934, NULL, NULL),
(139, 'Tunisian Dinar', 100, 'TND', 'د.ت', 'Millime', 1000, 0, '', '.', ',', 788, NULL, NULL),
(140, 'Tongan Paʻanga', 100, 'TOP', 'T$', 'Seniti', 100, 1, '', '.', ',', 776, NULL, NULL),
(141, 'Turkish Lira', 100, 'TRY', 'TL', 'kuruş', 100, 0, '', ',', '.', 949, NULL, NULL),
(142, 'Trinidad and Tobago Dollar', 100, 'TTD', '$', 'Cent', 100, 0, '$', '.', ',', 780, NULL, NULL),
(143, 'New Taiwan Dollar', 100, 'TWD', '$', 'Cent', 100, 1, '$', '.', ',', 901, NULL, NULL),
(144, 'Tanzanian Shilling', 100, 'TZS', 'Sh', 'Cent', 100, 1, '', '.', ',', 834, NULL, NULL),
(145, 'Ukrainian Hryvnia', 100, 'UAH', '₴', 'Kopiyka', 100, 0, '&#x20B4;', '.', ',', 980, NULL, NULL),
(146, 'Ugandan Shilling', 100, 'UGX', 'USh', 'Cent', 100, 0, '', '.', ',', 800, NULL, NULL),
(147, 'United States Dollar', 1, 'USD', '$', 'Cent', 100, 1, '$', '.', ',', 840, NULL, NULL),
(148, 'Uruguayan Peso', 100, 'UYU', '$', 'Centésimo', 100, 1, '&#x20B1;', ',', '.', 858, NULL, NULL),
(149, 'Uzbekistani Som', 100, 'UZS', 'null', 'Tiyin', 100, 0, '', '.', ',', 860, NULL, NULL),
(150, 'Venezuelan Bolívar', 100, 'VEF', 'Bs F', 'Céntimo', 100, 1, '', ',', '.', 937, NULL, NULL),
(151, 'Vietnamese Đồng', 100, 'VND', '₫', 'Hào', 10, 1, '&#x20AB;', ',', '.', 704, NULL, NULL),
(152, 'Vanuatu Vatu', 100, 'VUV', 'Vt', 'null', 1, 1, '', '.', ',', 548, NULL, NULL),
(153, 'Samoan Tala', 100, 'WST', 'T', 'Sene', 100, 0, '', '.', ',', 882, NULL, NULL),
(154, 'Central African Cfa Franc', 100, 'XAF', 'Fr', 'Centime', 100, 0, '', '.', ',', 950, NULL, NULL),
(155, 'Silver (Troy Ounce)', 100, 'XAG', 'oz t', 'oz', 1, 0, '', '.', ',', 961, NULL, NULL),
(156, 'Gold (Troy Ounce)', 100, 'XAU', 'oz t', 'oz', 1, 0, '', '.', ',', 959, NULL, NULL),
(157, 'East Caribbean Dollar', 100, 'XCD', '$', 'Cent', 100, 1, '$', '.', ',', 951, NULL, NULL),
(158, 'Special Drawing Rights', 100, 'XDR', 'SDR', '', 1, 0, '$', '.', ',', 960, NULL, NULL),
(159, 'West African Cfa Franc', 100, 'XOF', 'Fr', 'Centime', 100, 0, '', '.', ',', 952, NULL, NULL),
(160, 'Cfp Franc', 100, 'XPF', 'Fr', 'Centime', 100, 0, '', '.', ',', 953, NULL, NULL),
(161, 'Yemeni Rial', 100, 'YER', '﷼', 'Fils', 100, 0, '&#xFDFC;', '.', ',', 886, NULL, NULL),
(162, 'South African Rand', 100, 'ZAR', 'R', 'Cent', 100, 1, '&#x0052;', '.', ',', 710, NULL, NULL),
(163, 'Zambian Kwacha', 100, 'ZMK', 'ZK', 'Ngwee', 100, 0, '', '.', ',', 894, NULL, NULL),
(164, 'Zambian Kwacha', 100, 'ZMW', 'ZK', 'Ngwee', 100, 0, '', '.', ',', 967, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dispatcher_status_options`
--

CREATE TABLE `dispatcher_status_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1 - for order, 2 - fordispatch',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 - active, 0 - inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispatcher_status_options`
--

INSERT INTO `dispatcher_status_options` (`id`, `title`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Created', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(2, 'Assigned', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(3, 'Started', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(4, 'Arrived', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(5, 'Completed', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `dispatcher_template_type_options`
--

CREATE TABLE `dispatcher_template_type_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 for inactive and 1 for active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispatcher_warning_pages`
--

CREATE TABLE `dispatcher_warning_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 for inactive and 1 for active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_registration_documents`
--

CREATE TABLE `driver_registration_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_registration_document_translations`
--

CREATE TABLE `driver_registration_document_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `driver_registration_document_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `tags` mediumtext COLLATE utf8mb4_unicode_ci,
  `label` mediumtext COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `subject` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `slug`, `tags`, `label`, `content`, `subject`, `created_at`, `updated_at`) VALUES
(1, 'new-vendor-signup', '{vendor_name}, {title}, {description}, {email}, {phone_no}, {address},{website}', 'New Vendor Signup', '<tbody><tr><td><div style=\"margin-bottom: 20px;\"><h4 style=\"margin-bottom: 5px;\">Name</h4><p>{vendor_name}</p></div><div style=\"margin-bottom: 20px;\"><h4 style=\"margin-bottom: 5px;\">Title</h4><p>{title}</p></div><div style=\"margin-bottom: 20px;\"><h4 style=\"margin-bottom: 5px;\">Description</h4><p>{description}</p></div><div style=\"margin-bottom: 20px;\"><h4 style=\"margin-bottom: 5px;\">Email</h4><p>{email}</p></div><div style=\"margin-bottom: 20px;\"><h4 style=\"margin-bottom: 5px;\">Phone Number</h4><p>{phone_no}</p></div><div style=\"margin-bottom: 20px;\"><h4 style=\"margin-bottom: 5px;\">Address</h4><address style=\"font-style: normal;\"><p style=\"width: 300px;\">{address}</p></address></div><div style=\"margin-bottom: 20px;\"><h4 style=\"margin-bottom: 5px;\">Website</h4><a style=\"color: #8142ff;\" href=\"{website}\" target=\"_blank\"><b>{website}</b></a></div></td></tr></tbody>', 'New Vendor Signup', '2021-12-20 07:29:56', '2021-12-20 07:29:56'),
(2, 'verify-mail', '{customer_name}, {code}', 'Verify Mail', '<tbody style=\"text-align: center;\"><tr><td style=\"padding-top: 0;\"><div style=\"background: #fff;box-shadow: 0 3px 4px #ddd;border-bottom-left-radius: 20px;border-bottom-right-radius: 20px;padding: 15px 40px 30px;\"><b style=\"margin-bottom: 10px; display: block;\">Hi {customer_name},</b><p>You can also verify manually by entering the following OTP</p><div style=\"padding:10px;border: 2px dashed #cb202d;word-break:keep-all!important;width: calc(100% - 40px);margin: 25px auto;\"><p style=\"Margin:0;Margin-bottom:16px;color:#cb202d;font-family:-apple-system,Helvetica,Arial,sans-serif;font-size:20px;font-weight:600;line-height:1.5;margin:0;margin-bottom:0;padding:0;text-align:center;word-break:keep-all!important\">{code}</p></div><p>Note: The OTP will expire in 10 minutes and can only be used once.</p></div></td></tr></tbody>', 'Verify Mail', '2021-12-20 07:29:56', '2021-12-20 07:29:56'),
(3, 'forgot-password', '{reset_link}', 'Forgot Password', '<tbody><tr><td><table style=\"background-color: #f2f3f8; max-width:670px; margin:0 auto;\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\"><tr><td style=\"height:20px;\">&nbsp;</td></tr><tr><td style=\"padding:0 35px;\"> <h1 style=\"color:rgb(51,51,51);font-weight:500;line-height:27px;font-size:21px\">You have requested to reset your password</h1><span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span><p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\"> We cannot simply send you your old password. A unique link to reset your password has been generated for you. To reset your password, click the following link and follow the instructions. </p> <a href=\"{reset_link}\" style=\"display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;margin-top: 35px;\">Reset Password</a></td></tr><tr><td style=\"height:20px;\">&nbsp;</td></tr></table></td><tr><td style=\"height:20px;\">&nbsp;</td></tr></table></td></tr></tbody>', 'Reset Password Notification', '2021-12-20 07:29:56', '2021-12-20 07:29:56'),
(4, 'refund', '{product_image}, {product_name}, {price}', 'Refund', '<tbody><tr><td><table style=\"width:100%;border: 1px solid rgb(221 221 221 / 41%);\"> <thead> <tr> <th style=\"border-bottom: 1px solid rgb(221 221 221 / 41%);\"><h3 style=\"color:rgb(51,51,51);font-weight:bold;line-height:27px;font-size:21px\">Refund Confirmation</h3> </th> </tr> </thead> <tbody> <tr><td><b><span style=\"font-size:16px;line-height:21px\"> Hello Share, </span> </b> <p style=\"margin:1px 0px 8px 0px;font-size:14px;line-height:18px;color:rgb(17,17,17)\"> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Totam sed vitae fugiat nam, ut natus officia optio a suscipit molestiae earum magni, voluptatum debitis repellat magnam. Officiis odit qui, provident doloremque dicta modi voluptatum placeat. </p></td></tr><tr><td><p style=\"margin:1px 0px 8px 0px;font-size:14px;line-height:18px;color:rgb(17,17,17)\"> You can find the list of possible reasons why the package is being returned to us as undelivered <a href=\"#\"><span style=\"color:#0066c0\">here</span></a>. If you still want the item, please check your address and place a new order. </p> </td> </tr> <tr> <td> <a style=\"display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;\" href=\"#\"> View return &amp; refund status </a> </td> </tr> <tr> <td> <div style=\"padding: 10px;border: 1px solid rgb(221 221 221 / 41%);margin-top: 15px;\"> <ul style=\"display: flex;align-items: center;\"> <li style=\"width: 80px;height: 80px;margin-right: 30px;\"> <img src=\"{product_image}\" alt=\"\" style=\"width: 100%;height: 100%;object-fit: cover;border-radius: 4px;\"> </li> <li> <a href=\"#\"><b>{product_name}</b></a> </li> </ul> <hr style=\"border:0; border-bottom: 1px solid rgb(221 221 221 / 41%);margin: 15px 0 20px;\"> <p align=\"right\" style=\"margin:1px 0px 8px 0px;font-size:14px;line-height:18px;font-family:&quot;Roboto&quot;,&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;color:rgb(17,17,17)\"><b> <span style=\"font-size:16px\"> Refund total: <span style=\"font-size:16px\">${price}* </span> </span> </b><br> <span style=\"display:inline-block;text-align:left\"> Refund of ${price} is now initiated. </span> </p></div></td> </tr> <tr> <td> <table id=\"m_-2085618623145965177legalCopy\" style=\"margin:0px 0px 0px 0px;font-weight:400;font-style:normal;font-size:13px;color:rgb(170,170,170);line-height:16px\"> <tbody> <tr> <td><p style=\"font-size:13px;color:rgb(102,102,102);line-height:16px;margin:0\"> * Learn more <a href=\"#\"><span style=\"color:#0066c0\">about refunds</span></a> </p></td> </tr> <tr> <td><p style=\"font-size:13px;color:rgb(102,102,102);line-height:16px;margin:0\"> This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message. </p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody>', 'Refund', '2021-12-20 07:29:56', '2021-12-20 07:29:56'),
(5, 'orders', '{customer_name}, {description}, {products}, {order_id}, {address}', 'Orders', '<table style=\"width: 100%;background-color: #fff;padding: 50px 0 0;\">\n                <thead>\n                    <tr>\n                       <th colspan=\"2\" style=\"text-align: center;\">\n                         <h1 style=\"color: rgba(0,0,0,0.66);font-family: &quot;Times New Roman&quot;;font-size: 28px;font-weight: bold;letter-spacing: 0;line-height: 32px;\">Thanks for your order</h1>\n                         <p style=\"color: rgba(0,0,0,0.66);font-size: 15px;letter-spacing: 0;line-height: 25px;width: 80%;margin: 30px auto 10px;\"><span style=\"display: block;\">Hi {customer_name},</span> we have received your order and we working on it now.\n                          We will email you an update as soon as your order is processed.</p>\n                          </th>\n                       </tr>\n                 </thead>\n                <tbody>\n                    <tr>\n                        <td colspan=\"2\" style=\"padding-left: 0;padding-right: 0;\">\n                            <table style=\"width:100%; border: 1px solid rgb(221 221 221 / 41%);\">\n                                <tbody>\n                                      <tr>\n                                        <td colspan=\"2\" style=\"padding: 0;\">\n                                            <table style=\"width:100%;\">\n                                                <tbody> {products} </tbody>\n                                            </table>\n                                        </td>\n                                    </tr>\n                                </tbody>\n                            </table>\n                        </td>\n                    </tr>\n                </tbody>\n            </table>', 'Orders', '2021-12-20 07:29:56', '2021-12-20 07:29:56'),
(6, 'successemail', '{name}', 'SuccessEmail', '<table style=\"width: 100%; background-color:#fff;\"> <thead> <tr> <th colspan=\"2\" style=\"text-align: center;\"> <a style=\"display: block;margin-bottom: 10px;\" href=\"#\"><img src=\"images/logo.png\" alt=\"\"> </a> <h1 style=\"margin: 0 0 10px;font-weight:400;\">Thanks for your order</h1> <p style=\"margin: 0 0 20px;font-weight:300;\">Hi {name}, <br> Payment done successfully. </p> <a style=\"display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;\" href=\"#\">View your order</a> </th> </tr> </thead> <tbody> <tr> <td colspan=\"2\"> <table style=\"width:100%; border: 1px solid rgb(221 221 221 / 41%);\"> <thead> <tr> <th colspan=\"2\" style=\"border-bottom: 1px solid rgb(221 221 221 / 41%);\"> <h3 style=\"font-weight: 700;\">Items Ordered</h3> </th> </tr> </thead> <tbody> <tr style=\"vertical-align: top;\"> <td style=\"border-bottom: 1px solid rgb(221 221 221 / 41%);border-right: 1px solid rgb(221 221 221 / 41%);width: 50%;\"> <p style=\"margin-bottom: 5px;\"><b></b></p> <p></p> </td> </tr> <tr> <td colspan=\"2\" style=\"padding: 0;\"> <table style=\"width:100%;\"> <tbody>  </tbody> <tfoot> <tr> <td colspan=\"2\" style=\"background-color: #8142ff;color: #fff; border-top: 1px solid rgb(221 221 221 / 41%);text-align: center;\"> <b>Powered By Royo</b> </td> </tr> </tfoot> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>', 'Success Email Notification', '2021-12-20 07:29:56', '2021-12-20 07:29:56'),
(7, 'failemail', '{name}', 'FailEmail', '<table style=\"width: 100%; background-color:#fff;\"> <thead> <tr> <th colspan=\"2\" style=\"text-align: center;\"> <a style=\"display: block;margin-bottom: 10px;\" href=\"#\"><img src=\"images/logo.png\" alt=\"\"> </a> <h1 style=\"margin: 0 0 10px;font-weight:400;\"></h1> <p style=\"margin: 0 0 20px;font-weight:300;\">Hi {name}, <br> Payment failed. </p> <a style=\"display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;\" href=\"#\">View your order</a> </th> </tr> </thead> <tbody> <tr> <td colspan=\"2\"> <table style=\"width:100%; border: 1px solid rgb(221 221 221 / 41%);\"> <thead> <tr> <th colspan=\"2\" style=\"border-bottom: 1px solid rgb(221 221 221 / 41%);\"> <h3 style=\"font-weight: 700;\">Items Ordered</h3> </th> </tr> </thead> <tbody> <tr style=\"vertical-align: top;\"> <td style=\"border-bottom: 1px solid rgb(221 221 221 / 41%);border-right: 1px solid rgb(221 221 221 / 41%);width: 50%;\"> <p style=\"margin-bottom: 5px;\"><b></b></p> <p></p> </td> </tr> <tr> <td colspan=\"2\" style=\"padding: 0;\"> <table style=\"width:100%;\"> <tbody>  </tbody> <tfoot> <tr> <td colspan=\"2\" style=\"background-color: #8142ff;color: #fff; border-top: 1px solid rgb(221 221 221 / 41%);text-align: center;\"> <b>Powered By Royo</b> </td> </tr> </tfoot> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>', 'Failure Email Notification', '2021-12-20 07:29:56', '2021-12-20 07:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_page_labels`
--

CREATE TABLE `home_page_labels` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '0-No, 1-Yes',
  `order_by` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_page_labels`
--

INSERT INTO `home_page_labels` (`id`, `title`, `slug`, `is_active`, `order_by`, `created_at`, `updated_at`) VALUES
(1, 'Vendors', 'vendors', 1, 1, NULL, NULL),
(2, 'Featured Products', 'featured_products', 1, 2, NULL, NULL),
(3, 'New Products', 'new_products', 1, 3, NULL, NULL),
(4, 'On Sale', 'on_sale', 1, 4, NULL, NULL),
(5, 'Best Sellers', 'best_sellers', 1, 5, NULL, NULL),
(6, 'Brands', 'brands', 1, 6, NULL, NULL),
(7, 'Pickup Delivery', 'pickup_delivery', 0, 7, NULL, NULL),
(8, 'Dynamic HTML', 'dynamic_page', 1, 8, NULL, NULL),
(9, 'Trending Vendors', 'trending_vendors', 1, 9, NULL, NULL),
(10, 'Recent Orders', 'recent_orders', 1, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `home_page_label_transaltions`
--

CREATE TABLE `home_page_label_transaltions` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_page_label_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `sort_code` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nativeName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `sort_code`, `name`, `nativeName`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', 'English', NULL, NULL),
(2, 'ab', 'Abkhaz', 'аҧсуа', NULL, NULL),
(3, 'aa', 'Afar', 'Afaraf', NULL, NULL),
(4, 'af', 'Afrikaans', 'Afrikaans', NULL, NULL),
(5, 'ak', 'Akan', 'Akan', NULL, NULL),
(6, 'sq', 'Albanian', 'Shqip', NULL, NULL),
(7, 'am', 'Amharic', 'አማርኛ', NULL, NULL),
(8, 'ar', 'Arabic', 'العربية', NULL, NULL),
(9, 'an', 'Aragonese', 'Aragonés', NULL, NULL),
(10, 'hy', 'Armenian', 'Հայերեն', NULL, NULL),
(11, 'as', 'Assamese', 'অসমীয়া', NULL, NULL),
(12, 'av', 'Avaric', 'авар мацӀ, магӀарул мацӀ', NULL, NULL),
(13, 'ae', 'Avestan', 'avesta', NULL, NULL),
(14, 'ay', 'Aymara', 'aymar aru', NULL, NULL),
(15, 'az', 'Azerbaijani', 'azərbaycan dili', NULL, NULL),
(16, 'bm', 'Bambara', 'bamanankan', NULL, NULL),
(17, 'ba', 'Bashkir', 'башҡорт теле', NULL, NULL),
(18, 'eu', 'Basque', 'euskara, euskera', NULL, NULL),
(19, 'be', 'Belarusian', 'Беларуская', NULL, NULL),
(20, 'bn', 'Bengali', 'বাংলা', NULL, NULL),
(21, 'bh', 'Bihari', 'भोजपुरी', NULL, NULL),
(22, 'bi', 'Bislama', 'Bislama', NULL, NULL),
(23, 'bs', 'Bosnian', 'bosanski jezik', NULL, NULL),
(24, 'br', 'Breton', 'brezhoneg', NULL, NULL),
(25, 'bg', 'Bulgarian', 'български език', NULL, NULL),
(26, 'my', 'Burmese', 'ဗမာစာ', NULL, NULL),
(27, 'ca', 'Catalan; Valencian', 'Català', NULL, NULL),
(28, 'ch', 'Chamorro', 'Chamoru', NULL, NULL),
(29, 'ce', 'Chechen', 'нохчийн мотт', NULL, NULL),
(30, 'ny', 'Chichewa; Chewa; Nyanja', 'chiCheŵa, chinyanja', NULL, NULL),
(31, 'zh', 'Chinese', '中文 (Zhōngwén), 汉语, 漢語', NULL, NULL),
(32, 'cv', 'Chuvash', 'чӑваш чӗлхи', NULL, NULL),
(33, 'kw', 'Cornish', 'Kernewek', NULL, NULL),
(34, 'co', 'Corsican', 'corsu, lingua corsa', NULL, NULL),
(35, 'cr', 'Cree', 'ᓀᐦᐃᔭᐍᐏᐣ', NULL, NULL),
(36, 'hr', 'Croatian', 'hrvatski', NULL, NULL),
(37, 'cs', 'Czech', 'česky, čeština', NULL, NULL),
(38, 'da', 'Danish', 'dansk', NULL, NULL),
(39, 'dv', 'Divehi; Dhivehi; Maldivian;', 'ދިވެހި', NULL, NULL),
(40, 'nl', 'Dutch', 'Nederlands, Vlaams', NULL, NULL),
(41, 'eo', 'Esperanto', 'Esperanto', NULL, NULL),
(42, 'et', 'Estonian', 'eesti, eesti keel', NULL, NULL),
(43, 'ee', 'Ewe', 'Eʋegbe', NULL, NULL),
(44, 'fo', 'Faroese', 'føroyskt', NULL, NULL),
(45, 'fj', 'Fijian', 'vosa Vakaviti', NULL, NULL),
(46, 'fi', 'Finnish', 'suomi, suomen kieli', NULL, NULL),
(47, 'fr', 'French', 'français, langue française', NULL, NULL),
(48, 'ff', 'Fula; Fulah; Pulaar; Pular', 'Fulfulde, Pulaar, Pular', NULL, NULL),
(49, 'gl', 'Galician', 'Galego', NULL, NULL),
(50, 'ka', 'Georgian', 'ქართული', NULL, NULL),
(51, 'de', 'German', 'Deutsch', NULL, NULL),
(52, 'el', 'Greek, Modern', 'Ελληνικά', NULL, NULL),
(53, 'gn', 'Guaraní', 'Avañeẽ', NULL, NULL),
(54, 'gu', 'Gujarati', 'ગુજરાતી', NULL, NULL),
(55, 'ht', 'Haitian; Haitian Creole', 'Kreyòl ayisyen', NULL, NULL),
(56, 'ha', 'Hausa', 'Hausa, هَوُسَ', NULL, NULL),
(57, 'he', 'Hebrew (modern)', 'עברית', NULL, NULL),
(58, 'hz', 'Herero', 'Otjiherero', NULL, NULL),
(59, 'hi', 'Hindi', 'हिन्दी, हिंदी', NULL, NULL),
(60, 'ho', 'Hiri Motu', 'Hiri Motu', NULL, NULL),
(61, 'hu', 'Hungarian', 'Magyar', NULL, NULL),
(62, 'ia', 'Interlingua', 'Interlingua', NULL, NULL),
(63, 'id', 'Indonesian', 'Bahasa Indonesia', NULL, NULL),
(64, 'ie', 'Interlingue', 'Originally called Occidental; then Interlingue after WWII', NULL, NULL),
(65, 'ga', 'Irish', 'Gaeilge', NULL, NULL),
(66, 'ig', 'Igbo', 'Asụsụ Igbo', NULL, NULL),
(67, 'ik', 'Inupiaq', 'Iñupiaq, Iñupiatun', NULL, NULL),
(68, 'io', 'Ido', 'Ido', NULL, NULL),
(69, 'is', 'Icelandic', 'Íslenska', NULL, NULL),
(70, 'it', 'Italian', 'Italiano', NULL, NULL),
(71, 'iu', 'Inuktitut', 'ᐃᓄᒃᑎᑐᑦ', NULL, NULL),
(72, 'ja', 'Japanese', '日本語 (にほんご／にっぽんご)', NULL, NULL),
(73, 'jv', 'Javanese', 'basa Jawa', NULL, NULL),
(74, 'kl', 'Kalaallisut, Greenlandic', 'kalaallisut, kalaallit oqaasii', NULL, NULL),
(75, 'kn', 'Kannada', 'ಕನ್ನಡ', NULL, NULL),
(76, 'kr', 'Kanuri', 'Kanuri', NULL, NULL),
(77, 'ks', 'Kashmiri', 'कश्मीरी, كشميري‎', NULL, NULL),
(78, 'kk', 'Kazakh', 'Қазақ тілі', NULL, NULL),
(79, 'km', 'Khmer', 'ភាសាខ្មែរ', NULL, NULL),
(80, 'ki', 'Kikuyu, Gikuyu', 'Gĩkũyũ', NULL, NULL),
(81, 'rw', 'Kinyarwanda', 'Ikinyarwanda', NULL, NULL),
(82, 'ky', 'Kirghiz, Kyrgyz', 'кыргыз тили', NULL, NULL),
(83, 'kv', 'Komi', 'коми кыв', NULL, NULL),
(84, 'kg', 'Kongo', 'KiKongo', NULL, NULL),
(85, 'ko', 'Korean', '한국어 (韓國語), 조선말 (朝鮮語)', NULL, NULL),
(86, 'ku', 'Kurdish', 'Kurdî, كوردی‎', NULL, NULL),
(87, 'kj', 'Kwanyama, Kuanyama', 'Kuanyama', NULL, NULL),
(88, 'la', 'Latin', 'latine, lingua latina', NULL, NULL),
(89, 'lb', 'Luxembourgish, Letzeburgesch', 'Lëtzebuergesch', NULL, NULL),
(90, 'lg', 'Luganda', 'Luganda', NULL, NULL),
(91, 'li', 'Limburgish, Limburgan, Limburger', 'Limburgs', NULL, NULL),
(92, 'ln', 'Lingala', 'Lingála', NULL, NULL),
(93, 'lo', 'Lao', 'ພາສາລາວ', NULL, NULL),
(94, 'lt', 'Lithuanian', 'lietuvių kalba', NULL, NULL),
(95, 'lu', 'Luba-Katanga', '', NULL, NULL),
(96, 'lv', 'Latvian', 'latviešu valoda', NULL, NULL),
(97, 'gv', 'Manx', 'Gaelg, Gailck', NULL, NULL),
(98, 'mk', 'Macedonian', 'македонски јазик', NULL, NULL),
(99, 'mg', 'Malagasy', 'Malagasy fiteny', NULL, NULL),
(100, 'ms', 'Malay', 'bahasa Melayu, بهاس ملايو‎', NULL, NULL),
(101, 'ml', 'Malayalam', 'മലയാളം', NULL, NULL),
(102, 'mt', 'Maltese', 'Malti', NULL, NULL),
(103, 'mi', 'Māori', 'te reo Māori', NULL, NULL),
(104, 'mr', 'Marathi (Marāṭhī)', 'मराठी', NULL, NULL),
(105, 'mh', 'Marshallese', 'Kajin M̧ajeļ', NULL, NULL),
(106, 'mn', 'Mongolian', 'монгол', NULL, NULL),
(107, 'na', 'Nauru', 'Ekakairũ Naoero', NULL, NULL),
(108, 'nv', 'Navajo, Navaho', 'Diné bizaad, Dinékʼehǰí', NULL, NULL),
(109, 'nb', 'Norwegian Bokmål', 'Norsk bokmål', NULL, NULL),
(110, 'nd', 'North Ndebele', 'isiNdebele', NULL, NULL),
(111, 'ne', 'Nepali', 'नेपाली', NULL, NULL),
(112, 'ng', 'Ndonga', 'Owambo', NULL, NULL),
(113, 'nn', 'Norwegian Nynorsk', 'Norsk nynorsk', NULL, NULL),
(114, 'no', 'Norwegian', 'Norsk', NULL, NULL),
(115, 'ii', 'Nuosu', 'ꆈꌠ꒿ Nuosuhxop', NULL, NULL),
(116, 'nr', 'South Ndebele', 'isiNdebele', NULL, NULL),
(117, 'oc', 'Occitan', 'Occitan', NULL, NULL),
(118, 'oj', 'Ojibwe, Ojibwa', 'ᐊᓂᔑᓈᐯᒧᐎᓐ', NULL, NULL),
(119, 'cu', 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic', 'ѩзыкъ словѣньскъ', NULL, NULL),
(120, 'om', 'Oromo', 'Afaan Oromoo', NULL, NULL),
(121, 'or', 'Oriya', 'ଓଡ଼ିଆ', NULL, NULL),
(122, 'os', 'Ossetian, Ossetic', 'ирон æвзаг', NULL, NULL),
(123, 'pa', 'Panjabi, Punjabi', 'ਪੰਜਾਬੀ, پنجابی‎', NULL, NULL),
(124, 'pi', 'Pāli', 'पाऴि', NULL, NULL),
(125, 'fa', 'Persian', 'فارسی', NULL, NULL),
(126, 'pl', 'Polish', 'polski', NULL, NULL),
(127, 'ps', 'Pashto, Pushto', 'پښتو', NULL, NULL),
(128, 'pt', 'Portuguese', 'Português', NULL, NULL),
(129, 'qu', 'Quechua', 'Runa Simi, Kichwa', NULL, NULL),
(130, 'rm', 'Romansh', 'rumantsch grischun', NULL, NULL),
(131, 'rn', 'Kirundi', 'kiRundi', NULL, NULL),
(132, 'ro', 'Romanian, Moldavian, Moldovan', 'română', NULL, NULL),
(133, 'ru', 'Russian', 'русский язык', NULL, NULL),
(134, 'sa', 'Sanskrit (Saṁskṛta)', 'संस्कृतम्', NULL, NULL),
(135, 'sc', 'Sardinian', 'sardu', NULL, NULL),
(136, 'sd', 'Sindhi', 'सिन्धी, سنڌي، سندھی‎', NULL, NULL),
(137, 'se', 'Northern Sami', 'Davvisámegiella', NULL, NULL),
(138, 'sm', 'Samoan', 'gagana faa Samoa', NULL, NULL),
(139, 'sg', 'Sango', 'yângâ tî sängö', NULL, NULL),
(140, 'sr', 'Serbian', 'српски језик', NULL, NULL),
(141, 'gd', 'Scottish Gaelic; Gaelic', 'Gàidhlig', NULL, NULL),
(142, 'sn', 'Shona', 'chiShona', NULL, NULL),
(143, 'si', 'Sinhala, Sinhalese', 'සිංහල', NULL, NULL),
(144, 'sk', 'Slovak', 'slovenčina', NULL, NULL),
(145, 'sl', 'Slovene', 'slovenščina', NULL, NULL),
(146, 'so', 'Somali', 'Soomaaliga, af Soomaali', NULL, NULL),
(147, 'st', 'Southern Sotho', 'Sesotho', NULL, NULL),
(148, 'es', 'Spanish', 'español', NULL, NULL),
(149, 'su', 'Sundanese', 'Basa Sunda', NULL, NULL),
(150, 'sw', 'Swahili', 'Kiswahili', NULL, NULL),
(151, 'ss', 'Swati', 'SiSwati', NULL, NULL),
(152, 'sv', 'Swedish', 'svenska', NULL, NULL),
(153, 'ta', 'Tamil', 'தமிழ்', NULL, NULL),
(154, 'te', 'Telugu', 'తెలుగు', NULL, NULL),
(155, 'tg', 'Tajik', 'тоҷикӣ, toğikī, تاجیکی‎', NULL, NULL),
(156, 'th', 'Thai', 'ไทย', NULL, NULL),
(157, 'ti', 'Tigrinya', 'ትግርኛ', NULL, NULL),
(158, 'bo', 'Tibetan Standard, Tibetan, Central', 'བོད་ཡིག', NULL, NULL),
(159, 'tk', 'Turkmen', 'Türkmen, Түркмен', NULL, NULL),
(160, 'tl', 'Tagalog', 'Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔', NULL, NULL),
(161, 'tn', 'Tswana', 'Setswana', NULL, NULL),
(162, 'to', 'Tonga (Tonga Islands)', 'faka Tonga', NULL, NULL),
(163, 'tr', 'Turkish', 'Türkçe', NULL, NULL),
(164, 'ts', 'Tsonga', 'Xitsonga', NULL, NULL),
(165, 'tt', 'Tatar', 'татарча, tatarça, تاتارچا‎', NULL, NULL),
(166, 'tw', 'Twi', 'Twi', NULL, NULL),
(167, 'ty', 'Tahitian', 'Reo Tahiti', NULL, NULL),
(168, 'ug', 'Uighur, Uyghur', 'Uyƣurqə, ئۇيغۇرچە‎', NULL, NULL),
(169, 'uk', 'Ukrainian', 'українська', NULL, NULL),
(170, 'ur', 'Urdu', 'اردو', NULL, NULL),
(171, 'uz', 'Uzbek', 'zbek, Ўзбек, أۇزبېك‎', NULL, NULL),
(172, 've', 'Venda', 'Tshivenḓa', NULL, NULL),
(173, 'vi', 'Vietnamese', 'Tiếng Việt', NULL, NULL),
(174, 'vo', 'Volapük', 'Volapük', NULL, NULL),
(175, 'wa', 'Walloon', 'Walon', NULL, NULL),
(176, 'cy', 'Welsh', 'Cymraeg', NULL, NULL),
(177, 'wo', 'Wolof', 'Wollof', NULL, NULL),
(178, 'fy', 'Western Frisian', 'Frysk', NULL, NULL),
(179, 'xh', 'Xhosa', 'isiXhosa', NULL, NULL),
(180, 'yi', 'Yiddish', 'ייִדיש', NULL, NULL),
(181, 'yo', 'Yoruba', 'Yorùbá', NULL, NULL),
(182, 'za', 'Zhuang, Chuang', 'Saɯ cueŋƅ, Saw cuengh', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_cards`
--

CREATE TABLE `loyalty_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_points` int DEFAULT NULL,
  `per_order_minimum_amount` int DEFAULT NULL,
  `per_order_points` int DEFAULT NULL,
  `per_purchase_minimum_amount` int DEFAULT NULL,
  `amount_per_loyalty_point` int DEFAULT NULL,
  `redeem_points_per_primary_currency` int DEFAULT NULL,
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Active, 1-Deactive, 2-Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `loyalty_check` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Active, 1-Deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_cards`
--

INSERT INTO `loyalty_cards` (`id`, `name`, `description`, `image`, `minimum_points`, `per_order_minimum_amount`, `per_order_points`, `per_purchase_minimum_amount`, `amount_per_loyalty_point`, `redeem_points_per_primary_currency`, `status`, `created_at`, `updated_at`, `loyalty_check`) VALUES
(1, 'Gold Plan', 'Gold Loyalty Card', '2f3120/loyalty/image/im5953PjFoo5xub5X4JKes2yV2CwnoAaBiy8ACh1.png', 400, NULL, 5, NULL, 10, 10, '0', '2021-11-16 05:03:53', '2021-11-16 05:15:49', '0'),
(2, 'Silver Plan', 'Silver Loyalty Card', '2f3120/loyalty/image/EAJdZtUl3sjzDLyvZfAjadapVc1S3eAQBSAqvjbr.png', 600, NULL, 8, NULL, 14, 10, '0', '2021-11-16 05:04:29', '2021-11-16 05:15:49', '0'),
(3, 'Platinum Plan', 'Platinum Loyalty Card', '2f3120/loyalty/image/rHwJcu9Q1NWp7TXnANRWoBOhdlWBPVbrBZgS2w1g.png', 800, NULL, 10, NULL, 20, 10, '0', '2021-11-16 05:07:24', '2021-11-16 05:15:49', '0');

-- --------------------------------------------------------

--
-- Table structure for table `luxury_options`
--

CREATE TABLE `luxury_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `luxury_options`
--

INSERT INTO `luxury_options` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'delivery', NULL, NULL),
(2, 'dine_in', NULL, NULL),
(3, 'takeaway', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `map_providers`
--

CREATE TABLE `map_providers` (
  `id` bigint UNSIGNED NOT NULL,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT ' 0 for no, 1 for yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `map_providers`
--

INSERT INTO `map_providers` (`id`, `provider`, `keyword`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Google Map', 'google_map', 1, NULL, NULL),
(2, 'Map Box', 'map_box', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_admins_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_11_06_222923_create_transactions_table', 1),
(4, '2018_11_07_192923_create_transfers_table', 1),
(5, '2018_11_07_202152_update_transfers_table', 1),
(6, '2018_11_15_124230_create_wallets_table', 1),
(7, '2018_11_19_164609_update_transactions_table', 1),
(8, '2018_11_20_133759_add_fee_transfers_table', 1),
(9, '2018_11_22_131953_add_status_transfers_table', 1),
(10, '2018_11_22_133438_drop_refund_transfers_table', 1),
(11, '2019_05_13_111553_update_status_transfers_table', 1),
(12, '2019_06_25_103755_add_exchange_status_transfers_table', 1),
(13, '2019_07_29_184926_decimal_places_wallets_table', 1),
(14, '2019_08_19_000000_create_failed_jobs_table', 1),
(15, '2019_09_17_051112_create_api_logs_table', 1),
(16, '2019_10_02_193759_add_discount_transfers_table', 1),
(17, '2019_10_13_000000_create_social_credentials_table', 1),
(18, '2020_10_30_193412_add_meta_wallets_table', 1),
(19, '2020_12_07_100603_create_types_table', 1),
(20, '2020_12_07_103301_create_countries_table', 1),
(21, '2020_12_07_103302_create_currencies_table', 1),
(22, '2020_12_07_103380_create_languages_table', 1),
(23, '2020_12_07_103418_create_notification_types_table', 1),
(24, '2020_12_07_103419_create_blocked_tokens_table', 1),
(25, '2020_12_21_104934_create_clients_table', 1),
(26, '2020_12_21_120042_create_roles_table', 1),
(27, '2020_12_21_135144_create_users_table', 1),
(28, '2020_12_24_103343_create_map_providers_table', 1),
(29, '2020_12_24_104834_create_sms_providers_table', 1),
(30, '2020_12_25_114722_create_templates_table', 1),
(31, '2020_12_25_124722_create_client_preferences_table', 1),
(32, '2020_12_29_121021_create_client_languages_table', 1),
(33, '2020_12_30_053607_create_vendors_table', 1),
(34, '2020_12_30_060809_create_vendor_users_table', 1),
(35, '2020_12_30_061924_create_service_areas_table', 1),
(36, '2020_12_30_095943_create_cms_table', 1),
(37, '2020_12_31_142820_create_categories_table', 1),
(38, '2020_12_31_142836_create_category_translations_table', 1),
(39, '2020_12_31_165834_create_banners_table', 1),
(40, '2020_12_31_263025_create_vendor_slots_table', 1),
(41, '2020_12_31_323159_create_vendor_slot_dates_table', 1),
(42, '2020_12_31_325657_create_slot_days_table', 1),
(43, '2021_01_14_063531_create_client_currencies_table', 1),
(44, '2021_01_14_114953_create_variants_table', 1),
(45, '2021_01_14_115058_create_variant_categories_table', 1),
(46, '2021_01_14_115141_create_variant_translations_table', 1),
(47, '2021_01_14_115200_create_variant_options_table', 1),
(48, '2021_01_14_115217_create_variant_option_translations_table', 1),
(49, '2021_01_14_135222_create_brands_table', 1),
(50, '2021_01_18_141503_create_brand_categories_table', 1),
(51, '2021_01_18_141534_create_brand_translations_table', 1),
(52, '2021_01_19_103352_create_category_histories_table', 1),
(53, '2021_01_19_125204_create_tax_categories_table', 1),
(54, '2021_01_19_125318_create_tax_rates_table', 1),
(55, '2021_01_19_125451_create_tax_rate_categories_table', 1),
(56, '2021_01_20_114648_create_addon_sets_table', 1),
(57, '2021_01_20_114706_create_addon_set_translations_table', 1),
(58, '2021_01_20_114724_create_addon_options_table', 1),
(59, '2021_01_20_114734_create_addon_option_translations_table', 1),
(60, '2021_01_21_101637_create_vendor_media_table', 1),
(61, '2021_01_21_112832_create_products_table', 1),
(62, '2021_01_21_112848_create_product_translations_table', 1),
(63, '2021_01_22_101046_create_product_categories_table', 1),
(64, '2021_01_22_113717_create_product_addons_table', 1),
(65, '2021_01_22_113948_create_product_cross_sells_table', 1),
(66, '2021_01_22_114102_create_product_up_sells_table', 1),
(67, '2021_01_22_114129_create_product_related_table', 1),
(68, '2021_01_22_134800_create_product_variants_table', 1),
(69, '2021_01_22_141044_create_product_variant_sets_table', 1),
(70, '2021_02_01_101734_create_product_images_table', 1),
(71, '2021_02_03_052127_create_product_variant_images_table', 1),
(72, '2021_02_19_061327_create_user_devices_table', 1),
(73, '2021_02_20_100004_create_terminologies_table', 1),
(74, '2021_02_20_100026_create_accounts_table', 1),
(75, '2021_02_20_100053_create_reports_table', 1),
(76, '2021_02_26_094556_create_category_tags_table', 1),
(77, '2021_03_09_074202_add_port_to_clients_table', 1),
(78, '2021_03_25_064409_add_social_login_field', 1),
(79, '2021_03_25_072244_add_brands_field', 1),
(80, '2021_04_14_055025_create_user_wishlists_table', 1),
(81, '2021_04_15_125922_create_loyalty_cards_table', 1),
(82, '2021_04_16_074202_add_image_to_users_table', 1),
(83, '2021_04_20_054439_create_carts_table', 1),
(84, '2021_04_20_055234_create_promo_types_table', 1),
(85, '2021_04_20_055359_create_promocodes_table', 1),
(86, '2021_04_20_055509_create_promocode_restrictions_table', 1),
(87, '2021_04_20_055624_create_cart_coupons_table', 1),
(88, '2021_04_20_055625_create_cart_products_table', 1),
(89, '2021_04_20_092608_create_user_addresses_table', 1),
(90, '2021_04_28_041838_create_cart_addons_table', 1),
(91, '2021_05_03_092015_create_orders_table', 1),
(92, '2021_05_04_070811_create_order_vendors_table', 1),
(93, '2021_05_04_071200_create_order_products_table', 1),
(94, '2021_05_04_071929_create_order_product_addons_table', 1),
(95, '2021_05_05_080739_add_currency_field_in_cart_products', 1),
(96, '2021_05_07_145709_create_promo_usages_table', 1),
(97, '2021_05_10_034916_add_fields_to_promocodes_table', 1),
(98, '2021_05_10_052517_create_celebrities_table', 1),
(99, '2021_05_10_131738_create_promocode_details_table', 1),
(100, '2021_05_11_124314_add_fields_to_preferences_table', 1),
(101, '2021_05_12_050135_create_user_loyalty_points_table', 1),
(102, '2021_05_12_050252_create_user_loyalty_point_histories', 1),
(103, '2021_05_12_050529_alter_promocode_image_field_table', 1),
(104, '2021_05_12_100036_create_payments_table', 1),
(105, '2021_05_12_102501_alter_promocodes_short_desc_field_table', 1),
(106, '2021_05_13_053118_create_refer_and_earns_table', 1),
(107, '2021_05_13_080525_create_user_refferals_table', 1),
(108, '2021_05_13_102448_alter_cart_coupons_for_vendor_ids_field_table', 1),
(109, '2021_05_13_153007_create_celebrity_brand_table', 1),
(110, '2021_05_14_083633_add_status_to_orders', 1),
(111, '2021_05_14_084221_add_order_id_to_payments', 1),
(112, '2021_05_14_141254_add_country_id_to_celebrities', 1),
(113, '2021_05_17_092828_create_product_celebrities_table', 1),
(114, '2021_05_17_141254_add_description_to_celebrities', 1),
(115, '2021_05_19_042503_create_payment_options_table', 1),
(116, '2021_05_20_065410_alter_orders_table_order_no', 1),
(117, '2021_05_20_123811_create_jobs_table', 1),
(118, '2021_05_21_045823_add_phonecode_in_address_table', 1),
(119, '2021_05_21_045823_change_county_field_type_table', 1),
(120, '2021_05_21_063543_permission_table_for_acl', 1),
(121, '2021_05_21_074707_user_permissions_table_for_acl', 1),
(122, '2021_05_21_082602_alter_order_products_table', 1),
(123, '2021_05_21_103918_alter_order_products_for_rename_table_table', 1),
(124, '2021_05_21_113803_create_vendor_categories_table', 1),
(125, '2021_05_25_094250_user_vendors_table_for_acl', 1),
(126, '2021_05_25_100950_alter_order_vendors_table', 1),
(127, '2021_05_25_104437_alter_order_vendors_table_for_coupon_code', 1),
(128, '2021_05_27_091733_add_wishlist_field_in_categories_table', 1),
(129, '2021_05_27_112637_add_status_in_product_variants_table', 1),
(130, '2021_05_28_051503_add_timezone_in_users_table', 1),
(131, '2021_05_28_052713_add_code_in_user', 1),
(132, '2021_05_28_052713_add_superadmin_in_client', 1),
(133, '2021_05_28_052713_add_superadmin_in_user', 1),
(134, '2021_05_31_062720_add_category_switch_in_vendors_table', 1),
(135, '2021_05_31_090803_create_vendor_templetes_table', 1),
(136, '2021_05_31_091703_add_templete_id_in_vendors_table', 1),
(137, '2021_06_01_043327_create_timezones_table', 1),
(138, '2021_06_01_043453_create_order_status_table', 1),
(139, '2021_06_01_051302_add_timezone_field_users_table', 1),
(140, '2021_06_01_113407_alter_promo_codes_table', 1),
(141, '2021_06_03_061348_add_delete_at_products_table', 1),
(142, '2021_06_08_094834_create_csv_product_imports_table', 1),
(143, '2021_06_09_043732_add_loyalty_points_earned_orders_table', 1),
(144, '2021_06_09_074633_alter_cart_addons_table', 1),
(145, '2021_06_10_074037_alter_preference_dispatch_new_keys', 1),
(146, '2021_06_10_075653_add_credentials_to_payment_options', 1),
(147, '2021_06_10_094428_alter_categories_table_for_deleted_at', 1),
(148, '2021_06_11_083205_create_csv_vendor_imports_table', 1),
(149, '2021_06_14_050458_create_app_stylings_table', 1),
(150, '2021_06_14_050649_create_app_styling_options_table', 1),
(151, '2021_06_14_065037_alter_currency_id_cart_products_table', 1),
(152, '2021_06_14_105745_drop_vendor_payment_options_table', 1),
(153, '2021_06_15_102257_rename_order_status_table', 1),
(154, '2021_06_15_102631_create_dispatcher_status_options_table', 1),
(155, '2021_06_15_103435_create_order_statuses_table', 1),
(156, '2021_06_15_103450_create_dispatcher_statuses_table', 1),
(157, '2021_06_16_051848_rename_order_status2_table', 1),
(158, '2021_06_16_052121_add_vendor_id_to_order_status', 1),
(159, '2021_06_16_052903_rename_dispatcher_statuses_table', 1),
(160, '2021_06_16_052952_add_vendor_id_to_dispatcher_status', 1),
(161, '2021_06_16_064425_add_timezone_to_user_table', 1),
(162, '2021_06_16_110618_add_refer_and_earn_columns_to_client_preferences', 1),
(163, '2021_06_16_132604_drop_wallets_table', 1),
(164, '2021_06_17_044423_alter_order_vendor_web_hook_code_table', 1),
(165, '2021_06_18_072955_addtrackingurlinordervendor', 1),
(166, '2021_06_18_112628_alter_some_table_for_ref', 1),
(167, '2021_06_21_064857_add_total_delivery_fee_to_orders', 1),
(168, '2021_06_21_065449_orderproductratingstable', 1),
(169, '2021_06_21_094217_add_template_id_field_to_app_styling_options_table', 1),
(170, '2021_06_21_123621_alter_user_user_id_field_for_order_vendors_table', 1),
(171, '2021_06_21_123928_add_web_color_field_to_client_preferences_table', 1),
(172, '2021_06_22_062736_createreviewfilestable', 1),
(173, '2021_06_22_063355_alterorderproductratingsstatustable', 1),
(174, '2021_06_23_063853_add_pharmacy_check_field_to_client_preferences_table', 1),
(175, '2021_06_24_044626_add_description_to_users_table', 1),
(176, '2021_06_24_045847_add_pharmacy_check_field_to_products_table', 1),
(177, '2021_06_24_064903_create_cart_product_prescriptions_table', 1),
(178, '2021_06_24_122153_create_order_product_prescriptions_table', 1),
(179, '2021_06_25_045048_create_dispatcher_template_type_options_table', 1),
(180, '2021_06_25_052752_create_dispatcher_warning_pages_table', 1),
(181, '2021_06_25_053039_add_vendor_id_field_to_order_product_prescriptions_table', 1),
(182, '2021_06_25_054358_add_vendor_id_field_to_cart_product_prescriptions_table', 1),
(183, '2021_06_25_083707_alter_category_table_for_dipatcher_field_in_tables', 1),
(184, '2021_06_28_110323_add_category_id_field_to_order_products_table', 1),
(185, '2021_06_28_112229_alter_type_table_for_sequences_field_in_tables', 1),
(186, '2021_06_28_134738_alter_vendor_for_slug_tables', 1),
(187, '2021_06_29_045943_add_admin_commission_fields_to_order_vendors_table', 1),
(188, '2021_06_29_063720_add_actual_amount_fields_to_order_vendors_table', 1),
(189, '2021_06_29_084253_add_taxable_amount_fields_to_order_vendors_table', 1),
(190, '2021_06_29_094113_change_taxable_amount_fields_to_order_vendors_table', 1),
(191, '2021_06_29_102709_createtableorderreturnrequests', 1),
(192, '2021_06_29_121116_change_limit_fields_to_promocodes_table', 1),
(193, '2021_06_30_053518_createtablereturn_reasons', 1),
(194, '2021_06_30_095821_createtablereturnrequestfiles', 1),
(195, '2021_06_30_123018_add_image_path_field_to_dispatcher_template_type_options_table', 1),
(196, '2021_06_30_123341_add_image_path_field_to_dispatcher_warning_pages_table', 1),
(197, '2021_06_30_130644_alter_vendor_orders_add_payment_option_id_table', 1),
(198, '2021_07_01_071008_alter_orders_for_loyalty_membership_id_table', 1),
(199, '2021_07_01_072413_add_dine_in_fields_to_client_preferences_table', 1),
(200, '2021_07_01_124823_create_order_taxes_table', 1),
(201, '2021_07_02_123701_alter_order_vendor_products_for_order_vendor_id', 1),
(202, '2021_07_05_045644_add_slug_in_celebrities_table', 1),
(203, '2021_07_05_075226_alter_types_for_images_table', 1),
(204, '2021_07_05_104133_addreasonbyvendorinreturnrequests', 1),
(205, '2021_07_05_123711_add_loyalty_check_field_to_loyalty_cards_table', 1),
(206, '2021_07_06_045605_alter_vendors_table_forfew_fields', 1),
(207, '2021_07_06_054045_addpickupdeliverykeysinclientprefereance', 1),
(208, '2021_07_06_084032_create_subscription_validities_table', 1),
(209, '2021_07_06_091605_create_social_media_table', 1),
(210, '2021_07_06_091729_create_subscription_features_list_table', 1),
(211, '2021_07_06_095637_create_user_subscriptions_table', 1),
(212, '2021_07_06_095655_create_user_subscription_features_table', 1),
(213, '2021_07_07_054636_create_luxury_options_table', 1),
(214, '2021_07_07_055509_add_luxury_option_id_to_cart_products_table', 1),
(215, '2021_07_07_092630_create_vendor_subscriptions_table', 1),
(216, '2021_07_07_092704_create_vendor_subscription_features_table', 1),
(217, '2021_07_07_104413_alter_client_preferences_table_for_cart_enable', 1),
(218, '2021_07_07_114341_alter_products_table_for_enquire_mod', 1),
(219, '2021_07_07_122135_add_rating_check_to_client_preferences_table', 1),
(220, '2021_07_08_050906_addtagkeyinproducttable', 1),
(221, '2021_07_08_060154_create_product_inquiries_table', 1),
(222, '2021_07_08_064807_add_vendor_id_to_product_inquiries_table', 1),
(223, '2021_07_08_070945_create_pages_table', 1),
(224, '2021_07_08_105308_alter_pages_table', 1),
(225, '2021_07_08_132134_alter_wallets_table', 1),
(226, '2021_07_09_052529_alter_user_subscriptions_table', 1),
(227, '2021_07_09_063048_alter_vendor_subscriptions_table', 1),
(228, '2021_07_09_064149_create_page_translations_table', 1),
(229, '2021_07_09_072813_create_subscribed_users_table', 1),
(230, '2021_07_09_091843_create_subscribed_status_options_table', 1),
(231, '2021_07_12_101144_add_phone_code_field_to_users_table', 1),
(232, '2021_07_12_105145_rename_phone_code_in_users_table', 1),
(233, '2021_07_12_130335_alter_payment_options_table', 1),
(234, '2021_07_15_141847_create_subscription_status_options_table', 1),
(235, '2021_07_16_042043_create_subscription_features_list_user_table', 1),
(236, '2021_07_16_042211_create_subscription_plans_user_table', 1),
(237, '2021_07_16_042346_create_subscription_plan_features_user_table', 1),
(238, '2021_07_16_042524_create_subscription_invoices_user_table', 1),
(239, '2021_07_16_042848_create_subscription_log_user_table', 1),
(240, '2021_07_16_045752_create_subscription_invoice_features_user_table', 1),
(241, '2021_07_16_054443_create_vendor_registration_documents_table', 1),
(242, '2021_07_16_061522_drop_subscriptions_table', 1),
(243, '2021_07_16_083525_alter_subscription_plans_user_table', 1),
(244, '2021_07_16_092718_create_subscription_features_list_vendor_table', 1),
(245, '2021_07_16_092754_create_subscription_plans_vendor_table', 1),
(246, '2021_07_16_092836_create_subscription_plan_features_vendor_table', 1),
(247, '2021_07_16_100042_create_vendor_docs_table', 1),
(248, '2021_07_16_105955_alter_user_table_for_title', 1),
(249, '2021_07_16_120516_add_frequency_to_subscription_plans_user_table', 1),
(250, '2021_07_16_121019_add_frequency_to_subscription_plans_vendor_table', 1),
(251, '2021_07_16_121201_add_frequency_to_subscription_invoices_user_table', 1),
(252, '2021_07_19_052218_alter_vendor_registration_document_translations_table', 1),
(253, '2021_07_19_081612_create_nomenclatures_table', 1),
(254, '2021_07_19_083627_alter_vendors_table_for_is_show_category_details', 1),
(255, '2021_07_20_045632_alter_vendor_docs_table', 1),
(256, '2021_07_20_054826_create_user_saved_payment_methods_table', 1),
(257, '2021_07_20_085414_create_nomenclatures_translations_table', 1),
(258, '2021_07_20_111025_add_subscription_discount_to_orders', 1),
(259, '2021_07_21_045727_create_email_templates_table', 1),
(260, '2021_07_21_051114_add_subscription_invoice_id_to_payments', 1),
(261, '2021_07_21_081216_alter_subscription_invoices_user_table', 1),
(262, '2021_07_22_045616_create_subscription_invoices_vendor_table', 1),
(263, '2021_07_22_045636_create_subscription_invoice_features_vendor_table', 1),
(264, '2021_07_22_051752_add_vendor_subscription_invoice_id_to_payments', 1),
(265, '2021_07_22_053723_create_vendor_saved_payment_methods_table', 1),
(266, '2021_07_22_064142_add_added_by_in_promos_table', 1),
(267, '2021_07_22_085935_add_frequency_to_subscription_invoices_vendor', 1),
(268, '2021_07_23_065414_alter_description_in_subscription_plans_user', 1),
(269, '2021_07_23_065425_alter_description_in_subscription_plans_vendor', 1),
(270, '2021_07_23_065528_add_user_id_to_vendor_saved_payment_method', 1),
(271, '2021_07_23_085721_add_subscription_mode_to_client_preferences', 1),
(272, '2021_07_23_095519_add_user_id_to_subscription_invoices_vendor', 1),
(273, '2021_07_26_051945_add_tip_amount_to_orders', 1),
(274, '2021_07_26_054718_create_vendor_dinein_categories_table', 1),
(275, '2021_07_26_062106_add_age_restriction_field_to_client_preferences', 1),
(276, '2021_07_26_064915_add_age_restriction_title_field_to_client_preferences', 1),
(277, '2021_07_26_072854_create_vendor_dinein_tables_table', 1),
(278, '2021_07_26_092647_create_vendor_dinein_table_translations_table', 1),
(279, '2021_07_26_105532_add_vendor_id_field_to_vendor_dinein_tables', 1),
(280, '2021_07_27_094908_add_contact_us_field_to_client_preferences_table', 1),
(281, '2021_07_27_103709_addserviceskeyinconfigtable', 1),
(282, '2021_07_27_130205_addpricefromdispatcherproductstable', 1),
(283, '2021_07_28_053234_addproduct_dispatcher_taginorder_vendor_productstable', 1),
(284, '2021_07_28_085341_alter_orders_table_for_scheduled_date_time', 1),
(285, '2021_07_28_101214_create_vendor_dinein_category_translations_table', 1),
(286, '2021_07_28_115607_add_seating_number_to_vendor_dinein_tables_table', 1),
(287, '2021_07_30_064620_add_fields_wishlist_to_client_preferences_table', 1),
(288, '2021_07_30_070813_create_home_page_labels_table', 1),
(289, '2021_07_30_071648_create_home_page_label_transaltions_table', 1),
(290, '2021_07_30_090119_add_slug_field_to_home_page_labels_table', 1),
(291, '2021_07_30_095659_add_loyalty_check_field_to_client_preferences_table', 1),
(292, '2021_08_02_130659_alterdispatcherstatusinordervendorstable', 1),
(293, '2021_08_04_064805_add_order_by_field_to_home_page_labels_table', 1),
(294, '2021_08_05_050409_addlast_mile_team_in_preference', 1),
(295, '2021_08_06_082831_alter_csv_product_imports_table_for_woocomerce_import', 1),
(296, '2021_08_06_113231_create_woocommerces_table', 1),
(297, '2021_08_09_084617_create_notification_templates_table', 1),
(298, '2021_08_10_071354_add_image_to_loyalty_cards', 1),
(299, '2021_08_12_114552_add_vendor_dinein_table_id_to_order_vendors', 1),
(300, '2021_08_12_122400_add_vendor_dinein_table_id_to_cart_products', 1),
(301, '2021_08_17_095154_add_wallet_amount_used_to_orders', 1),
(302, '2021_08_17_103153_add_show_payment_icons_to_client_preferences_table', 1),
(303, '2021_08_19_063431_add_dark_mode_toggle_to_client_preferences_table', 1),
(304, '2021_08_19_090959_add_scheduled_date_time_to_carts', 1),
(305, '2021_08_24_065505_alter_timezone_in_clients', 1),
(306, '2021_08_24_091110_add_instructionsin_carts_table', 1),
(307, '2021_08_26_130550_add_site_top_header_color_to_client_preferences', 1),
(308, '2021_09_03_123843_addmodeofserviceinproductstable', 1),
(309, '2021_09_04_044855_add_columns_to_cart_products_table', 1),
(310, '2021_09_04_121447_addschedulecolumnsinorderproducts', 1),
(311, '2021_09_06_072333_create_driver_registration_documents_table', 1),
(312, '2021_09_06_092853_alter_driver_registration_document_translations_table', 1),
(313, '2021_09_06_094107_addtipafterbeofreclientpreferences', 1),
(314, '2021_09_06_112912_addautoacceptorderclientpreferences', 1),
(315, '2021_09_06_134705_addautoacceptvendors', 1),
(316, '2021_09_07_120350_onoffnowschedulekeyinclientpreferenace', 1),
(317, '2021_09_10_131003_addisolatesinglevendorordertoclientpreferences', 1),
(318, '2021_09_14_061845_create_onboard_settingstable', 1),
(319, '2021_09_14_125501_add_testmode_to_payment_options', 1),
(320, '2021_09_15_085625_createcabbookinglayoutstable', 1),
(321, '2021_09_16_064640_createcab_booking_layout_translations', 1),
(322, '2021_09_16_070957_add_firebase_credentials_columns_to_client_preferences', 1),
(323, '2021_09_16_113622_createcab_booking_layout_catrgories', 1),
(324, '2021_09_17_070649_altercab_booking_layout_transaltionstable', 1),
(325, '2021_09_21_062239_add_column_to_page_translations_table', 1),
(326, '2021_09_21_063043_add_distance_time_cal_to_client_preference', 1),
(327, '2021_09_21_142819_add_product_type_to_client_preference', 1),
(328, '2021_09_23_113026_add_columns_to_brand_categories', 1),
(329, '2021_09_23_124035_webstylingtable', 1),
(330, '2021_09_23_124059_webstylingoptionstable', 1),
(331, '2021_09_23_142527_alter_order_vendors_for_eta', 1),
(332, '2021_09_23_142815_hide_nav_bar_client_preferences', 1),
(333, '2021_09_29_053310_createbusinessmodaltable', 1),
(334, '2021_09_29_065353_alterproducttypeclientpreftable', 1),
(335, '2021_10_01_121216_add_column_to_order_vendors_table', 1),
(336, '2021_10_06_112147_add_image_mobile_to_banners', 1),
(337, '2021_10_07_054022_add_column_to_brands_table', 1),
(338, '2021_10_11_064940_add_payment_status_to_orders', 1),
(339, '2021_10_13_105952_alter_apple_playstore_link', 1),
(340, '2021_10_13_122046_add_luxury_option_id_to_orders', 1),
(341, '2021_10_14_054612_create_mobile_banners_table', 1),
(342, '2021_10_14_094040_altersingle_vendorinclientpref', 1),
(343, '2021_10_14_105550_add_column_to_permissions_table', 1),
(344, '2021_10_19_065203_auto_reject_orders_cron', 1),
(345, '2021_10_19_071128_alterimageinlayouttranslations', 1),
(346, '2021_10_22_110103_create_app_dynamic_tutorials_table', 1),
(347, '2021_10_28_131829_add_stripe_connect_to_client_preferences', 1),
(348, '2021_11_08_065839_add_column_promo_security_in_promocodes', 1),
(349, '2021_11_09_123052_addlaundrykeysinpreftable', 1),
(350, '2021_11_10_054734_add_column_is_required_to_vendor_registration', 1),
(351, '2021_11_10_130246_alter_email_null_to_users', 1),
(352, '2021_11_11_125845_altercartspickupdropoffcomment', 1),
(353, '2021_11_11_132220_alterorderspickupdropoffcomment', 1),
(354, '2021_11_12_105836_altercartschedulepickup', 1),
(355, '2021_11_12_115351_alterschedulepickupinorders', 1),
(356, '2021_11_13_103058_create_vendor_connected_accounts', 1),
(357, '2021_11_15_073414_create_payout_options_table', 1),
(358, '2021_11_16_051354_create_vendor_payouts', 1),
(359, '2021_11_17_115023_tagsandtagtranslations', 1),
(360, '2021_11_17_132120_createproducttagstable', 1),
(361, '2021_11_18_064423_alterspecific_instructionorders', 1),
(362, '2021_11_18_112035_add_column_admin_email_to_client_preferences', 1),
(363, '2021_11_22_063948_uploadiconintagstable', 1),
(364, '2021_11_23_060003_delay_orderinclient_preftable', 1),
(365, '2021_11_23_085524_alterdelayorderhrs', 1),
(366, '2021_11_24_052349_add_others_type_to_user_addresses', 1),
(367, '2021_11_24_093126_alter_users_remove_unique_email', 1),
(368, '2021_11_25_083724_add_gifting_to_client_preferences_table', 1),
(369, '2021_11_25_090306_add_is_gifit_to_orders_table', 1),
(370, '2021_11_25_091835_addproduct_variant_setsincartproductorderproducts', 1),
(371, '2021_11_27_093049_alter_table_orders_change_tip_amount', 1),
(372, '2021_11_29_113505_add_house_number_to_user_addresses_table', 1),
(373, '2021_11_30_060041_createproductfaqstable', 1),
(374, '2021_11_30_100231_alterclientpreproductorderform', 1),
(375, '2021_12_01_114017_add_service_fee_to_vendors', 1),
(376, '2021_12_01_114828_alterproductfaqs', 1),
(377, '2021_12_01_124153_alterordervendorproductsforfaqs', 1),
(378, '2021_12_03_072456_alterproductforpickupdropdelay', 1),
(379, '2021_12_06_093330_alterordervendorcacelledby', 1),
(380, '2021_12_07_120543_alter_commission_percent', 1),
(381, '2021_12_09_065303_create_shipping_options_table', 1),
(382, '2021_12_09_101244_add_need_shipment_column_to_products_table', 1),
(383, '2021_12_09_122123_add_sms_credentials_to_client_preferences_table', 1),
(384, '2021_12_14_111734_changeskustringlenghthinproducts', 1),
(385, '2021_12_15_123336_add_pickup_delivery_service_area_mod', 1),
(386, '2021_12_16_063556_add_customer_support_to_preferences', 1),
(387, '2021_12_17_091114_add_need_shipping_column_to_preferences_table', 1),
(388, '2021_12_17_114832_changer_doller_compare_inclient_curr', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_banners`
--

CREATE TABLE `mobile_banners` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validity_on` tinyint NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
  `sorting` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - active, 0 - pending, 2 - blocked',
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `redirect_category_id` bigint UNSIGNED DEFAULT NULL,
  `redirect_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mobile_banners`
--

INSERT INTO `mobile_banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', NULL, 'banner/WPe41MP5sseOwa0sUIjnepxXuBfuHVctMXnyGJCY.png', 1, 1, 1, '2021-10-05 01:06:00', '2021-12-31 12:00:00', 19, NULL, 'category', NULL, '2021-11-15 10:40:54'),
(2, 'Vegetables', NULL, 'banner/U9MskRtiahL1nm6OB9ZqH5E3Lu0eMTOf8z6juMXC.png', 1, 2, 1, '2021-10-05 01:07:00', '2021-12-31 12:00:00', 8, NULL, 'category', NULL, '2021-11-15 10:41:05'),
(3, 'Pharmacy', NULL, 'banner/JCFtxnEOAU0KfhwDjpUVbmyLYhbDdX3CQ7j4keAN.jpg', 1, 3, 1, '2021-10-05 01:07:00', '2021-12-31 12:00:00', 5, NULL, 'category', NULL, '2021-11-15 10:41:32'),
(4, 'Fashion', NULL, 'banner/pRSAJJoztTKAMbm8QPTaRiWtSnSme8xkNPHw3pK5.png', 1, 4, 1, '2021-10-05 01:07:00', '2021-12-31 12:00:00', 26, NULL, 'category', '2021-10-04 18:49:39', '2021-11-15 10:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `nomenclatures`
--

CREATE TABLE `nomenclatures` (
  `id` bigint UNSIGNED NOT NULL,
  `label` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nomenclatures_translations`
--

CREATE TABLE `nomenclatures_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `nomenclature_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `tags` mediumtext COLLATE utf8mb4_unicode_ci,
  `label` mediumtext COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `subject` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `slug`, `tags`, `label`, `content`, `subject`, `created_at`, `updated_at`) VALUES
(1, 'new-order', '', 'New Order', 'Thanks for your Order', 'New Vendor Signup', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(2, 'order-status-update', '', 'Order Status Update', 'Your Order status has been updated', 'Verify Mail', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(3, 'refund-status-update', '', 'Refund Status Update', 'Your Order status has been updated', 'Reset Password Notification', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(4, 'new-order-received', '', 'New Order Received (Owner)', 'You have received a new order', 'New Order Received', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(5, 'order-accepted', '{order_id}', 'Order Accepted (Customer)', 'Your order ({order_id}) has been accepted', 'Order Accepted', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(6, 'order-rejected', '{order_id}', 'Order Rejected (Customer)', 'Your order ({order_id}) has been rejected', 'Order Rejected', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(7, 'order-processing', '{order_id}', 'Order Processing (Customer)', 'Your order ({order_id}) has been processed', 'Order Processed', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(8, 'order-out-of-delivery', '{order_id}', 'Out of delivery (Customer)', 'Your order ({order_id}) has been reached to you soon', 'Out of delivery', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(9, 'order-delivered', '{order_id}', 'Order Delivered (Customer)', 'Your order ({order_id}) has delivered', 'Order Delivered', '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(10, 'place-order-reminder', '', 'Place Order Reminder (Customer)', 'Place your order before it\'s too late', 'Don\'t wait too much', '2021-12-20 06:18:44', '2021-12-20 06:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `notification_types`
--

CREATE TABLE `notification_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onboard_settings`
--

CREATE TABLE `onboard_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_from` tinyint NOT NULL DEFAULT '1' COMMENT '1 : For GodPanel',
  `on_off` tinyint NOT NULL DEFAULT '0' COMMENT '0 : For off',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_date_time` datetime DEFAULT NULL,
  `payment_option_id` tinyint NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `address_id` bigint UNSIGNED DEFAULT NULL,
  `is_deleted` tinyint NOT NULL COMMENT '0-No, 1-Yes',
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `loyalty_membership_id` int UNSIGNED DEFAULT NULL,
  `luxury_option_id` int UNSIGNED DEFAULT NULL,
  `loyalty_points_used` decimal(10,2) DEFAULT NULL,
  `loyalty_amount_saved` decimal(10,2) DEFAULT NULL,
  `loyalty_points_earned` decimal(10,2) DEFAULT NULL,
  `paid_via_wallet` tinyint NOT NULL COMMENT '0-No, 1-Yes',
  `paid_via_loyalty` tinyint NOT NULL COMMENT '0-No, 1-Yes',
  `total_amount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `wallet_amount_used` decimal(12,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `subscription_discount` decimal(12,2) DEFAULT NULL,
  `total_discount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `total_delivery_fee` decimal(8,2) DEFAULT NULL,
  `taxable_amount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `tip_amount` decimal(8,2) UNSIGNED DEFAULT '0.00',
  `payable_amount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Credit Card, 2 - Cash On Delivery, 3 - Paypal, 4 - Wallet',
  `payment_status` tinyint DEFAULT '0' COMMENT '0=Pending, 1=Paid',
  `comment_for_pickup_driver` mediumtext COLLATE utf8mb4_unicode_ci,
  `comment_for_dropoff_driver` mediumtext COLLATE utf8mb4_unicode_ci,
  `comment_for_vendor` mediumtext COLLATE utf8mb4_unicode_ci,
  `schedule_pickup` datetime DEFAULT NULL,
  `schedule_dropoff` datetime DEFAULT NULL,
  `specific_instructions` text COLLATE utf8mb4_unicode_ci,
  `is_gift` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `total_service_fee` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_addons`
--

CREATE TABLE `order_product_addons` (
  `id` bigint UNSIGNED NOT NULL,
  `order_product_id` bigint UNSIGNED NOT NULL,
  `addon_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_prescriptions`
--

CREATE TABLE `order_product_prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `prescription` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_ratings`
--

CREATE TABLE `order_product_ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `order_vendor_product_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `rating` decimal(4,2) DEFAULT NULL,
  `review` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_rating_files`
--

CREATE TABLE `order_product_rating_files` (
  `id` bigint UNSIGNED NOT NULL,
  `order_product_rating_id` bigint UNSIGNED NOT NULL,
  `file` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_return_requests`
--

CREATE TABLE `order_return_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `order_vendor_product_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `return_by` bigint UNSIGNED NOT NULL,
  `reason` varchar(220) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coments` text COLLATE utf8mb4_unicode_ci,
  `reason_by_vendor` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` enum('Pending','Accepted','Rejected','On-Hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_return_request_files`
--

CREATE TABLE `order_return_request_files` (
  `id` bigint UNSIGNED NOT NULL,
  `order_return_request_id` bigint UNSIGNED NOT NULL,
  `file` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_options`
--

CREATE TABLE `order_status_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1 - for order, 2 - fordispatch',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 - active, 0 - inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_options`
--

INSERT INTO `order_status_options` (`id`, `title`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Placed', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(2, 'Accepted', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(3, 'Rejected', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(4, 'Processing', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(5, 'Out For Delivery', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(6, 'Delivered', 1, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(7, 'Accept', 2, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(8, 'Reject', 2, 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_taxes`
--

CREATE TABLE `order_taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `tax_category_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_vendors`
--

CREATE TABLE `order_vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_dinein_table_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `delivery_fee` decimal(8,2) UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL COMMENT '0-Created, 1-Confirmed, 2-Dispatched, 3-Delivered',
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxable_amount` decimal(10,2) DEFAULT NULL,
  `subtotal_amount` decimal(10,2) DEFAULT NULL,
  `payable_amount` decimal(8,2) DEFAULT NULL,
  `discount_amount` decimal(8,2) DEFAULT NULL,
  `web_hook_code` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_commission_percentage_amount` decimal(10,2) DEFAULT NULL,
  `admin_commission_fixed_amount` decimal(10,2) DEFAULT NULL,
  `coupon_paid_by` tinyint DEFAULT '1' COMMENT '0-Vendor, 1-Admin',
  `payment_option_id` tinyint DEFAULT NULL,
  `dispatcher_status_option_id` tinyint UNSIGNED DEFAULT NULL,
  `order_status_option_id` tinyint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dispatch_traking_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_pre_time` int UNSIGNED DEFAULT '0',
  `user_to_vendor_time` int UNSIGNED DEFAULT '0',
  `reject_reason` mediumtext COLLATE utf8mb4_unicode_ci,
  `service_fee_percentage_amount` decimal(10,2) DEFAULT '0.00',
  `cancelled_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_vendor_products`
--

CREATE TABLE `order_vendor_products` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `order_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `taxable_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `product_dispatcher_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_date_time` datetime DEFAULT NULL,
  `product_variant_sets` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_product_order_form` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'privacy-policies', '2021-12-20 06:30:35', '2021-12-20 06:30:35'),
(2, 'terms-and-conditions', '2021-12-20 06:30:57', '2021-12-20 06:30:57'),
(3, 'vendor-registration', '2021-12-20 06:31:26', '2021-12-20 06:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `page_translations`
--

CREATE TABLE `page_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `meta_title` mediumtext COLLATE utf8mb4_unicode_ci,
  `meta_keyword` mediumtext COLLATE utf8mb4_unicode_ci,
  `meta_description` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint NOT NULL DEFAULT '0' COMMENT '0 draft and 1 for published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type_of_form` tinyint NOT NULL DEFAULT '0' COMMENT '0 for none; 1 for vendor registration; 2 for driver registration;'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_translations`
--

INSERT INTO `page_translations` (`id`, `title`, `description`, `page_id`, `language_id`, `meta_title`, `meta_keyword`, `meta_description`, `is_published`, `created_at`, `updated_at`, `type_of_form`) VALUES
(1, 'Privacy policies', '<p>This is dummy Privacy policies</p>', 1, 1, NULL, NULL, NULL, 1, '2021-12-20 06:30:35', '2021-12-20 06:30:35', 0),
(2, 'Terms and Conditions', '<p>This is dummy Terms and Conditions</p>', 2, 1, NULL, NULL, NULL, 1, '2021-12-20 06:30:57', '2021-12-20 06:30:57', 0),
(3, 'Vendor Registration', '<p>This is dummy Vendor Registration</p>', 3, 1, NULL, NULL, NULL, 1, '2021-12-20 06:31:26', '2021-12-20 06:31:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `amount` int NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance_transaction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `cart_id` bigint UNSIGNED DEFAULT NULL,
  `user_subscription_invoice_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_subscription_invoice_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_options`
--

CREATE TABLE `payment_options` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` json DEFAULT NULL COMMENT 'credentials in json format',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 inactive, 1 active, 2 delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `off_site` tinyint UNSIGNED DEFAULT '0' COMMENT '0 = on-site, 1 = off-site',
  `test_mode` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = false, 1 = true'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_options`
--

INSERT INTO `payment_options` (`id`, `code`, `path`, `title`, `credentials`, `status`, `created_at`, `updated_at`, `off_site`, `test_mode`) VALUES
(1, 'cod', '', 'Cash On Delivery', NULL, 1, NULL, '2021-12-20 06:27:43', 0, 0),
(3, 'paypal', 'omnipay/paypal', 'PayPal', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(4, 'stripe', 'omnipay/stripe', 'Stripe', NULL, 0, NULL, '2021-12-20 06:27:43', 0, 0),
(5, 'paystack', 'paystackhq/omnipay-paystack', 'Paystack', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(6, 'payfast', 'omnipay/payfast', 'Payfast', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(7, 'mobbex', 'mobbex/sdk', 'Mobbex', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(8, 'yoco', 'yoco/yoco-php-laravel', 'Yoco', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(9, 'paylink', 'paylink/paylink', 'Paylink', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(10, 'razorpay', 'razorpay/razorpay', 'Razorpay', NULL, 0, NULL, '2021-12-20 06:27:43', 0, 0),
(11, 'gcash', 'adyen/php-api-library', 'GCash', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(12, 'simplify', 'rak/simplify', 'Simplify', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(13, 'square', 'square/square', 'Square', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0),
(14, 'ozow', 'tradesafe/omnipay-ozow', 'Ozow', NULL, 0, NULL, '2021-12-20 06:27:43', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payout_options`
--

CREATE TABLE `payout_options` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` json DEFAULT NULL COMMENT 'credentials in json format',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 inactive, 1 active, 2 delete',
  `off_site` tinyint UNSIGNED DEFAULT '0' COMMENT '0 = on-site, 1 = off-site',
  `test_mode` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = false, 1 = true',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DASHBOARD', 'dashboard', 1, NULL, NULL),
(2, 'ORDERS', 'orders', 1, NULL, NULL),
(3, 'VENDORS', 'vendors', 1, NULL, NULL),
(4, 'CUSTOMERS', 'customers', 1, NULL, NULL),
(5, 'Profile', 'profile', 1, NULL, NULL),
(6, 'CUSTOMIZE', 'customize', 1, NULL, NULL),
(7, 'CONFIGURATIONS', 'configurations', 1, NULL, NULL),
(8, 'BANNER', 'banner', 1, NULL, NULL),
(9, 'CATALOG', 'catalog', 1, NULL, NULL),
(10, 'TAX', 'tax', 1, NULL, NULL),
(11, 'PAYMENT', 'payment', 1, NULL, NULL),
(12, 'PROMOCODE', 'promocode', 1, NULL, NULL),
(13, 'LOYALTY CARDS', 'loyalty_cards', 1, NULL, NULL),
(14, 'CELEBRITY', 'celebrity', 1, NULL, NULL),
(15, 'WEB STYLING', 'web_styling', 1, NULL, NULL),
(16, 'APP STYLING', 'app_styling', 1, NULL, NULL),
(17, 'Accounting Orders', 'accounting_orders', 1, NULL, NULL),
(18, 'Accounting Loyality', 'accounting_loyality', 1, NULL, NULL),
(19, 'Accounting Promo Codes', 'accounting_promo_codes', 1, NULL, NULL),
(20, 'Accounting Taxes', 'accounting_taxes', 1, NULL, NULL),
(21, 'Accounting Vendors', 'accounting_vendors', 1, NULL, NULL),
(22, 'Subscriptions Customers', 'subscriptions_customers', 1, NULL, NULL),
(23, 'Subscriptions Vendors', 'subscriptions_vendors', 1, NULL, NULL),
(24, 'CMS Pages', 'cms_pages', 1, NULL, NULL),
(25, 'CMS Emails', 'cms_emails', 1, NULL, NULL),
(26, 'Inquiries', 'inquiries', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `body_html` longtext COLLATE utf8mb4_unicode_ci,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `type_id` bigint UNSIGNED DEFAULT NULL,
  `country_origin_id` bigint UNSIGNED DEFAULT NULL,
  `is_new` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `is_featured` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `is_live` tinyint NOT NULL DEFAULT '0' COMMENT '0 - draft, 1 - published, 2 - blocked',
  `is_physical` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `weight` decimal(10,4) DEFAULT NULL,
  `weight_unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_inventory` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `has_variant` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `sell_when_out_of_stock` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `requires_shipping` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `Requires_last_mile` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `averageRating` decimal(4,2) DEFAULT NULL,
  `inquiry_only` tinyint DEFAULT '0',
  `publish_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `pharmacy_check` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `need_price_from_dispatcher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode_of_service` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delay_order_hrs` int NOT NULL DEFAULT '0',
  `delay_order_min` int NOT NULL DEFAULT '0',
  `pickup_delay_order_hrs` int NOT NULL DEFAULT '0',
  `pickup_delay_order_min` int NOT NULL DEFAULT '0',
  `dropoff_delay_order_hrs` int NOT NULL DEFAULT '0',
  `dropoff_delay_order_min` int NOT NULL DEFAULT '0',
  `need_shipment` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `title`, `url_slug`, `description`, `body_html`, `vendor_id`, `category_id`, `type_id`, `country_origin_id`, `is_new`, `is_featured`, `is_live`, `is_physical`, `weight`, `weight_unit`, `has_inventory`, `has_variant`, `sell_when_out_of_stock`, `requires_shipping`, `Requires_last_mile`, `averageRating`, `inquiry_only`, `publish_at`, `created_at`, `updated_at`, `brand_id`, `tax_category_id`, `deleted_at`, `pharmacy_check`, `tags`, `need_price_from_dispatcher`, `mode_of_service`, `delay_order_hrs`, `delay_order_min`, `pickup_delay_order_hrs`, `pickup_delay_order_min`, `dropoff_delay_order_hrs`, `dropoff_delay_order_min`, `need_shipment`) VALUES
(1, 'sku-id1633375333', '1', 'sku-id1633375333', NULL, NULL, 1, NULL, 1, NULL, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:22:13', NULL, NULL, '2021-10-04 19:22:13', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(2, 'TS001', 'Royo XL', 'TS001', NULL, '', 1, 29, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:24:08', NULL, '2021-10-04 19:24:08', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(3, 'TS002', 'Royo Platinum', 'TS002', NULL, '', 1, 29, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:25:32', NULL, '2021-10-04 19:25:32', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(4, 'TS003', 'Royo Pool', 'TS003', NULL, '', 1, 29, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:26:53', NULL, '2021-10-04 19:26:53', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(5, 'TS004', 'Royo Moto', 'TS004', NULL, '', 1, 30, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:27:36', NULL, '2021-10-04 19:27:36', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(6, 'TS005', 'Royo Auto', 'TS005', NULL, '', 1, 31, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:28:16', NULL, '2021-10-04 19:28:16', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(7, 'DEL001', NULL, 'del001', NULL, NULL, 4, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:31:03', '2021-10-04 19:29:59', '2021-10-04 19:31:03', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(8, 'DEL002', NULL, 'del002', NULL, NULL, 4, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:31:58', '2021-10-04 19:31:14', '2021-10-04 19:31:58', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(9, 'DEL003', NULL, 'del003', NULL, NULL, 4, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 19:32:48', '2021-10-04 19:32:09', '2021-10-04 19:32:48', NULL, NULL, NULL, 0, 'foodservice', '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(10, 'IT100', 'Pizza Fritta', 'IT100', NULL, '', 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:41:45', NULL, '2021-10-11 11:16:19', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(11, 'IT1011633376398', 'Pizza Napoletana', 'IT1011633376398', NULL, '', 2, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:39:58', NULL, NULL, '2021-10-04 19:39:58', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(12, 'IT102', 'Tomato Bacon Pasta', 'IT102', NULL, '', 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:42:52', NULL, '2021-10-11 11:15:50', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(13, 'IT103', 'Pasta Carbonara', 'IT103', NULL, '', 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:43:49', NULL, '2021-10-04 19:43:49', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(14, 'IT1041633376365', 'Margherita pizza', 'IT1041633376365', NULL, '', 2, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:39:25', NULL, NULL, '2021-10-04 19:39:25', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(15, 'IT1051633376381', 'Mushroom Risotto', 'IT1051633376381', NULL, '', 2, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:39:41', NULL, NULL, '2021-10-04 19:39:41', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(16, 'IT106', 'Focaccia Bread ', 'IT106', NULL, '', 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:44:36', NULL, '2021-10-04 19:44:36', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(17, 'IT1071633376406', 'Lasagna ', 'IT1071633376406', NULL, '', 2, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:40:06', NULL, NULL, '2021-10-04 19:40:06', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(18, 'IT1081633376415', 'Panzanella', 'IT1081633376415', NULL, '', 2, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:40:15', NULL, NULL, '2021-10-04 19:40:15', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(19, 'IT1091633376371', 'Burrata ', 'IT1091633376371', NULL, '', 2, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:39:31', NULL, NULL, '2021-10-04 19:39:31', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(20, 'CH200', 'Dim Sums', 'CH200', NULL, '', 2, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:46:56', NULL, '2021-10-04 19:46:56', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(21, 'CH202', 'Chicken with Chestnuts', 'CH202', NULL, '', 2, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:47:53', NULL, '2021-10-04 19:47:53', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(22, 'CH203', 'Spring Rolls', 'CH203', NULL, '', 2, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:48:54', NULL, '2021-10-04 19:48:54', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(23, 'CH204', 'Stir Fried Tofu with Rice', 'CH204', NULL, '', 2, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:49:46', NULL, '2021-10-04 19:49:46', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(24, 'CH2051633376730', 'Hot Sour Soup', 'CH2051633376730', NULL, '', 2, 16, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:45:30', NULL, NULL, '2021-10-04 19:45:30', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(25, 'CH2061633376737', 'Sichuan Pork', 'CH2061633376737', NULL, '', 2, 16, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:45:37', NULL, NULL, '2021-10-04 19:45:37', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(26, 'CH2071633376743', 'Shrimp with Vermicelli and Garlic', 'CH2071633376743', NULL, '', 2, 16, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:45:43', NULL, NULL, '2021-10-04 19:45:43', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(27, 'CH2081633376749', 'Peking Roasted Duck', 'CH2081633376749', NULL, '', 2, 16, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:45:49', NULL, NULL, '2021-10-04 19:45:49', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(28, 'CH2091633376755', 'Steamed Vermicelli Rolls', 'CH2091633376755', NULL, '', 2, 16, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:45:55', NULL, NULL, '2021-10-04 19:45:55', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(29, 'CH2101633376762', 'Fried Shrimp with Cashew Nuts', 'CH2101633376762', NULL, '', 2, 16, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 19:46:02', NULL, NULL, '2021-10-04 19:46:02', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(30, 'CN500', 'Prawn Pie', 'CN500', NULL, '', 3, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:53:32', NULL, '2021-10-11 10:59:35', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(31, 'CN502', 'Crispy Calamari Rings', 'CN502', NULL, '', 3, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:54:13', NULL, '2021-10-11 10:59:57', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(32, 'CN503', 'Yorkshire Lamb Patties', 'CN503', NULL, '', 3, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:56:13', NULL, '2021-10-11 11:01:11', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(33, 'FR600', 'Yellow Bananas ', 'FR600', NULL, '', 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 19:59:52', NULL, '2021-10-08 12:53:16', 2, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(34, 'FR601', 'Raspberries Mexico ', 'FR601', NULL, '', 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:00:10', NULL, '2021-10-08 12:53:05', 2, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(35, 'FR602', 'Pears Forelle ', 'FR602', NULL, '', 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:00:31', NULL, '2021-10-08 12:53:29', 2, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(36, 'FR603', 'Tomato Bunch ', 'FR603', NULL, '', 5, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:00:50', NULL, '2021-10-08 12:53:42', 2, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(37, 'FR604', 'Sweet Potato Australia', 'FR604', NULL, '', 5, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:01:09', NULL, '2021-10-11 11:06:22', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(38, 'FR605', 'Cauliflower ', 'FR605', NULL, '', 5, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:01:26', NULL, '2021-10-11 11:05:58', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(39, 'DE110', 'Choco lava cake', 'DE110', NULL, '', 5, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:02:42', NULL, '2021-10-08 12:51:45', 2, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(40, 'DE111', 'Black Forest', 'DE111', NULL, '', 5, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:03:30', NULL, '2021-10-08 12:52:32', 2, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(41, 'D202', NULL, 'd202', NULL, NULL, 5, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:04:26', '2021-10-04 20:03:55', '2021-10-08 12:52:46', 2, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(42, 'PH200', 'Vitamin C + Zinc', 'PH200', NULL, '', 6, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:07:37', NULL, '2021-10-08 12:49:53', 1, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(43, 'PH201', 'Folic B9', 'PH201', NULL, '', 6, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:08:36', NULL, '2021-10-08 12:50:03', 1, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(44, 'PH202', 'Casein Protein', 'PH202', NULL, '', 6, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:09:11', NULL, '2021-10-08 12:50:22', 1, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(45, 'PH206', 'Thermometer', 'PH206', NULL, '', 6, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:12:04', NULL, '2021-10-08 12:50:34', 1, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(46, 'PH207', 'Pulse Oximeter', 'PH207', NULL, '', 6, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:15:15', NULL, '2021-10-08 12:50:45', 1, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(47, 'PH208', 'Breathe Free Vaporizer', 'PH208', NULL, '', 6, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:15:36', NULL, '2021-10-08 12:49:30', 1, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(48, 'EL806', 'Asus Zenbook 14', 'EL806', NULL, '', 7, 21, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:20:54', NULL, '2021-10-11 10:43:56', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(49, 'EL807', 'HP Pavilion 14', 'EL807', NULL, '', 7, 21, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:23:04', NULL, '2021-10-11 10:44:03', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(50, 'EL808', 'Macbook Pro M1 Chip', 'EL808', NULL, '', 7, 21, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:21:30', NULL, '2021-10-11 10:43:28', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(51, 'MB900', NULL, 'mb900', NULL, NULL, 7, 20, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:25:26', '2021-10-04 20:24:03', '2021-10-11 10:45:46', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(52, 'MB901', NULL, 'mb901', NULL, NULL, 7, 20, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:27:36', '2021-10-04 20:25:57', '2021-10-11 10:46:30', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(53, 'MB902', NULL, 'mb902', NULL, NULL, 7, 20, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:29:46', '2021-10-04 20:27:53', '2021-10-11 10:47:10', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(54, 'MEN301', NULL, 'men301', NULL, NULL, 8, 27, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:33:12', '2021-10-04 20:32:42', '2021-10-08 12:47:07', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(55, 'MEN302', NULL, 'men302', NULL, NULL, 8, 27, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:33:58', '2021-10-04 20:33:28', '2021-10-08 12:47:18', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(56, 'MEN303', NULL, 'men303', NULL, NULL, 8, 27, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:34:39', '2021-10-04 20:34:10', '2021-10-08 12:47:29', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(57, 'WOMEN304', NULL, 'women304', NULL, NULL, 8, 28, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:38:53', '2021-10-04 20:35:45', '2021-10-08 12:47:42', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(58, 'WOMEN305', NULL, 'women305', NULL, NULL, 8, 28, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:37:30', '2021-10-04 20:36:48', '2021-10-08 12:48:26', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(59, 'WOMEN306', NULL, 'women306', NULL, NULL, 8, 28, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:38:05', '2021-10-04 20:37:43', '2021-10-08 12:48:36', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(60, 'AC100', 'Split Ac Service', 'AC100', NULL, '', 9, 23, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-10-04 20:51:29', NULL, '2021-10-05 05:47:31', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(61, 'AC101', 'Window Ac Service', 'AC101', NULL, '', 9, 23, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:52:29', NULL, '2021-10-04 20:52:29', NULL, NULL, NULL, 0, 'foodservice', '0', 'schedule', 0, 0, 0, 0, 0, 0, 0),
(62, 'AC1021633380563', 'Split Ac Installation', 'AC1021633380563', NULL, '', 9, 23, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 20:49:23', NULL, NULL, '2021-10-04 20:49:23', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(63, 'AC1031633380570', 'Window Ac Installation', 'AC1031633380570', NULL, '', 9, 23, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 20:49:30', NULL, NULL, '2021-10-04 20:49:30', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(64, 'CL111', 'Full Home Cleaning', 'CL111', NULL, '', 9, 24, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:53:02', NULL, '2021-10-04 20:53:02', NULL, NULL, NULL, 0, 'foodservice', '0', 'schedule', 0, 0, 0, 0, 0, 0, 0),
(65, 'CL112', 'Bathroom Cleaning', 'CL112', NULL, '', 9, 24, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:55:31', NULL, '2021-10-04 20:55:31', NULL, NULL, NULL, 0, 'foodservice', '0', 'schedule', 0, 0, 0, 0, 0, 0, 0),
(66, 'CL113', 'Car Cleaning', 'CL113', NULL, '', 9, 24, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:56:03', NULL, '2021-10-04 20:56:03', NULL, NULL, NULL, 0, 'foodservice', '0', 'schedule', 0, 0, 0, 0, 0, 0, 0),
(67, 'CL1141633380586', 'Sofa & Carpet Cleaning', 'CL1141633380586', NULL, '', 9, 24, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-10-04 20:49:46', NULL, NULL, '2021-10-04 20:49:46', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(68, 'PC280', 'Bed bugs control', 'PC280', NULL, '', 9, 25, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:56:38', NULL, '2021-10-04 20:56:38', NULL, NULL, NULL, 0, 'foodservice', '0', 'schedule', 0, 0, 0, 0, 0, 0, 0),
(69, 'PC281', 'Cockroaches pest control', 'PC281', NULL, '', 9, 25, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:57:05', NULL, '2021-10-04 20:57:05', NULL, NULL, NULL, 0, 'foodservice', '0', 'schedule', 0, 0, 0, 0, 0, 0, 0),
(70, 'PC282', 'Termite control', 'PC282', NULL, '', 9, 25, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-10-04 20:57:31', NULL, '2021-10-04 20:57:31', NULL, NULL, NULL, 0, 'foodservice', '0', 'schedule', 0, 0, 0, 0, 0, 0, 0),
(71, 'Shirt', NULL, 'Shirt', NULL, NULL, 8, 27, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:19:05', '2021-10-11 05:39:06', '2021-10-11 06:23:14', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(72, 'CasualT-shirts', NULL, 'casualt-shirts', NULL, NULL, 8, 27, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:19:36', '2021-10-11 05:45:30', '2021-10-11 06:23:35', 3, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(73, 'Capri', NULL, 'capris', NULL, NULL, 8, 27, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 05:54:17', '2021-10-11 05:49:45', '2021-10-11 05:54:17', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(74, 'WomenCapri1633932140', NULL, 'womencapri1633932140', NULL, NULL, 8, 28, 1, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, '2021-10-11 06:01:33', '2021-10-11 06:02:20', NULL, NULL, '2021-10-11 06:02:20', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0),
(75, 'Shorts', NULL, 'shorts', NULL, NULL, 8, 28, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:15:07', '2021-10-11 06:03:22', '2021-10-11 06:15:07', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(76, 'Scirt', NULL, 'scirt', NULL, NULL, 8, 28, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:18:24', '2021-10-11 06:16:53', '2021-10-11 06:18:24', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(77, 'Sweaters', NULL, 'sweaters', NULL, NULL, 8, 28, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:22:36', '2021-10-11 06:20:16', '2021-10-11 06:22:36', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(78, 'Inspiron14Laptop', NULL, 'inspiron14laptop', NULL, NULL, 7, 21, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:31:29', '2021-10-11 06:29:40', '2021-10-11 06:31:29', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(79, 'LenovoIdeaPadSlim310thGen', NULL, 'lenovoideapadslim310thgen', NULL, NULL, 7, 21, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:37:39', '2021-10-11 06:34:21', '2021-10-11 06:37:39', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(80, 'AcerNitro5GamingLaptopAMD', NULL, 'acernitro5gaminglaptopamd', NULL, NULL, 7, 21, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:41:34', '2021-10-11 06:39:03', '2021-10-11 06:41:34', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(81, 'OnePlus9R5G', NULL, 'oneplus9r5g', NULL, NULL, 7, 20, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:45:28', '2021-10-11 06:42:44', '2021-10-11 06:45:28', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(82, 'Samsung Galaxy S20 FE 5G', NULL, 'Samsung Galaxy S20 FE 5G', NULL, NULL, 7, 20, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 06:57:57', '2021-10-11 06:48:14', '2021-10-11 06:57:57', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(83, 'OPPOF19Pro', NULL, 'OPPO F19 Prooppof19pro', NULL, NULL, 7, 20, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:03:04', '2021-10-11 07:00:01', '2021-10-11 07:03:04', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(84, 'VitaminB-12', NULL, 'vitaminb-12', NULL, NULL, 6, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:08:23', '2021-10-11 07:05:53', '2021-10-11 07:08:23', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(85, 'HempProtein', NULL, 'hempprotein', NULL, NULL, 6, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:12:09', '2021-10-11 07:09:53', '2021-10-11 07:12:09', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(86, 'BrownRiceProtein1633951999', NULL, 'Brown Rice Protein1633951999', NULL, NULL, 6, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:16:51', '2021-10-11 07:13:17', '2021-10-11 11:33:19', NULL, NULL, '2021-10-11 11:33:19', 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(87, 'BloodGlucoseMonitors', NULL, 'Blood Glucose Monitors:', NULL, NULL, 6, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:21:32', '2021-10-11 07:19:10', '2021-10-11 07:21:32', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(88, 'PedometersAndWeighingScales', NULL, 'Pedometers And Weighing Scales', NULL, NULL, 6, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:26:27', '2021-10-11 07:22:15', '2021-10-11 07:26:27', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(89, 'Apples', NULL, 'apples', NULL, NULL, 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:32:36', '2021-10-11 07:29:40', '2021-10-11 07:32:36', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(90, 'watermelon', NULL, 'watermelon', NULL, NULL, 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:36:35', '2021-10-11 07:33:24', '2021-10-11 07:36:35', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(91, 'cabbage', NULL, 'cabbage', NULL, NULL, 5, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:41:27', '2021-10-11 07:38:46', '2021-10-11 07:41:27', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(92, 'broccoli', NULL, 'broccoli', NULL, NULL, 5, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:44:39', '2021-10-11 07:42:27', '2021-10-11 07:44:39', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(93, 'cheese', NULL, 'cheese', NULL, NULL, 5, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:49:31', '2021-10-11 07:48:03', '2021-10-11 07:49:31', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(94, 'milkshake', NULL, 'milk shake', NULL, NULL, 5, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 07:54:04', '2021-10-11 07:52:05', '2021-10-11 07:54:04', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(95, 'RoestiAndSalad', NULL, 'Roesti And Salad', NULL, NULL, 3, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 08:44:42', '2021-10-11 08:42:31', '2021-10-11 08:44:42', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(96, 'PaneerSteak', NULL, 'Paneer Steak', NULL, NULL, 3, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 08:50:55', '2021-10-11 08:45:11', '2021-10-11 08:50:55', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(97, 'AppleSausagePlait.', NULL, 'Apple Sausage Plait.', NULL, NULL, 3, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 08:56:22', '2021-10-11 08:52:12', '2021-10-11 08:56:22', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(98, 'Polenta', NULL, 'Polenta.', NULL, NULL, 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 09:01:57', '2021-10-11 08:59:48', '2021-10-11 09:01:57', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(99, 'Ossobuco', NULL, 'Osso buco', NULL, NULL, 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 09:06:37', '2021-10-11 09:02:33', '2021-10-11 09:06:37', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(100, 'QuickNoodles', NULL, 'quicknoodles', NULL, NULL, 2, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 09:10:06', '2021-10-11 09:07:23', '2021-10-11 09:10:06', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0),
(101, 'SzechwanChilliChicken', NULL, 'szechwanchillichicken', NULL, NULL, 2, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-11 09:14:13', '2021-10-11 09:11:13', '2021-10-11 09:14:13', NULL, NULL, NULL, 0, NULL, '0', NULL, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_addons`
--

CREATE TABLE `product_addons` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `addon_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_addons`
--

INSERT INTO `product_addons` (`product_id`, `addon_id`, `created_at`, `updated_at`) VALUES
(64, 2, NULL, NULL),
(65, 4, NULL, NULL),
(66, 3, NULL, NULL),
(54, 5, NULL, NULL),
(55, 6, NULL, NULL),
(56, 7, NULL, NULL),
(57, 8, NULL, NULL),
(58, 9, NULL, NULL),
(59, 10, NULL, NULL),
(77, 16, NULL, NULL),
(76, 15, NULL, NULL),
(75, 14, NULL, NULL),
(73, 13, NULL, NULL),
(72, 12, NULL, NULL),
(71, 11, NULL, NULL),
(50, 19, NULL, NULL),
(48, 17, NULL, NULL),
(49, 18, NULL, NULL),
(51, 20, NULL, NULL),
(52, 21, NULL, NULL),
(53, 22, NULL, NULL),
(81, 27, NULL, NULL),
(82, 28, NULL, NULL),
(83, 29, NULL, NULL),
(42, 30, NULL, NULL),
(43, 31, NULL, NULL),
(44, 32, NULL, NULL),
(45, 33, NULL, NULL),
(46, 34, NULL, NULL),
(47, 35, NULL, NULL),
(85, 37, NULL, NULL),
(86, 38, NULL, NULL),
(87, 39, NULL, NULL),
(88, 41, NULL, NULL),
(30, 57, NULL, NULL),
(78, 24, NULL, NULL),
(31, 58, NULL, NULL),
(32, 59, NULL, NULL),
(94, 56, NULL, NULL),
(93, 55, NULL, NULL),
(92, 54, NULL, NULL),
(79, 25, NULL, NULL),
(91, 53, NULL, NULL),
(90, 52, NULL, NULL),
(89, 51, NULL, NULL),
(41, 50, NULL, NULL),
(40, 49, NULL, NULL),
(39, 48, NULL, NULL),
(38, 47, NULL, NULL),
(37, 46, NULL, NULL),
(36, 45, NULL, NULL),
(35, 44, NULL, NULL),
(34, 43, NULL, NULL),
(33, 42, NULL, NULL),
(95, 60, NULL, NULL),
(96, 61, NULL, NULL),
(97, 62, NULL, NULL),
(101, 74, NULL, NULL),
(100, 73, NULL, NULL),
(99, 72, NULL, NULL),
(98, 71, NULL, NULL),
(80, 26, NULL, NULL),
(23, 70, NULL, NULL),
(22, 69, NULL, NULL),
(21, 68, NULL, NULL),
(20, 67, NULL, NULL),
(16, 66, NULL, NULL),
(13, 65, NULL, NULL),
(12, 64, NULL, NULL),
(10, 63, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, NULL),
(2, 29, NULL, NULL),
(2, 29, NULL, NULL),
(3, 29, NULL, NULL),
(2, 29, NULL, NULL),
(3, 29, NULL, NULL),
(4, 29, NULL, NULL),
(2, 29, NULL, NULL),
(3, 29, NULL, NULL),
(4, 29, NULL, NULL),
(5, 30, NULL, NULL),
(2, 29, NULL, NULL),
(3, 29, NULL, NULL),
(4, 29, NULL, NULL),
(5, 30, NULL, NULL),
(6, 31, NULL, NULL),
(7, 2, '2021-10-04 19:29:59', '2021-10-04 19:29:59'),
(8, 2, '2021-10-04 19:31:14', '2021-10-04 19:31:14'),
(9, 2, '2021-10-04 19:32:09', '2021-10-04 19:32:09'),
(10, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(13, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(13, 14, NULL, NULL),
(14, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(13, 14, NULL, NULL),
(14, 14, NULL, NULL),
(15, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(13, 14, NULL, NULL),
(14, 14, NULL, NULL),
(15, 14, NULL, NULL),
(16, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(13, 14, NULL, NULL),
(14, 14, NULL, NULL),
(15, 14, NULL, NULL),
(16, 14, NULL, NULL),
(17, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(13, 14, NULL, NULL),
(14, 14, NULL, NULL),
(15, 14, NULL, NULL),
(16, 14, NULL, NULL),
(17, 14, NULL, NULL),
(18, 14, NULL, NULL),
(10, 14, NULL, NULL),
(11, 14, NULL, NULL),
(12, 14, NULL, NULL),
(13, 14, NULL, NULL),
(14, 14, NULL, NULL),
(15, 14, NULL, NULL),
(16, 14, NULL, NULL),
(17, 14, NULL, NULL),
(18, 14, NULL, NULL),
(19, 14, NULL, NULL),
(20, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(23, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(23, 16, NULL, NULL),
(24, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(23, 16, NULL, NULL),
(24, 16, NULL, NULL),
(25, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(23, 16, NULL, NULL),
(24, 16, NULL, NULL),
(25, 16, NULL, NULL),
(26, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(23, 16, NULL, NULL),
(24, 16, NULL, NULL),
(25, 16, NULL, NULL),
(26, 16, NULL, NULL),
(27, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(23, 16, NULL, NULL),
(24, 16, NULL, NULL),
(25, 16, NULL, NULL),
(26, 16, NULL, NULL),
(27, 16, NULL, NULL),
(28, 16, NULL, NULL),
(20, 16, NULL, NULL),
(21, 16, NULL, NULL),
(22, 16, NULL, NULL),
(23, 16, NULL, NULL),
(24, 16, NULL, NULL),
(25, 16, NULL, NULL),
(26, 16, NULL, NULL),
(27, 16, NULL, NULL),
(28, 16, NULL, NULL),
(29, 16, NULL, NULL),
(30, 15, NULL, NULL),
(30, 15, NULL, NULL),
(31, 15, NULL, NULL),
(30, 15, NULL, NULL),
(31, 15, NULL, NULL),
(32, 15, NULL, NULL),
(33, 9, NULL, NULL),
(33, 9, NULL, NULL),
(34, 9, NULL, NULL),
(33, 9, NULL, NULL),
(34, 9, NULL, NULL),
(35, 9, NULL, NULL),
(33, 9, NULL, NULL),
(34, 9, NULL, NULL),
(35, 9, NULL, NULL),
(36, 8, NULL, NULL),
(33, 9, NULL, NULL),
(34, 9, NULL, NULL),
(35, 9, NULL, NULL),
(36, 8, NULL, NULL),
(37, 8, NULL, NULL),
(33, 9, NULL, NULL),
(34, 9, NULL, NULL),
(35, 9, NULL, NULL),
(36, 8, NULL, NULL),
(37, 8, NULL, NULL),
(38, 8, NULL, NULL),
(39, 10, NULL, NULL),
(39, 10, NULL, NULL),
(40, 10, NULL, NULL),
(41, 10, '2021-10-04 20:03:55', '2021-10-04 20:03:55'),
(42, 17, NULL, NULL),
(42, 17, NULL, NULL),
(43, 17, NULL, NULL),
(42, 17, NULL, NULL),
(43, 17, NULL, NULL),
(44, 17, NULL, NULL),
(42, 17, NULL, NULL),
(43, 17, NULL, NULL),
(44, 17, NULL, NULL),
(45, 18, NULL, NULL),
(42, 17, NULL, NULL),
(43, 17, NULL, NULL),
(44, 17, NULL, NULL),
(45, 18, NULL, NULL),
(46, 18, NULL, NULL),
(42, 17, NULL, NULL),
(43, 17, NULL, NULL),
(44, 17, NULL, NULL),
(45, 18, NULL, NULL),
(46, 18, NULL, NULL),
(47, 18, NULL, NULL),
(48, 21, NULL, NULL),
(48, 21, NULL, NULL),
(49, 21, NULL, NULL),
(48, 21, NULL, NULL),
(49, 21, NULL, NULL),
(50, 21, NULL, NULL),
(51, 20, '2021-10-04 20:24:03', '2021-10-04 20:24:03'),
(52, 20, '2021-10-04 20:25:57', '2021-10-04 20:25:57'),
(53, 20, '2021-10-04 20:27:53', '2021-10-04 20:27:53'),
(54, 27, '2021-10-04 20:32:42', '2021-10-04 20:32:42'),
(55, 27, '2021-10-04 20:33:28', '2021-10-04 20:33:28'),
(56, 27, '2021-10-04 20:34:10', '2021-10-04 20:34:10'),
(57, 28, '2021-10-04 20:35:45', '2021-10-04 20:35:45'),
(58, 28, '2021-10-04 20:36:48', '2021-10-04 20:36:48'),
(59, 28, '2021-10-04 20:37:43', '2021-10-04 20:37:43'),
(60, 23, NULL, NULL),
(60, 23, NULL, NULL),
(61, 23, NULL, NULL),
(60, 23, NULL, NULL),
(61, 23, NULL, NULL),
(62, 23, NULL, NULL),
(60, 23, NULL, NULL),
(61, 23, NULL, NULL),
(62, 23, NULL, NULL),
(63, 23, NULL, NULL),
(64, 24, NULL, NULL),
(64, 24, NULL, NULL),
(65, 24, NULL, NULL),
(64, 24, NULL, NULL),
(65, 24, NULL, NULL),
(66, 24, NULL, NULL),
(64, 24, NULL, NULL),
(65, 24, NULL, NULL),
(66, 24, NULL, NULL),
(67, 24, NULL, NULL),
(68, 25, NULL, NULL),
(68, 25, NULL, NULL),
(69, 25, NULL, NULL),
(68, 25, NULL, NULL),
(69, 25, NULL, NULL),
(70, 25, NULL, NULL),
(71, 27, '2021-10-11 05:39:06', '2021-10-11 05:39:06'),
(72, 27, '2021-10-11 05:45:30', '2021-10-11 05:45:30'),
(73, 27, '2021-10-11 05:49:45', '2021-10-11 05:49:45'),
(74, 28, '2021-10-11 06:01:33', '2021-10-11 06:01:33'),
(75, 28, '2021-10-11 06:03:22', '2021-10-11 06:03:22'),
(76, 28, '2021-10-11 06:16:53', '2021-10-11 06:16:53'),
(77, 28, '2021-10-11 06:20:16', '2021-10-11 06:20:16'),
(78, 21, '2021-10-11 06:29:40', '2021-10-11 06:29:40'),
(79, 21, '2021-10-11 06:34:21', '2021-10-11 06:34:21'),
(80, 21, '2021-10-11 06:39:03', '2021-10-11 06:39:03'),
(81, 20, '2021-10-11 06:42:44', '2021-10-11 06:42:44'),
(82, 20, '2021-10-11 06:48:14', '2021-10-11 06:48:14'),
(83, 20, '2021-10-11 07:00:01', '2021-10-11 07:00:01'),
(84, 17, '2021-10-11 07:05:53', '2021-10-11 07:05:53'),
(85, 17, '2021-10-11 07:09:53', '2021-10-11 07:09:53'),
(86, 17, '2021-10-11 07:13:17', '2021-10-11 07:13:17'),
(87, 18, '2021-10-11 07:19:10', '2021-10-11 07:19:10'),
(88, 18, '2021-10-11 07:22:15', '2021-10-11 07:22:15'),
(89, 9, '2021-10-11 07:29:40', '2021-10-11 07:29:40'),
(90, 9, '2021-10-11 07:33:24', '2021-10-11 07:33:24'),
(91, 8, '2021-10-11 07:38:46', '2021-10-11 07:38:46'),
(92, 8, '2021-10-11 07:42:27', '2021-10-11 07:42:27'),
(93, 10, '2021-10-11 07:48:03', '2021-10-11 07:48:03'),
(94, 10, '2021-10-11 07:52:05', '2021-10-11 07:52:05'),
(95, 15, '2021-10-11 08:42:31', '2021-10-11 08:42:31'),
(96, 15, '2021-10-11 08:45:11', '2021-10-11 08:45:11'),
(97, 15, '2021-10-11 08:52:12', '2021-10-11 08:52:12'),
(98, 14, '2021-10-11 08:59:48', '2021-10-11 08:59:48'),
(99, 14, '2021-10-11 09:02:33', '2021-10-11 09:02:33'),
(100, 16, '2021-10-11 09:07:23', '2021-10-11 09:07:23'),
(101, 16, '2021-10-11 09:11:13', '2021-10-11 09:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `product_celebrities`
--

CREATE TABLE `product_celebrities` (
  `celebrity_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_cross_sells`
--

CREATE TABLE `product_cross_sells` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `cross_product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_faqs`
--

CREATE TABLE `product_faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `is_required` int NOT NULL DEFAULT '1' COMMENT '0 means not required, 1 means required',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_faq_translations`
--

CREATE TABLE `product_faq_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `product_faq_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `media_id` bigint UNSIGNED DEFAULT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `media_id`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, NULL, NULL),
(2, 3, 2, 1, NULL, NULL),
(3, 4, 3, 1, NULL, NULL),
(4, 5, 4, 1, NULL, NULL),
(5, 6, 5, 1, NULL, NULL),
(6, 7, 6, 1, NULL, NULL),
(7, 8, 7, 1, NULL, NULL),
(8, 9, 8, 1, NULL, NULL),
(10, 11, 10, 1, '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(13, 14, 13, 1, '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(14, 15, 14, 1, '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(16, 17, 16, 1, '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(17, 18, 17, 1, '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(18, 19, 18, 1, '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(19, 10, 19, 1, NULL, NULL),
(20, 12, 20, 1, NULL, NULL),
(21, 13, 21, 1, NULL, NULL),
(22, 16, 22, 1, NULL, NULL),
(27, 24, 27, 1, '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(28, 25, 28, 1, '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(29, 26, 29, 1, '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(30, 27, 30, 1, '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(31, 28, 31, 1, '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(32, 29, 32, 1, '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(33, 20, 33, 1, NULL, NULL),
(34, 21, 34, 1, NULL, NULL),
(35, 22, 35, 1, NULL, NULL),
(36, 23, 36, 1, NULL, NULL),
(37, 30, 37, 1, NULL, NULL),
(38, 31, 38, 1, NULL, NULL),
(39, 32, 39, 1, NULL, NULL),
(42, 33, 42, 1, NULL, NULL),
(43, 34, 43, 1, NULL, NULL),
(44, 35, 44, 1, NULL, NULL),
(45, 36, 45, 1, NULL, NULL),
(46, 37, 46, 1, NULL, NULL),
(47, 38, 47, 1, NULL, NULL),
(48, 39, 48, 1, NULL, NULL),
(49, 40, 49, 1, NULL, NULL),
(50, 41, 50, 1, NULL, NULL),
(56, 48, 56, 1, NULL, NULL),
(57, 50, 57, 1, NULL, NULL),
(58, 49, 58, 1, NULL, NULL),
(59, 51, 59, 1, NULL, NULL),
(60, 52, 60, 1, NULL, NULL),
(61, 53, 61, 1, NULL, NULL),
(62, 60, 62, 1, NULL, NULL),
(63, 61, 63, 1, NULL, NULL),
(64, 64, 64, 1, NULL, NULL),
(65, 65, 65, 1, NULL, NULL),
(66, 66, 66, 1, NULL, NULL),
(67, 68, 67, 1, NULL, NULL),
(68, 69, 68, 1, NULL, NULL),
(69, 70, 69, 1, NULL, NULL),
(70, 54, 70, 1, NULL, NULL),
(71, 55, 71, 1, NULL, NULL),
(72, 56, 72, 1, NULL, NULL),
(73, 57, 73, 1, NULL, NULL),
(74, 58, 74, 1, NULL, NULL),
(75, 59, 75, 1, NULL, NULL),
(77, 43, 77, 1, NULL, NULL),
(79, 45, 79, 1, NULL, NULL),
(80, 46, 80, 1, NULL, NULL),
(81, 47, 81, 1, NULL, NULL),
(82, 44, 82, 1, NULL, NULL),
(83, 42, 83, 1, NULL, NULL),
(84, 71, 84, 1, NULL, NULL),
(85, 72, 85, 1, NULL, NULL),
(86, 73, 86, 1, NULL, NULL),
(87, 75, 87, 1, NULL, NULL),
(88, 76, 88, 1, NULL, NULL),
(89, 77, 89, 1, NULL, NULL),
(93, 81, 93, 1, NULL, NULL),
(94, 82, 94, 1, NULL, NULL),
(95, 83, 95, 1, NULL, NULL),
(96, 84, 96, 1, NULL, NULL),
(97, 85, 97, 1, NULL, NULL),
(98, 86, 98, 1, NULL, NULL),
(99, 87, 99, 1, NULL, NULL),
(100, 88, 100, 1, NULL, NULL),
(101, 89, 101, 1, NULL, NULL),
(102, 90, 102, 1, NULL, NULL),
(103, 91, 103, 1, NULL, NULL),
(104, 92, 104, 1, NULL, NULL),
(105, 93, 105, 1, NULL, NULL),
(106, 94, 106, 1, NULL, NULL),
(107, 95, 107, 1, NULL, NULL),
(108, 96, 108, 1, NULL, NULL),
(109, 97, 109, 1, NULL, NULL),
(110, 98, 110, 1, NULL, NULL),
(111, 99, 111, 1, NULL, NULL),
(112, 100, 112, 1, NULL, NULL),
(113, 101, 113, 1, NULL, NULL),
(114, 78, 114, 1, NULL, NULL),
(115, 78, 115, 1, NULL, NULL),
(117, 79, 117, 1, NULL, NULL),
(118, 80, 118, 1, NULL, NULL),
(119, 80, 119, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_inquiries`
--

CREATE TABLE `product_inquiries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_related`
--

CREATE TABLE `product_related` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `related_product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tags`
--

CREATE TABLE `product_tags` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `tag_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_translations`
--

CREATE TABLE `product_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_html` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_translations`
--

INSERT INTO `product_translations` (`id`, `title`, `body_html`, `meta_title`, `meta_keyword`, `meta_description`, `product_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Xiaomi', NULL, 'Xiaomi', 'Xiaomi', NULL, 1, 1, NULL, '2021-10-06 09:46:51'),
(2, 'Royo XL', NULL, NULL, NULL, NULL, 2, 1, NULL, '2021-10-07 06:26:47'),
(3, 'Royo XL', '', '', '', '', 2, 1, NULL, '2021-10-06 09:46:51'),
(4, 'Royo Platinum', NULL, NULL, NULL, NULL, 3, 1, NULL, '2021-10-06 09:46:51'),
(5, 'Royo XL', '', '', '', '', 2, 1, NULL, '2021-10-06 09:46:51'),
(6, 'Royo Platinum', '', '', '', '', 3, 1, NULL, '2021-10-06 09:46:51'),
(7, 'Royo Pool', NULL, NULL, NULL, NULL, 4, 1, NULL, '2021-10-06 09:46:51'),
(8, 'Royo XL', '', '', '', '', 2, 1, NULL, '2021-10-06 09:46:51'),
(9, 'Royo Platinum', '', '', '', '', 3, 1, NULL, '2021-10-06 09:46:51'),
(10, 'Royo Pool', '', '', '', '', 4, 1, NULL, '2021-10-06 09:46:51'),
(11, 'Royo Moto', NULL, NULL, NULL, NULL, 5, 1, NULL, '2021-10-06 09:46:51'),
(12, 'Royo XL', '', '', '', '', 2, 1, NULL, '2021-10-06 09:46:51'),
(13, 'Royo Platinum', '', '', '', '', 3, 1, NULL, '2021-10-06 09:46:51'),
(14, 'Royo Pool', '', '', '', '', 4, 1, NULL, '2021-10-06 09:46:51'),
(15, 'Royo Moto', '', '', '', '', 5, 1, NULL, '2021-10-06 09:46:51'),
(16, 'Royo Auto', NULL, NULL, NULL, NULL, 6, 1, NULL, '2021-10-06 09:46:51'),
(17, 'Small Box', NULL, NULL, NULL, NULL, 7, 1, NULL, '2021-10-06 09:46:51'),
(18, 'Medium Box', NULL, NULL, NULL, NULL, 8, 1, NULL, '2021-10-06 09:46:51'),
(19, 'Large Box', NULL, NULL, NULL, NULL, 9, 1, NULL, '2021-10-06 09:46:51'),
(20, 'Pizza Fritta', '<p>Fritta Pizza&nbsp;starts and ends with an Italian crust that&#39;s been fried to a light caramel color, giving the dough an incredible flavor.</p>', NULL, NULL, NULL, 10, 1, NULL, '2021-10-06 14:58:41'),
(21, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(22, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(23, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(24, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(25, 'Tomato Bacon Pasta', '<p>Tomato Bacon Pasta is a quintessential example of how to make a quick pasta dish that&rsquo;s totally slurp-worthy with just a few ingredients.</p>', NULL, NULL, NULL, 12, 1, NULL, '2021-10-07 06:08:50'),
(26, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(27, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(28, 'Tomato Bacon Pasta', '', '', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(29, 'Pasta Carbonara', '<p>Carbonara is an Italian pasta dish from Rome made with eggs, hard cheese, cured pork, and black pepper.</p>', NULL, NULL, NULL, 13, 1, NULL, '2021-10-07 06:10:05'),
(30, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(31, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(32, 'Tomato Bacon Pasta', '', '', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(33, 'Pasta Carbonara', '', '', '', '', 13, 1, NULL, '2021-10-06 09:46:51'),
(34, 'Margherita pizza', '', '', '', '', 14, 1, NULL, '2021-10-06 09:46:51'),
(35, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(36, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(37, 'Tomato Bacon Pasta', '', '', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(38, 'Pasta Carbonara', '', '', '', '', 13, 1, NULL, '2021-10-06 09:46:51'),
(39, 'Margherita pizza', '', '', '', '', 14, 1, NULL, '2021-10-06 09:46:51'),
(40, 'Mushroom Risotto', '', '', '', '', 15, 1, NULL, '2021-10-06 09:46:51'),
(41, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(42, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(43, 'Tomato Bacon Pasta', '', '', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(44, 'Pasta Carbonara', '', '', '', '', 13, 1, NULL, '2021-10-06 09:46:51'),
(45, 'Margherita pizza', '', '', '', '', 14, 1, NULL, '2021-10-06 09:46:51'),
(46, 'Mushroom Risotto', '', '', '', '', 15, 1, NULL, '2021-10-06 09:46:51'),
(47, 'Focaccia Bread', '<p>Focaccia is a flat leavened oven-baked Italian bread similar in style and texture to pizza. In some places, it is called pizza bianca.</p>', NULL, NULL, NULL, 16, 1, NULL, '2021-10-07 06:11:15'),
(48, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(49, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(50, 'Tomato Bacon Pasta', '', '', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(51, 'Pasta Carbonara', '', '', '', '', 13, 1, NULL, '2021-10-06 09:46:51'),
(52, 'Margherita pizza', '', '', '', '', 14, 1, NULL, '2021-10-06 09:46:51'),
(53, 'Mushroom Risotto', '', '', '', '', 15, 1, NULL, '2021-10-06 09:46:51'),
(54, 'Focaccia Bread ', '', '', '', '', 16, 1, NULL, '2021-10-06 09:46:51'),
(55, 'Lasagna ', '', '', '', '', 17, 1, NULL, '2021-10-06 09:46:51'),
(56, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(57, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(58, 'Tomato Bacon Pasta', '', '', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(59, 'Pasta Carbonara', '', '', '', '', 13, 1, NULL, '2021-10-06 09:46:51'),
(60, 'Margherita pizza', '', '', '', '', 14, 1, NULL, '2021-10-06 09:46:51'),
(61, 'Mushroom Risotto', '', '', '', '', 15, 1, NULL, '2021-10-06 09:46:51'),
(62, 'Focaccia Bread ', '', '', '', '', 16, 1, NULL, '2021-10-06 09:46:51'),
(63, 'Lasagna ', '', '', '', '', 17, 1, NULL, '2021-10-06 09:46:51'),
(64, 'Panzanella', '', '', '', '', 18, 1, NULL, '2021-10-06 09:46:51'),
(65, 'Pizza Fritta', '', '', '', '', 10, 1, NULL, '2021-10-06 09:46:51'),
(66, 'Pizza Napoletana', '', '', '', '', 11, 1, NULL, '2021-10-06 09:46:51'),
(67, 'Tomato Bacon Pasta', '', '', '', '', 12, 1, NULL, '2021-10-06 09:46:51'),
(68, 'Pasta Carbonara', '', '', '', '', 13, 1, NULL, '2021-10-06 09:46:51'),
(69, 'Margherita pizza', '', '', '', '', 14, 1, NULL, '2021-10-06 09:46:51'),
(70, 'Mushroom Risotto', '', '', '', '', 15, 1, NULL, '2021-10-06 09:46:51'),
(71, 'Focaccia Bread ', '', '', '', '', 16, 1, NULL, '2021-10-06 09:46:51'),
(72, 'Lasagna ', '', '', '', '', 17, 1, NULL, '2021-10-06 09:46:51'),
(73, 'Panzanella', '', '', '', '', 18, 1, NULL, '2021-10-06 09:46:51'),
(74, 'Burrata ', '', '', '', '', 19, 1, NULL, '2021-10-06 09:46:51'),
(75, 'Dim Sums', '<p>Dim sum is a&nbsp;traditional Chinese meal made up of small plates of dumplings and other snack dishes&nbsp;and is usually accompanied by tea.</p>', NULL, NULL, NULL, 20, 1, NULL, '2021-10-07 06:11:52'),
(76, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(77, 'Chicken with Chestnuts', '<p>Chicken with Chestnuts minced chicken stir fried with mushrooms, water chestnuts and radish and served on a bed of lettuce.</p>', NULL, NULL, NULL, 21, 1, NULL, '2021-10-07 06:13:33'),
(78, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(79, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(80, 'Spring Rolls', '<p>&nbsp;</p>\r\n\r\n<p>Spring roll is a fried dish usually available as a dim sum. They typically contain&nbsp;minced pork, shredded carrot, bean sprouts and other vegetables served with Dipping sauce.</p>', NULL, NULL, NULL, 22, 1, NULL, '2021-10-07 06:14:23'),
(81, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(82, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(83, 'Spring Rolls', '', '', '', '', 22, 1, NULL, '2021-10-06 09:46:51'),
(84, 'Stir Fried Tofu with Rice', '<p>&nbsp;</p>\r\n\r\n<p>Stir Fried Tofu with Rice is made from&nbsp;dried soybeans&nbsp;that are soaked in water, crushed, and boiled. The mixture is separated into solid pulp and soy milk.</p>', NULL, NULL, NULL, 23, 1, NULL, '2021-10-07 06:16:34'),
(85, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(86, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(87, 'Spring Rolls', '', '', '', '', 22, 1, NULL, '2021-10-06 09:46:51'),
(88, 'Stir Fried Tofu with Rice', '', '', '', '', 23, 1, NULL, '2021-10-06 09:46:51'),
(89, 'Hot Sour Soup', '', '', '', '', 24, 1, NULL, '2021-10-06 09:46:51'),
(90, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(91, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(92, 'Spring Rolls', '', '', '', '', 22, 1, NULL, '2021-10-06 09:46:51'),
(93, 'Stir Fried Tofu with Rice', '', '', '', '', 23, 1, NULL, '2021-10-06 09:46:51'),
(94, 'Hot Sour Soup', '', '', '', '', 24, 1, NULL, '2021-10-06 09:46:51'),
(95, 'Sichuan Pork', '', '', '', '', 25, 1, NULL, '2021-10-06 09:46:51'),
(96, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(97, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(98, 'Spring Rolls', '', '', '', '', 22, 1, NULL, '2021-10-06 09:46:51'),
(99, 'Stir Fried Tofu with Rice', '', '', '', '', 23, 1, NULL, '2021-10-06 09:46:51'),
(100, 'Hot Sour Soup', '', '', '', '', 24, 1, NULL, '2021-10-06 09:46:51'),
(101, 'Sichuan Pork', '', '', '', '', 25, 1, NULL, '2021-10-06 09:46:51'),
(102, 'Shrimp with Vermicelli and Garlic', '', '', '', '', 26, 1, NULL, '2021-10-06 09:46:51'),
(103, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(104, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(105, 'Spring Rolls', '', '', '', '', 22, 1, NULL, '2021-10-06 09:46:51'),
(106, 'Stir Fried Tofu with Rice', '', '', '', '', 23, 1, NULL, '2021-10-06 09:46:51'),
(107, 'Hot Sour Soup', '', '', '', '', 24, 1, NULL, '2021-10-06 09:46:51'),
(108, 'Sichuan Pork', '', '', '', '', 25, 1, NULL, '2021-10-06 09:46:51'),
(109, 'Shrimp with Vermicelli and Garlic', '', '', '', '', 26, 1, NULL, '2021-10-06 09:46:51'),
(110, 'Peking Roasted Duck', '', '', '', '', 27, 1, NULL, '2021-10-06 09:46:51'),
(111, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(112, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(113, 'Spring Rolls', '', '', '', '', 22, 1, NULL, '2021-10-06 09:46:51'),
(114, 'Stir Fried Tofu with Rice', '', '', '', '', 23, 1, NULL, '2021-10-06 09:46:51'),
(115, 'Hot Sour Soup', '', '', '', '', 24, 1, NULL, '2021-10-06 09:46:51'),
(116, 'Sichuan Pork', '', '', '', '', 25, 1, NULL, '2021-10-06 09:46:51'),
(117, 'Shrimp with Vermicelli and Garlic', '', '', '', '', 26, 1, NULL, '2021-10-06 09:46:51'),
(118, 'Peking Roasted Duck', '', '', '', '', 27, 1, NULL, '2021-10-06 09:46:51'),
(119, 'Steamed Vermicelli Rolls', '', '', '', '', 28, 1, NULL, '2021-10-06 09:46:51'),
(120, 'Dim Sums', '', '', '', '', 20, 1, NULL, '2021-10-06 09:46:51'),
(121, 'Chicken with Chestnuts', '', '', '', '', 21, 1, NULL, '2021-10-06 09:46:51'),
(122, 'Spring Rolls', '', '', '', '', 22, 1, NULL, '2021-10-06 09:46:51'),
(123, 'Stir Fried Tofu with Rice', '', '', '', '', 23, 1, NULL, '2021-10-06 09:46:51'),
(124, 'Hot Sour Soup', '', '', '', '', 24, 1, NULL, '2021-10-06 09:46:51'),
(125, 'Sichuan Pork', '', '', '', '', 25, 1, NULL, '2021-10-06 09:46:51'),
(126, 'Shrimp with Vermicelli and Garlic', '', '', '', '', 26, 1, NULL, '2021-10-06 09:46:51'),
(127, 'Peking Roasted Duck', '', '', '', '', 27, 1, NULL, '2021-10-06 09:46:51'),
(128, 'Steamed Vermicelli Rolls', '', '', '', '', 28, 1, NULL, '2021-10-06 09:46:51'),
(129, 'Fried Shrimp with Cashew Nuts', '', '', '', '', 29, 1, NULL, '2021-10-06 09:46:51'),
(130, 'Prawn Pie', '<p>Prawn Pie&nbsp;and hard boiled eggs are other common additional ingredients. Parsley or chives are sometimes added to the sauce.</p>', NULL, NULL, NULL, 30, 1, NULL, '2021-10-06 14:54:37'),
(131, 'Prawn Pie', '', '', '', '', 30, 1, NULL, '2021-10-06 09:46:51'),
(132, 'Crispy Calamari Rings', '<p>Crispy Calamari Rings&nbsp;used for calamari. Calamari rings are a type of dish made using squid, and they can be prepared in a number of different ways.</p>', NULL, NULL, NULL, 31, 1, NULL, '2021-10-06 14:55:52'),
(133, 'Prawn Pie', '', '', '', '', 30, 1, NULL, '2021-10-06 09:46:51'),
(134, 'Crispy Calamari Rings', '', '', '', '', 31, 1, NULL, '2021-10-06 09:46:51'),
(135, 'Yorkshire Lamb Patties', '<p>Yorkshire Lamb Patties&nbsp;which melt in your mouth, and are quick and easy to make.&nbsp;</p>', NULL, NULL, NULL, 32, 1, NULL, '2021-10-06 14:57:31'),
(136, 'Bananas', '<p>Banana is an elongated, edible fruit&nbsp; botanically&nbsp; produced by several kinds of large herbaceous flowering plants in the genus Musa.</p>', NULL, NULL, NULL, 33, 1, NULL, '2021-10-06 14:44:32'),
(137, 'Yellow Bananas ', '', '', '', '', 33, 1, NULL, '2021-10-06 09:46:51'),
(138, 'Raspberries Mexico', '<p>Raspberry&nbsp;in&nbsp;Mexico&nbsp;is considered as the red gold, since it is one of the most emblematic and fastest growing fruit productions in the country.</p>', NULL, NULL, NULL, 34, 1, NULL, '2021-10-06 14:45:27'),
(139, 'Yellow Bananas ', '', '', '', '', 33, 1, NULL, '2021-10-06 09:46:51'),
(140, 'Raspberries Mexico ', '', '', '', '', 34, 1, NULL, '2021-10-06 09:46:51'),
(141, 'Pears Forelle', '<p>Pears Forelle pear is green and yellow with hints of pink. They originated in Germany and are now heavily grown in Oregon and Washington.</p>', NULL, NULL, NULL, 35, 1, NULL, '2021-10-06 14:46:14'),
(142, 'Yellow Bananas ', '', '', '', '', 33, 1, NULL, '2021-10-06 09:46:51'),
(143, 'Raspberries Mexico ', '', '', '', '', 34, 1, NULL, '2021-10-06 09:46:51'),
(144, 'Pears Forelle ', '', '', '', '', 35, 1, NULL, '2021-10-06 09:46:51'),
(145, 'Tomato', '<p>Tomato is the edible berry of the plant Solanum lycopersicum, commonly known as a tomato plant. The species originated in western South America and Central America.&nbsp;</p>', NULL, NULL, NULL, 36, 1, NULL, '2021-10-06 14:46:56'),
(146, 'Yellow Bananas ', '', '', '', '', 33, 1, NULL, '2021-10-06 09:46:51'),
(147, 'Raspberries Mexico ', '', '', '', '', 34, 1, NULL, '2021-10-06 09:46:51'),
(148, 'Pears Forelle ', '', '', '', '', 35, 1, NULL, '2021-10-06 09:46:51'),
(149, 'Tomato Bunch ', '', '', '', '', 36, 1, NULL, '2021-10-06 09:46:51'),
(150, 'Sweet Potato Australia', '<p>&nbsp;</p>\r\n\r\n<p>Sweet potatoes, known as kumera, are originally a&nbsp;native of South America&nbsp;and are an important starch staple in the Pacific Islands.&nbsp;</p>', NULL, NULL, NULL, 37, 1, NULL, '2021-10-06 14:47:40'),
(151, 'Yellow Bananas ', '', '', '', '', 33, 1, NULL, '2021-10-06 09:46:51'),
(152, 'Raspberries Mexico ', '', '', '', '', 34, 1, NULL, '2021-10-06 09:46:51'),
(153, 'Pears Forelle ', '', '', '', '', 35, 1, NULL, '2021-10-06 09:46:51'),
(154, 'Tomato Bunch ', '', '', '', '', 36, 1, NULL, '2021-10-06 09:46:51'),
(155, 'Sweet Potato Australia', '', '', '', '', 37, 1, NULL, '2021-10-06 09:46:51'),
(156, 'Cauliflower', '<p>Cauliflower is one of several vegetables in the species Brassica oleracea in the genus Brassica, which is in the Brassicaceae family.</p>', NULL, NULL, NULL, 38, 1, NULL, '2021-10-06 14:48:07'),
(157, 'Choco lava cake', '<p>Molten chocolate cake is a popular dessert that combines the elements of a chocolate cake and a souffl&eacute;.</p>', NULL, NULL, NULL, 39, 1, NULL, '2021-10-06 14:48:47'),
(158, 'Choco lava cake', '', '', '', '', 39, 1, NULL, '2021-10-06 09:46:51'),
(159, 'Black Forest', '<p>Black Forest g&acirc;teau or Black Forest cake is a chocolate sponge cake with a rich cherry filling based on the German dessert, literally Black Forest Cherry-torte.&nbsp;</p>', NULL, NULL, NULL, 40, 1, NULL, '2021-10-06 14:50:05'),
(160, 'Brown Eggs', '<p>Brown eggs are&nbsp;bigger in size&nbsp;and the yolk of the egg is darker than the white variant. Also, the brown egg comes from the chicken which has a big appetite.</p>', NULL, NULL, NULL, 41, 1, NULL, '2021-10-06 14:50:45'),
(161, 'Vitamin C + Zinc', '<p>Vitamin C and zinc play important roles in&nbsp;providing adequate nutrition and immune defense. This supplement may be given to prevent or treat certain deficiencies caused by poor nutrition, different diseases, medications, or pregnancy.</p>', NULL, NULL, NULL, 42, 1, NULL, '2021-10-06 14:38:22'),
(162, 'Vitamin C + Zinc', '', '', '', '', 42, 1, NULL, '2021-10-06 09:46:51'),
(163, 'Folic B9', '<p>Folic B9 also called folate or folic acid, is one of 8 B vitamins. All B vitamins help the body convert food&nbsp; into fuel, which is used to produce energy.</p>', NULL, NULL, NULL, 43, 1, NULL, '2021-10-06 14:39:28'),
(164, 'Vitamin C + Zinc', '', '', '', '', 42, 1, NULL, '2021-10-06 09:46:51'),
(165, 'Folic B9', '', '', '', '', 43, 1, NULL, '2021-10-06 09:46:51'),
(166, 'Casein Protein', '<p>Casein&nbsp;is a slow-digesting&nbsp;protein&nbsp;that can boost muscle growth and aid recovery after exercise.&nbsp;</p>', NULL, NULL, NULL, 44, 1, NULL, '2021-10-06 14:40:28'),
(167, 'Vitamin C + Zinc', '', '', '', '', 42, 1, NULL, '2021-10-06 09:46:51'),
(168, 'Folic B9', '', '', '', '', 43, 1, NULL, '2021-10-06 09:46:51'),
(169, 'Casein Protein', '', '', '', '', 44, 1, NULL, '2021-10-06 09:46:51'),
(170, 'Thermometer', '<p>Thermometer&nbsp;is the quickest, most accurate way to test for fever.</p>', NULL, NULL, NULL, 45, 1, NULL, '2021-10-06 14:41:40'),
(171, 'Vitamin C + Zinc', '', '', '', '', 42, 1, NULL, '2021-10-06 09:46:51'),
(172, 'Folic B9', '', '', '', '', 43, 1, NULL, '2021-10-06 09:46:51'),
(173, 'Casein Protein', '', '', '', '', 44, 1, NULL, '2021-10-06 09:46:51'),
(174, 'Thermometer', '', '', '', '', 45, 1, NULL, '2021-10-06 09:46:51'),
(175, 'Pulse Oximeter', '<p>Pulse oximetry is a noninvasive method for monitoring a person&#39;s oxygen saturation. Peripheral oxygen saturation readings are typically within 2% accuracy of the more desirable reading of arterial oxygen saturation from arterial blood gas analysis.</p>', NULL, NULL, NULL, 46, 1, NULL, '2021-10-06 14:42:23'),
(176, 'Vitamin C + Zinc', '', '', '', '', 42, 1, NULL, '2021-10-06 09:46:51'),
(177, 'Folic B9', '', '', '', '', 43, 1, NULL, '2021-10-06 09:46:51'),
(178, 'Casein Protein', '', '', '', '', 44, 1, NULL, '2021-10-06 09:46:51'),
(179, 'Thermometer', '', '', '', '', 45, 1, NULL, '2021-10-06 09:46:51'),
(180, 'Pulse Oximeter', '', '', '', '', 46, 1, NULL, '2021-10-06 09:46:51'),
(181, 'Breathe Free Vaporizer', '<p>Breathe Free Vaporizer is&nbsp;a mechanical device that turns water into steam and transmits that steam into the surrounding atmosphere.</p>', NULL, NULL, NULL, 47, 1, NULL, '2021-10-06 14:43:20'),
(182, 'Asus Zenbook 14', '<p>Asus ZenBook 14&nbsp; is&nbsp;a Windows 10 Home laptop&nbsp;with a 14.00-inch display that has a resolution of 1920x1080 pixels.</p>', NULL, NULL, NULL, 48, 1, NULL, '2021-10-06 14:31:16'),
(183, 'Asus Zenbook 14', '', '', '', '', 48, 1, NULL, '2021-10-06 09:46:51'),
(184, 'HP Pavilion 15', '<p>HP Pavilion 14-dv0053TU is a&nbsp;Windows 10 Home laptop&nbsp;with a 14.00-inch display that has a resolution of 1920x1080 pixels.</p>', NULL, NULL, NULL, 49, 1, NULL, '2021-10-06 14:32:04'),
(185, 'Asus Zenbook 14', '', '', '', '', 48, 1, NULL, '2021-10-06 09:46:51'),
(186, 'HP Pavilion 14', '', '', '', '', 49, 1, NULL, '2021-10-06 09:46:51'),
(187, 'Macbook Pro M1 Chip', '<p>Apple M1 chip with&nbsp;8‑core CPU, 8-core GPU and 16-core Neural Engine.&nbsp;8GB unified memory.&nbsp;256GB SSD&nbsp;storage 33.74 cm Retina display with True Tone.</p>', NULL, NULL, NULL, 50, 1, NULL, '2021-10-06 14:33:10'),
(188, 'Iphone 12 Pro Max', '<p>Iphone 12 Pro Max&nbsp;comes with a&nbsp;6.70-inch touchscreen display&nbsp;with a resolution of 1284x2778 pixels at a pixel density of 458 pixels per inch. iPhone 12 Pro Max is powered by a hexa-core Apple A14 Bionic processor.&nbsp;</p>', NULL, NULL, NULL, 51, 1, NULL, '2021-10-06 14:34:21'),
(189, 'Oneplus 9Pro', '<p>OnePlus exclusive, Hyper Touch reduces&nbsp;in-game imaging delays&nbsp;during even the most demanding mobile gaming for up to 6 times faster then other. Experience ultra-fast aiming and effortlessly precise control.</p>', NULL, NULL, NULL, 52, 1, NULL, '2021-10-06 14:35:52'),
(190, 'Samsung Note 20 Ultra', '<p>Samsung Galaxy Note 20 Ultra 5G is extremely expensive but offers the best of Samsung&#39;s technology including a&nbsp;108-megapixel camera, 5x optical zoom, and an enhanced S-Pen styles.</p>', NULL, NULL, NULL, 53, 1, NULL, '2021-10-06 14:37:12'),
(191, 'Jackets', '<p>Jacket is a garment for the upper body, usually extending below the hips. A jacket typically has sleeves, and fastens in the front or slightly on the side. A jacket is generally lighter, tighter-fitting, and less insulating than a coat, which is outerwear.</p>', NULL, NULL, NULL, 54, 1, NULL, '2021-10-06 14:13:41'),
(192, 'Coats', '<p>Coat is a garment worn on the upper body by either gender for warmth or fashion. Coats typically have long sleeves and are open down the front, closing by means of buttons, zippers, hook-and-loop fasteners, toggles, a belt, or a combination of some of these.</p>', NULL, NULL, NULL, 55, 1, NULL, '2021-10-06 14:14:46'),
(193, 'Denim', '<p>Jeans are a type of pants or trousers, typically made from denim or dungaree cloth.&nbsp;</p>', NULL, NULL, NULL, 56, 1, NULL, '2021-10-06 14:18:30'),
(194, 'Fluffy Jackets', '<p>Fluffy Jackets, also called quilted jackets, have a signature quilted design with sections that are puffy between the stitching.</p>', NULL, NULL, NULL, 57, 1, NULL, '2021-10-11 09:30:55'),
(195, 'Partywear', '<p>Party dress is&nbsp;a dress worn especially for different types of party such as children&#39;s party, cocktail party, garden party and costume party would tend to require different styles of dress.&nbsp;</p>', NULL, NULL, NULL, 58, 1, NULL, '2021-10-06 14:28:23'),
(196, 'Printed Dresses', '<p>&nbsp;Printed dress&nbsp;is which can wow you in the summer or make you glamorous in the theme.</p>', NULL, NULL, NULL, 59, 1, NULL, '2021-10-06 14:30:09'),
(197, 'Split Ac Service', '<p>During AC service, the&nbsp;technician cleans the dust and debris from the condenser coil and evaporator coil&nbsp;and other key components of the system. In a split AC, the condenser coils are in the outdoor unit.</p>', NULL, NULL, NULL, 60, 1, NULL, '2021-10-06 09:46:51'),
(198, 'Split Ac Service', '', '', '', '', 60, 1, NULL, '2021-10-06 09:46:51'),
(199, 'Window Ac Service', '<p>Cleaning of AC Condenser Coil, Cooling Coil. Cleaning of AC Filter. Cleaning of AC Drainage Piping (Wet Method). Overall Inspection of the AC unit.</p>', NULL, NULL, NULL, 61, 1, NULL, '2021-10-06 09:46:51'),
(200, 'Split Ac Service', '', '', '', '', 60, 1, NULL, '2021-10-06 09:46:51'),
(201, 'Window Ac Service', '', '', '', '', 61, 1, NULL, '2021-10-06 09:46:51'),
(202, 'Split Ac Installation', '', '', '', '', 62, 1, NULL, '2021-10-06 09:46:51'),
(203, 'Split Ac Service', '', '', '', '', 60, 1, NULL, '2021-10-06 09:46:51'),
(204, 'Window Ac Service', '', '', '', '', 61, 1, NULL, '2021-10-06 09:46:51'),
(205, 'Split Ac Installation', '', '', '', '', 62, 1, NULL, '2021-10-06 09:46:51'),
(206, 'Window Ac Installation', '', '', '', '', 63, 1, NULL, '2021-10-06 09:46:51'),
(207, 'Full Home Cleaning', '<p>Dusting, sweeping, mopping, and washing floors, toilets, showers, tubs, driveways, windows, and counters. Vacuuming carpets, upholstery, and any other dusty surface. Cleaning all surfaces in the kitchen and bathroom. Making beds and fluffing pillows. Folding clean laundry.</p>', NULL, NULL, NULL, 64, 1, NULL, '2021-10-06 09:46:51'),
(208, 'Full Home Cleaning', '', '', '', '', 64, 1, NULL, '2021-10-06 09:46:51'),
(209, 'Bathroom Cleaning', '<p>A preparation for cleaning bathrooms. cleaner, cleanser, cleansing agent - a preparation used in cleaning something. Based on WordNet 3.0, Farlex clipart collection.</p>', NULL, NULL, NULL, 65, 1, NULL, '2021-10-06 09:46:51'),
(210, 'Full Home Cleaning', '', '', '', '', 64, 1, NULL, '2021-10-06 09:46:51'),
(211, 'Bathroom Cleaning', '', '', '', '', 65, 1, NULL, '2021-10-06 09:46:51'),
(212, 'Car Cleaning', '<p>The&nbsp;Car&nbsp;Detailer will&nbsp;clean&nbsp;vehicles according to company standards or client specifications, which may include performing&nbsp;detail&nbsp;inspections.</p>', NULL, NULL, NULL, 66, 1, NULL, '2021-10-06 09:46:51'),
(213, 'Full Home Cleaning', '', '', '', '', 64, 1, NULL, '2021-10-06 09:46:51'),
(214, 'Bathroom Cleaning', '', '', '', '', 65, 1, NULL, '2021-10-06 09:46:51'),
(215, 'Car Cleaning', '', '', '', '', 66, 1, NULL, '2021-10-06 09:46:51'),
(216, 'Sofa & Carpet Cleaning', '', '', '', '', 67, 1, NULL, '2021-10-06 09:46:51'),
(217, 'Bed bugs control', '<p>Thorough vacuuming&nbsp;can get rid of some of your bed bugs. Carefully vacuum rugs, floors, upholstered furniture, bed frames, under beds, around bed legs, and all cracks and crevices around the room.</p>', NULL, NULL, NULL, 68, 1, NULL, '2021-10-06 09:46:51'),
(218, 'Bed bugs control', '', '', '', '', 68, 1, NULL, '2021-10-06 09:46:51'),
(219, 'Cockroaches pest control', '<p>There are many methods of pest control that you could do yourself. &bull; You can&nbsp;kill cockroaches with the help of diatomaceous earth. It eats through the exoskeleton of the pest killing it within 24 hours.</p>', NULL, NULL, NULL, 69, 1, NULL, '2021-10-06 09:46:51'),
(220, 'Bed bugs control', '', '', '', '', 68, 1, NULL, '2021-10-06 09:46:51'),
(221, 'Cockroaches pest control', '', '', '', '', 69, 1, NULL, '2021-10-06 09:46:51'),
(222, 'Termite control', '<p>The treatment involves&nbsp;detection of termites using Termatrac, drilling holes at skirting level on the walls and injecting liquid termiticide in them by trained technicians.The termiticide, once injected into the ground, forms a barrier between the soil and the building structure.</p>', NULL, NULL, NULL, 70, 1, NULL, '2021-10-06 09:46:51'),
(223, 'Shirt', '<p>A shirt is&nbsp;a cloth garment for the upper body (from the neck to the waist). Originally an undergarment worn exclusively by men, it has become, in American English, a catch-all term for a broad variety of upper-body garments and undergarments.</p>', NULL, NULL, NULL, 71, 1, NULL, '2021-10-11 05:44:25'),
(224, 'Casual T-shirts', '<p>Men Casual T-shirts&nbsp;Jeans, dress shirt&nbsp;(casually turn down collared), and a T-shirt or sleeveless shirt are typically considered casual wear for men in modern times.</p>', NULL, NULL, NULL, 72, 1, NULL, '2021-10-11 05:47:32'),
(225, 'Capri', '<p>Capri pants, for men or women, are defined&nbsp;fairly easily as pants that come down to approximately mid-calf. Lower usually just looks like the pants are too short, and higher may give the impression of shorts.</p>', NULL, NULL, NULL, 73, 1, NULL, '2021-10-11 05:54:17'),
(226, '', '', '', '', '', 74, 1, NULL, NULL),
(227, 'Shorts', '<p>Shorts are&nbsp;a garment worn over the pelvic area, circling the waist and splitting to cover the upper part of the legs, sometimes extending down to the knees but not covering the entire length of the leg.</p>', NULL, NULL, NULL, 75, 1, NULL, '2021-10-11 06:15:07'),
(228, 'Scirt', NULL, NULL, NULL, NULL, 76, 1, NULL, '2021-10-11 06:18:24'),
(229, 'Sweaters', '<p>A sweater or pullover, also called a&nbsp;jumper in British&nbsp;and Australian English, is a piece of clothing, typically with long sleeves, made of knitted or crocheted material, that covers the upper part of the body. When sleeveless, the garment is often called a slipover or sweater vest.</p>', NULL, NULL, NULL, 77, 1, NULL, '2021-10-11 06:22:36'),
(230, 'Dell Inspiron 14', '<p>Inspiron 14 Laptop&nbsp; is&nbsp;a Windows 10 Home laptop&nbsp;with a 14.00-inch display that has a resolution of 1920x1080 pixels.</p>', NULL, NULL, NULL, 78, 1, NULL, '2021-10-11 10:59:45'),
(231, 'Lenovo Idea pad Slim', '<p>Lenovo IdeaPad Slim 3 10th Gen Intel Core i3 14&quot; (35.56cm) FHD Thin &amp; Light Laptop.</p>', NULL, NULL, NULL, 79, 1, NULL, '2021-10-11 11:03:13'),
(232, 'Acer Nitro 5 Gaming Laptop', '<p>Acer Nitro 5 Gaming Laptop AMD Ryzen 5-5600H - (16GB/1 TB HDD/256 GB SSD/Nvidia RTX 3060/ Windows 10 Home/144hz) AN515 With 39.6 Cm (15.6 Inches) FHD Display / 2.4 Kgs / XBOX Game Pass</p>', NULL, NULL, NULL, 80, 1, NULL, '2021-10-11 06:41:34'),
(233, 'OnePlus 9R 5G', '<p>OnePlus 9R 5G (Lake Blue, 8GB RAM, 128GB Storage).</p>', NULL, NULL, NULL, 81, 1, NULL, '2021-10-11 06:45:28'),
(234, 'Samsung Galaxy S20 FE 5G', '<p>Samsung Galaxy S20 FE 5G (Cloud Green, 8GB RAM, 128GB Storage)</p>', NULL, NULL, NULL, 82, 1, NULL, '2021-10-11 06:57:57'),
(235, 'OPPO F19 Pro', '<p>OPPO F19 Pro (Fluid Black, 8GB RAM, 128GB Storage)&nbsp;</p>', NULL, NULL, NULL, 83, 1, NULL, '2021-10-11 07:03:04'),
(236, 'Vitamin B-12', '<p>Vitamin B-12 that&nbsp;the body needs in small amounts to function and stay healthy. Vitamin B12 helps make red blood cells, DNA, RNA, energy, and tissues, and keeps nerve cells healthy. It is found in liver, meat, eggs, poultry, shellfish, milk, and milk products.</p>', NULL, NULL, NULL, 84, 1, NULL, '2021-10-11 07:08:24'),
(237, 'Hemp Protein', '<p>Hemp protein is&nbsp;the protein content of hemp seeds. The protein in hemp seeds is made up of the two globular types of proteins, edestin (60&ndash;80%) and 2S albumin, with edestin also being rich in the essential amino acids.</p>', NULL, NULL, NULL, 85, 1, NULL, '2021-10-11 07:12:09'),
(238, 'Brown Rice', '<p>Brown Rice protein supplementation&nbsp;great for muscle building and recovery, it is also beneficial to people looking to lose weight, as brown rice has a thermic effect.</p>', NULL, NULL, NULL, 86, 1, NULL, '2021-10-11 07:16:51'),
(239, 'Blood Glucose Monitors', '<p>A blood glucose meter is&nbsp;a small, portable machine that&#39;s used to measure how much glucose (a type of sugar) is in the blood&nbsp;(also known as the blood glucose level). People with diabetes often use a blood glucose meter to help them manage their condition.</p>', NULL, NULL, NULL, 87, 1, NULL, '2021-10-11 07:21:32'),
(240, 'Pedometers And Weighing Scales', '<p>Pedometer is extensively used for&nbsp;tracking total number of steps&nbsp;and in many other suitable applications.</p>', NULL, NULL, NULL, 88, 1, NULL, '2021-10-11 07:26:27'),
(241, 'Apple', '<p>The apple is a&nbsp;pome fruit, in which the ripened ovary and surrounding tissue both become fleshy and edible.When harvested, apples are usually roundish, 5&ndash;10 cm in diameter, and some shade of red, green, or yellow in colour; they vary in size, shape, and acidity depending on the variety</p>', NULL, NULL, NULL, 89, 1, NULL, '2021-10-11 07:32:36'),
(242, 'Watermelon', '<p>The watermelon is a large fruit of a&nbsp;more or less spherical shape.It has an oval or spherical shape and a dark green and smooth rind, sometimes showing irregular areas of a pale green colour. It has a sweet, juicy, refreshing flesh of yellowish or reddish colour, containing multiple black, brown or white pips.</p>', NULL, NULL, NULL, 90, 1, NULL, '2021-10-11 07:36:35'),
(243, 'cabbage', '<p>Cabbage, comprising several cultivars of&nbsp;Brassica oleracea, is a leafy green, red (purple), or white (pale green) biennial plant grown as an annual vegetable crop for its dense-leaved heads. oleracea), and belongs to the &quot;cole crops&quot; or brassicas, meaning it is closely related to broccoli and cauliflower.</p>', NULL, NULL, NULL, 91, 1, NULL, '2021-10-11 07:41:27'),
(244, 'broccoli', '<p>Broccoli is a&nbsp;fast-growing annual plant&nbsp;that grows 60&ndash;90 cm tall. Upright and branching with leathery leaves, broccoli bears dense green clusters of flower buds at the ends of the central axis and the branches.</p>', NULL, NULL, NULL, 92, 1, NULL, '2021-10-11 07:44:39'),
(245, 'cheese', '<p>Cheese is made from Cheese, Sodium Citrate, Common Salt, Citric Acid, permitted natural colour - Annatto. Emulsifier and Class II preservatives. It is made from graded cow/buffalo milk using microbial rennet.</p>', NULL, NULL, NULL, 93, 1, NULL, '2021-10-11 07:49:31'),
(246, 'Milk shake', '<p>Milk shake&nbsp;is a sweet drink made by blending milk, ice cream, and flavorings or sweeteners such as butterscotch, caramel sauce, chocolate syrup.</p>', NULL, NULL, NULL, 94, 1, NULL, '2021-10-11 07:54:04'),
(247, 'Roesti And Salad', '<p>Roesti and Salad Recipe. Crispy creamy potato cakes topped with the freshness of salad. The perfect recipe for a lazy Sunday brunch! Total Cook Time 50 mins</p>', NULL, NULL, NULL, 95, 1, NULL, '2021-10-11 08:44:42'),
(248, 'Paneer Steak', '<p>Paneer Steak&nbsp;is an Indian dish made from chunks of paneer marinated in spices and grilled in a tandoor.</p>', NULL, NULL, NULL, 96, 1, NULL, '2021-10-11 08:50:55'),
(249, 'Apple Sausage Plait.', '<p>Apple Sausage Plait&nbsp;in the form of a cylindrical length of minced pork or other meat encased in a skin, typically sold raw to be grilled or fried before eating.</p>', NULL, NULL, NULL, 97, 1, NULL, '2021-10-11 08:56:22'),
(250, 'Polenta', '<p>Polenta&nbsp;a porridge or mush usually made of ground corn (maize) cooked in salted water. Cheese and butter or oil are often added. Polenta can be eaten hot or cold as a porridge, or it can be cooled until firm, cut into shapes, and then baked, toasted, panfried, or deep-fried.</p>', NULL, NULL, NULL, 98, 1, NULL, '2021-10-11 09:01:57'),
(251, 'Osso buco', '<p>Ossobuco or osso buco&nbsp; is&nbsp;a specialty of Lombard cuisine of cross-cut veal<strong> </strong>shanks braised with vegetables, white wine and broth.</p>', NULL, NULL, NULL, 99, 1, NULL, '2021-10-11 09:06:37'),
(252, 'Quick Noodles', '<p>Quick Noodles&nbsp;a cooked egg-and-flour paste prominent in European and Asian cuisine, generally distinguished from pasta by its elongated ribbonlike form. Noodles are commonly used to add body and flavour to broth soups</p>', NULL, NULL, NULL, 100, 1, NULL, '2021-10-11 09:10:06'),
(253, 'Szechwan Chilli Chicken', '<p>About&nbsp;Szechwan Chilli Chicken Recipe: A fiery side&nbsp; fried chicken cooked with brown, green &amp; white peppercorns and oriental spices.</p>', NULL, NULL, NULL, 101, 1, NULL, '2021-10-11 10:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_up_sells`
--

CREATE TABLE `product_up_sells` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `upsell_product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT NULL,
  `position` tinyint NOT NULL DEFAULT '1',
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `barcode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `inventory_policy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fulfillment_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory_management` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 for avtive, 0 for inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `sku`, `product_id`, `title`, `quantity`, `price`, `position`, `compare_at_price`, `barcode`, `cost_price`, `currency_id`, `tax_category_id`, `inventory_policy`, `fulfillment_service`, `inventory_management`, `created_at`, `updated_at`, `status`) VALUES
(1, 'sku-id1633375333925589c', 1, NULL, 100, '500.00', 1, '500.00', '7543ebf012007e', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:13', 1),
(2, 'sku-id163337533398a4b3b', 1, 'sku-id-Black-Black', 100, '500.00', 1, '500.00', '1500cdf2d597df', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:13', 1),
(3, 'sku-id16333753335644674', 1, 'sku-id-Black-Grey', 100, '500.00', 1, '500.00', '2ea56327679387', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:13', 1),
(4, 'sku-id1633375333c50fbde', 1, 'sku-id-Medium-Black', 100, '500.00', 1, '500.00', '8f47f11a19433f', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:13', 1),
(5, 'sku-id16333753339f83b05', 1, 'sku-id-Medium-Grey', 100, '500.00', 1, '500.00', '8f7318b112bbe9', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:13', 1),
(6, 'TS001', 2, NULL, 0, NULL, 1, NULL, 'a2a9bccf12bafa', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:32', '2021-10-07 06:26:47', 1),
(7, 'TS002', 3, NULL, 0, NULL, 1, NULL, '19fd99e3967244', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:32', '2021-10-05 05:54:43', 1),
(8, 'TS003', 4, NULL, 0, NULL, 1, NULL, '7ea865f59596dd', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:32', '2021-10-05 05:49:10', 1),
(9, 'TS004', 5, NULL, 0, NULL, 1, NULL, 'e5ac06b2f9a7e4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:32', '2021-10-05 05:48:56', 1),
(10, 'TS005', 6, NULL, 0, NULL, 1, NULL, 'dd23f8e8176eca', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:22:32', '2021-10-04 19:28:16', 1),
(11, 'DEL001', 7, NULL, 0, NULL, 1, NULL, '4ebe28f1edd8bf', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:29:59', '2021-10-04 19:31:03', 1),
(12, 'DEL002', 8, NULL, 0, NULL, 1, NULL, '939edaff47fa9d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:31:14', '2021-10-04 19:31:58', 1),
(13, 'DEL003', 9, NULL, 0, NULL, 1, NULL, '4be5dd2821fe16', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:32:09', '2021-10-04 19:32:48', 1),
(14, 'IT100', 10, NULL, 220, '9.00', 1, '12.00', 'd2b98d462b9bb6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:10', 1),
(15, 'IT10116333763982553f36', 11, NULL, 240, '10.00', 1, '14.00', 'd0693ee5682c9f', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:58', 1),
(16, 'IT102', 12, NULL, 310, '8.00', 1, '10.00', '8b42a582def893', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:10', 1),
(17, 'IT103', 13, NULL, 340, '12.00', 1, '15.00', 'bd32f120e3dc5b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:10', 1),
(18, 'IT10416333763651c9713b', 14, NULL, 270, '8.00', 1, '10.00', '8f2c14da51b407', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:25', 1),
(19, 'IT10516333763818f4411a', 15, NULL, 240, '9.00', 1, '12.00', 'f9baaec43fa47c', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:41', 1),
(20, 'IT106', 16, NULL, 210, '11.00', 1, '13.00', '894cc8af32de64', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:10', 1),
(21, 'IT10716333764065e15904', 17, NULL, 310, '14.00', 1, '16.00', '727770fa957754', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:40:06', 1),
(22, 'IT10816333764152b544af', 18, NULL, 330, '13.00', 1, '15.00', 'c75361d366189d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:40:15', 1),
(23, 'IT10916333763718930e5d', 19, NULL, 350, '15.00', 1, '17.00', '17147e33cb7190', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:39:10', '2021-10-04 19:39:31', 1),
(24, 'CH200', 20, NULL, 220, '8.00', 1, '10.00', '83231956e105d3', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:11', 1),
(25, 'CH202', 21, NULL, 240, '10.00', 1, '12.00', '34fafe9bb02d83', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:11', 1),
(26, 'CH203', 22, NULL, 310, '7.00', 1, '10.00', 'f2e7a0dde9eddf', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:11', 1),
(27, 'CH204', 23, NULL, 340, '12.00', 1, '14.00', 'b601d372ffea76', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:11', 1),
(28, 'CH2051633376730f942258', 24, NULL, 240, '7.00', 1, '10.00', '98619f23def7a7', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:30', 1),
(29, 'CH2061633376737ee72d4f', 25, NULL, 270, '13.00', 1, '15.00', '175690f77bdda4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:37', 1),
(30, 'CH2071633376743f9a2349', 26, NULL, 310, '14.00', 1, '16.00', '6c202dbb294539', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:43', 1),
(31, 'CH2081633376749e5989f5', 27, NULL, 230, '9.00', 1, '12.00', '34c0c90c084dc6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:49', 1),
(32, 'CH2091633376755a405fe3', 28, NULL, 280, '12.00', 1, '14.00', 'b2533bef2b5621', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:45:55', 1),
(33, 'CH2101633376762cebe295', 29, NULL, 360, '10.00', 1, '12.00', '6bd43a31eeb9e4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:45:11', '2021-10-04 19:46:02', 1),
(34, 'CN500', 30, NULL, 220, '9.00', 1, '12.00', 'c18b14ae294cb0', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:52:15', '2021-10-04 19:52:15', 1),
(35, 'CN502', 31, NULL, 310, '10.00', 1, '12.00', '285745652e76f4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:52:15', '2021-10-04 19:52:15', 1),
(36, 'CN503', 32, NULL, 340, '14.00', 1, '16.00', '3902e977f96448', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:52:15', '2021-10-04 19:52:15', 1),
(37, 'FR600', 33, NULL, 220, '6.00', 1, '10.00', '71f76a727739d7', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:30', '2021-10-04 19:58:30', 1),
(38, 'FR601', 34, NULL, 240, '8.00', 1, '12.00', '384980f2ced2c1', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:30', '2021-10-04 19:58:30', 1),
(39, 'FR602', 35, NULL, 310, '7.00', 1, '10.00', 'b88f8a6bbfefb8', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:30', '2021-10-04 19:58:30', 1),
(40, 'FR603', 36, NULL, 340, '10.00', 1, '12.00', 'd9eb46fc061623', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:30', '2021-10-04 19:58:30', 1),
(41, 'FR604', 37, NULL, 280, '12.00', 1, '14.00', 'e76f981458a8b5', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:30', '2021-10-04 19:58:30', 1),
(42, 'FR605', 38, NULL, 360, '9.00', 1, '12.00', '05ffb65d96e2f2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:30', '2021-10-04 19:58:30', 1),
(43, 'DE110', 39, NULL, 245, '14.00', 1, '16.00', 'c466bfb9b86738', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:53', '2021-10-04 19:58:53', 1),
(44, 'DE111', 40, NULL, 265, '16.00', 1, '18.00', '162643a11065de', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 19:58:53', '2021-10-04 19:58:53', 1),
(45, 'D202', 41, NULL, 310, '11.00', 1, '14.00', '266488f94537c6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:03:55', '2021-10-04 20:04:37', 1),
(46, 'PH200', 42, NULL, 230, '10.00', 1, '12.00', 'cc3a3ba79a1529', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:06:51', '2021-10-04 20:08:11', 1),
(47, 'PH201', 43, NULL, 190, '11.00', 1, '13.00', '0f7a69cbaf52cf', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:06:51', '2021-10-04 20:08:47', 1),
(48, 'PH202', 44, NULL, 410, '16.00', 1, '18.00', '0069e71ae0c8ac', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:06:51', '2021-10-04 20:09:25', 1),
(49, 'PH206', 45, NULL, 350, '15.00', 1, '17.00', '79a10fb3b147fb', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:06:51', '2021-10-04 20:12:20', 1),
(50, 'PH207', 46, NULL, 240, '14.00', 1, '16.00', '661fc75d00de82', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:06:51', '2021-10-04 20:15:50', 1),
(51, 'PH208', 47, NULL, 260, '18.00', 1, '20.00', '2dc5525e2241a2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:06:51', '2021-10-04 20:16:02', 1),
(52, 'EL806', 48, NULL, 340, '40.00', 1, '45.00', '17e07677edc94a', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:18:06', '2021-10-04 20:23:19', 1),
(53, 'EL807', 49, NULL, 290, '45.00', 1, '48.00', 'b71ab30defc3c5', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:18:06', '2021-10-04 20:23:33', 1),
(54, 'EL808', 50, NULL, 260, '50.00', 1, '55.00', 'b502cfe753bd3e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:18:06', '2021-10-04 20:23:47', 1),
(55, 'MB900', 51, NULL, 240, '52.00', 1, '55.00', '07d30863f387a6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:24:03', '2021-10-04 20:29:58', 1),
(56, 'MB901', 52, NULL, 270, '45.00', 1, '48.00', 'b69822c61a56d1', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:25:57', '2021-10-04 20:30:12', 1),
(57, 'MB902', 53, NULL, 290, '46.00', 1, '48.00', 'a0e66bf4cce3e7', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:27:53', '2021-10-04 20:30:26', 1),
(58, 'MEN301', 54, NULL, 280, '25.00', 1, '30.00', '819b47cfafb64b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:32:42', '2021-10-04 20:35:00', 1),
(59, 'MEN302', 55, NULL, 310, '26.00', 1, '28.00', '4ba1a680f3ba2b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:33:28', '2021-10-04 20:35:11', 1),
(60, 'MEN303', 56, NULL, 330, '24.00', 1, '28.00', '856fe68fc740c0', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:34:10', '2021-10-04 20:35:23', 1),
(61, 'WOMEN304', 57, NULL, 350, '24.00', 1, '26.00', '0f3bd79fd38f36', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:35:45', '2021-10-04 20:38:20', 1),
(62, 'WOMEN305', 58, NULL, 360, '18.00', 1, '20.00', '387f6aa42d06ae', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:36:48', '2021-10-04 20:38:32', 1),
(63, 'WOMEN306', 59, NULL, 310, '15.00', 1, '18.00', '2f2874dbfa0677', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:37:43', '2021-10-04 20:38:43', 1),
(64, 'AC100', 60, NULL, 0, '10.00', 1, '12.00', '1cb19b60453bc3', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:18', '2021-10-05 05:47:31', 1),
(65, 'AC101', 61, NULL, 0, '8.00', 1, '10.00', '656f80de886d60', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:18', '2021-10-05 05:17:38', 1),
(66, 'AC1021633380563ff753d8', 62, NULL, 0, '0.00', 1, '0.00', '98841a7c9fb742', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:18', '2021-10-04 20:49:23', 1),
(67, 'AC1031633380570c8d914a', 63, NULL, 0, '0.00', 1, '0.00', 'db5a13bee5c98d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:18', '2021-10-04 20:49:30', 1),
(68, 'CL111', 64, NULL, 0, '16.00', 1, '18.00', '1761f84082e3e4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:37', '2021-10-05 05:18:46', 1),
(69, 'CL112', 65, NULL, 0, '14.00', 1, '16.00', 'f233a58aae14c5', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:37', '2021-10-05 05:20:38', 1),
(70, 'CL113', 66, NULL, 0, '13.00', 1, '14.00', 'b7af164ab4c109', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:37', '2021-10-05 05:21:24', 1),
(71, 'CL1141633380586e0b4580', 67, NULL, 0, '0.00', 1, '0.00', '98cad2e3cf2091', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:37', '2021-10-04 20:49:46', 1),
(72, 'PC280', 68, NULL, 0, '9.00', 1, '12.00', '425cd02d706ca0', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:54', '2021-10-05 05:22:08', 1),
(73, 'PC281', 69, NULL, 0, '11.00', 1, '13.00', '50256d5c5d8fb3', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:54', '2021-10-05 05:23:22', 1),
(74, 'PC282', 70, NULL, 0, '14.00', 1, '16.00', 'bfc74887be5c87', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-04 20:49:54', '2021-10-05 05:24:38', 1),
(75, 'Shirt', 71, NULL, 1990, '12.00', 1, '15.00', '13a8a1ebb00b92', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 05:39:06', '2021-10-11 05:44:26', 1),
(76, 'CasualT-shirts', 72, NULL, 1990, '17.00', 1, '22.00', 'be53b93d8b4f17', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 05:45:30', '2021-10-11 05:47:32', 1),
(77, 'Capris', 73, NULL, 1990, '19.00', 1, '22.00', 'a9f6ba2ca77dec', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 05:49:45', '2021-10-11 05:54:17', 1),
(78, 'WomenCapri16339321401ba78c6', 74, NULL, 0, NULL, 1, NULL, '52026e71cb192e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:01:33', '2021-10-11 06:02:20', 1),
(79, 'Shorts', 75, NULL, 2900, '18.00', 1, '22.00', 'a8dcad36a8c50e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:03:22', '2021-10-11 06:18:45', 1),
(80, 'Scirt', 76, NULL, 1889, '19.00', 1, '22.00', '804cdfb98108ee', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:16:53', '2021-10-11 06:18:24', 1),
(81, 'Sweaters', 77, NULL, 1890, '17.00', 1, '22.00', '9ef3df3ac9722b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:20:16', '2021-10-11 06:22:36', 1),
(82, 'Inspiron14Laptop', 78, NULL, 1990, '20.00', 1, '23.00', 'a94347e8103a1d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:29:40', '2021-10-11 06:31:29', 1),
(83, 'LenovoIdeaPadSlim310thGen', 79, NULL, 1900, '19.00', 1, '22.00', 'ee8b027a53323c', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:34:21', '2021-10-11 06:37:39', 1),
(84, 'AcerNitro5GamingLaptopAMD', 80, NULL, 199, '150.00', 1, '165.00', 'b8893cc1f82a49', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:39:03', '2021-10-11 11:11:44', 1),
(85, 'OnePlus9R5G', 81, NULL, 1880, '16.00', 1, '20.00', 'b1d2d3de25950a', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:42:45', '2021-10-11 06:45:28', 1),
(86, 'OnePlus9R5G(LakeBlue,8GBRAM,128GBStorage)', 82, NULL, 1890, '18.00', 1, '22.00', '34ff409e53d79a', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 06:48:14', '2021-10-11 06:57:57', 1),
(87, 'OPPOF19Pro', 83, NULL, 1000, '12.00', 1, '15.00', 'c4f70ae7e0ad25', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:00:01', '2021-10-11 07:03:04', 1),
(88, 'VitaminB-12', 84, NULL, 888, '16.00', 1, '20.00', '17622b8dc42f52', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:05:53', '2021-10-11 11:33:41', 1),
(89, 'HempProtein', 85, NULL, 1890, '14.00', 1, '20.00', '359df175e9c185', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:09:53', '2021-10-11 07:12:09', 1),
(90, 'BrownRiceProtein16339519990a4bbe7', 86, NULL, 1600, '13.00', 1, '15.00', '41ec612c88d1d1', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:13:17', '2021-10-11 11:33:19', 1),
(91, 'BloodGlucoseMonitors:', 87, NULL, 1789, '12.00', 1, '15.00', 'dd587e7fd593c7', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:19:10', '2021-10-11 07:21:32', 1),
(92, 'PedometersAndWeighingScales', 88, NULL, 1780, '12.00', 1, '15.00', '80fe4269747b13', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:22:15', '2021-10-11 07:26:28', 1),
(93, 'Apples', 89, NULL, 1590, '12.00', 1, '15.00', '72d282b61f2f8d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:29:40', '2021-10-11 07:32:36', 1),
(94, 'watermelon', 90, NULL, 1678, '12.00', 1, '14.00', '5f9a22faa94d54', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:33:24', '2021-10-11 07:36:35', 1),
(95, 'cabbage', 91, NULL, 1600, '12.00', 1, '15.00', '34b9366fd85077', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:38:46', '2021-10-11 07:41:27', 1),
(96, 'broccoli', 92, NULL, 1700, '15.00', 1, '18.00', 'dcb836cd06d258', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:42:27', '2021-10-11 07:44:39', 1),
(97, 'cheese', 93, NULL, 1789, '14.00', 1, '18.00', 'f3ffe0746049e4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:48:03', '2021-10-11 07:49:31', 1),
(98, 'milkshake', 94, NULL, 1789, '19.00', 1, '22.00', 'e7fe61ed5956a4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 07:52:05', '2021-10-11 07:54:04', 1),
(99, 'RoestiAndSalad', 95, NULL, 1200, '13.00', 1, '15.00', '1ca362bdd97d82', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 08:42:31', '2021-10-11 10:27:22', 1),
(100, 'PaneerSteak', 96, NULL, 1690, '12.00', 1, '14.00', 'd51ec2b603ff25', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 08:45:11', '2021-10-11 08:50:55', 1),
(101, 'AppleSausagePlait.', 97, NULL, 1789, '12.00', 1, '16.00', 'b7ac8fa7ffb21a', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 08:52:12', '2021-10-11 08:56:22', 1),
(102, 'Polenta.', 98, NULL, 2300, '17.00', 1, '19.00', '345b1f10e32dca', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 08:59:48', '2021-10-11 09:01:57', 1),
(103, 'Ossobuco', 99, NULL, 1500, '15.00', 1, '18.00', '555f31634b8f9f', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 09:02:33', '2021-10-11 09:06:37', 1),
(104, 'QuickNoodles', 100, NULL, 1690, '12.00', 1, '15.00', '1786576b9779e2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 09:07:23', '2021-10-11 09:10:06', 1),
(105, 'SzechwanChilliChicken', 101, NULL, 1890, '17.00', 1, '19.00', 'e992069726f6ee', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-11 09:11:13', '2021-10-11 09:14:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_images`
--

CREATE TABLE `product_variant_images` (
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `product_image_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_sets`
--

CREATE TABLE `product_variant_sets` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `variant_type_id` bigint UNSIGNED DEFAULT NULL,
  `variant_option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_sets`
--

INSERT INTO `product_variant_sets` (`id`, `product_id`, `product_variant_id`, `variant_type_id`, `variant_option_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 1, NULL, NULL),
(2, 1, 2, 2, 5, NULL, NULL),
(3, 1, 3, 1, 1, NULL, NULL),
(4, 1, 3, 2, 6, NULL, NULL),
(5, 1, 3, 1, 1, NULL, NULL),
(6, 1, 3, 2, 6, NULL, NULL),
(7, 1, 4, 1, 7, NULL, NULL),
(8, 1, 4, 2, 5, NULL, NULL),
(9, 1, 5, 1, 7, NULL, NULL),
(10, 1, 5, 2, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci,
  `short_desc` mediumtext COLLATE utf8mb4_unicode_ci,
  `amount` decimal(12,2) UNSIGNED DEFAULT NULL,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `promo_type_id` bigint UNSIGNED DEFAULT NULL,
  `allow_free_delivery` tinyint DEFAULT '0' COMMENT '0- No, 1- yes',
  `minimum_spend` int UNSIGNED DEFAULT NULL,
  `maximum_spend` int UNSIGNED DEFAULT NULL,
  `first_order_only` tinyint DEFAULT '0' COMMENT '0- No, 1- yes',
  `limit_per_user` int DEFAULT NULL,
  `limit_total` int DEFAULT NULL,
  `paid_by_vendor_admin` tinyint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT '0' COMMENT '0- No, 1- yes',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `restriction_on` tinyint DEFAULT '0' COMMENT '0- product, 1-vendor',
  `restriction_type` tinyint DEFAULT '0' COMMENT '0- Include, 1-Exclude',
  `added_by` bigint UNSIGNED DEFAULT NULL,
  `promo_visibility` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'public' COMMENT 'public/private'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promocode_details`
--

CREATE TABLE `promocode_details` (
  `id` bigint UNSIGNED NOT NULL,
  `promocode_id` bigint UNSIGNED DEFAULT NULL,
  `refrence_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promocode_restrictions`
--

CREATE TABLE `promocode_restrictions` (
  `promocode_id` bigint UNSIGNED DEFAULT NULL,
  `restriction_type` tinyint DEFAULT '0' COMMENT '0- product, 1-vendor, 2-category',
  `data_id` bigint UNSIGNED DEFAULT NULL,
  `is_included` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no',
  `is_excluded` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promo_types`
--

CREATE TABLE `promo_types` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - Block, 3 - delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promo_types`
--

INSERT INTO `promo_types` (`id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Percentage Discount', 1, NULL, NULL),
(2, 'Fixed Amount', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promo_usages`
--

CREATE TABLE `promo_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `promocode_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `usage_count` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refer_and_earns`
--

CREATE TABLE `refer_and_earns` (
  `id` bigint UNSIGNED NOT NULL,
  `reffered_by_amount` decimal(8,2) DEFAULT NULL,
  `reffered_to_amount` decimal(8,2) DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_reasons`
--

CREATE TABLE `return_reasons` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Block') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `order` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `return_reasons`
--

INSERT INTO `return_reasons` (`id`, `title`, `status`, `order`, `created_at`, `updated_at`) VALUES
(1, 'The merchant shipped the wrong item', 'Active', 1, NULL, NULL),
(2, 'Purchase arrived too late', 'Active', 2, NULL, NULL),
(3, 'Customer doesn\'t need it anymore', 'Active', 3, NULL, NULL),
(4, 'The product was damaged or defective', 'Active', 4, NULL, NULL),
(5, 'Other', 'Active', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL COMMENT '0 - pending, 1 - active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Buyer', 1, NULL, NULL),
(2, 'Seller', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_areas`
--

CREATE TABLE `service_areas` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `geo_array` text COLLATE utf8mb4_unicode_ci,
  `zoom_level` smallint NOT NULL DEFAULT '13',
  `polygon` geometry DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_options`
--

CREATE TABLE `shipping_options` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` json DEFAULT NULL COMMENT 'credentials in json format',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 inactive, 1 active, 2 delete',
  `test_mode` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = false, 1 = true',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slot_days`
--

CREATE TABLE `slot_days` (
  `id` bigint UNSIGNED NOT NULL,
  `slot_id` bigint UNSIGNED DEFAULT NULL,
  `day` tinyint NOT NULL DEFAULT '0' COMMENT '1 sunday, 2 monday, 3 tuesday, 4 wednesday, 5 thursday, 6 friday, 7 saturday',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_providers`
--

CREATE TABLE `sms_providers` (
  `id` bigint UNSIGNED NOT NULL,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT ' 0 for no, 1 for yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_providers`
--

INSERT INTO `sms_providers` (`id`, `provider`, `keyword`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Twilio Service', 'twilio', 1, NULL, NULL),
(2, 'mTalkz Service', 'mTalkz', 1, NULL, NULL),
(3, 'Mazinhost Service', 'mazinhost', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `social_credentials`
--

CREATE TABLE `social_credentials` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `access_token` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refresh_token` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_media`
--

INSERT INTO `social_media` (`id`, `icon`, `title`, `url`, `created_at`, `updated_at`) VALUES
(1, 'facebook', NULL, 'https://www.facebook.com', '2021-12-20 06:28:13', '2021-12-20 06:28:13'),
(2, 'instagram', NULL, 'https://www.instagram.com', '2021-12-20 06:28:26', '2021-12-20 06:28:26'),
(3, 'twitter', NULL, 'https://twitter.com', '2021-12-20 06:28:45', '2021-12-20 06:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_features_list_user`
--

CREATE TABLE `subscription_features_list_user` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_features_list_user`
--

INSERT INTO `subscription_features_list_user` (`id`, `title`, `Description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Free Delivery', '', 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_features_list_vendor`
--

CREATE TABLE `subscription_features_list_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_features_list_vendor`
--

INSERT INTO `subscription_features_list_vendor` (`id`, `title`, `Description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Trending', '', 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_invoices_user`
--

CREATE TABLE `subscription_invoices_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `payment_option_id` tinyint UNSIGNED DEFAULT NULL,
  `status_id` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `next_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `subscription_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(12,2) DEFAULT NULL,
  `paid_amount` decimal(12,2) DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_invoices_vendor`
--

CREATE TABLE `subscription_invoices_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `payment_option_id` tinyint UNSIGNED DEFAULT NULL,
  `status_id` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `next_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `subscription_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(12,2) DEFAULT NULL,
  `paid_amount` decimal(12,2) DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `rejected_by` bigint UNSIGNED DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_invoice_features_user`
--

CREATE TABLE `subscription_invoice_features_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_invoice_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `feature_id` bigint UNSIGNED DEFAULT NULL,
  `feature_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_invoice_features_vendor`
--

CREATE TABLE `subscription_invoice_features_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `subscription_invoice_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `feature_id` bigint UNSIGNED DEFAULT NULL,
  `feature_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_log_user`
--

CREATE TABLE `subscription_log_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_invoice_id` bigint UNSIGNED DEFAULT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `payment_option_id` tinyint UNSIGNED DEFAULT NULL,
  `status_id` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_reference` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime DEFAULT NULL,
  `next_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `subscription_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(12,2) DEFAULT NULL,
  `paid_amount` decimal(12,2) DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans_user`
--

CREATE TABLE `subscription_plans_user` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `period` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'plan validity in days',
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint NOT NULL DEFAULT '1' COMMENT 'for same position, display asc order',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans_vendor`
--

CREATE TABLE `subscription_plans_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `period` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'plan validity in days',
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint NOT NULL DEFAULT '1' COMMENT 'for same position, display asc order',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `on_request` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_features_user`
--

CREATE TABLE `subscription_plan_features_user` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_plan_id` bigint UNSIGNED NOT NULL,
  `feature_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_features_vendor`
--

CREATE TABLE `subscription_plan_features_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_plan_id` bigint UNSIGNED NOT NULL,
  `feature_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_status_options`
--

CREATE TABLE `subscription_status_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0-Inactive, 1-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_status_options`
--

INSERT INTO `subscription_status_options` (`id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pending', 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(2, 'Active', 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(3, 'Inactive', 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(4, 'Rejected', 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43'),
(5, 'Cancelled', 1, '2021-12-20 06:18:43', '2021-12-20 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag_translations`
--

CREATE TABLE `tag_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `language_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_categories`
--

CREATE TABLE `tax_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_core` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_categories`
--

INSERT INTO `tax_categories` (`id`, `title`, `code`, `description`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'VAT', 'vat', NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` bigint UNSIGNED NOT NULL,
  `identifier` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_zip` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_from` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_to` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `identifier`, `is_zip`, `zip_code`, `zip_from`, `zip_to`, `state`, `country`, `tax_rate`, `tax_amount`, `created_at`, `updated_at`) VALUES
(1, 'VAT', 0, '', '', '', 'Dubai', 'United Arab Emirates', '5.00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tax_rate_categories`
--

CREATE TABLE `tax_rate_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `tax_cate_id` bigint UNSIGNED NOT NULL,
  `tax_rate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_rate_categories`
--

INSERT INTO `tax_rate_categories` (`id`, `tax_cate_id`, `tax_rate_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `for` tinyint NOT NULL DEFAULT '0' COMMENT '1 for web, 2 for app',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `name`, `image`, `for`, `created_at`, `updated_at`) VALUES
(1, 'Default', 'default/templete.jpg', 1, NULL, NULL),
(2, 'Default', 'default/templete.jpg', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `terminologies`
--

CREATE TABLE `terminologies` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint UNSIGNED NOT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `offset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diff_from_gtm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `timezone`, `offset`, `diff_from_gtm`, `created_at`, `updated_at`) VALUES
(1, 'Africa/Abidjan', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(2, 'Africa/Accra', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(3, 'Africa/Addis_Ababa', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:39', '2021-12-20 09:18:39'),
(4, 'Africa/Algiers', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(5, 'Africa/Asmara', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:39', '2021-12-20 09:18:39'),
(6, 'Africa/Bamako', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(7, 'Africa/Bangui', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(8, 'Africa/Banjul', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(9, 'Africa/Bissau', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(10, 'Africa/Blantyre', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(11, 'Africa/Brazzaville', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(12, 'Africa/Bujumbura', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(13, 'Africa/Cairo', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(14, 'Africa/Casablanca', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(15, 'Africa/Ceuta', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(16, 'Africa/Conakry', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(17, 'Africa/Dakar', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(18, 'Africa/Dar_es_Salaam', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:39', '2021-12-20 09:18:39'),
(19, 'Africa/Djibouti', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:39', '2021-12-20 09:18:39'),
(20, 'Africa/Douala', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(21, 'Africa/El_Aaiun', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(22, 'Africa/Freetown', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(23, 'Africa/Gaborone', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(24, 'Africa/Harare', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(25, 'Africa/Johannesburg', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(26, 'Africa/Juba', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(27, 'Africa/Kampala', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:39', '2021-12-20 09:18:39'),
(28, 'Africa/Khartoum', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(29, 'Africa/Kigali', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(30, 'Africa/Kinshasa', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(31, 'Africa/Lagos', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(32, 'Africa/Libreville', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(33, 'Africa/Lome', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(34, 'Africa/Luanda', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(35, 'Africa/Lubumbashi', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(36, 'Africa/Lusaka', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(37, 'Africa/Malabo', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(38, 'Africa/Maputo', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(39, 'Africa/Maseru', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(40, 'Africa/Mbabane', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(41, 'Africa/Mogadishu', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:39', '2021-12-20 09:18:39'),
(42, 'Africa/Monrovia', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(43, 'Africa/Nairobi', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:39', '2021-12-20 09:18:39'),
(44, 'Africa/Ndjamena', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(45, 'Africa/Niamey', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(46, 'Africa/Nouakchott', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(47, 'Africa/Ouagadougou', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(48, 'Africa/Porto-Novo', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(49, 'Africa/Sao_Tome', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:39', '2021-12-20 06:18:39'),
(50, 'Africa/Tripoli', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(51, 'Africa/Tunis', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:39', '2021-12-20 07:18:39'),
(52, 'Africa/Windhoek', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:39', '2021-12-20 08:18:39'),
(53, 'America/Adak', '-10:00', 'UTC/GMT -10:00', '2021-12-19 20:18:39', '2021-12-19 20:18:39'),
(54, 'America/Anchorage', '-09:00', 'UTC/GMT -09:00', '2021-12-19 21:18:39', '2021-12-19 21:18:39'),
(55, 'America/Anguilla', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:39', '2021-12-20 02:18:39'),
(56, 'America/Antigua', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:39', '2021-12-20 02:18:39'),
(57, 'America/Araguaina', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(58, 'America/Argentina/Buenos_Aires', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(59, 'America/Argentina/Catamarca', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(60, 'America/Argentina/Cordoba', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(61, 'America/Argentina/Jujuy', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(62, 'America/Argentina/La_Rioja', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(63, 'America/Argentina/Mendoza', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(64, 'America/Argentina/Rio_Gallegos', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(65, 'America/Argentina/Salta', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(66, 'America/Argentina/San_Juan', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(67, 'America/Argentina/San_Luis', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:39', '2021-12-20 03:18:39'),
(68, 'America/Argentina/Tucuman', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(69, 'America/Argentina/Ushuaia', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(70, 'America/Aruba', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(71, 'America/Asuncion', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(72, 'America/Atikokan', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(73, 'America/Bahia', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(74, 'America/Bahia_Banderas', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(75, 'America/Barbados', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(76, 'America/Belem', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(77, 'America/Belize', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(78, 'America/Blanc-Sablon', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(79, 'America/Boa_Vista', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(80, 'America/Bogota', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(81, 'America/Boise', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(82, 'America/Cambridge_Bay', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(83, 'America/Campo_Grande', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(84, 'America/Cancun', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(85, 'America/Caracas', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(86, 'America/Cayenne', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(87, 'America/Cayman', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(88, 'America/Chicago', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(89, 'America/Chihuahua', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(90, 'America/Costa_Rica', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(91, 'America/Creston', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(92, 'America/Cuiaba', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(93, 'America/Curacao', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(94, 'America/Danmarkshavn', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:40', '2021-12-20 06:18:40'),
(95, 'America/Dawson', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(96, 'America/Dawson_Creek', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(97, 'America/Denver', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(98, 'America/Detroit', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(99, 'America/Dominica', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(100, 'America/Edmonton', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(101, 'America/Eirunepe', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(102, 'America/El_Salvador', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(103, 'America/Fort_Nelson', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(104, 'America/Fortaleza', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(105, 'America/Glace_Bay', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(106, 'America/Goose_Bay', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(107, 'America/Grand_Turk', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(108, 'America/Grenada', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(109, 'America/Guadeloupe', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(110, 'America/Guatemala', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(111, 'America/Guayaquil', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(112, 'America/Guyana', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(113, 'America/Halifax', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(114, 'America/Havana', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(115, 'America/Hermosillo', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(116, 'America/Indiana/Indianapolis', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(117, 'America/Indiana/Knox', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(118, 'America/Indiana/Marengo', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(119, 'America/Indiana/Petersburg', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(120, 'America/Indiana/Tell_City', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(121, 'America/Indiana/Vevay', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(122, 'America/Indiana/Vincennes', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(123, 'America/Indiana/Winamac', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(124, 'America/Inuvik', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(125, 'America/Iqaluit', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(126, 'America/Jamaica', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(127, 'America/Juneau', '-09:00', 'UTC/GMT -09:00', '2021-12-19 21:18:40', '2021-12-19 21:18:40'),
(128, 'America/Kentucky/Louisville', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(129, 'America/Kentucky/Monticello', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(130, 'America/Kralendijk', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(131, 'America/La_Paz', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(132, 'America/Lima', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(133, 'America/Los_Angeles', '-08:00', 'UTC/GMT -08:00', '2021-12-19 22:18:40', '2021-12-19 22:18:40'),
(134, 'America/Lower_Princes', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(135, 'America/Maceio', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(136, 'America/Managua', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(137, 'America/Manaus', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(138, 'America/Marigot', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(139, 'America/Martinique', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(140, 'America/Matamoros', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(141, 'America/Mazatlan', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:40', '2021-12-19 23:18:40'),
(142, 'America/Menominee', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(143, 'America/Merida', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(144, 'America/Metlakatla', '-09:00', 'UTC/GMT -09:00', '2021-12-19 21:18:40', '2021-12-19 21:18:40'),
(145, 'America/Mexico_City', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(146, 'America/Miquelon', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(147, 'America/Moncton', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(148, 'America/Monterrey', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(149, 'America/Montevideo', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(150, 'America/Montserrat', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:40', '2021-12-20 02:18:40'),
(151, 'America/Nassau', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(152, 'America/New_York', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(153, 'America/Nipigon', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:40', '2021-12-20 01:18:40'),
(154, 'America/Nome', '-09:00', 'UTC/GMT -09:00', '2021-12-19 21:18:40', '2021-12-19 21:18:40'),
(155, 'America/Noronha', '-02:00', 'UTC/GMT -02:00', '2021-12-20 04:18:40', '2021-12-20 04:18:40'),
(156, 'America/North_Dakota/Beulah', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(157, 'America/North_Dakota/Center', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(158, 'America/North_Dakota/New_Salem', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:40', '2021-12-20 00:18:40'),
(159, 'America/Nuuk', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:40', '2021-12-20 03:18:40'),
(160, 'America/Ojinaga', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:41', '2021-12-19 23:18:41'),
(161, 'America/Panama', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:41', '2021-12-20 01:18:41'),
(162, 'America/Pangnirtung', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:41', '2021-12-20 01:18:41'),
(163, 'America/Paramaribo', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(164, 'America/Phoenix', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:41', '2021-12-19 23:18:41'),
(165, 'America/Port-au-Prince', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:41', '2021-12-20 01:18:41'),
(166, 'America/Port_of_Spain', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(167, 'America/Porto_Velho', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(168, 'America/Puerto_Rico', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(169, 'America/Punta_Arenas', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(170, 'America/Rainy_River', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:41', '2021-12-20 00:18:41'),
(171, 'America/Rankin_Inlet', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:41', '2021-12-20 00:18:41'),
(172, 'America/Recife', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(173, 'America/Regina', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:41', '2021-12-20 00:18:41'),
(174, 'America/Resolute', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:41', '2021-12-20 00:18:41'),
(175, 'America/Rio_Branco', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:41', '2021-12-20 01:18:41'),
(176, 'America/Santarem', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(177, 'America/Santiago', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(178, 'America/Santo_Domingo', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(179, 'America/Sao_Paulo', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(180, 'America/Scoresbysund', '-01:00', 'UTC/GMT -01:00', '2021-12-20 05:18:41', '2021-12-20 05:18:41'),
(181, 'America/Sitka', '-09:00', 'UTC/GMT -09:00', '2021-12-19 21:18:41', '2021-12-19 21:18:41'),
(182, 'America/St_Barthelemy', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(183, 'America/St_Johns', '-03:30', 'UTC/GMT -03:30', '2021-12-20 02:48:41', '2021-12-20 02:48:41'),
(184, 'America/St_Kitts', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(185, 'America/St_Lucia', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(186, 'America/St_Thomas', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(187, 'America/St_Vincent', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(188, 'America/Swift_Current', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:41', '2021-12-20 00:18:41'),
(189, 'America/Tegucigalpa', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:41', '2021-12-20 00:18:41'),
(190, 'America/Thule', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(191, 'America/Thunder_Bay', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:41', '2021-12-20 01:18:41'),
(192, 'America/Tijuana', '-08:00', 'UTC/GMT -08:00', '2021-12-19 22:18:41', '2021-12-19 22:18:41'),
(193, 'America/Toronto', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:41', '2021-12-20 01:18:41'),
(194, 'America/Tortola', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:41', '2021-12-20 02:18:41'),
(195, 'America/Vancouver', '-08:00', 'UTC/GMT -08:00', '2021-12-19 22:18:41', '2021-12-19 22:18:41'),
(196, 'America/Whitehorse', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:41', '2021-12-19 23:18:41'),
(197, 'America/Winnipeg', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:41', '2021-12-20 00:18:41'),
(198, 'America/Yakutat', '-09:00', 'UTC/GMT -09:00', '2021-12-19 21:18:41', '2021-12-19 21:18:41'),
(199, 'America/Yellowknife', '-07:00', 'UTC/GMT -07:00', '2021-12-19 23:18:41', '2021-12-19 23:18:41'),
(200, 'Antarctica/Casey', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:41', '2021-12-20 17:18:41'),
(201, 'Antarctica/Davis', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:41', '2021-12-20 13:18:41'),
(202, 'Antarctica/DumontDUrville', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:41', '2021-12-20 16:18:41'),
(203, 'Antarctica/Macquarie', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:41', '2021-12-20 17:18:41'),
(204, 'Antarctica/Mawson', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:41', '2021-12-20 11:18:41'),
(205, 'Antarctica/McMurdo', '+13:00', 'UTC/GMT +13:00', '2021-12-20 19:18:41', '2021-12-20 19:18:41'),
(206, 'Antarctica/Palmer', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(207, 'Antarctica/Rothera', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:41', '2021-12-20 03:18:41'),
(208, 'Antarctica/Syowa', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:41', '2021-12-20 09:18:41'),
(209, 'Antarctica/Troll', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:41', '2021-12-20 06:18:41'),
(210, 'Antarctica/Vostok', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:41', '2021-12-20 12:18:41'),
(211, 'Arctic/Longyearbyen', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:41', '2021-12-20 07:18:41'),
(212, 'Asia/Aden', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:41', '2021-12-20 09:18:41'),
(213, 'Asia/Almaty', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:41', '2021-12-20 12:18:41'),
(214, 'Asia/Amman', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:41', '2021-12-20 08:18:41'),
(215, 'Asia/Anadyr', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:41', '2021-12-20 18:18:41'),
(216, 'Asia/Aqtau', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:41', '2021-12-20 11:18:41'),
(217, 'Asia/Aqtobe', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:41', '2021-12-20 11:18:41'),
(218, 'Asia/Ashgabat', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:41', '2021-12-20 11:18:41'),
(219, 'Asia/Atyrau', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:41', '2021-12-20 11:18:41'),
(220, 'Asia/Baghdad', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:41', '2021-12-20 09:18:41'),
(221, 'Asia/Bahrain', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:41', '2021-12-20 09:18:41'),
(222, 'Asia/Baku', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:41', '2021-12-20 10:18:41'),
(223, 'Asia/Bangkok', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:41', '2021-12-20 13:18:41'),
(224, 'Asia/Barnaul', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:41', '2021-12-20 13:18:41'),
(225, 'Asia/Beirut', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:41', '2021-12-20 08:18:41'),
(226, 'Asia/Bishkek', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:41', '2021-12-20 12:18:41'),
(227, 'Asia/Brunei', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(228, 'Asia/Chita', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:41', '2021-12-20 15:18:41'),
(229, 'Asia/Choibalsan', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(230, 'Asia/Colombo', '+05:30', 'UTC/GMT +05:30', '2021-12-20 11:48:41', '2021-12-20 11:48:41'),
(231, 'Asia/Damascus', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:41', '2021-12-20 08:18:41'),
(232, 'Asia/Dhaka', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:41', '2021-12-20 12:18:41'),
(233, 'Asia/Dili', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:41', '2021-12-20 15:18:41'),
(234, 'Asia/Dubai', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:41', '2021-12-20 10:18:41'),
(235, 'Asia/Dushanbe', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:41', '2021-12-20 11:18:41'),
(236, 'Asia/Famagusta', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:41', '2021-12-20 08:18:41'),
(237, 'Asia/Gaza', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:41', '2021-12-20 08:18:41'),
(238, 'Asia/Hebron', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:41', '2021-12-20 08:18:41'),
(239, 'Asia/Ho_Chi_Minh', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:41', '2021-12-20 13:18:41'),
(240, 'Asia/Hong_Kong', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(241, 'Asia/Hovd', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:41', '2021-12-20 13:18:41'),
(242, 'Asia/Irkutsk', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(243, 'Asia/Jakarta', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:41', '2021-12-20 13:18:41'),
(244, 'Asia/Jayapura', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:41', '2021-12-20 15:18:41'),
(245, 'Asia/Jerusalem', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:41', '2021-12-20 08:18:41'),
(246, 'Asia/Kabul', '+04:30', 'UTC/GMT +04:30', '2021-12-20 10:48:41', '2021-12-20 10:48:41'),
(247, 'Asia/Kamchatka', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:41', '2021-12-20 18:18:41'),
(248, 'Asia/Karachi', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:41', '2021-12-20 11:18:41'),
(249, 'Asia/Kathmandu', '+05:45', 'UTC/GMT +05:45', '2021-12-20 12:03:41', '2021-12-20 12:03:41'),
(250, 'Asia/Khandyga', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:41', '2021-12-20 15:18:41'),
(251, 'Asia/Kolkata', '+05:30', 'UTC/GMT +05:30', '2021-12-20 11:48:41', '2021-12-20 11:48:41'),
(252, 'Asia/Krasnoyarsk', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:41', '2021-12-20 13:18:41'),
(253, 'Asia/Kuala_Lumpur', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(254, 'Asia/Kuching', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(255, 'Asia/Kuwait', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:41', '2021-12-20 09:18:41'),
(256, 'Asia/Macau', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(257, 'Asia/Magadan', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:41', '2021-12-20 17:18:41'),
(258, 'Asia/Makassar', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(259, 'Asia/Manila', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:41', '2021-12-20 14:18:41'),
(260, 'Asia/Muscat', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:41', '2021-12-20 10:18:41'),
(261, 'Asia/Nicosia', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(262, 'Asia/Novokuznetsk', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:42', '2021-12-20 13:18:42'),
(263, 'Asia/Novosibirsk', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:42', '2021-12-20 13:18:42'),
(264, 'Asia/Omsk', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:42', '2021-12-20 12:18:42'),
(265, 'Asia/Oral', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:42', '2021-12-20 11:18:42'),
(266, 'Asia/Phnom_Penh', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:42', '2021-12-20 13:18:42'),
(267, 'Asia/Pontianak', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:42', '2021-12-20 13:18:42'),
(268, 'Asia/Pyongyang', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:42', '2021-12-20 15:18:42'),
(269, 'Asia/Qatar', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:42', '2021-12-20 09:18:42'),
(270, 'Asia/Qostanay', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:42', '2021-12-20 12:18:42'),
(271, 'Asia/Qyzylorda', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:42', '2021-12-20 11:18:42'),
(272, 'Asia/Riyadh', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:42', '2021-12-20 09:18:42'),
(273, 'Asia/Sakhalin', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:42', '2021-12-20 17:18:42'),
(274, 'Asia/Samarkand', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:42', '2021-12-20 11:18:42'),
(275, 'Asia/Seoul', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:42', '2021-12-20 15:18:42'),
(276, 'Asia/Shanghai', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:42', '2021-12-20 14:18:42'),
(277, 'Asia/Singapore', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:42', '2021-12-20 14:18:42'),
(278, 'Asia/Srednekolymsk', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:42', '2021-12-20 17:18:42'),
(279, 'Asia/Taipei', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:42', '2021-12-20 14:18:42'),
(280, 'Asia/Tashkent', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:42', '2021-12-20 11:18:42'),
(281, 'Asia/Tbilisi', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:42', '2021-12-20 10:18:42'),
(282, 'Asia/Tehran', '+03:30', 'UTC/GMT +03:30', '2021-12-20 09:48:42', '2021-12-20 09:48:42'),
(283, 'Asia/Thimphu', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:42', '2021-12-20 12:18:42'),
(284, 'Asia/Tokyo', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:42', '2021-12-20 15:18:42'),
(285, 'Asia/Tomsk', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:42', '2021-12-20 13:18:42'),
(286, 'Asia/Ulaanbaatar', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:42', '2021-12-20 14:18:42'),
(287, 'Asia/Urumqi', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:42', '2021-12-20 12:18:42'),
(288, 'Asia/Ust-Nera', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:42', '2021-12-20 16:18:42'),
(289, 'Asia/Vientiane', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:42', '2021-12-20 13:18:42'),
(290, 'Asia/Vladivostok', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:42', '2021-12-20 16:18:42'),
(291, 'Asia/Yakutsk', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:42', '2021-12-20 15:18:42'),
(292, 'Asia/Yangon', '+06:30', 'UTC/GMT +06:30', '2021-12-20 12:48:42', '2021-12-20 12:48:42'),
(293, 'Asia/Yekaterinburg', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:42', '2021-12-20 11:18:42'),
(294, 'Asia/Yerevan', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:42', '2021-12-20 10:18:42'),
(295, 'Atlantic/Azores', '-01:00', 'UTC/GMT -01:00', '2021-12-20 05:18:42', '2021-12-20 05:18:42'),
(296, 'Atlantic/Bermuda', '-04:00', 'UTC/GMT -04:00', '2021-12-20 02:18:42', '2021-12-20 02:18:42'),
(297, 'Atlantic/Canary', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(298, 'Atlantic/Cape_Verde', '-01:00', 'UTC/GMT -01:00', '2021-12-20 05:18:42', '2021-12-20 05:18:42'),
(299, 'Atlantic/Faroe', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(300, 'Atlantic/Madeira', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(301, 'Atlantic/Reykjavik', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(302, 'Atlantic/South_Georgia', '-02:00', 'UTC/GMT -02:00', '2021-12-20 04:18:42', '2021-12-20 04:18:42'),
(303, 'Atlantic/St_Helena', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(304, 'Atlantic/Stanley', '-03:00', 'UTC/GMT -03:00', '2021-12-20 03:18:42', '2021-12-20 03:18:42'),
(305, 'Australia/Adelaide', '+10:30', 'UTC/GMT +10:30', '2021-12-20 16:48:42', '2021-12-20 16:48:42'),
(306, 'Australia/Brisbane', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:42', '2021-12-20 16:18:42'),
(307, 'Australia/Broken_Hill', '+10:30', 'UTC/GMT +10:30', '2021-12-20 16:48:42', '2021-12-20 16:48:42'),
(308, 'Australia/Darwin', '+09:30', 'UTC/GMT +09:30', '2021-12-20 15:48:42', '2021-12-20 15:48:42'),
(309, 'Australia/Eucla', '+08:45', 'UTC/GMT +08:45', '2021-12-20 15:03:42', '2021-12-20 15:03:42'),
(310, 'Australia/Hobart', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:42', '2021-12-20 17:18:42'),
(311, 'Australia/Lindeman', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:42', '2021-12-20 16:18:42'),
(312, 'Australia/Lord_Howe', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:42', '2021-12-20 17:18:42'),
(313, 'Australia/Melbourne', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:42', '2021-12-20 17:18:42'),
(314, 'Australia/Perth', '+08:00', 'UTC/GMT +08:00', '2021-12-20 14:18:42', '2021-12-20 14:18:42'),
(315, 'Australia/Sydney', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:42', '2021-12-20 17:18:42'),
(316, 'Europe/Amsterdam', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(317, 'Europe/Andorra', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(318, 'Europe/Astrakhan', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:42', '2021-12-20 10:18:42'),
(319, 'Europe/Athens', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(320, 'Europe/Belgrade', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(321, 'Europe/Berlin', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(322, 'Europe/Bratislava', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(323, 'Europe/Brussels', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(324, 'Europe/Bucharest', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(325, 'Europe/Budapest', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(326, 'Europe/Busingen', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(327, 'Europe/Chisinau', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(328, 'Europe/Copenhagen', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(329, 'Europe/Dublin', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(330, 'Europe/Gibraltar', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(331, 'Europe/Guernsey', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(332, 'Europe/Helsinki', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(333, 'Europe/Isle_of_Man', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(334, 'Europe/Istanbul', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:42', '2021-12-20 09:18:42'),
(335, 'Europe/Jersey', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(336, 'Europe/Kaliningrad', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(337, 'Europe/Kiev', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(338, 'Europe/Kirov', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:42', '2021-12-20 09:18:42'),
(339, 'Europe/Lisbon', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(340, 'Europe/Ljubljana', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(341, 'Europe/London', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:42', '2021-12-20 06:18:42'),
(342, 'Europe/Luxembourg', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(343, 'Europe/Madrid', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(344, 'Europe/Malta', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(345, 'Europe/Mariehamn', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(346, 'Europe/Minsk', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:42', '2021-12-20 09:18:42'),
(347, 'Europe/Monaco', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(348, 'Europe/Moscow', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:42', '2021-12-20 09:18:42'),
(349, 'Europe/Oslo', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(350, 'Europe/Paris', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(351, 'Europe/Podgorica', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(352, 'Europe/Prague', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(353, 'Europe/Riga', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(354, 'Europe/Rome', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(355, 'Europe/Samara', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:42', '2021-12-20 10:18:42'),
(356, 'Europe/San_Marino', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(357, 'Europe/Sarajevo', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(358, 'Europe/Saratov', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:42', '2021-12-20 10:18:42'),
(359, 'Europe/Simferopol', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:42', '2021-12-20 09:18:42'),
(360, 'Europe/Skopje', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(361, 'Europe/Sofia', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(362, 'Europe/Stockholm', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(363, 'Europe/Tallinn', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:42', '2021-12-20 08:18:42'),
(364, 'Europe/Tirane', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:42', '2021-12-20 07:18:42'),
(365, 'Europe/Ulyanovsk', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:42', '2021-12-20 10:18:42'),
(366, 'Europe/Uzhgorod', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:43', '2021-12-20 08:18:43'),
(367, 'Europe/Vaduz', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:43', '2021-12-20 07:18:43'),
(368, 'Europe/Vatican', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:43', '2021-12-20 07:18:43'),
(369, 'Europe/Vienna', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:43', '2021-12-20 07:18:43'),
(370, 'Europe/Vilnius', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:43', '2021-12-20 08:18:43'),
(371, 'Europe/Volgograd', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:43', '2021-12-20 09:18:43'),
(372, 'Europe/Warsaw', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:43', '2021-12-20 07:18:43'),
(373, 'Europe/Zagreb', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:43', '2021-12-20 07:18:43'),
(374, 'Europe/Zaporozhye', '+02:00', 'UTC/GMT +02:00', '2021-12-20 08:18:43', '2021-12-20 08:18:43'),
(375, 'Europe/Zurich', '+01:00', 'UTC/GMT +01:00', '2021-12-20 07:18:43', '2021-12-20 07:18:43'),
(376, 'Indian/Antananarivo', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:43', '2021-12-20 09:18:43'),
(377, 'Indian/Chagos', '+06:00', 'UTC/GMT +06:00', '2021-12-20 12:18:43', '2021-12-20 12:18:43'),
(378, 'Indian/Christmas', '+07:00', 'UTC/GMT +07:00', '2021-12-20 13:18:43', '2021-12-20 13:18:43'),
(379, 'Indian/Cocos', '+06:30', 'UTC/GMT +06:30', '2021-12-20 12:48:43', '2021-12-20 12:48:43'),
(380, 'Indian/Comoro', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:43', '2021-12-20 09:18:43'),
(381, 'Indian/Kerguelen', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:43', '2021-12-20 11:18:43'),
(382, 'Indian/Mahe', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:43', '2021-12-20 10:18:43'),
(383, 'Indian/Maldives', '+05:00', 'UTC/GMT +05:00', '2021-12-20 11:18:43', '2021-12-20 11:18:43'),
(384, 'Indian/Mauritius', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:43', '2021-12-20 10:18:43'),
(385, 'Indian/Mayotte', '+03:00', 'UTC/GMT +03:00', '2021-12-20 09:18:43', '2021-12-20 09:18:43'),
(386, 'Indian/Reunion', '+04:00', 'UTC/GMT +04:00', '2021-12-20 10:18:43', '2021-12-20 10:18:43'),
(387, 'Pacific/Apia', '+13:00', 'UTC/GMT +13:00', '2021-12-20 19:18:43', '2021-12-20 19:18:43'),
(388, 'Pacific/Auckland', '+13:00', 'UTC/GMT +13:00', '2021-12-20 19:18:43', '2021-12-20 19:18:43'),
(389, 'Pacific/Bougainville', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:43', '2021-12-20 17:18:43'),
(390, 'Pacific/Chatham', '+13:45', 'UTC/GMT +13:45', '2021-12-20 20:03:43', '2021-12-20 20:03:43'),
(391, 'Pacific/Chuuk', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:43', '2021-12-20 16:18:43'),
(392, 'Pacific/Easter', '-05:00', 'UTC/GMT -05:00', '2021-12-20 01:18:43', '2021-12-20 01:18:43'),
(393, 'Pacific/Efate', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:43', '2021-12-20 17:18:43'),
(394, 'Pacific/Fakaofo', '+13:00', 'UTC/GMT +13:00', '2021-12-20 19:18:43', '2021-12-20 19:18:43'),
(395, 'Pacific/Fiji', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(396, 'Pacific/Funafuti', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(397, 'Pacific/Galapagos', '-06:00', 'UTC/GMT -06:00', '2021-12-20 00:18:43', '2021-12-20 00:18:43'),
(398, 'Pacific/Gambier', '-09:00', 'UTC/GMT -09:00', '2021-12-19 21:18:43', '2021-12-19 21:18:43'),
(399, 'Pacific/Guadalcanal', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:43', '2021-12-20 17:18:43'),
(400, 'Pacific/Guam', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:43', '2021-12-20 16:18:43'),
(401, 'Pacific/Honolulu', '-10:00', 'UTC/GMT -10:00', '2021-12-19 20:18:43', '2021-12-19 20:18:43'),
(402, 'Pacific/Kanton', '+13:00', 'UTC/GMT +13:00', '2021-12-20 19:18:43', '2021-12-20 19:18:43'),
(403, 'Pacific/Kiritimati', '+14:00', 'UTC/GMT +14:00', '2021-12-20 20:18:43', '2021-12-20 20:18:43'),
(404, 'Pacific/Kosrae', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:43', '2021-12-20 17:18:43'),
(405, 'Pacific/Kwajalein', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(406, 'Pacific/Majuro', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(407, 'Pacific/Marquesas', '-09:30', 'UTC/GMT -09:30', '2021-12-19 20:48:43', '2021-12-19 20:48:43'),
(408, 'Pacific/Midway', '-11:00', 'UTC/GMT -11:00', '2021-12-19 19:18:43', '2021-12-19 19:18:43'),
(409, 'Pacific/Nauru', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(410, 'Pacific/Niue', '-11:00', 'UTC/GMT -11:00', '2021-12-19 19:18:43', '2021-12-19 19:18:43'),
(411, 'Pacific/Norfolk', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(412, 'Pacific/Noumea', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:43', '2021-12-20 17:18:43'),
(413, 'Pacific/Pago_Pago', '-11:00', 'UTC/GMT -11:00', '2021-12-19 19:18:43', '2021-12-19 19:18:43'),
(414, 'Pacific/Palau', '+09:00', 'UTC/GMT +09:00', '2021-12-20 15:18:43', '2021-12-20 15:18:43'),
(415, 'Pacific/Pitcairn', '-08:00', 'UTC/GMT -08:00', '2021-12-19 22:18:43', '2021-12-19 22:18:43'),
(416, 'Pacific/Pohnpei', '+11:00', 'UTC/GMT +11:00', '2021-12-20 17:18:43', '2021-12-20 17:18:43'),
(417, 'Pacific/Port_Moresby', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:43', '2021-12-20 16:18:43'),
(418, 'Pacific/Rarotonga', '-10:00', 'UTC/GMT -10:00', '2021-12-19 20:18:43', '2021-12-19 20:18:43'),
(419, 'Pacific/Saipan', '+10:00', 'UTC/GMT +10:00', '2021-12-20 16:18:43', '2021-12-20 16:18:43'),
(420, 'Pacific/Tahiti', '-10:00', 'UTC/GMT -10:00', '2021-12-19 20:18:43', '2021-12-19 20:18:43'),
(421, 'Pacific/Tarawa', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(422, 'Pacific/Tongatapu', '+13:00', 'UTC/GMT +13:00', '2021-12-20 19:18:43', '2021-12-20 19:18:43'),
(423, 'Pacific/Wake', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(424, 'Pacific/Wallis', '+12:00', 'UTC/GMT +12:00', '2021-12-20 18:18:43', '2021-12-20 18:18:43'),
(425, 'UTC', '+00:00', 'UTC/GMT +00:00', '2021-12-20 06:18:43', '2021-12-20 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `payable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_id` bigint UNSIGNED NOT NULL,
  `wallet_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('deposit','withdraw') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(64,0) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `meta` json DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `from_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint UNSIGNED NOT NULL,
  `to_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_id` bigint UNSIGNED NOT NULL,
  `status` enum('exchange','transfer','paid','refund','gift') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `status_last` enum('exchange','transfer','paid','refund','gift') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_id` bigint UNSIGNED NOT NULL,
  `withdraw_id` bigint UNSIGNED NOT NULL,
  `discount` decimal(64,0) NOT NULL DEFAULT '0',
  `fee` decimal(64,0) NOT NULL DEFAULT '0',
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` mediumtext COLLATE utf8mb4_unicode_ci,
  `sequence` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `title`, `description`, `image`, `sequence`, `created_at`, `updated_at`) VALUES
(1, 'Product', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'product.png', 2, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(2, 'Pickup/Parent', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'pickup_delivery.png', 7, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(3, 'Vendor', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'vendor.png', 3, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(4, 'Brand', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'brand.png', 4, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(5, 'Celebrity', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'celebrity.png', 6, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(6, 'Subcategory', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'subcategory.png', 1, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(7, 'Pickup/Delivery', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'dispatcher.png', 6, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(8, 'On Demand Service', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'ondemand.png', 7, '2021-12-20 06:18:44', '2021-12-20 06:18:44'),
(9, 'Laundry', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'laundry.png', 8, '2021-12-20 06:18:44', '2021-12-20 06:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dial_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1 for buyer, 2 for seller',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - pending, 1 - active, 2 - blocked, 3 - inactive',
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `facebook_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_token` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_token_valid_till` timestamp NULL DEFAULT NULL,
  `phone_token` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_token_valid_till` timestamp NULL DEFAULT NULL,
  `is_email_verified` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `is_phone_verified` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_superadmin` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `is_admin` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone_id` bigint UNSIGNED DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `description`, `phone_number`, `dial_code`, `email_verified_at`, `password`, `type`, `status`, `country_id`, `role_id`, `auth_token`, `system_id`, `remember_token`, `created_at`, `updated_at`, `facebook_auth_id`, `twitter_auth_id`, `google_auth_id`, `apple_auth_id`, `image`, `email_token`, `email_token_valid_till`, `phone_token`, `phone_token_valid_till`, `is_email_verified`, `is_phone_verified`, `code`, `is_superadmin`, `is_admin`, `title`, `timezone_id`, `timezone`) VALUES
(1, 'Moving Wheels Delivery', 'admin@movingwheelsdelivery.com', NULL, '6138936373', NULL, NULL, '$2y$10$vbOUut0ELEpa2SGoZsU4KOhuFuzBlU52MHFbcye7v.JB5BQlmSS7u', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-20 11:28:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 0, NULL, NULL, 'Africa/Abidjan'),
(2, 'Waitrose', 'testvendor@gmail.com', NULL, '8942795234', NULL, NULL, '$2y$10$nUgoRoCHolTxgY9bFmxNS.DqWPhAYOFWJ5BAVK/7t67phB/6JaEoa', 1, 1, 226, 1, NULL, NULL, NULL, '2021-12-20 06:35:52', '2021-12-20 06:35:52', NULL, NULL, NULL, NULL, NULL, '509229', '2021-12-20 06:45:52', '904110', '2021-12-20 06:45:52', 0, 0, NULL, 0, 1, 'Dummy Vendor', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `house_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(15,12) DEFAULT NULL,
  `longitude` decimal(16,12) DEFAULT NULL,
  `pincode` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `phonecode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1 - home',
  `type_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `device_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_loyalty_points`
--

CREATE TABLE `user_loyalty_points` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `points` int DEFAULT '0',
  `loyalty_card_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_loyalty_point_histories`
--

CREATE TABLE `user_loyalty_point_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `points` int DEFAULT NULL,
  `earn_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'on_purchase, get_as_gift, add_wallet_money',
  `earn_type_id` bigint UNSIGNED DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `permission_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(2, 2, 2, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(3, 2, 3, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(4, 2, 12, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(5, 2, 17, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(6, 2, 18, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(7, 2, 19, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(8, 2, 20, '2021-12-20 06:35:52', '2021-12-20 06:35:52'),
(9, 2, 21, '2021-12-20 06:35:52', '2021-12-20 06:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_refferals`
--

CREATE TABLE `user_refferals` (
  `id` bigint UNSIGNED NOT NULL,
  `refferal_code` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reffered_by` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_saved_payment_methods`
--

CREATE TABLE `user_saved_payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `payment_option_id` int UNSIGNED DEFAULT NULL,
  `card_last_four_digit` int UNSIGNED DEFAULT NULL,
  `card_expiry_month` int UNSIGNED DEFAULT NULL,
  `card_expiry_year` int UNSIGNED DEFAULT NULL,
  `customerReference` text COLLATE utf8mb4_unicode_ci,
  `cardReference` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_vendors`
--

CREATE TABLE `user_vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_vendors`
--

INSERT INTO `user_vendors` (`id`, `user_id`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 2, 10, '2021-12-20 06:35:52', '2021-12-20 06:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_wishlists`
--

CREATE TABLE `user_wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `added_on` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1 for dropdown, 2 for color',
  `position` smallint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `title`, `type`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, 1, NULL, NULL),
(2, 'Color', 2, 2, 1, NULL, NULL),
(3, 'Phones', 1, 3, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variant_categories`
--

CREATE TABLE `variant_categories` (
  `variant_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variant_options`
--

CREATE TABLE `variant_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `hexacode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` smallint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variant_options`
--

INSERT INTO `variant_options` (`id`, `title`, `variant_id`, `hexacode`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Small', 1, '', 1, NULL, NULL),
(2, 'White', 2, '#ffffff', 1, NULL, NULL),
(3, 'Black', 2, '#000000', 1, NULL, NULL),
(4, 'Grey', 2, '#808080', 1, NULL, NULL),
(5, 'Medium', 1, '', 1, NULL, NULL),
(6, 'Large', 1, '', 1, NULL, NULL),
(7, 'IPhone', 3, '', 1, NULL, NULL),
(8, 'Samsung', 3, '', 1, NULL, NULL),
(9, 'Xiaomi', 3, '', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variant_option_translations`
--

CREATE TABLE `variant_option_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_option_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variant_option_translations`
--

INSERT INTO `variant_option_translations` (`id`, `title`, `variant_option_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small', 1, 1, NULL, '2021-10-06 09:46:51'),
(2, 'White', 2, 1, NULL, '2021-10-06 09:46:51'),
(3, 'Black', 3, 1, NULL, '2021-10-06 09:46:51'),
(4, 'Grey', 4, 1, NULL, '2021-10-06 09:46:51'),
(5, 'Medium', 5, 1, NULL, '2021-10-06 09:46:51'),
(6, 'Large', 6, 1, NULL, '2021-10-06 09:46:51'),
(7, 'IPhone', 7, 1, NULL, '2021-10-06 09:46:51'),
(8, 'Samsung', 8, 1, NULL, '2021-10-06 09:46:51'),
(9, 'Xiaomi', 9, 1, NULL, '2021-10-06 09:46:51');

-- --------------------------------------------------------

--
-- Table structure for table `variant_translations`
--

CREATE TABLE `variant_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variant_translations`
--

INSERT INTO `variant_translations` (`id`, `title`, `variant_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, NULL, '2021-10-06 09:46:51'),
(2, 'Color', 2, 1, NULL, '2021-10-06 09:46:51'),
(3, 'Phones', 3, 1, NULL, '2021-10-06 09:46:51');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(15,12) DEFAULT NULL,
  `longitude` decimal(16,12) DEFAULT NULL,
  `order_min_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_pre_time` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_reject_time` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_percent` decimal(10,2) DEFAULT '1.00',
  `commission_fixed_per_order` decimal(10,2) DEFAULT '0.00',
  `commission_monthly` decimal(10,2) DEFAULT '0.00',
  `dine_in` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `takeaway` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `delivery` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1-active, 0-pending, 2-blocked',
  `add_category` tinyint NOT NULL DEFAULT '1' COMMENT '0 for no, 1 for yes',
  `setting` tinyint NOT NULL DEFAULT '0' COMMENT '0 for no, 1 for yes',
  `is_show_vendor_details` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `show_slot` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no',
  `vendor_templete_id` bigint UNSIGNED DEFAULT NULL,
  `auto_accept_order` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `service_fee_percent` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `slug`, `desc`, `logo`, `banner`, `address`, `email`, `website`, `phone_no`, `latitude`, `longitude`, `order_min_amount`, `order_pre_time`, `auto_reject_time`, `commission_percent`, `commission_fixed_per_order`, `commission_monthly`, `dine_in`, `takeaway`, `delivery`, `status`, `add_category`, `setting`, `is_show_vendor_details`, `created_at`, `updated_at`, `show_slot`, `vendor_templete_id`, `auto_accept_order`, `service_fee_percent`) VALUES
(1, 'Flywheel', 'flywheel', NULL, 'vendor/kMTjc9TATs2qflKon3GZss5j4wANzfBKeSjqH7Nm.jpg', 'vendor/8c2XFWji25iTF4x8usv1NDqNt28FOBv6daYTWe4F.png', 'Sector 40, Chandigarh, India', 'flywheel@support.com', NULL, '9874563456', '30.739443900000', '76.737980800000', '0.00', '0', NULL, '10.00', '10.00', NULL, 0, 0, 1, 1, 1, 0, 0, NULL, '2021-10-11 10:54:51', 1, 2, 0, '0.00'),
(2, 'Spread Love', 'spread-love', NULL, 'vendor/d95ukJzHoOIQFtVnqs2uQGrcsfEClBHxeMvcw6WD.png', 'vendor/eJ9Uo3Yio8qt3ZD2HTikne5Rq442NDHr3eddYC0J.jpg', 'Sector 26, Chandigarh, India', 'spreadlove@support.com', NULL, '9867845635', '30.729958600000', '76.810103800000', '0.00', '0', NULL, '13.00', '13.00', NULL, 1, 1, 1, 1, 1, 0, 0, '2021-10-04 19:03:12', '2021-11-15 10:45:31', 1, 5, 0, '0.00'),
(3, 'The Red Cafe', 'the-red-cafe', NULL, 'vendor/9s02B4OdWyXTm2Hb9rXlUOTHJtfiUzVYfNiHXrXd.png', 'vendor/mhEgIxPquCrdqy2fBP0QvF4g1YlQRD0T63o60j9a.jpg', 'Sector 43, Chandigarh, India', 'theredcafe@support.com', NULL, '9865478467', '30.719058600000', '76.748704400000', '0.00', '0', NULL, '10.00', '10.00', NULL, 1, 1, 1, 1, 1, 0, 0, '2021-10-04 19:05:24', '2021-11-15 10:45:17', 1, 5, 0, '0.00'),
(4, 'Shipmate', 'shipmate', NULL, 'vendor/K8P4SSyKwo33pBlHnqDgrFTRHRDFyJOOKDHxNlbJ.png', 'vendor/hZHrXAPaJbL1APnGfdNvxXsrqfcKPejaML6vrAjr.jpg', 'Chandigarh, India', 'shipmate@support.com', NULL, '9823451123', '30.733314800000', '76.779417900000', '0.00', '0', NULL, '12.00', '12.00', NULL, 0, 0, 1, 1, 1, 0, 0, '2021-10-04 19:07:18', '2021-10-07 05:48:24', 1, 2, 0, '0.00'),
(5, 'West Zone', 'west-zone', NULL, 'vendor/FhiQAQ7avgPCv1osVsUIztld64HFclWgjAiIsODw.jpg', 'vendor/khKzjIp9ZByYYTEquG9SyLPOY29cli3yYQoXLZZu.jpg', 'Sector 22, Chandigarh, India', 'westzone@support.com', NULL, '9867543123', '30.732038500000', '76.772633400000', '0.00', '0', NULL, '12.00', '12.00', NULL, 0, 0, 1, 1, 1, 0, 0, '2021-10-04 19:09:13', '2021-11-15 10:45:05', 1, 5, 0, '0.00'),
(6, 'HealthMart', 'healthmart', NULL, 'vendor/4y22qOyXWIwSG2gKhWnpMSLzxFgN65cRbDmOl1a7.png', 'vendor/yGCLI2hGlbNql1R7zBsYmJXmqRnaZXAp2ocrpWNa.jpg', 'Sector 27, Chandigarh, India', 'healthmart@support.com', NULL, '8936453362', '30.723634600000', '76.797631700000', '0.00', '0', NULL, '15.00', '15.00', NULL, 0, 0, 1, 1, 1, 0, 0, '2021-10-04 19:14:22', '2021-10-11 11:55:30', 1, 5, 0, '0.00'),
(7, 'Plog Electronics', 'plog-electronics', NULL, 'vendor/kJ2PVrwXdJjHVuVN4dw9GADtw3sy4sBDHwmShRRM.jpg', 'vendor/KeMcZT3kUMlduxSrQXCS6jJzQUzy3bUbo19Lf76p.jpg', 'Sector 17, Chandigarh, India', 'plogelectronics@support.com', NULL, '9809545246', '30.741051700000', '76.779015000000', '0.00', '0', NULL, '14.00', '14.00', NULL, 0, 0, 1, 1, 1, 0, 0, '2021-10-04 19:17:32', '2021-11-15 10:44:42', 1, 5, 0, '0.00'),
(8, 'Dressify', 'dressify', NULL, 'vendor/w3evi2alGLaDHp1MvZ7Caiv7JAAFgZn7zKaVtxB6.jpg', 'vendor/eduHNF09phhpAIUoLnNCyQM5Ltw8gBL0yw8D8VZR.jpg', 'Sector 19, Chandigarh, India', 'retric@support.com', NULL, '9875936245', '30.729363100000', '76.791972800000', '0.00', '0', NULL, '12.00', '12.00', NULL, 0, 0, 1, 1, 1, 0, 0, '2021-10-04 19:21:24', '2021-11-15 10:44:29', 1, 5, 0, '0.00'),
(9, 'Cinch', 'cinch', NULL, 'vendor/yhA5rN6E0vdXYNwpCNiAZNXMxiQUROGUeJGUDHss.jpg', 'vendor/a1LFdIUqtGOTD6IeFVTw0OJ9phrTgRBFtbufMTUP.png', 'Sector 22, Chandigarh, India', 'cinch@support.com', NULL, '9468393425', '30.732038500000', '76.772633400000', '0.00', '0', NULL, '14.00', '14.00', NULL, 0, 0, 1, 1, 1, 0, 0, '2021-10-04 20:48:03', '2021-10-11 10:52:45', 1, 2, 0, '0.00'),
(10, 'Waitrose', 'waitrose', NULL, 'vendor/Kv515vLk88icj7j2vynH7NDJPtT20wt7Qyq3F3Z7.jpg', 'default/default_image.png', 'Canada', 'testvendor@gmail.com', NULL, NULL, '56.130366000000', '-106.346771000000', '0.00', NULL, NULL, '1.00', '0.00', '0.00', 0, 0, 1, 0, 1, 0, 0, '2021-12-20 06:35:52', '2021-12-20 06:35:52', 1, NULL, 0, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_categories`
--

CREATE TABLE `vendor_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `vendor_id`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 29, 1, '2021-10-04 19:21:55', '2021-10-04 19:21:55'),
(2, 1, 30, 1, '2021-10-04 19:21:56', '2021-10-04 19:21:56'),
(3, 1, 31, 1, '2021-10-04 19:21:57', '2021-10-04 19:21:57'),
(4, 4, 2, 1, '2021-10-04 19:29:04', '2021-10-04 19:29:04'),
(5, 2, 3, 1, '2021-10-04 19:38:45', '2021-10-04 19:38:45'),
(6, 2, 14, 1, '2021-10-04 19:38:45', '2021-10-04 19:38:45'),
(7, 2, 16, 1, '2021-10-04 19:38:46', '2021-10-04 19:38:46'),
(8, 3, 3, 1, '2021-10-04 19:51:26', '2021-10-04 19:51:26'),
(9, 3, 15, 1, '2021-10-04 19:51:27', '2021-10-04 19:51:27'),
(10, 5, 4, 1, '2021-10-04 19:57:12', '2021-10-04 19:57:12'),
(11, 5, 9, 1, '2021-10-04 19:57:13', '2021-10-04 19:57:13'),
(12, 5, 8, 1, '2021-10-04 19:57:14', '2021-10-04 19:57:14'),
(13, 5, 10, 1, '2021-10-04 19:57:15', '2021-10-04 19:57:15'),
(14, 6, 5, 1, '2021-10-04 20:05:53', '2021-10-04 20:05:53'),
(15, 6, 17, 1, '2021-10-04 20:05:53', '2021-10-04 20:05:53'),
(16, 6, 18, 1, '2021-10-04 20:05:55', '2021-10-04 20:05:55'),
(17, 7, 19, 1, '2021-10-04 20:16:46', '2021-10-04 20:16:46'),
(18, 7, 20, 1, '2021-10-04 20:16:47', '2021-10-04 20:16:47'),
(19, 7, 21, 1, '2021-10-04 20:16:48', '2021-10-04 20:16:48'),
(20, 8, 26, 1, '2021-10-04 20:31:19', '2021-10-04 20:31:19'),
(21, 8, 27, 1, '2021-10-04 20:31:20', '2021-10-04 20:31:20'),
(22, 8, 28, 1, '2021-10-04 20:31:21', '2021-10-04 20:31:21'),
(23, 9, 22, 1, '2021-10-04 20:48:30', '2021-10-04 20:48:30'),
(24, 9, 23, 1, '2021-10-04 20:48:32', '2021-10-04 20:48:32'),
(25, 9, 24, 1, '2021-10-04 20:48:33', '2021-10-04 20:48:33'),
(26, 9, 25, 1, '2021-10-04 20:48:35', '2021-10-04 20:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_connected_accounts`
--

CREATE TABLE `vendor_connected_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_option_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0-inactive, 1-active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_dinein_categories`
--

CREATE TABLE `vendor_dinein_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_dinein_category_translations`
--

CREATE TABLE `vendor_dinein_category_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_dinein_tables`
--

CREATE TABLE `vendor_dinein_tables` (
  `id` bigint UNSIGNED NOT NULL,
  `table_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `seating_number` int DEFAULT NULL,
  `vendor_dinein_category_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0-active, 1-inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_dinein_table_translations`
--

CREATE TABLE `vendor_dinein_table_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_dinein_table_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_docs`
--

CREATE TABLE `vendor_docs` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `vendor_registration_document_id` bigint UNSIGNED NOT NULL,
  `file_name` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_media`
--

CREATE TABLE `vendor_media` (
  `id` bigint UNSIGNED NOT NULL,
  `media_type` tinyint NOT NULL DEFAULT '1' COMMENT '1 - image, 2 - video, 3 - file',
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_media`
--

INSERT INTO `vendor_media` (`id`, `media_type`, `vendor_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'prods/0f2SsduEoi4TwUYVCQDymSTazMlvEH3JRLDxf5tk.jpg', '2021-10-04 19:24:05', '2021-10-04 19:24:05'),
(2, 1, 1, 'prods/7EpEtpmg8p3ELvgrA102IfO61P4HntsPF9Scsn4p.jpg', '2021-10-04 19:25:29', '2021-10-04 19:25:29'),
(3, 1, 1, 'prods/qxREUVET35jyDXi3ja32g3bYJEm3iOkryTbiDsKc.jpg', '2021-10-04 19:26:50', '2021-10-04 19:26:50'),
(4, 1, 1, 'prods/bycySSo01DIRyWPx3q4NqcYBCRk76jWs4C5TUu3r.jpg', '2021-10-04 19:27:34', '2021-10-04 19:27:34'),
(5, 1, 1, 'prods/gYjYtNaQgtNn2Xl3fLemBsEXIk8PfyqzqwsqYVWa.jpg', '2021-10-04 19:28:14', '2021-10-04 19:28:14'),
(6, 1, 4, 'prods/CqEwe4PSlQEptRhK1ObO4TzTvkkPkJaAo8qEsd2o.jpg', '2021-10-04 19:30:59', '2021-10-04 19:30:59'),
(7, 1, 4, 'prods/zdOb1N0wTvNovRriCtfYHmUq22oa8ueUAnW7RQ3V.jpg', '2021-10-04 19:31:54', '2021-10-04 19:31:54'),
(8, 1, 4, 'prods/ycXeXh7vR5Ex8qnqvRMmcWsI77WY1TadZauqpf8R.jpg', '2021-10-04 19:32:45', '2021-10-04 19:32:45'),
(10, 1, 2, 'https://i.postimg.cc/tJdS5HKP/pizza-napoletana-1-1.jpg', '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(13, 1, 2, 'https://i.postimg.cc/9FWJhs38/Margherita-Pizza-018.jpg', '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(14, 1, 2, 'https://i.postimg.cc/2jx72Tq4/vegan-mushroom-risotto-close-1000x1500.jpg', '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(16, 1, 2, 'https://i.postimg.cc/dVcdFyTq/55369113.jpg', '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(17, 1, 2, 'https://i.postimg.cc/Bv627GKD/layered-panzanella-salad-recipe-3.jpg', '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(18, 1, 2, 'https://i.postimg.cc/KYw393FS/IMG-9109-2.jpg', '2021-10-04 19:39:10', '2021-10-04 19:39:10'),
(19, 1, 2, 'prods/FHjKPoTVguQIqj1w3923pvuGWNm4hhHLL2Zs0u2R.jpg', '2021-10-04 19:41:42', '2021-10-04 19:41:42'),
(20, 1, 2, 'prods/cJ7sJAhMfZVB3aRqHU00bo9KLNV9hsM9u4Qi30M6.jpg', '2021-10-04 19:42:48', '2021-10-04 19:42:48'),
(21, 1, 2, 'prods/tk5cDdxj33aGrpzq2N0gEIJLwpsl7qhoCoJkkhj4.jpg', '2021-10-04 19:43:44', '2021-10-04 19:43:44'),
(22, 1, 2, 'prods/wMo6xlHqaGTsROGB5LKP9bnOsOV9eoFwWf4Of60S.jpg', '2021-10-04 19:44:34', '2021-10-04 19:44:34'),
(27, 1, 2, 'https://i.postimg.cc/kMRMFMT8/hot-and-sour-soup-1.jpg', '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(28, 1, 2, 'https://i.postimg.cc/zfP5K4W0/sichuan-pork-105860-1.jpg', '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(29, 1, 2, 'https://i.postimg.cc/dtnfhrG9/20160905-TDP0382.jpg', '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(30, 1, 2, 'https://i.postimg.cc/pTZfsYWG/Peking-Duck-7206-450-450x375.jpg', '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(31, 1, 2, 'https://i.postimg.cc/4NKccT9F/8d8be8b0ee63cf8ab7f58a867e9d9f9c.jpg', '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(32, 1, 2, 'https://i.postimg.cc/vTxm4bNr/c263b384881937647b0442bbf20a616a.jpg', '2021-10-04 19:45:11', '2021-10-04 19:45:11'),
(33, 1, 2, 'prods/78WX98VB4H18bknWYwr7NADRlxm7hxYuzpxEPdWg.jpg', '2021-10-04 19:46:53', '2021-10-04 19:46:53'),
(34, 1, 2, 'prods/V1PiyKpoJa7C29ZtzMyTThv1YPLNsl8fSK2zn43O.jpg', '2021-10-04 19:47:49', '2021-10-04 19:47:49'),
(35, 1, 2, 'prods/kHlB44k8onT0trHrGNfk1fZ9vkDl3peNHoPAlYTQ.jpg', '2021-10-04 19:48:50', '2021-10-04 19:48:50'),
(36, 1, 2, 'prods/rZu1L8nkTDMfOytmSkeoDymyZVPnyi6eMpMevwxI.jpg', '2021-10-04 19:49:43', '2021-10-04 19:49:43'),
(37, 1, 3, 'prods/KOI3UpUyvfRHVLadmZIHGzbMt8pUFu0V5eRZATqD.jpg', '2021-10-04 19:53:29', '2021-10-04 19:53:29'),
(38, 1, 3, 'prods/koAJQxGZ3teJeglHSsdDZqnaXwlgnJZcP8UXZfFP.jpg', '2021-10-04 19:54:10', '2021-10-04 19:54:10'),
(39, 1, 3, 'prods/V5wZ12IsYPnM7ZbqJB5WlToYJBEmxfi7oZxBNuN2.jpg', '2021-10-04 19:56:09', '2021-10-04 19:56:09'),
(42, 1, 5, 'prods/9Oro83aQ6GRxHUdumrE2g6rYDRqVoJMbau8r1twv.jpg', '2021-10-04 19:59:48', '2021-10-04 19:59:48'),
(43, 1, 5, 'prods/YK5ybuiGgaOOgMXl7UllxTvugADaqy4J3WX0U1eK.jpg', '2021-10-04 20:00:08', '2021-10-04 20:00:08'),
(44, 1, 5, 'prods/uCnXPTjPOUWnlnJznetRdPuFBt3zrXZRAnLGJ4KV.jpg', '2021-10-04 20:00:27', '2021-10-04 20:00:27'),
(45, 1, 5, 'prods/huMCO4SpP2zaUB89tT10Rw2HkyLXdHhAxKOW3rna.jpg', '2021-10-04 20:00:47', '2021-10-04 20:00:47'),
(46, 1, 5, 'prods/G2hrs5D4zn1Y1saWyctCppdOyJ2VGyuhgnovxX6S.jpg', '2021-10-04 20:01:05', '2021-10-04 20:01:05'),
(47, 1, 5, 'prods/xnwL8B1gBZy3YrBFgTHJrO9bFpsBHN9ul1NV3j5X.jpg', '2021-10-04 20:01:22', '2021-10-04 20:01:22'),
(48, 1, 5, 'prods/vW8HwonJ9dCra4W4KG4ekaxqUZNHBDALOMaIbFmh.jpg', '2021-10-04 20:02:38', '2021-10-04 20:02:38'),
(49, 1, 5, 'prods/2y1Z2c1fM53aSUME2quT1Ysj3GcrDqaRtGZk0fma.jpg', '2021-10-04 20:03:27', '2021-10-04 20:03:27'),
(50, 1, 5, 'prods/ZpDfeyO3fzQmG8ZOiyeOez8X1AJJf852rJhUfZal.jpg', '2021-10-04 20:04:17', '2021-10-04 20:04:17'),
(56, 1, 7, 'prods/Veo0KtjlIHBmJ9EpNswy6v8ETwmSGhmJ5WSTO1zs.jpg', '2021-10-04 20:20:51', '2021-10-04 20:20:51'),
(57, 1, 7, 'prods/KUlWTWuLH7A94gTou8ROZoETgbMrGSJAODkcScE5.jpg', '2021-10-04 20:21:27', '2021-10-04 20:21:27'),
(58, 1, 7, 'prods/57JqcxZHyM87sORTvBWNZYpovzSouIP3jWZg0kq0.jpg', '2021-10-04 20:23:01', '2021-10-04 20:23:01'),
(59, 1, 7, 'prods/Rwj8MjPmo77lRF89I2OcEuGV2wSCWk7eqO9F9Rvu.jpg', '2021-10-04 20:25:15', '2021-10-04 20:25:15'),
(60, 1, 7, 'prods/CxjDFV2OTyGKT3R05yCotDJjsRoAlERa31S9K5yH.jpg', '2021-10-04 20:27:26', '2021-10-04 20:27:26'),
(61, 1, 7, 'prods/G62PMVeLsatBVYc7ZhqeibE6OPTAI3Nbzl9jeXH3.jpg', '2021-10-04 20:29:36', '2021-10-04 20:29:36'),
(62, 1, 9, 'prods/nD0GARCrh5jMrOybasw7zYAbNFqXJeg5RJjuXgRM.jpg', '2021-10-04 20:51:22', '2021-10-04 20:51:22'),
(63, 1, 9, 'prods/hOFxGasprLLNn8zZrq4gkzARSRq0CuCtqEXlrniJ.jpg', '2021-10-04 20:52:19', '2021-10-04 20:52:19'),
(64, 1, 9, 'prods/yc1J3ea2uNQne8rKTc6t0c2ruD2slNhYaJXIUhkB.jpg', '2021-10-04 20:52:52', '2021-10-04 20:52:52'),
(65, 1, 9, 'prods/mi6KXGb2dSkiaRIiFKYj6hDJDiiSsvujJbkWAcET.jpg', '2021-10-04 20:55:21', '2021-10-04 20:55:21'),
(66, 1, 9, 'prods/mnE00o38TnIcOeNvRMYbuVBerr5C5rwOEyTrIAQq.jpg', '2021-10-04 20:55:50', '2021-10-04 20:55:50'),
(67, 1, 9, 'prods/2mVFAh7S5YBjlQVLfpnHQ2v0LuejDGDDeacn1w9P.jpg', '2021-10-04 20:56:33', '2021-10-04 20:56:33'),
(68, 1, 9, 'prods/QOM6i59AXgoTkkOFhMGmXVWSCv7kdk5fstQGr7rg.jpg', '2021-10-04 20:56:56', '2021-10-04 20:56:56'),
(69, 1, 9, 'prods/9QzfyLDk4sD9rcnpHkyczEwXLEMhQuVa1bIMWqTC.jpg', '2021-10-04 20:57:24', '2021-10-04 20:57:24'),
(70, 1, 8, 'prods/tUKa24xRBXijC6NpS1nAhDTTP6Xcx1USXLa1iOic.jpg', '2021-10-05 04:02:54', '2021-10-05 04:02:54'),
(71, 1, 8, 'prods/dPpZpnnzEskrQY37TgNcwRWSHPKsnGkK7xu1OI2j.jpg', '2021-10-05 04:06:05', '2021-10-05 04:06:05'),
(72, 1, 8, 'prods/BZh2XAon8wjnq6OjDFzSYskhlSyU5GgFNRf3CpQb.jpg', '2021-10-05 04:09:39', '2021-10-05 04:09:39'),
(73, 1, 8, 'prods/IFflxqkyykPvN2rpM5lH3YqG7YglI5ioqxyHiYZZ.jpg', '2021-10-05 04:11:48', '2021-10-05 04:11:48'),
(74, 1, 8, 'prods/RTW9mhnllYpoBLLU8CGBvpJSWAlqLbYIoDpbHiY9.jpg', '2021-10-05 04:17:28', '2021-10-05 04:17:28'),
(75, 1, 8, 'prods/kA6NjDO0TOOK6LHRaFGsYvvngIE5hMVFpBTW6GWi.jpg', '2021-10-05 04:19:35', '2021-10-05 04:19:35'),
(77, 1, 6, 'prods/vg0Y0uZmZKG2YWTwitQCgOSkFuDQ8jcTZuzSa0CU.jpg', '2021-10-05 04:26:29', '2021-10-05 04:26:29'),
(79, 1, 6, 'prods/FVWKTlGr3yLCjodAbEmn8NpnV5xhOqfD5DMQsoJd.jpg', '2021-10-05 04:30:43', '2021-10-05 04:30:43'),
(80, 1, 6, 'prods/dslZcB2Dsxd0I2ePJ6SjWiLrj0ldyvjEAwlvLM80.jpg', '2021-10-05 04:32:17', '2021-10-05 04:32:17'),
(81, 1, 6, 'prods/XIONBl2agAeh6M87Ea0ZTSRUQbk954uWNnyQA5nF.jpg', '2021-10-05 04:35:04', '2021-10-05 04:35:04'),
(82, 1, 6, 'prods/H2DfvQ9PZxVETbeM3GBPIeXRw2bYDH6GonUomQki.jpg', '2021-10-05 04:39:47', '2021-10-05 04:39:47'),
(83, 1, 6, 'prods/UOFFE3QahMkyGYOqs4Y9ZJ1tHgWYXGCrHlIf7UyC.jpg', '2021-10-05 04:41:08', '2021-10-05 04:41:08'),
(84, 1, 8, 'prods/ugpQSzf2cpbuCJ6OLqJXbRFOeIffgPacqw2oalH9.jpg', '2021-10-11 05:43:29', '2021-10-11 05:43:29'),
(85, 1, 8, 'prods/5LQTjQW7AqICSlXi779tOzn3afVC1Vmud7VkMAcC.jpg', '2021-10-11 05:47:28', '2021-10-11 05:47:28'),
(86, 1, 8, 'prods/OEIIrWxC7Lx1rAspI5TcF4HTARUfJm4YZr5JCQg7.jpg', '2021-10-11 05:53:06', '2021-10-11 05:53:06'),
(87, 1, 8, 'prods/4MpPyPeykMc9hk36lL5d7rqAZAgJD5GivzSLTfYK.jpg', '2021-10-11 06:14:44', '2021-10-11 06:14:44'),
(88, 1, 8, 'prods/TrozrhN16F03P9wP1CclSwLYW0MiMyYGYQjrpUg9.jpg', '2021-10-11 06:18:19', '2021-10-11 06:18:19'),
(89, 1, 8, 'prods/Tb2LJ8iogoBXXd7H8sNTk1PaXeRcteX24J5cFlJP.jpg', '2021-10-11 06:20:57', '2021-10-11 06:20:57'),
(93, 1, 7, 'prods/9O6do3A5oiFS1PQKBbPxivL1jSCszCvOvKzfyLvw.jpg', '2021-10-11 06:44:36', '2021-10-11 06:44:36'),
(94, 1, 7, 'prods/ik4fDQG3JxZokFQRWKg33HrAIE9jeAs1eaHzLn3g.jpg', '2021-10-11 06:57:50', '2021-10-11 06:57:50'),
(95, 1, 7, 'prods/qRBNab8l8OETMlFctXpre3orlHvFEJmU4pojahQK.jpg', '2021-10-11 07:02:57', '2021-10-11 07:02:57'),
(96, 1, 6, 'prods/kJX2JpVZBacmSzM9HoC2L3Ca45AqpVsfCrXKdbG1.jpg', '2021-10-11 07:08:15', '2021-10-11 07:08:15'),
(97, 1, 6, 'prods/omtRhU9JtGfY9SfF3qF1tujvORqgcp4kuu7ilmWm.jpg', '2021-10-11 07:11:50', '2021-10-11 07:11:50'),
(98, 1, 6, 'prods/zsB27RP9ya73O6bmCivEMZEoZK0WwonK8rWVI44i.jpg', '2021-10-11 07:16:21', '2021-10-11 07:16:21'),
(99, 1, 6, 'prods/TLRonqV2PxKJHQkJZSewUAwfkLPUw1dN9svc1BpF.jpg', '2021-10-11 07:21:23', '2021-10-11 07:21:23'),
(100, 1, 6, 'prods/aHG2T4yzTV6uM3TJ0uXYOKGkAB8mfPKfeqEzBKVs.png', '2021-10-11 07:26:24', '2021-10-11 07:26:24'),
(101, 1, 5, 'prods/1ix1Ru5KP9lXOf7nSbaqTiNmD2imcDUgyVOk0GDM.jpg', '2021-10-11 07:31:24', '2021-10-11 07:31:24'),
(102, 1, 5, 'prods/5rstRPcdVveNe7KOBKwLooBN0ufAq3DNvfD5z3Os.jpg', '2021-10-11 07:36:31', '2021-10-11 07:36:31'),
(103, 1, 5, 'prods/u2hLqKOjhwWMWi3fc8UmndP1hApwNdr1Lu7VjtiT.jpg', '2021-10-11 07:41:22', '2021-10-11 07:41:22'),
(104, 1, 5, 'prods/hYO66PHqwL1apGnS1D4eMcyoSB2LkmfHMTGjhnyU.jpg', '2021-10-11 07:43:19', '2021-10-11 07:43:19'),
(105, 1, 5, 'prods/5KgGeGT3XpcqBe145CpWPCJszllTYxTpBrZp2jdn.jpg', '2021-10-11 07:49:24', '2021-10-11 07:49:24'),
(106, 1, 5, 'prods/GGqzR97OSaLRy8lFa4yKwruYWIwTIfWT1qgaxNWF.jpg', '2021-10-11 07:52:40', '2021-10-11 07:52:40'),
(107, 1, 3, 'prods/mCQ3omLTIJQ5Q2kwfPYLhqSMkW6wuYDqPWYwzjFb.jpg', '2021-10-11 08:43:44', '2021-10-11 08:43:44'),
(108, 1, 3, 'prods/MOxmeajOKrWMICd5M06jw3jYewWUY6oStAG0OaPu.jpg', '2021-10-11 08:47:10', '2021-10-11 08:47:10'),
(109, 1, 3, 'prods/Xe8rEp6gtAROCovOtONOcqUH6TKhE6lQ6uLWToDu.jpg', '2021-10-11 08:53:26', '2021-10-11 08:53:26'),
(110, 1, 2, 'prods/oq2YB7XwrfarWv6Lv7lmMWPeUMwaoRXv98Kp54cC.jpg', '2021-10-11 09:00:29', '2021-10-11 09:00:29'),
(111, 1, 2, 'prods/IRJb6Rv9ZDlAZmsORxTwCy5jU8KK7jzuMNjzfbRO.jpg', '2021-10-11 09:04:22', '2021-10-11 09:04:22'),
(112, 1, 2, 'prods/eQKXCOxauzclzWGJIDGYh9qUxl0MZQwfC6kNsIZ5.jpg', '2021-10-11 09:08:08', '2021-10-11 09:08:08'),
(113, 1, 2, 'prods/c4l199c7SmvGFh9pv7WL6ZoCuiVFiY6ivWM4WD3h.jpg', '2021-10-11 09:14:06', '2021-10-11 09:14:06'),
(114, 1, 7, 'prods/gN0JKzY1QIvX8VsHEDGxx0VeGKeHCJSBBVuDdq5V.jpg', '2021-10-11 10:59:39', '2021-10-11 10:59:39'),
(115, 1, 7, 'prods/DvVWcVxd0iND2OT3gGm27HgKikThZTtG1cYafJ34.jpg', '2021-10-11 10:59:41', '2021-10-11 10:59:41'),
(117, 1, 7, 'prods/saWoLQeQ8j3rQ6nEa1oQ2YNnlRb26dWITjfAmv9s.jpg', '2021-10-11 11:00:59', '2021-10-11 11:00:59'),
(118, 1, 7, 'prods/6XBwJPAHo3sEIQGXNDAmmXZyKzKZQZH1lmAEDUvp.jpg', '2021-10-11 11:11:03', '2021-10-11 11:11:03'),
(119, 1, 7, 'prods/3ZCZ6tOMxviMx7sU4vBmG2NFzwAS2ZzRhTS6ptMm.jpg', '2021-10-11 11:11:06', '2021-10-11 11:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_order_dispatcher_statuses`
--

CREATE TABLE `vendor_order_dispatcher_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `dispatcher_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `dispatcher_status_option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_order_statuses`
--

CREATE TABLE `vendor_order_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `order_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `order_status_option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payouts`
--

CREATE TABLE `vendor_payouts` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `payout_option_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency` bigint UNSIGNED DEFAULT NULL,
  `requested_by` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0-pending, 1-paid, 2-failed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_registration_documents`
--

CREATE TABLE `vendor_registration_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_required` int NOT NULL DEFAULT '1' COMMENT '0 means not required, 1 means required',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_registration_document_translations`
--

CREATE TABLE `vendor_registration_document_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `vendor_registration_document_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_saved_payment_methods`
--

CREATE TABLE `vendor_saved_payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `payment_option_id` int UNSIGNED DEFAULT NULL,
  `card_last_four_digit` int UNSIGNED DEFAULT NULL,
  `card_expiry_month` int UNSIGNED DEFAULT NULL,
  `card_expiry_year` int UNSIGNED DEFAULT NULL,
  `customerReference` text COLLATE utf8mb4_unicode_ci,
  `cardReference` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_slots`
--

CREATE TABLE `vendor_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `geo_id` bigint UNSIGNED DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `dine_in` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `takeaway` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `delivery` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_slot_dates`
--

CREATE TABLE `vendor_slot_dates` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `specific_date` date NOT NULL,
  `working_today` tinyint NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
  `dine_in` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `takeaway` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `delivery` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_templetes`
--

CREATE TABLE `vendor_templetes` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 - active, 0 - inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_templetes`
--

INSERT INTO `vendor_templetes` (`id`, `title`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Only Product', 'Grid', 1, NULL, NULL),
(2, 'Only Category', 'Grid', 1, NULL, NULL),
(3, 'Only Product', 'List', 0, NULL, NULL),
(4, 'Only Category', 'List', 0, NULL, NULL),
(5, 'Product with Category', 'Grid', 1, NULL, NULL),
(6, 'Product with Category', 'List', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_users`
--

CREATE TABLE `vendor_users` (
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holder_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` json DEFAULT NULL,
  `balance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holder_id` bigint UNSIGNED NOT NULL,
  `decimal_places` tinyint NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `name`, `slug`, `holder_type`, `meta`, `balance`, `description`, `holder_id`, `decimal_places`, `created_at`, `updated_at`) VALUES
(1, 'Default Wallet', 'default', 'App\\Models\\User', '[]', '0.00', NULL, 2, 2, '2021-12-20 12:19:32', '2021-12-20 12:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `web_stylings`
--

CREATE TABLE `web_stylings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint DEFAULT NULL COMMENT '1-Text, 2-Option, 3-Option Images, 4-Color',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `web_stylings`
--

INSERT INTO `web_stylings` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Home Page Style', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `web_styling_options`
--

CREATE TABLE `web_styling_options` (
  `id` bigint UNSIGNED NOT NULL,
  `web_styling_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_selected` tinyint NOT NULL DEFAULT '1' COMMENT '1-yes, 2-no',
  `template_id` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `web_styling_options`
--

INSERT INTO `web_styling_options` (`id`, `web_styling_id`, `name`, `image`, `is_selected`, `template_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Home Page 1', 'template-one.png', 1, 1, NULL, NULL),
(2, 1, 'Home Page 2', 'template-two.png', 0, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `woocommerces`
--

CREATE TABLE `woocommerces` (
  `id` bigint UNSIGNED NOT NULL,
  `url` mediumtext COLLATE utf8mb4_unicode_ci,
  `consumer_key` mediumtext COLLATE utf8mb4_unicode_ci,
  `consumer_secret` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addon_options`
--
ALTER TABLE `addon_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_options_addon_id_foreign` (`addon_id`);

--
-- Indexes for table `addon_option_translations`
--
ALTER TABLE `addon_option_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_option_translations_addon_opt_id_foreign` (`addon_opt_id`),
  ADD KEY `addon_option_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `addon_sets`
--
ALTER TABLE `addon_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_sets_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `addon_set_translations`
--
ALTER TABLE `addon_set_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_set_translations_addon_id_foreign` (`addon_id`),
  ADD KEY `addon_set_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_dynamic_tutorials`
--
ALTER TABLE `app_dynamic_tutorials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_stylings`
--
ALTER TABLE `app_stylings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_styling_options`
--
ALTER TABLE `app_styling_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_styling_options_app_styling_id_foreign` (`app_styling_id`);

--
-- Indexes for table `auto_reject_orders_cron`
--
ALTER TABLE `auto_reject_orders_cron`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_redirect_category_id_foreign` (`redirect_category_id`),
  ADD KEY `banners_redirect_vendor_id_foreign` (`redirect_vendor_id`),
  ADD KEY `banners_name_index` (`name`),
  ADD KEY `banners_status_index` (`status`),
  ADD KEY `banners_start_date_time_index` (`start_date_time`),
  ADD KEY `banners_end_date_time_index` (`end_date_time`);

--
-- Indexes for table `blocked_tokens`
--
ALTER TABLE `blocked_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_position_index` (`position`),
  ADD KEY `brands_status_index` (`status`);

--
-- Indexes for table `brand_categories`
--
ALTER TABLE `brand_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_categories_brand_id_foreign` (`brand_id`),
  ADD KEY `brand_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `brand_translations`
--
ALTER TABLE `brand_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_translations_brand_id_foreign` (`brand_id`),
  ADD KEY `brand_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `business_types`
--
ALTER TABLE `business_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cab_booking_layouts`
--
ALTER TABLE `cab_booking_layouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cab_booking_layout_categories`
--
ALTER TABLE `cab_booking_layout_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cab_booking_layout_categories_cab_booking_layout_id_foreign` (`cab_booking_layout_id`),
  ADD KEY `cab_booking_layout_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `cab_booking_layout_transaltions`
--
ALTER TABLE `cab_booking_layout_transaltions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cab_booking_layout_transaltions_cab_booking_layout_id_foreign` (`cab_booking_layout_id`),
  ADD KEY `cab_booking_layout_transaltions_language_id_foreign` (`language_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_created_by_foreign` (`created_by`),
  ADD KEY `carts_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `cart_addons`
--
ALTER TABLE `cart_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_addons_cart_product_id_foreign` (`cart_product_id`),
  ADD KEY `cart_addons_addon_id_foreign` (`addon_id`),
  ADD KEY `cart_addons_option_id_foreign` (`option_id`);

--
-- Indexes for table `cart_coupons`
--
ALTER TABLE `cart_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_coupons_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_coupons_coupon_id_foreign` (`coupon_id`);

--
-- Indexes for table `cart_products`
--
ALTER TABLE `cart_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_products_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_products_product_id_foreign` (`product_id`),
  ADD KEY `cart_products_vendor_id_foreign` (`vendor_id`),
  ADD KEY `cart_products_created_by_foreign` (`created_by`),
  ADD KEY `cart_products_variant_id_foreign` (`variant_id`),
  ADD KEY `cart_products_tax_rate_id_foreign` (`tax_rate_id`),
  ADD KEY `cart_products_status_index` (`status`),
  ADD KEY `cart_products_is_tax_applied_index` (`is_tax_applied`),
  ADD KEY `cart_products_tax_category_id_foreign` (`tax_category_id`);

--
-- Indexes for table `cart_product_prescriptions`
--
ALTER TABLE `cart_product_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_product_prescriptions_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_product_prescriptions_product_id_foreign` (`product_id`),
  ADD KEY `cart_product_prescriptions_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_client_code_foreign` (`client_code`),
  ADD KEY `categories_vendor_id_foreign` (`vendor_id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`),
  ADD KEY `categories_type_id_foreign` (`type_id`),
  ADD KEY `categories_status_index` (`status`),
  ADD KEY `categories_is_core_index` (`is_core`),
  ADD KEY `categories_position_index` (`position`),
  ADD KEY `categories_can_add_products_index` (`can_add_products`),
  ADD KEY `categories_display_mode_index` (`display_mode`);

--
-- Indexes for table `category_histories`
--
ALTER TABLE `category_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_histories_category_id_foreign` (`category_id`);

--
-- Indexes for table `category_tags`
--
ALTER TABLE `category_tags`
  ADD KEY `category_tags_category_id_foreign` (`category_id`);

--
-- Indexes for table `category_translations`
--
ALTER TABLE `category_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_translations_name_index` (`name`),
  ADD KEY `category_translations_category_id_foreign` (`category_id`),
  ADD KEY `category_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `celebrities`
--
ALTER TABLE `celebrities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `celebrities_email_unique` (`email`),
  ADD KEY `celebrities_country_id_foreign` (`country_id`);

--
-- Indexes for table `celebrity_brands`
--
ALTER TABLE `celebrity_brands`
  ADD KEY `celebrity_brands_celebrity_id_foreign` (`celebrity_id`),
  ADD KEY `celebrity_brands_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD UNIQUE KEY `clients_code_unique` (`code`),
  ADD KEY `clients_country_id_foreign` (`country_id`),
  ADD KEY `clients_language_id_foreign` (`language_id`),
  ADD KEY `clients_phone_number_index` (`phone_number`),
  ADD KEY `clients_custom_domain_index` (`custom_domain`),
  ADD KEY `clients_is_deleted_index` (`is_deleted`),
  ADD KEY `clients_is_blocked_index` (`is_blocked`),
  ADD KEY `clients_database_name_index` (`database_name`),
  ADD KEY `clients_company_name_index` (`company_name`),
  ADD KEY `clients_status_index` (`status`);

--
-- Indexes for table `client_currencies`
--
ALTER TABLE `client_currencies`
  ADD KEY `client_currencies_client_code_foreign` (`client_code`),
  ADD KEY `client_currencies_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `client_languages`
--
ALTER TABLE `client_languages`
  ADD KEY `client_languages_client_code_foreign` (`client_code`),
  ADD KEY `client_languages_language_id_foreign` (`language_id`);

--
-- Indexes for table `client_preferences`
--
ALTER TABLE `client_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_preferences_client_code_unique` (`client_code`),
  ADD KEY `client_preferences_fb_login_index` (`fb_login`),
  ADD KEY `client_preferences_twitter_login_index` (`twitter_login`),
  ADD KEY `client_preferences_google_login_index` (`google_login`),
  ADD KEY `client_preferences_apple_login_index` (`apple_login`),
  ADD KEY `client_preferences_verify_email_index` (`verify_email`),
  ADD KEY `client_preferences_verify_phone_index` (`verify_phone`),
  ADD KEY `client_preferences_currency_id_foreign` (`currency_id`),
  ADD KEY `client_preferences_language_id_foreign` (`language_id`),
  ADD KEY `client_preferences_map_provider_foreign` (`map_provider`),
  ADD KEY `client_preferences_sms_provider_foreign` (`sms_provider`),
  ADD KEY `client_preferences_web_template_id_foreign` (`web_template_id`),
  ADD KEY `client_preferences_app_template_id_foreign` (`app_template_id`),
  ADD KEY `client_preferences_is_hyperlocal_index` (`is_hyperlocal`),
  ADD KEY `client_preferences_need_delivery_service_index` (`need_delivery_service`),
  ADD KEY `client_preferences_mail_type_index` (`mail_type`),
  ADD KEY `client_preferences_celebrity_check_index` (`celebrity_check`),
  ADD KEY `client_preferences_need_laundry_service_index` (`need_laundry_service`);

--
-- Indexes for table `cms`
--
ALTER TABLE `cms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_title_unique` (`title`),
  ADD KEY `cms_language_id_foreign` (`language_id`),
  ADD KEY `cms_title_index` (`title`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countries_code_index` (`code`),
  ADD KEY `countries_name_index` (`name`);

--
-- Indexes for table `csv_product_imports`
--
ALTER TABLE `csv_product_imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `csv_product_imports_vendor_id_foreign` (`vendor_id`),
  ADD KEY `csv_product_imports_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `csv_vendor_imports`
--
ALTER TABLE `csv_vendor_imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `csv_vendor_imports_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currencies_name_index` (`name`),
  ADD KEY `currencies_priority_index` (`priority`),
  ADD KEY `currencies_iso_code_index` (`iso_code`),
  ADD KEY `currencies_iso_numeric_index` (`iso_numeric`);

--
-- Indexes for table `dispatcher_status_options`
--
ALTER TABLE `dispatcher_status_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatcher_template_type_options`
--
ALTER TABLE `dispatcher_template_type_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatcher_warning_pages`
--
ALTER TABLE `dispatcher_warning_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_registration_documents`
--
ALTER TABLE `driver_registration_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_registration_document_translations`
--
ALTER TABLE `driver_registration_document_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_page_labels`
--
ALTER TABLE `home_page_labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_page_label_transaltions`
--
ALTER TABLE `home_page_label_transaltions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `home_page_label_transaltions_home_page_label_id_foreign` (`home_page_label_id`),
  ADD KEY `home_page_label_transaltions_language_id_foreign` (`language_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_sort_code_unique` (`sort_code`),
  ADD UNIQUE KEY `languages_name_unique` (`name`);

--
-- Indexes for table `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `luxury_options`
--
ALTER TABLE `luxury_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `map_providers`
--
ALTER TABLE `map_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_banners`
--
ALTER TABLE `mobile_banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobile_banners_redirect_category_id_foreign` (`redirect_category_id`),
  ADD KEY `mobile_banners_redirect_vendor_id_foreign` (`redirect_vendor_id`),
  ADD KEY `mobile_banners_name_index` (`name`),
  ADD KEY `mobile_banners_status_index` (`status`),
  ADD KEY `mobile_banners_start_date_time_index` (`start_date_time`),
  ADD KEY `mobile_banners_end_date_time_index` (`end_date_time`);

--
-- Indexes for table `nomenclatures`
--
ALTER TABLE `nomenclatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nomenclatures_translations`
--
ALTER TABLE `nomenclatures_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_types`
--
ALTER TABLE `notification_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_types_name_index` (`name`);

--
-- Indexes for table `onboard_settings`
--
ALTER TABLE `onboard_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_address_id_foreign` (`address_id`),
  ADD KEY `orders_tax_category_id_foreign` (`tax_category_id`),
  ADD KEY `orders_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `order_product_addons`
--
ALTER TABLE `order_product_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_addons_order_product_id_foreign` (`order_product_id`),
  ADD KEY `order_product_addons_addon_id_foreign` (`addon_id`),
  ADD KEY `order_product_addons_option_id_foreign` (`option_id`);

--
-- Indexes for table `order_product_prescriptions`
--
ALTER TABLE `order_product_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_prescriptions_order_id_foreign` (`order_id`),
  ADD KEY `order_product_prescriptions_product_id_foreign` (`product_id`),
  ADD KEY `order_product_prescriptions_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `order_product_ratings`
--
ALTER TABLE `order_product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_ratings_order_id_foreign` (`order_id`),
  ADD KEY `order_product_ratings_order_vendor_product_id_foreign` (`order_vendor_product_id`);

--
-- Indexes for table `order_product_rating_files`
--
ALTER TABLE `order_product_rating_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_rating_files_order_product_rating_id_foreign` (`order_product_rating_id`);

--
-- Indexes for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_return_requests_order_vendor_product_id_foreign` (`order_vendor_product_id`),
  ADD KEY `order_return_requests_order_id_foreign` (`order_id`),
  ADD KEY `order_return_requests_return_by_foreign` (`return_by`);

--
-- Indexes for table `order_return_request_files`
--
ALTER TABLE `order_return_request_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_return_request_files_order_return_request_id_foreign` (`order_return_request_id`);

--
-- Indexes for table `order_status_options`
--
ALTER TABLE `order_status_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_taxes`
--
ALTER TABLE `order_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_vendors_order_id_foreign` (`order_id`),
  ADD KEY `order_vendors_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `order_vendor_products`
--
ALTER TABLE `order_vendor_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_products_order_id_foreign` (`order_id`),
  ADD KEY `order_products_product_id_foreign` (`product_id`),
  ADD KEY `order_products_tax_category_id_foreign` (`tax_category_id`),
  ADD KEY `order_products_vendor_id_foreign` (`vendor_id`),
  ADD KEY `order_products_variant_id_foreign` (`variant_id`),
  ADD KEY `order_vendor_products_category_id_foreign` (`category_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_translations`
--
ALTER TABLE `page_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_translations_page_id_foreign` (`page_id`),
  ADD KEY `page_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_cart_id_foreign` (`cart_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `payment_options`
--
ALTER TABLE `payment_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payout_options`
--
ALTER TABLE `payout_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_vendor_id_foreign` (`vendor_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_type_id_foreign` (`type_id`),
  ADD KEY `products_country_origin_id_foreign` (`country_origin_id`),
  ADD KEY `products_is_new_index` (`is_new`),
  ADD KEY `products_is_featured_index` (`is_featured`),
  ADD KEY `products_is_live_index` (`is_live`),
  ADD KEY `products_is_physical_index` (`is_physical`),
  ADD KEY `products_has_inventory_index` (`has_inventory`),
  ADD KEY `products_sell_when_out_of_stock_index` (`sell_when_out_of_stock`),
  ADD KEY `products_requires_shipping_index` (`requires_shipping`),
  ADD KEY `products_requires_last_mile_index` (`Requires_last_mile`),
  ADD KEY `products_averagerating_index` (`averageRating`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_tax_category_id_foreign` (`tax_category_id`);

--
-- Indexes for table `product_addons`
--
ALTER TABLE `product_addons`
  ADD KEY `product_addons_product_id_foreign` (`product_id`),
  ADD KEY `product_addons_addon_id_foreign` (`addon_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD KEY `product_categories_product_id_foreign` (`product_id`),
  ADD KEY `product_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_celebrities`
--
ALTER TABLE `product_celebrities`
  ADD KEY `product_celebrities_celebrity_id_foreign` (`celebrity_id`),
  ADD KEY `product_celebrities_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_cross_sells`
--
ALTER TABLE `product_cross_sells`
  ADD KEY `product_cross_sells_product_id_foreign` (`product_id`),
  ADD KEY `product_cross_sells_cross_product_id_foreign` (`cross_product_id`);

--
-- Indexes for table `product_faqs`
--
ALTER TABLE `product_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_faqs_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_faq_translations`
--
ALTER TABLE `product_faq_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`),
  ADD KEY `product_images_media_id_foreign` (`media_id`);

--
-- Indexes for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_inquiries_product_id_foreign` (`product_id`),
  ADD KEY `product_inquiries_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `product_related`
--
ALTER TABLE `product_related`
  ADD KEY `product_related_product_id_foreign` (`product_id`),
  ADD KEY `product_related_related_product_id_foreign` (`related_product_id`);

--
-- Indexes for table `product_tags`
--
ALTER TABLE `product_tags`
  ADD KEY `product_tags_product_id_foreign` (`product_id`),
  ADD KEY `product_tags_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `product_translations`
--
ALTER TABLE `product_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_translations_product_id_foreign` (`product_id`),
  ADD KEY `product_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `product_up_sells`
--
ALTER TABLE `product_up_sells`
  ADD KEY `product_up_sells_product_id_foreign` (`product_id`),
  ADD KEY `product_up_sells_upsell_product_id_foreign` (`upsell_product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD UNIQUE KEY `product_variants_barcode_unique` (`barcode`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_variants_tax_category_id_foreign` (`tax_category_id`),
  ADD KEY `product_variants_sku_index` (`sku`),
  ADD KEY `product_variants_quantity_index` (`quantity`),
  ADD KEY `product_variants_price_index` (`price`),
  ADD KEY `product_variants_compare_at_price_index` (`compare_at_price`),
  ADD KEY `product_variants_cost_price_index` (`cost_price`);

--
-- Indexes for table `product_variant_images`
--
ALTER TABLE `product_variant_images`
  ADD KEY `product_variant_images_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `product_variant_images_product_image_id_foreign` (`product_image_id`);

--
-- Indexes for table `product_variant_sets`
--
ALTER TABLE `product_variant_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_sets_product_id_foreign` (`product_id`),
  ADD KEY `product_variant_sets_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `product_variant_sets_variant_type_id_foreign` (`variant_type_id`),
  ADD KEY `product_variant_sets_variant_option_id_foreign` (`variant_option_id`);

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promocodes_promo_type_id_foreign` (`promo_type_id`),
  ADD KEY `promocodes_created_by_foreign` (`created_by`),
  ADD KEY `promocodes_allow_free_delivery_index` (`allow_free_delivery`),
  ADD KEY `promocodes_minimum_spend_index` (`minimum_spend`),
  ADD KEY `promocodes_maximum_spend_index` (`maximum_spend`),
  ADD KEY `promocodes_first_order_only_index` (`first_order_only`),
  ADD KEY `promocodes_limit_per_user_index` (`limit_per_user`),
  ADD KEY `promocodes_limit_total_index` (`limit_total`),
  ADD KEY `promocodes_paid_by_vendor_admin_index` (`paid_by_vendor_admin`),
  ADD KEY `promocodes_is_deleted_index` (`is_deleted`);

--
-- Indexes for table `promocode_details`
--
ALTER TABLE `promocode_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promocode_restrictions`
--
ALTER TABLE `promocode_restrictions`
  ADD KEY `promocode_restrictions_is_included_index` (`is_included`),
  ADD KEY `promocode_restrictions_is_excluded_index` (`is_excluded`),
  ADD KEY `promocode_restrictions_promocode_id_foreign` (`promocode_id`);

--
-- Indexes for table `promo_types`
--
ALTER TABLE `promo_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_usages`
--
ALTER TABLE `promo_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo_usages_promocode_id_foreign` (`promocode_id`),
  ADD KEY `promo_usages_user_id_foreign` (`user_id`);

--
-- Indexes for table `refer_and_earns`
--
ALTER TABLE `refer_and_earns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refer_and_earns_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_reasons`
--
ALTER TABLE `return_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_areas_vendor_id_foreign` (`vendor_id`),
  ADD KEY `service_areas_name_index` (`name`);

--
-- Indexes for table `shipping_options`
--
ALTER TABLE `shipping_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slot_days`
--
ALTER TABLE `slot_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slot_days_slot_id_foreign` (`slot_id`),
  ADD KEY `slot_days_day_index` (`day`);

--
-- Indexes for table `sms_providers`
--
ALTER TABLE `sms_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_credentials`
--
ALTER TABLE `social_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_features_list_user`
--
ALTER TABLE `subscription_features_list_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_features_list_vendor`
--
ALTER TABLE `subscription_features_list_vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_invoices_user`
--
ALTER TABLE `subscription_invoices_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_invoices_user_user_id_index` (`user_id`),
  ADD KEY `subscription_invoices_user_status_id_index` (`status_id`),
  ADD KEY `subscription_invoices_user_subscription_id_index` (`subscription_id`),
  ADD KEY `subscription_invoices_user_payment_option_id_index` (`payment_option_id`);

--
-- Indexes for table `subscription_invoices_vendor`
--
ALTER TABLE `subscription_invoices_vendor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_invoices_vendor_vendor_id_index` (`vendor_id`),
  ADD KEY `subscription_invoices_vendor_status_id_index` (`status_id`),
  ADD KEY `subscription_invoices_vendor_subscription_id_index` (`subscription_id`),
  ADD KEY `subscription_invoices_vendor_payment_option_id_index` (`payment_option_id`);

--
-- Indexes for table `subscription_invoice_features_user`
--
ALTER TABLE `subscription_invoice_features_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_invoice_features_user_subscription_invoice_id_index` (`subscription_invoice_id`),
  ADD KEY `subscription_invoice_features_user_feature_id_index` (`feature_id`);

--
-- Indexes for table `subscription_invoice_features_vendor`
--
ALTER TABLE `subscription_invoice_features_vendor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_subscription_invoice_id_index` (`subscription_invoice_id`),
  ADD KEY `subscription_invoice_features_vendor_feature_id_index` (`feature_id`);

--
-- Indexes for table `subscription_log_user`
--
ALTER TABLE `subscription_log_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans_user`
--
ALTER TABLE `subscription_plans_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans_vendor`
--
ALTER TABLE `subscription_plans_vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plan_features_user`
--
ALTER TABLE `subscription_plan_features_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_plan_features_user_subscription_plan_id_foreign` (`subscription_plan_id`),
  ADD KEY `subscription_plan_features_user_feature_id_foreign` (`feature_id`);

--
-- Indexes for table `subscription_plan_features_vendor`
--
ALTER TABLE `subscription_plan_features_vendor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_plan_features_vendor_subscription_plan_id_foreign` (`subscription_plan_id`),
  ADD KEY `subscription_plan_features_vendor_feature_id_foreign` (`feature_id`);

--
-- Indexes for table `subscription_status_options`
--
ALTER TABLE `subscription_status_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag_translations`
--
ALTER TABLE `tag_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tag_translations_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `tax_categories`
--
ALTER TABLE `tax_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_categories_vendor_id_foreign` (`vendor_id`),
  ADD KEY `tax_categories_code_index` (`code`),
  ADD KEY `tax_categories_is_core_index` (`is_core`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_rates_is_zip_index` (`is_zip`);

--
-- Indexes for table `tax_rate_categories`
--
ALTER TABLE `tax_rate_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_rate_categories_tax_cate_id_foreign` (`tax_cate_id`),
  ADD KEY `tax_rate_categories_tax_rate_id_foreign` (`tax_rate_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminologies`
--
ALTER TABLE `terminologies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_uuid_unique` (`uuid`),
  ADD KEY `transactions_payable_type_payable_id_index` (`payable_type`,`payable_id`),
  ADD KEY `payable_type_ind` (`payable_type`,`payable_id`,`type`),
  ADD KEY `payable_confirmed_ind` (`payable_type`,`payable_id`,`confirmed`),
  ADD KEY `payable_type_confirmed_ind` (`payable_type`,`payable_id`,`type`,`confirmed`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_wallet_id_foreign` (`wallet_id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfers_uuid_unique` (`uuid`),
  ADD KEY `transfers_from_type_from_id_index` (`from_type`,`from_id`),
  ADD KEY `transfers_to_type_to_id_index` (`to_type`,`to_id`),
  ADD KEY `transfers_deposit_id_foreign` (`deposit_id`),
  ADD KEY `transfers_withdraw_id_foreign` (`withdraw_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_country_id_foreign` (`country_id`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_phone_number_index` (`phone_number`),
  ADD KEY `users_type_index` (`type`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_facebook_auth_id_index` (`facebook_auth_id`),
  ADD KEY `users_twitter_auth_id_index` (`twitter_auth_id`),
  ADD KEY `users_google_auth_id_index` (`google_auth_id`),
  ADD KEY `users_apple_auth_id_index` (`apple_auth_id`),
  ADD KEY `users_timezone_id_foreign` (`timezone_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_devices_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_loyalty_points`
--
ALTER TABLE `user_loyalty_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_loyalty_points_points_index` (`points`),
  ADD KEY `user_loyalty_points_user_id_foreign` (`user_id`),
  ADD KEY `user_loyalty_points_loyalty_card_id_foreign` (`loyalty_card_id`);

--
-- Indexes for table `user_loyalty_point_histories`
--
ALTER TABLE `user_loyalty_point_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_refferals`
--
ALTER TABLE `user_refferals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_refferals_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_saved_payment_methods`
--
ALTER TABLE `user_saved_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_saved_payment_methods_user_id_index` (`user_id`),
  ADD KEY `user_saved_payment_methods_payment_option_id_index` (`payment_option_id`);

--
-- Indexes for table `user_vendors`
--
ALTER TABLE `user_vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_vendors_vendor_id_foreign` (`vendor_id`),
  ADD KEY `user_vendors_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_wishlists_user_id_foreign` (`user_id`),
  ADD KEY `user_wishlists_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variants_type_index` (`type`),
  ADD KEY `variants_position_index` (`position`),
  ADD KEY `variants_status_index` (`status`);

--
-- Indexes for table `variant_categories`
--
ALTER TABLE `variant_categories`
  ADD KEY `variant_categories_variant_id_foreign` (`variant_id`),
  ADD KEY `variant_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_options_position_index` (`position`),
  ADD KEY `variant_options_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `variant_option_translations`
--
ALTER TABLE `variant_option_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_option_translations_variant_option_id_foreign` (`variant_option_id`),
  ADD KEY `variant_option_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `variant_translations`
--
ALTER TABLE `variant_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_translations_variant_id_foreign` (`variant_id`),
  ADD KEY `variant_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendors_name_index` (`name`),
  ADD KEY `vendors_order_min_amount_index` (`order_min_amount`),
  ADD KEY `vendors_order_pre_time_index` (`order_pre_time`),
  ADD KEY `vendors_auto_reject_time_index` (`auto_reject_time`),
  ADD KEY `vendors_commission_percent_index` (`commission_percent`),
  ADD KEY `vendors_commission_fixed_per_order_index` (`commission_fixed_per_order`),
  ADD KEY `vendors_commission_monthly_index` (`commission_monthly`),
  ADD KEY `vendors_dine_in_index` (`dine_in`),
  ADD KEY `vendors_takeaway_index` (`takeaway`),
  ADD KEY `vendors_delivery_index` (`delivery`),
  ADD KEY `vendors_add_category_index` (`add_category`),
  ADD KEY `vendors_vendor_templete_id_foreign` (`vendor_templete_id`);

--
-- Indexes for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_categories_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `vendor_connected_accounts`
--
ALTER TABLE `vendor_connected_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_dinein_categories`
--
ALTER TABLE `vendor_dinein_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_dinein_categories_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_dinein_category_translations`
--
ALTER TABLE `vendor_dinein_category_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_dinein_category_translations_category_id_foreign` (`category_id`),
  ADD KEY `vendor_dinein_category_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `vendor_dinein_tables`
--
ALTER TABLE `vendor_dinein_tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_dinein_tables_vendor_dinein_category_id_foreign` (`vendor_dinein_category_id`),
  ADD KEY `vendor_dinein_tables_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_dinein_table_translations`
--
ALTER TABLE `vendor_dinein_table_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_dinein_table_translations_vendor_dinein_table_id_foreign` (`vendor_dinein_table_id`),
  ADD KEY `vendor_dinein_table_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `vendor_docs`
--
ALTER TABLE `vendor_docs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_media`
--
ALTER TABLE `vendor_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_media_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_media_media_type_index` (`media_type`);

--
-- Indexes for table `vendor_order_dispatcher_statuses`
--
ALTER TABLE `vendor_order_dispatcher_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispatcher_statuses_order_id_foreign` (`order_id`),
  ADD KEY `dispatcher_statuses_dispatcher_status_option_id_foreign` (`dispatcher_status_option_id`),
  ADD KEY `vendor_order_dispatcher_statuses_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_order_statuses`
--
ALTER TABLE `vendor_order_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_statuses_order_id_foreign` (`order_id`),
  ADD KEY `order_statuses_order_status_option_id_foreign` (`order_status_option_id`),
  ADD KEY `vendor_order_statuses_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_payouts`
--
ALTER TABLE `vendor_payouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_registration_documents`
--
ALTER TABLE `vendor_registration_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_registration_document_translations`
--
ALTER TABLE `vendor_registration_document_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_saved_payment_methods`
--
ALTER TABLE `vendor_saved_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_saved_payment_methods_vendor_id_index` (`vendor_id`),
  ADD KEY `vendor_saved_payment_methods_payment_option_id_index` (`payment_option_id`);

--
-- Indexes for table `vendor_slots`
--
ALTER TABLE `vendor_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_slots_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_slots_category_id_foreign` (`category_id`),
  ADD KEY `vendor_slots_start_time_index` (`start_time`),
  ADD KEY `vendor_slots_end_time_index` (`end_time`),
  ADD KEY `vendor_slots_dine_in_index` (`dine_in`),
  ADD KEY `vendor_slots_takeaway_index` (`takeaway`),
  ADD KEY `vendor_slots_delivery_index` (`delivery`);

--
-- Indexes for table `vendor_slot_dates`
--
ALTER TABLE `vendor_slot_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_slot_dates_specific_date_index` (`specific_date`),
  ADD KEY `vendor_slot_dates_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_slot_dates_category_id_foreign` (`category_id`),
  ADD KEY `vendor_slot_dates_dine_in_index` (`dine_in`),
  ADD KEY `vendor_slot_dates_takeaway_index` (`takeaway`),
  ADD KEY `vendor_slot_dates_delivery_index` (`delivery`);

--
-- Indexes for table `vendor_templetes`
--
ALTER TABLE `vendor_templetes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_users`
--
ALTER TABLE `vendor_users`
  ADD KEY `vendor_users_user_id_foreign` (`user_id`),
  ADD KEY `vendor_users_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_stylings`
--
ALTER TABLE `web_stylings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_styling_options`
--
ALTER TABLE `web_styling_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_styling_options_web_styling_id_foreign` (`web_styling_id`);

--
-- Indexes for table `woocommerces`
--
ALTER TABLE `woocommerces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addon_options`
--
ALTER TABLE `addon_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `addon_option_translations`
--
ALTER TABLE `addon_option_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `addon_sets`
--
ALTER TABLE `addon_sets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `addon_set_translations`
--
ALTER TABLE `addon_set_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_dynamic_tutorials`
--
ALTER TABLE `app_dynamic_tutorials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_stylings`
--
ALTER TABLE `app_stylings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `app_styling_options`
--
ALTER TABLE `app_styling_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `auto_reject_orders_cron`
--
ALTER TABLE `auto_reject_orders_cron`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blocked_tokens`
--
ALTER TABLE `blocked_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `brand_categories`
--
ALTER TABLE `brand_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `brand_translations`
--
ALTER TABLE `brand_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_types`
--
ALTER TABLE `business_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cab_booking_layouts`
--
ALTER TABLE `cab_booking_layouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cab_booking_layout_categories`
--
ALTER TABLE `cab_booking_layout_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cab_booking_layout_transaltions`
--
ALTER TABLE `cab_booking_layout_transaltions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_addons`
--
ALTER TABLE `cart_addons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_coupons`
--
ALTER TABLE `cart_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_products`
--
ALTER TABLE `cart_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_product_prescriptions`
--
ALTER TABLE `cart_product_prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `category_histories`
--
ALTER TABLE `category_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_translations`
--
ALTER TABLE `category_translations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `celebrities`
--
ALTER TABLE `celebrities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_preferences`
--
ALTER TABLE `client_preferences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms`
--
ALTER TABLE `cms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `csv_product_imports`
--
ALTER TABLE `csv_product_imports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `csv_vendor_imports`
--
ALTER TABLE `csv_vendor_imports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `dispatcher_status_options`
--
ALTER TABLE `dispatcher_status_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dispatcher_template_type_options`
--
ALTER TABLE `dispatcher_template_type_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispatcher_warning_pages`
--
ALTER TABLE `dispatcher_warning_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_registration_documents`
--
ALTER TABLE `driver_registration_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_registration_document_translations`
--
ALTER TABLE `driver_registration_document_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_page_labels`
--
ALTER TABLE `home_page_labels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `home_page_label_transaltions`
--
ALTER TABLE `home_page_label_transaltions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `luxury_options`
--
ALTER TABLE `luxury_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `map_providers`
--
ALTER TABLE `map_providers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=389;

--
-- AUTO_INCREMENT for table `mobile_banners`
--
ALTER TABLE `mobile_banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nomenclatures`
--
ALTER TABLE `nomenclatures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nomenclatures_translations`
--
ALTER TABLE `nomenclatures_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onboard_settings`
--
ALTER TABLE `onboard_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_addons`
--
ALTER TABLE `order_product_addons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_prescriptions`
--
ALTER TABLE `order_product_prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_ratings`
--
ALTER TABLE `order_product_ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_rating_files`
--
ALTER TABLE `order_product_rating_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return_request_files`
--
ALTER TABLE `order_return_request_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_options`
--
ALTER TABLE `order_status_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_taxes`
--
ALTER TABLE `order_taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_vendors`
--
ALTER TABLE `order_vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_vendor_products`
--
ALTER TABLE `order_vendor_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `page_translations`
--
ALTER TABLE `page_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_options`
--
ALTER TABLE `payment_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payout_options`
--
ALTER TABLE `payout_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `product_faqs`
--
ALTER TABLE `product_faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_faq_translations`
--
ALTER TABLE `product_faq_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_translations`
--
ALTER TABLE `product_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `product_variant_sets`
--
ALTER TABLE `product_variant_sets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promocode_details`
--
ALTER TABLE `promocode_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_types`
--
ALTER TABLE `promo_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promo_usages`
--
ALTER TABLE `promo_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refer_and_earns`
--
ALTER TABLE `refer_and_earns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_reasons`
--
ALTER TABLE `return_reasons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_areas`
--
ALTER TABLE `service_areas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_options`
--
ALTER TABLE `shipping_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slot_days`
--
ALTER TABLE `slot_days`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_providers`
--
ALTER TABLE `sms_providers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `social_credentials`
--
ALTER TABLE `social_credentials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscription_features_list_user`
--
ALTER TABLE `subscription_features_list_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_features_list_vendor`
--
ALTER TABLE `subscription_features_list_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_invoices_user`
--
ALTER TABLE `subscription_invoices_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_invoices_vendor`
--
ALTER TABLE `subscription_invoices_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_invoice_features_user`
--
ALTER TABLE `subscription_invoice_features_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_invoice_features_vendor`
--
ALTER TABLE `subscription_invoice_features_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_log_user`
--
ALTER TABLE `subscription_log_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans_user`
--
ALTER TABLE `subscription_plans_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans_vendor`
--
ALTER TABLE `subscription_plans_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plan_features_user`
--
ALTER TABLE `subscription_plan_features_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plan_features_vendor`
--
ALTER TABLE `subscription_plan_features_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_status_options`
--
ALTER TABLE `subscription_status_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tag_translations`
--
ALTER TABLE `tag_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_categories`
--
ALTER TABLE `tax_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_rate_categories`
--
ALTER TABLE `tax_rate_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `terminologies`
--
ALTER TABLE `terminologies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_loyalty_points`
--
ALTER TABLE `user_loyalty_points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_loyalty_point_histories`
--
ALTER TABLE `user_loyalty_point_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_refferals`
--
ALTER TABLE `user_refferals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_saved_payment_methods`
--
ALTER TABLE `user_saved_payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_vendors`
--
ALTER TABLE `user_vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `variant_options`
--
ALTER TABLE `variant_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `variant_option_translations`
--
ALTER TABLE `variant_option_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `variant_translations`
--
ALTER TABLE `variant_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `vendor_connected_accounts`
--
ALTER TABLE `vendor_connected_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_dinein_categories`
--
ALTER TABLE `vendor_dinein_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_dinein_category_translations`
--
ALTER TABLE `vendor_dinein_category_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_dinein_tables`
--
ALTER TABLE `vendor_dinein_tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_dinein_table_translations`
--
ALTER TABLE `vendor_dinein_table_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_docs`
--
ALTER TABLE `vendor_docs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_media`
--
ALTER TABLE `vendor_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `vendor_order_dispatcher_statuses`
--
ALTER TABLE `vendor_order_dispatcher_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_order_statuses`
--
ALTER TABLE `vendor_order_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payouts`
--
ALTER TABLE `vendor_payouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_registration_documents`
--
ALTER TABLE `vendor_registration_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_registration_document_translations`
--
ALTER TABLE `vendor_registration_document_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_saved_payment_methods`
--
ALTER TABLE `vendor_saved_payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_slots`
--
ALTER TABLE `vendor_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_slot_dates`
--
ALTER TABLE `vendor_slot_dates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_templetes`
--
ALTER TABLE `vendor_templetes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `web_stylings`
--
ALTER TABLE `web_stylings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `web_styling_options`
--
ALTER TABLE `web_styling_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `woocommerces`
--
ALTER TABLE `woocommerces`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addon_options`
--
ALTER TABLE `addon_options`
  ADD CONSTRAINT `addon_options_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `addon_option_translations`
--
ALTER TABLE `addon_option_translations`
  ADD CONSTRAINT `addon_option_translations_addon_opt_id_foreign` FOREIGN KEY (`addon_opt_id`) REFERENCES `addon_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `addon_option_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `addon_sets`
--
ALTER TABLE `addon_sets`
  ADD CONSTRAINT `addon_sets_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `addon_set_translations`
--
ALTER TABLE `addon_set_translations`
  ADD CONSTRAINT `addon_set_translations_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `addon_set_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `app_styling_options`
--
ALTER TABLE `app_styling_options`
  ADD CONSTRAINT `app_styling_options_app_styling_id_foreign` FOREIGN KEY (`app_styling_id`) REFERENCES `app_stylings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_redirect_category_id_foreign` FOREIGN KEY (`redirect_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `banners_redirect_vendor_id_foreign` FOREIGN KEY (`redirect_vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `brand_categories`
--
ALTER TABLE `brand_categories`
  ADD CONSTRAINT `brand_categories_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `brand_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `brand_translations`
--
ALTER TABLE `brand_translations`
  ADD CONSTRAINT `brand_translations_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `brand_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `cab_booking_layout_categories`
--
ALTER TABLE `cab_booking_layout_categories`
  ADD CONSTRAINT `cab_booking_layout_categories_cab_booking_layout_id_foreign` FOREIGN KEY (`cab_booking_layout_id`) REFERENCES `cab_booking_layouts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cab_booking_layout_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cab_booking_layout_transaltions`
--
ALTER TABLE `cab_booking_layout_transaltions`
  ADD CONSTRAINT `cab_booking_layout_transaltions_cab_booking_layout_id_foreign` FOREIGN KEY (`cab_booking_layout_id`) REFERENCES `cab_booking_layouts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cab_booking_layout_transaltions_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_addons`
--
ALTER TABLE `cart_addons`
  ADD CONSTRAINT `cart_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_addons_cart_product_id_foreign` FOREIGN KEY (`cart_product_id`) REFERENCES `cart_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_addons_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `addon_options` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_coupons`
--
ALTER TABLE `cart_coupons`
  ADD CONSTRAINT `cart_coupons_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_coupons_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `promocodes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_products`
--
ALTER TABLE `cart_products`
  ADD CONSTRAINT `cart_products_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_products_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_tax_rate_id_foreign` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_product_prescriptions`
--
ALTER TABLE `cart_product_prescriptions`
  ADD CONSTRAINT `cart_product_prescriptions_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_product_prescriptions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_product_prescriptions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `category_histories`
--
ALTER TABLE `category_histories`
  ADD CONSTRAINT `category_histories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_tags`
--
ALTER TABLE `category_tags`
  ADD CONSTRAINT `category_tags_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_translations`
--
ALTER TABLE `category_translations`
  ADD CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `celebrities`
--
ALTER TABLE `celebrities`
  ADD CONSTRAINT `celebrities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `celebrity_brands`
--
ALTER TABLE `celebrity_brands`
  ADD CONSTRAINT `celebrity_brands_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `celebrity_brands_celebrity_id_foreign` FOREIGN KEY (`celebrity_id`) REFERENCES `celebrities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `client_currencies`
--
ALTER TABLE `client_currencies`
  ADD CONSTRAINT `client_currencies_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_currencies_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `client_languages`
--
ALTER TABLE `client_languages`
  ADD CONSTRAINT `client_languages_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_languages_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `client_preferences`
--
ALTER TABLE `client_preferences`
  ADD CONSTRAINT `client_preferences_app_template_id_foreign` FOREIGN KEY (`app_template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_map_provider_foreign` FOREIGN KEY (`map_provider`) REFERENCES `map_providers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_sms_provider_foreign` FOREIGN KEY (`sms_provider`) REFERENCES `sms_providers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_web_template_id_foreign` FOREIGN KEY (`web_template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cms`
--
ALTER TABLE `cms`
  ADD CONSTRAINT `cms_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `csv_product_imports`
--
ALTER TABLE `csv_product_imports`
  ADD CONSTRAINT `csv_product_imports_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `csv_product_imports_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `csv_vendor_imports`
--
ALTER TABLE `csv_vendor_imports`
  ADD CONSTRAINT `csv_vendor_imports_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `home_page_label_transaltions`
--
ALTER TABLE `home_page_label_transaltions`
  ADD CONSTRAINT `home_page_label_transaltions_home_page_label_id_foreign` FOREIGN KEY (`home_page_label_id`) REFERENCES `home_page_labels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `home_page_label_transaltions_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mobile_banners`
--
ALTER TABLE `mobile_banners`
  ADD CONSTRAINT `mobile_banners_redirect_category_id_foreign` FOREIGN KEY (`redirect_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mobile_banners_redirect_vendor_id_foreign` FOREIGN KEY (`redirect_vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_addons`
--
ALTER TABLE `order_product_addons`
  ADD CONSTRAINT `order_product_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_addons_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `addon_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_addons_order_product_id_foreign` FOREIGN KEY (`order_product_id`) REFERENCES `order_vendor_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_prescriptions`
--
ALTER TABLE `order_product_prescriptions`
  ADD CONSTRAINT `order_product_prescriptions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_prescriptions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_prescriptions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_ratings`
--
ALTER TABLE `order_product_ratings`
  ADD CONSTRAINT `order_product_ratings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_ratings_order_vendor_product_id_foreign` FOREIGN KEY (`order_vendor_product_id`) REFERENCES `order_vendor_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_rating_files`
--
ALTER TABLE `order_product_rating_files`
  ADD CONSTRAINT `order_product_rating_files_order_product_rating_id_foreign` FOREIGN KEY (`order_product_rating_id`) REFERENCES `order_product_ratings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  ADD CONSTRAINT `order_return_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_return_requests_order_vendor_product_id_foreign` FOREIGN KEY (`order_vendor_product_id`) REFERENCES `order_vendor_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_return_requests_return_by_foreign` FOREIGN KEY (`return_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_return_request_files`
--
ALTER TABLE `order_return_request_files`
  ADD CONSTRAINT `order_return_request_files_order_return_request_id_foreign` FOREIGN KEY (`order_return_request_id`) REFERENCES `order_return_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD CONSTRAINT `order_vendors_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_vendors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_vendor_products`
--
ALTER TABLE `order_vendor_products`
  ADD CONSTRAINT `order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_products_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_vendor_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `page_translations`
--
ALTER TABLE `page_translations`
  ADD CONSTRAINT `page_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  ADD CONSTRAINT `page_translations_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_country_origin_id_foreign` FOREIGN KEY (`country_origin_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_addons`
--
ALTER TABLE `product_addons`
  ADD CONSTRAINT `product_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_addons_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_celebrities`
--
ALTER TABLE `product_celebrities`
  ADD CONSTRAINT `product_celebrities_celebrity_id_foreign` FOREIGN KEY (`celebrity_id`) REFERENCES `celebrities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_celebrities_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_cross_sells`
--
ALTER TABLE `product_cross_sells`
  ADD CONSTRAINT `product_cross_sells_cross_product_id_foreign` FOREIGN KEY (`cross_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_cross_sells_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_faqs`
--
ALTER TABLE `product_faqs`
  ADD CONSTRAINT `product_faqs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `vendor_media` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  ADD CONSTRAINT `product_inquiries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_inquiries_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_related`
--
ALTER TABLE `product_related`
  ADD CONSTRAINT `product_related_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_related_related_product_id_foreign` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_tags`
--
ALTER TABLE `product_tags`
  ADD CONSTRAINT `product_tags_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_translations`
--
ALTER TABLE `product_translations`
  ADD CONSTRAINT `product_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_up_sells`
--
ALTER TABLE `product_up_sells`
  ADD CONSTRAINT `product_up_sells_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_up_sells_upsell_product_id_foreign` FOREIGN KEY (`upsell_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_variant_images`
--
ALTER TABLE `product_variant_images`
  ADD CONSTRAINT `product_variant_images_product_image_id_foreign` FOREIGN KEY (`product_image_id`) REFERENCES `product_images` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_images_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_sets`
--
ALTER TABLE `product_variant_sets`
  ADD CONSTRAINT `product_variant_sets_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_sets_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_sets_variant_option_id_foreign` FOREIGN KEY (`variant_option_id`) REFERENCES `variant_options` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_variant_sets_variant_type_id_foreign` FOREIGN KEY (`variant_type_id`) REFERENCES `variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD CONSTRAINT `promocodes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `promocodes_promo_type_id_foreign` FOREIGN KEY (`promo_type_id`) REFERENCES `promo_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `promocode_restrictions`
--
ALTER TABLE `promocode_restrictions`
  ADD CONSTRAINT `promocode_restrictions_promocode_id_foreign` FOREIGN KEY (`promocode_id`) REFERENCES `promocodes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promo_usages`
--
ALTER TABLE `promo_usages`
  ADD CONSTRAINT `promo_usages_promocode_id_foreign` FOREIGN KEY (`promocode_id`) REFERENCES `promocodes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promo_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `refer_and_earns`
--
ALTER TABLE `refer_and_earns`
  ADD CONSTRAINT `refer_and_earns_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD CONSTRAINT `service_areas_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `slot_days`
--
ALTER TABLE `slot_days`
  ADD CONSTRAINT `slot_days_slot_id_foreign` FOREIGN KEY (`slot_id`) REFERENCES `vendor_slots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_plan_features_user`
--
ALTER TABLE `subscription_plan_features_user`
  ADD CONSTRAINT `subscription_plan_features_user_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `subscription_features_list_user` (`id`),
  ADD CONSTRAINT `subscription_plan_features_user_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans_user` (`id`);

--
-- Constraints for table `subscription_plan_features_vendor`
--
ALTER TABLE `subscription_plan_features_vendor`
  ADD CONSTRAINT `subscription_plan_features_vendor_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `subscription_features_list_vendor` (`id`),
  ADD CONSTRAINT `subscription_plan_features_vendor_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans_vendor` (`id`);

--
-- Constraints for table `tag_translations`
--
ALTER TABLE `tag_translations`
  ADD CONSTRAINT `tag_translations_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tax_categories`
--
ALTER TABLE `tax_categories`
  ADD CONSTRAINT `tax_categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tax_rate_categories`
--
ALTER TABLE `tax_rate_categories`
  ADD CONSTRAINT `tax_rate_categories_tax_cate_id_foreign` FOREIGN KEY (`tax_cate_id`) REFERENCES `tax_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tax_rate_categories_tax_rate_id_foreign` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_deposit_id_foreign` FOREIGN KEY (`deposit_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfers_withdraw_id_foreign` FOREIGN KEY (`withdraw_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_timezone_id_foreign` FOREIGN KEY (`timezone_id`) REFERENCES `timezones` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_loyalty_points`
--
ALTER TABLE `user_loyalty_points`
  ADD CONSTRAINT `user_loyalty_points_loyalty_card_id_foreign` FOREIGN KEY (`loyalty_card_id`) REFERENCES `loyalty_cards` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_loyalty_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_refferals`
--
ALTER TABLE `user_refferals`
  ADD CONSTRAINT `user_refferals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_vendors`
--
ALTER TABLE `user_vendors`
  ADD CONSTRAINT `user_vendors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vendors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  ADD CONSTRAINT `user_wishlists_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_categories`
--
ALTER TABLE `variant_categories`
  ADD CONSTRAINT `variant_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_categories_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD CONSTRAINT `variant_options_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_option_translations`
--
ALTER TABLE `variant_option_translations`
  ADD CONSTRAINT `variant_option_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_option_translations_variant_option_id_foreign` FOREIGN KEY (`variant_option_id`) REFERENCES `variant_options` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_translations`
--
ALTER TABLE `variant_translations`
  ADD CONSTRAINT `variant_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_translations_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_vendor_templete_id_foreign` FOREIGN KEY (`vendor_templete_id`) REFERENCES `vendor_templetes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD CONSTRAINT `vendor_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_dinein_categories`
--
ALTER TABLE `vendor_dinein_categories`
  ADD CONSTRAINT `vendor_dinein_categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_dinein_category_translations`
--
ALTER TABLE `vendor_dinein_category_translations`
  ADD CONSTRAINT `vendor_dinein_category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `vendor_dinein_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_dinein_category_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_dinein_tables`
--
ALTER TABLE `vendor_dinein_tables`
  ADD CONSTRAINT `vendor_dinein_tables_vendor_dinein_category_id_foreign` FOREIGN KEY (`vendor_dinein_category_id`) REFERENCES `vendor_dinein_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_dinein_tables_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_dinein_table_translations`
--
ALTER TABLE `vendor_dinein_table_translations`
  ADD CONSTRAINT `vendor_dinein_table_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_dinein_table_translations_vendor_dinein_table_id_foreign` FOREIGN KEY (`vendor_dinein_table_id`) REFERENCES `vendor_dinein_tables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_media`
--
ALTER TABLE `vendor_media`
  ADD CONSTRAINT `vendor_media_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_order_dispatcher_statuses`
--
ALTER TABLE `vendor_order_dispatcher_statuses`
  ADD CONSTRAINT `dispatcher_statuses_dispatcher_status_option_id_foreign` FOREIGN KEY (`dispatcher_status_option_id`) REFERENCES `dispatcher_status_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispatcher_statuses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_order_dispatcher_statuses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_order_statuses`
--
ALTER TABLE `vendor_order_statuses`
  ADD CONSTRAINT `order_statuses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_statuses_order_status_option_id_foreign` FOREIGN KEY (`order_status_option_id`) REFERENCES `order_status_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_order_statuses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_slots`
--
ALTER TABLE `vendor_slots`
  ADD CONSTRAINT `vendor_slots_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_slots_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendor_slot_dates`
--
ALTER TABLE `vendor_slot_dates`
  ADD CONSTRAINT `vendor_slot_dates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_slot_dates_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_users`
--
ALTER TABLE `vendor_users`
  ADD CONSTRAINT `vendor_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_users_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `web_styling_options`
--
ALTER TABLE `web_styling_options`
  ADD CONSTRAINT `web_styling_options_web_styling_id_foreign` FOREIGN KEY (`web_styling_id`) REFERENCES `web_stylings` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 03:57 PM
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
-- Database: `bus_ticket_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','super_admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `name`, `role`, `created_at`) VALUES
(6, 'admin', '$2y$10$7FPYOl1nLH6dcba0ks5QveMoolecekmBj4fIAZm.8QI/JwpKbVoVm', 'admin@trotter.com', 'Administrator', 'super_admin', '2026-03-30 13:29:15');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `route_id` int(11) NOT NULL,
  `seat_numbers` varchar(255) NOT NULL,
  `journey_date` date NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_fare` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `cancellation_reason` text DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_date` datetime DEFAULT NULL,
  `refund_status` enum('none','pending','approved','rejected') DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_id`, `user_id`, `route_id`, `seat_numbers`, `journey_date`, `booking_date`, `total_fare`, `payment_method`, `payment_status`, `status`, `cancellation_reason`, `cancelled_at`, `created_at`, `payment_date`, `refund_status`) VALUES
(1, 'BOK1774872113418', 'USR17747632875179', 3, '2,4,5', '2026-04-03', '2026-03-30 12:01:53', 1680.00, NULL, 'pending', 'cancelled', NULL, NULL, '2026-03-30 12:01:53', NULL, 'none'),
(2, 'BOK1774872182146', 'USR17747632875179', 3, '9,10', '2026-04-03', '2026-03-30 12:03:02', 1120.00, NULL, 'pending', 'cancelled', NULL, NULL, '2026-03-30 12:03:02', NULL, 'none'),
(3, 'BOK1774875318829', 'USR17747632875179', 3, '4,5', '2026-03-30', '2026-03-30 12:55:18', 1120.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 12:55:18', NULL, 'none'),
(4, 'BOK1774875454277', 'USR17747632875179', 3, '9,10', '2026-03-30', '2026-03-30 12:57:34', 1120.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 12:57:34', NULL, 'none'),
(5, 'BOK1774875824985', 'USR17747632875179', 3, '18,19', '2026-03-30', '2026-03-30 13:03:44', 1120.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:03:44', NULL, 'none'),
(6, 'BOK1774875857994', 'USR17747632875179', 3, '39,40', '2026-03-30', '2026-03-30 13:04:17', 1120.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:04:17', NULL, 'none'),
(7, 'BOK1774876032603', 'USR17747632875179', 3, '1', '2026-03-30', '2026-03-30 13:07:12', 560.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:07:12', NULL, 'none'),
(8, 'BOK1774876080797', 'USR17747632875179', 2, '11', '2026-03-30', '2026-03-30 13:08:00', 1250.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:08:00', NULL, 'none'),
(9, 'BOK1774876384651', 'USR17747632875179', 2, '31', '2026-03-30', '2026-03-30 13:13:04', 1250.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:13:04', NULL, 'none'),
(10, 'BOK1774876463701', 'USR17747632875179', 2, '30', '2026-03-30', '2026-03-30 13:14:23', 1250.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:14:23', NULL, 'none'),
(11, 'BOK1774876662766', 'USR17747632875179', 2, '19', '2026-03-30', '2026-03-30 13:17:42', 1250.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:17:42', NULL, 'none'),
(12, 'BOK1774878368680', 'USR17747632875179', 39, '13,14', '2026-03-30', '2026-03-30 13:46:08', 1600.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-30 13:46:08', NULL, 'none'),
(13, 'BOK1774921503575', 'USR17747632875179', 39, '23', '2026-03-31', '2026-03-31 01:45:03', 800.00, NULL, 'pending', 'confirmed', NULL, NULL, '2026-03-31 01:45:03', NULL, 'none'),
(14, 'BOK1774922184367', 'USR17747632875179', 39, '28', '2026-03-31', '2026-03-31 01:56:24', 800.00, NULL, 'pending', '', NULL, NULL, '2026-03-31 01:56:24', NULL, 'none'),
(15, 'BOK1774922839940', 'USR17747632875179', 39, '28', '2026-03-31', '2026-03-31 02:07:19', 800.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:07:19', NULL, 'none'),
(16, 'BOK1774923045306', 'USR17747632875179', 17, '5', '2026-03-31', '2026-03-31 02:10:45', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:10:45', NULL, 'none'),
(17, 'BOK1774923611164', 'USR17747632875179', 17, '35', '2026-03-31', '2026-03-31 02:20:11', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:20:11', NULL, 'none'),
(18, 'BOK1774923948248', 'USR17747632875179', 17, '30', '2026-03-31', '2026-03-31 02:25:48', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:25:48', NULL, 'none'),
(19, 'BOK1774924142898', 'USR17747632875179', 17, '34', '2026-03-31', '2026-03-31 02:29:02', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:29:02', NULL, 'none'),
(20, 'BOK1774924608318', 'USR17747632875179', 17, '29', '2026-03-31', '2026-03-31 02:36:48', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:36:48', NULL, 'none'),
(21, 'BOK1774924865982', 'USR17747632875179', 17, '24', '2026-03-31', '2026-03-31 02:41:05', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:41:05', NULL, 'none'),
(22, 'BOK1774925366457', 'USR17747632875179', 17, '25', '2026-03-31', '2026-03-31 02:49:26', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 02:49:26', NULL, 'none'),
(23, 'BOK1774927858125', 'USR17747632875179', 16, '9', '2026-03-31', '2026-03-31 03:30:58', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 03:30:58', NULL, 'none'),
(24, 'BOK1774933114421', 'USR17747632875179', 16, '35', '2026-03-31', '2026-03-31 04:58:34', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 04:58:34', NULL, 'none'),
(25, 'BOK1774955649911', 'USR17747632875179', 24, '5', '2026-03-31', '2026-03-31 11:14:09', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 11:14:09', NULL, 'none'),
(26, 'BOK1774956052596', 'USR17747632875179', 24, '5', '2026-03-31', '2026-03-31 11:20:52', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 11:20:52', NULL, 'none'),
(27, 'BOK1774956124372', 'USR17747632875179', 24, '34', '2026-03-31', '2026-03-31 11:22:04', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 11:22:04', NULL, 'none'),
(28, 'BOK1774956259311', 'USR17747632875179', 16, '4', '2026-03-31', '2026-03-31 11:24:19', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 11:24:19', NULL, 'none'),
(29, 'BOK1774956565570', 'USR17747632875179', 16, '4', '2026-03-31', '2026-03-31 11:29:25', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 11:29:25', NULL, 'none'),
(30, 'BOK1774956720883', 'USR17747632875179', 16, '4', '2026-03-31', '2026-03-31 11:32:00', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 11:32:00', NULL, 'none'),
(31, 'BOK1774956933885', 'USR17747632875179', 16, '29', '2026-03-31', '2026-03-31 11:35:33', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 11:35:33', NULL, 'none'),
(32, 'BOK1774957115357', 'USR17747632875179', 16, '3', '2026-03-31', '2026-03-31 11:38:35', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 11:38:35', NULL, 'none'),
(33, 'BOK1774957202317', 'USR17747632875179', 16, '5', '2026-03-31', '2026-03-31 11:40:02', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 11:40:02', NULL, 'none'),
(34, 'BOK1774957215644', 'USR17747632875179', 16, '5', '2026-03-31', '2026-03-31 11:40:15', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 11:40:15', NULL, 'none'),
(35, 'BOK1774957437540', 'USR17747632875179', 16, '34', '2026-03-31', '2026-03-31 11:43:57', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 11:43:57', NULL, 'none'),
(36, 'BOK1774957503510', 'USR17747632875179', 2, '3', '2026-03-31', '2026-03-31 11:45:03', 1250.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 11:45:03', NULL, 'none'),
(37, 'BOK1774957966346', 'USR17747632875179', 39, '5', '2026-03-31', '2026-03-31 11:52:46', 800.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 11:52:46', NULL, 'none'),
(38, 'BOK1774960088653', 'USR17747632875179', 44, '30', '2026-03-31', '2026-03-31 12:28:08', 1600.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 12:28:08', NULL, 'none'),
(39, 'BOK1774960883386', 'USR17747632875179', 46, '88', '2026-03-31', '2026-03-31 12:41:23', 500.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 12:41:23', NULL, 'none'),
(40, 'BOK1774961149196', 'USR17747632875179', 46, '88', '2026-03-31', '2026-03-31 12:45:49', 500.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 12:45:49', NULL, 'none'),
(41, 'BOK1774961201204', 'USR17747632875179', 46, '80', '2026-03-31', '2026-03-31 12:46:41', 500.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 12:46:41', NULL, 'none'),
(42, 'BOK1774961481958', 'USR17747632875179', 46, '80', '2026-03-31', '2026-03-31 12:51:21', 500.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 12:51:21', NULL, 'none'),
(43, 'BOK1774961668595', 'USR17747632875179', 46, '75', '2026-03-31', '2026-03-31 12:54:28', 500.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 12:54:28', NULL, 'none'),
(44, 'BOK1774961899834', 'USR17747632875179', 46, '75', '2026-03-31', '2026-03-31 12:58:19', 500.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 12:58:19', NULL, 'none'),
(45, 'BOK1774961910695', 'USR17747632875179', 46, '3', '2026-03-31', '2026-03-31 12:58:30', 500.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 12:58:30', NULL, 'none'),
(46, 'BOK1774962035656', 'USR17747632875179', 16, '18', '2026-03-31', '2026-03-31 13:00:35', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 13:00:35', NULL, 'none'),
(47, 'BOK1774962288259', 'USR17747632875179', 16, '18', '2026-03-31', '2026-03-31 13:04:48', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 13:04:48', NULL, 'none'),
(48, 'BOK1774962519586', 'USR17747632875179', 16, '18', '2026-03-31', '2026-03-31 13:08:39', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 13:08:39', NULL, 'none'),
(49, 'BOK1774962584909', 'USR17747632875179', 16, '67', '2026-03-31', '2026-03-31 13:09:44', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 13:09:44', NULL, 'none'),
(50, 'BOK1774962703862', 'USR17747632875179', 16, '72', '2026-03-31', '2026-03-31 13:11:43', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-03-31 13:11:43', NULL, 'none'),
(51, 'BOK1774962789414', 'USR17747632875179', 16, '64', '2026-03-31', '2026-03-31 13:13:09', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 13:13:09', NULL, 'none'),
(52, 'BOK1774962834986', 'USR17747632875179', 16, '64', '2026-03-31', '2026-03-31 13:13:54', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 13:13:54', NULL, 'none'),
(53, 'BOK1774962887493', 'USR17747632875179', 16, '64', '2026-03-31', '2026-03-31 13:14:47', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-03-31 13:14:47', NULL, 'none'),
(56, 'BOK1775017412319', 'USR17747632875179', 16, '72', '2026-04-01', '2026-04-01 04:23:32', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-01 04:23:32', NULL, 'none'),
(57, 'BOK1775023171692', 'USR17747632875179', 24, '67', '2026-04-01', '2026-04-01 05:59:31', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-04-01 05:59:31', NULL, 'none'),
(58, 'BOK1775023204321', 'USR17747632875179', 24, '67', '2026-04-01', '2026-04-01 06:00:04', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-04-01 06:00:04', NULL, 'none'),
(59, 'BOK1775105452148', 'USR17750082936913', 47, '5', '2026-04-02', '2026-04-02 04:50:52', 1100.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-02 04:50:52', NULL, 'none'),
(60, 'BOK1775105543647', 'USR17750082936913', 56, '1', '2026-04-02', '2026-04-02 04:52:23', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-04-02 04:52:23', NULL, 'none'),
(61, 'BOK1775224394382', 'USR17750082936913', 56, '1', '2026-04-03', '2026-04-03 13:53:14', 700.00, 'nagad', 'completed', 'confirmed', NULL, NULL, '2026-04-03 13:53:14', '2026-04-03 20:40:38', 'none'),
(62, 'BOK1775225062543', 'USR17750082936913', 56, '1', '2026-04-03', '2026-04-03 14:04:22', 700.00, 'nagad', 'completed', 'confirmed', NULL, NULL, '2026-04-03 14:04:22', '2026-04-03 20:26:14', 'none'),
(63, 'BOK1775225275534', 'USR17750082936913', 56, '14', '2026-04-03', '2026-04-03 14:07:55', 700.00, 'nagad', 'completed', 'confirmed', NULL, NULL, '2026-04-03 14:07:55', '2026-04-03 20:24:31', 'none'),
(64, 'BOK1775226456734', 'USR17750082936913', 47, '80', '2026-04-03', '2026-04-03 14:27:36', 1100.00, 'bkash', 'completed', 'confirmed', NULL, NULL, '2026-04-03 14:27:36', '2026-04-03 20:34:56', 'none'),
(65, 'BOK1775229009259', 'USR17750082936913', 56, '2', '2026-04-03', '2026-04-03 15:10:09', 700.00, 'card', 'completed', 'confirmed', NULL, NULL, '2026-04-03 15:10:09', '2026-04-03 21:10:28', 'none'),
(66, 'BOK1775229047433', 'USR17750082936913', 56, '8', '2026-04-03', '2026-04-03 15:10:47', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-03 15:10:47', NULL, 'none'),
(67, 'BOK1775270081455', 'USR17747632875179', 16, '2', '2026-04-04', '2026-04-04 02:34:41', 700.00, 'bkash', 'completed', 'confirmed', NULL, NULL, '2026-04-04 02:34:41', '2026-04-04 08:35:18', 'none'),
(68, 'BOK1775271325540', 'USR17747632875179', 16, '32', '2026-04-04', '2026-04-04 02:55:25', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-04-04 02:55:25', NULL, 'none'),
(69, 'BOK1775273542741', 'USR17747632875179', 16, '67', '2026-04-04', '2026-04-04 03:32:22', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-04-04 03:32:22', NULL, 'none'),
(70, 'BOK1775356942830', 'USR17747632875179', 2, '16', '2026-04-05', '2026-04-05 02:42:22', 1250.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-05 02:42:22', NULL, 'none'),
(71, 'BOK1775393564314', 'USR17747632875179', 56, '1', '2026-04-05', '2026-04-05 12:52:44', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-05 12:52:44', NULL, 'none'),
(72, 'BOK1775394219126', 'USR17747632875179', 56, '2', '2026-04-05', '2026-04-05 13:03:39', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-05 13:03:39', NULL, 'none'),
(73, 'BOK1775394560281', 'USR17747632875179', 47, '4', '2026-04-05', '2026-04-05 13:09:20', 1100.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-05 13:09:20', NULL, 'none'),
(74, 'BOK1775523281200', 'USR17747632875179', 16, '3', '2026-04-07', '2026-04-07 00:54:41', 700.00, 'bkash', 'completed', 'confirmed', NULL, NULL, '2026-04-07 00:54:41', '2026-04-07 06:55:34', 'none'),
(75, 'BOK1775523433880', 'USR17747632875179', 16, '8', '2026-04-07', '2026-04-07 00:57:13', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-07 00:57:13', NULL, 'none'),
(76, 'BOK1775523995326', 'USR17747632875179', 56, '3', '2026-04-07', '2026-04-07 01:06:35', 700.00, NULL, 'pending', 'pending', NULL, NULL, '2026-04-07 01:06:35', NULL, 'none'),
(77, 'BOK1775524692420', 'USR17747632875179', 56, '1', '2026-04-07', '2026-04-07 01:18:12', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-07 01:18:12', NULL, 'none'),
(78, 'BOK1775524869998', 'USR17747632875179', 56, '32', '2026-04-07', '2026-04-07 01:21:09', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-07 01:21:09', NULL, 'none'),
(81, 'BOK1775531928749', 'USR1775531471399', 16, '7', '2026-04-07', '2026-04-07 03:18:48', 700.00, NULL, 'completed', 'confirmed', NULL, NULL, '2026-04-07 03:18:48', NULL, 'none'),
(82, 'BOK1775532160291', 'USR1775531471399', 56, '3', '2026-04-07', '2026-04-07 03:22:40', 700.00, 'bkash', 'completed', 'confirmed', NULL, NULL, '2026-04-07 03:22:40', '2026-04-07 09:23:13', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `cancellations`
--

CREATE TABLE `cancellations` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `reason` text DEFAULT NULL,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `refund_status` enum('pending','processed','failed') DEFAULT 'pending',
  `processed_by` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `venue` varchar(200) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `from_city` varchar(100) DEFAULT NULL,
  `to_city` varchar(100) DEFAULT NULL,
  `days_left` int(11) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed') DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `end_date`, `location`, `venue`, `image_path`, `from_city`, `to_city`, `days_left`, `status`, `created_at`) VALUES
(1, 'Boishakhi Mela 2026', 'Bangladesh\'s biggest cultural festival celebrating Pohela Boishakh. Enjoy traditional music, dance, food and cultural programs.', '2026-04-14', '10:00:00', '2026-04-15', 'Dhaka', 'Ramna Park', NULL, 'Dhaka', 'Dhaka', 9, 'upcoming', '2026-04-05 02:04:46'),
(2, 'International Trade Fair', 'Annual trade fair featuring products from Bangladesh and other countries.', '2026-04-20', '09:00:00', '2026-04-30', 'Dhaka', 'Purbachal New Town', NULL, 'Dhaka', 'Dhaka', 15, 'upcoming', '2026-04-05 02:04:46'),
(3, 'Eid-ul-Fitr Special', 'Special Eid celebration and prayer gatherings across the country.', '2026-05-02', '08:00:00', '2026-05-03', 'Dhaka', 'National Mosque', NULL, 'Dhaka', 'Dhaka', 27, 'upcoming', '2026-04-05 02:04:46'),
(4, 'Cox\'s Bazar Beach Festival', 'International beach festival with music, sports, and cultural events.', '2026-05-15', '14:00:00', '2026-05-18', 'Cox\'s Bazar', 'Laboni Beach', NULL, 'Dhaka', 'Cox\'s Bazar', 40, 'upcoming', '2026-04-05 02:04:46'),
(5, 'Sylhet Tea Festival', 'Celebration of Sylhet\'s famous tea gardens with cultural programs.', '2026-06-05', '11:00:00', '2026-06-07', 'Sylhet', 'Tea Resort', NULL, 'Dhaka', 'Sylhet', 61, 'upcoming', '2026-04-05 02:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passengers`
--

CREATE TABLE `passengers` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `emergency_contact` varchar(15) DEFAULT NULL,
  `seat_number` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_type` enum('full','partial') DEFAULT 'full',
  `status` enum('pending','successful','failed') DEFAULT 'pending',
  `payment_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refund_requests`
--

CREATE TABLE `refund_requests` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `request_date` datetime DEFAULT current_timestamp(),
  `processed_date` datetime DEFAULT NULL,
  `admin_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refund_requests`
--

INSERT INTO `refund_requests` (`id`, `booking_id`, `user_id`, `amount`, `reason`, `status`, `request_date`, `processed_date`, `admin_note`) VALUES
(1, 'BOK1775393564314', 'USR17747632875179', 700.00, 'My Plan is change. I need Refund', 'approved', '2026-04-05 18:54:36', '2026-04-05 18:54:56', NULL),
(2, 'BOK1775394219126', 'USR17747632875179', 700.00, 'I need money', 'rejected', '2026-04-05 19:04:19', '2026-04-05 19:09:52', NULL),
(3, 'BOK1775394560281', 'USR17747632875179', 1100.00, 'i need money', 'rejected', '2026-04-05 19:09:36', '2026-04-05 19:09:49', NULL),
(4, 'BOK1775524692420', 'USR17747632875179', 700.00, 'I need money', 'rejected', '2026-04-07 07:19:20', '2026-04-07 07:20:40', NULL),
(5, 'BOK1775524869998', 'USR17747632875179', 700.00, 'আমার জাও্বয়ার ইচভছা নাই', 'approved', '2026-04-07 07:21:41', '2026-04-07 07:21:55', NULL),
(6, 'BOK1775532160291', 'USR1775531471399', 700.00, 'I need money', 'approved', '2026-04-07 09:25:22', '2026-04-07 09:26:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `route_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `from_city` varchar(100) NOT NULL,
  `to_city` varchar(100) NOT NULL,
  `bus_name` varchar(100) NOT NULL,
  `bus_type` enum('AC','Non-AC','AC Business','Economy') DEFAULT 'Non-AC',
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `fare` decimal(10,2) NOT NULL,
  `total_seats` int(11) DEFAULT 40,
  `available_days` varchar(50) DEFAULT 'All',
  `amenities` text DEFAULT NULL,
  `operator_name` varchar(100) DEFAULT NULL,
  `operator_phone` varchar(15) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `round_trip_fare` decimal(10,2) DEFAULT NULL,
  `is_round_trip` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `from_city`, `to_city`, `bus_name`, `bus_type`, `departure_time`, `arrival_time`, `fare`, `total_seats`, `available_days`, `amenities`, `operator_name`, `operator_phone`, `status`, `created_at`, `round_trip_fare`, `is_round_trip`) VALUES
(2, 'Dhaka', 'Sylhet', 'Hanif-Mo14521', 'AC Business', '22:00:00', '06:00:00', 1250.00, 32, 'All', NULL, NULL, NULL, 'active', '2026-03-30 11:56:43', NULL, 0),
(3, 'Dhaka', 'Mymensingh', 'Ena-kha14521', 'Non-AC', '10:00:00', '12:30:00', 560.00, 42, 'All', NULL, NULL, NULL, 'active', '2026-03-30 11:58:17', NULL, 0),
(16, 'Dhaka', 'Chittagong', 'Ena-kha14522', 'Non-AC', '22:00:00', '05:00:00', 700.00, 36, 'All', NULL, NULL, NULL, 'active', '2026-03-30 13:32:26', NULL, 0),
(17, 'Dhaka', 'Chittagong', 'Ena-kha14522', 'Non-AC', '22:00:00', '05:00:00', 700.00, 36, 'All', NULL, NULL, NULL, 'active', '2026-03-30 13:32:27', NULL, 0),
(24, 'Dhaka', 'Chittagong', 'Ena-kha14522', 'Non-AC', '22:00:00', '05:00:00', 700.00, 36, 'All', NULL, NULL, NULL, 'active', '2026-03-30 13:32:28', NULL, 0),
(39, 'Dhaka', 'Sylhet', 'Ena-kha14524', 'Non-AC', '10:00:00', '14:00:00', 800.00, 40, 'All', NULL, NULL, NULL, 'active', '2026-03-30 13:44:42', NULL, 0),
(44, 'Dhaka', 'Cox&#039;s Bazar', 'Green line16332', 'AC', '00:00:00', '07:00:00', 1600.00, 28, 'All', NULL, NULL, NULL, 'active', '2026-03-31 12:06:28', NULL, 0),
(46, 'Dhaka', 'Mymensingh', 'Green line16334', 'Non-AC', '17:00:00', '20:00:00', 500.00, 42, 'All', NULL, NULL, NULL, 'active', '2026-03-31 12:32:32', NULL, 0),
(47, 'Cox&#039;s Bazar', 'Dhaka', 'IMAD212223', 'Non-AC', '15:00:00', '22:00:00', 1100.00, 40, 'All', NULL, NULL, NULL, 'active', '2026-04-01 03:32:19', NULL, 0),
(56, 'Chittagong', 'Dhaka', 'Ena-kha14521', 'AC', '12:00:00', '17:00:00', 700.00, 40, 'All', NULL, NULL, NULL, 'active', '2026-04-01 04:16:30', NULL, 0),
(57, 'Dhaka', 'Chittagong', 'Hanif-Mo14521', 'AC Business', '17:00:00', '22:00:00', 1000.00, 26, 'All', NULL, NULL, NULL, 'active', '2026-04-04 02:40:18', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','number','boolean','json') DEFAULT 'text',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trending_destinations`
--

CREATE TABLE `trending_destinations` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `from_city` varchar(100) DEFAULT NULL,
  `to_city` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `order_position` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trending_destinations`
--

INSERT INTO `trending_destinations` (`id`, `title`, `description`, `image_path`, `from_city`, `to_city`, `price`, `order_position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sylhet', 'Sylhet town', 'uploads/trending/1774880112_Sylhet-town.jpg', 'Dhaka', 'Sylhet', 800.00, 0, 'active', '2026-03-30 14:15:12', '2026-03-30 14:15:12'),
(3, 'Chittagong', 'Chittagaon city', 'uploads/trending/1774880456_chittagaon.jpg', 'Dhaka', 'Chittagong', 700.00, 0, 'active', '2026-03-30 14:20:56', '2026-03-30 14:20:56'),
(4, 'Cox&#039;s Bazar', 'Cox&#039;s Bazar sea beach', 'uploads/trending/1774959292_bangladesh-cox-bazar.jpg', 'Dhaka', 'Cox&#039;s Bazar', 1200.00, 0, 'active', '2026-03-31 12:14:52', '2026-03-31 12:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default.jpg',
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT 0,
  `phone_verified` tinyint(1) DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `language` varchar(10) DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `name`, `email`, `phone`, `password`, `profile_image`, `address`, `city`, `zip_code`, `status`, `email_verified`, `phone_verified`, `last_login`, `created_at`, `updated_at`, `language`) VALUES
(2, 'USR17747632875179', 'Liyar Ahmed', 'liyar11223344@gmail.com', '01700000000', '$2y$10$eSFIxC/5dlvqyz2B.g3lP.tFaXbmywPMNtOQrTe1.dvupzpEruAdu', '1775357472_481206077_1936564687160531_5001176598488632535_n.jpg', 'Gazipur', 'Dhaka', NULL, 'active', 0, 0, NULL, '2026-03-29 05:48:08', '2026-04-05 02:51:12', 'en'),
(3, 'USR17750082936913', 'Jebin', 'example2@gmail.conm', '01800000000', '$2y$10$ZcJ2n2NiOwoavnU6KXwZBOo0sDCM0Z2CfjiW8oc4sjc//r3xSn46S', '1775525224_hq720.jpg', '', '', NULL, 'active', 0, 0, NULL, '2026-04-01 01:51:33', '2026-04-07 01:27:04', 'en'),
(4, 'USR17750087184464', 'Lamiya Sultana', 'example@gmail.conm', '01800000001', '$2y$10$CRJQ3EByH/52pWYf2h02..dFGfjB36u8g/WAKGGpbicA56xo9N6n6', 'default.jpg', NULL, NULL, NULL, 'active', 0, 0, NULL, '2026-04-01 01:58:38', '2026-04-01 01:58:38', 'en'),
(6, 'USR1775531471399', 'Liyar Ahmed', 'example3@gmail.conm', '01700000004', '$2y$10$to3aRB8KumaOPayGrVOeiOP2W1/Xr8qcyoeiW6ZXOGf4rmwl2aEM6', 'default.jpg', NULL, NULL, NULL, 'active', 0, 0, NULL, '2026-04-07 03:11:11', '2026-04-07 03:11:11', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `action`, `ip_address`, `user_agent`, `details`, `created_at`) VALUES
(1, 'USR17733774105750', 'login', '::1', NULL, NULL, '2026-03-13 04:50:38'),
(2, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 05:49:46'),
(3, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 05:50:12'),
(4, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 05:53:04'),
(5, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 05:53:07'),
(6, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-03-29 05:54:15'),
(7, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 05:54:40'),
(8, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-03-29 05:55:11'),
(9, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 05:55:23'),
(10, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-03-29 06:06:26'),
(11, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 06:06:43'),
(12, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-03-29 06:07:04'),
(13, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-29 06:07:27'),
(14, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-30 12:00:08'),
(15, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-30 12:54:36'),
(16, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-30 13:45:43'),
(17, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-31 01:37:25'),
(18, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-03-31 03:04:08'),
(19, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-31 03:04:30'),
(20, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-03-31 04:09:28'),
(21, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-31 04:17:03'),
(22, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-03-31 11:13:11'),
(23, 'USR17750087184464', 'login', '::1', NULL, NULL, '2026-04-01 01:58:56'),
(24, 'USR17750087184464', 'logout', '::1', NULL, NULL, '2026-04-01 02:00:51'),
(25, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-01 02:02:55'),
(26, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-01 03:24:57'),
(27, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-01 03:25:14'),
(28, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-01 03:25:28'),
(29, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-01 03:26:40'),
(30, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-01 03:26:48'),
(31, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-01 04:21:54'),
(32, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-01 05:01:09'),
(33, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-01 05:55:05'),
(34, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-02 04:50:15'),
(35, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-03 13:52:58'),
(36, 'USR17750082936913', 'logout', '::1', NULL, NULL, '2026-04-03 14:46:24'),
(37, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-03 15:05:06'),
(38, 'USR17750082936913', 'logout', '::1', NULL, NULL, '2026-04-03 15:05:15'),
(39, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-03 15:09:35'),
(40, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-04 02:33:57'),
(41, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-04 02:36:22'),
(42, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-04 02:54:54'),
(43, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-04 03:36:06'),
(44, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-04 05:56:48'),
(45, 'USR17750082936913', 'logout', '::1', NULL, NULL, '2026-04-04 05:57:09'),
(46, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-05 02:42:10'),
(47, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-05 12:25:14'),
(48, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-07 00:53:50'),
(49, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-07 01:15:48'),
(50, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-07 01:16:37'),
(51, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-07 01:22:45'),
(52, 'USR17750082936913', 'login', '::1', NULL, NULL, '2026-04-07 01:25:26'),
(53, 'USR17750082936913', 'logout', '::1', NULL, NULL, '2026-04-07 02:29:51'),
(54, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-07 02:31:43'),
(55, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-07 03:08:41'),
(56, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-07 03:09:53'),
(57, 'USR17747632875179', 'logout', '::1', NULL, NULL, '2026-04-07 03:10:07'),
(58, 'USR1775531471399', 'login', '::1', NULL, NULL, '2026-04-07 03:11:30'),
(59, 'USR17747632875179', 'login', '::1', NULL, NULL, '2026-04-10 13:25:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_journey_date` (`journey_date`);

--
-- Indexes for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_read` (`user_id`,`is_read`);

--
-- Indexes for table `passengers`
--
ALTER TABLE `passengers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_route` (`from_city`,`to_city`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `trending_destinations`
--
ALTER TABLE `trending_destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_action` (`user_id`,`action`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `cancellations`
--
ALTER TABLE `cancellations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passengers`
--
ALTER TABLE `passengers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trending_destinations`
--
ALTER TABLE `trending_destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD CONSTRAINT `cancellations_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `passengers`
--
ALTER TABLE `passengers`
  ADD CONSTRAINT `passengers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD CONSTRAINT `refund_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 01:22 AM
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
-- Database: `fcsit_kiosk`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `email`, `created_at`) VALUES
(1, '', '', NULL, '2025-06-17 20:01:58'),
(2, 'admin', '$2y$10$NQsFSIXl1upsEFbMhhLO6uL.AnUxTUmE3wRi2JCWeICBj5hdypGnC', 'admin@zombeat.com', '2025-06-17 21:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `hourly_rate` decimal(5,2) DEFAULT NULL,
  `attendance_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `user_id`, `schedule_id`, `hourly_rate`, `attendance_status`) VALUES
(5, 2, 1, 10.00, 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'John Employee', 'john.employee@example.com', '$$2y$10$Jcyxp0DbEBx2Azm7i3mWT.kTUPTKss0FrzEAj9Yo2pvnnCJUW1.Xa', '2025-06-18 17:11:58'),
(3, 'Sam Employee', 'Sam.employee@example.com', '$2y$10$gTqb/yHlmmPpNPOO5B8fP.z4beWALNN6tWWKh/JPzPI8BPr2RVAia', '2025-06-18 17:30:59');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `rating`, `feedback`, `created_at`) VALUES
(1, 11, 5, 'Excellent service!', '2025-06-18 20:59:23'),
(2, 11, 5, 'Fast delivery!', '2025-06-18 21:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `financial_report`
--

CREATE TABLE `financial_report` (
  `report_id` int(11) NOT NULL,
  `report_date` date DEFAULT NULL,
  `total_sales` decimal(12,2) DEFAULT NULL,
  `total_orders` int(11) DEFAULT NULL,
  `total_profit` decimal(12,2) DEFAULT NULL,
  `generated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `financial_report`
--

INSERT INTO `financial_report` (`report_id`, `report_date`, `total_sales`, `total_orders`, `total_profit`, `generated_by`) VALUES
(3, '2025-06-18', 55.00, 10, 22.00, 1),
(4, '2025-06-11', 310.00, 45, 125.00, 1),
(5, '2025-05-19', 910.00, 132, 400.00, 1),
(6, '2025-06-17', 85.50, 14, 38.00, 1),
(7, '2025-06-03', 465.00, 63, 180.00, 1),
(8, '2025-06-16', 109.50, 15, 40.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `tracking_status` enum('Pending','Preparing','Out for Delivery','Complete','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `processed_by`, `order_date`, `status`, `total_amount`, `payment_method`, `tracking_status`) VALUES
(6, 2, NULL, '2025-06-18 02:10:08', 'Completed', 7.00, 'Cash', ''),
(7, 3, NULL, '2025-06-16 02:10:08', 'Completed', 10.00, 'Cash', ''),
(8, 6, NULL, '2025-06-13 02:10:08', 'Completed', 9.50, 'Card', ''),
(9, 7, NULL, '2025-06-08 02:10:08', 'Completed', 13.50, 'Cash', ''),
(11, 3, NULL, '2025-05-29 02:24:44', 'Completed', 8.00, 'Card', ''),
(42, 2, NULL, '2025-06-01 00:00:00', 'Completed', 9.50, 'Cash', ''),
(43, 3, NULL, '2025-06-02 00:00:00', 'Completed', 7.00, 'Card', ''),
(44, 4, NULL, '2025-06-03 00:00:00', 'Completed', 11.00, 'Cash', ''),
(45, 6, NULL, '2025-06-04 00:00:00', 'Completed', 6.00, 'Cash', ''),
(46, 7, NULL, '2025-06-05 00:00:00', 'Completed', 7.00, 'Card', ''),
(47, 8, NULL, '2025-06-06 00:00:00', 'Completed', 5.50, 'Cash', ''),
(48, 9, NULL, '2025-06-07 00:00:00', 'Completed', 9.50, 'Cash', ''),
(49, 10, NULL, '2025-06-08 00:00:00', 'Completed', 8.00, 'Card', ''),
(50, 11, NULL, '2025-06-09 00:00:00', 'Completed', 6.00, 'Cash', ''),
(51, 2, NULL, '2025-06-10 00:00:00', 'Completed', 6.50, 'Card', ''),
(52, 3, NULL, '2025-06-11 00:00:00', 'Completed', 4.00, 'Cash', ''),
(53, 4, NULL, '2025-06-12 00:00:00', 'Completed', 8.00, 'Card', ''),
(54, 6, NULL, '2025-06-13 00:00:00', 'Completed', 9.00, 'Cash', ''),
(55, 7, NULL, '2025-06-14 00:00:00', 'Completed', 6.50, 'Cash', ''),
(56, 8, NULL, '2025-06-15 00:00:00', 'Completed', 10.00, 'Card', ''),
(57, 2, NULL, '2025-06-10 00:00:00', 'Completed', 9.00, 'Cash', ''),
(58, 3, NULL, '2025-06-11 00:00:00', 'Completed', 8.00, 'Card', ''),
(59, 4, NULL, '2025-06-12 00:00:00', 'Completed', 6.50, 'Cash', ''),
(60, 6, NULL, '2025-06-13 00:00:00', 'Completed', 10.00, 'Card', ''),
(61, 7, NULL, '2025-06-14 00:00:00', 'Completed', 7.50, 'Cash', ''),
(62, 8, NULL, '2025-06-16 00:00:00', 'Completed', 8.00, 'Cash', ''),
(63, 9, NULL, '2025-06-16 00:00:00', 'Completed', 6.00, 'Card', ''),
(64, 10, NULL, '2025-06-16 00:00:00', 'Completed', 9.50, 'Cash', ''),
(65, 11, NULL, '2025-06-16 00:00:00', 'Completed', 7.00, 'Card', ''),
(66, 2, NULL, '2025-06-16 00:00:00', 'Completed', 10.50, 'Cash', ''),
(67, 2, NULL, '2025-06-18 00:00:00', 'Completed', 9.00, 'Cash', ''),
(68, 3, NULL, '2025-06-18 00:00:00', 'Completed', 7.00, 'Card', ''),
(69, 4, NULL, '2025-06-18 00:00:00', 'Completed', 6.50, 'Cash', ''),
(70, 6, NULL, '2025-06-18 00:00:00', 'Completed', 8.00, 'Cash', ''),
(71, 7, NULL, '2025-06-18 00:00:00', 'Completed', 9.50, 'Card', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `product_id`, `quantity`, `subtotal`) VALUES
(32, 42, 29, 1, 6.00),
(33, 42, 11, 1, 2.50),
(34, 42, 34, 1, 1.00),
(35, 43, 30, 1, 6.00),
(36, 43, 35, 1, 1.00),
(39, 44, 31, 1, 4.50),
(40, 44, 12, 1, 2.50),
(41, 44, 33, 1, 4.00),
(42, 45, 13, 1, 3.50),
(43, 45, 11, 1, 2.50),
(44, 46, 30, 1, 6.00),
(45, 46, 36, 1, 1.00),
(46, 47, 32, 1, 3.00),
(47, 47, 15, 1, 2.50),
(48, 48, 18, 1, 5.50),
(49, 48, 12, 1, 2.50),
(50, 48, 11, 1, 1.50),
(51, 49, 13, 1, 3.50),
(52, 49, 19, 1, 2.50),
(53, 49, 11, 1, 2.00),
(54, 50, 29, 1, 6.00),
(55, 51, 17, 1, 3.50),
(56, 51, 11, 1, 3.00),
(57, 52, 33, 1, 4.00),
(58, 53, 31, 1, 4.50),
(59, 53, 11, 1, 2.50),
(60, 53, 35, 1, 1.00),
(61, 54, 30, 1, 6.00),
(62, 54, 37, 1, 2.00),
(63, 55, 18, 1, 5.50),
(64, 55, 11, 1, 1.00),
(65, 56, 29, 1, 6.00),
(66, 56, 11, 1, 2.50),
(67, 56, 34, 1, 1.50),
(68, 57, 29, 1, 6.00),
(69, 57, 11, 1, 2.50),
(70, 57, 34, 1, 0.50),
(71, 58, 30, 1, 6.00),
(72, 58, 35, 1, 2.00),
(73, 59, 31, 1, 4.50),
(74, 59, 36, 1, 2.00),
(75, 60, 32, 1, 3.00),
(76, 60, 15, 1, 3.00),
(77, 60, 34, 1, 4.00),
(78, 61, 18, 1, 5.50),
(79, 61, 12, 1, 2.00),
(80, 62, 33, 1, 4.00),
(81, 62, 11, 1, 2.50),
(82, 62, 35, 1, 1.50),
(83, 63, 14, 1, 3.50),
(84, 63, 12, 1, 2.50),
(85, 64, 13, 1, 3.50),
(86, 64, 19, 1, 2.50),
(87, 64, 34, 1, 3.50),
(88, 65, 37, 1, 2.50),
(89, 65, 29, 1, 4.50),
(90, 66, 29, 1, 6.00),
(91, 66, 36, 1, 2.50),
(92, 66, 11, 1, 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `description`, `price`, `category`, `stock_quantity`, `image_url`, `is_active`) VALUES
(11, 'Air Mineral', 'Bottled mineral water', 2.50, 'Drinks', NULL, 'img/drinkingwater.jpg', 1),
(12, 'Dutch Lady Strawberry Milk', 'Chilled strawberry milk drink', 2.50, 'Drinks', NULL, 'img/strawberrymilk.jpeg', 1),
(13, 'Bubur Ayam', 'Savory chicken rice porridge', 3.50, 'Heavy Foods', NULL, 'img/BuburAyam2.png', 1),
(14, 'Seri Muka', 'Glutinous rice with pandan custard', 3.50, 'Snacks', NULL, 'img/SeriMuka.png', 1),
(15, 'Sandwich Roll Egg', 'Egg sandwich roll', 3.00, 'Snacks', NULL, 'img/SandwichRoleEgg.png', 1),
(16, 'Pavlova', 'Meringue with cream & fruit', 4.00, 'Snacks', NULL, 'img/Pavlova.png', 1),
(17, 'Kuih Lapis', 'Colorful layered kuih', 3.50, 'Snacks', NULL, 'img/KuihLapis2.png', 1),
(18, 'Chicken Wrap', 'Wrap with chicken & veggies', 5.50, 'Heavy Foods', NULL, 'img/ChickenWrap.png', 1),
(19, 'Kuih Muih Campur', 'Mixed traditional kuih', 2.50, 'Snacks', NULL, 'img/KuihMuihCampur.png', 1),
(29, 'Nasi Lemak Ayam', 'Coconut rice with sambal', 6.00, 'Heavy Foods', NULL, 'img/NasiLemakAyam.png', 1),
(30, 'Mee Jawa', 'Javanese noodle in gravy', 6.00, 'Heavy Foods', NULL, 'img/MeeJawa.png', 1),
(31, 'Loaded Fries', 'Fries with cheese and sauce', 4.50, 'Snacks', NULL, 'img/LoadedFries.png', 1),
(32, 'Wantan Goreng', 'Crispy fried dumplings', 3.00, 'Snacks', NULL, 'img/WantanGoreng.png', 1),
(33, 'Bergedil Daging', 'Fried mashed meat potato', 4.00, 'Snacks', NULL, 'img/BergedilDaging.png', 1),
(34, 'Ice Cream Soda', 'Canned Drinks', 2.50, 'Drinks', NULL, 'img/IceCreamSoda.jpeg', 1),
(35, 'Strawberry', 'Canned Drinks', 2.50, 'Drinks', NULL, 'img/Strawberry.jpg', 1),
(36, 'Orange', 'Canned Drinks', 2.50, 'Drinks', NULL, 'img/Orange.png', 1),
(37, 'Chrysanthemum Tea', 'Canned Drinks', 2.50, 'Drinks', NULL, 'img/TehBunga.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `report_order`
--

CREATE TABLE `report_order` (
  `report_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_order`
--

INSERT INTO `report_order` (`report_id`, `order_id`) VALUES
(6, 42),
(6, 43),
(6, 44),
(6, 45),
(6, 46),
(6, 47),
(6, 48),
(6, 49),
(6, 50),
(6, 51),
(6, 52),
(6, 53),
(6, 54),
(6, 55),
(6, 56);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `schedule_id` int(11) NOT NULL,
  `shift_date` date DEFAULT NULL,
  `shift_time_start` time DEFAULT NULL,
  `shift_time_end` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`schedule_id`, `shift_date`, `shift_time_start`, `shift_time_end`) VALUES
(1, '2025-06-05', '08:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` enum('public','registered','employee','admin') DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `full_name`, `email`, `password`, `user_type`, `registration_date`) VALUES
(2, 'Nurul Izzati', 'nizzati006@gmail.com', '12345678', 'registered', '2025-06-10 00:02:38'),
(3, 'Hana Iris', 'hana@gmail.com', '246810', 'employee', '2025-06-10 00:38:37'),
(4, '', 'rania_iris@gmail.com', '$2y$10$FIDkJtHr8iHk.LSIpzVpuuVeqm0kiMB9C2HzPgdRMPCLA8e7Ge7t2', 'registered', '2025-06-09 20:22:24'),
(6, 'Sely Kim', 'sely@gmail.com', '$2y$10$Lb2f/l1yar8esUl/.UgWDuEUZUKIn2GBZvBoenMMwKPS4RfuDl1Ia', 'registered', '2025-06-09 20:35:53'),
(7, 'Aqilah Jolihi', 'aqij204@gmail.com', '$2y$10$JXV2UD5mjOb9sV5Uf4RHJuHZ20XAuknjix0v5BIUbfER525DB4BKq', 'registered', '2025-06-09 20:44:36'),
(8, '', '', '$2y$10$pEHu8FRBk1JbfkCZqtgl9upQ7KwdPOh8oBCpqDf4gNjPZpb9KkXmW', 'registered', '2025-06-10 07:25:58'),
(9, 'Eizlyn Ismail', 'eizlynlyn@gmail.com', '$2y$10$fyMhUED6VF5d2euneGjMw.TdsWH7iB4.p5CU9dzJOV/KSxUgyv6HG', 'registered', '2025-06-10 09:06:20'),
(10, 'Nurul Hazwani', 'nurulhazwaninh06@gmail.com', '$2y$10$UuzF.TyIIaznIft2WC1TZO3.1wRH62HzbHkHOjkF.kerwJmZ9GuiC', NULL, NULL),
(11, 'Jennie Kim', 'jennie@example.com', '$2y$10$WHWI7emNWnICdRK6IWrhEO5igIl.jAJsDOfWszQmNcUE1V.TwyBWa', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `financial_report`
--
ALTER TABLE `financial_report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_generated_by` (`generated_by`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `report_order`
--
ALTER TABLE `report_order`
  ADD PRIMARY KEY (`report_id`,`order_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `financial_report`
--
ALTER TABLE `financial_report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`schedule_id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `financial_report`
--
ALTER TABLE `financial_report`
  ADD CONSTRAINT `fk_generated_by` FOREIGN KEY (`generated_by`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`processed_by`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `report_order`
--
ALTER TABLE `report_order`
  ADD CONSTRAINT `report_order_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `financial_report` (`report_id`),
  ADD CONSTRAINT `report_order_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

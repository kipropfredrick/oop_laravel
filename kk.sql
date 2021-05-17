-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2021 at 08:59 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acme`
--

-- --------------------------------------------------------

--
-- Table structure for table `paymentconfigurations`
--

CREATE TABLE `paymentconfigurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paymentconfigurations`
--

INSERT INTO `paymentconfigurations` (`id`, `name`, `cost`, `created_at`, `updated_at`, `fullname`) VALUES
(1, 'words_per_page', '276', '2021-05-11 19:01:34', '2021-05-12 12:39:05', 'Words per page'),
(2, 'price_per_page', '10', '2021-05-12 05:08:25', '2021-05-12 13:02:52', 'price per page'),
(3, '12hr_price_per_page', '12', '2021-05-12 05:08:51', '2021-05-12 12:45:49', '12hr price per page'),
(4, 'power_point_price_per_page', '5', '2021-05-12 05:09:14', '2021-05-12 12:46:03', 'power point price per page'),
(5, 'extra_cost', '5', '2021-05-12 05:09:38', '2021-05-12 12:46:07', 'extra cost'),
(6, 'late_delivery_fee', '5', '2021-05-12 05:09:54', '2021-05-12 12:46:11', 'late delivery fee'),
(7, 'writer_commission', '0', '2021-05-12 05:10:15', '2021-05-12 05:10:15', 'writer commission'),
(8, 'cancellation_fee', '5', '2021-05-12 05:10:54', '2021-05-12 12:46:14', 'cancellation fee'),
(9, 'revision_cost', '5', '2021-05-12 05:11:15', '2021-05-12 12:46:35', 'revision_cost'),
(10, 'discount_pages_minimum', '5', '2021-05-12 05:11:36', '2021-05-12 12:46:38', 'dicscount pages minimum'),
(11, 'discount_pages_cost', '5', '2021-05-12 05:11:59', '2021-05-12 12:46:41', 'discount pages cost'),
(12, 'referal', '5', '2021-05-12 05:12:19', '2021-05-12 12:46:44', 'referal'),
(13, 'currency', 'USD', '2021-05-12 05:12:45', '2021-05-12 05:12:45', 'Currency');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `paymentconfigurations`
--
ALTER TABLE `paymentconfigurations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `paymentconfigurations`
--
ALTER TABLE `paymentconfigurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

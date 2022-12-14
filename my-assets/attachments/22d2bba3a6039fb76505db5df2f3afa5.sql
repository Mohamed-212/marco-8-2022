-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 02, 2022 at 02:24 AM
-- Server version: 5.7.38-cll-lve
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kop-ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `item_extras` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_withouts` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dough_type_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dough_type_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double NOT NULL,
  `offer_price` double DEFAULT NULL,
  `offer_id` bigint(20) DEFAULT NULL,
  `offer_last_updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pure_price` double DEFAULT NULL,
  `dough_type_2_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dough_type_2_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `item_id`, `quantity`, `item_extras`, `item_withouts`, `dough_type_ar`, `dough_type_en`, `price`, `offer_price`, `offer_id`, `offer_last_updated_at`, `deleted_at`, `created_at`, `updated_at`, `pure_price`, `dough_type_2_ar`, `dough_type_2_en`) VALUES
(1, 1, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(2, 1, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(3, 1, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(4, 2, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(5, 3, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(6, 3, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(7, 4, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(8, 4, 2, 1, '2, 1', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(9, 5, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(10, 5, 2, 1, '2, 1', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(11, 6, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(12, 6, 2, 1, '2, 1', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(13, 7, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(14, 7, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(15, 8, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(16, 8, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(17, 9, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(18, 9, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(19, 10, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(20, 10, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(21, 11, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(22, 11, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(23, 12, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(24, 12, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(25, 13, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(26, 14, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(27, 14, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(28, 15, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(29, 15, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(30, 16, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(31, 17, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(32, 18, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(33, 19, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(34, 19, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(35, 19, 8, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(36, 20, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(37, 20, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(38, 21, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(39, 22, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(40, 23, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(41, 24, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(42, 25, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(43, 26, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(44, 27, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(45, 28, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(46, 29, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(47, 30, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(48, 31, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(49, 31, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(50, 31, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(51, 32, 2, 1, '', '', NULL, NULL, 2.3, 1.5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(52, 32, 2, 1, '2, 1', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(53, 33, 2, 1, '2', '', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(54, 33, 2, 1, '', '', NULL, NULL, 2.3, 1.5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(55, 34, 2, 1, '[null]', '[null]', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(56, 34, 2, 1, '[null,null]', '[null]', 'n', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(57, 35, 2, 1, '[null]', '[null]', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(58, 35, 2, 1, '[null,null]', '[null]', 'n', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(59, 36, 2, 1, '2', '1', 'normal', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(60, 37, 20, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(61, 37, 23, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(62, 38, 20, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(63, 38, 23, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(64, 38, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(65, 38, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(66, 38, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(67, 38, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(68, 38, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(69, 39, 20, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(70, 39, 23, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(71, 39, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(72, 39, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(73, 39, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(74, 39, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(75, 39, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(76, 39, 110, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(77, 40, 20, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(78, 40, 23, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(79, 40, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(80, 40, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(81, 40, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(82, 40, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(83, 40, 27, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(84, 40, 110, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(85, 41, 9, 1, '', '', '', 't', 12, 13.25, 15, '2022-08-31 16:30:18', NULL, NULL, NULL, 12, NULL, NULL),
(86, 42, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(87, 42, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(88, 42, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(89, 42, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(90, 42, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(91, 42, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(92, 43, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(93, 43, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(94, 43, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(95, 43, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(96, 43, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(97, 43, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(98, 44, 5, 1, '', '', NULL, NULL, 4.6, 0, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(99, 44, 6, 1, '', '', NULL, NULL, 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(100, 44, 7, 1, '', '', NULL, NULL, 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(101, 44, 5, 1, '', '', NULL, NULL, 4.6, -4.6, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(102, 44, 6, 1, '', '', NULL, NULL, 8.05, -8.05, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(103, 44, 7, 1, '', '', NULL, NULL, 8.05, -8.05, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(104, 45, 5, 1, '', '', NULL, NULL, 4.6, 0, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(105, 45, 6, 1, '', '', NULL, NULL, 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(106, 45, 7, 1, '', '', NULL, NULL, 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(107, 45, 5, 1, '', '', NULL, NULL, 4.6, -4.6, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(108, 45, 6, 1, '', '', NULL, NULL, 8.05, -8.05, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(109, 45, 7, 1, '', '', NULL, NULL, 8.05, -8.05, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(110, 46, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(111, 46, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(112, 46, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(113, 46, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(114, 46, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(115, 46, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(116, 47, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(117, 47, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(118, 47, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(119, 47, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(120, 47, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(121, 47, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(122, 48, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(123, 48, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(124, 48, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(125, 48, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(126, 48, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(127, 48, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(128, 49, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(129, 49, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(130, 49, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(131, 49, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(132, 49, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(133, 49, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(134, 50, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(135, 50, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(136, 50, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(137, 50, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(138, 50, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(139, 50, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(140, 51, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(141, 51, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(142, 51, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(143, 51, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(144, 51, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(145, 51, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(146, 52, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(147, 52, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(148, 52, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(149, 52, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(150, 52, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(151, 52, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(152, 53, 78, 1, '', '', NULL, NULL, 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(153, 53, 79, 1, '', '', NULL, NULL, 13.8, 0, NULL, NULL, NULL, NULL, NULL, 13.8, NULL, NULL),
(154, 53, 80, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(155, 53, 82, 1, '', '', NULL, NULL, 19.55, 0, NULL, NULL, NULL, NULL, NULL, 19.55, NULL, NULL),
(156, 53, 90, 1, '', '', NULL, NULL, 20.7, -20.7, NULL, NULL, NULL, NULL, NULL, 20.7, NULL, NULL),
(157, 53, 96, 1, '', '', NULL, NULL, 21.85, -21.85, NULL, NULL, NULL, NULL, NULL, 21.85, NULL, NULL),
(158, 54, 2, 1, '', '', NULL, NULL, 2.3, 1.5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(159, 55, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(160, 56, 5, 1, '', '', NULL, NULL, 4.6, 0, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(161, 56, 6, 1, '', '', NULL, NULL, 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(162, 56, 7, 1, '', '', NULL, NULL, 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(163, 56, 5, 1, '', '', NULL, NULL, 4.6, -4.6, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(164, 56, 6, 1, '', '', NULL, NULL, 8.05, -8.05, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(165, 56, 7, 1, '', '', NULL, NULL, 8.05, -8.05, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(166, 56, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(167, 57, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(168, 57, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(169, 57, 2, 1, '', '', 'normal', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(170, 57, 2, 1, '2, 3', '', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(171, 58, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(172, 59, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(173, 60, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(174, 61, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(175, 62, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(176, 63, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(177, 64, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(178, 64, 2, 1, '2, 3', '2', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(179, 64, 2, 1, '2, 3', '2', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(180, 64, 2, 1, '2, 3', '1', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(181, 64, 2, 1, '2, 3', '2', 'normal', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(182, 65, 2, 1, '', '', 'Borr', 'Borr', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(183, 66, 2, 1, '', '', '????', 'Borr', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(184, 67, 2, 1, '', '', '????', 'Borr', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(185, 68, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(186, 68, 2, 1, '', '', '????', 'Borr', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(187, 69, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(188, 70, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(189, 71, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(190, 72, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(191, 72, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(192, 73, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(193, 74, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(194, 75, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(195, 76, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(196, 77, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(197, 78, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(198, 79, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(199, 80, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(200, 81, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(201, 82, 8, 1, '', '', '????????', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, '??????????', 'thin'),
(202, 83, 8, 1, '', '', '????????', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, '??????????', 'thin'),
(203, 84, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(204, 84, 78, 1, '', '', '????', 'Borr', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'Thick'),
(205, 85, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(206, 86, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(207, 87, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(208, 88, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(209, 89, 2, 1, '', '', '????', 'Borr', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(210, 90, 2, 1, '', '', '????', 'Borr', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(211, 90, 2, 1, '', '', '????', 'Borr', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(212, 91, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(213, 92, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(214, 93, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(215, 93, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(216, 94, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(217, 95, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(218, 96, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(219, 97, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(220, 98, 8, 1, '', '', '????????', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(221, 99, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(222, 100, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(223, 100, 98, 1, '', '', 'normal', 'normal', 24.15, 0, NULL, NULL, NULL, NULL, NULL, 24.15, NULL, NULL),
(224, 101, 29, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(225, 102, 5, 1, '', '', 'normal', 'normal', 4.6, 0, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(226, 102, 34, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(227, 102, 36, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(228, 102, 106, 1, '', '', 'normal', 'normal', 3.45, 0, NULL, NULL, NULL, NULL, NULL, 3.45, NULL, NULL),
(229, 103, 91, 1, '', '', '????????', 'normal', 16.1, 0, NULL, NULL, NULL, NULL, NULL, 16.1, NULL, NULL),
(230, 104, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(231, 105, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(232, 106, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(233, 107, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(234, 108, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(235, 109, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(236, 110, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(237, 111, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(238, 112, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(239, 112, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, NULL, NULL),
(240, 113, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(241, 114, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, '??????????', 'thin'),
(242, 115, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(243, 115, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(244, 116, 78, 1, '', '', '????????', 'normal', 18.4, 0, NULL, NULL, NULL, NULL, NULL, 18.4, '??????????', 'thin'),
(245, 117, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(246, 118, 2, 1, '2, 3', '2, 3', '????????', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(247, 118, 2, 1, '', '', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(248, 118, 2, 1, '2, 1', '', '????????', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(249, 119, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(250, 120, 2, 1, '[null,null]', '[null,null]', '', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(251, 120, 2, 1, '[null]', '[null]', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(252, 120, 2, 1, '[null,null]', '[null]', '', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(253, 121, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(254, 122, 2, 1, '2, 1', '', '????????', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(255, 123, 2, 1, '[null,null]', '[null,null]', '', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(256, 123, 2, 1, '[null]', '[null]', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(257, 123, 2, 1, '[null,null]', '[null]', '', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(258, 124, 2, 1, '[null,null]', '[null,null]', '', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(259, 124, 2, 1, '[null]', '[null]', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(260, 124, 2, 1, '[null,null]', '[null]', '', 'n', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(261, 125, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(262, 126, 2, 1, '[null]', '[null]', '', 'n', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(263, 127, 2, 1, '[null]', '[null]', '', 'n', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(264, 128, 2, 1, '2', '', '????????', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(265, 129, 100, 1, '', '', 'normal', 'normal', 75.9, 0, NULL, NULL, NULL, NULL, NULL, 75.9, NULL, NULL),
(266, 130, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(267, 131, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(268, 131, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(269, 131, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(270, 131, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(271, 131, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(272, 132, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(273, 132, 8, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(274, 133, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(275, 133, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(276, 133, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(277, 133, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(278, 133, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(279, 134, 8, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(280, 134, 9, 1, '', '', 'normal', 'normal', 12, 0, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL),
(281, 134, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(282, 135, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(283, 135, 3, 1, '', '', NULL, NULL, 5.75, 2.88, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(284, 135, 8, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(285, 136, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(286, 136, 3, 1, '', '', NULL, NULL, 5.75, 2.88, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(287, 136, 8, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(288, 137, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(289, 137, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(290, 137, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(291, 137, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(292, 137, 19, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(293, 137, 29, 1, '', '', 'normal', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(294, 138, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(295, 138, 3, 1, '', '', NULL, NULL, 5.75, 2.88, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(296, 138, 8, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(297, 139, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(298, 140, 109, 1, '', '', '????????', 'normal', 8.05, 0, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(299, 140, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(300, 141, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(301, 141, 9, 1, '', '', '????????', 'normal', 12, 0, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL),
(302, 142, 8, 1, '', '', '????????', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(303, 143, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(304, 143, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(305, 144, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(306, 144, 5, 1, '', '', NULL, NULL, 4.6, -4.6, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(307, 145, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(308, 145, 5, 1, '', '', NULL, NULL, 4.6, -4.6, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(309, 146, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(310, 146, 5, 1, '', '', NULL, NULL, 4.6, -4.6, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(311, 147, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(312, 147, 5, 1, '', '', NULL, NULL, 4.6, -4.6, NULL, NULL, NULL, NULL, NULL, 4.6, NULL, NULL),
(313, 148, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(314, 148, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(315, 149, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(316, 149, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(317, 149, 3, 1, '1, 3', '', '????????', 'normal', 55.75, 50, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(318, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(319, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(320, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(321, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(322, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(323, 149, 3, 1, '2, 1, 3', '', '????????', 'normal', 60.75, 55, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(324, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(325, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(326, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(327, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(328, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(329, 149, 3, 1, '1, 2', '', '????????', 'normal', 35.75, 30, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(330, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(331, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(332, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(333, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(334, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(335, 149, 3, 1, '', '', '????????', 'normal', 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(336, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(337, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(338, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(339, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(340, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(341, 149, 3, 1, '', '', '????????', 'normal', 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(342, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(343, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(344, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(345, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(346, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(347, 149, 3, 1, '', '', '????????', 'normal', 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(348, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(349, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(350, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(351, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(352, 149, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(353, 150, 8, 1, '', '', 'normal', 'normal', 6.9, 0, NULL, NULL, NULL, NULL, NULL, 6.9, NULL, NULL),
(354, 151, 2, 1, '', '', '????????', 'normal', 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(355, 151, 2, 1, '2', '', '????????', 'normal', 7.3, 5, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(356, 151, 2, 1, '', '', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(357, 151, 2, 1, '2, 3', '', '????????', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(358, 151, 2, 1, '', '', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(359, 151, 3, 1, '', '', NULL, NULL, 5.75, 0, NULL, NULL, NULL, NULL, NULL, 5.75, NULL, NULL),
(360, 151, 7, 1, '', '', NULL, NULL, 8.05, -8.05, NULL, NULL, NULL, NULL, NULL, 8.05, NULL, NULL),
(361, 151, 2, 1, '2, 3', '', '????????', 'normal', 32.3, 30, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(362, 151, 2, 1, '', '', NULL, NULL, 2.3, 0, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL),
(363, 152, 9, 1, '', '', '????????', 'noraml', 12, 0, NULL, NULL, NULL, NULL, NULL, 12, '??????????', 'thick'),
(364, 152, 9, 1, '1, 2, 3', '3, 4', '????????', 'noraml', 67, 55, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL),
(365, 152, 9, 1, '2', '1', '????????', 'noraml', 17, 5, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL),
(366, 153, 2, 1, '', '', NULL, NULL, 2.3, 1.15, NULL, NULL, NULL, NULL, NULL, 2.3, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

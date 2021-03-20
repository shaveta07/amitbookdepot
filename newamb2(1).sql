-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 17, 2020 at 01:23 PM
-- Server version: 5.7.30-0ubuntu0.16.04.1
-- PHP Version: 7.2.31-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newamb2`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `unique_identifier` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `activated` int(1) NOT NULL DEFAULT '1',
  `image` varchar(1000) COLLATE utf32_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `name`, `unique_identifier`, `version`, `activated`, `image`, `created_at`, `updated_at`) VALUES
(10, 'refund', 'refund_request', '1.0', 1, 'refund_request.png', '2020-07-03 05:23:43', '2020-07-03 05:23:43'),
(11, 'affiliate', 'affiliate_system', '1.2', 1, 'affiliate_banner.jpg', '2020-07-03 05:24:26', '2020-07-03 05:24:26'),
(12, 'OTP', 'otp_system', '1.2', 1, 'otp_system.jpg', '2020-07-03 05:24:50', '2020-07-03 05:24:50'),
(13, 'Paytm', 'paytm', '1.0', 1, 'paytm.png', '2020-07-03 05:28:04', '2020-07-03 05:28:04'),
(14, 'Offline Payment', 'offline_payment', '1.1', 1, 'offline_banner.jpg', '2020-07-03 05:28:16', '2020-07-03 05:28:16');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `set_default` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `address`, `country`, `city`, `postal_code`, `phone`, `set_default`, `created_at`, `updated_at`) VALUES
(1, 8, 'chandigarh', 'India', 'Chandigarh', '23121', '7696866526', 0, '2020-07-12 10:48:42', '2020-07-12 10:48:42'),
(2, 27, 'chandigarh', 'Afghanistan', 'Chandigarh', '23121', '7696866526', 0, '2020-07-15 01:21:51', '2020-07-15 01:21:51');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_configs`
--

CREATE TABLE `affiliate_configs` (
  `id` int(11) NOT NULL,
  `type` varchar(1000) COLLATE utf32_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf32_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `affiliate_configs`
--

INSERT INTO `affiliate_configs` (`id`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'verification_form', '[{\"type\":\"text\",\"label\":\"Your name\"},{\"type\":\"text\",\"label\":\"Email\"},{\"type\":\"text\",\"label\":\"Full Address\"},{\"type\":\"text\",\"label\":\"Phone Number\"},{\"type\":\"text\",\"label\":\"How will you affiliate?\"}]', '2020-03-09 09:56:21', '2020-03-09 04:30:59');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_options`
--

CREATE TABLE `affiliate_options` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `details` longtext COLLATE utf32_unicode_ci,
  `percentage` double NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `affiliate_options`
--

INSERT INTO `affiliate_options` (`id`, `type`, `details`, `percentage`, `status`, `created_at`, `updated_at`) VALUES
(2, 'user_registration_first_purchase', NULL, 20, 1, '2020-03-03 05:08:37', '2020-03-05 03:56:30'),
(3, 'product_sharing', NULL, 20, 0, '2020-03-08 01:55:03', '2020-03-10 02:12:32'),
(4, 'category_wise_affiliate', NULL, 0, 0, '2020-03-08 01:55:03', '2020-03-10 02:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_payments`
--

CREATE TABLE `affiliate_payments` (
  `id` int(11) NOT NULL,
  `affiliate_user_id` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_details` longtext COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `affiliate_payments`
--

INSERT INTO `affiliate_payments` (`id`, `affiliate_user_id`, `amount`, `payment_method`, `payment_details`, `created_at`, `updated_at`) VALUES
(2, 1, 20.00, 'Paypal', NULL, '2020-03-10 02:04:30', '2020-03-10 02:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_users`
--

CREATE TABLE `affiliate_users` (
  `id` int(11) NOT NULL,
  `paypal_email` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `bank_information` text COLLATE utf32_unicode_ci,
  `user_id` int(11) NOT NULL,
  `informations` text COLLATE utf32_unicode_ci,
  `balance` double(10,2) NOT NULL DEFAULT '0.00',
  `status` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `affiliate_users`
--

INSERT INTO `affiliate_users` (`id`, `paypal_email`, `bank_information`, `user_id`, `informations`, `balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 'demo@gmail.com', '123456', 8, '[{\"type\":\"text\",\"label\":\"Your name\",\"value\":\"Nostrum dicta sint l\"},{\"type\":\"text\",\"label\":\"Email\",\"value\":\"Aut perferendis null\"},{\"type\":\"text\",\"label\":\"Full Address\",\"value\":\"Voluptatem Sit dolo\"},{\"type\":\"text\",\"label\":\"Phone Number\",\"value\":\"Ut ad beatae occaeca\"},{\"type\":\"text\",\"label\":\"How will you affiliate?\",\"value\":\"Porro sint soluta u\"}]', 30.00, 1, '2020-03-09 05:35:07', '2020-03-10 02:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `currency_format` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_plus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `name`, `logo`, `currency_id`, `currency_format`, `facebook`, `twitter`, `instagram`, `youtube`, `google_plus`, `created_at`, `updated_at`) VALUES
(1, 'Active eCommerce', 'uploads/logo/matggar.png', 1, 'symbol', 'https://facebook.com', 'https://twitter.com', 'https://instagram.com', 'https://youtube.com', 'https://google.com', '2019-08-04 16:39:15', '2019-08-04 16:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Size', '2020-02-24 05:55:07', '2020-02-24 05:55:07'),
(2, 'Fabric', '2020-02-24 05:55:13', '2020-02-24 05:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '1',
  `published` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `photo`, `url`, `position`, `published`, `created_at`, `updated_at`) VALUES
(4, 'uploads/banners/banner.jpg', '#', 1, 1, '2019-03-12 05:58:23', '2019-06-11 04:56:50'),
(5, 'uploads/banners/banner.jpg', '#', 1, 1, '2019-03-12 05:58:41', '2019-03-12 05:58:57'),
(6, 'uploads/banners/banner.jpg', '#', 2, 1, '2019-03-12 05:58:52', '2019-03-12 05:58:57'),
(7, 'uploads/banners/banner.jpg', '#', 2, 1, '2019-05-26 05:16:38', '2019-05-26 05:17:34'),
(8, 'uploads/banners/banner.jpg', '#', 2, 1, '2019-06-11 05:00:06', '2019-06-11 05:00:27'),
(9, 'uploads/banners/banner.jpg', '#', 1, 1, '2019-06-11 05:00:15', '2019-06-11 05:00:29'),
(10, 'uploads/banners/banner.jpg', '#', 1, 0, '2019-06-11 05:00:24', '2019-06-11 05:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `top` int(1) NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `top`, `slug`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Demo brand', 'uploads/brands/brand.jpg', 1, 'Demo-brand-12', 'Demo brand', NULL, '2019-03-12 06:05:56', '2019-08-06 06:52:40'),
(2, 'Demo brand1', 'uploads/brands/brand.jpg', 1, 'Demo-brand1', 'Demo brand1', NULL, '2019-03-12 06:06:13', '2019-08-06 06:07:26');

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

CREATE TABLE `business_settings` (
  `id` int(11) NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'home_default_currency', '28', '2018-10-16 01:35:52', '2020-07-03 05:17:44'),
(2, 'system_default_currency', '28', '2018-10-16 01:36:58', '2020-07-03 05:17:44'),
(3, 'currency_format', '1', '2018-10-17 03:01:59', '2018-10-17 03:01:59'),
(4, 'symbol_format', '1', '2018-10-17 03:01:59', '2019-01-20 02:10:55'),
(5, 'no_of_decimals', '3', '2018-10-17 03:01:59', '2020-03-04 00:57:16'),
(6, 'product_activation', '1', '2018-10-28 01:38:37', '2019-02-04 01:11:41'),
(7, 'vendor_system_activation', '1', '2018-10-28 07:44:16', '2019-02-04 01:11:38'),
(8, 'show_vendors', '1', '2018-10-28 07:44:47', '2019-02-04 01:11:13'),
(9, 'paypal_payment', '0', '2018-10-28 07:45:16', '2019-01-31 05:09:10'),
(10, 'stripe_payment', '0', '2018-10-28 07:45:47', '2018-11-14 01:51:51'),
(11, 'cash_payment', '1', '2018-10-28 07:46:05', '2019-01-24 03:40:18'),
(12, 'payumoney_payment', '0', '2018-10-28 07:46:27', '2019-03-05 05:41:36'),
(13, 'best_selling', '1', '2018-12-24 08:13:44', '2019-02-14 05:29:13'),
(14, 'paypal_sandbox', '0', '2019-01-16 12:44:18', '2019-01-16 12:44:18'),
(15, 'sslcommerz_sandbox', '1', '2019-01-16 12:44:18', '2019-03-14 00:07:26'),
(16, 'sslcommerz_payment', '0', '2019-01-24 09:39:07', '2019-01-29 06:13:46'),
(17, 'vendor_commission', '20', '2019-01-31 06:18:04', '2019-04-13 06:49:26'),
(18, 'verification_form', '[{\"type\":\"text\",\"label\":\"Your name\"},{\"type\":\"text\",\"label\":\"Shop name\"},{\"type\":\"text\",\"label\":\"Email\"},{\"type\":\"text\",\"label\":\"License No\"},{\"type\":\"text\",\"label\":\"Full Address\"},{\"type\":\"text\",\"label\":\"Phone Number\"},{\"type\":\"file\",\"label\":\"Tax Papers\"}]', '2019-02-03 11:36:58', '2019-02-16 06:14:42'),
(19, 'google_analytics', '0', '2019-02-06 12:22:35', '2019-02-06 12:22:35'),
(20, 'facebook_login', '0', '2019-02-07 12:51:59', '2019-02-08 19:41:15'),
(21, 'google_login', '0', '2019-02-07 12:52:10', '2019-02-08 19:41:14'),
(22, 'twitter_login', '0', '2019-02-07 12:52:20', '2019-02-08 02:32:56'),
(23, 'payumoney_payment', '1', '2019-03-05 11:38:17', '2019-03-05 11:38:17'),
(24, 'payumoney_sandbox', '1', '2019-03-05 11:38:17', '2019-03-05 05:39:18'),
(36, 'facebook_chat', '0', '2019-04-15 11:45:04', '2019-04-15 11:45:04'),
(37, 'email_verification', '0', '2019-04-30 07:30:07', '2019-04-30 07:30:07'),
(38, 'wallet_system', '0', '2019-05-19 08:05:44', '2019-05-19 02:11:57'),
(39, 'coupon_system', '0', '2019-06-11 09:46:18', '2019-06-11 09:46:18'),
(40, 'current_version', '2.9', '2019-06-11 09:46:18', '2019-06-11 09:46:18'),
(41, 'instamojo_payment', '0', '2019-07-06 09:58:03', '2019-07-06 09:58:03'),
(42, 'instamojo_sandbox', '1', '2019-07-06 09:58:43', '2019-07-06 09:58:43'),
(43, 'razorpay', '0', '2019-07-06 09:58:43', '2019-07-06 09:58:43'),
(44, 'paystack', '0', '2019-07-21 13:00:38', '2019-07-21 13:00:38'),
(45, 'pickup_point', '0', '2019-10-17 11:50:39', '2019-10-17 11:50:39'),
(46, 'maintenance_mode', '0', '2019-10-17 11:51:04', '2019-10-17 11:51:04'),
(47, 'voguepay', '0', '2019-10-17 11:51:24', '2019-10-17 11:51:24'),
(48, 'voguepay_sandbox', '0', '2019-10-17 11:51:38', '2019-10-17 11:51:38'),
(50, 'category_wise_commission', '0', '2020-01-21 07:22:47', '2020-01-21 07:22:47'),
(51, 'conversation_system', '1', '2020-01-21 07:23:21', '2020-01-21 07:23:21'),
(52, 'guest_checkout_active', '1', '2020-01-22 07:36:38', '2020-01-22 07:36:38'),
(53, 'facebook_pixel', '0', '2020-01-22 11:43:58', '2020-01-22 11:43:58'),
(55, 'classified_product', '0', '2020-05-13 13:01:05', '2020-05-13 13:01:05'),
(56, 'pos_activation_for_seller', '1', '2020-06-11 09:45:02', '2020-06-11 09:45:02'),
(57, 'shipping_type', 'product_wise_shipping', '2020-07-01 13:49:56', '2020-07-01 13:49:56'),
(58, 'flat_rate_shipping_cost', '0', '2020-07-01 13:49:56', '2020-07-01 13:49:56'),
(59, 'shipping_cost_admin', '0', '2020-07-01 13:49:56', '2020-07-01 13:49:56'),
(60, 'refund_request_time', '3', '2019-03-12 00:28:23', '2019-03-12 00:28:23');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `variation` text COLLATE utf8_unicode_ci,
  `price` double(8,2) DEFAULT NULL,
  `tax` double(8,2) DEFAULT NULL,
  `shipping_cost` double(8,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `commision_rate` double(8,2) NOT NULL DEFAULT '0.00',
  `banner` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featured` int(1) NOT NULL DEFAULT '0',
  `top` int(1) NOT NULL DEFAULT '0',
  `digital` int(1) NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `commision_rate`, `banner`, `icon`, `featured`, `top`, `digital`, `slug`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Demo category 1', 0.00, 'uploads/categories/banner/category-banner.jpg', 'uploads/categories/icon/KjJP9wuEZNL184XVUk3S7EiZ8NnBN99kiU4wdvp3.png', 1, 1, 0, 'Demo-category-1', 'Demo category 1', NULL, '2019-08-06 12:06:58', '2019-08-06 06:06:58'),
(2, 'Demo category 2', 0.00, 'uploads/categories/banner/category-banner.jpg', 'uploads/categories/icon/h9XhWwI401u6sRoLITEk9SUMRAlWN8moGrpPfS6I.png', 1, 0, 0, 'Demo-category-2', 'Demo category 2', NULL, '2019-08-06 12:06:58', '2019-08-06 06:06:58'),
(3, 'Demo category 3', 0.00, 'uploads/categories/banner/category-banner.jpg', 'uploads/categories/icon/rKAPw5rNlS84JtD9ZQqn366jwE11qyJqbzAe5yaA.png', 1, 1, 0, 'Demo-category-3', 'Demo category 3', NULL, '2019-08-06 12:06:58', '2019-08-06 06:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'IndianRed', '#CD5C5C', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(2, 'LightCoral', '#F08080', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(3, 'Salmon', '#FA8072', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(4, 'DarkSalmon', '#E9967A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(5, 'LightSalmon', '#FFA07A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(6, 'Crimson', '#DC143C', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(7, 'Red', '#FF0000', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(8, 'FireBrick', '#B22222', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(9, 'DarkRed', '#8B0000', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(10, 'Pink', '#FFC0CB', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(11, 'LightPink', '#FFB6C1', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(12, 'HotPink', '#FF69B4', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(13, 'DeepPink', '#FF1493', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(14, 'MediumVioletRed', '#C71585', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(15, 'PaleVioletRed', '#DB7093', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(16, 'LightSalmon', '#FFA07A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(17, 'Coral', '#FF7F50', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(18, 'Tomato', '#FF6347', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(19, 'OrangeRed', '#FF4500', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(20, 'DarkOrange', '#FF8C00', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(21, 'Orange', '#FFA500', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(22, 'Gold', '#FFD700', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(23, 'Yellow', '#FFFF00', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(24, 'LightYellow', '#FFFFE0', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(25, 'LemonChiffon', '#FFFACD', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(26, 'LightGoldenrodYellow', '#FAFAD2', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(27, 'PapayaWhip', '#FFEFD5', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(28, 'Moccasin', '#FFE4B5', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(29, 'PeachPuff', '#FFDAB9', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(30, 'PaleGoldenrod', '#EEE8AA', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(31, 'Khaki', '#F0E68C', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(32, 'DarkKhaki', '#BDB76B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(33, 'Lavender', '#E6E6FA', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(34, 'Thistle', '#D8BFD8', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(35, 'Plum', '#DDA0DD', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(36, 'Violet', '#EE82EE', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(37, 'Orchid', '#DA70D6', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(38, 'Fuchsia', '#FF00FF', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(39, 'Magenta', '#FF00FF', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(40, 'MediumOrchid', '#BA55D3', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(41, 'MediumPurple', '#9370DB', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(42, 'Amethyst', '#9966CC', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(43, 'BlueViolet', '#8A2BE2', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(44, 'DarkViolet', '#9400D3', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(45, 'DarkOrchid', '#9932CC', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(46, 'DarkMagenta', '#8B008B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(47, 'Purple', '#800080', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(48, 'Indigo', '#4B0082', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(49, 'SlateBlue', '#6A5ACD', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(50, 'DarkSlateBlue', '#483D8B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(51, 'MediumSlateBlue', '#7B68EE', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(52, 'GreenYellow', '#ADFF2F', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(53, 'Chartreuse', '#7FFF00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(54, 'LawnGreen', '#7CFC00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(55, 'Lime', '#00FF00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(56, 'LimeGreen', '#32CD32', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(57, 'PaleGreen', '#98FB98', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(58, 'LightGreen', '#90EE90', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(59, 'MediumSpringGreen', '#00FA9A', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(60, 'SpringGreen', '#00FF7F', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(61, 'MediumSeaGreen', '#3CB371', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(62, 'SeaGreen', '#2E8B57', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(63, 'ForestGreen', '#228B22', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(64, 'Green', '#008000', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(65, 'DarkGreen', '#006400', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(66, 'YellowGreen', '#9ACD32', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(67, 'OliveDrab', '#6B8E23', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(68, 'Olive', '#808000', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(69, 'DarkOliveGreen', '#556B2F', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(70, 'MediumAquamarine', '#66CDAA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(71, 'DarkSeaGreen', '#8FBC8F', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(72, 'LightSeaGreen', '#20B2AA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(73, 'DarkCyan', '#008B8B', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(74, 'Teal', '#008080', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(75, 'Aqua', '#00FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(76, 'Cyan', '#00FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(77, 'LightCyan', '#E0FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(78, 'PaleTurquoise', '#AFEEEE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(79, 'Aquamarine', '#7FFFD4', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(80, 'Turquoise', '#40E0D0', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(81, 'MediumTurquoise', '#48D1CC', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(82, 'DarkTurquoise', '#00CED1', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(83, 'CadetBlue', '#5F9EA0', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(84, 'SteelBlue', '#4682B4', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(85, 'LightSteelBlue', '#B0C4DE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(86, 'PowderBlue', '#B0E0E6', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(87, 'LightBlue', '#ADD8E6', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(88, 'SkyBlue', '#87CEEB', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(89, 'LightSkyBlue', '#87CEFA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(90, 'DeepSkyBlue', '#00BFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(91, 'DodgerBlue', '#1E90FF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(92, 'CornflowerBlue', '#6495ED', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(93, 'MediumSlateBlue', '#7B68EE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(94, 'RoyalBlue', '#4169E1', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(95, 'Blue', '#0000FF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(96, 'MediumBlue', '#0000CD', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(97, 'DarkBlue', '#00008B', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(98, 'Navy', '#000080', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(99, 'MidnightBlue', '#191970', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(100, 'Cornsilk', '#FFF8DC', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(101, 'BlanchedAlmond', '#FFEBCD', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(102, 'Bisque', '#FFE4C4', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(103, 'NavajoWhite', '#FFDEAD', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(104, 'Wheat', '#F5DEB3', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(105, 'BurlyWood', '#DEB887', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(106, 'Tan', '#D2B48C', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(107, 'RosyBrown', '#BC8F8F', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(108, 'SandyBrown', '#F4A460', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(109, 'Goldenrod', '#DAA520', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(110, 'DarkGoldenrod', '#B8860B', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(111, 'Peru', '#CD853F', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(112, 'Chocolate', '#D2691E', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(113, 'SaddleBrown', '#8B4513', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(114, 'Sienna', '#A0522D', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(115, 'Brown', '#A52A2A', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(116, 'Maroon', '#800000', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(117, 'White', '#FFFFFF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(118, 'Snow', '#FFFAFA', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(119, 'Honeydew', '#F0FFF0', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(120, 'MintCream', '#F5FFFA', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(121, 'Azure', '#F0FFFF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(122, 'AliceBlue', '#F0F8FF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(123, 'GhostWhite', '#F8F8FF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(124, 'WhiteSmoke', '#F5F5F5', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(125, 'Seashell', '#FFF5EE', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(126, 'Beige', '#F5F5DC', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(127, 'OldLace', '#FDF5E6', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(128, 'FloralWhite', '#FFFAF0', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(129, 'Ivory', '#FFFFF0', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(130, 'AntiqueWhite', '#FAEBD7', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(131, 'Linen', '#FAF0E6', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(132, 'LavenderBlush', '#FFF0F5', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(133, 'MistyRose', '#FFE4E1', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(134, 'Gainsboro', '#DCDCDC', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(135, 'LightGrey', '#D3D3D3', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(136, 'Silver', '#C0C0C0', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(137, 'DarkGray', '#A9A9A9', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(138, 'Gray', '#808080', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(139, 'DimGray', '#696969', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(140, 'LightSlateGray', '#778899', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(141, 'SlateGray', '#708090', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(142, 'DarkSlateGray', '#2F4F4F', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(143, 'Black', '#000000', '2018-11-05 02:12:30', '2018-11-05 02:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `title` varchar(1000) COLLATE utf32_unicode_ci DEFAULT NULL,
  `sender_viewed` int(1) NOT NULL DEFAULT '1',
  `receiver_viewed` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'AF', 'Afghanistan', 1, NULL, NULL),
(2, 'AL', 'Albania', 1, NULL, NULL),
(3, 'DZ', 'Algeria', 1, NULL, NULL),
(4, 'DS', 'American Samoa', 1, NULL, NULL),
(5, 'AD', 'Andorra', 1, NULL, NULL),
(6, 'AO', 'Angola', 1, NULL, NULL),
(7, 'AI', 'Anguilla', 1, NULL, NULL),
(8, 'AQ', 'Antarctica', 1, NULL, NULL),
(9, 'AG', 'Antigua and Barbuda', 1, NULL, NULL),
(10, 'AR', 'Argentina', 1, NULL, NULL),
(11, 'AM', 'Armenia', 1, NULL, NULL),
(12, 'AW', 'Aruba', 1, NULL, NULL),
(13, 'AU', 'Australia', 1, NULL, NULL),
(14, 'AT', 'Austria', 1, NULL, NULL),
(15, 'AZ', 'Azerbaijan', 1, NULL, NULL),
(16, 'BS', 'Bahamas', 1, NULL, NULL),
(17, 'BH', 'Bahrain', 1, NULL, NULL),
(18, 'BD', 'Bangladesh', 1, NULL, NULL),
(19, 'BB', 'Barbados', 1, NULL, NULL),
(20, 'BY', 'Belarus', 1, NULL, NULL),
(21, 'BE', 'Belgium', 1, NULL, NULL),
(22, 'BZ', 'Belize', 1, NULL, NULL),
(23, 'BJ', 'Benin', 1, NULL, NULL),
(24, 'BM', 'Bermuda', 1, NULL, NULL),
(25, 'BT', 'Bhutan', 1, NULL, NULL),
(26, 'BO', 'Bolivia', 1, NULL, NULL),
(27, 'BA', 'Bosnia and Herzegovina', 1, NULL, NULL),
(28, 'BW', 'Botswana', 1, NULL, NULL),
(29, 'BV', 'Bouvet Island', 1, NULL, NULL),
(30, 'BR', 'Brazil', 1, NULL, NULL),
(31, 'IO', 'British Indian Ocean Territory', 1, NULL, NULL),
(32, 'BN', 'Brunei Darussalam', 1, NULL, NULL),
(33, 'BG', 'Bulgaria', 1, NULL, NULL),
(34, 'BF', 'Burkina Faso', 1, NULL, NULL),
(35, 'BI', 'Burundi', 1, NULL, NULL),
(36, 'KH', 'Cambodia', 1, NULL, NULL),
(37, 'CM', 'Cameroon', 1, NULL, NULL),
(38, 'CA', 'Canada', 1, NULL, NULL),
(39, 'CV', 'Cape Verde', 1, NULL, NULL),
(40, 'KY', 'Cayman Islands', 1, NULL, NULL),
(41, 'CF', 'Central African Republic', 1, NULL, NULL),
(42, 'TD', 'Chad', 1, NULL, NULL),
(43, 'CL', 'Chile', 1, NULL, NULL),
(44, 'CN', 'China', 1, NULL, NULL),
(45, 'CX', 'Christmas Island', 1, NULL, NULL),
(46, 'CC', 'Cocos (Keeling) Islands', 1, NULL, NULL),
(47, 'CO', 'Colombia', 1, NULL, NULL),
(48, 'KM', 'Comoros', 1, NULL, NULL),
(49, 'CG', 'Congo', 1, NULL, NULL),
(50, 'CK', 'Cook Islands', 1, NULL, NULL),
(51, 'CR', 'Costa Rica', 1, NULL, NULL),
(52, 'HR', 'Croatia (Hrvatska)', 1, NULL, NULL),
(53, 'CU', 'Cuba', 1, NULL, NULL),
(54, 'CY', 'Cyprus', 1, NULL, NULL),
(55, 'CZ', 'Czech Republic', 1, NULL, NULL),
(56, 'DK', 'Denmark', 1, NULL, NULL),
(57, 'DJ', 'Djibouti', 1, NULL, NULL),
(58, 'DM', 'Dominica', 1, NULL, NULL),
(59, 'DO', 'Dominican Republic', 1, NULL, NULL),
(60, 'TP', 'East Timor', 1, NULL, NULL),
(61, 'EC', 'Ecuador', 1, NULL, NULL),
(62, 'EG', 'Egypt', 1, NULL, NULL),
(63, 'SV', 'El Salvador', 1, NULL, NULL),
(64, 'GQ', 'Equatorial Guinea', 1, NULL, NULL),
(65, 'ER', 'Eritrea', 1, NULL, NULL),
(66, 'EE', 'Estonia', 1, NULL, NULL),
(67, 'ET', 'Ethiopia', 1, NULL, NULL),
(68, 'FK', 'Falkland Islands (Malvinas)', 1, NULL, NULL),
(69, 'FO', 'Faroe Islands', 1, NULL, NULL),
(70, 'FJ', 'Fiji', 1, NULL, NULL),
(71, 'FI', 'Finland', 1, NULL, NULL),
(72, 'FR', 'France', 1, NULL, NULL),
(73, 'FX', 'France, Metropolitan', 1, NULL, NULL),
(74, 'GF', 'French Guiana', 1, NULL, NULL),
(75, 'PF', 'French Polynesia', 1, NULL, NULL),
(76, 'TF', 'French Southern Territories', 1, NULL, NULL),
(77, 'GA', 'Gabon', 1, NULL, NULL),
(78, 'GM', 'Gambia', 1, NULL, NULL),
(79, 'GE', 'Georgia', 1, NULL, NULL),
(80, 'DE', 'Germany', 1, NULL, NULL),
(81, 'GH', 'Ghana', 1, NULL, NULL),
(82, 'GI', 'Gibraltar', 1, NULL, NULL),
(83, 'GK', 'Guernsey', 1, NULL, NULL),
(84, 'GR', 'Greece', 1, NULL, NULL),
(85, 'GL', 'Greenland', 1, NULL, NULL),
(86, 'GD', 'Grenada', 1, NULL, NULL),
(87, 'GP', 'Guadeloupe', 1, NULL, NULL),
(88, 'GU', 'Guam', 1, NULL, NULL),
(89, 'GT', 'Guatemala', 1, NULL, NULL),
(90, 'GN', 'Guinea', 1, NULL, NULL),
(91, 'GW', 'Guinea-Bissau', 1, NULL, NULL),
(92, 'GY', 'Guyana', 1, NULL, NULL),
(93, 'HT', 'Haiti', 1, NULL, NULL),
(94, 'HM', 'Heard and Mc Donald Islands', 1, NULL, NULL),
(95, 'HN', 'Honduras', 1, NULL, NULL),
(96, 'HK', 'Hong Kong', 1, NULL, NULL),
(97, 'HU', 'Hungary', 1, NULL, NULL),
(98, 'IS', 'Iceland', 1, NULL, NULL),
(99, 'IN', 'India', 1, NULL, NULL),
(100, 'IM', 'Isle of Man', 1, NULL, NULL),
(101, 'ID', 'Indonesia', 1, NULL, NULL),
(102, 'IR', 'Iran (Islamic Republic of)', 1, NULL, NULL),
(103, 'IQ', 'Iraq', 1, NULL, NULL),
(104, 'IE', 'Ireland', 1, NULL, NULL),
(105, 'IL', 'Israel', 1, NULL, NULL),
(106, 'IT', 'Italy', 1, NULL, NULL),
(107, 'CI', 'Ivory Coast', 1, NULL, NULL),
(108, 'JE', 'Jersey', 1, NULL, NULL),
(109, 'JM', 'Jamaica', 1, NULL, NULL),
(110, 'JP', 'Japan', 1, NULL, NULL),
(111, 'JO', 'Jordan', 1, NULL, NULL),
(112, 'KZ', 'Kazakhstan', 1, NULL, NULL),
(113, 'KE', 'Kenya', 1, NULL, NULL),
(114, 'KI', 'Kiribati', 1, NULL, NULL),
(115, 'KP', 'Korea, Democratic People\'s Republic of', 1, NULL, NULL),
(116, 'KR', 'Korea, Republic of', 1, NULL, NULL),
(117, 'XK', 'Kosovo', 1, NULL, NULL),
(118, 'KW', 'Kuwait', 1, NULL, NULL),
(119, 'KG', 'Kyrgyzstan', 1, NULL, NULL),
(120, 'LA', 'Lao People\'s Democratic Republic', 1, NULL, NULL),
(121, 'LV', 'Latvia', 1, NULL, NULL),
(122, 'LB', 'Lebanon', 1, NULL, NULL),
(123, 'LS', 'Lesotho', 1, NULL, NULL),
(124, 'LR', 'Liberia', 1, NULL, NULL),
(125, 'LY', 'Libyan Arab Jamahiriya', 1, NULL, NULL),
(126, 'LI', 'Liechtenstein', 1, NULL, NULL),
(127, 'LT', 'Lithuania', 1, NULL, NULL),
(128, 'LU', 'Luxembourg', 1, NULL, NULL),
(129, 'MO', 'Macau', 1, NULL, NULL),
(130, 'MK', 'Macedonia', 1, NULL, NULL),
(131, 'MG', 'Madagascar', 1, NULL, NULL),
(132, 'MW', 'Malawi', 1, NULL, NULL),
(133, 'MY', 'Malaysia', 1, NULL, NULL),
(134, 'MV', 'Maldives', 1, NULL, NULL),
(135, 'ML', 'Mali', 1, NULL, NULL),
(136, 'MT', 'Malta', 1, NULL, NULL),
(137, 'MH', 'Marshall Islands', 1, NULL, NULL),
(138, 'MQ', 'Martinique', 1, NULL, NULL),
(139, 'MR', 'Mauritania', 1, NULL, NULL),
(140, 'MU', 'Mauritius', 1, NULL, NULL),
(141, 'TY', 'Mayotte', 1, NULL, NULL),
(142, 'MX', 'Mexico', 1, NULL, NULL),
(143, 'FM', 'Micronesia, Federated States of', 1, NULL, NULL),
(144, 'MD', 'Moldova, Republic of', 1, NULL, NULL),
(145, 'MC', 'Monaco', 1, NULL, NULL),
(146, 'MN', 'Mongolia', 1, NULL, NULL),
(147, 'ME', 'Montenegro', 1, NULL, NULL),
(148, 'MS', 'Montserrat', 1, NULL, NULL),
(149, 'MA', 'Morocco', 1, NULL, NULL),
(150, 'MZ', 'Mozambique', 1, NULL, NULL),
(151, 'MM', 'Myanmar', 1, NULL, NULL),
(152, 'NA', 'Namibia', 1, NULL, NULL),
(153, 'NR', 'Nauru', 1, NULL, NULL),
(154, 'NP', 'Nepal', 1, NULL, NULL),
(155, 'NL', 'Netherlands', 1, NULL, NULL),
(156, 'AN', 'Netherlands Antilles', 1, NULL, NULL),
(157, 'NC', 'New Caledonia', 1, NULL, NULL),
(158, 'NZ', 'New Zealand', 1, NULL, NULL),
(159, 'NI', 'Nicaragua', 1, NULL, NULL),
(160, 'NE', 'Niger', 1, NULL, NULL),
(161, 'NG', 'Nigeria', 1, NULL, NULL),
(162, 'NU', 'Niue', 1, NULL, NULL),
(163, 'NF', 'Norfolk Island', 1, NULL, NULL),
(164, 'MP', 'Northern Mariana Islands', 1, NULL, NULL),
(165, 'NO', 'Norway', 1, NULL, NULL),
(166, 'OM', 'Oman', 1, NULL, NULL),
(167, 'PK', 'Pakistan', 1, NULL, NULL),
(168, 'PW', 'Palau', 1, NULL, NULL),
(169, 'PS', 'Palestine', 1, NULL, NULL),
(170, 'PA', 'Panama', 1, NULL, NULL),
(171, 'PG', 'Papua New Guinea', 1, NULL, NULL),
(172, 'PY', 'Paraguay', 1, NULL, NULL),
(173, 'PE', 'Peru', 1, NULL, NULL),
(174, 'PH', 'Philippines', 1, NULL, NULL),
(175, 'PN', 'Pitcairn', 1, NULL, NULL),
(176, 'PL', 'Poland', 1, NULL, NULL),
(177, 'PT', 'Portugal', 1, NULL, NULL),
(178, 'PR', 'Puerto Rico', 1, NULL, NULL),
(179, 'QA', 'Qatar', 1, NULL, NULL),
(180, 'RE', 'Reunion', 1, NULL, NULL),
(181, 'RO', 'Romania', 1, NULL, NULL),
(182, 'RU', 'Russian Federation', 1, NULL, NULL),
(183, 'RW', 'Rwanda', 1, NULL, NULL),
(184, 'KN', 'Saint Kitts and Nevis', 1, NULL, NULL),
(185, 'LC', 'Saint Lucia', 1, NULL, NULL),
(186, 'VC', 'Saint Vincent and the Grenadines', 1, NULL, NULL),
(187, 'WS', 'Samoa', 1, NULL, NULL),
(188, 'SM', 'San Marino', 1, NULL, NULL),
(189, 'ST', 'Sao Tome and Principe', 1, NULL, NULL),
(190, 'SA', 'Saudi Arabia', 1, NULL, NULL),
(191, 'SN', 'Senegal', 1, NULL, NULL),
(192, 'RS', 'Serbia', 1, NULL, NULL),
(193, 'SC', 'Seychelles', 1, NULL, NULL),
(194, 'SL', 'Sierra Leone', 1, NULL, NULL),
(195, 'SG', 'Singapore', 1, NULL, NULL),
(196, 'SK', 'Slovakia', 1, NULL, NULL),
(197, 'SI', 'Slovenia', 1, NULL, NULL),
(198, 'SB', 'Solomon Islands', 1, NULL, NULL),
(199, 'SO', 'Somalia', 1, NULL, NULL),
(200, 'ZA', 'South Africa', 1, NULL, NULL),
(201, 'GS', 'South Georgia South Sandwich Islands', 1, NULL, NULL),
(202, 'SS', 'South Sudan', 1, NULL, NULL),
(203, 'ES', 'Spain', 1, NULL, NULL),
(204, 'LK', 'Sri Lanka', 1, NULL, NULL),
(205, 'SH', 'St. Helena', 1, NULL, NULL),
(206, 'PM', 'St. Pierre and Miquelon', 1, NULL, NULL),
(207, 'SD', 'Sudan', 1, NULL, NULL),
(208, 'SR', 'Suriname', 1, NULL, NULL),
(209, 'SJ', 'Svalbard and Jan Mayen Islands', 1, NULL, NULL),
(210, 'SZ', 'Swaziland', 1, NULL, NULL),
(211, 'SE', 'Sweden', 1, NULL, NULL),
(212, 'CH', 'Switzerland', 1, NULL, NULL),
(213, 'SY', 'Syrian Arab Republic', 1, NULL, NULL),
(214, 'TW', 'Taiwan', 1, NULL, NULL),
(215, 'TJ', 'Tajikistan', 1, NULL, NULL),
(216, 'TZ', 'Tanzania, United Republic of', 1, NULL, NULL),
(217, 'TH', 'Thailand', 1, NULL, NULL),
(218, 'TG', 'Togo', 1, NULL, NULL),
(219, 'TK', 'Tokelau', 1, NULL, NULL),
(220, 'TO', 'Tonga', 1, NULL, NULL),
(221, 'TT', 'Trinidad and Tobago', 1, NULL, NULL),
(222, 'TN', 'Tunisia', 1, NULL, NULL),
(223, 'TR', 'Turkey', 1, NULL, NULL),
(224, 'TM', 'Turkmenistan', 1, NULL, NULL),
(225, 'TC', 'Turks and Caicos Islands', 1, NULL, NULL),
(226, 'TV', 'Tuvalu', 1, NULL, NULL),
(227, 'UG', 'Uganda', 1, NULL, NULL),
(228, 'UA', 'Ukraine', 1, NULL, NULL),
(229, 'AE', 'United Arab Emirates', 1, NULL, NULL),
(230, 'GB', 'United Kingdom', 1, NULL, NULL),
(231, 'US', 'United States', 1, NULL, NULL),
(232, 'UM', 'United States minor outlying islands', 1, NULL, NULL),
(233, 'UY', 'Uruguay', 1, NULL, NULL),
(234, 'UZ', 'Uzbekistan', 1, NULL, NULL),
(235, 'VU', 'Vanuatu', 1, NULL, NULL),
(236, 'VA', 'Vatican City State', 1, NULL, NULL),
(237, 'VE', 'Venezuela', 1, NULL, NULL),
(238, 'VN', 'Vietnam', 1, NULL, NULL),
(239, 'VG', 'Virgin Islands (British)', 1, NULL, NULL),
(240, 'VI', 'Virgin Islands (U.S.)', 1, NULL, NULL),
(241, 'WF', 'Wallis and Futuna Islands', 1, NULL, NULL),
(242, 'EH', 'Western Sahara', 1, NULL, NULL),
(243, 'YE', 'Yemen', 1, NULL, NULL),
(244, 'ZR', 'Zaire', 1, NULL, NULL),
(245, 'ZM', 'Zambia', 1, NULL, NULL),
(246, 'ZW', 'Zimbabwe', 1, NULL, NULL),
(247, 'AF', 'Afghanistan', 1, NULL, NULL),
(248, 'AL', 'Albania', 1, NULL, NULL),
(249, 'DZ', 'Algeria', 1, NULL, NULL),
(250, 'DS', 'American Samoa', 1, NULL, NULL),
(251, 'AD', 'Andorra', 1, NULL, NULL),
(252, 'AO', 'Angola', 1, NULL, NULL),
(253, 'AI', 'Anguilla', 1, NULL, NULL),
(254, 'AQ', 'Antarctica', 1, NULL, NULL),
(255, 'AG', 'Antigua and Barbuda', 1, NULL, NULL),
(256, 'AR', 'Argentina', 1, NULL, NULL),
(257, 'AM', 'Armenia', 1, NULL, NULL),
(258, 'AW', 'Aruba', 1, NULL, NULL),
(259, 'AU', 'Australia', 1, NULL, NULL),
(260, 'AT', 'Austria', 1, NULL, NULL),
(261, 'AZ', 'Azerbaijan', 1, NULL, NULL),
(262, 'BS', 'Bahamas', 1, NULL, NULL),
(263, 'BH', 'Bahrain', 1, NULL, NULL),
(264, 'BD', 'Bangladesh', 1, NULL, NULL),
(265, 'BB', 'Barbados', 1, NULL, NULL),
(266, 'BY', 'Belarus', 1, NULL, NULL),
(267, 'BE', 'Belgium', 1, NULL, NULL),
(268, 'BZ', 'Belize', 1, NULL, NULL),
(269, 'BJ', 'Benin', 1, NULL, NULL),
(270, 'BM', 'Bermuda', 1, NULL, NULL),
(271, 'BT', 'Bhutan', 1, NULL, NULL),
(272, 'BO', 'Bolivia', 1, NULL, NULL),
(273, 'BA', 'Bosnia and Herzegovina', 1, NULL, NULL),
(274, 'BW', 'Botswana', 1, NULL, NULL),
(275, 'BV', 'Bouvet Island', 1, NULL, NULL),
(276, 'BR', 'Brazil', 1, NULL, NULL),
(277, 'IO', 'British Indian Ocean Territory', 1, NULL, NULL),
(278, 'BN', 'Brunei Darussalam', 1, NULL, NULL),
(279, 'BG', 'Bulgaria', 1, NULL, NULL),
(280, 'BF', 'Burkina Faso', 1, NULL, NULL),
(281, 'BI', 'Burundi', 1, NULL, NULL),
(282, 'KH', 'Cambodia', 1, NULL, NULL),
(283, 'CM', 'Cameroon', 1, NULL, NULL),
(284, 'CA', 'Canada', 1, NULL, NULL),
(285, 'CV', 'Cape Verde', 1, NULL, NULL),
(286, 'KY', 'Cayman Islands', 1, NULL, NULL),
(287, 'CF', 'Central African Republic', 1, NULL, NULL),
(288, 'TD', 'Chad', 1, NULL, NULL),
(289, 'CL', 'Chile', 1, NULL, NULL),
(290, 'CN', 'China', 1, NULL, NULL),
(291, 'CX', 'Christmas Island', 1, NULL, NULL),
(292, 'CC', 'Cocos (Keeling) Islands', 1, NULL, NULL),
(293, 'CO', 'Colombia', 1, NULL, NULL),
(294, 'KM', 'Comoros', 1, NULL, NULL),
(295, 'CG', 'Congo', 1, NULL, NULL),
(296, 'CK', 'Cook Islands', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8_unicode_ci NOT NULL,
  `discount` double(8,2) NOT NULL,
  `discount_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` int(15) NOT NULL,
  `end_date` int(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `exchange_rate` double(10,5) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '0',
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbol`, `exchange_rate`, `status`, `code`, `created_at`, `updated_at`) VALUES
(1, 'U.S. Dollar', '$', 1.00000, 1, 'USD', '2018-10-09 11:35:08', '2018-10-17 05:50:52'),
(2, 'Australian Dollar', '$', 1.28000, 1, 'AUD', '2018-10-09 11:35:08', '2019-02-04 05:51:55'),
(5, 'Brazilian Real', 'R$', 3.25000, 1, 'BRL', '2018-10-09 11:35:08', '2018-10-17 05:51:00'),
(6, 'Canadian Dollar', '$', 1.27000, 1, 'CAD', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(7, 'Czech Koruna', 'Kč', 20.65000, 1, 'CZK', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(8, 'Danish Krone', 'kr', 6.05000, 1, 'DKK', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(9, 'Euro', '€', 0.85000, 1, 'EUR', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(10, 'Hong Kong Dollar', '$', 7.83000, 1, 'HKD', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(11, 'Hungarian Forint', 'Ft', 255.24000, 1, 'HUF', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(12, 'Israeli New Sheqel', '₪', 3.48000, 1, 'ILS', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(13, 'Japanese Yen', '¥', 107.12000, 1, 'JPY', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(14, 'Malaysian Ringgit', 'RM', 3.91000, 1, 'MYR', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(15, 'Mexican Peso', '$', 18.72000, 1, 'MXN', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(16, 'Norwegian Krone', 'kr', 7.83000, 1, 'NOK', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(17, 'New Zealand Dollar', '$', 1.38000, 1, 'NZD', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(18, 'Philippine Peso', '₱', 52.26000, 1, 'PHP', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(19, 'Polish Zloty', 'zł', 3.39000, 1, 'PLN', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(20, 'Pound Sterling', '£', 0.72000, 1, 'GBP', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(21, 'Russian Ruble', 'руб', 55.93000, 1, 'RUB', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(22, 'Singapore Dollar', '$', 1.32000, 1, 'SGD', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(23, 'Swedish Krona', 'kr', 8.19000, 1, 'SEK', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(24, 'Swiss Franc', 'CHF', 0.94000, 1, 'CHF', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(26, 'Thai Baht', '฿', 31.39000, 1, 'THB', '2018-10-09 11:35:08', '2018-10-09 11:35:08'),
(27, 'Taka', '৳', 84.00000, 1, 'BDT', '2018-10-09 11:35:08', '2018-12-02 05:16:13'),
(28, 'Indian Rupee', 'Rs', 68.45000, 1, 'Rupee', '2019-07-07 10:33:46', '2019-07-07 10:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(4, 8, '2019-08-01 10:35:09', '2019-08-01 10:35:09'),
(9, 24, '2020-07-09 04:23:36', '2020-07-09 04:23:36'),
(10, 25, '2020-07-09 04:28:25', '2020-07-09 04:28:25'),
(11, 26, '2020-07-09 04:45:10', '2020-07-09 04:45:10'),
(12, 27, '2020-07-15 01:16:50', '2020-07-15 01:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `customer_categories`
--

CREATE TABLE `customer_categories` (
  `id` int(255) NOT NULL,
  `name` varchar(200) NOT NULL,
  `isdeleted` enum('N','Y') NOT NULL DEFAULT 'N',
  `customertype` enum('S','C') NOT NULL DEFAULT 'C',
  `lastupdateddate` datetime NOT NULL,
  `lastupdatedby` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_categories`
--

INSERT INTO `customer_categories` (`id`, `name`, `isdeleted`, `customertype`, `lastupdateddate`, `lastupdatedby`, `created_at`, `updated_at`) VALUES
(1, 'Medical 2016', 'N', 'S', '2015-10-10 07:15:08', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(2, 'Non Medical Entrance Exams', 'N', 'S', '2016-12-22 00:12:38', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(3, 'BSC PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:18:02', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(4, 'BA PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:09:01', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(5, 'BCOM PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:15:10', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(6, 'BBA PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:12:05', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(7, 'BE/BTECH passout 2019', 'N', 'S', '2019-07-27 15:45:53', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(8, 'MBBS passout 2019', 'N', 'S', '2019-07-27 15:51:03', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(9, 'BDS passout 2015', 'N', 'S', '2019-07-27 14:23:06', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(10, 'GATE', 'N', 'S', '2017-06-08 14:35:11', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(11, 'Nonmedical 2016', 'N', 'S', '2015-10-10 07:14:36', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(12, 'Medical Entrance Exams', 'N', 'S', '2017-01-24 23:54:35', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(13, 'IAS/UPSC', 'N', 'S', '2017-06-08 14:36:01', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(14, 'Stationary', 'N', 'S', '0000-00-00 00:00:00', '-1', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(16, 'LAw', 'N', 'S', '2015-10-04 12:05:35', 'diljotjattana@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(17, 'INSTITUTES', 'N', 'C', '2017-07-20 00:51:06', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(18, 'Shop', 'N', 'C', '2017-07-20 00:53:00', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(19, 'BCOM PASSOUT IN 2017', 'N', 'S', '2017-07-17 19:32:45', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(20, 'BCOM PASSOUT IN 2016', 'N', 'S', '2017-07-17 19:32:03', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(21, 'BBA PASSOUT IN 2017', 'N', 'S', '2017-07-17 19:30:59', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(22, 'BBA PASSOUT IN 2016', 'N', 'S', '2017-07-17 19:27:24', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(23, 'BA PASS OUT IN 2017', 'N', 'S', '2017-07-17 19:26:20', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(24, 'BA PASS OUT IN 2016', 'N', 'S', '2017-07-17 19:26:45', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(25, 'BE/BTECH PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:18:53', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(26, 'BE/BTECH PASSOUT IN 2017', 'N', 'S', '2017-07-17 19:33:50', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(27, 'BE/BTECH 2012', 'Y', 'S', '2015-10-10 07:23:35', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(28, 'BDS PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:21:53', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(29, 'BDS PASSOUT IN 2017', 'N', 'S', '2017-08-19 17:00:07', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(30, 'BDS 2012', 'Y', 'S', '2015-10-10 07:26:29', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(31, 'BSC PASSOUT IN 2017', 'N', 'S', '2017-07-17 19:34:56', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(32, 'BSC 2013', 'Y', 'S', '2015-10-10 07:27:34', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(33, 'MBBS PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:24:13', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(34, 'MBBS PASSOUT IN 2017', 'N', 'S', '2017-08-19 17:01:30', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(35, 'BCA PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:13:55', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(36, 'MCA passout 2019', 'N', 'S', '2019-07-27 16:08:42', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(37, 'Others', 'N', 'S', '2015-10-10 22:00:55', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(38, 'BCA 2013', 'Y', 'S', '2015-10-10 22:07:45', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(39, 'suppliers', 'N', 'C', '2017-07-21 00:19:43', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(40, 'govt jobs', 'N', 'S', '2015-10-24 01:52:10', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(41, 'BCA PASSOUT IN 2017', 'N', 'S', '2017-07-17 19:31:42', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(42, 'pharmacy', 'N', 'S', '2015-11-10 03:18:27', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(43, 'ca/cs', 'N', 'S', '2015-11-18 04:29:39', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(44, 'COMMERCE 2016', 'N', 'S', '2016-01-11 04:39:42', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(45, 'MCOM PASSOUT IN 2017', 'N', 'S', '2017-07-18 00:33:50', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(46, 'MCOM PASSOUT IN 2016', 'N', 'S', '2017-07-18 00:34:21', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(47, 'pbi university', 'N', 'S', '2016-01-28 05:28:39', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(48, 'BIOLOGY', 'N', 'C', '2017-07-20 00:50:38', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(49, 'mathematics', 'N', 'C', '2017-07-20 00:51:19', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(50, 'PC', 'N', 'C', '2017-07-20 00:52:49', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(51, 'college faculties', 'N', 'C', '2017-07-20 00:50:53', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(52, 'medical 2018', 'N', 'S', '2017-04-03 19:37:44', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(53, 'BE/BTECH 3rd YEAR( 2017)', 'N', 'S', '2019-07-27 15:45:10', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(54, 'nonmedical 2018', 'N', 'S', '2017-04-03 19:36:54', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(55, 'commerce 2017', 'N', 'S', '2016-04-04 04:26:15', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(56, 'commerce 2018', 'N', 'S', '2016-04-24 04:24:04', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(57, 'BA passout ( 2016)', 'N', 'S', '2019-07-27 14:12:47', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(58, 'BBA passout (2016)', 'N', 'S', '2019-07-27 14:10:20', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(59, 'BCOM passout 2019', 'N', 'S', '2019-07-27 13:48:08', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(60, 'BSC passout (2016)', 'N', 'S', '2019-07-27 14:24:22', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(61, 'BE/BTECH 4th YEAR (2016)', 'N', 'S', '2019-07-27 15:45:28', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(62, 'MCOM PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:36:11', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(63, 'MBBS 4th YEAR (2016)', 'N', 'S', '2019-07-27 15:50:17', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(64, 'BDS 4th YEAR 2016', 'N', 'S', '2019-07-27 14:22:22', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(65, 'BCA passout (2016)', 'N', 'S', '2019-07-27 14:20:09', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(66, 'website', 'N', 'S', '2016-11-10 18:17:10', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(67, 'nonmedical 2019', 'N', 'S', '2017-04-03 19:36:23', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(68, 'medical 2019', 'N', 'S', '2017-04-03 19:37:03', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(69, 'test', 'N', 'S', '2017-05-01 00:00:00', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(70, 'BA 3rd YEAR (2017)', 'N', 'S', '2019-07-27 14:12:30', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(71, 'BBA 3rd YEAR (2017)', 'N', 'S', '2019-07-27 14:10:02', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(72, 'BCA 3rd YEAR (2017)', 'N', 'S', '2019-07-27 14:19:57', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(73, 'BCOM 3rd YEAR (2017)', 'N', 'S', '2019-07-27 13:47:42', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(74, 'BSC 3rd YEAR (2017)', 'N', 'S', '2019-07-27 14:24:10', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(75, 'MCOM passout 2019', 'N', 'S', '2019-07-27 16:10:05', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(76, 'MCA 2nd YEAR (2018)', 'N', 'S', '2019-07-27 16:08:28', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(77, 'MCA PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:34:58', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(78, 'MBBS 1st Year 2019', 'N', 'S', '2019-07-27 15:48:39', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(79, 'BSC 1st Year 2019', 'N', 'S', '2019-07-27 14:24:53', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(80, 'MA ENGLISH 2nd YEAR ( 2017)', 'N', 'S', '2018-08-01 19:38:43', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(81, 'MA ENGLISH PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:39:26', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(82, 'MA SOCIOLOGY 2nd YEAR ( 2017)', 'N', 'S', '2018-08-01 19:39:58', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(83, 'MA SOCIOLOGY PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:40:10', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(84, 'MA ECONOMICS 2nd YEAR (2017)', 'N', 'S', '2018-08-01 19:37:13', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(85, 'MA ECONOMICS PASSOUT IN 2018', 'N', 'S', '2018-08-01 19:37:45', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(86, 'MA ENGLISH PASSOUT IN 2017', 'N', 'S', '2017-08-13 14:00:34', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(87, 'MA SOCIOLOGY PASSOUT IN 2017', 'N', 'S', '2017-08-13 14:01:29', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(88, 'MA ECONOMICS PASSOUT IN 2017', 'N', 'S', '2017-08-13 14:02:00', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(89, 'BDS 3rd YEAR (2017)', 'N', 'S', '2019-07-27 14:22:08', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(90, 'MBBS 3rd YEAR (2017)', 'N', 'S', '2019-07-27 15:50:05', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(91, 'Nonmed 2020', 'N', 'S', '2018-01-07 13:53:25', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(92, 'MEDICAL 2020', 'N', 'S', '2018-01-07 13:53:48', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(93, 'FIRST CLASS ( 2018)', 'N', 'S', '2018-02-26 17:14:31', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(94, 'SECOND CLASS (2018)', 'N', 'S', '2018-02-26 17:14:39', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(95, 'THIRD CLASS ( 2018 )', 'N', 'S', '2018-02-26 17:14:55', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(96, 'FORTH CLASS (2018)', 'N', 'S', '2018-02-26 17:15:27', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(97, 'FIFTH CLASS (2018)', 'N', 'S', '2018-02-26 17:16:26', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(98, 'SIXTH CLASS (2018)', 'N', 'S', '2018-02-26 17:19:57', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(99, 'SEVENTH CLASS (2018)', 'N', 'S', '2018-02-26 17:20:05', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(100, 'EIGHT CLASS (2018)', 'N', 'S', '2018-02-26 17:20:16', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(101, 'NINTH CLASS(2018)', 'N', 'S', '2018-02-26 17:20:26', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(102, 'TENTH CLASS (2018)', 'N', 'S', '2018-02-26 17:20:34', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(103, 'COMMERCE 2019', 'N', 'S', '2018-03-11 12:19:07', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(104, 'PATIALA MEDICAL 2019', 'N', 'S', '2018-03-24 13:05:07', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(105, 'PATIALA NONMED 2019', 'N', 'S', '2018-03-24 13:05:30', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(106, 'PATIALA MEDICAL 2020', 'N', 'S', '2018-03-24 13:05:49', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(107, 'PATIALA NONMED 2020', 'N', 'S', '2018-03-24 13:06:08', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(108, 'TENTH ( 2019 )', 'N', 'S', '2018-06-09 13:45:09', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(109, 'BSC 2nd Year ( 2018)', 'N', 'S', '2019-07-27 14:23:53', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(110, 'BA 2nd Year (2018)', 'N', 'S', '2019-07-27 14:12:12', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(111, 'BCOM 2nd Year (2018)', 'N', 'S', '2019-07-27 13:47:21', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(112, 'BBA 2nd Year 2018', 'N', 'S', '2019-07-27 14:09:50', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(113, 'BCA 2nd Year (2018)', 'N', 'S', '2019-07-27 14:19:35', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(114, 'BE/BTECH 2nd YEAR (2018)', 'N', 'S', '2019-07-27 15:44:56', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(115, 'BDS 2nd YEAR 2018', 'N', 'S', '2019-07-27 14:21:51', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(116, 'MBBS 2nd YEAR 2018', 'N', 'S', '2019-07-27 15:49:53', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(117, 'MCOM 2nd YEAR (2018)', 'N', 'S', '2019-07-27 16:09:52', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(118, 'MA ECONOMICS 1st YEAR (2018)', 'N', 'S', '2018-08-01 19:38:18', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(119, 'MA ENGLISH 1st YEAR ( 2018)', 'N', 'S', '2018-08-01 19:39:07', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(120, 'KG 2018', 'N', 'S', '2018-08-13 20:02:40', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(121, 'Nursery 2018', 'N', 'S', '2018-08-13 20:03:40', 'amitbookdepot.com@gmail.com', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(122, 'Schools', 'N', 'C', '2018-08-17 15:17:21', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(123, 'UKG 2018', 'N', 'S', '2018-09-19 16:16:50', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(124, 'LKG 2018', 'N', 'S', '2018-09-19 16:16:57', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(125, 'pre nursury', 'N', 'S', '2018-12-01 15:52:42', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(126, 'playway', 'N', 'S', '2018-12-04 15:45:04', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(127, 'Commerce 2020', 'N', 'S', '2018-12-21 12:12:16', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(128, 'NM 21', 'N', 'S', '2019-04-02 15:00:29', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(129, 'MED 21', 'N', 'S', '2019-04-02 15:00:45', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(130, 'commerce 21', 'N', 'S', '2019-04-02 15:00:56', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(131, 'BCOM 1st SEM (2019)', 'N', 'S', '2019-07-25 18:15:30', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(132, 'BBA 1st Year 2019', 'N', 'S', '2019-07-27 14:11:06', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(133, 'BA 1st Year 2019', 'N', 'S', '2019-07-27 14:11:19', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(134, 'BCA 1st year 2019', 'N', 'S', '2019-07-27 14:21:16', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(135, 'BDS 1st year 2019', 'N', 'S', '2019-07-27 14:23:20', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(136, 'BE/BTECH 1st year 2019', 'N', 'S', '2019-07-27 15:47:00', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(137, 'MCA 1st Year 2019', 'N', 'S', '2019-07-27 16:09:39', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(138, 'MCOM 1sy Year 2019', 'N', 'S', '2019-07-27 16:10:16', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(139, 'Tricity Custmers', 'N', 'C', '2019-08-08 11:07:27', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(140, 'IELTS/PTE', 'N', 'S', '2020-01-07 18:53:11', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(141, 'med 22', 'N', 'S', '2020-05-06 14:17:39', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(142, 'nm 22', 'N', 'S', '2020-05-06 14:17:48', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23'),
(143, 'commerce 22', 'N', 'S', '2020-05-09 12:54:50', '', '2020-06-19 07:02:23', '2020-06-19 07:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `customer_packages`
--

CREATE TABLE `customer_packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double(28,2) DEFAULT NULL,
  `product_upload` int(11) DEFAULT NULL,
  `logo` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_products`
--

CREATE TABLE `customer_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `added_by` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `subsubcategory_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `photos` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_img` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conditon` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8_unicode_ci,
  `video_provider` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_link` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `unit_price` double(28,2) DEFAULT '0.00',
  `meta_title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_img` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pdf` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flash_deals`
--

CREATE TABLE `flash_deals` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` int(20) DEFAULT NULL,
  `end_date` int(20) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `featured` int(1) NOT NULL DEFAULT '0',
  `background_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flash_deal_products`
--

CREATE TABLE `flash_deal_products` (
  `id` int(11) NOT NULL,
  `flash_deal_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount` double(8,2) DEFAULT '0.00',
  `discount_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int(11) NOT NULL,
  `frontend_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_login_background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_login_sidebar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `site_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_plus` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `frontend_color`, `logo`, `admin_logo`, `admin_login_background`, `admin_login_sidebar`, `favicon`, `site_name`, `address`, `description`, `phone`, `email`, `facebook`, `instagram`, `twitter`, `youtube`, `google_plus`, `created_at`, `updated_at`) VALUES
(1, 'default', 'uploads/logo/pfdIuiMeXGkDAIpPEUrvUCbQrOHu484nbGfz77zB.png', 'uploads/admin_logo/wCgHrz0Q5QoL1yu4vdrNnQIr4uGuNL48CXfcxOuS.png', NULL, NULL, 'uploads/favicon/uHdGidSaRVzvPgDj6JFtntMqzJkwDk9659233jrb.png', 'Amit Book Depot', 'SCO 210-211, GROUND FLOOR,  SECTOR-34A,CHANDIGARH', 'Active eCommerce CMS is a Multi vendor system is such a platform to build a border less marketplace.', '7696866526', 'brajkishorpandey@gmail.com', 'https://www.facebook.com', 'https://www.instagram.com', 'https://www.twitter.com', 'https://www.youtube.com', 'https://www.googleplus.com', '2020-07-03 10:47:44', '2020-07-03 05:17:44');

-- --------------------------------------------------------

--
-- Table structure for table `home_categories`
--

CREATE TABLE `home_categories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subsubcategories` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `home_categories`
--

INSERT INTO `home_categories` (`id`, `category_id`, `subsubcategories`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '[\"1\"]', 1, '2019-03-12 06:38:23', '2019-03-12 06:38:23'),
(2, 2, '[\"10\"]', 1, '2019-03-12 06:44:54', '2019-03-12 06:44:54');

-- --------------------------------------------------------

--
-- Table structure for table `institutes`
--

CREATE TABLE `institutes` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `isdeleted` enum('Y','N') NOT NULL DEFAULT 'N',
  `createdby` varchar(100) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `institutes`
--

INSERT INTO `institutes` (`id`, `name`, `isdeleted`, `createdby`, `created_at`, `updated_at`) VALUES
(1, 'ALLEN MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:35:01', '2020-06-19 09:21:30'),
(2, 'ALLEN NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:35:43', '2020-06-19 09:21:30'),
(3, 'HELIX MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:36:05', '2020-06-19 09:21:30'),
(4, 'HELIX NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:36:20', '2020-06-19 09:21:30'),
(5, 'AKASH MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:36:29', '2020-06-19 09:21:30'),
(6, 'AKASH NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:36:45', '2020-06-19 09:21:30'),
(7, 'ACE MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:36:58', '2020-06-19 09:21:30'),
(8, 'ACE NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:37:09', '2020-06-19 09:21:30'),
(9, 'AADHAR MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:37:25', '2020-06-19 09:21:30'),
(10, 'AADHAR NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:37:36', '2020-06-19 09:21:30'),
(11, 'LAKSHAY MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:37:49', '2020-06-19 09:21:30'),
(12, 'LAKSHAY NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:38:00', '2020-06-19 09:21:30'),
(13, 'OTHER', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:38:21', '2020-06-19 09:21:30'),
(14, 'ZETTA MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:38:49', '2020-06-19 09:21:30'),
(15, 'ZETTA NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-05-31 16:38:57', '2020-06-19 09:21:30'),
(16, 'SANGEETA KHANNA', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:41:52', '2020-06-19 09:21:30'),
(17, 'R N CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:42:13', '2020-06-19 09:21:30'),
(18, 'WAVES INSTITUTE PHYSICS', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:42:35', '2020-06-19 09:21:30'),
(19, 'ACE AXIS A P SINGH MATH', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:42:47', '2020-06-19 09:21:30'),
(20, 'CAREER JOINT MOHALI', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:43:43', '2020-06-19 09:21:30'),
(21, 'RISE INSTITUTE NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:44:02', '2020-06-19 09:21:30'),
(22, 'RISE INSTITUTE MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:44:14', '2020-06-19 09:21:30'),
(23, 'SANT ISHER SINGH SCHOOL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:44:26', '2020-06-19 09:21:30'),
(24, 'SHAILENDRA MATH SEC 19', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:44:37', '2020-06-19 09:21:30'),
(25, 'NEW PUBLIC SCHOOL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:44:46', '2020-06-19 09:21:30'),
(26, 'AAR ESS NONMED', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:46:11', '2020-06-19 09:21:30'),
(27, 'AAR ESS MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:46:20', '2020-06-19 09:21:30'),
(28, 'NARAYANA NONMED', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:47:45', '2020-06-19 09:21:30'),
(29, 'SRI CHAITANYA MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:47:57', '2020-06-19 09:21:30'),
(30, 'SRI CHAITANYA NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:48:06', '2020-06-19 09:21:30'),
(31, 'VIDYAMANDIR CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:49:26', '2020-06-19 09:21:30'),
(32, 'GANGULY CHEMISTRY CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:49:39', '2020-06-19 09:21:30'),
(33, 'KATARIA CHEMISTRY CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:50:02', '2020-06-19 09:21:30'),
(34, 'G S ARORA CHEMISTRY CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:50:13', '2020-06-19 09:21:30'),
(35, 'HARPAL BIOLOGY CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:50:40', '2020-06-19 09:21:30'),
(36, 'ARVIND GOYAL BIO CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:51:08', '2020-06-19 09:21:30'),
(37, 'GEETA CLASSES KHARAR', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:51:45', '2020-06-19 09:21:30'),
(38, 'SCIENCE TUTORIAL NURSING', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:52:28', '2020-06-19 09:21:30'),
(39, 'SANJAY AHLAWAT PHYSICS', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:52:38', '2020-06-19 09:21:30'),
(40, 'NAVNEET CHEMISTRY CLASSES', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:52:47', '2020-06-19 09:21:30'),
(41, 'MATH SATISH GUPTA', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:53:00', '2020-06-19 09:21:30'),
(42, 'SANJAY MISHRA MATH ', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:53:15', '2020-06-19 09:21:30'),
(43, 'PC MEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:54:37', '2020-06-19 09:21:30'),
(44, 'PC NONMEDICAL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:54:45', '2020-06-19 09:21:30'),
(45, 'BHAWNA BANSAL BEST CHEMISTRY ', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:55:38', '2020-06-19 09:21:30'),
(46, 'ANURAG AGGARWAL KESHAV CHEMISTRY', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:55:59', '2020-06-19 09:21:30'),
(47, 'MUNISH KAKKAR CHEMISTRY', 'N', 'amitbookdepot.com@gmail.com', '2018-06-04 07:57:41', '2020-06-19 09:21:30'),
(48, 'FIIT JEE', 'N', 'amitbookdepot.com@gmail.com', '2018-06-05 10:29:40', '2020-06-19 09:21:30'),
(49, 'Patiala', 'N', 'amitbookdepot.com@gmail.com', '2018-06-08 09:37:26', '2020-06-19 09:21:30'),
(50, 'BANSAL CLASSES NON MED', 'N', 'chander.ramesh85@gmail.com', '2018-06-14 07:59:56', '2020-06-19 09:21:30'),
(51, 'BANSAL CLASSES  MEDICAL', 'N', 'chander.ramesh85@gmail.com', '2018-06-14 08:00:21', '2020-06-19 09:21:30'),
(52, 'PC BOTH', 'N', 'amitbookdepot.com@gmail.com', '2018-06-16 14:27:31', '2020-06-19 09:21:30'),
(53, 'website', 'N', 'amitbookdepot.COM@gmail.com', '2018-06-18 17:04:17', '2020-06-19 09:21:30'),
(54, 'BIOCELL', 'N', 'amitbookdepot.com@gmail.com', '2018-06-27 06:43:20', '2020-06-19 09:21:30'),
(55, 'SSG SECTOR 35B', 'N', 'amitbookdepot.com@gmail.com', '2018-06-28 07:25:20', '2020-06-19 09:21:30'),
(56, 'aagaz patiala', 'N', 'amitbookdepot.com@gmail.com', '2018-07-26 10:36:41', '2020-06-19 09:21:30'),
(57, 'Shishu Niketan Sector 22 Chd', 'N', 'amitbookdepot.com@gmail.com', '2018-08-02 14:36:25', '2020-06-19 09:21:30'),
(58, 'Shishu Niketan 43', 'N', 'amitbookdepot.com@gmail.com', '2018-09-24 07:03:00', '2020-06-19 09:21:30'),
(59, 'Shivalik 41', 'N', 'amitbookdepot.com@gmail.com', '2018-10-15 11:58:12', '2020-06-19 09:21:30'),
(60, 'DAV Public 8C', 'N', 'amitbookdepot.com@gmail.com', '2018-10-19 14:40:22', '2020-06-19 09:21:30'),
(61, 'KB DAV Sec 7', 'N', 'amitbookdepot.com@gmail.com', '2018-10-27 11:15:48', '2020-06-19 09:21:30'),
(62, 'Anju Gupta Chemistry Classes', 'N', 'amitbookdepot.com@gmail.com', '2018-11-27 09:04:15', '2020-06-19 09:21:30'),
(63, 'GNP 36', 'N', 'amitbookdepot.com@gmail.com', '2018-11-27 09:46:07', '2020-06-19 09:21:30'),
(64, 'DAV 39', 'N', 'amitbookdepot.com@gmail.com', '2018-12-20 10:45:07', '2020-06-19 09:21:30'),
(65, 'shishu niketan pkl', 'N', 'amitbookdepot.com@gmail.com', '2018-12-21 06:41:27', '2020-06-19 05:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `rtl` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `rtl`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 0, '2019-01-20 12:13:20', '2019-01-20 12:13:20'),
(3, 'Bangla', 'bd', 0, '2019-02-17 06:35:37', '2019-02-18 06:49:51'),
(4, 'Arabic', 'sa', 1, '2019-04-28 18:34:12', '2019-04-28 18:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manual_payment_methods`
--

CREATE TABLE `manual_payment_methods` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `heading` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `bank_info` text COLLATE utf8_unicode_ci,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text COLLATE utf32_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('125ce8289850f80d9fea100325bf892fbd0deba1f87dbfc2ab81fb43d57377ec24ed65f7dc560e46', 1, 1, 'Personal Access Token', '[]', 0, '2019-07-30 04:51:13', '2019-07-30 04:51:13', '2020-07-30 10:51:13'),
('293d2bb534220c070c4e90d25b5509965d23d3ddbc05b1e29fb4899ae09420ff112dbccab1c6f504', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 06:00:04', '2019-08-04 06:00:04', '2020-08-04 12:00:04'),
('5363e91c7892acdd6417aa9c7d4987d83568e229befbd75be64282dbe8a88147c6c705e06c1fb2bf', 1, 1, 'Personal Access Token', '[]', 0, '2019-07-13 06:44:28', '2019-07-13 06:44:28', '2020-07-13 12:44:28'),
('681b4a4099fac5e12517307b4027b54df94cbaf0cbf6b4bf496534c94f0ccd8a79dd6af9742d076b', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 07:23:06', '2019-08-04 07:23:06', '2020-08-04 13:23:06'),
('6d229e3559e568df086c706a1056f760abc1370abe74033c773490581a042442154afa1260c4b6f0', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 07:32:12', '2019-08-04 07:32:12', '2020-08-04 13:32:12'),
('6efc0f1fc3843027ea1ea7cd35acf9c74282f0271c31d45a164e7b27025a315d31022efe7bb94aaa', 1, 1, 'Personal Access Token', '[]', 0, '2019-08-08 02:35:26', '2019-08-08 02:35:26', '2020-08-08 08:35:26'),
('7745b763da15a06eaded371330072361b0524c41651cf48bf76fc1b521a475ece78703646e06d3b0', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 07:29:44', '2019-08-04 07:29:44', '2020-08-04 13:29:44'),
('815b625e239934be293cd34479b0f766bbc1da7cc10d464a2944ddce3a0142e943ae48be018ccbd0', 1, 1, 'Personal Access Token', '[]', 1, '2019-07-22 02:07:47', '2019-07-22 02:07:47', '2020-07-22 08:07:47'),
('8921a4c96a6d674ac002e216f98855c69de2568003f9b4136f6e66f4cb9545442fb3e37e91a27cad', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 06:05:05', '2019-08-04 06:05:05', '2020-08-04 12:05:05'),
('8d8b85720304e2f161a66564cec0ecd50d70e611cc0efbf04e409330086e6009f72a39ce2191f33a', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 06:44:35', '2019-08-04 06:44:35', '2020-08-04 12:44:35'),
('bcaaebdead4c0ef15f3ea6d196fd80749d309e6db8603b235e818cb626a5cea034ff2a55b66e3e1a', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 07:14:32', '2019-08-04 07:14:32', '2020-08-04 13:14:32'),
('c25417a5c728073ca8ba57058ded43d496a9d2619b434d2a004dd490a64478c08bc3e06ffc1be65d', 1, 1, 'Personal Access Token', '[]', 1, '2019-07-30 01:45:31', '2019-07-30 01:45:31', '2020-07-30 07:45:31'),
('c7423d85b2b5bdc5027cb283be57fa22f5943cae43f60b0ed27e6dd198e46f25e3501b3081ed0777', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-05 05:02:59', '2019-08-05 05:02:59', '2020-08-05 11:02:59'),
('e76f19dbd5c2c4060719fb1006ac56116fd86f7838b4bf74e2c0a0ac9696e724df1e517dbdb357f4', 1, 1, 'Personal Access Token', '[]', 1, '2019-07-15 02:53:40', '2019-07-15 02:53:40', '2020-07-15 08:53:40'),
('ed7c269dd6f9a97750a982f62e0de54749be6950e323cdfef892a1ec93f8ddbacf9fe26e6a42180e', 1, 1, 'Personal Access Token', '[]', 1, '2019-07-13 06:36:45', '2019-07-13 06:36:45', '2020-07-13 12:36:45'),
('f6d1475bc17a27e389000d3df4da5c5004ce7610158b0dd414226700c0f6db48914637b4c76e1948', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 07:22:01', '2019-08-04 07:22:01', '2020-08-04 13:22:01'),
('f85e4e444fc954430170c41779a4238f84cd6fed905f682795cd4d7b6a291ec5204a10ac0480eb30', 1, 1, 'Personal Access Token', '[]', 1, '2019-07-30 06:38:49', '2019-07-30 06:38:49', '2020-07-30 12:38:49'),
('f8bf983a42c543b99128296e4bc7c2d17a52b5b9ef69670c629b93a653c6a4af27be452e0c331f79', 1, 1, 'Personal Access Token', '[]', 1, '2019-08-04 07:28:55', '2019-08-04 07:28:55', '2020-08-04 13:28:55');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'eR2y7WUuem28ugHKppFpmss7jPyOHZsMkQwBo1Jj', 'http://localhost', 1, 0, 0, '2019-07-13 06:17:34', '2019-07-13 06:17:34'),
(2, NULL, 'Laravel Password Grant Client', 'WLW2Ol0GozbaXEnx1NtXoweYPuKEbjWdviaUgw77', 'http://localhost', 0, 1, 0, '2019-07-13 06:17:34', '2019-07-13 06:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-07-13 06:17:34', '2019-07-13 06:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `shipping_address` longtext COLLATE utf8_unicode_ci,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manual_payment` int(1) NOT NULL DEFAULT '0',
  `manual_payment_data` text COLLATE utf8_unicode_ci,
  `payment_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'unpaid',
  `payment_details` longtext COLLATE utf8_unicode_ci,
  `grand_total` double(8,2) DEFAULT NULL,
  `coupon_discount` double(8,2) NOT NULL DEFAULT '0.00',
  `code` mediumtext COLLATE utf8_unicode_ci,
  `date` int(20) NOT NULL,
  `viewed` int(1) NOT NULL DEFAULT '0',
  `delivery_viewed` int(1) NOT NULL DEFAULT '1',
  `payment_status_viewed` int(1) DEFAULT '1',
  `commission_calculated` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `guest_id`, `shipping_address`, `payment_type`, `manual_payment`, `manual_payment_data`, `payment_status`, `payment_details`, `grand_total`, `coupon_discount`, `code`, `date`, `viewed`, `delivery_viewed`, `payment_status_viewed`, `commission_calculated`, `created_at`, `updated_at`) VALUES
(1, 8, NULL, '{\"name\":\"Mr. Customer\",\"email\":null,\"address\":\"chandigarh\",\"country\":\"India\",\"city\":\"Chandigarh\",\"postal_code\":\"23121\",\"phone\":\"7696866526\",\"checkout_type\":\"logged\"}', 'paytm', 0, NULL, 'unpaid', NULL, 1800.00, 0.00, '20200712-16191486', 1594570754, 0, 0, 0, 0, '2020-07-12 10:49:14', '2020-07-12 10:49:14'),
(2, 8, NULL, '{\"name\":\"Mr. Customer\",\"email\":null,\"address\":\"chandigarh\",\"country\":\"India\",\"city\":\"Chandigarh\",\"postal_code\":\"23121\",\"phone\":\"7696866526\",\"checkout_type\":\"logged\"}', 'paytm', 0, NULL, 'unpaid', NULL, 1800.00, 0.00, '20200712-16201149', 1594570811, 0, 0, 0, 0, '2020-07-12 10:50:11', '2020-07-12 10:50:11'),
(3, 8, NULL, '{\"name\":\"Mr. Customer\",\"email\":null,\"address\":\"chandigarh\",\"country\":\"India\",\"city\":\"Chandigarh\",\"postal_code\":\"23121\",\"phone\":\"7696866526\",\"checkout_type\":\"logged\"}', 'paytm', 0, NULL, 'unpaid', NULL, 1800.00, 0.00, '20200712-16251155', 1594571111, 0, 0, 0, 0, '2020-07-12 10:55:11', '2020-07-12 10:55:11'),
(4, 8, NULL, '{\"name\":\"Mr. Customer\",\"email\":null,\"address\":\"chandigarh\",\"country\":\"India\",\"city\":\"Chandigarh\",\"postal_code\":\"23121\",\"phone\":\"7696866526\",\"checkout_type\":\"logged\"}', 'paytm', 0, NULL, 'unpaid', NULL, 1800.00, 0.00, '20200712-16292615', 1594571366, 0, 0, 0, 0, '2020-07-12 10:59:26', '2020-07-12 10:59:26'),
(5, 8, NULL, '{\"name\":\"Mr. Customer\",\"email\":null,\"address\":\"chandigarh\",\"country\":\"India\",\"city\":\"Chandigarh\",\"postal_code\":\"23121\",\"phone\":\"7696866526\",\"checkout_type\":\"logged\"}', 'paytm', 0, NULL, 'unpaid', NULL, 1800.00, 0.00, '20200712-16304259', 1594571442, 1, 0, 0, 0, '2020-07-12 11:00:42', '2020-07-15 01:26:36'),
(6, 27, NULL, '{\"name\":\"amit\",\"email\":null,\"address\":\"chandigarh\",\"country\":\"Afghanistan\",\"city\":\"Chandigarh\",\"postal_code\":\"23121\",\"phone\":\"7696866526\",\"checkout_type\":\"logged\"}', 'paytm', 0, NULL, 'unpaid', NULL, NULL, 0.00, '20200715-06531032', 1594795990, 0, 0, 0, 0, '2020-07-15 01:23:10', '2020-07-15 01:23:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `variation` longtext COLLATE utf8_unicode_ci,
  `price` double(8,2) DEFAULT NULL,
  `tax` double(8,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` double(8,2) NOT NULL DEFAULT '0.00',
  `quantity` int(11) DEFAULT NULL,
  `payment_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid',
  `delivery_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'pending',
  `shipping_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pickup_point_id` int(11) DEFAULT NULL,
  `product_referral_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `seller_id`, `product_id`, `variation`, `price`, `tax`, `shipping_cost`, `quantity`, `payment_status`, `delivery_status`, `shipping_type`, `pickup_point_id`, `product_referral_code`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 168, 'Amethyst-L-old', 1800.00, 0.00, 0.00, 10, 'unpaid', 'pending', 'home_delivery', NULL, NULL, '2020-07-12 10:49:14', '2020-07-12 10:49:14'),
(2, 2, 12, 168, 'Amethyst-L-old', 1800.00, 0.00, 0.00, 10, 'unpaid', 'pending', 'home_delivery', NULL, NULL, '2020-07-12 10:50:11', '2020-07-12 10:50:11'),
(3, 3, 12, 168, 'Amethyst-L-old', 1800.00, 0.00, 0.00, 10, 'unpaid', 'pending', 'home_delivery', NULL, NULL, '2020-07-12 10:55:11', '2020-07-12 10:55:11'),
(4, 4, 12, 168, 'Amethyst-L-old', 1800.00, 0.00, 0.00, 10, 'unpaid', 'pending', 'home_delivery', NULL, NULL, '2020-07-12 10:59:26', '2020-07-12 10:59:26'),
(5, 5, 12, 168, 'Amethyst-L-old', 1800.00, 0.00, 0.00, 10, 'unpaid', 'pending', 'home_delivery', NULL, NULL, '2020-07-12 11:00:42', '2020-07-12 11:00:42');

-- --------------------------------------------------------

--
-- Table structure for table `otp_configurations`
--

CREATE TABLE `otp_configurations` (
  `id` int(11) NOT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `otp_configurations`
--

INSERT INTO `otp_configurations` (`id`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'nexmo', '0', '2020-03-22 09:19:07', '2020-07-07 02:21:19'),
(2, 'otp_for_order', '1', '2020-03-22 09:19:07', '2020-03-22 09:19:07'),
(3, 'otp_for_delivery_status', '1', '2020-03-22 09:19:37', '2020-03-22 09:19:37'),
(4, 'otp_for_paid_status', '0', '2020-03-22 09:19:37', '2020-03-22 09:19:37'),
(5, 'twillo', '0', '2020-03-22 09:54:03', '2020-03-22 03:54:20'),
(6, 'ssl_wireless', '0', '2020-03-22 09:54:03', '2020-03-22 03:54:20'),
(7, 'fast2sms', '0', '2020-03-22 09:54:03', '2020-07-07 02:21:16'),
(8, 'nicesms', '1', '2020-03-22 09:54:03', '2020-07-07 02:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `meta_title` text COLLATE utf8_unicode_ci,
  `meta_description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL DEFAULT '0.00',
  `payment_details` longtext COLLATE utf8_unicode_ci,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `txn_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pickup_points`
--

CREATE TABLE `pickup_points` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `pick_up_status` int(1) DEFAULT NULL,
  `cash_on_pickup_status` int(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` int(11) NOT NULL,
  `name` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `name`, `content`, `created_at`, `updated_at`) VALUES
(1, 'support_policy', NULL, '2019-10-29 12:54:45', '2019-01-22 05:13:15'),
(2, 'return_policy', NULL, '2019-10-29 12:54:47', '2019-01-24 05:40:11'),
(4, 'seller_policy', NULL, '2019-10-29 12:54:49', '2019-02-04 17:50:15'),
(5, 'terms', NULL, '2019-10-29 12:54:51', '2019-10-28 18:00:00'),
(6, 'privacy_policy', NULL, '2019-10-29 12:54:54', '2019-10-28 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `added_by` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'admin',
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `subsubcategory_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `author` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `version` enum('old','new') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `isbn` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oldisbn` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `photos` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_img` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featured_img` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flash_deal_img` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_provider` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tags` mediumtext COLLATE utf8_unicode_ci,
  `description` longtext COLLATE utf8_unicode_ci,
  `unit_price` double(8,2) NOT NULL,
  `purchase_price` double(8,2) NOT NULL,
  `mrp` double(8,2) NOT NULL DEFAULT '0.00',
  `minstock` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bundleprice` varchar(2000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `erpprice` double(8,2) NOT NULL DEFAULT '0.00',
  `minorderqty` int(11) NOT NULL DEFAULT '1',
  `maxorderqty` int(11) NOT NULL DEFAULT '1',
  `onrent` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `securityamount` double(8,2) NOT NULL DEFAULT '0.00',
  `rentamount` double(8,2) NOT NULL DEFAULT '0.00',
  `variant_product` int(1) NOT NULL DEFAULT '0',
  `attributes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[]',
  `choice_options` mediumtext COLLATE utf8_unicode_ci,
  `colors` mediumtext COLLATE utf8_unicode_ci,
  `variations` text COLLATE utf8_unicode_ci,
  `todays_deal` int(11) NOT NULL DEFAULT '0',
  `published` int(11) NOT NULL DEFAULT '1',
  `featured` int(11) NOT NULL DEFAULT '0',
  `current_stock` int(10) NOT NULL DEFAULT '0',
  `unit` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount` double(8,2) DEFAULT NULL,
  `discount_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax` double(8,2) DEFAULT NULL,
  `cgst` double(8,2) NOT NULL DEFAULT '0.00',
  `igst` double(8,2) NOT NULL DEFAULT '0.00',
  `sgst` double(8,2) NOT NULL DEFAULT '0.00',
  `tax_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'flat_rate',
  `shipping_cost` double(8,2) DEFAULT '0.00',
  `num_of_sale` int(11) NOT NULL DEFAULT '0',
  `meta_title` mediumtext COLLATE utf8_unicode_ci,
  `meta_description` longtext COLLATE utf8_unicode_ci,
  `meta_img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pdf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `refundable` int(1) NOT NULL DEFAULT '0',
  `rating` double(8,2) NOT NULL DEFAULT '0.00',
  `barcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `digital` int(1) NOT NULL DEFAULT '0',
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `added_by`, `user_id`, `category_id`, `subcategory_id`, `subsubcategory_id`, `brand_id`, `author`, `version`, `isbn`, `oldisbn`, `photos`, `thumbnail_img`, `featured_img`, `flash_deal_img`, `video_provider`, `video_link`, `tags`, `description`, `unit_price`, `purchase_price`, `mrp`, `minstock`, `bundleprice`, `erpprice`, `minorderqty`, `maxorderqty`, `onrent`, `securityamount`, `rentamount`, `variant_product`, `attributes`, `choice_options`, `colors`, `variations`, `todays_deal`, `published`, `featured`, `current_stock`, `unit`, `discount`, `discount_type`, `tax`, `cgst`, `igst`, `sgst`, `tax_type`, `shipping_type`, `shipping_cost`, `num_of_sale`, `meta_title`, `meta_description`, `meta_img`, `pdf`, `slug`, `refundable`, `rating`, `barcode`, `digital`, `file_name`, `file_path`, `created_at`, `updated_at`) VALUES
(15, 'Electrochemical Cell Working Model with Printed Report', 'admin', 12, 2, 4, 10, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/gRUAvzYELozN0oD28wOZAddjgcg6TpCIJmHWhh5j.jpeg\",\"uploads\\/products\\/photos\\/2oXWo91MgZKKAO1ZNrPM8IyOiTwl1ug1ATIU5Zrt.jpeg\"]', 'uploads/products/thumbnail/PDHV6DwYC2AP8LABgLanogzy0gWV1rymgMAonVNM.jpeg', 'uploads/products/featured/0wM8cZ7qY4ST0ZZ5BxazsbXyluymas4tI0D4OV9X.jpeg', 'uploads/products/flash_deal/pyrZGDVvyFObnuupC7vmuDCrElfQVUrb22ixXOR3.jpeg', 'youtube', 'https://www.youtube.com/watch?v=9vDDM8G9tcs', 'test', 'test product<br>', 200.00, 150.00, 250.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', NULL, 0, 1, 0, 0, '10', 10.00, 'percent', 12.00, 6.00, 0.00, 6.00, 'percent', 'local_pickup', 0.00, 0, NULL, NULL, NULL, NULL, 'Electrochemical-Cell-Working-Model-with-Printed-Report-kKmkx', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 04:33:33', '2020-06-01 02:09:10'),
(134, 'MBD History of India (1200 to 1750 AD) for BA 2nd Sem PU (English) (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/phfEh8i1yN9YIlPIKoM3MIsBm84W96Nv03Le9haa.jpeg\"]', 'uploads/products/thumbnail/1901M4823C5703.jpg', 'uploads/products/featured/1901M4823C5703.jpg', 'uploads/products/flash_deal/1901M4823C5703.jpg', 'youtube', 'https://www.youtube.com/watch?v=9vDDM8G9tcs', 'test', 'test product<br>', 225.00, 225.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', NULL, 1, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, NULL, NULL, NULL, 'MBD-History-of-India-1200-to-1750-AD-for-BA-2nd-Sem-PU-English-NEW-h-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 14:57:35', '2020-05-19 01:38:22'),
(135, 'MBD History of The Punjab for BA 4th Sem PU (English) (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/1812M4823C5669.jpg\"]', 'uploads/products/thumbnail/1812M4823C5669.jpg', 'uploads/products/featured/1812M4823C5669.jpg', 'uploads/products/flash_deal/1812M4823C5669.jpg', '', '', 'test', '', 310.00, 310.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', '', '', '', 'mbd-history-of-the-punjab-for-ba-4th-sem-pu-english-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 14:57:35', '2020-05-17 09:48:57'),
(142, '41 Years Chemistry for JEE Main and Advanced (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789388026123.jpg\"]', 'uploads/products/thumbnail/9789388026123.jpg', 'uploads/products/featured/9789388026123.jpg', 'uploads/products/flash_deal/9789388026123.jpg', '', '', 'test', '<p>\r\n	The book &ldquo;39 Years IIT-JEE Advanced + 15 yrs JEE Main/ AIEEE Topic-wise Solved Paper MATHEMATICS with Free ebook&rdquo; is the first integrated book, which contains Topic-wise collection of past JEE Advanced (including 1978-2012 IIT-JEE &amp; 2013-16 JEE Advanced) questions from 1978 to 2016 and past JEE Main (including 2002-2012 AIEEE &amp; 2013-16 JEE Main) questions from 2002 to 2016. &bull; The new edition has been designed in 2-colour layout and comes with a Free ebook which gives you the power of accessing your book anywhere - anytime through web and tablets. &bull; The book is divided into 23 chapters. The flow of chapters has been aligned as per the NCERT books. &bull; Each divides the questions into 9 categories (as per the NEW IIT pattern) - Fill in the Blanks, True/False, MCQ 1 correct, MCQ more than 1 correct, Passage Based, Assertion-Reason, Multiple Matching, Integer Answer MCQs and Subjective Questions. &bull; All the Screening and Mains papers of IIT-JEE have been incorporated in the book. &bull; Detailed solution of each and every question has been provided for 100% conceptual clarity of the student. Well elaborated detailed solutions with user friendly language provided at the end of each chapter. &bull; Solutions have been given with enough diagrams, proper reasoning to bring conceptual clarity. &bull; The students are advised to attempt questions of a topic immediately after they complete a topic in their class/school/home. The book contains around 3200+ MILESTONE PROBLEMS IN CHEMISTRY. How does the FREE ebook help? &bull; Provides the Digital version of the book which can be accessed through tablets and web in both online and offline mediums. &bull; Also provides the AIEEE Rescheduled 2011 paper and 1997 IIT-JEE cancelled paper. &bull; Alternate Solutions to a number of Questions. &bull; Quick Revision Material.</p>', 500.00, 500.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'disha 40 years jee main and advanced chemistry 40 years disha chemistry disha 40 years chemistry disha publication 40 year chemistry disha chemistry 40 years disa chemistry 40 years disha past papers disha previous year papers disha last 40 years papers disha chemistry 40 years', '', '', '41-years-chemistry-for-jee-main-and-advanced-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 09:48:20'),
(144, 'Understanding Physics Electricity and Magnetism for JEE Main and Advanced (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789312147184.jpg\"]', 'uploads/products/thumbnail/9789312147184.jpg', 'uploads/products/featured/9789312147184.jpg', 'uploads/products/flash_deal/9789312147184.jpg', '', '', 'test', '<p>\r\n	A powerhouse of knowledge, a foundation stone for the future success, the textbooks have always held their relevance high be it for a first grader or the one preparing for IIT JEE. Same is the significance of textbooks which play a crucial role in achieving success in various engineering entrances like IIT JEE Mains &amp; Advanced. The textbooks designed for engineering entrances aim at presenting the in-depth knowledge which helps the students grasp the concepts better leading to a strong foundation of exhaustive knowledge and ultimately the success seems near.<br />\r\n	Understanding Physics for JEE Main &amp; Advanced authored by Mr. D.C. Pandey has become a synonym with success in <strong>JEE Main &amp; Advanced.Electricity &amp; Magnetism of Understanding Physics</strong> Series&nbsp;closely examines the <strong>concepts of electricity and magnetism</strong>, and the relationship between the two that makes the electromagnetism such an important and all-pervasive component of our existence. The book has been divided into six chapters namely&nbsp;<strong>Current Electricity, Electrostatics, Capacitors, Magnetic, Electromagnetic Induction</strong>&nbsp;and&nbsp;Alternating Current, each focusing on concept building &amp; application of the concepts in solving varied physical problems. This is the only book having detailed Text Matter along with all types of questions like Single Correct Option, Multiple Correct Option, Assertion-Reason, Comprehension Based Questions and Single Integer Answers. The exercises have been divided into two sections i.e. JEE Main and JEE Advanced. The book also includes questions on Experimental Physics and &lsquo;Hints &amp; Solutions&rsquo; section at the end.<br />\r\n	Each chapter in the book contains theoretical content as well as practice exercises to facilitate proper understanding of the topics. The theoretical content provided in the book has been well explained with diagrams, graphs, illustrations and tables to facilitate easy understanding. Also introductory exercises have been included for each topic to help students test their abilities. After the comprehensive study of the concepts, solved problems based on the same have also been given in the book. At the end of the book Hints &amp; Solutions for all the exercises have been given for comprehensive understanding of the concepts on which the problems are based.</p>', 440.00, 440.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'D C PANDEY ARIHANT ELECTRICITY AND MAGNETISM DC PANDEY ELECTRICITY AND MAGNETISM D C PANDEY ELECTRICITY AND MAGNETISM ARIHANT DC PANDEY ELCTICITY AND MAGNETISM  ARIHANT ELECTRICITY AND MAGNETISM', '', '', 'understanding-physics-electricity-and-magnetism-for-jee-main-and-advanced-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 09:48:27'),
(145, 'Understanding Physics Optics and Modern Physics for JEE Main and Advanced (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789312147207.jpg\"]', 'uploads/products/thumbnail/9789312147207.jpg', 'uploads/products/featured/9789312147207.jpg', 'uploads/products/flash_deal/9789312147207.jpg', '', '', 'test', '<h5>\r\n	A powerhouse of knowledge, a foundation stone for the future success, the textbooks have always held their relevance high be it for a first grader or the one preparing for IIT JEE. Same is the significance of textbooks which play a crucial role in achieving success in various engineering entrances like IIT JEE Mains and Advanced. The textbooks designed for engineering entrances aim at presenting the in-depth knowledge which helps the students grasp the concepts better leading to a strong foundation of exhaustive knowledge and ultimately the success seems near.<br />\r\n	Understanding Physics for JEE Main and Advanced authored by Mr. D.C. Pandey has become a synonym with success in JEE Main and Advanced.&nbsp;Optics and Modern Physics of Understanding Physics Series&nbsp;takes a balanced approach to the essential components of both the Ray optics and the Modern Physics. The book has been divided into eight chapters Electromagnetic Waves, Reflection of Light, Refraction of Light, Interference and Diffraction of Light, Modern Physics-I, Modern Physics-II, Semiconductors and Communication System,each focusing on concept building and application of the concepts in solving varied physical problems. This is the only book having detailed Text Matter along with all types of questions like Single Correct Option, Multiple Correct Option, Assertion-Reason, Comprehension Based Questions and Single Integer Answers. The exercises have been divided into two sections i.e. JEE Main and JEE Advanced. The book also includes questions on Experimental Physics and &lsquo;Hints and Solutions&rsquo; section at the end.<br />\r\n	Each chapter in the book contains theoretical content as well as practice exercises to facilitate proper understanding of the topics. The theoretical content provided in the book has been well explained with diagrams, graphs, illustrations and tables to facilitate easy understanding. Also introductory exercises have been included for each topic to help students test their abilities. After the comprehensive study of the concepts, solved problems based on the same have also been given in the book. At the end of the book Hints and Solutions for all the exercises have been given for comprehensive understanding of the concepts on which the problems are based.</h5>', 355.00, 355.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'Optics and Modern Physics of Understanding Physics Series takes a balanced approach to the essential components of both the Ray optics and the Modern Physics. The book has been divided into eight chapters Electromagnetic Waves, Reflection of Light, Refraction of Light, Interference and Diffraction of Light, Modern Physics-I, Modern Physics-II, Semiconductors and Communication System,each focusing', '', '', 'understanding-physics-optics-and-modern-physics-for-jee-main-and-advanced-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 09:48:28'),
(146, '40 Years Chapterwise Topicwise Solved Papers of Physics for JEE Main and Advanced (NEW)', 'admin', 12, 2, 4, 10, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/9789313163299.jpg\"]', 'uploads/products/thumbnail/9789313163299.jpg', 'uploads/products/featured/9789313163299.jpg', 'uploads/products/flash_deal/9789313163299.jpg', 'youtube', NULL, 'test', '<p>\r\n	The aspirants preparing for JEE Main and Advanced need to be very dedicated and focused with their efforts and preparation in order to do well in the examinations as getting into an IIT is not an easy task. Every year a large number of students dream of getting into IITs, the premier engineering institutes of our country, but only the ones with thorough preparation and determination succeed in getting admission in undergraduate engineering programs at IITs. It is all about practice and with this best-selling resource from Arihant students preparing for JEE Main &amp; Advanced can get themselves perfected and have an upper edge over other students.<br>\r\n	The present book for JEE Main and Advanced Physics has been divided into 17 Chapters namely General Physics, Kinematics, Laws of Motions, Work, Power and Energy, Center of Mass, Rotation, Gravitation, Simple Harmonic Motion, Properties of Matter, Wave Motion, <strong>Heat and Thermodynamics, Optics, Current Electricity, Electrostatics, Magnetic, Electromagnetic Induction and Alternating Current and Modern Physics</strong>, according to the syllabic of JEE Main &amp; Advanced. This specialized book contains last 39 Years’ (1979-2017) Chapter wise Solved Questions of IIT JEE&nbsp;Physics&nbsp;along with previous years’ solved papers of IIT JEE and JEE Main &amp; Advanced. The entire syllabus of Class 11th and 12th has been dealt with comprehensively in this book. The questions asked in previous years’ examinations have been solved with their authentic and accurate solutions and have been provided chapter wise and topic wise in this book. Also wherever required necessary study material required for comprehensive understanding has been included in each chapter. The book also contains Solved Paper 2015 &amp; 2016 Solved Papers to help aspirants get an insight into the current pattern of the examination and the types of questions asked therein.</p>\r\n\r\n<p>\r\n	As the book contains ample number of previous solved questions and relevant theoretical material, it for sure will help the aspirants score higher in the upcoming JEE Main and Advanced Entrance Examination 2017.</p>', 380.00, 380.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, '39 Years Chapterwise Topicwise Solved Papers IIT JEE (Jee Main & Advanced) Physics has been divided into 17 Chapters namely General Physics, Kinematics, Laws of Motions, Work, Power and Energy, Center of Mass, Rotation, Gravitation, Simple Harmonic Motion, Properties of Matter, Wave Motion, Heat and Thermodynamics, Optics, Current Electricity, Electrostatics, Magnetic, Electromagnetic Induction an', NULL, '', '40-Years-Chapterwise-Topicwise-Solved-Papers-of-Physics-for-JEE-Main-and-Advanced-NEW-d-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-23 04:35:08'),
(147, 'Objective Physics for Neet Vol 1 (MBBS and BDS) (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789312146958.jpg\"]', 'uploads/products/thumbnail/9789312146958.jpg', 'uploads/products/featured/9789312146958.jpg', 'uploads/products/flash_deal/9789312146958.jpg', '', '', 'test', '<p>\r\n	&nbsp;</p>\r\n\r\n<p>\r\n	&nbsp;</p>\r\n\r\n<div id=&#34;ctl00_ContentPlaceHolder1_divAboutBook&#34;>\r\n	<p>\r\n		Various popular national and regional medical entrances like<strong> NEET, CBSE AIPMT, AIIMS, WBJEE, MH-CET</strong>, etc demand thorough knowledge of the concepts covered under the syllabic of the examinations. And to help aspirants master the concepts of Physics, Arihant has come up with the revised edition of Objective Physics Volume 1 designed especially for the aspirants preparing for National Eligibility cum Entrance Test (NEET) 2017.<br />\r\n		The present book for NEET 2017 based on the Class XI Physics syllabus has been designed to help aspirants prepare for the competitions along with their school studies. The book contains the best available collection of objective questions and has been designed to work as a self-study guide for Physics for NEET and all other national &amp; regional medical entrance examinations. The book contains ample number of objective questions of all types like single option correct, matching, assertion-reason and statement based questions.The book has been divided into <strong>17 chapters namely Units, Dimensions &amp; Error Analysis, Basic Mathematics &amp; Vectors, Motion in One Dimension, Projectile Motion, Laws of Motion, Work, Energy &amp; Power, Circular Motion, Center of Mass, Conservation of Linear Momentum Impulse &amp; Collision, Rotation, Gravitation, Simple Harmonic Motion, Elasticity, Fluid Mechanics, Thermometer, Thermal Expansion &amp; Kinetic Theory of Gases, The First Law of Thermodynamics, Calorimetry &amp; Heat Transfer and Wave Motion, each sub-divided into number of topics</strong>. The questions covered in the book have been designed to ensure comprehensive knowledge of the concepts and also their applications for solving different types of problems. The questions in the book incorporate the syllabic of almost all medical entrances in India.&nbsp; Each chapter contains objective questions presented in two levels, Level I covering the basic questions and Level II covering higher difficulty order questions. Each chapter also contains a collection of questions asked in different national and regional medical entrances. The exercises have been solved in detail to help students understand the concepts better and effectively. The book also contains questions asked in last four years&rsquo; (2012-2015) medical entrance examinations which will help aspirants get an insight into the types of questions asked in recent years. Also 2016 NEET Phase I &amp; Phase II Solved Papers have&nbsp;been provided in the book along with three Mock Tests for NEET to help aspirants get insight into the recent examination pattern and the types of questions asked therein.<br />\r\n		With ample collection of questions which may be asked in the upcoming NEET and other medical entrances as well as previous years&rsquo; solved questions, this book for sure will act as the perfect resource book to master the concepts of Class XI Physics curriculum and also the skills required to tackle the questions asked in different formats in NEET and other medical entrances.</p>\r\n</div>', 670.00, 670.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'ARIHANT NEET OBJECTIVE PHYSICS MEDICAL OBJECTIVE PHYSICS VOL 1 ARIHANT  NEET OBJECTIVE PHYSICS DC PANDEY OBJECTIVE PHYSICS ARIHANT OBJECTIVE PHYSICS VOLUME 1 D C PANDEY NEET OBJECTIVE PHYSICS VOLUME 1 NEET OBJECTIVE PHYSICS VOL 1 NEET OBJECTIVE PHYSICS FOR NEET ARIHANT', '', '', 'objective-physics-for-neet-vol-1-mbbs-and-bds-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 09:48:32'),
(150, 'Objective Physics for Neet Vol 2 (MBBS and BDS) (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789312146965.jpg\"]', 'uploads/products/thumbnail/9789312146965.jpg', 'uploads/products/featured/9789312146965.jpg', 'uploads/products/flash_deal/9789312146965.jpg', '', '', 'test', '<p>\r\n	Various popular national and regional medical entrances like NEET, CBSE AIPMT, AIIMS, WBJEE, MH-CET, etc demand thorough knowledge of the concepts covered under the syllabi of the examinations. And to help aspirants master the concepts of Physics, Arihant has come up with the revised edition of Objective Physics Volume 1 designed especially for the aspirants preparing for National Eligibility cum Entrance Test (NEET) 2017.<br />\r\n	The present book for NEET 2017 based on the Class XII Physics syllabus has been designed to help aspirants prepare for the competitions along with their school studies. The book contains the best available collection of <strong>objective questions and has been designed to work as a self-study guide for Physics for NEET and all other national &amp; regional medical entrance examinations</strong>.The book contains ample number of objective questions of all types like single option correct, matching, assertion-reason and statement based questions.The book has been divided into 15 chapters namely Electrostatics, Current Electricity, Magnetic Effects of Current, Magnetism, Electromagnetic Induction, Alternating Current, Geometric Optics, Interference &amp; Diffraction of Light, Electromagnetic Waves, Modern Physics, Solids &amp; Semiconductor Devices, Basics of Communications, Electron Tubes, Universe and Theory of Relativity, each sub-divided into number of topics as per the Class XII Physics syllabi. <strong>The questions covered in the book have been designed to ensure comprehensive knowledge of the concepts</strong> and also their applications for solving different types of problems. The questions in the book incorporate the syllabi of almost all medical entrances in India.&nbsp; Each chapter contains objective questions presented in two levels, Level I covering the basic questions and Level II covering higher difficulty order questions. Each chapter also contains a collection of questions asked in different national and regional medical entrances. The exercises have been solved in detail to help students understand the concepts better and effectively.<strong> The book also contains questions asked in last four years&rsquo; (2012-2015) medical entrance examinations which will help aspirants get an insight into the types of questions asked in recent years. Also NEET 2016 Phase I &amp; Phase II Solved Papers have<a name=&#34;_GoBack&#34;></a>&nbsp;been provided in the book</strong> along with three Mock Tests for NEET to help aspirants get insight into the recent examination pattern and the types of questions asked therein.<br />\r\n	With ample collection of questions which may be asked in the upcoming NEET and other medical entrances as well as previous years&rsquo; solved questions, this book for sure will act as the perfect resource book to master the concepts of Class XII Physics curriculum and also the skills required to tackle the questions asked in different formats in NEET and other medical entrances.</p>', 670.00, 670.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'VOL 2 ARIHANT NEET OBJECTIVE PHYSICS MEDICAL OBJECTIVE PHYSICS VOL 2 ARIHANT  NEET OBJECTIVE PHYSICS DC PANDEY OBJECTIVE PHYSICS ARIHANT OBJECTIVE PHYSICS VOLUME 2 D C PANDEY NEET OBJECTIVE PHYSICS VOLUME 2 NEET OBJECTIVE PHYSICS VOL 2 NEET OBJECTIVE PHYSICS FOR NEET ARIHANT NEET PHYSICS OBJECTIVE ARIHANT PHYSICS', '', '', 'objective-physics-for-neet-vol-2-mbbs-and-bds-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 09:48:35'),
(151, 'Understanding Physics Mechanics Volume 1 for JEE Main and Advanced (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/97893121471604.jpg\"]', 'uploads/products/thumbnail/97893121471604.jpg', 'uploads/products/featured/97893121471604.jpg', 'uploads/products/flash_deal/97893121471604.jpg', '', '', 'test', '<p>\r\n	Textbooks have always held their relevance high be it for a first grader or the one preparing for IIT JEE. A powerhouse of knowledge, a foundation stone for the future success, the textbooks play a crucial role in achieving success in various engineering entrances like JEE Main &amp; Advanced. Understanding Physics for JEE Main &amp; Advanced authored by Mr. D.C. Pandey has become a synonym with success in JEE Main &amp; Advanced.&nbsp;<br />\r\n	Mechanics Part 1 of Understanding Physics Series covers the foundations of Mechanics in an effective and easily comprehensible manner. <strong>The present book has been divided into 10 chapters namely Basic Mathematics, Measurements &amp; Errors, Experiments, Units &amp; Dimensions, Vectors, Kinematics, Projectile Motion, Laws of Motion, Work, Energy &amp; Power and Circular Motion</strong>, each focusing on concept building &amp; application of the concepts in solving varied physical problems. This is the only book having detailed Text Matter along with all types of <strong>questions like Single Correct Option, Multiple Correct Option, Assertion-Reason, Comprehension Based Questions and Single Integer Answers</strong>. The exercises have been divided into two sections i.e. JEE Main and JEE Advanced.&nbsp;<a name=&#34;_GoBack&#34;></a><br />\r\n	This fully revised 2016 edition of comprehensive guide covers various topics, their theory and also example exercises to facilitate proper understanding of the topics discussed. The theoretical content in the book has been well explained with diagrams, graphs, illustrations and tables to facilitate easy understanding. Also some examples have been given before the text which will help in enhancing the students&rsquo; understanding of the topic. Also introductory exercises have been included for each topic. After the comprehensive study of the concepts covered in the chapters, solved examples based on the same have also been given.The exercises at the end of each chapter have been divided into two separate sections i.e. JEE Main and JEE Advanced covering the specific pattern of questions asked in those examinations. At the end of the book Hints &amp; Solutions for all the exercises have been given for comprehensive understanding of the concepts on which the problems are based.</p>', 395.00, 395.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'ADM0835 arihant mechanics part 1 mechanics arihant mechanics dc pandey mechancs d c pandey mechanics  d.c. pandey mechanics d.c pandey DC PANDEY MECHANICS VOLUME 1 D C PANDEY MECHANICS VOLUME 1 ARIHANT MECHANICS VOLUME 1  MECHANICS ARIHANT VOL 1 DC PANDEY MECHANICS 1 DC PANDEY', '', '', 'understanding-physics-mechanics-volume-1-for-jee-main-and-advanced-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 09:48:38'),
(152, 'Understanding Physics Mechanics Volume 2 for JEE Main and Advanced (NEW)', 'admin', 12, 2, 4, 10, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/9789312147177.jpg\"]', 'uploads/products/thumbnail/9789312147177.jpg', 'uploads/products/featured/9789312147177.jpg', 'uploads/products/flash_deal/9789312147177.jpg', 'youtube', NULL, 'test', '<p>\r\n	Textbooks have always held their relevance high be it for a first grader or the one preparing for IIT JEE. A powerhouse of knowledge, a foundation stone for the future success, the textbooks play a crucial role in achieving success in various engineering entrances like JEE Main &amp; Advanced. Understanding Physics for JEE Main &amp; Advanced authored by Mr. D.C. Pandey has become a synonym with success in JEE Main &amp; Advanced.&nbsp;</p>\r\n\r\n<p>\r\n	&nbsp;</p>\r\n\r\n<div id=\"&quot;ctl00_ContentPlaceHolder1_divAboutBook&quot;\">\r\n	<p>\r\n		Mechanics Part 2 of Understanding Physics Series covers the foundations of Mechanics in an effective and easily comprehensible manner. Starting with a brief review of the basic review of the basic of Rotational Motion and Gravitation, the book takes up the in-depth discussion of the SHM, Elasticity and Fluid Mechanics.The book has been divided into six chapters Center of Mass, Linear Momentum &amp; Collision,Rotational Mechanics, Gravitation, Simple Harmonic Motion, Elasticity and Fluid Mechanics, each focusing on concept building &amp; application of the concepts in solving varied physical problems. This is the only book having detailed Text Matter along with all types of questions like Single Correct Option, Multiple Correct Option, Assertion-Reason, Comprehension Based Questions and Single Integer Answers. The exercises have been divided into two sections i.e. JEE Main and JEE Advanced.</p>\r\n	This fully revised 2016 edition of comprehensive guide covers various topics, their theory and also example exercises to facilitate proper understanding of the topics discussed. The theoretical content in the book has been explained with diagrams, graphs, illustrations and tables to facilitate easy understanding of the concepts. After the comprehensive study of the concepts covered in the chapters, solved examples based on the same have also been given. The exercises at the end of each chapter have been divided into two separate sections i.e. JEE Main and JEE Advanced covering the specific pattern of questions asked in those examinations. At the end of the book Hints &amp; Solutions for all the exercises have been given for comprehensive understanding of the concepts on which the problems are based.</div>', 395.00, 395.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, 'arihant mechanics part 2 mechanics arihant mechanics dc pandey mechancs d c pandey mechanics  d.c. pandey mechanics d.c pandey DC PANDEY MECHANICS VOLUME 2 D C PANDEY MECHANICS VOLUME 2 ARIHANT MECHANICS VOLUME 2  MECHANICS ARIHANT VOL 2 DC PANDEY MECHANICS VOL 2 DC PANDEY', NULL, '', 'Understanding-Physics-Mechanics-Volume-2-for-JEE-Main-and-Advanced-NEW-d-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-23 04:35:40'),
(153, 'Understanding Physics Waves and Thermodynamics for JEE Main and Advanced (NEW)', 'admin', 12, 2, 4, 10, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/9789312147191.jpg\"]', 'uploads/products/thumbnail/9789312147191.jpg', 'uploads/products/featured/9789312147191.jpg', 'uploads/products/flash_deal/9789312147191.jpg', 'youtube', NULL, 'test', '<p class=\"&quot;MsoNormal&quot;\">\r\n	A powerhouse of knowledge, a foundation stone for the future success, the textbooks have always held their relevance high be it for a first grader or the one preparing for IIT JEE. Same is the significance of textbooks which play a crucial role in achieving success in various engineering entrances like IIT JEE Mains &amp; Advanced. The textbooks designed for engineering entrances aim at presenting the in-depth knowledge which helps the students grasp the concepts better leading to a strong foundation of exhaustive knowledge and ultimately the success seems near.<br>\r\n	Understanding Physics for JEE Main &amp; Advanced authored by Mr. D.C. Pandey has become a synonym with success in JEE Main &amp; Advanced.&nbsp;<strong>Waves &amp; Thermodynamics of Understanding Physics Series</strong> covers waves and the wave motion and the concepts of the thermodynamics in an effective and easily comprehensible way. The book has been divided into six chapters <strong>Wave Motion, Superposition of Waves, Sound Waves, Thermometry, Thermal Expansion &amp; Kinetic Theory of Gases, Laws of Thermodynamics and Calorimetry &amp; Heat Transfer</strong>,each focusing on concept building &amp; application of the concepts in solving varied physical problems based on the concepts. The book contains detailed Text Matter along with all types of questions like Single Correct Option, Multiple Correct Option, Assertion-Reason, Comprehension Based Questions and Single Integer Answers. The practice exercises in the book have been divided into two sections i.e. JEE Main and JEE Advanced. <strong>The book also includes questions on Experimental Physics and ‘Hints &amp; Solutions’ section at the end.</strong><br>\r\n	Each chapter in the book contains theoretical content as well as practice exercises to facilitate proper understanding of the topics. The theoretical content provided in the book has been well explained with diagrams, graphs, illustrations and tables to facilitate easy understanding. Also introductory exercises have been included for each topic to help students test their abilities. After the comprehensive study of the concepts, solved problems based on the same have also been given in the book. At the end of the book Hints &amp; Solutions for all the exercises have been given for comprehensive understanding of the concepts on which the problems are based.<a name=\"&quot;_GoBack&quot;\"></a></p>', 315.00, 315.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, 'arihant waves and thermodynamics dc pandey,waves and thermodynamics d.c. pandey,waves and thermodynamics ARIHANT D C PANDEY WAVES AND THERMODYNOMICS', NULL, '', 'Understanding-Physics-Waves-and-Thermodynamics-for-JEE-Main-and-Advanced-NEW-d-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-23 04:36:02'),
(154, 'New Pattern Jee Problems Physics for JEE Main and Advanced (NEW)', 'admin', 12, 2, 4, 10, 2, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/9789312146170.jpg\"]', 'uploads/products/thumbnail/9789312146170.jpg', 'uploads/products/featured/9789312146170.jpg', 'uploads/products/flash_deal/9789312146170.jpg', 'youtube', NULL, 'test', '<p>\r\n	Various national and state level engineering entrances like JEE Main &amp; Advanced require thorough knowledge and practice of the concepts covered under the syllabi. And for gaining thorough knowledge the aspirants need to practice using <strong>various types of questions which are asked in previous years’ JEE Main and Advanced and also model questions which may be asked in the future engineering entrances</strong>. The revised edition of this specialized book by Arihant is a true master practice book of Physics designed as per the examination pattern of JEE Main and JEE Advanced.<br>\r\n	The present practice book for JEE Main &amp; Advanced Physics contain over 8000 questions which have been divided into 23 chapters namely Experimental Skills &amp; General Physics, Kinematics 1, Kinematics 2, Laws of Motion, Work, Power &amp; Energy, Circular Motion, Center of Mass, Impulse &amp; Momentum, Rotation, Gravitation, Properties of Matter, Simple Harmonic Motion, Waves, Heat &amp; Thermodynamics, Ray Optics, Wave Optics, Electrostatics, Current Electricity, Magnetic Effect of Current &amp; Magnetism, Electromagnetic Induction &amp; Alternating Current, Electromagnetic Waves, Modern Physics, Semiconductors &amp; Electronic Devices and Communication System.This is a <strong>Master Practice Book consisting of innovative objective problems like MCQs with Single Correct Option, Multiple Correct Options, Assertion-Reason, Linked Comprehension Based, Matrix Matching and Single Integer Answer Type.</strong> The different types of objective questions provided in the book will help in sharpening the comprehension and analytical abilities in the students. All the questions are supplemented by hints and step-by-step explanatory solutions at the end of each chapter. The book also contains Solved Paper 2014 &amp; 2015 JEE Main &amp; Advanced which will help aspirants get an insight into the recent examination pattern and the types of questions asked therein.&nbsp;<br>\r\n	<strong>As the book contains ample number of&nbsp;<a name=\"&quot;_GoBack&quot;\"></a>physics&nbsp;objective questions on the basis of the new examination pattern, it will help students learn in depth about the various concepts and subjects covered under the syllabi of physics for JEE Main and Advanced Exam 2017</strong></p>', 675.00, 675.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, NULL, NULL, '', 'New-Pattern-Jee-Problems-Physics-for-JEE-Main-and-Advanced-NEW-d-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-23 04:38:15'),
(155, 'Objective Physics Vol 1 for Engineering Entrance (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789312145975.jpg\"]', 'uploads/products/thumbnail/9789312145975.jpg', 'uploads/products/featured/9789312145975.jpg', 'uploads/products/flash_deal/9789312145975.jpg', '', '', 'test', '<p>\r\n	Arihant has come up with the revised edition of Objective Physics Volume 1 to help aspirants appearing for popular engineering entrances JEE Main &amp; Advanced etc master the concepts of physics as thorough and comprehensive knowledge of the concepts is required for clearing such engineering entrances.<br />\r\n	The present Objective Physics Volume 1 has been designed in sync with Class 11th Physics NCERT textbook to help aspirants prepare for the competitions along with their school studies. The <strong>book contains over 5000 objective questions of all types like single option correct, matching, assertion-reason and statement</strong> based questions.The book has been divided into 17 chapters namely Units, Dimensions &amp; Error Analysis, Vectors, Motion in One Dimension, Projectile Motion, Laws of Motion, Work, Energy &amp; Power, Circular Motion, COM, Conservation of Linear Momentum Impulse &amp; Collision, Rotation, Gravitation, Simple Harmonic Motion, Elasticity, Fluid Mechanics, Thermometry, Thermal Expansion &amp; Kinetic Theory of Gases, The First Law of Thermodynamics, Calorimetry and Wave Motion, each sub-divided into number of topics as per the Class 11th Physics NCERT textbook and syllabi. The book contains ample number of solved and unsolved questions which have been designed in such a way that it makes different concepts clear and also their applications for solving different types of problems. The questions provided in the book incorporate the syllabi of almost all engineering entrances in India. Each chapter contains objective questions presented in two levels, Level I covering the basic questions and Level II covering higher difficulty order questions. The questions have been divided into two levels to help aspirants practice the concepts step by step. Each chapter also contains a collection of questions asked in different national and regional medical entrances. The questions of practice exercises have been solved in detail to help students understand the concepts better and effectively. The book also contains JEE Main &amp; Advanced 2015 Solved Papers to help candidates get an insight into the examination pattern and the types of question asked therein.<br />\r\n	As the book contains ample number of questions which can be asked in the upcoming engineering entrances as well as questions asked in previous years&rsquo; engineering entrance examinations, it for sure will help students master the concepts of Physics and also the skills required to tackle the questions asked in different formats in various popular engineering&nbsp;<a name=&#34;_GoBack&#34;></a>entrances.</p>', 645.00, 645.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 1, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'objective physics for engineering vol I by arihant,objective physics for engineering vol I by DC Pandey,objective physics for engineering vol I by d c pandey,arihant objective physics for engineering,d c pandey objective physics for engineering vol I,xi engineering physics by d c pandey,xi engineering physics by arihant,arihant engineering physics xi,d c pandey engineering physics xi', '', '', 'objective-physics-vol-1-for-engineering-entrance-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 15:00:25'),
(156, '40 Days JEE Main Mathematics (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789313161202.jpg\"]', 'uploads/products/thumbnail/9789313161202.jpg', 'uploads/products/featured/9789313161202.jpg', 'uploads/products/flash_deal/9789313161202.jpg', '', '', 'test', '<p>\r\n	<strong>About Book</strong></p>\r\n\r\n<p>\r\n	Not all but only a handful of the aspirants succeed in clearing JEE Main which serves a gateway for admission in undergraduate engineering programmes at NITs, IIITs and other centrally funded technical institution and also serves as an eligibility test for JEE Advanced. This revision cum crash course for JEE Main 2017 has been designed for the students aspiring to get through various regional and national engineering entrances with flying colors.<br />\r\n	The present revised edition of JEE Main Mathematics in 40 Days is an impeccable tool from Arihant designed to achieve perfection in the concepts asked in various regional and national engineering entrances. This book has been divided into 40 sections as per strategic division of the syllabus in 40 days along with six Unit Tests and 3 Full Length Mock Tests. The preparation starts with Sets, Relations &amp; Functions, Complex Numbers, Sequences &amp; Series, Quadratic Equation &amp; Inequalities, Determinants, Matrices, Binomial Theorem &amp; Mathematical Induction, Permutations &amp; Combinations, followed by Unit Test 1 on Day 9 then Real Function, Limits, Continuity &amp; Differentiability, Differentiation, Application of Derivatives, Maxima &amp; Minima, Indefinite Integrals, Definite Integrals, Area of Curves, Differential Equations, followed by Unit Test 2 on Day 19, then Trigonometric Functions &amp; Equations, Heights &amp; Distances, Inverse Trigonometric Functions, followed by Unit Test 3 on Day 23, then Cartesian System of Rectangular Coordinates, Straight Lines, The Circle, Parabola, Ellipse, Hyperbola, followed by Unit Test 4 on Day 30, then Vector Algebra, Three Dimensional Geometry, followed by Unit Test 5 on Day 33, then Statistics, Probability, Mathematical Reasoning, followed by Unit Test 6 on Day 37, followed by Mock Test 1, Mock Test 2 and Mock Test 3 on 38th, 39th and 40th day respectively. All the concepts have been discussed clearly and comprehensively to keep the students focused. Topics to Focus, a set of topics for each day determined first. All types of objective questions like single option correct, assertion &amp; reason, passage-based etc have been included in the warm up exercise for a day. Frequent unit tests have also been included in between the comprehensive study so that the students can assess their level of preparation for the examination. At the end of the book, 2015 &amp; 2016 JEE Main Solved Papers have also been given in the book to give the candidates an insight into the current examination pattern of JEE Main.&nbsp;<br />\r\n	As the book has been designed systematically to give the candidates a fast way to prepare for Mathematics without any other support or guidance, it for sure will act as a perfect revision cum crash resource book for various regional and national engineering entrances preparation like JEE Main&nbsp;<a name=&#34;_GoBack&#34;></a>etc.</p>', 340.00, 340.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'arihant 40 Days JEE Main Mathematics,40 Days JEE Main Mathematics by arihant,40 Days JEE Main Mathematics by rajeev manocha,arihant crash course JEE Main Mathematics,math crash course jee main arihant', '', '', '40-days-jee-main-mathematics-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:25', '2020-05-17 09:48:53'),
(157, '40 Years Chapterwise Topicwise Solved Papers of Mathematics for JEE Main and Advanced (NEW)', 'admin', 12, 2, 5, 13, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/1901M4823C5703.jpg\"]', 'uploads/products/thumbnail/9789313163312.jpg', 'uploads/products/featured/9789313163312.jpg', 'uploads/products/flash_deal/9789313163312.jpg', 'youtube', NULL, 'test', '<p>\r\n	<strong>About Book</strong></p>\r\n\r\n<p>\r\n	The aspirants preparing for JEE Main and Advanced need to be very dedicated and focused with their efforts and preparation in order to do well in the examinations as getting into an IIT is not an easy task. Every year a large number of students dream of getting into IITs, the premier engineering institutes of our country, but only the ones with thorough preparation and determination succeed in getting admission in undergraduate engineering programs at IITs. It is all about practice and with this best-selling resource from Arihant students preparing for JEE Main &amp; Advanced can get themselves perfected and have an upper edge over other students.<br>\r\n	The present book for JEE Main and Advanced Mathematics has been divided into 26 Chapters namely Complex Numbers, Theory of Equations, Sequences &amp; Series, Permutations &amp; Combinations, Binomial Theorem, Probability, Matrices &amp; Determinants, Functions, Limits, Continuity &amp; Differentiability, Application of Derivatives, Indefinite Integration, Definite Integration, Area, Differential Equations, Straight Line &amp; Pair of Straight Lines, Circle, Parabola, Ellipse, Hyperbola, Trigonometrical Ratios &amp; Identities, Trigonometrical Equations, Inverse Circular Functions, Properties of Triangles, Vectors, 3D Geometry and Miscellaneous, according to the syllabi of JEE Main &amp; Advanced. This specialized book contains last 39 Years’ (1979-2017) Chapterwise Solved Questions of IIT JEE Mathematics along with previous years’ solved papers of IIT JEE and JEE Main &amp; Advanced. The entire syllabus of Class 11th and 12th has been dealt with comprehensively in this book. The questions asked in previous years’ examinations have been solved with their authentic and accurate solutions and have been provided chapterwise and topicwise in this book. Also wherever required necessary study material required for comprehensive understanding has been included in each chapter. The book also contains Solved Paper 2015 &amp; 2016 Solved Papers to help aspirants get an insight into the current pattern of the examination and the types of questions asked therein.<br>\r\n	As the book contains ample number of previous solved questions and relevant theoretical material, it for sure will help the aspirants score higher in the upcoming JEE Main and Advanced Entrance Examination 2017<a name=\"&quot;_GoBack&quot;\"></a>.</p>', 425.00, 425.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 1, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, '39 YEARS MATHEMATICS ARIHANT 39 YEAR MATHS JEE MAIN PREVIOUS PAPERS MATHEMATICS 39 YEARS ARIHANT PUBLICATION MATHEMATICS 39 YEARS MATHEMATICS AMIT M AGARWAL ARIHANT THIRTY NINE YEARS MATHEMATICS AMIT AGARWAL IIT JEE 39 YEARS JEE MAIN 39 YEARS', NULL, '', '40-Years-Chapterwise-Topicwise-Solved-Papers-of-Mathematics-for-JEE-Main-and-Advanced-NEW-d-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-23 04:33:10');
INSERT INTO `products` (`id`, `name`, `added_by`, `user_id`, `category_id`, `subcategory_id`, `subsubcategory_id`, `brand_id`, `author`, `version`, `isbn`, `oldisbn`, `photos`, `thumbnail_img`, `featured_img`, `flash_deal_img`, `video_provider`, `video_link`, `tags`, `description`, `unit_price`, `purchase_price`, `mrp`, `minstock`, `bundleprice`, `erpprice`, `minorderqty`, `maxorderqty`, `onrent`, `securityamount`, `rentamount`, `variant_product`, `attributes`, `choice_options`, `colors`, `variations`, `todays_deal`, `published`, `featured`, `current_stock`, `unit`, `discount`, `discount_type`, `tax`, `cgst`, `igst`, `sgst`, `tax_type`, `shipping_type`, `shipping_cost`, `num_of_sale`, `meta_title`, `meta_description`, `meta_img`, `pdf`, `slug`, `refundable`, `rating`, `barcode`, `digital`, `file_name`, `file_path`, `created_at`, `updated_at`) VALUES
(158, '40 Years Chapterwise Topicwise Solved Papers of Chemistry for IIT JEE Main and Advanced (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789313163305.jpg\"]', 'uploads/products/thumbnail/9789313163305.jpg', 'uploads/products/featured/9789313163305.jpg', 'uploads/products/flash_deal/9789313163305.jpg', '', '', 'test', '<p>\r\n	The aspirants preparing for JEE Main and Advanced need to be very dedicated and focused with their efforts and preparation in order to do well in the examinations as getting into an IIT is not an easy task. Every year a large number of students dream of getting into IITs, the premier engineering institutes of our country, but only the ones with thorough preparation and determination succeed in getting admission in undergraduate engineering programs at IITs. It is all about practice and with this best-selling resource from Arihant students preparing for JEE Main &amp; Advanced can get themselves perfected and have an upper edge over other students.<br />\r\n	The present book for JEE Main and Advanced&nbsp;Chemistry has been divided into 32 Chapters namely Some Basic Concepts of Chemistry, Atomic Structure, Periodic Classification &amp; Periodic Properties, Chemical Bonding &amp; Molecular Structure, States of Matter, Equilibrium, Thermodynamics, Solid State, Solutions &amp; Colligative Properties, Electrochemistry, Chemical Kinetics, Nuclear Chemistry, Surface Chemistry, s-block Elements, p-block Elements Ist, p-Block elements IInd, Transition &amp; Inner-Transition Elements, Coordination Compounds, Extraction of Metals, Qualitative Analysis, Organic Chemistry Basics, Hydrocarbons, Alkyl Halides, Alcohols &amp; Ethers, Aldehydes &amp; Ketones, Carboxylic Acids &amp; their Derivatives, Aliphatic Compounds Containing Nitrogen, Benzene &amp; Alkyl Benzene, Aromatic Compounds Containing Nitrogen, Aryl Halides &amp; Phenols, Aromatic Aldehydes, Ketones &amp; Acids and Biomolecules &amp; Chemistry in Everyday Life, according to the syllabi of JEE Main &amp; Advanced. This specialized book contains last 39 Years&rsquo; (1979-2017) Chapterwise Solved Questions of IIT JEE Chemistry along with previous years&rsquo; solved papers of IIT JEE and JEE Main &amp; Advanced. The entire syllabus of Class 11th and 12thhas been dealt with comprehensively in this book. The questions asked in previous years&rsquo; examinations have been solved with their authentic and accurate solutions and have been provided chapterwise and topicwise in this book. Also wherever required necessary study material required for comprehensive understanding has been included in each chapter. The book also contains Solved Paper 2015 &amp; 2016 Solved Papers to help aspirants get an insight into the current pattern of the examination and the types of questions asked therein.</p>\r\n\r\n<p>\r\n	As the book contains ample number of previous solved questions and relevant theoretical material, it for sure will help the aspirants score higher in the upcoming JEE Main and Advanced Entrance Examination 2018.</p>', 380.00, 380.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 1, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', '39 YEARS CHEMISTRY ARIHANT 39 YEAR CHEMISTRY JEE MAIN PREVIOUS PAPERS CHEMISTRY 39 YEARS ARIHANT PUBLICATION 39 YEARS CHEMISTRY RANJIT SHAHI ARIHANT THIRTY NINE YEARS CHEMISTRY RANJIT SAHI', '', '', '40-years-chapterwise-topicwise-solved-papers-of-chemistry-for-iit-jee-main-and-advanced-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-17 09:48:02'),
(159, 'BITSAT Prep Guide (NEW)', 'admin', 12, 3, 7, NULL, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/97893131638002.jpg\"]', 'uploads/products/thumbnail/97893131638002.jpg', 'uploads/products/featured/97893131638002.jpg', 'uploads/products/flash_deal/97893131638002.jpg', 'youtube', NULL, 'test', '<p>\r\n	<strong>About Book</strong></p>\r\n\r\n<p>\r\n	Birla Institute of Technology and Science (BITS) is one of the reputed private engineering institutes in the country, which autonomously conducts combined Aptitude test for its admissions. BITS offer a wide range of post graduate, undergraduate and integrated courses. The book BITSAT Prep Guide is a bestselling Self Study Guide for BITSAT 2018 fully updated with the latest facts and information. It provides you a comprehensive coverage of the entire exam syllabus- Physics, Chemistry, Mathematics, English Proficiency and Logical Reasoning. The book extends its Coverage over Past years’ Question Papers and 5 Practice Sets to enable students to give their best performance in the exam.<br>\r\n	For practice in online format just like that of the exam, all the exercises &amp; Mock Test given in this book are also available for free on Web or On Mobile.</p>\r\n\r\n<p>\r\n	&nbsp;</p>\r\n\r\n<p>\r\n	<strong>Content</strong></p>\r\n\r\n<p>\r\n	Physics, Chemistry, Mathematics English Proficiency, Logical Reasoning 5 Practice Sets 3 Solved Papers 2015-17</p>', 815.00, 815.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 1, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, NULL, NULL, '', 'BITSAT-Prep-Guide-NEW-e-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-23 04:33:27'),
(160, 'DPP Mathematics Vol 5 Conic Section Vector and 3D Geometry for JEE Mains and Advanced (NEW', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789385929274.jpg\"]', 'uploads/products/thumbnail/9789385929274.jpg', 'uploads/products/featured/9789385929274.jpg', 'uploads/products/flash_deal/9789385929274.jpg', '', '', 'test', '<p>\r\n	There used to be a time when Daily Practice Problems (DPP) series was a hit and a bestseller amongst IIT JEE aspirants. It was popular because of the fact that it ensured daily practice and proper planning of the concepts covered &amp; discussed on day to day basis. And bringing back the same craze, Arihant for the very first time has come up with DPP Series covering the whole syllabi of Physics, Chemistry &amp; Mathematics for JEE Main &amp; Advanced.</p>\r\n\r\n<p>\r\n	The present Daily Practice Problems (DPP) book covers Conic Section, Vector and 3D Geometry. The primary focus of this series is on achieving success through practice and proper planning. The entire content in the book covering Integrals &amp; Its Applications has been divided into Daily modules namely Parabola, Ellipse, Hyberbola, Vectors and 3D Geometry. The daily modules will ensure that the students are just required to practice one sheet of each subject on daily basis. The book contains questions based on a topic of the chapter-syllabus, ensuring the complete Practice &amp; Assessment of the topic.<br />\r\n	Salient Features are:<br />\r\n	Micro Level Coverage of each chapter with all types of questions covering Conic Section, Vector and 3D Geometry<br />\r\n	Along with topical coverage, revisal DPPs for JEE Main &amp; JEE Advanced provided with each chapter for thorough revision of the concepts<br />\r\n	JEE Main &amp; Advanced archive (collection of previous years&rsquo; exams questions) with each chapter<br />\r\n	Complete solutions for each DPP to help students self analyse their level of preparation for the upcoming examinations</p>', 245.00, 245.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 1, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'arihant dpp vol 5 mathematics,dpp vol 5 mathematics by arihant,dpp on Conic Section, Vector and 3D Geometry,mathematics dpp vol 5 arihant,mathematics dpp vol 5 by amit m aggarwal,dpp volume 5 mathematics by amit m aggarwal,dpp volume 5 mathematics', '', '', 'dpp-mathematics-vol-5-conic-section-vector-and-3d-geometry-for-jee-mains-and-advanced-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-17 09:48:07'),
(161, 'Dictionary of Chemistry (NEW)', 'admin', 12, 2, 5, NULL, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/xXMCpBT9FaMPwwBymls3qBB2ivccS5kXMWfHPKHN.png\"]', 'uploads/products/thumbnail/sOJKKvjXQPySyqQl6yFhlFpPPRYSMfroyaX1J8aA.png', 'uploads/products/featured/ZchUJvi31lqqEaq9htv9vM1ORTISfUzOJ4kNgCot.png', 'uploads/products/flash_deal/rZQ22wNsq08EYxSVslQJbOj1yhZgd2L2Lbh00V5h.png', 'youtube', NULL, 'test', '<p>\r\n	This Book Covers Terms, Definitions, Concepts, Methods, Laws, Experiments of Chemistry. Each Letter Starts a series of Words and will keep you fascinated of your knowledge by the end.</p>', 160.00, 160.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"3\",\"values\":[\"old\",\"new\"]}]', '[\"#9966CC\"]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, 'arihant chemistry dictionary, chemistry dictionary by arihant,chemistry dictionary arihant,chemistry dictionary by purnima sharma,chemistry dictionary arihant', NULL, '', 'Dictionary-of-Chemistry-NEW-new-1', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-23 04:33:48'),
(162, 'Dictionary of Physics (NEW)', 'admin', 12, 3, 7, NULL, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/physics-dictionary-original-imadbz6z7zfmetdv.jpg\"]', 'uploads/products/thumbnail/physics-dictionary-original-imadbz6z7zfmetdv.jpg', 'uploads/products/featured/physics-dictionary-original-imadbz6z7zfmetdv.jpg', 'uploads/products/flash_deal/physics-dictionary-original-imadbz6z7zfmetdv.jpg', 'youtube', NULL, 'test', '<p>Dictionary of Physics&nbsp;is a book that covers terms, definitions, concepts, methods, laws and experiments. The book is essential for school and college going students. It is also helpful for reference purposes.</p>', 110.00, 110.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, 'arihant physics dictionary, physics dictionary by arihant,physics dictionary arihant,physics dictionary by Nipendra Bhatnagar ,physics dictionary arihant', NULL, '', 'Dictionary-of-Physics-NEW-new-1', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-23 04:34:05'),
(163, 'Mathematics Glossary 1300+ Terms of Mathematics (Hindi) (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789382111016.jpg\"]', 'uploads/products/thumbnail/9789382111016.jpg', 'uploads/products/featured/9789382111016.jpg', 'uploads/products/flash_deal/9789382111016.jpg', '', '', 'test', '<p>\r\n	Dictionary is a medium through which a student secures a desirable hold on the concerned subject. Dictionaries related to different language teach the correct spellings, pronunciation and meanings of the words through which learner&rsquo;s vocabulary power enhances.&nbsp;<br />\r\n	This book covers the Chapterwise (A-Z) glossary of the Terms, Definitions, Concepts, Methods, Laws, Formulae and Theorems. This Mathematics Glossary has been designed to deal precisely with those topics, which students of schools and colleges, and aspirants of various competitive examinations such as IIT JEE, AIEEE etc are always looking for. To the point and concise information has been provided in this glossary of Mathematics. Correct Pronunciation of the English words used in Mathematics has been given in Hindi for better understanding. Definitions in both English and Hindi have been provided &nbsp;&nbsp;to clear the concepts. More than 1300 terms have been covered in this glossary of Mathematics.</p>', 125.00, 125.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'arihant math glossary,arihant mathematics glossary,mathematics glossary by manjul tyagi,manjul tyagi mathematics glossary,math glossary by arihant,mathematics glossary by arihant', '', '', 'mathematics-glossary-1300-terms-of-mathematics-hindi-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-17 09:48:14'),
(164, 'Chemistry Glossary 1800+ Terms of Chemistry (Hindi) (NEW)', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads/products/photos/9789380068930.jpg\"]', 'uploads/products/thumbnail/9789380068930.jpg', 'uploads/products/featured/9789380068930.jpg', 'uploads/products/flash_deal/9789380068930.jpg', '', '', 'test', '', 130.00, 130.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 0, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', '', 0, 1, 1, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, '', 'arihant chemistry glossary,chemistry glossary by hansraj modi,hansraj modi chemistry glossary,chemistry glossary by arihant,chemistry glossary by arihant,chemistry glossary arihant', '', '', 'chemistry-glossary-1800-terms-of-chemistry-hindi-new', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 15:00:26', '2020-05-17 09:48:16'),
(166, 'Electrochemical Cell Working Model with Printed Report', 'admin', 12, 1, 1, 1, 1, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/gRUAvzYELozN0oD28wOZAddjgcg6TpCIJmHWhh5j.jpeg\",\"uploads\\/products\\/photos\\/2oXWo91MgZKKAO1ZNrPM8IyOiTwl1ug1ATIU5Zrt.jpeg\"]', 'uploads/products/thumbnail/PDHV6DwYC2AP8LABgLanogzy0gWV1rymgMAonVNM.jpeg', 'uploads/products/featured/0wM8cZ7qY4ST0ZZ5BxazsbXyluymas4tI0D4OV9X.jpeg', 'uploads/products/flash_deal/pyrZGDVvyFObnuupC7vmuDCrElfQVUrb22ixXOR3.jpeg', 'youtube', 'https://www.youtube.com/watch?v=9vDDM8G9tcs', 'test', 'test product<br>', 200.00, 150.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', NULL, 0, 1, 0, 0, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, NULL, NULL, NULL, 'Electrochemical-Cell-Working-Model-with-Printed-Report-0hkCR', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 11:59:03', '2020-05-17 11:59:03'),
(167, 'MBD History of India (1200 to 1750 AD) for BA 2nd Sem PU (English) (NEW)23', 'admin', 12, 2, 4, 10, 2, '', 'new', '', '', '[\"uploads\\/products\\/photos\\/1901M4823C5703.jpg\"]', 'uploads/products/thumbnail/1901M4823C5703.jpg', 'uploads/products/featured/1901M4823C5703.jpg', 'uploads/products/flash_deal/1901M4823C5703.jpg', 'youtube', 'https://www.youtube.com/watch?v=9vDDM8G9tcs', 'test', 'test product<br>', 225.00, 225.00, 0.00, '', '[]', 0.00, 1, 1, 'no', 0.00, 0.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"VOL-1\",\"VOL-2\"]}]', '[]', NULL, 0, 1, 0, 1, '10', 10.00, 'percent', 10.00, 0.00, 0.00, 0.00, 'percent', 'local_pickup', 0.00, 0, NULL, NULL, NULL, NULL, 'MBD-History-of-India-1200-to-1750-AD-for-BA-2nd-Sem-PU-English-NEW-Vmo9i', 0, 0.00, NULL, 0, NULL, NULL, '2020-05-17 11:59:41', '2020-05-23 04:39:12'),
(168, 'Electrochemical Cell Working Model with Printed Report11', 'admin', 12, 1, 1, 2, 1, 'braj', 'old', '111111', '111111', '[\"uploads\\/products\\/photos\\/xXMCpBT9FaMPwwBymls3qBB2ivccS5kXMWfHPKHN.png\"]', 'uploads/products/thumbnail/sOJKKvjXQPySyqQl6yFhlFpPPRYSMfroyaX1J8aA.png', 'uploads/products/featured/ZchUJvi31lqqEaq9htv9vM1ORTISfUzOJ4kNgCot.png', 'uploads/products/flash_deal/rZQ22wNsq08EYxSVslQJbOj1yhZgd2L2Lbh00V5h.png', 'youtube', NULL, 'electronics', NULL, 200.00, 180.00, 200.00, '-1', '{\"1\":{\"customercat\":\"customer\",\"varientqty\":\"10\",\"varientprice\":\"1700\"},\"2\":{\"customercat\":\"institute\",\"varientqty\":\"20\",\"varientprice\":\"3400\"},\"3\":{\"customercat\":\"wholeseller\",\"varientqty\":\"10\",\"varientprice\":\"1750\"},\"4\":{\"customercat\":\"customer\",\"varientqty\":\"20\",\"varientprice\":\"3200\"}}', 200.00, 1, 10, 'yes', 160.00, 60.00, 1, '[\"1\",\"2\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\"]},{\"attribute_id\":\"2\",\"values\":[\"COTTON\",\"SILK\"]}]', '[]', NULL, 0, 1, 0, 20, '100', 10.00, 'percent', 0.00, 2.50, 0.00, 0.00, 'amount', 'flat_rate', 0.00, 5, 'JEE Main / Advanced', 'test', 'uploads/products/meta/OV0NgYk4Wlv9IFFJ1o5KSeVYWeZJx3OaesiWdqVA.png', NULL, 'Electrochemical-Cell-Working-Model-with-Printed-Report11-jMCDv', 0, 0.00, NULL, 0, NULL, NULL, '2020-06-01 02:30:29', '2020-07-15 01:10:42');

-- --------------------------------------------------------

--
-- Table structure for table `product_stocks`
--

CREATE TABLE `product_stocks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `qty` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product_stocks`
--

INSERT INTO `product_stocks` (`id`, `product_id`, `variant`, `sku`, `price`, `qty`, `created_at`, `updated_at`) VALUES
(17, 168, 'L-COTTON', 'ECWMwPR-L-COTTON', 250.00, 10, '2020-07-15 01:10:41', '2020-07-15 01:10:41'),
(18, 168, 'L-SILK', 'ECWMwPR-L-SILK', 300.00, 10, '2020-07-15 01:10:41', '2020-07-15 01:10:41'),
(19, 168, 'XL-COTTON', 'ECWMwPR-XL-COTTON', 350.00, 10, '2020-07-15 01:10:41', '2020-07-15 01:10:41'),
(20, 168, 'XL-SILK', 'ECWMwPR-XL-SILK', 400.00, 10, '2020-07-15 01:10:41', '2020-07-15 01:10:41');

-- --------------------------------------------------------

--
-- Table structure for table `refund_requests`
--

CREATE TABLE `refund_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `seller_approval` int(1) NOT NULL DEFAULT '0',
  `admin_approval` int(1) NOT NULL DEFAULT '0',
  `refund_amount` double(8,2) NOT NULL DEFAULT '0.00',
  `reason` longtext COLLATE utf8_unicode_ci,
  `admin_seen` int(11) NOT NULL,
  `refund_status` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `comment` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `viewed` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Manager', '[\"1\",\"2\",\"4\"]', '2018-10-10 04:39:47', '2018-10-10 04:51:37'),
(2, 'Accountant', '[\"2\",\"3\"]', '2018-10-10 04:52:09', '2018-10-10 04:52:09');

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

CREATE TABLE `searches` (
  `id` int(11) NOT NULL,
  `query` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `searches`
--

INSERT INTO `searches` (`id`, `query`, `count`, `created_at`, `updated_at`) VALUES
(2, 'dcs', 1, '2020-03-08 00:29:09', '2020-03-08 00:29:09'),
(3, 'das', 3, '2020-03-08 00:29:15', '2020-03-08 00:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `verification_status` int(1) NOT NULL DEFAULT '0',
  `verification_info` longtext COLLATE utf8_unicode_ci,
  `cash_on_delivery_status` int(1) NOT NULL DEFAULT '0',
  `admin_to_pay` double(8,2) NOT NULL DEFAULT '0.00',
  `bank_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_acc_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_acc_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_routing_no` int(50) DEFAULT NULL,
  `bank_payment_status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `user_id`, `verification_status`, `verification_info`, `cash_on_delivery_status`, `admin_to_pay`, `bank_name`, `bank_acc_name`, `bank_acc_no`, `bank_routing_no`, `bank_payment_status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '[{\"type\":\"text\",\"label\":\"Name\",\"value\":\"Mr. Seller\"},{\"type\":\"select\",\"label\":\"Marital Status\",\"value\":\"Married\"},{\"type\":\"multi_select\",\"label\":\"Company\",\"value\":\"[\\\"Company\\\"]\"},{\"type\":\"select\",\"label\":\"Gender\",\"value\":\"Male\"},{\"type\":\"file\",\"label\":\"Image\",\"value\":\"uploads\\/verification_form\\/CRWqFifcbKqibNzllBhEyUSkV6m1viknGXMEhtiW.png\"}]', 1, 78.40, NULL, NULL, NULL, NULL, 0, '2018-10-07 04:42:57', '2020-01-26 04:21:11');

-- --------------------------------------------------------

--
-- Table structure for table `seller_withdraw_requests`
--

CREATE TABLE `seller_withdraw_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `message` longtext,
  `status` int(1) DEFAULT NULL,
  `viewed` int(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seo_settings`
--

CREATE TABLE `seo_settings` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `revisit` int(11) NOT NULL,
  `sitemap_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `seo_settings`
--

INSERT INTO `seo_settings` (`id`, `keyword`, `author`, `revisit`, `sitemap_link`, `description`, `created_at`, `updated_at`) VALUES
(1, 'bootstrap,responsive,template,developer', 'Active IT Zone', 11, 'https://www.activeitzone.com', 'Active E-commerce CMS Multi vendor system is such a platform to build a border less marketplace both for physical and digital goods.', '2019-08-08 08:56:11', '2019-08-08 02:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sliders` longtext COLLATE utf8_unicode_ci,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `pick_up_point_id` text COLLATE utf8_unicode_ci,
  `shipping_cost` double(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `user_id`, `name`, `logo`, `sliders`, `address`, `facebook`, `google`, `twitter`, `youtube`, `slug`, `meta_title`, `meta_description`, `pick_up_point_id`, `shipping_cost`, `created_at`, `updated_at`) VALUES
(1, 3, 'Demo Seller Shop', 'shop/logo/Gt1xw7vjTpMnwpADkGSilc35qrAfcw02kuZ36Jdn.png', '[\"uploads\\/shop\\/sliders\\/lToeKDeUyWcxy1HRs2yH37oBLyIwEwyPkqdyXBRO.jpeg\",\"uploads\\/shop\\/sliders\\/asDBJ3Bro1ijNaNnx3Hpnp6uq3n66ndyLczOJ0F6.jpeg\",\"uploads\\/shop\\/sliders\\/ltwUfHND4QP1K7bPFbuOC4i8v6zL9KHJKzex4zaX.jpeg\"]', 'House : Demo, Road : Demo, Section : Demo', 'www.facebook.com', 'www.google.com', 'www.twitter.com', 'www.youtube.com', 'Demo-Seller-Shop-1', 'Demo Seller Shop Title', 'Demo description', NULL, 0.00, '2018-11-27 10:23:13', '2019-08-06 06:43:16');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `photo`, `published`, `link`, `created_at`, `updated_at`) VALUES
(7, 'uploads/sliders/slider-image.jpg', 1, NULL, '2019-03-12 05:58:05', '2019-03-12 05:58:05'),
(8, 'uploads/sliders/slider-image.jpg', 1, NULL, '2019-03-12 05:58:12', '2019-03-12 05:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT 'india',
  `isactive` enum('yes','no') NOT NULL DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `country`, `isactive`, `created_at`, `updated_at`) VALUES
(1, 'ANDAMAN AND NICOBAR ISLANDS', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(2, 'ANDHRA PRADESH', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(3, 'ARUNACHAL PRADESH', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(4, 'ASSAM', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(5, 'BIHAR', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(6, 'CHATTISGARH', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(7, 'CHANDIGARH', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(8, 'DAMAN AND DIU', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(9, 'DELHI', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(10, 'DADRA AND NAGAR HAVELI', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(11, 'GOA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(12, 'GUJARAT', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(13, 'HIMACHAL PRADESH', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(14, 'HARYANA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(15, 'JAMMU AND KASHMIR', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(16, 'JHARKHAND', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(17, 'KERALA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(18, 'KARNATAKA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(19, 'LAKSHADWEEP', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(20, 'MEGHALAYA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(21, 'MAHARASHTRA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(22, 'MANIPUR', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(23, 'MADHYA PRADESH', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(24, 'MIZORAM', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(25, 'NAGALAND', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(26, 'ORISSA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(27, 'PUNJAB', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(28, 'PONDICHERRY', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(29, 'RAJASTHAN', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(30, 'SIKKIM', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(31, 'TAMIL NADU', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(32, 'TRIPURA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(33, 'UTTARAKHAND', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(34, 'UTTAR PRADESH', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(35, 'WEST BENGAL', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54'),
(36, 'TELANGANA', 'india', 'yes', '2020-06-21 10:59:54', '2020-06-21 10:59:54');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name`, `category_id`, `slug`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Demo sub category 1', 1, 'Demo-sub-category-1', 'Demo sub category 1', NULL, '2019-03-12 06:13:24', '2019-08-06 06:07:14'),
(2, 'Demo sub category 2', 1, 'Demo-sub-category-2', 'Demo sub category 2', NULL, '2019-03-12 06:13:44', '2019-08-06 06:07:14'),
(3, 'Demo sub category 3', 1, 'Demo-sub-category-3', 'Demo sub category 3', NULL, '2019-03-12 06:13:59', '2019-08-06 06:07:14'),
(4, 'Demo sub category 1', 2, 'Demo-sub-category-1', 'Demo sub category 1', NULL, '2019-03-12 06:18:25', '2019-08-06 06:07:14'),
(5, 'Demo sub category 2', 2, 'Demo-sub-category-2', 'Demo sub category 2', NULL, '2019-03-12 06:18:38', '2019-08-06 06:07:14'),
(6, 'Demo sub category 3', 2, 'Demo-sub-category-3', 'Demo sub category 3', NULL, '2019-03-12 06:18:51', '2019-08-06 06:07:14'),
(7, 'Demo sub category 1', 3, 'Demo-sub-category-1', 'Demo sub category 1', NULL, '2019-03-12 06:19:05', '2019-08-06 06:07:14'),
(8, 'Demo sub category 2', 3, 'Demo-sub-category-2', 'Demo sub category 2', NULL, '2019-03-12 06:19:13', '2019-08-06 06:07:14'),
(9, 'Demo sub category 3', 3, 'Demo-sub-category-3', 'Demo sub category 3', NULL, '2019-03-12 06:19:22', '2019-08-06 06:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_categories`
--

CREATE TABLE `sub_sub_categories` (
  `id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sub_sub_categories`
--

INSERT INTO `sub_sub_categories` (`id`, `sub_category_id`, `name`, `slug`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Demo sub sub category', 'Demo-sub-sub-category', 'Demo sub sub category', NULL, '2019-03-12 06:19:49', '2019-08-06 06:07:19'),
(2, 1, 'Demo sub sub category 2', 'Demo-sub-sub-category-2', 'Demo sub sub category 2', NULL, '2019-03-12 06:20:23', '2019-08-06 06:07:19'),
(3, 1, 'Demo sub sub category 3', 'Demo-sub-sub-category-3', 'Demo sub sub category 3', NULL, '2019-03-12 06:20:43', '2019-08-06 06:07:19'),
(4, 2, 'Demo sub sub category 1', 'Demo-sub-sub-category-1', 'Demo sub sub category 1', NULL, '2019-03-12 06:21:28', '2019-08-06 06:07:19'),
(5, 2, 'Demo sub sub category 2', 'Demo-sub-sub-category-2', 'Demo sub sub category 2', NULL, '2019-03-12 06:21:40', '2019-08-06 06:07:19'),
(6, 2, 'Demo sub sub category 3', 'Demo-sub-sub-category-3', 'Demo sub sub category 3', NULL, '2019-03-12 06:21:56', '2019-08-06 06:07:19'),
(7, 3, 'Demo sub sub category 1', 'Demo-sub-sub-category-1', 'Demo sub sub category 1', NULL, '2019-03-12 06:23:31', '2019-08-06 06:07:19'),
(8, 3, 'Demo sub sub category 3', 'Demo-sub-sub-category-3', 'Demo sub sub category 3', NULL, '2019-03-12 06:23:48', '2019-08-06 06:07:19'),
(9, 3, 'Demo sub sub category 3', 'Demo-sub-sub-category-3', 'Demo sub sub category 3', NULL, '2019-03-12 06:24:01', '2019-08-06 06:07:19'),
(10, 4, 'Demo sub sub category 1', 'Demo-sub-sub-category-1', 'Demo sub sub category 1', NULL, '2019-03-12 06:24:37', '2019-08-06 06:07:19'),
(11, 4, 'Demo sub sub category 2', 'Demo-sub-sub-category-2', 'Demo sub sub category 2', NULL, '2019-03-12 06:25:14', '2019-08-06 06:07:19'),
(12, 4, 'Demo sub sub category', 'Demo-sub-sub-category', 'Demo sub sub category', NULL, '2019-03-12 06:25:25', '2019-08-06 06:07:19'),
(13, 5, 'Demo sub sub category 1', 'Demo-sub-sub-category-1', 'Demo sub sub category 1', NULL, '2019-03-12 06:25:58', '2019-08-06 06:07:19'),
(14, 6, 'Demo sub sub category 1', 'Demo-sub-sub-category-1', 'Demo sub sub category 1', NULL, '2019-03-12 06:26:16', '2019-08-06 06:07:19'),
(15, 7, 'Demo sub sub category', 'Demo-sub-sub-category', 'Demo sub sub category', NULL, '2019-03-12 06:27:17', '2019-08-06 06:07:19'),
(16, 8, 'Demo sub sub category', 'Demo-sub-sub-category', 'Demo sub sub category', NULL, '2019-03-12 06:27:29', '2019-08-06 06:07:19'),
(17, 7, 'Demo sub sub category attribute', 'Demo-sub-sub-category', 'Demo sub sub category', NULL, '2019-03-12 06:27:41', '2020-03-05 04:03:54');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `code` int(6) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8_unicode_ci,
  `files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `status` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `viewed` int(1) NOT NULL DEFAULT '0',
  `client_viewed` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply` longtext COLLATE utf8_unicode_ci NOT NULL,
  `files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `referred_by` int(11) DEFAULT NULL,
  `provider_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'customer',
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar_original` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verification_code` int(50) DEFAULT NULL,
  `institute_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `state` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gstin` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `balance` double(8,2) NOT NULL DEFAULT '0.00',
  `referral_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_package_id` int(11) DEFAULT NULL,
  `remaining_uploads` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `referred_by`, `provider_id`, `user_type`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `avatar`, `avatar_original`, `address`, `country`, `city`, `postal_code`, `phone`, `verification_code`, `institute_id`, `category_id`, `state`, `gstin`, `balance`, `referral_code`, `customer_package_id`, `remaining_uploads`, `created_at`, `updated_at`) VALUES
(3, NULL, NULL, 'seller', 'Mr. Seller', 'seller@example.com', '2018-12-11 18:00:00', '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm', '1zoU4eQxnOC5yxRWLsTzMNBPpJuOvTk4g3GMUVYIrbGijiXHOfIlFq0wHrIn', 'https://lh3.googleusercontent.com/-7OnRtLyua5Q/AAAAAAAAAAI/AAAAAAAADRk/VqWKMl4f8CI/photo.jpg?sz=50', 'uploads/ucQhvfz4EQXNeTbN8Eif0Cpq5LnOwvg8t7qKNKVs.jpeg', 'Demo address', 'US', 'Demo city', '1234', NULL, NULL, '', '', '', '', 0.00, '3dLUoHsR1l', NULL, NULL, '2018-10-07 04:42:57', '2020-03-05 01:33:22'),
(8, NULL, NULL, 'customer', 'Mr. Customer', 'customer@example.com', '2018-12-11 18:00:00', '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm', '9ndcz5o7xgnuxctJIbvUQcP41QKmgnWCc7JDSnWdHOvipOP2AijpamCNafEe', 'https://lh3.googleusercontent.com/-7OnRtLyua5Q/AAAAAAAAAAI/AAAAAAAADRk/VqWKMl4f8CI/photo.jpg?sz=50', 'uploads/ucQhvfz4EQXNeTbN8Eif0Cpq5LnOwvg8t7qKNKVs.jpeg', 'Demo address', 'US', 'Demo city', '1234', NULL, NULL, '', '', '', '', 0.00, '8zJTyXTlTT', NULL, NULL, '2018-10-07 04:42:57', '2020-03-03 04:26:11'),
(12, NULL, NULL, 'admin', 'amitbook', 'brajkishorpandey@gmail.com', '2020-05-16 23:35:13', '$2y$10$6hFY6QsJIZ493wvsVvA.JuUP6Q3RzLCCxVcpI1l19Lg3GOLK2yjxq', 'KxFGrRs1fMcdQtlJwW0956o4UPlJYVJoHutJhV4U4HZQLdu28UF3xEDDJzE2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', 0.00, NULL, NULL, 0, '2020-05-16 23:48:13', '2020-05-16 23:48:13'),
(14, NULL, NULL, 'wholeseller', 'Mr. wholeseller', 'wholeseller@example.com', '2018-12-11 18:00:00', '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm', '9ndcz5o7xgnuxctJIbvUQcP41QKmgnWCc7JDSnWdHOvipOP2AijpamCNafEe', 'https://lh3.googleusercontent.com/-7OnRtLyua5Q/AAAAAAAAAAI/AAAAAAAADRk/VqWKMl4f8CI/photo.jpg?sz=50', 'uploads/ucQhvfz4EQXNeTbN8Eif0Cpq5LnOwvg8t7qKNKVs.jpeg', 'Demo address', 'US', 'Demo city', '1234', NULL, NULL, '', '', '', '', 0.00, '8zJTyXTlTT', NULL, NULL, '2018-10-07 04:42:57', '2020-03-03 04:26:11'),
(15, NULL, NULL, 'institute', 'institute', 'institute@example.com', '2018-12-11 18:00:00', '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm', '9ndcz5o7xgnuxctJIbvUQcP41QKmgnWCc7JDSnWdHOvipOP2AijpamCNafEe', 'https://lh3.googleusercontent.com/-7OnRtLyua5Q/AAAAAAAAAAI/AAAAAAAADRk/VqWKMl4f8CI/photo.jpg?sz=50', 'uploads/ucQhvfz4EQXNeTbN8Eif0Cpq5LnOwvg8t7qKNKVs.jpeg', 'Demo address', 'US', 'Demo city', '1234', NULL, NULL, '', '', '', '', 0.00, '8zJTyXTlTT', NULL, NULL, '2018-10-07 04:42:57', '2020-03-03 04:26:11'),
(16, NULL, NULL, 'customer', 'braj', 'braj@gmail.com', NULL, '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm', NULL, NULL, NULL, '55 van siclen ave', NULL, 'Chandigarh', '23121', '7696866526', NULL, '5', '8', '1', '1234567', 0.00, NULL, NULL, 0, '2020-06-21 05:06:58', '2020-06-21 11:20:17'),
(19, NULL, NULL, 'customer', 'bk', 'customer1@example.com', NULL, '$2y$10$7iSKWcSMjXCwC14h9WWMKe3G.mAhaZ2ZfIoAS96zz66GDNUXmbaAK', NULL, NULL, NULL, 'test address', NULL, 'Chandigarh', '12345', '9569843767', NULL, '1', '1', 'Chandigarh', '', 0.00, NULL, NULL, 0, '2020-06-25 02:27:49', '2020-06-25 02:27:49'),
(26, NULL, NULL, 'customer', 'braj', NULL, '2020-07-09 05:37:31', '$2y$10$2D3d2b5d1s3YOPUIqgBB9.eyCo7H9Qio1Sa.zxrvCB5Ay6rHFmPuC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+919877188099', 456839, '', '', '', '', 0.00, NULL, NULL, 0, '2020-07-09 04:45:10', '2020-07-09 05:35:31'),
(27, NULL, NULL, 'customer', 'amit', NULL, '2020-07-15 00:37:09', '$2y$10$5x/p.hpUmKGWNZBgcu0zKOchXaN47vYq2BOucbx1MKPRJmDrNRl2y', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+919216499664', 624991, '', '', '', '', 0.00, NULL, NULL, 0, '2020-07-15 01:16:50', '2020-07-15 01:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_details` longtext COLLATE utf8_unicode_ci,
  `approval` int(1) NOT NULL DEFAULT '0',
  `offline_payment` int(1) NOT NULL DEFAULT '0',
  `reciept` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `affiliate_configs`
--
ALTER TABLE `affiliate_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `affiliate_options`
--
ALTER TABLE `affiliate_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `affiliate_payments`
--
ALTER TABLE `affiliate_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `affiliate_users`
--
ALTER TABLE `affiliate_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_settings`
--
ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_categories`
--
ALTER TABLE `customer_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_packages`
--
ALTER TABLE `customer_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_products`
--
ALTER TABLE `customer_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flash_deals`
--
ALTER TABLE `flash_deals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flash_deal_products`
--
ALTER TABLE `flash_deal_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_categories`
--
ALTER TABLE `home_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutes`
--
ALTER TABLE `institutes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_payment_methods`
--
ALTER TABLE `manual_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_configurations`
--
ALTER TABLE `otp_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pickup_points`
--
ALTER TABLE `pickup_points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `searches`
--
ALTER TABLE `searches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_withdraw_requests`
--
ALTER TABLE `seller_withdraw_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seo_settings`
--
ALTER TABLE `seo_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category_id` (`category_id`);

--
-- Indexes for table `sub_sub_categories`
--
ALTER TABLE `sub_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sub_category_id` (`sub_category_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `affiliate_configs`
--
ALTER TABLE `affiliate_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `affiliate_options`
--
ALTER TABLE `affiliate_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `affiliate_payments`
--
ALTER TABLE `affiliate_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `affiliate_users`
--
ALTER TABLE `affiliate_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `business_settings`
--
ALTER TABLE `business_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_categories`
--
ALTER TABLE `customer_categories`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `customer_packages`
--
ALTER TABLE `customer_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_products`
--
ALTER TABLE `customer_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flash_deals`
--
ALTER TABLE `flash_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flash_deal_products`
--
ALTER TABLE `flash_deal_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_categories`
--
ALTER TABLE `home_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `institutes`
--
ALTER TABLE `institutes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manual_payment_methods`
--
ALTER TABLE `manual_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `otp_configurations`
--
ALTER TABLE `otp_configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pickup_points`
--
ALTER TABLE `pickup_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `product_stocks`
--
ALTER TABLE `product_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `searches`
--
ALTER TABLE `searches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seller_withdraw_requests`
--
ALTER TABLE `seller_withdraw_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seo_settings`
--
ALTER TABLE `seo_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sub_sub_categories`
--
ALTER TABLE `sub_sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

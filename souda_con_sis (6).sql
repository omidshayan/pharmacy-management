-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 24, 2026 at 11:20 AM
-- Server version: 9.1.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `souda_con_sis`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_balances`
--

DROP TABLE IF EXISTS `account_balances`;
CREATE TABLE IF NOT EXISTS `account_balances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `user_id` int NOT NULL,
  `balance` decimal(15,2) DEFAULT NULL,
  `total_out` decimal(15,2) DEFAULT NULL,
  `total_in` decimal(15,2) DEFAULT NULL,
  `year` varchar(4) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`branch_id`,`user_id`),
  KEY `idx_user_branch` (`user_id`,`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `account_balances`
--

INSERT INTO `account_balances` (`id`, `branch_id`, `user_id`, `balance`, `total_out`, `total_in`, `year`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, '', 1, '2026-02-08 12:36:45', '2026-03-24 15:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `admin_sis`
--

DROP TABLE IF EXISTS `admin_sis`;
CREATE TABLE IF NOT EXISTS `admin_sis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `role` int NOT NULL DEFAULT '1',
  `verify_token` varchar(124) DEFAULT NULL,
  `image` varchar(124) DEFAULT NULL,
  `state` tinyint NOT NULL DEFAULT '1' COMMENT '1 -> ative, 2 -> deactive\r\n',
  `forgot_token` varchar(256) DEFAULT NULL,
  `forgot_token_expire` datetime DEFAULT NULL,
  `remember_token` varchar(124) DEFAULT NULL,
  `expire_remember_token` varchar(124) DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `admin_sis`
--

INSERT INTO `admin_sis` (`id`, `name`, `phone`, `password`, `role`, `verify_token`, `image`, `state`, `forgot_token`, `forgot_token_expire`, `remember_token`, `expire_remember_token`, `last_visit`, `created_at`, `updated_at`) VALUES
(1, 'ahmad', '11', '$2y$10$j/uRpDUpYm/4mFe2k5Mz3eIhr89/207kgYTsKuZ13jHYDAb3bD6Rm', 3, NULL, NULL, 1, NULL, '2024-05-22 18:20:16', 'af98ebf7e817d5dfb0a60943047a8e0407a7a7cb36e46f726d3c3e63fe52a68f', '3', NULL, '2024-05-23 22:50:16', '2025-08-04 00:00:51'),
(2, 'ahmad', '22', '$2y$10$j/uRpDUpYm/4mFe2k5Mz3eIhr89/207kgYTsKuZ13jHYDAb3bD6Rm', 1, NULL, NULL, 1, NULL, '2024-05-22 18:20:16', NULL, NULL, NULL, '2024-05-23 22:50:16', '2024-05-24 15:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `att_name` varchar(128) NOT NULL,
  `att_type` varchar(16) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `branch_id`, `att_name`, `att_type`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(14, 1, 'محصول جایزه دار', 'checkbox', 1, 'محمد', '2026-02-20 15:56:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

DROP TABLE IF EXISTS `attribute_values`;
CREATE TABLE IF NOT EXISTS `attribute_values` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `attribute_id` int NOT NULL,
  `value` varchar(128) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `product_id`, `attribute_id`, `value`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(65, 71, 14, '1', 1, '', '2026-03-24 15:10:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `branch_name` varchar(256) NOT NULL,
  `en_branch_name` varchar(128) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `phone2` varchar(15) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `customer_id`, `branch_name`, `en_branch_name`, `phone`, `phone2`, `code`, `address`, `is_active`, `who_it`, `created_at`, `updated_at`) VALUES
(1, 1, 'شعبه مرکزی', 'center', '', '', NULL, '', 1, 'ali', '2025-08-10 15:27:42', NULL),
(2, 2, 'شعبه دوم', 'center', '', '', NULL, '', 2, 'ali', '2025-08-10 15:27:42', NULL),
(3, 3, 'شعبه سوم', 'center', '', '', NULL, '', 2, 'ali', '2025-08-10 15:27:42', NULL),
(4, 4, 'شعبه چهارم\r\n', 'center', '', '', NULL, '', 2, 'ali', '2025-08-10 15:27:42', NULL),
(5, 5, 'شعبه پنجم\r\n', 'center', '', '', NULL, '', 2, 'ali', '2025-08-10 15:27:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `calendar_settings`
--

DROP TABLE IF EXISTS `calendar_settings`;
CREATE TABLE IF NOT EXISTS `calendar_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `calendar_type` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `calendar_settings`
--

INSERT INTO `calendar_settings` (`id`, `calendar_type`, `created_at`, `updated_at`) VALUES
(1, 'jalali', '2025-03-05 07:43:51', '2025-03-07 23:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `cash_boxes`
--

DROP TABLE IF EXISTS `cash_boxes`;
CREATE TABLE IF NOT EXISTS `cash_boxes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `name` varchar(124) NOT NULL,
  `balance_amount` decimal(15,2) DEFAULT NULL,
  `type` varchar(32) NOT NULL COMMENT 'cash, bank, wallet,...',
  `currency` varchar(32) NOT NULL COMMENT 'af, doller,...',
  `opening_balance` decimal(15,2) DEFAULT NULL,
  `allow_negative` tinyint NOT NULL DEFAULT '1' COMMENT '1-> allow, 2-> not allow',
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `cash_boxes`
--

INSERT INTO `cash_boxes` (`id`, `branch_id`, `name`, `balance_amount`, `type`, `currency`, `opening_balance`, `allow_negative`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(1, 1, 'دخل', -100.00, 'cash', 'af', NULL, 1, 1, 'محمد', '2026-02-08 23:10:38', '2026-03-24 15:21:37'),
(2, 1, 'صندوق اصلی', NULL, 'cash', 'af', 0.00, 1, 1, 'محمد', '2026-02-09 00:54:36', '2026-03-21 19:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `cash_transactions`
--

DROP TABLE IF EXISTS `cash_transactions`;
CREATE TABLE IF NOT EXISTS `cash_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `from_cash_id` int DEFAULT NULL,
  `to_cash_id` int DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `exchange_rate` decimal(16,0) DEFAULT NULL,
  `converted_amount` decimal(16,0) DEFAULT NULL,
  `previous_balance` decimal(15,2) DEFAULT NULL,
  `type` tinyint NOT NULL COMMENT '5->in, 6->out',
  `date` varchar(64) DEFAULT NULL,
  `category_id` tinyint DEFAULT NULL,
  `ref_type` int DEFAULT NULL COMMENT 'sale | purchase | manual | salary',
  `ref_id` int DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `center_fund`
--

DROP TABLE IF EXISTS `center_fund`;
CREATE TABLE IF NOT EXISTS `center_fund` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `year` varchar(4) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `who_it` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `center_fund`
--

INSERT INTO `center_fund` (`id`, `branch_id`, `amount`, `year`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(2, 1, 0.00, '1404', 1, 'احمد رضا 1', '2025-11-28 16:09:42', '2025-11-30 14:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `center_fund_transactions`
--

DROP TABLE IF EXISTS `center_fund_transactions`;
CREATE TABLE IF NOT EXISTS `center_fund_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1->imported\r\n',
  `date` varchar(64) NOT NULL,
  `imported_from` varchar(64) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `year` int NOT NULL,
  `month` tinyint NOT NULL,
  `who_it` varchar(32) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `company_name` varchar(128) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `csrf_token_logs`
--

DROP TABLE IF EXISTS `csrf_token_logs`;
CREATE TABLE IF NOT EXISTS `csrf_token_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` varchar(1024) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `daily_reports`
--

DROP TABLE IF EXISTS `daily_reports`;
CREATE TABLE IF NOT EXISTS `daily_reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `report_date` date NOT NULL,
  `total_sales` decimal(15,2) DEFAULT NULL,
  `total_purchases` decimal(15,2) DEFAULT NULL,
  `total_payments` decimal(15,2) DEFAULT NULL,
  `total_received` decimal(15,2) DEFAULT NULL,
  `total_purchase_discounts` decimal(15,2) DEFAULT NULL,
  `total_discount_sales` decimal(15,2) DEFAULT NULL,
  `total_purchase_return` decimal(15,2) DEFAULT NULL,
  `total_sales_return` decimal(15,2) DEFAULT NULL,
  `total_creditor` decimal(15,2) DEFAULT NULL,
  `total_debts` decimal(15,2) DEFAULT NULL,
  `total_expenses` decimal(15,2) DEFAULT NULL,
  `gross_profit` decimal(15,2) DEFAULT NULL,
  `invoice_count` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `branch_id` (`branch_id`),
  KEY `report_date` (`report_date`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `edited`
--

DROP TABLE IF EXISTS `edited`;
CREATE TABLE IF NOT EXISTS `edited` (
  `id` int NOT NULL AUTO_INCREMENT,
  `edit_ref_id` int NOT NULL,
  `who_id` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `edit_draft_items`
--

DROP TABLE IF EXISTS `edit_draft_items`;
CREATE TABLE IF NOT EXISTS `edit_draft_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `original_item_id` int DEFAULT NULL,
  `invoice_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `package_qty` int DEFAULT NULL,
  `unit_qty` int DEFAULT NULL,
  `package_price_sell` decimal(15,2) DEFAULT NULL,
  `unit_price_sell` decimal(15,2) DEFAULT NULL,
  `quantity` int NOT NULL,
  `discount` decimal(15,2) DEFAULT NULL,
  `item_total_price` decimal(15,2) DEFAULT NULL,
  `product_name` int DEFAULT NULL,
  `quantity_in_pack` int DEFAULT NULL,
  `package_price_buy` decimal(15,2) DEFAULT NULL,
  `unit_price_buy` decimal(15,2) DEFAULT NULL,
  `who_it` varchar(16) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=189 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `employee_name` varchar(50) NOT NULL,
  `father_name` varchar(30) DEFAULT NULL,
  `phone` int NOT NULL,
  `password` varchar(124) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `position` varchar(30) NOT NULL,
  `role` int DEFAULT '1',
  `verify_token` varchar(124) DEFAULT NULL,
  `forgot_token` varchar(256) DEFAULT NULL,
  `forgot_token_expire` datetime DEFAULT NULL,
  `remember_token` varchar(124) DEFAULT NULL,
  `expire_remember_token` varchar(124) DEFAULT NULL,
  `image` varchar(124) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `salary_price` int DEFAULT NULL,
  `who_it` varchar(30) NOT NULL,
  `state` tinyint NOT NULL DEFAULT '1',
  `super_admin` tinyint DEFAULT NULL,
  `notif` tinyint NOT NULL DEFAULT '1' COMMENT '1 -> disable, 2 -> active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_name` (`employee_name`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `branch_id`, `employee_name`, `father_name`, `phone`, `password`, `email`, `address`, `position`, `role`, `verify_token`, `forgot_token`, `forgot_token_expire`, `remember_token`, `expire_remember_token`, `image`, `description`, `salary_price`, `who_it`, `state`, `super_admin`, `notif`, `created_at`, `updated_at`) VALUES
(1, 100, 'کاظم حسینی', NULL, 11, '$2y$10$EfLQ0PKX4GeGGXnbfeNCdeao/DXcMSDb2Cm99gbyrLmuovnifQfki', 'ali.afg@gmail.com', NULL, '', 3, NULL, '1daa771ddafb5d1cdc6968fa34a02a4de8c28ed632288dfd33d403619c458ea9', '2025-03-01 13:47:53', NULL, NULL, '2024-09-01-23-53-55_66d4bf4bc0f96.jpg', NULL, 2000, '1', 1, 3, 2, '2024-09-01 23:53:55', '2026-02-03 13:09:03'),
(2, 1, 'کاظم حسینی', '', 66, '$2y$10$Ul3s3Yod6SWPGOw6N1fi.OcUu2jQgwNf0B5odk0V1JTVsqJmowtsa', NULL, '', 'مدیر', 1, NULL, NULL, NULL, 'fef3d5d9760172dc47d03fc9eb36a2a04046f0cd323c5861e4ff9a33dc3bc2ce', '1', NULL, '', 6666, 'کاظم حسینی', 1, NULL, 1, '2026-02-03 13:08:58', '2026-03-15 16:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `title_expenses` varchar(124) DEFAULT NULL,
  `category` varchar(30) NOT NULL,
  `price` varchar(11) DEFAULT NULL,
  `payment_from` varchar(32) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `image_expense` varchar(124) DEFAULT NULL,
  `by_whom` varchar(40) DEFAULT NULL,
  `payment_expense` varchar(11) DEFAULT NULL,
  `remainder_expense` varchar(11) DEFAULT NULL,
  `who_it` varchar(30) NOT NULL,
  `year` smallint NOT NULL,
  `month` tinyint NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content` (`title_expenses`),
  KEY `year` (`year`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_categories`
--

DROP TABLE IF EXISTS `expenses_categories`;
CREATE TABLE IF NOT EXISTS `expenses_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `who_it` varchar(30) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_name` (`cat_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `factor_settings`
--

DROP TABLE IF EXISTS `factor_settings`;
CREATE TABLE IF NOT EXISTS `factor_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `center_name` varchar(256) NOT NULL,
  `slogan` varchar(512) DEFAULT NULL,
  `phone1` varchar(15) DEFAULT NULL,
  `phone2` varchar(15) DEFAULT NULL,
  `phone3` varchar(15) DEFAULT NULL,
  `phone4` varchar(15) DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `factor_settings`
--

INSERT INTO `factor_settings` (`id`, `branch_id`, `center_name`, `slogan`, `phone1`, `phone2`, `phone3`, `phone4`, `address`, `website`, `email`, `image`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(1, 1, 'شرکت رزین و رنگ سازی افغان فیضی ', 'شعار در این قسمت است', '0799585858', '0790207878', '0788888888', '0799454558', 'افغانستان-هرات، جاده بانک خون، رو به روی اتاق های تجارت مارکت انصاری \r\n', 'www.afghanfaizi.com', 'afghanfaizi@info.com', '2025-11-14-23-10-57_691777b98e87c.png', 1, 'محمد', '2025-11-13 19:12:30', '2026-03-06 22:50:49');

-- --------------------------------------------------------

--
-- Table structure for table `financial_summary`
--

DROP TABLE IF EXISTS `financial_summary`;
CREATE TABLE IF NOT EXISTS `financial_summary` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `initial_balance` decimal(20,2) DEFAULT NULL,
  `total_sales_amount` decimal(20,2) DEFAULT NULL,
  `total_purchase_amount` decimal(20,2) DEFAULT NULL,
  `total_profit` decimal(20,2) DEFAULT NULL,
  `total_expense` decimal(20,2) DEFAULT NULL,
  `total_cash_in` decimal(20,2) DEFAULT NULL,
  `total_cash_out` decimal(20,2) DEFAULT NULL,
  `total_debt_to_users` decimal(20,2) DEFAULT NULL,
  `total_debt_from_users` decimal(20,2) DEFAULT NULL,
  `total_sales_count` int DEFAULT NULL,
  `total_purchases_count` int DEFAULT NULL,
  `total_sales_discount` decimal(15,2) DEFAULT NULL,
  `total_purchase_discount` decimal(20,2) DEFAULT NULL,
  `current_balance` decimal(15,2) DEFAULT NULL,
  `total_return_from_purchase` decimal(20,2) DEFAULT NULL,
  `total_return_from_sales` decimal(20,2) DEFAULT NULL,
  `year` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `financial_summary`
--

INSERT INTO `financial_summary` (`id`, `branch_id`, `initial_balance`, `total_sales_amount`, `total_purchase_amount`, `total_profit`, `total_expense`, `total_cash_in`, `total_cash_out`, `total_debt_to_users`, `total_debt_from_users`, `total_sales_count`, `total_purchases_count`, `total_sales_discount`, `total_purchase_discount`, `current_balance`, `total_return_from_purchase`, `total_return_from_sales`, `year`, `status`, `created_at`, `updated_at`) VALUES
(15, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, '2025-08-10 15:27:42', '2025-09-27 16:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `funds`
--

DROP TABLE IF EXISTS `funds`;
CREATE TABLE IF NOT EXISTS `funds` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `fund_type` varchar(32) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `income` decimal(15,2) NOT NULL,
  `transferred` decimal(17,2) NOT NULL,
  `year` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `funds`
--

INSERT INTO `funds` (`id`, `branch_id`, `fund_type`, `total`, `income`, `transferred`, `year`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'main_fund', 500.00, 87259.00, 0.00, 1404, 1, '2025-11-23 19:23:29', '2026-02-08 17:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `fund_transactions`
--

DROP TABLE IF EXISTS `fund_transactions`;
CREATE TABLE IF NOT EXISTS `fund_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `branch_id_to` int DEFAULT NULL,
  `fund_type_from` tinyint NOT NULL COMMENT '1-> income to main fund',
  `amount` decimal(15,2) NOT NULL,
  `date` varchar(128) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `year` int NOT NULL,
  `month` tinyint NOT NULL,
  `who_it` varchar(32) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `product_id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `product_name` varchar(124) NOT NULL,
  `quantity` varchar(11) DEFAULT NULL,
  `package_price_buy` decimal(12,2) DEFAULT NULL,
  `package_price_sell` decimal(12,2) DEFAULT NULL,
  `unit_price_buy` decimal(15,2) DEFAULT NULL,
  `unit_price_sell` decimal(15,2) DEFAULT NULL,
  `quantity_in_pack` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_name` (`product_name`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

DROP TABLE IF EXISTS `inventory_movements`;
CREATE TABLE IF NOT EXISTS `inventory_movements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `invoice_id` int DEFAULT NULL,
  `invoice_item_id` int DEFAULT NULL,
  `movement_type` tinyint DEFAULT NULL COMMENT '1.out / 2.in\r\n',
  `reference_type` tinyint NOT NULL COMMENT '1->sell, 2->buy, 3->return from sell, 4->return from buy, 5->edit',
  `entry_mode` varchar(16) DEFAULT NULL COMMENT 'unit or package',
  `total_unit_qty` int DEFAULT NULL,
  `remaining_qty` int DEFAULT NULL,
  `package_price_buy` decimal(15,2) DEFAULT NULL,
  `package_price_sell` decimal(15,2) DEFAULT NULL,
  `unit_price_buy` decimal(15,2) DEFAULT NULL COMMENT 'price buy',
  `unit_price_sell` decimal(15,2) DEFAULT NULL,
  `movement_date` varchar(64) DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `location` varchar(512) DEFAULT NULL,
  `expiration_date` varchar(64) DEFAULT NULL,
  `who_it` varchar(16) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(48) DEFAULT NULL,
  `ref_id` varchar(32) DEFAULT NULL,
  `branch_id` varchar(32) DEFAULT NULL,
  `user_id` varchar(32) DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `discount` decimal(15,2) DEFAULT NULL,
  `date` varchar(128) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT NULL,
  `invoice_type` tinyint DEFAULT NULL COMMENT '1-> sell, 2->purchase, 3-> return from sell, 4-> return from purchase',
  `payment_type` tinyint NOT NULL COMMENT '1-> naghd, 2-> nesie',
  `ancillary_expenses` decimal(12,2) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `year` int DEFAULT NULL,
  `month` tinyint DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `description` varchar(1024) DEFAULT NULL,
  `who_it` varchar(128) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`,`user_id`),
  KEY `invoice_type` (`invoice_type`),
  KEY `date` (`date`),
  KEY `invoice_number` (`invoice_number`),
  KEY `idx_branch_user` (`branch_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(128) NOT NULL,
  `package_price_buy` decimal(15,2) DEFAULT NULL,
  `package_price_sell` decimal(15,2) DEFAULT NULL,
  `unit_price_buy` decimal(15,2) DEFAULT NULL,
  `unit_price_sell` decimal(15,2) DEFAULT NULL,
  `quantity_in_pack` int NOT NULL,
  `package_qty` int DEFAULT NULL,
  `unit_qty` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `item_total_price` decimal(12,2) NOT NULL,
  `warehouse_id` int DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `discount` decimal(12,2) DEFAULT NULL,
  `expiration_date` varchar(64) DEFAULT NULL,
  `item_status` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_profits`
--

DROP TABLE IF EXISTS `invoice_profits`;
CREATE TABLE IF NOT EXISTS `invoice_profits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `invoice_item_id` int NOT NULL,
  `product_id` int NOT NULL,
  `batch_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_type` varchar(16) DEFAULT NULL,
  `buy_price` decimal(15,2) NOT NULL,
  `sell_price` decimal(15,2) NOT NULL,
  `profit` decimal(15,2) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `user_id` int NOT NULL,
  `ref_id` varchar(32) NOT NULL,
  `notif_type` tinyint NOT NULL DEFAULT '1' COMMENT '1->sale,buy.returns, 1->payment and recipt, 3->salaries',
  `title` varchar(64) NOT NULL,
  `msg` varchar(1024) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `read_at` date DEFAULT NULL,
  `state` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_status_created` (`user_id`,`status`,`created_at`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `not_access_logs`
--

DROP TABLE IF EXISTS `not_access_logs`;
CREATE TABLE IF NOT EXISTS `not_access_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `page_address` varchar(124) NOT NULL,
  `ip_address` varchar(32) DEFAULT NULL,
  `user_agent` varchar(512) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_name` varchar(50) DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=276 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `section_name`, `employee_id`, `created_at`, `updated_at`) VALUES
(274, 'general', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
CREATE TABLE IF NOT EXISTS `positions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `who_it` varchar(30) NOT NULL,
  `state` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `branch_id`, `name`, `who_it`, `state`, `created_at`, `updated_at`) VALUES
(1, 1, 'حسابدار', 'کاظم حسینی', 1, '2025-11-07 22:01:20', '2026-01-04 16:12:44');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `product_name` varchar(124) NOT NULL,
  `product_code` varchar(124) DEFAULT NULL,
  `package_price_buy` decimal(15,2) DEFAULT NULL,
  `package_price_sell` decimal(15,2) DEFAULT NULL,
  `unit_price_buy` decimal(15,2) DEFAULT NULL,
  `unit_price_sell` decimal(15,2) DEFAULT NULL,
  `product_cat` varchar(124) NOT NULL,
  `package_type` varchar(32) NOT NULL,
  `quantity_in_pack` int NOT NULL,
  `unit_type` varchar(32) DEFAULT NULL,
  `reorder_point` int DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `product_image` varchar(256) DEFAULT NULL,
  `who_it` varchar(60) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 -> active, 2 => deactive',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_name` (`product_name`),
  KEY `product_code` (`product_code`),
  KEY `idx_branch_product` (`branch_id`,`product_name`),
  KEY `award` (`reorder_point`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `products_units`
--

DROP TABLE IF EXISTS `products_units`;
CREATE TABLE IF NOT EXISTS `products_units` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `global` tinyint NOT NULL,
  `product_unit` varchar(124) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `products_units`
--

INSERT INTO `products_units` (`id`, `branch_id`, `global`, `product_unit`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'لیتر', 1, 'محمد', '2026-03-14 23:08:42', NULL),
(2, 1, 0, 'سطل', 1, 'محمد', '2026-03-14 23:08:44', NULL),
(3, 1, 0, 'عدد', 1, 'محمد', '2026-03-14 23:08:45', NULL),
(4, 1, 0, 'گالن', 1, 'محمد', '2026-03-14 23:08:48', NULL),
(5, 1, 0, 'کارتن', 1, 'محمد', '2026-03-14 23:08:49', NULL),
(6, 1, 0, 'کیلو', 1, 'محمد', '2026-03-14 23:08:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_batches`
--

DROP TABLE IF EXISTS `product_batches`;
CREATE TABLE IF NOT EXISTS `product_batches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `product_id` int NOT NULL,
  `package_price_buy` decimal(15,2) DEFAULT NULL,
  `package_price_sell` decimal(15,2) DEFAULT NULL,
  `unit_price_buy` decimal(15,2) DEFAULT NULL,
  `unit_price_sell` decimal(15,2) DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `expiration_date` varchar(256) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `product_cat`
--

DROP TABLE IF EXISTS `product_cat`;
CREATE TABLE IF NOT EXISTS `product_cat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `global` tinyint NOT NULL,
  `product_cat_name` varchar(124) NOT NULL,
  `who_it` varchar(60) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `product_cat`
--

INSERT INTO `product_cat` (`id`, `branch_id`, `global`, `product_cat_name`, `who_it`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'تینر', 'محمد', 1, '2026-03-14 23:38:17', NULL),
(2, 1, 0, 'رنگ فوری هیل', 'محمد', 1, '2026-03-14 23:38:21', NULL),
(3, 1, 0, 'رنگ فوری یشم افغان فیضی', 'محمد', 1, '2026-03-14 23:38:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `safe_transactions`
--

DROP TABLE IF EXISTS `safe_transactions`;
CREATE TABLE IF NOT EXISTS `safe_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_title` varchar(256) NOT NULL,
  `amount` int NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `money_invoice` varchar(256) DEFAULT NULL,
  `type` varchar(14) NOT NULL,
  `who_it` varchar(30) NOT NULL,
  `year` smallint NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 => active, 2 => deactive',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `salary_months`
--

DROP TABLE IF EXISTS `salary_months`;
CREATE TABLE IF NOT EXISTS `salary_months` (
  `id` int NOT NULL AUTO_INCREMENT,
  `month_number` tinyint NOT NULL,
  `month_name` varchar(16) NOT NULL,
  `year` int NOT NULL,
  `is_current` tinyint NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `salary_months`
--

INSERT INTO `salary_months` (`id`, `month_number`, `month_name`, `year`, `is_current`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'حمل', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(2, 2, 'ثور', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(3, 3, 'جوزا', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(4, 4, 'سرطان', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(5, 5, 'اسد', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(6, 6, 'سنبله', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(7, 7, 'میزان', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(8, 8, 'عقرب', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(9, 9, 'قوس', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(10, 10, 'جدی', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(11, 11, 'دلو', 1404, 0, 0, '2025-11-08 12:01:26', NULL),
(12, 12, 'حوت', 1404, 0, 0, '2025-11-08 12:01:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salary_transactions`
--

DROP TABLE IF EXISTS `salary_transactions`;
CREATE TABLE IF NOT EXISTS `salary_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `period_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` tinyint NOT NULL DEFAULT '1' COMMENT '1->salary, 2->overtime, 3->kasri',
  `description` varchar(1024) DEFAULT NULL,
  `date` varchar(32) NOT NULL,
  `year` int NOT NULL,
  `month` tinyint NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
CREATE TABLE IF NOT EXISTS `sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(124) DEFAULT NULL,
  `en_name` varchar(30) DEFAULT NULL,
  `section_id` int DEFAULT NULL,
  `who_it` varchar(30) NOT NULL,
  `state` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `en_name`, `section_id`, `who_it`, `state`, `created_at`, `updated_at`) VALUES
(151, 'شاگردان', 'students', NULL, '', 1, '2024-11-23 21:51:01', NULL),
(152, 'ثبت شاگرد', 'addNewStudent', 151, '', 1, '2024-11-23 21:51:43', NULL),
(153, 'نمایش شاگردان', 'showStudents', 151, '', 1, '2024-11-23 21:52:01', NULL),
(154, 'ثبت شاگرد در صنف', 'addStudentAtClass', 151, '', 1, '2024-11-23 21:52:33', NULL),
(155, 'مدیریت صنف ها', 'classesManagement', NULL, '', 1, '2024-11-23 21:53:06', NULL),
(156, 'برگزاری صنف جدید', 'holdingNewClass', 155, '', 1, '2024-11-23 21:53:48', NULL),
(157, 'نمایش صنف های برگزار شده', 'showHoldingClasses', 155, '', 1, '2024-11-23 21:54:17', NULL),
(158, 'ساعت های درسی', 'times', 155, '', 1, '2024-11-23 21:54:43', NULL),
(159, 'صنف های درسی', 'classRooms', 155, '', 1, '2024-11-23 21:56:16', NULL),
(160, 'مدیریت درسی', 'courseManagement', NULL, '', 1, '2024-11-23 21:56:51', NULL),
(161, 'دیپارتمنت ها', 'departments', 159, '', 1, '2024-11-23 21:57:10', NULL),
(162, 'دروس', 'lessons', 160, '', 1, '2024-11-23 21:57:37', NULL),
(163, 'پکیج ها', 'packages', 160, '', 1, '2024-11-23 21:57:59', NULL),
(164, 'مدیریت نمرات', 'gradeManagement', NULL, '', 1, '2024-11-23 21:58:31', NULL),
(165, 'ثبت نمرات', 'addGrade', 164, '', 1, '2024-11-23 21:59:01', NULL),
(166, 'نمایش نمرات', 'showGrade', 164, '', 1, '2024-11-23 21:59:14', NULL),
(167, 'مدیریت حاضری', 'attendanceManagement', NULL, '', 1, '2024-11-23 21:59:43', NULL),
(168, 'ثبت حاضری', 'addAttendance', 167, '', 1, '2024-11-23 22:00:07', NULL),
(169, 'نمایش حاضری ها', 'showAttendance', 167, '', 1, '2024-11-23 22:00:20', NULL),
(170, 'مدیریت مالی', 'financialManagement', NULL, '', 1, '2024-11-23 22:00:41', NULL),
(171, 'موجودی صندوق', 'fundBalance', 170, '', 1, '2024-11-23 22:01:38', NULL),
(172, 'افزودن پول (کیف پول)', 'addMoneyToWallet', 170, '', 1, '2024-11-23 22:02:56', NULL),
(173, 'نمایش کیف پول', 'showWallets', 170, '', 1, '2024-11-23 22:03:13', NULL),
(174, 'مصارف', 'expenses', NULL, '', 1, '2024-11-23 22:04:22', NULL),
(175, 'ثبت مصرفی', 'addExpenses', 174, '', 1, '2024-11-23 22:05:03', NULL),
(176, 'نمایش مصارف', 'showExpenses', 174, '', 1, '2024-11-23 22:05:31', NULL),
(177, 'مدیریت دسته بندی های مصارف', 'categoryExpenses', 174, '', 1, '2024-11-23 22:06:11', NULL),
(178, 'کارمندان', 'employees', NULL, '', 1, '2024-11-23 22:07:05', NULL),
(179, 'ثبت کارمند جدید', 'addEmployee', 178, '', 1, '2024-11-23 22:07:29', NULL),
(180, 'نمایش کارمندان', 'showEmployees', 178, '', 1, '2024-11-23 22:07:44', NULL),
(181, 'مدیریت وظایف کارمندان', 'positionsOfEmployees', 178, '', 1, '2024-11-23 22:08:26', NULL),
(182, 'مراکز و قراردادها', 'centerAndContracts', NULL, '', 1, '2024-11-23 22:09:11', NULL),
(183, 'مدیریت مراکز', 'centerManagement', 182, '', 1, '2024-11-23 22:09:40', NULL),
(184, 'مدیریت قراردادها', 'contractManagement', 182, '', 1, '2024-11-23 22:10:06', NULL),
(185, 'مدیریت سوالات', 'questionsManagement', NULL, '', 1, '2024-11-23 22:10:32', NULL),
(186, 'سوالات چهار گزینه ای', 'fourOptionsQuestions', 185, '', 1, '2024-11-23 22:12:02', NULL),
(187, 'تنظیمات', 'settings', NULL, '', 1, '2024-11-23 22:12:26', NULL),
(188, 'ثبت پکیج جدید', 'package-store', 163, '', 1, '2025-01-04 21:00:33', NULL),
(189, 'صفحه ویرایش پکیج', 'edit-package', 163, '', 1, '2025-01-04 22:43:05', NULL),
(190, 'ثبت ویرایش پکیج', 'edit-package-store', 163, '', 1, '2025-01-04 22:58:50', NULL),
(191, 'تغییر وضعیت پکیج', 'change-status-package', 163, '', 1, '2025-01-04 23:14:33', NULL),
(192, 'نمایش جزئیات پکیج', 'package-details', 163, '', 1, '2025-01-04 23:16:30', NULL),
(193, 'ثبت درس', 'lesson-store', 162, '', 1, '2025-01-04 23:28:22', NULL),
(194, 'صفحه ویرایش درس', 'edit-lesson', 162, '', 1, '2025-01-04 23:38:56', NULL),
(195, 'ثبت ویرایش درس', 'edit-lesson-store', 162, '', 1, '2025-01-04 23:47:03', NULL),
(196, 'نمایش جزئیات درس', 'lesson-details', 162, '', 1, '2025-01-05 14:34:50', NULL),
(197, 'تغییر وضعیت درس', 'change-status-lesson', 162, '', 1, '2025-01-05 14:38:43', NULL),
(198, 'ثبت صنف درسی جدید', 'classRoom-store', 155, '', 1, '2025-01-05 15:03:22', NULL),
(199, 'صفحه ویرایش صنف درسی', 'edit-classRoom', 155, '', 1, '2025-01-05 15:06:01', NULL),
(200, 'ثبت ویرایش صنف درسی', 'edit-classRoom-store', 155, '', 1, '2025-01-05 15:07:56', NULL),
(201, 'نمایش جزئیات صنف درسی', 'classRoom-details', 155, '', 1, '2025-01-05 15:17:21', NULL),
(202, 'تغییر وضعیت صنف درسی', 'change-status-classRoom', 155, '', 1, '2025-01-05 15:20:09', NULL),
(203, 'ثبت ساعت درسی', 'time-store', 155, '', 1, '2025-01-05 15:42:09', NULL),
(204, 'ویرایش ساعت درسی', 'edit-time', 155, '', 1, '2025-01-05 15:48:47', NULL),
(205, 'ثبت ویرایش ساعت درسی', 'edit-time-store', 155, '', 1, '2025-01-05 15:52:44', NULL),
(206, 'نمایش جزئیات ساعت درسی', 'time-details', 155, '', 1, '2025-01-05 15:56:20', NULL),
(207, 'تغییر وضعیت ساعت درسی', 'change-status-time', 155, '', 1, '2025-01-05 15:58:16', NULL),
(208, 'انتخاب درس برای برگزاری صنف جدید', 'select-losson', 155, '', 1, '2025-01-05 16:34:02', NULL),
(209, 'نمایش فرم برگزاری صنف جدید', 'add-class', 155, '', 1, '2025-01-05 16:39:26', NULL),
(210, 'ثبت برگزاری صنف جدید', 'class-store', 155, '', 1, '2025-01-05 21:52:24', NULL),
(211, 'نمایش جزئیات صنف برگزار شده', 'class-details', 155, '', 1, '2025-01-05 21:58:16', NULL),
(212, 'صفحه انتخاب دیپارتمنت برای ثبت شاگرد در صنف', 'get-department-register', 151, 'ali', 1, '2025-01-07 22:38:44', NULL),
(213, 'فیس ها', 'payments', NULL, 'ali', 1, '2025-01-22 23:38:23', NULL),
(214, 'ثبت فیس شاگرد', 'fee-payment', 213, 'ali', 1, '2025-01-22 23:38:52', NULL),
(215, 'تغییر بین سال ها', 'years', NULL, '', 1, '2024-11-23 22:00:41', NULL),
(216, 'بروزرسانی بخش‌ها', 'update_sections', NULL, '', 1, '2024-11-23 22:07:05', NULL),
(217, 'عمومی', 'general', NULL, '', 1, '2024-11-23 21:51:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `sell_any_situation` tinyint NOT NULL DEFAULT '1' COMMENT '1 -> permission to sell any situation, 2 -> not permission',
  `buy_any_situation` tinyint NOT NULL DEFAULT '1' COMMENT '1 -> permission to buy any situation, 2 -> not permission',
  `expiration_date` tinyint DEFAULT '1',
  `warehouse` tinyint NOT NULL DEFAULT '1',
  `help_status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `branch_id`, `sell_any_situation`, `buy_any_situation`, `expiration_date`, `warehouse`, `help_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 2, 2, 1, '2025-04-01 13:30:36', '2026-03-15 15:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `user_name` varchar(64) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(124) DEFAULT NULL,
  `password` varchar(124) DEFAULT NULL,
  `is_customer` tinyint DEFAULT NULL,
  `is_seller` tinyint DEFAULT NULL,
  `remnants_past` decimal(15,2) DEFAULT NULL,
  `reagent` int DEFAULT NULL,
  `reagent_phone` int DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `user_image` varchar(254) DEFAULT NULL,
  `father_name` varchar(30) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_name` (`user_name`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `user_name`, `phone`, `email`, `password`, `is_customer`, `is_seller`, `remnants_past`, `reagent`, `reagent_phone`, `address`, `description`, `user_image`, `father_name`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(1, 1, 'حساب عمومی', '1', NULL, '$2y$10$T1Tm9NDhpsC4wuuQWU8vDejws1SUZqDZ7hV03Q0Lw7FYDV4/41m6O', 1, 1, 0.00, 0, 0, '', '', NULL, NULL, 1, 'کاظم حسینی', '2026-03-24 15:21:01', NULL),
(2, 2, 'حساب عمومی', '1', NULL, '$2y$10$T1Tm9NDhpsC4wuuQWU8vDejws1SUZqDZ7hV03Q0Lw7FYDV4/41m6O', 1, 1, 0.00, 0, 0, '', '', NULL, NULL, 2, 'کاظم حسینی', '2026-03-24 15:21:01', NULL),
(3, 3, 'حساب عمومی', '1', NULL, '$2y$10$T1Tm9NDhpsC4wuuQWU8vDejws1SUZqDZ7hV03Q0Lw7FYDV4/41m6O', 1, 1, 0.00, 0, 0, '', '', NULL, NULL, 2, 'کاظم حسینی', '2026-03-24 15:21:01', NULL),
(4, 4, 'حساب عمومی', '1', NULL, '$2y$10$T1Tm9NDhpsC4wuuQWU8vDejws1SUZqDZ7hV03Q0Lw7FYDV4/41m6O', 1, 1, 0.00, 0, 0, '', '', NULL, NULL, 2, 'کاظم حسینی', '2026-03-24 15:21:01', NULL),
(5, 5, 'حساب عمومی', '1', NULL, '$2y$10$T1Tm9NDhpsC4wuuQWU8vDejws1SUZqDZ7hV03Q0Lw7FYDV4/41m6O', 1, 1, 0.00, 0, 0, '', '', NULL, NULL, 2, 'کاظم حسینی', '2026-03-24 15:21:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_transactions`
--

DROP TABLE IF EXISTS `users_transactions`;
CREATE TABLE IF NOT EXISTS `users_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `ref_id` varchar(32) DEFAULT NULL,
  `user_id` int NOT NULL,
  `transaction_type` tinyint NOT NULL DEFAULT '1' COMMENT '1 -> sale, 2 -> buy, 3 -> return for sale, 4 -> return for purchars, 5 -> Taking money from the user, 6 -> paying money to the user',
  `total_amount` decimal(15,2) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT NULL,
  `transaction_date` varchar(65) NOT NULL,
  `discount` decimal(15,2) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `adjustment_reason` varchar(1024) DEFAULT NULL,
  `balance_effect` tinyint DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seller_id` (`user_id`),
  KEY `created_at` (`created_at`),
  KEY `transaction_type` (`transaction_type`),
  KEY `ref_id` (`ref_id`),
  KEY `branch_id` (`branch_id`),
  KEY `idx_user_branch_status_date` (`user_id`,`branch_id`,`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user_agent`
--

DROP TABLE IF EXISTS `user_agent`;
CREATE TABLE IF NOT EXISTS `user_agent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(124) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `user_ip` varchar(30) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `isp` varchar(128) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `org` varchar(100) DEFAULT NULL,
  `os` varchar(20) DEFAULT NULL,
  `browser` varchar(20) DEFAULT NULL,
  `device` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user_licenses`
--

DROP TABLE IF EXISTS `user_licenses`;
CREATE TABLE IF NOT EXISTS `user_licenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `branch_id` int NOT NULL,
  `license_key` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(64) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user_licenses`
--

INSERT INTO `user_licenses` (`id`, `user_id`, `branch_id`, `license_key`, `start_date`, `end_date`, `status`, `who_it`, `created_at`, `updated_at`) VALUES
(1, 48, 48, 'sadfa3243edfdsfd', '2025-09-04', '2026-09-17', 1, NULL, '2025-09-17 12:37:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE IF NOT EXISTS `warehouses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(24) NOT NULL,
  `warehouse_name` varchar(256) DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `branch_id` int NOT NULL,
  `manager_id` int DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `who_it` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `type`, `warehouse_name`, `address`, `branch_id`, `manager_id`, `is_active`, `who_it`, `created_at`, `updated_at`) VALUES
(4, 'shop', 'داخلی فروشگاه', NULL, 1, NULL, 1, '', '2026-02-15 17:22:38', NULL),
(5, 'warehouse', 'انبار شماره 1', NULL, 1, NULL, 1, '', '2026-02-15 17:22:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

DROP TABLE IF EXISTS `years`;
CREATE TABLE IF NOT EXISTS `years` (
  `id` int NOT NULL AUTO_INCREMENT,
  `year` smallint DEFAULT NULL,
  `calendar_type` varchar(11) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 -> active, 2 -> deactive',
  `activation_code` varchar(32) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`id`, `year`, `calendar_type`, `status`, `activation_code`, `created_at`, `updated_at`) VALUES
(6, 1405, 'jalali', 1, NULL, '2025-02-25 17:47:21', '2025-09-16 23:41:15');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

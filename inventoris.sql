-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 05, 2020 at 04:05 PM
-- Server version: 10.4.11-MariaDB
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
-- Database: `inventoris`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `code` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_name` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`code`, `name`, `address_name`) VALUES
(1, 'PT. Bali Gatra Komunikasi', 'Bali'),
(2, 'PT Tokopedia', 'Bandung'),
(3, 'PT Budi', 'Jl. Budis');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_sell`
--

CREATE TABLE `history_sell` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `modified_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history_sell`
--

INSERT INTO `history_sell` (`id`, `modified_user`, `created_at`, `modified_at`) VALUES
(1, 'Tengku', '2020-07-05 13:47:32', '2020-07-05 05:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `history_sell_product`
--

CREATE TABLE `history_sell_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `history_sell` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `branch_code` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `buy_price` float NOT NULL,
  `sell_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history_sell_product`
--

INSERT INTO `history_sell_product` (`id`, `history_sell`, `product_id`, `branch_code`, `qty`, `buy_price`, `sell_price`) VALUES
(1, 1, 16, 1, 30, 5000000, 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2020_06_23_085335_create_users_table', 1),
(4, '2020_06_23_085525_create_branch_table', 2),
(5, '2020_06_23_085548_create_products_stock_table', 2),
(6, '2020_06_23_085617_create_products_table', 2),
(7, '2020_06_24_060817_create_products_table', 3),
(8, '2020_06_24_060941_create_products_stock_table', 4),
(9, '2020_06_24_061037_create_users_table', 5),
(10, '2020_06_24_061223_create_products_stock_table', 6),
(11, '2020_06_30_034444_add_roles_to_users_table', 7),
(12, '2020_07_02_045953_create_history_sell_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('neneklo1234@gmail.com', '$2y$10$WTmirGkeEKtW9w7CWX6CTOGs.1MtqG7MTV0MAq/HRkB3GGDt.PkU6', '2020-07-01 22:49:55'),
('anountengku@gmail.com', '$2y$10$lbapoI9idvG9TcitO4b/3uCO0dL/rXtPdUwXAaRx13DbNz2nbdiIO', '2020-07-01 22:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sell_price` float NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `sell_price`, `status`, `created_at`, `modified_at`) VALUES
(5, 'Playstation 5 with 825GB/SSD', 'playstation-5-with-825gbssd', 10, 'in_stock', '2020-07-02 10:31:06', '2020-07-05 10:42:43'),
(6, 'Game Outward', 'game-outward', 15, 'out_of_stock', '2020-06-29 12:20:54', '2020-07-05 04:55:43'),
(16, 'Buku Web Master Edisi Terlengkap', 'buku-web-master-edisi-terlengkap', 1000000, 'in_stock', '2020-07-04 20:18:31', '2020-07-05 10:42:43'),
(17, 'Cicak', 'cicak', 1000, 'in_stock', '2020-07-05 00:26:25', '2020-07-05 10:42:43');

-- --------------------------------------------------------

--
-- Table structure for table `products_stock`
--

CREATE TABLE `products_stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `branch_code` bigint(20) UNSIGNED DEFAULT NULL,
  `buy_price` float DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_stock`
--

INSERT INTO `products_stock` (`id`, `product_id`, `branch_code`, `buy_price`, `qty`, `created_at`, `modified_at`) VALUES
(2, 6, 1, 150000, 19, '2020-07-02 05:54:55', '2020-07-05 04:55:43'),
(3, 5, 1, 9500000, 180, '2020-07-05 04:57:34', '2020-07-05 02:40:16'),
(13, 16, 1, 5000000, 850, '2020-07-05 02:24:08', '2020-07-05 05:47:32'),
(17, 16, 1, 5000000, 2000, '2020-07-05 05:24:08', '2020-07-05 05:47:32'),
(18, 17, 2, NULL, NULL, '2020-07-05 08:26:25', '2020-07-05 08:26:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_code` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `roles` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `full_name`, `email`, `email_verified_at`, `remember_token`, `status`, `branch_code`, `created_at`, `modified_at`, `roles`, `modified_user`) VALUES
(1, 'Tengku', '$2y$10$kC.Lylk7oVc/A7l61jVk..GK9kruq72TF6IBKhyl6QrqSsDmgDuLe', NULL, 'anountengku@gmail.com', NULL, 's9BwiDqXLVxBeW66vKmz25EFw1c9paNRvLyQyx41LSTcysARQCuv4n8LAfyj', NULL, 2, '2020-07-05 10:49:10', '2020-07-05 10:49:10', '[\"Master\"]', ''),
(2, 'Chen Beixuan', '$2y$10$offPTqdqWsVVJRP2uTB3.ebcDyUD6Rv6dsiTgj.t3.SyHyR4nQYW.', NULL, 'neneklo1234@gmail.com', NULL, NULL, NULL, 1, '2020-07-05 09:48:16', '2020-07-05 09:48:16', '[\"Admin\"]', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_sell`
--
ALTER TABLE `history_sell`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_sell_product`
--
ALTER TABLE `history_sell_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `branch_code` (`branch_code`),
  ADD KEY `history_sell` (`history_sell`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_stock`
--
ALTER TABLE `products_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `products_stock_ibfk_2` (`branch_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_ibfk_1` (`branch_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `code` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_sell`
--
ALTER TABLE `history_sell`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `history_sell_product`
--
ALTER TABLE `history_sell_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products_stock`
--
ALTER TABLE `products_stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history_sell_product`
--
ALTER TABLE `history_sell_product`
  ADD CONSTRAINT `history_branch_code` FOREIGN KEY (`branch_code`) REFERENCES `branch` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `history_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `history_sell` FOREIGN KEY (`history_sell`) REFERENCES `history_sell` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products_stock`
--
ALTER TABLE `products_stock`
  ADD CONSTRAINT `products_stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_stock_ibfk_2` FOREIGN KEY (`branch_code`) REFERENCES `branch` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`branch_code`) REFERENCES `branch` (`code`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

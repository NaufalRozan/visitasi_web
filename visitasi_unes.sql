-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 10, 2024 at 12:58 AM
-- Server version: 8.0.35
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visitasi_unes`
--

-- --------------------------------------------------------

--
-- Table structure for table `akreditasi`
--

CREATE TABLE `akreditasi` (
  `id` bigint UNSIGNED NOT NULL,
  `sub_unit_id` bigint UNSIGNED NOT NULL,
  `nama_akreditasi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'tidak aktif'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akreditasi`
--

INSERT INTO `akreditasi` (`id`, `sub_unit_id`, `nama_akreditasi`, `status`) VALUES
(2, 4, 'A', 'tidak aktif'),
(5, 1, 'Akreditasi 1', 'tidak aktif'),
(6, 1, 'Akreditasi 2', 'tidak aktif'),
(7, 1, 'Akreditasi 3 Edited', 'tidak aktif'),
(9, 1, 'LAM2025', 'aktif'),
(10, 2, 'Akreditasi TM', 'aktif'),
(25, 1, 'Akreditasi 5', 'tidak aktif'),
(33, 1, 'Akreditasi 6', 'tidak aktif'),
(34, 1, 'Akreditasi 7', 'tidak aktif'),
(35, 1, 'Akreditasi 8', 'tidak aktif'),
(36, 1, 'Akreditasi 9', 'tidak aktif'),
(37, 1, 'Akreditasi 10', 'tidak aktif'),
(38, 1, 'Akreditasi 11', 'tidak aktif');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail`
--

CREATE TABLE `detail` (
  `id` bigint UNSIGNED NOT NULL,
  `substandar_id` bigint UNSIGNED NOT NULL,
  `no_urut` int NOT NULL,
  `nama_detail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail`
--

INSERT INTO `detail` (`id`, `substandar_id`, `no_urut`, `nama_detail`) VALUES
(20, 12, 1, 'Detail 1.1'),
(21, 12, 2, 'Detail 1.2'),
(22, 15, 1, 'Detail 1'),
(24, 18, 1, 'SK Rektor'),
(25, 18, 2, 'SK Senat'),
(30, 18, 3, 'Detail 3'),
(31, 18, 4, 'Detail 4'),
(32, 18, 5, 'Detail 5'),
(33, 18, 6, 'Detail 6'),
(34, 18, 7, 'Detail 7'),
(35, 18, 8, 'Detail 8');

-- --------------------------------------------------------

--
-- Table structure for table `detail_item`
--

CREATE TABLE `detail_item` (
  `id` bigint UNSIGNED NOT NULL,
  `detail_id` bigint UNSIGNED NOT NULL,
  `no_urut` int NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_item`
--

INSERT INTO `detail_item` (`id`, `detail_id`, `no_urut`, `deskripsi`, `lokasi`, `tipe`, `file_path`) VALUES
(35, 20, 1, 'Dokumen 1', 'storage/documents/HNJ2gnPRBTFgl0PXmuLgAW83uarzPBpMD6UreyOD.pdf', 'Document', NULL),
(36, 21, 1, 'Dokumen 1.1.2', 'storage/documents/5q4MVWtRAf2SzuE1AzFsn0YJ2lRoy0wOC64Yyr80.pdf', 'Document', NULL),
(37, 20, 2, 'Dokumen 2', 'storage/documents/9mDyqlPHOURONeToeq1oPr04PygA6XQCRLLooQnP.png', 'Image', NULL),
(38, 20, 3, 'Dokumen 3', 'storage/documents/50mpRvC9FT15jGFscGKPOhWTGFAHKy7i5ZhddKwH.png', 'Video', NULL),
(39, 20, 4, 'Dokumen 4', 'storage/documents/KTSy122IAZCY5IrWidplCDHXGNlby0bAHZgWCCqB.pdf', 'Document', NULL),
(40, 24, 1, 'SK Rektor Penetapan Renstra', 'storage/documents/9LJ9SFsAZ2wcMNcAqJNtothKjCkUv9nyrmJVxKGH.pdf', 'Document', NULL),
(42, 24, 2, 'Dokumen 1', 'storage/documents/fk0gQRQaz7BFOk928v21J0sCVnnzACkCwimwKNd4.pdf', 'Document', NULL),
(44, 24, 3, 'tes', 'storage/documents/8ZbwiOG92x15aJwO2Diai7pzRbdR1SUW8d7ZCtwY.pdf', 'Image', NULL),
(48, 24, 4, 'Documen 2', 'storage/documents/l6Q0N5fHDGO39sPdy0FUKCLKeIKcBmgmCMNSW0QJ.pdf', 'Document', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('xGtwTdTWOKrUdNn2ZPnEVXFGevXs0wzcK4ytqidi', 14, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidnZMZ2oyNFlKZWtPaHdYMDNTNU9KeVlNMzdsNnFxV3lRQmUzWXViTiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcmVzdW1lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTQ7czoxMToic3ViX3VuaXRfaWQiO3M6MToiMSI7fQ==', 1727342234),
('YIkuPKQSNeOcEMr75A0lVKcnNaBr9KaNiTw8ngrC', 13, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidHpGRFNCeHJrT3VZdnQ4R0VSUnJhUEtZY2VKSHRvUkxZa3Rndk13RiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2JlcmthcyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYmVya2FzL2RldGFpbC8xOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEzO3M6MTE6InN1Yl91bml0X2lkIjtzOjE6IjEiO30=', 1727342277);

-- --------------------------------------------------------

--
-- Table structure for table `standar`
--

CREATE TABLE `standar` (
  `id` bigint UNSIGNED NOT NULL,
  `akreditasi_id` bigint UNSIGNED NOT NULL,
  `no_urut` int NOT NULL,
  `nama_standar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `standar`
--

INSERT INTO `standar` (`id`, `akreditasi_id`, `no_urut`, `nama_standar`) VALUES
(11, 6, 1, 'Bagian 1.1'),
(12, 6, 2, 'Bagian 1.2'),
(13, 6, 3, 'Bagian 1.3'),
(17, 5, 2, 'Bagian 2'),
(18, 5, 3, 'Bagian 3'),
(19, 5, 1, 'Bagian 1'),
(20, 6, 4, 'Bagian 1.4'),
(22, 9, 1, 'VMTS'),
(27, 10, 1, 'Bagian 1 TM'),
(30, 10, 2, 'Bagian 2 TM'),
(31, 10, 3, 'Bagian 3 TM'),
(32, 10, 4, 'Bagian 4 TM'),
(37, 9, 2, 'Bagian 2 TI'),
(38, 9, 3, 'Bagian 3 TI Edited'),
(49, 9, 4, 'Bagian 4'),
(50, 9, 5, 'Bagian 5'),
(51, 9, 6, 'Bagian 6'),
(52, 9, 7, 'Bagian 7'),
(53, 9, 8, 'Bagian 8'),
(54, 9, 9, 'Bagian 9');

-- --------------------------------------------------------

--
-- Table structure for table `substandar`
--

CREATE TABLE `substandar` (
  `id` bigint UNSIGNED NOT NULL,
  `standar_id` bigint UNSIGNED NOT NULL,
  `no_urut` int NOT NULL,
  `nama_substandar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `substandar`
--

INSERT INTO `substandar` (`id`, `standar_id`, `no_urut`, `nama_substandar`) VALUES
(12, 11, 1, 'Sub Bagian 1.1'),
(13, 11, 2, 'Sub Bagian 1.2'),
(14, 11, 3, 'Sub Bagian 1.3'),
(15, 19, 2, 'tes'),
(16, 19, 1, 'tes12'),
(17, 11, 4, 'Sub Bagian 1.4'),
(18, 22, 1, 'Penetapan'),
(28, 27, 1, 'Tes 1'),
(40, 27, 2, 'tes'),
(44, 22, 2, 'Sub Bagian 1'),
(45, 22, 3, 'Sub Bagian 3'),
(46, 22, 4, 'Sub Bagian 4'),
(47, 22, 5, 'Sub Bagian 5'),
(48, 22, 6, 'Sub Bagian 6');

-- --------------------------------------------------------

--
-- Table structure for table `sub_units`
--

CREATE TABLE `sub_units` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `nama_sub_unit` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `is_prodi` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_units`
--

INSERT INTO `sub_units` (`id`, `unit_id`, `nama_sub_unit`, `is_prodi`) VALUES
(1, 1, 'Teknologi Informasi', 0),
(2, 1, 'Teknik Mesin', 0),
(3, 2, 'Ekonomi', 0),
(4, 2, 'Akuntansi', 0),
(5, 2, 'Manajemen', 0),
(6, 1, 'Teknik Sipil', 0),
(7, 2, 'Ekonomi Syariah', 0),
(8, 3, 'Universitas', 0);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_unit` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `is_fakultas` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `nama_unit`, `is_fakultas`) VALUES
(1, 'Teknik', 0),
(2, 'Ekonomi dan Bisnis', 0),
(3, 'Universitas', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(10, 'Admin Fakultas Teknik', 'admin_teknik@gmail.com', '$2y$12$8TBQNVMr2dlllbffZH1SjurcN0pjkDKrZyXvWVlRdtf1MnD9mTOFm', 'Fakultas'),
(11, 'Admin Prodi Ekonomi Syariah', 'admin_eksya@gmail.com', '$2y$12$mA53rFBqFS2614uctp5QbeNJ8D/FwPRKgExV4rI8MluqZQsLOZlUa', 'Prodi'),
(12, 'Admin Fakultas Ekonomi dan Bisnis', 'admin_feb@gmail.com', '$2y$12$yl2d5SsYK7Cjy6IEHuuzM.mhij9LcVwuuL5nueC38iRf77I0n/kfm', 'Fakultas'),
(13, 'Admin Universitas', 'admin_univ@umy.ac.id', '$2y$12$RWc4QBO/n6l.QWIdjctLre8GN0vwtv3JcAV2pQVce7Fme3NEh/5/C', 'Universitas'),
(14, 'Admin Teknologi Informasi', 'admin_ti@umy.ac.id', '$2y$12$gHKeiJhW7xOe8D6sbau1w.WS8Y8YBp7FOD5.P/ZgJukXZ.sVMOH9O', 'Prodi'),
(15, 'Admin Ekonomi', 'admin_ekonomi@umy.ac.id', '$2y$12$dTyaSEe5bwfIL0wgAxOoUOUUW08cpc8Jxu.UPMv.kOvVDnl/rGrbW', 'Prodi'),
(16, 'Admin Teknik 2', 'admin_teknik@umy.ac.id', '$2y$12$JiK7AFLpCWHJmgVzoGi.keiLroADErLxwCcC6zda7fxyw6UA0mCK6', 'Fakultas');

-- --------------------------------------------------------

--
-- Table structure for table `users_sub_unit`
--

CREATE TABLE `users_sub_unit` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `sub_unit_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_sub_unit`
--

INSERT INTO `users_sub_unit` (`id`, `user_id`, `sub_unit_id`) VALUES
(20, 10, 1),
(21, 10, 2),
(22, 10, 6),
(23, 11, 7),
(24, 12, 3),
(25, 12, 4),
(26, 12, 5),
(27, 12, 7),
(28, 13, 1),
(29, 13, 2),
(31, 13, 3),
(32, 13, 4),
(33, 13, 5),
(30, 13, 6),
(34, 13, 7),
(36, 13, 8),
(35, 14, 1),
(37, 15, 3),
(38, 16, 1),
(39, 16, 2),
(40, 16, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akreditasi`
--
ALTER TABLE `akreditasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodi_id` (`sub_unit_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail`
--
ALTER TABLE `detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `substandar_id` (`substandar_id`);

--
-- Indexes for table `detail_item`
--
ALTER TABLE `detail_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_id` (`detail_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `standar`
--
ALTER TABLE `standar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `akreditasi_id` (`akreditasi_id`);

--
-- Indexes for table `substandar`
--
ALTER TABLE `substandar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `standar_id` (`standar_id`);

--
-- Indexes for table `sub_units`
--
ALTER TABLE `sub_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fakultas_id` (`unit_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_sub_unit`
--
ALTER TABLE `users_sub_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`sub_unit_id`),
  ADD KEY `prodi_id` (`sub_unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akreditasi`
--
ALTER TABLE `akreditasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `detail`
--
ALTER TABLE `detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `detail_item`
--
ALTER TABLE `detail_item`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `standar`
--
ALTER TABLE `standar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `substandar`
--
ALTER TABLE `substandar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `sub_units`
--
ALTER TABLE `sub_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users_sub_unit`
--
ALTER TABLE `users_sub_unit`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akreditasi`
--
ALTER TABLE `akreditasi`
  ADD CONSTRAINT `akreditasi_ibfk_1` FOREIGN KEY (`sub_unit_id`) REFERENCES `sub_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail`
--
ALTER TABLE `detail`
  ADD CONSTRAINT `detail_ibfk_1` FOREIGN KEY (`substandar_id`) REFERENCES `substandar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_item`
--
ALTER TABLE `detail_item`
  ADD CONSTRAINT `detail_item_ibfk_1` FOREIGN KEY (`detail_id`) REFERENCES `detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `standar`
--
ALTER TABLE `standar`
  ADD CONSTRAINT `standar_ibfk_1` FOREIGN KEY (`akreditasi_id`) REFERENCES `akreditasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `substandar`
--
ALTER TABLE `substandar`
  ADD CONSTRAINT `substandar_ibfk_1` FOREIGN KEY (`standar_id`) REFERENCES `standar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_units`
--
ALTER TABLE `sub_units`
  ADD CONSTRAINT `sub_units_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_sub_unit`
--
ALTER TABLE `users_sub_unit`
  ADD CONSTRAINT `users_sub_unit_ibfk_1` FOREIGN KEY (`sub_unit_id`) REFERENCES `sub_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_sub_unit_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

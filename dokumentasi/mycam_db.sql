-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2026 at 08:02 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mycam_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `nama_alat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_sewa` decimal(8,2) NOT NULL,
  `stok` int NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id`, `kategori_id`, `nama_alat`, `harga_sewa`, `stok`, `deskripsi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 1, 'Canon C50', 50000.00, 5, 'Canon C50', 'gambar-alat/y0KTQyvmVYMdVSNtfV8EfhMZEY3hXgxF6547anse.jpg', '2026-11-18 00:48:38', '2026-11-18 00:48:38'),
(2, 1, 'DG Cam', 45000.00, 15, 'DG Cam', 'gambar-alat/FTTXchoU1AK8qxq6rBKLA9Jty1gFK7wHIbEn62cu.jpg', '2026-11-18 00:49:10', '2026-11-18 00:49:10'),
(3, 2, 'Drone', 80000.00, 7, 'Drone', 'gambar-alat/vSQHpYR6pPZb5kSVohgBKtOXRSPnEVUBmduOEaXJ.jpg', '2026-11-18 00:49:31', '2026-11-18 00:49:31'),
(4, 3, 'Gimbal', 35000.00, 15, 'Stabilizer', 'gambar-alat/ub5Xw2ZoltOMlIfxJ3YGccN251p3wL6srB3YE0hP.jpg', '2026-11-18 00:50:03', '2026-11-18 00:50:03'),
(5, 3, 'Mic', 30000.00, 20, 'Mic', 'gambar-alat/zfkjoLTiu3wish1yTElJdEWESTImPbvFVZYbe2HI.jpg', '2026-11-18 00:50:29', '2026-11-18 00:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjamen`
--

CREATE TABLE `detail_peminjamen` (
  `id` bigint UNSIGNED NOT NULL,
  `peminjaman_id` bigint UNSIGNED NOT NULL,
  `alat_id` bigint UNSIGNED NOT NULL,
  `lama_pinjam` int NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('Menunggu','Diaprove','Dipinjam','Dikembalikan','Dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_peminjamen`
--

INSERT INTO `detail_peminjamen` (`id`, `peminjaman_id`, `alat_id`, `lama_pinjam`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 2, 70000.00, 'Dikembalikan', '2026-11-18 00:51:23', '2026-11-18 00:51:56'),
(2, 2, 3, 2, 160000.00, 'Diaprove', '2026-11-18 00:52:42', '2026-11-18 00:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Kamera', 'Kamera', '2026-11-18 00:46:46', '2026-11-18 00:46:46'),
(2, 'Drone', 'Drone', '2026-11-18 00:47:39', '2026-11-18 00:47:39'),
(3, 'Alat Pendukung', 'Alat Pendukung', '2026-11-18 00:47:59', '2026-11-18 00:47:59');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `aksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `user_id`, `aksi`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Login', 'Login Berhasil Selesai', '2026-11-18 00:46:36', '2026-11-18 00:46:36'),
(2, 1, 'Tambah Kategori', 'Tambah Kategori Berhasil', '2026-11-18 00:46:46', '2026-11-18 00:46:46'),
(3, 1, 'Tambah Kategori', 'Tambah Kategori Berhasil', '2026-11-18 00:47:39', '2026-11-18 00:47:39'),
(4, 1, 'Tambah Kategori', 'Tambah Kategori Berhasil', '2026-11-18 00:47:59', '2026-11-18 00:47:59'),
(5, 1, 'Tambah Alat', 'Tambah Alat Berhasil', '2026-11-18 00:48:38', '2026-11-18 00:48:38'),
(6, 1, 'Tambah Alat', 'Tambah Alat Berhasil', '2026-11-18 00:49:10', '2026-11-18 00:49:10'),
(7, 1, 'Tambah Alat', 'Tambah Alat Berhasil', '2026-11-18 00:49:31', '2026-11-18 00:49:31'),
(8, 1, 'Tambah Alat', 'Tambah Alat Berhasil', '2026-11-18 00:50:03', '2026-11-18 00:50:03'),
(9, 1, 'Tambah Alat', 'Tambah Alat Berhasil', '2026-11-18 00:50:29', '2026-11-18 00:50:29'),
(10, 1, 'Logout', 'Logout Berhasil Selesai', '2026-11-18 00:50:34', '2026-11-18 00:50:34'),
(11, 3, 'Login', 'Login Berhasil Selesai', '2026-11-18 00:50:43', '2026-11-18 00:50:43'),
(12, 3, 'Mengajukan Peminjaman', 'Peminjaman Berhasil', '2026-11-18 00:51:23', '2026-11-18 00:51:23'),
(13, 2, 'Login', 'Login Berhasil Selesai', '2026-11-18 00:51:38', '2026-11-18 00:51:38'),
(14, 2, 'Update Aprove', 'Update Aprove Berhasil Selesai Kode: PJM-20261118075123', '2026-11-18 00:51:43', '2026-11-18 00:51:43'),
(15, 2, 'Update Pinjam', 'Update Pinjam Berhasil Selesai Kode: PJM-20261118075123', '2026-11-18 00:51:53', '2026-11-18 00:51:53'),
(16, 2, 'Update Kembalikan', 'Update Kembalikan Berhasil Selesai Kode: PJM-20261118075123', '2026-11-18 00:51:56', '2026-11-18 00:51:56'),
(17, 3, 'Logout', 'Logout Berhasil Selesai', '2026-11-18 00:52:01', '2026-11-18 00:52:01'),
(18, 4, 'Register', 'Register Berhasil', '2026-11-18 00:52:34', '2026-11-18 00:52:34'),
(19, 4, 'Mengajukan Peminjaman', 'Peminjaman Berhasil', '2026-11-18 00:52:42', '2026-11-18 00:52:42'),
(20, 2, 'Update Aprove', 'Update Aprove Berhasil Selesai Kode: PJM-20261118075242', '2026-11-18 00:52:58', '2026-11-18 00:52:58'),
(21, 2, 'Logout', 'Logout Berhasil Selesai', '2026-11-18 01:01:15', '2026-11-18 01:01:15'),
(22, 1, 'Login', 'Login Berhasil Selesai', '2026-11-18 01:01:43', '2026-11-18 01:01:43');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_16_020727_create_pelanggans_table', 1),
(5, '2026_11_16_020735_create_kategoris_table', 1),
(6, '2026_11_16_020740_create_alats_table', 1),
(7, '2026_11_16_020759_create_peminjamen_table', 1),
(8, '2026_11_16_020809_create_detail_peminjamen_table', 1),
(9, '2026_11_16_020819_create_pengembalians_table', 1),
(10, '2026_11_17_023135_create_log_aktivitas_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `user_id`, `jenis_kelamin`, `no_hp`, `alamat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'Laki-laki', '08123456', 'Karangaanyar', '2026-11-18 00:52:34', '2026-11-18 00:52:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `status` enum('Menunggu','Diaprove','Dipinjam','Dikembalikan','Dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `subtotal` decimal(8,2) DEFAULT NULL,
  `nama_bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `kode`, `user_id`, `tgl_pinjam`, `tgl_kembali`, `status`, `subtotal`, `nama_bukti`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PJM-20261118075123', 3, '2026-11-18', '2026-11-20', 'Dikembalikan', 70000.00, 'Ktp Pelanggan', '2026-11-18 00:51:23', '2026-11-18 00:51:56', NULL),
(2, 'PJM-20261118075242', 4, '2026-11-18', '2026-11-20', 'Diaprove', 160000.00, NULL, '2026-11-18 00:52:42', '2026-11-18 00:52:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id` bigint UNSIGNED NOT NULL,
  `peminjaman_id` bigint UNSIGNED NOT NULL,
  `tgl_dikembalikan` date NOT NULL,
  `terlambat` int NOT NULL DEFAULT '0',
  `denda` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`id`, `peminjaman_id`, `tgl_dikembalikan`, `terlambat`, `denda`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-11-18', 0, 0.00, '2026-11-18 00:51:56', '2026-11-18 00:51:56');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Icv1DHdCXEEqpZYMWAHRN39vFLjOSjn0LmmP6pHy', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJkQjZTNHhQU3ZIUTFoc0ZXaWN2cG1kaXlralZBd1ByYU1VM1VFb1lkIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3Nob3dcLzMiLCJyb3V0ZSI6InNob3cifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjQsInBhc3N3b3JkX2hhc2hfd2ViIjoiNjJiYzYzNmQ5ZDg2NmY1NDdiM2M0MTllNGVjODQxYjk4NzBlMmY3NDc1NGI2NTg4NzBkMmE3MDQ1MjU4ODk3YiJ9', 1794988519),
('L0EoOwgOWTemZeiuAFgvJ15jEfmYZLjwaEELHxvR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'eyJfdG9rZW4iOiI1ejMxQkNON09RcnBXRzlGSGdXMFlKWVM4d05DTmRxNGJnQXR2ZlpGIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3BlbWluamFtYW5cL3BldHVnYXNcL2FsbCIsInJvdXRlIjoicGVtaW5qYW1hbi5hbGwifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiMWY0OWZiOTIwMTFiNmRmN2QwMmE5ZWU4ODc0OWY1YTE4OWI4ODI3OTg1YmQ2YmNiZTZjNWZlZGFjZTllOGVmZSJ9', 1794988905);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Petugas','Pelanggan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pelanggan',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'uroo', 'uroo@gmail.com', NULL, '$2y$12$i4ZSyg12coS2BUveh.bZmui/OCXCA.4piUavVsNp.jy1Db7hqY1ZS', 'Admin', NULL, '2026-11-18 00:46:29', '2026-11-18 00:46:29', NULL),
(2, 'petugas', 'petugas@gmail.com', NULL, '$2y$12$IigPAuHVO4euJ.Xs/79L8.pPG3VkDZV8AcN00durZCr/r8SIbszu.', 'Petugas', NULL, '2026-11-18 00:46:29', '2026-11-18 00:46:29', NULL),
(3, 'pelanggan', 'pelanggan@gmail.com', NULL, '$2y$12$VtOo5AGzVoikoaYXnHxjAOeHkOPyOcsJN3yVSVDES5rTAQPDqD7GO', 'Pelanggan', NULL, '2026-11-18 00:46:29', '2026-11-18 00:46:29', NULL),
(4, 'Dimas', 'dimas@gmail.com', NULL, '$2y$12$kWkW5rPYxu/zz8QVtg/fMOZoHkvhmWSIrTODcaNYisWC9ME.QR19i', 'Pelanggan', NULL, '2026-11-18 00:52:34', '2026-11-18 00:52:34', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alat_nama_alat_unique` (`nama_alat`),
  ADD KEY `alat_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `detail_peminjamen`
--
ALTER TABLE `detail_peminjamen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_peminjamen_peminjaman_id_foreign` (`peminjaman_id`),
  ADD KEY `detail_peminjamen_alat_id_foreign` (`alat_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_nama_kategori_unique` (`nama_kategori`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_aktivitas_user_id_foreign` (`user_id`);

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
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_user_id_foreign` (`user_id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `peminjaman_kode_unique` (`kode`),
  ADD KEY `peminjaman_user_id_foreign` (`user_id`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengembalian_peminjaman_id_foreign` (`peminjaman_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_peminjamen`
--
ALTER TABLE `detail_peminjamen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alat`
--
ALTER TABLE `alat`
  ADD CONSTRAINT `alat_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `detail_peminjamen`
--
ALTER TABLE `detail_peminjamen`
  ADD CONSTRAINT `detail_peminjamen_alat_id_foreign` FOREIGN KEY (`alat_id`) REFERENCES `alat` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `detail_peminjamen_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE RESTRICT;

-- ========================================================
-- UKK RPL 2026/2027: STORED PROCEDURES, FUNCTIONS & TRIGGERS
-- Standard Kebutuhan Soal UKK Langkah Kerja 6
-- ========================================================

DELIMITER $$

--
-- 1. FUNCTION: Hitung Total Biaya Sewa (Harga * Durasi Hari)
--
DROP FUNCTION IF EXISTS `fn_hitung_total_sewa`$$
CREATE FUNCTION `fn_hitung_total_sewa`(
    `p_harga` DECIMAL(12,2),
    `p_lama` INT
) RETURNS DECIMAL(12,2)
DETERMINISTIC
BEGIN
    DECLARE v_total DECIMAL(12,2);
    IF p_lama <= 0 THEN
        SET p_lama = 1;
    END IF;
    SET v_total = p_harga * p_lama;
    RETURN v_total;
END$$

--
-- 2. FUNCTION: Hitung Denda Keterlambatan (Rp 50.000 / hari telat)
--
DROP FUNCTION IF EXISTS `fn_hitung_denda`$$
CREATE FUNCTION `fn_hitung_denda`(
    `p_tgl_kembali` DATE,
    `p_tgl_dikembalikan` DATE
) RETURNS DECIMAL(12,2)
DETERMINISTIC
BEGIN
    DECLARE v_hari_telat INT;
    DECLARE v_denda DECIMAL(12,2);
    SET v_hari_telat = DATEDIFF(p_tgl_dikembalikan, p_tgl_kembali);
    IF v_hari_telat > 0 THEN
        SET v_denda = v_hari_telat * 50000.00;
    ELSE
        SET v_denda = 0.00;
    END IF;
    RETURN v_denda;
END$$

--
-- 3. STORED PROCEDURE: Persetujuan Peminjaman dengan Syarat Jaminan & Transaksi (COMMIT & ROLLBACK)
--
DROP PROCEDURE IF EXISTS `sp_approve_peminjaman`$$
CREATE PROCEDURE `sp_approve_peminjaman`(
    IN `p_peminjaman_id` BIGINT,
    IN `p_syarat` VARCHAR(255),
    IN `p_petugas_id` BIGINT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    -- Update status peminjaman dan simpan syarat jaminan / pembayaran
    UPDATE `peminjaman`
    SET `status` = 'Diaprove',
        `nama_bukti` = p_syarat,
        `updated_at` = NOW()
    WHERE `id` = p_peminjaman_id;

    -- Update status detail peminjaman
    UPDATE `detail_peminjamen`
    SET `status` = 'Diaprove',
        `updated_at` = NOW()
    WHERE `peminjaman_id` = p_peminjaman_id;

    -- Catat log aktivitas petugas
    INSERT INTO `log_aktivitas` (`user_id`, `aksi`, `keterangan`, `created_at`, `updated_at`)
    VALUES (p_petugas_id, 'Approve Peminjaman (SP)', CONCAT('Persetujuan peminjaman ID #', p_peminjaman_id, ' dengan syarat: ', p_syarat), NOW(), NOW());

    COMMIT;
END$$

--
-- 4. STORED PROCEDURE: Pengembalian Alat & Perhitungan Denda Otomatis (COMMIT & ROLLBACK)
--
DROP PROCEDURE IF EXISTS `sp_pengembalian_alat`$$
CREATE PROCEDURE `sp_pengembalian_alat`(
    IN `p_peminjaman_id` BIGINT,
    IN `p_petugas_id` BIGINT
)
BEGIN
    DECLARE v_tgl_kembali DATE;
    DECLARE v_hari_telat INT DEFAULT 0;
    DECLARE v_denda DECIMAL(12,2) DEFAULT 0.00;
    DECLARE v_tgl_sekarang DATE;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    SET v_tgl_sekarang = CURDATE();

    -- Ambil tanggal kembali dari peminjaman
    SELECT `tgl_kembali` INTO v_tgl_kembali FROM `peminjaman` WHERE `id` = p_peminjaman_id LIMIT 1;

    -- Hitung denda jika terlambat
    IF v_tgl_sekarang > v_tgl_kembali THEN
        SET v_hari_telat = DATEDIFF(v_tgl_sekarang, v_tgl_kembali);
        SET v_denda = v_hari_telat * 50000.00;
    END IF;

    -- Insert ke tabel pengembalian
    INSERT INTO `pengembalian` (`peminjaman_id`, `tgl_dikembalikan`, `terlambat`, `denda`, `created_at`, `updated_at`)
    VALUES (p_peminjaman_id, v_tgl_sekarang, v_hari_telat, v_denda, NOW(), NOW());

    -- Update status peminjaman menjadi Dikembalikan
    UPDATE `peminjaman`
    SET `status` = 'Dikembalikan',
        `updated_at` = NOW()
    WHERE `id` = p_peminjaman_id;

    -- Update status detail peminjaman
    UPDATE `detail_peminjamen`
    SET `status` = 'Dikembalikan',
        `updated_at` = NOW()
    WHERE `peminjaman_id` = p_peminjaman_id;

    -- Kembalikan stok alat untuk setiap alat dalam peminjaman
    UPDATE `alat` a
    INNER JOIN `detail_peminjamen` dp ON a.id = dp.alat_id
    SET a.stok = a.stok + 1,
        a.updated_at = NOW()
    WHERE dp.peminjaman_id = p_peminjaman_id;

    -- Catat log aktivitas
    INSERT INTO `log_aktivitas` (`user_id`, `aksi`, `keterangan`, `created_at`, `updated_at`)
    VALUES (p_petugas_id, 'Pengembalian Alat (SP)', CONCAT('Pengembalian peminjaman ID #', p_peminjaman_id, ' berhasil diproses. Telat: ', v_hari_telat, ' hari, Denda: Rp ', v_denda), NOW(), NOW());

    COMMIT;
END$$

--
-- 5. TRIGGER: Log otomatis saat data alat diubah
--
DROP TRIGGER IF EXISTS `trg_log_update_alat`$$
CREATE TRIGGER `trg_log_update_alat`
AFTER UPDATE ON `alat`
FOR EACH ROW
BEGIN
    IF OLD.stok <> NEW.stok OR OLD.harga_sewa <> NEW.harga_sewa THEN
        INSERT INTO `log_aktivitas` (`user_id`, `aksi`, `keterangan`, `created_at`, `updated_at`)
        VALUES (1, 'Trigger Update Alat', CONCAT('Trigger sistem: Alat "', NEW.nama_alat, '" stok berubah dari ', OLD.stok, ' menjadi ', NEW.stok), NOW(), NOW());
    END IF;
END$$

DELIMITER ;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251201.40f7317dad
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 03, 2026 at 02:01 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `account_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('tabungan','giro','deposito') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tabungan',
  `balance` bigint NOT NULL DEFAULT '0' COMMENT 'Saldo dalam Rupiah (satuan)',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `status` enum('active','inactive','blocked','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `pin` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'PIN terenkripsi untuk transaksi',
  `opened_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `account_number`, `account_type`, `balance`, `currency`, `status`, `pin`, `opened_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'INB8913410571', 'tabungan', 505828, 'IDR', 'active', '$2y$12$aswzs9HJXImIRwWlexQs3OYz/osZJjWZKG43I1i7xQJwNHfq06DDK', '2026-04-17 00:45:00', '2026-04-17 00:45:00', '2026-05-03 06:47:46'),
(2, 2, 'INB9695636285', 'tabungan', 2684994, 'IDR', 'active', '$2y$12$cm6AMAln40t/s/rDyWTrDet1QioCh8OtyMcmJgrMbRUg8axZMyEwu', '2026-04-17 21:01:52', '2026-04-17 21:01:52', '2026-05-03 06:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint UNSIGNED NOT NULL,
  `bill_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik tagihan mis: PLN, BPJS, PDAM',
  `bill_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama tagihan untuk ditampilkan',
  `category` enum('listrik','air','telepon','internet','bpjs','pajak','pendidikan','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lainnya',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama ikon atau path gambar',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `bill_code`, `bill_name`, `category`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'BILL-U8FPBG', 'PLN', 'listrik', 'fas fa-file-invoice', 1, '2026-04-25 00:40:18', '2026-04-25 00:42:00'),
(3, 'BILL-TYOFPS', 'Air', 'air', 'fas fa-file-invoice', 1, '2026-04-28 00:49:03', '2026-04-28 00:49:03');

-- --------------------------------------------------------

--
-- Table structure for table `bill_payments`
--

CREATE TABLE `bill_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `bill_id` bigint UNSIGNED NOT NULL,
  `customer_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'No pelanggan / ID pelanggan tagihan',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` bigint NOT NULL COMMENT 'Jumlah tagihan dalam Rupiah',
  `admin_fee` bigint NOT NULL DEFAULT '0',
  `period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Periode tagihan mis: 2025-01',
  `status` enum('pending','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_payments`
--

INSERT INTO `bill_payments` (`id`, `transaction_id`, `account_id`, `bill_id`, `customer_number`, `customer_name`, `amount`, `admin_fee`, `period`, `status`, `created_at`, `updated_at`) VALUES
(1, 35, 1, 2, '123213132', NULL, 50000, 0, NULL, 'success', '2026-04-25 00:49:24', '2026-04-25 00:49:24'),
(2, 55, 1, 3, '123213122', NULL, 50000, 0, NULL, 'success', '2026-05-03 06:45:53', '2026-05-03 06:45:53'),
(3, 56, 1, 2, '13212321', NULL, 100000, 0, NULL, 'success', '2026-05-03 06:47:02', '2026-05-03 06:47:02');

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `attempts` tinyint UNSIGNED NOT NULL,
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
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `principal` bigint NOT NULL COMMENT 'Pokok pinjaman dalam Rupiah',
  `interest_rate` decimal(5,2) NOT NULL COMMENT 'Bunga per tahun dalam persen, mis: 12.00',
  `tenor_months` tinyint UNSIGNED NOT NULL COMMENT 'Tenor dalam bulan',
  `monthly_installment` bigint NOT NULL COMMENT 'Cicilan per bulan (flat)',
  `total_debt` bigint NOT NULL COMMENT 'Total hutang = pokok + bunga total',
  `remaining_debt` bigint NOT NULL COMMENT 'Sisa hutang yang belum dibayar',
  `paid_installments` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Jumlah cicilan yang sudah dibayar',
  `status` enum('pending','active','paid_off','overdue','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tujuan pinjaman',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `disbursed_at` date DEFAULT NULL COMMENT 'Tanggal dana dicairkan',
  `due_date` date DEFAULT NULL COMMENT 'Tanggal jatuh tempo cicilan per bulan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `account_id`, `principal`, `interest_rate`, `tenor_months`, `monthly_installment`, `total_debt`, `remaining_debt`, `paid_installments`, `status`, `purpose`, `rejection_reason`, `disbursed_at`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1000000, 12.00, 3, 343334, 1030000, 0, 3, 'paid_off', 'Modal buka toko', NULL, '2026-04-19', '2026-05-19', '2026-04-18 19:49:20', '2026-04-20 05:50:32'),
(2, 2, 1000000, 12.00, 3, 343334, 1030000, 1030000, 0, 'rejected', 'Modal Beli Laptop', 'mang eak', NULL, NULL, '2026-04-18 22:01:00', '2026-04-18 22:15:11'),
(3, 2, 1000000, 12.00, 3, 343334, 1030000, 1030000, 0, 'rejected', 'Beli Rokok', '123', NULL, NULL, '2026-04-18 22:16:17', '2026-04-18 22:17:40'),
(4, 2, 1000000, 12.00, 3, 343334, 1030000, 0, 3, 'paid_off', 'Slot', NULL, '2026-04-20', '2026-07-20', '2026-04-18 22:18:53', '2026-04-26 03:45:14'),
(5, 1, 4900000, 12.00, 3, 1682334, 5047000, 5047000, 0, 'rejected', 'Beli Rokok', 'tolak', NULL, NULL, '2026-04-20 05:50:56', '2026-04-20 05:56:50'),
(6, 1, 1000000, 12.00, 3, 343334, 1030000, 343332, 2, 'active', 'Beli Gas', NULL, '2026-04-26', '2026-07-26', '2026-04-26 03:22:05', '2026-05-03 06:47:46'),
(7, 2, 1200000, 12.00, 3, 412000, 1236000, 1236000, 0, 'rejected', 'Jualan Pempek Modal', 'Ditolak', NULL, NULL, '2026-04-26 03:45:46', '2026-04-26 04:00:13'),
(8, 2, 1000000, 12.00, 6, 176667, 1060000, 1060000, 0, 'active', 'Modal Usaha Cilok', NULL, '2026-04-26', '2026-10-26', '2026-04-26 04:00:42', '2026-04-26 04:01:16');

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED NOT NULL,
  `amount` bigint NOT NULL COMMENT 'Total yang dibayarkan',
  `principal_paid` bigint NOT NULL COMMENT 'Porsi pokok yang dibayar',
  `interest_paid` bigint NOT NULL COMMENT 'Porsi bunga yang dibayar',
  `remaining_after` bigint NOT NULL COMMENT 'Sisa hutang setelah pembayaran ini',
  `installment_number` tinyint UNSIGNED NOT NULL COMMENT 'Angsuran ke-berapa',
  `paid_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_payments`
--

INSERT INTO `loan_payments` (`id`, `transaction_id`, `loan_id`, `amount`, `principal_paid`, `interest_paid`, `remaining_after`, `installment_number`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 343334, 333334, 10000, 686666, 1, '2026-04-18 21:55:23', '2026-04-18 21:55:23', '2026-04-18 21:55:23'),
(2, 12, 1, 343334, 333334, 10000, 343332, 2, '2026-04-18 21:56:17', '2026-04-18 21:56:17', '2026-04-18 21:56:17'),
(3, 31, 1, 343334, 333334, 10000, 0, 3, '2026-04-20 05:50:32', '2026-04-20 05:50:32', '2026-04-20 05:50:32'),
(4, 37, 4, 343334, 333334, 10000, 686666, 1, '2026-04-26 03:44:54', '2026-04-26 03:44:54', '2026-04-26 03:44:54'),
(5, 38, 4, 343334, 333334, 10000, 343332, 2, '2026-04-26 03:45:03', '2026-04-26 03:45:03', '2026-04-26 03:45:03'),
(6, 39, 4, 343334, 333334, 10000, 0, 3, '2026-04-26 03:45:14', '2026-04-26 03:45:14', '2026-04-26 03:45:14'),
(7, 46, 6, 343334, 333334, 10000, 686666, 1, '2026-04-28 00:50:13', '2026-04-28 00:50:13', '2026-04-28 00:50:13'),
(8, 57, 6, 343334, 333334, 10000, 343332, 2, '2026-05-03 06:47:46', '2026-05-03 06:47:46', '2026-05-03 06:47:46');

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
(4, '2026_04_14_072049_accounts', 1),
(5, '2026_04_14_072518_transactions', 1),
(6, '2026_04_14_083728_transfers', 1),
(7, '2026_04_14_083740_top_ups', 1),
(8, '2026_04_14_083810_bills', 1),
(9, '2026_04_14_083830_loans', 1),
(10, '2026_04_14_083845_savings_books', 1),
(11, '2026_04_16_145402_profiles', 1);

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
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `nik`, `phone`, `address`, `city`, `created_at`, `updated_at`) VALUES
(1, 1, '1234567890987654', '0895632651921', 'Jl.Sukakarya', 'Palembang', '2026-04-17 00:45:00', '2026-04-17 00:45:00'),
(2, 2, '1234567876543212', '085161676029', 'Jl.Macan Lindungan', 'Palembang', '2026-04-17 21:01:52', '2026-04-17 21:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `savings_books`
--

CREATE TABLE `savings_books` (
  `id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `book_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor buku tabungan fisik',
  `issued_at` date NOT NULL COMMENT 'Tanggal buku diterbitkan',
  `last_printed` timestamp NULL DEFAULT NULL COMMENT 'Terakhir kali cetak mutasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings_books`
--

INSERT INTO `savings_books` (`id`, `account_id`, `book_number`, `issued_at`, `last_printed`, `created_at`, `updated_at`) VALUES
(1, 2, 'SB0079911', '2026-04-26', '2026-04-26 04:46:20', '2026-04-26 04:45:52', '2026-04-26 04:46:20'),
(2, 1, 'SB7546917', '2026-04-28', '2026-05-02 07:19:58', '2026-04-28 00:08:00', '2026-05-02 07:19:58');

-- --------------------------------------------------------

--
-- Table structure for table `savings_book_entries`
--

CREATE TABLE `savings_book_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `savings_book_id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `entry_date` date NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit` bigint NOT NULL DEFAULT '0' COMMENT 'Uang masuk ke rekening',
  `credit` bigint NOT NULL DEFAULT '0' COMMENT 'Uang keluar dari rekening',
  `balance` bigint NOT NULL COMMENT 'Saldo setelah transaksi ini',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings_book_entries`
--

INSERT INTO `savings_book_entries` (`id`, `savings_book_id`, `transaction_id`, `entry_date`, `description`, `debit`, `credit`, `balance`, `created_at`, `updated_at`) VALUES
(1, 1, 9, '2026-04-18', 'Transfer masuk dari INB8913410571', 10000, 0, 10000, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(2, 1, 27, '2026-04-20', 'Transfer masuk dari INB8913410571', 10000, 0, 20000, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(3, 1, 29, '2026-04-20', 'Transfer masuk dari INB8913410571', 99999, 0, 119999, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(4, 1, 32, '2026-04-20', 'Pencairan Pinjaman ID #4', 1000000, 0, 1119999, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(5, 1, 37, '2026-04-26', 'Pembayaran Cicilan INB Ke-1', 0, 343334, 776665, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(6, 1, 38, '2026-04-26', 'Pembayaran Cicilan INB Ke-2', 0, 343334, 433331, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(7, 1, 39, '2026-04-26', 'Pembayaran Cicilan INB Ke-3', 0, 343334, 89997, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(8, 1, 40, '2026-04-26', 'Pencairan Pinjaman ID #8', 1000000, 0, 1089997, '2026-04-26 04:46:20', '2026-04-26 04:46:20'),
(9, 2, 2, '2026-04-18', 'Top up via mobile_banking', 10000, 0, 10000, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(10, 2, 3, '2026-04-18', 'Top up via mobile_banking', 49999, 0, 59999, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(11, 2, 8, '2026-04-18', 'Transfer ke INB9695636285 (Buat Jajan)', 0, 12500, 47499, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(12, 2, 10, '2026-04-19', 'Pencairan pinjaman INB - Modal buka toko', 1000000, 0, 1047499, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(13, 2, 11, '2026-04-19', 'Pembayaran Cicilan INB Ke-1', 0, 343334, 704165, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(14, 2, 12, '2026-04-19', 'Pembayaran Cicilan INB Ke-2', 0, 343334, 360831, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(15, 2, 26, '2026-04-20', 'Transfer ke INB9695636285 (Buat Jajan)', 0, 12500, 348331, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(16, 2, 28, '2026-04-20', 'Transfer ke INB9695636285 (Buat Jajan)', 0, 102499, 245832, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(17, 2, 30, '2026-04-20', 'Top up via mobile_banking', 100000, 0, 345832, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(18, 2, 31, '2026-04-20', 'Pembayaran Cicilan INB Ke-3', 0, 343334, 2498, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(19, 2, 33, '2026-04-25', 'Top up via minimarket', 1000000, 0, 1002498, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(20, 2, 34, '2026-04-25', 'Top up via minimarket', 1000000, 0, 2002498, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(21, 2, 35, '2026-04-25', 'Pembayaran Tagihan PLN', 0, 50000, 1852498, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(22, 2, 36, '2026-04-26', 'Pencairan Pinjaman ID #6', 1000000, 0, 2852498, '2026-04-28 00:08:03', '2026-04-28 00:08:03'),
(23, 2, 41, '2026-04-28', 'Transfer ke INB9695636285 (Buat Jajan)', 0, 102500, 2749998, '2026-04-28 00:46:06', '2026-04-28 00:46:06'),
(24, 2, 43, '2026-04-28', 'Top up via mobile_banking', 99995, 0, 2849993, '2026-04-28 00:46:06', '2026-04-28 00:46:06'),
(25, 2, 44, '2026-04-28', 'Transfer ke INB9695636285 ()', 0, 1002497, 1847496, '2026-04-28 00:46:06', '2026-04-28 00:46:06'),
(26, 2, 46, '2026-04-28', 'Pembayaran Cicilan INB Ke-1', 0, 343334, 1504162, '2026-05-02 07:19:58', '2026-05-02 07:19:58'),
(27, 2, 47, '2026-04-28', 'Transfer ke INB9695636285 (Tes)', 0, 12500, 1491662, '2026-05-02 07:19:58', '2026-05-02 07:19:58'),
(28, 2, 50, '2026-04-28', 'Transfer masuk dari INB9695636285', 10000, 0, 1501662, '2026-05-02 07:19:58', '2026-05-02 07:19:58'),
(29, 2, 52, '2026-04-28', 'Transfer masuk dari INB9695636285', 500000, 0, 2001662, '2026-05-02 07:19:58', '2026-05-02 07:19:58');

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
('JHG88OjWy1XRmGEX9IuBDzMbY1LtZ4EVh1a4lOII', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJHb0x5TUVXcVZmcW9OTWMwZXFGUkRnZDdTZ3llaTc5a3lYVW1YRTI5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvYmlsbHMiLCJyb3V0ZSI6ImFkbWluLmJpbGxzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjN9', 1777816788),
('YjLYVETewSTY4LuYRsaMOzSoEZZ2n0q2zaIpF3mF', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJraDVyREZyVzNYaHlacGkxRmhqeGtaR1ZXQ2VnOGhicVlWSEU0UkFyIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1777816070);

-- --------------------------------------------------------

--
-- Table structure for table `top_ups`
--

CREATE TABLE `top_ups` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `amount` bigint NOT NULL COMMENT 'Jumlah top up dalam Rupiah',
  `channel` enum('transfer_bank','atm','minimarket','mobile_banking','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer_bank',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Referensi dari channel pembayaran',
  `status` enum('pending','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `top_ups`
--

INSERT INTO `top_ups` (`id`, `transaction_id`, `account_id`, `amount`, `channel`, `reference`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 10000, 'mobile_banking', 'REF123', 'success', '2026-04-17 20:46:39', '2026-04-17 20:46:39'),
(2, 3, 1, 49999, 'mobile_banking', 'REF124', 'success', '2026-04-17 21:18:43', '2026-04-17 21:18:43'),
(3, 30, 1, 100000, 'mobile_banking', NULL, 'success', '2026-04-20 00:24:42', '2026-04-20 00:24:42'),
(4, 33, 1, 1000000, 'minimarket', 'JUE12', 'success', '2026-04-25 00:28:12', '2026-04-25 00:28:12'),
(5, 34, 1, 1000000, 'minimarket', 'JUE12', 'success', '2026-04-25 00:28:13', '2026-04-25 00:28:13'),
(6, 43, 1, 99995, 'mobile_banking', 'REF123', 'success', '2026-04-28 00:44:46', '2026-04-28 00:44:46');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `related_account_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('transfer_in','transfer_out','top_up','withdrawal','bill_payment','va_payment','qr_payment','loan_disbursement','loan_payment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` bigint NOT NULL COMMENT 'Jumlah transaksi dalam Rupiah',
  `balance_before` bigint NOT NULL COMMENT 'Saldo sebelum transaksi',
  `balance_after` bigint NOT NULL COMMENT 'Saldo setelah transaksi',
  `reference_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','success','failed','reversed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `reference_number`, `account_id`, `related_account_id`, `type`, `amount`, `balance_before`, `balance_after`, `reference_code`, `description`, `status`, `ip_address`, `created_at`, `updated_at`) VALUES
(2, '', 1, NULL, 'top_up', 10000, 0, 10000, NULL, 'Top up via mobile_banking', 'success', NULL, '2026-04-17 20:46:39', '2026-04-17 20:46:39'),
(3, '', 1, NULL, 'top_up', 49999, 10000, 59999, NULL, 'Top up via mobile_banking', 'success', NULL, '2026-04-17 21:18:43', '2026-04-17 21:18:43'),
(8, '', 1, NULL, 'transfer_out', 12500, 59999, 47499, NULL, 'Transfer ke INB9695636285 (Buat Jajan)', 'success', NULL, '2026-04-17 21:21:43', '2026-04-17 21:21:43'),
(9, '', 2, NULL, 'transfer_in', 10000, 0, 10000, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-04-17 21:21:43', '2026-04-17 21:21:43'),
(10, 'LNS17D98035366', 1, NULL, 'loan_disbursement', 1000000, 47499, 1047499, NULL, 'Pencairan pinjaman INB - Modal buka toko', 'success', NULL, '2026-04-18 20:52:29', '2026-04-18 20:52:29'),
(11, 'CIC03BD2301621', 1, NULL, 'loan_payment', 343334, 1047499, 704165, NULL, 'Pembayaran Cicilan INB Ke-1', 'success', NULL, '2026-04-18 21:55:23', '2026-04-18 21:55:23'),
(12, 'CIC07199E74894', 1, NULL, 'loan_payment', 343334, 704165, 360831, NULL, 'Pembayaran Cicilan INB Ke-2', 'success', NULL, '2026-04-18 21:56:17', '2026-04-18 21:56:17'),
(26, 'TRF38BEF567', 1, NULL, 'transfer_out', 12500, 360831, 348331, NULL, 'Transfer ke INB9695636285 (Buat Jajan)', 'success', NULL, '2026-04-20 00:19:39', '2026-04-20 00:19:39'),
(27, 'TRF38BEF567', 2, NULL, 'transfer_in', 10000, 10000, 20000, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-04-20 00:19:39', '2026-04-20 00:19:39'),
(28, 'TRF3D009155', 1, NULL, 'transfer_out', 102499, 348331, 245832, NULL, 'Transfer ke INB9695636285 (Buat Jajan)', 'success', NULL, '2026-04-20 00:20:48', '2026-04-20 00:20:48'),
(29, 'TRF3D009155', 2, NULL, 'transfer_in', 99999, 20000, 119999, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-04-20 00:20:48', '2026-04-20 00:20:48'),
(30, 'TOP4BA9B0DC', 1, NULL, 'top_up', 100000, 245832, 345832, NULL, 'Top up via mobile_banking', 'success', NULL, '2026-04-20 00:24:42', '2026-04-20 00:24:42'),
(31, 'CIC118520B7926', 1, NULL, 'loan_payment', 343334, 345832, 2498, NULL, 'Pembayaran Cicilan INB Ke-3', 'success', NULL, '2026-04-20 05:50:32', '2026-04-20 05:50:32'),
(32, 'LOAN2F088A7A', 2, NULL, 'loan_disbursement', 1000000, 119999, 1119999, NULL, 'Pencairan Pinjaman ID #4', 'success', NULL, '2026-04-20 05:58:24', '2026-04-20 05:58:24'),
(33, 'JUE12', 1, NULL, 'top_up', 1000000, 2498, 1002498, NULL, 'Top up via minimarket', 'success', NULL, '2026-04-25 00:28:12', '2026-04-25 00:28:12'),
(34, 'JUE12', 1, NULL, 'top_up', 1000000, 1002498, 2002498, NULL, 'Top up via minimarket', 'success', NULL, '2026-04-25 00:28:13', '2026-04-25 00:28:13'),
(35, 'TRX-04ALTQLCN10U', 1, NULL, 'withdrawal', 50000, 1902498, 1852498, NULL, 'Pembayaran Tagihan PLN', 'success', NULL, '2026-04-25 00:49:24', '2026-04-25 00:49:24'),
(36, 'LOAN756BA5FD', 1, NULL, 'loan_disbursement', 1000000, 1852498, 2852498, NULL, 'Pencairan Pinjaman ID #6', 'success', NULL, '2026-04-26 03:22:14', '2026-04-26 03:22:14'),
(37, 'CICCA63AFBC361', 2, NULL, 'loan_payment', 343334, 1119999, 776665, NULL, 'Pembayaran Cicilan INB Ke-1', 'success', NULL, '2026-04-26 03:44:54', '2026-04-26 03:44:54'),
(38, 'CICCAF760DA975', 2, NULL, 'loan_payment', 343334, 776665, 433331, NULL, 'Pembayaran Cicilan INB Ke-2', 'success', NULL, '2026-04-26 03:45:03', '2026-04-26 03:45:03'),
(39, 'CICCBA0F15F728', 2, NULL, 'loan_payment', 343334, 433331, 89997, NULL, 'Pembayaran Cicilan INB Ke-3', 'success', NULL, '2026-04-26 03:45:14', '2026-04-26 03:45:14'),
(40, 'LOAN07CADE8B', 2, NULL, 'loan_disbursement', 1000000, 89997, 1089997, NULL, 'Pencairan Pinjaman ID #8', 'success', NULL, '2026-04-26 04:01:16', '2026-04-26 04:01:16'),
(41, 'TRF541BEC78', 1, NULL, 'transfer_out', 102500, 2852498, 2749998, NULL, 'Transfer ke INB9695636285 (Buat Jajan)', 'success', NULL, '2026-04-28 00:44:01', '2026-04-28 00:44:01'),
(42, 'TRF541BEC78', 2, NULL, 'transfer_in', 100000, 1089997, 1189997, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-04-28 00:44:01', '2026-04-28 00:44:01'),
(43, 'REF123', 1, NULL, 'top_up', 99995, 2749998, 2849993, NULL, 'Top up via mobile_banking', 'success', NULL, '2026-04-28 00:44:46', '2026-04-28 00:44:46'),
(44, 'TRF59368C2B', 1, NULL, 'transfer_out', 1002497, 2849993, 1847496, NULL, 'Transfer ke INB9695636285 ()', 'success', NULL, '2026-04-28 00:45:23', '2026-04-28 00:45:23'),
(45, 'TRF59368C2B', 2, NULL, 'transfer_in', 999997, 1189997, 2189994, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-04-28 00:45:23', '2026-04-28 00:45:23'),
(46, 'CIC6B5AF306932', 1, NULL, 'loan_payment', 343334, 1847496, 1504162, NULL, 'Pembayaran Cicilan INB Ke-1', 'success', NULL, '2026-04-28 00:50:13', '2026-04-28 00:50:13'),
(47, 'TRF73C405A4', 1, NULL, 'transfer_out', 12500, 1504162, 1491662, NULL, 'Transfer ke INB9695636285 (Tes)', 'success', NULL, '2026-04-28 00:52:28', '2026-04-28 00:52:28'),
(48, 'TRF73C405A4', 2, NULL, 'transfer_in', 10000, 2189994, 2199994, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-04-28 00:52:28', '2026-04-28 00:52:28'),
(49, 'TRF76BC143F', 2, NULL, 'transfer_out', 12500, 2199994, 2187494, NULL, 'Transfer ke INB8913410571 (Buat Jajan)', 'success', NULL, '2026-04-28 00:53:15', '2026-04-28 00:53:15'),
(50, 'TRF76BC143F', 1, NULL, 'transfer_in', 10000, 1491662, 1501662, NULL, 'Transfer masuk dari INB9695636285', 'success', NULL, '2026-04-28 00:53:15', '2026-04-28 00:53:15'),
(51, 'TRF784D780E', 2, NULL, 'transfer_out', 502500, 2187494, 1684994, NULL, 'Transfer ke INB8913410571 (Tes)', 'success', NULL, '2026-04-28 00:53:40', '2026-04-28 00:53:40'),
(52, 'TRF784D780E', 1, NULL, 'transfer_in', 500000, 1501662, 2001662, NULL, 'Transfer masuk dari INB9695636285', 'success', NULL, '2026-04-28 00:53:40', '2026-04-28 00:53:40'),
(53, 'TRF15E7E8E2', 1, NULL, 'transfer_out', 1002500, 2001662, 999162, NULL, 'Transfer ke INB9695636285 (gege)', 'success', NULL, '2026-05-03 06:45:02', '2026-05-03 06:45:02'),
(54, 'TRF15E7E8E2', 2, NULL, 'transfer_in', 1000000, 1684994, 2684994, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-05-03 06:45:02', '2026-05-03 06:45:02'),
(55, 'TRX-BYKSEGFFVBCU', 1, NULL, 'withdrawal', 50000, 999162, 949162, NULL, 'Pembayaran Tagihan Air', 'success', NULL, '2026-05-03 06:45:53', '2026-05-03 06:45:53'),
(56, 'TRX-HZNOWYZYN5DV', 1, NULL, 'withdrawal', 100000, 949162, 849162, NULL, 'Pembayaran Tagihan PLN', 'success', NULL, '2026-05-03 06:47:02', '2026-05-03 06:47:02'),
(57, 'CIC202B04AC251', 1, NULL, 'loan_payment', 343334, 849162, 505828, NULL, 'Pembayaran Cicilan INB Ke-2', 'success', NULL, '2026-05-03 06:47:46', '2026-05-03 06:47:46');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `from_account_id` bigint UNSIGNED NOT NULL,
  `to_account_id` bigint UNSIGNED NOT NULL,
  `amount` bigint NOT NULL COMMENT 'Jumlah transfer dalam Rupiah',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Berita/catatan transfer',
  `method` enum('internal','bi_fast','realtime','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_fee` bigint NOT NULL DEFAULT '0' COMMENT 'Biaya administrasi transfer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`id`, `transaction_id`, `from_account_id`, `to_account_id`, `amount`, `note`, `method`, `admin_fee`, `created_at`, `updated_at`) VALUES
(1, 8, 1, 2, 10000, 'Buat Jajan', 'bi_fast', 2500, '2026-04-17 21:21:43', '2026-04-17 21:21:43'),
(2, 26, 1, 2, 10000, 'Buat Jajan', 'bi_fast', 2500, '2026-04-20 00:19:39', '2026-04-20 00:19:39'),
(3, 28, 1, 2, 99999, 'Buat Jajan', 'bi_fast', 2500, '2026-04-20 00:20:48', '2026-04-20 00:20:48'),
(4, 41, 1, 2, 100000, 'Buat Jajan', 'bi_fast', 2500, '2026-04-28 00:44:01', '2026-04-28 00:44:01'),
(5, 44, 1, 2, 999997, NULL, 'bi_fast', 2500, '2026-04-28 00:45:23', '2026-04-28 00:45:23'),
(6, 47, 1, 2, 10000, 'Tes', 'bi_fast', 2500, '2026-04-28 00:52:28', '2026-04-28 00:52:28'),
(7, 49, 2, 1, 10000, 'Buat Jajan', 'bi_fast', 2500, '2026-04-28 00:53:15', '2026-04-28 00:53:15'),
(8, 51, 2, 1, 500000, 'Tes', 'bi_fast', 2500, '2026-04-28 00:53:40', '2026-04-28 00:53:40'),
(9, 53, 1, 2, 1000000, 'gege', 'bi_fast', 2500, '2026-05-03 06:45:02', '2026-05-03 06:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `nik`, `address`, `birth_date`, `gender`, `photo`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Iqbal', 'muhammad.iqbal.31d@gmail.com', '2026-04-17 00:58:25', '$2y$12$ugNEgvArVSfI0gZ1BbGoKecMHfAmsK2hMyY4MpmsR6VYw.cu2UXGu', '0895632651921', '1234567890987654', 'Jl.Sukakarya', '2006-12-31', 'male', NULL, 'user', 1, NULL, '2026-04-17 00:45:00', '2026-04-17 00:58:25'),
(2, 'Ariq', 'crocodille.000@gmail.com', '2026-04-17 21:06:54', '$2y$12$qDCtWPCQUnq5llPbDJqay.NWsuLkBn6zUY1FF8wQSvMhwfstkxV2e', '085161676029', '1234567876543212', 'Jl.Macan Lindungan', '2006-12-31', 'male', NULL, 'user', 1, NULL, '2026-04-17 21:01:52', '2026-04-17 21:06:54'),
(3, 'Administrator INB', 'admin@inb.com', '2026-04-18 20:16:48', '$2y$12$rQExfKYlNSEdQByz9jjXeeeXB5xF2L26/RJEiSTgxo5r69bvUsZ72', NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 1, 'criXXmDc0M5Y3kqU7NSXzBvYOm0pY60jwtX31k7OJuDA2bw2ESiwvs3uD5HH', '2026-04-18 20:16:49', '2026-04-18 20:16:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounts_account_number_unique` (`account_number`),
  ADD KEY `accounts_user_id_foreign` (`user_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_bill_code_unique` (`bill_code`);

--
-- Indexes for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_payments_transaction_id_foreign` (`transaction_id`),
  ADD KEY `bill_payments_bill_id_foreign` (`bill_id`),
  ADD KEY `bill_payments_account_id_created_at_index` (`account_id`,`created_at`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_account_id_status_index` (`account_id`,`status`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_payments_transaction_id_foreign` (`transaction_id`),
  ADD KEY `loan_payments_loan_id_paid_at_index` (`loan_id`,`paid_at`);

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
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profiles_nik_unique` (`nik`),
  ADD KEY `profiles_user_id_foreign` (`user_id`),
  ADD KEY `profiles_phone_index` (`phone`),
  ADD KEY `profiles_city_index` (`city`);

--
-- Indexes for table `savings_books`
--
ALTER TABLE `savings_books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `savings_books_account_id_unique` (`account_id`),
  ADD UNIQUE KEY `savings_books_book_number_unique` (`book_number`);

--
-- Indexes for table `savings_book_entries`
--
ALTER TABLE `savings_book_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `savings_book_entries_transaction_id_foreign` (`transaction_id`),
  ADD KEY `savings_book_entries_savings_book_id_entry_date_index` (`savings_book_id`,`entry_date`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `top_ups`
--
ALTER TABLE `top_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `top_ups_transaction_id_foreign` (`transaction_id`),
  ADD KEY `top_ups_account_id_foreign` (`account_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_reference_code_unique` (`reference_code`),
  ADD KEY `transactions_related_account_id_foreign` (`related_account_id`),
  ADD KEY `transactions_account_id_created_at_index` (`account_id`,`created_at`),
  ADD KEY `transactions_reference_code_index` (`reference_code`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfers_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transfers_from_account_id_created_at_index` (`from_account_id`,`created_at`),
  ADD KEY `transfers_to_account_id_created_at_index` (`to_account_id`,`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bill_payments`
--
ALTER TABLE `bill_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `savings_books`
--
ALTER TABLE `savings_books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `savings_book_entries`
--
ALTER TABLE `savings_book_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `top_ups`
--
ALTER TABLE `top_ups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD CONSTRAINT `bill_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_payments_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `savings_books`
--
ALTER TABLE `savings_books`
  ADD CONSTRAINT `savings_books_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `savings_book_entries`
--
ALTER TABLE `savings_book_entries`
  ADD CONSTRAINT `savings_book_entries_savings_book_id_foreign` FOREIGN KEY (`savings_book_id`) REFERENCES `savings_books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `savings_book_entries_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `top_ups`
--
ALTER TABLE `top_ups`
  ADD CONSTRAINT `top_ups_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `top_ups_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_related_account_id_foreign` FOREIGN KEY (`related_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfers_to_account_id_foreign` FOREIGN KEY (`to_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfers_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

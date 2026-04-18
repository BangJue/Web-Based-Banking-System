-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251201.40f7317dad
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 18, 2026 at 04:25 AM
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
(1, 1, 'INB8913410571', 'tabungan', 47499, 'IDR', 'active', '$2y$12$aswzs9HJXImIRwWlexQs3OYz/osZJjWZKG43I1i7xQJwNHfq06DDK', '2026-04-17 00:45:00', '2026-04-17 00:45:00', '2026-04-17 21:21:43'),
(2, 2, 'INB9695636285', 'tabungan', 10000, 'IDR', 'active', '$2y$12$cm6AMAln40t/s/rDyWTrDet1QioCh8OtyMcmJgrMbRUg8axZMyEwu', '2026-04-17 21:01:52', '2026-04-17 21:01:52', '2026-04-17 21:21:43');

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

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1776412707),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1776412707;', 1776412707),
('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1776485160),
('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1776485160;', 1776485160);

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
('2Ee37m5tf897tTp8Ub0B05YwOTuQgMK3qERDI9A9', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ0a1J0U1VTR2lzcllXaXZPMlU5MXREbWdTU1BOaXVXZG9NcWZLSmJFIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90cmFuc2FjdGlvbnMiLCJyb3V0ZSI6InRyYW5zYWN0aW9ucy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==', 1776486163),
('rgLeEuizFaSYoTiAJmcDwKtTSXfp6iAii1LlIbGS', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJUTFFWVnlkSm9lV3lwelg0TTZURFM5VURhejlPdkU1TUVmckxLUUVsIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sInVybCI6W10sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1776486103);

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
(2, 3, 1, 49999, 'mobile_banking', 'REF124', 'success', '2026-04-17 21:18:43', '2026-04-17 21:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
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

INSERT INTO `transactions` (`id`, `account_id`, `related_account_id`, `type`, `amount`, `balance_before`, `balance_after`, `reference_code`, `description`, `status`, `ip_address`, `created_at`, `updated_at`) VALUES
(2, 1, NULL, 'top_up', 10000, 0, 10000, NULL, 'Top up via mobile_banking', 'success', NULL, '2026-04-17 20:46:39', '2026-04-17 20:46:39'),
(3, 1, NULL, 'top_up', 49999, 10000, 59999, NULL, 'Top up via mobile_banking', 'success', NULL, '2026-04-17 21:18:43', '2026-04-17 21:18:43'),
(8, 1, NULL, 'transfer_out', 12500, 59999, 47499, NULL, 'Transfer ke INB9695636285 (Buat Jajan)', 'success', NULL, '2026-04-17 21:21:43', '2026-04-17 21:21:43'),
(9, 2, NULL, 'transfer_in', 10000, 0, 10000, NULL, 'Transfer masuk dari INB8913410571', 'success', NULL, '2026-04-17 21:21:43', '2026-04-17 21:21:43');

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
(1, 8, 1, 2, 10000, 'Buat Jajan', 'bi_fast', 2500, '2026-04-17 21:21:43', '2026-04-17 21:21:43');

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
(2, 'Ariq', 'crocodille.000@gmail.com', '2026-04-17 21:06:54', '$2y$12$qDCtWPCQUnq5llPbDJqay.NWsuLkBn6zUY1FF8wQSvMhwfstkxV2e', '085161676029', '1234567876543212', 'Jl.Macan Lindungan', '2006-12-31', 'male', NULL, 'user', 1, NULL, '2026-04-17 21:01:52', '2026-04-17 21:06:54');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_payments`
--
ALTER TABLE `bill_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `savings_book_entries`
--
ALTER TABLE `savings_book_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `top_ups`
--
ALTER TABLE `top_ups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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

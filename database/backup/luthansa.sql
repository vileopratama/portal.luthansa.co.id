-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 23 Nov 2016 pada 09.19
-- Versi Server: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luthansa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `account_no` varchar(100) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `accounts`
--

INSERT INTO `accounts` (`id`, `bank_id`, `account_no`, `account_name`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, '4281-94-0303', 'PT.Anther Prima Angkasa', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(2, 2, '101-0006-3789-03', 'Faky Syaiful Bahri', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `armada`
--

CREATE TABLE `armada` (
  `id` int(11) NOT NULL,
  `armada_category_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `number` varchar(30) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_booking` tinyint(1) NOT NULL,
  `booking_from_date` date NOT NULL,
  `booking_to_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `armada`
--

INSERT INTO `armada` (`id`, `armada_category_id`, `company_id`, `number`, `is_active`, `is_booking`, `booking_from_date`, `booking_to_date`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 1, 1, 'B 8909 BXL', 1, 0, '0000-00-00', '0000-00-00', 0, '0000-00-00 00:00:00', NULL, NULL),
(2, 1, 1, 'B 1510 DX', 1, 0, '0000-00-00', '0000-00-00', 1, '2016-11-01 15:47:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `armada_categories`
--

CREATE TABLE `armada_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` int(3) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `armada_categories`
--

INSERT INTO `armada_categories` (`id`, `name`, `capacity`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Commuter Hi-Ace 15 Seats', 15, 1, 1, '2016-10-11 05:00:00', 0, '0000-00-00 00:00:00'),
(2, 'Elf Long 19 Seats', 19, 1, 1, '2016-10-11 05:00:00', 0, '0000-00-00 00:00:00'),
(3, 'Elf Long 21 Seats', 21, 1, 1, '2016-10-03 07:00:00', NULL, NULL),
(4, 'Medium Bus 31 Seats', 31, 1, 1, '2016-10-11 05:00:00', NULL, NULL),
(5, 'Big Bus 59 Seats', 59, 1, 1, '2016-10-11 20:00:00', 1, '2016-11-15 11:31:47'),
(6, 'Elft', 60, 1, 1, '2016-11-01 12:11:51', 1, '2016-11-15 11:32:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `banks`
--

INSERT INTO `banks` (`id`, `name`, `branch`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Bank BCA', 'Cengkareng L40', 1, '0000-00-00 00:00:00', 0, '2016-11-09 11:13:17', 1),
(2, 'Bank Mandiri', 'Menara Prima Jaksel', 1, '0000-00-00 00:00:00', 0, '2016-11-09 11:14:38', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `fax_number` varchar(30) NOT NULL,
  `contact_mobile_number` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `companies`
--

INSERT INTO `companies` (`id`, `name`, `contact_name`, `phone_number`, `fax_number`, `contact_mobile_number`, `address`, `city`, `zip_code`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Luthansa', 'Faky Bahri', '0218700000', '0218700000', '0852222054064', 'Jln Kebagusan Raya No.80', 'Jakarta Selatan', '40252', 1, 1, '2016-10-11 06:00:00', 1, '2016-11-01 14:42:29'),
(2, 'Cahaya Mustika', 'Suhendar', '0215850000', '', '', '', 'Jakarta', '12090', 1, 1, '2016-11-01 14:45:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'Individual',
  `phone_number` varchar(18) NOT NULL,
  `mobile_number` varchar(18) DEFAULT NULL,
  `fax_number` varchar(18) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` date NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `type`, `phone_number`, `mobile_number`, `fax_number`, `address`, `city`, `zip_code`, `is_active`, `remember_token`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Suhendar', 'hendarsyahss@gmail.com', '', 'Corporate', '08522205406', '08522205406', '', 'Jalan pegangsaan TImur No.80 ', 'DKI Jakarta', '12090', 1, '', '2016-11-01', 1, '2016-11-03', 1),
(2, 'PT One Piece Sekatung', 'hendarsyahss2@gmail.com', '', 'Corporate', '', '', '', 'Jln Petarung No.90 ', 'Bandung', '14025', 1, '', '2016-11-02', 1, '2016-11-18', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `departments`
--

INSERT INTO `departments` (`id`, `name`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Driver', 1, '2016-11-21 08:50:16', 1, '0000-00-00 00:00:00', 0),
(3, 'Helper', 1, '2016-11-21 08:51:58', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(18) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id`, `name`, `department_id`, `email`, `phone_number`, `address`, `city`, `zip_code`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Mulyaono', 1, 'it2@luthansa.co.id', '021-7525-222', 'Jl.Pemanukan No.90', 'Jakarta', '11090', 1, '2016-11-21 09:23:47', 1, '2016-11-21 09:24:35', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoices`
--

CREATE TABLE `sales_invoices` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) NOT NULL,
  `number` varchar(100) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `booking_from_date` date NOT NULL,
  `booking_to_date` date NOT NULL,
  `booking_total_days` int(3) NOT NULL,
  `pick_up_point` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `total` double NOT NULL,
  `expense` double NOT NULL,
  `payment` double NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_invoices`
--

INSERT INTO `sales_invoices` (`id`, `sales_order_id`, `number`, `invoice_date`, `due_date`, `booking_from_date`, `booking_to_date`, `booking_total_days`, `pick_up_point`, `destination`, `customer_id`, `status`, `total`, `expense`, `payment`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 4, '-', '2016-11-04', '2016-11-11', '0000-00-00', '0000-00-00', 0, '', '', 1, 0, 170000, 80000, 0, '2016-11-04 13:19:32', 1, '2016-11-07 11:49:45', 1),
(3, 6, '-', '2016-11-07', '2016-11-26', '0000-00-00', '0000-00-00', 0, '', '', 2, 2, 27130000, 10000, 27130000, '2016-11-07 14:40:12', 1, '2016-11-07 16:49:32', 1),
(4, 7, '-', '2016-12-04', '2016-12-10', '0000-00-00', '0000-00-00', 0, '', '', 1, 0, 5850000, 0, 0, '2016-11-09 09:52:22', 1, '0000-00-00 00:00:00', 0),
(5, 12, '', '2016-11-11', '2016-11-15', '0000-00-00', '0000-00-00', 0, '', '', 6, 1, 9000, 0, 4500, '2016-11-14 15:02:09', 1, '0000-00-00 00:00:00', 0),
(6, 14, '0245.11/XI/2016', '2016-11-15', '2016-11-19', '0000-00-00', '0000-00-00', 0, '', '', 1, 0, 102000, 0, 0, '2016-11-16 08:49:28', 1, '0000-00-00 00:00:00', 0),
(7, 15, '0246.11/XI/2016', '2016-11-16', '2016-11-19', '0000-00-00', '0000-00-00', 0, '', '', 1, 2, 1500000, 0, 1500000, '2016-11-16 15:55:00', 1, '0000-00-00 00:00:00', 0),
(8, 13, '0244.11/XI/2016', '2016-11-15', '2016-11-19', '2016-11-18', '2016-11-18', 1, 'Jakarta', 'Kalideres Tangerang', 2, 0, 170000, 0, 0, '2016-11-18 14:45:22', 1, '2016-11-18 15:18:07', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_armada`
--

CREATE TABLE `sales_invoice_armada` (
  `id` int(11) NOT NULL,
  `sales_invoice_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `helper_id` int(11) DEFAULT NULL,
  `armada_id` int(11) NOT NULL,
  `hour_pick_up` varchar(18) NOT NULL,
  `km_start` varchar(100) NOT NULL,
  `km_end` varchar(100) NOT NULL,
  `driver_premi` double NOT NULL,
  `helper_premi` double NOT NULL,
  `operational_cost` double NOT NULL,
  `total_cost` double NOT NULL,
  `bbm` double NOT NULL,
  `tol` double NOT NULL,
  `parking_fee` double NOT NULL,
  `total_expense` double NOT NULL,
  `saldo` double NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_invoice_armada`
--

INSERT INTO `sales_invoice_armada` (`id`, `sales_invoice_id`, `driver_id`, `helper_id`, `armada_id`, `hour_pick_up`, `km_start`, `km_end`, `driver_premi`, `helper_premi`, `operational_cost`, `total_cost`, `bbm`, `tol`, `parking_fee`, `total_expense`, `saldo`, `updated_at`, `updated_by`) VALUES
(11, 8, 1, NULL, 1, '09:00', '1000', '2000', 500000, 0, 0, 500000, 20000, 100000, 10000, 130000, 370000, '2016-11-21 17:05:26', 1),
(12, 8, 1, NULL, 1, '09:00', '1000', '2000', 500000, 20000, 10000, 530000, 20000, 20000, 102000, 142000, 388000, '2016-11-21 17:05:49', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_costs`
--

CREATE TABLE `sales_invoice_costs` (
  `id` int(11) NOT NULL,
  `sales_invoice_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cost` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_invoice_costs`
--

INSERT INTO `sales_invoice_costs` (`id`, `sales_invoice_id`, `description`, `cost`) VALUES
(6, 2, 'Biaya Bus', 90000),
(10, 3, 'Biaya Penjemputan', 50000),
(11, 4, 'Biaya Penjemputan ke Tangerang', 60000),
(12, 6, 'Jemput Bandara Soekawro', 100000),
(15, 8, 'Biaya Penjemputan Kalideres', 80000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_details`
--

CREATE TABLE `sales_invoice_details` (
  `id` int(11) NOT NULL,
  `sales_invoice_id` int(11) NOT NULL,
  `armada_category_id` int(11) NOT NULL,
  `qty` int(3) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `days` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_invoice_details`
--

INSERT INTO `sales_invoice_details` (`id`, `sales_invoice_id`, `armada_category_id`, `qty`, `description`, `price`, `days`) VALUES
(4, 1, 3, 1, 'Pembelian 3 Biji', 120000, 3),
(20, 2, 4, 1, 'Testing2', 80000, 1),
(29, 3, 1, 4, 'Untuk 3 Hari', 1000000, 3),
(30, 3, 1, 3, 'Untuk 3 Hari', 1000000, 5),
(31, 3, 2, 1, 'Testing', 8000, 10),
(32, 4, 1, 3, '1 (satu) Unit HiAce Commuter 15 Seat\n3 (Tiga) hari ; Selasa - Kamis, 18 - 20 Oktober 2016\nDalam Kota Jakarta dan Tangerang', 1350000, 3),
(33, 4, 2, 1, '1 (satu) Unit HiAce Commuter 19 Seat 3 (Tiga) hari ; Selasa - Kamis, 18 - 20 Oktober 2016 Dalam Kota Jakarta dan Tangerang', 1800000, 3),
(34, 5, 1, 3, 'SSD', 2000, 3),
(35, 5, 2, 3, 'XXX', 1000, 3),
(36, 6, 1, 1, 'Bandara Soekawro', 2000, 1),
(37, 7, 1, 1, '1 Unit Commuter Hi Ace 15 Jakarta Surabaya', 1500000, 1),
(42, 8, 1, 1, 'Test', 10000, 1),
(43, 8, 4, 1, 'Jakarta Tangerang', 80000, 1),
(44, 8, 2, 2, 'Testing', 500000, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_expense`
--

CREATE TABLE `sales_invoice_expense` (
  `id` int(11) NOT NULL,
  `sales_invoice_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `expense` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_invoice_expense`
--

INSERT INTO `sales_invoice_expense` (`id`, `sales_invoice_id`, `description`, `expense`, `created_by`, `created_at`) VALUES
(5, 2, 'Biaya Perawatan', 80000, 0, '0000-00-00 00:00:00'),
(7, 3, 'Biaya Tambahan Bensin', 10000, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_payments`
--

CREATE TABLE `sales_invoice_payments` (
  `id` int(11) NOT NULL,
  `sales_invoice_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `percentage` double NOT NULL,
  `value` double NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_invoice_payments`
--

INSERT INTO `sales_invoice_payments` (`id`, `sales_invoice_id`, `account_id`, `payment_date`, `percentage`, `value`, `description`, `created_at`, `created_by`) VALUES
(2, 3, 0, '2016-11-07', 50, 13565000, 'Pembayaran Uang Muka', '2016-11-08 10:36:57', 1),
(3, 3, 0, '2016-11-16', 50, 13565000, 'Pembayaran Pelunasan', '2016-11-08 11:07:04', 1),
(4, 5, 0, '2016-11-11', 50, 4500, 'Pembayaran Pertama untuk DP', '2016-11-14 15:02:50', 1),
(5, 7, 0, '2016-11-16', 50, 750000, 'Pembayaran DP', '2016-11-16 15:58:39', 1),
(6, 7, 0, '2016-11-16', 50, 750000, 'Pelunasan', '2016-11-16 15:59:32', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` int(11) NOT NULL,
  `source` tinyint(1) NOT NULL,
  `number` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `due_date` date NOT NULL,
  `booking_from_date` date NOT NULL,
  `booking_to_date` date NOT NULL,
  `booking_total_days` int(3) NOT NULL,
  `pick_up_point` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone_number` varchar(18) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `total` double NOT NULL,
  `expense` double NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `source`, `number`, `order_date`, `due_date`, `booking_from_date`, `booking_to_date`, `booking_total_days`, `pick_up_point`, `destination`, `customer_id`, `customer_email`, `customer_phone_number`, `status`, `total`, `expense`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 0, '-', '2016-11-02', '2016-11-10', '2016-11-17', '2016-11-26', 10, '', '', 1, '', '', 0, 360000, 0, '2016-11-02 16:56:54', 1, '2016-11-18 15:16:19', 1),
(4, 0, '-', '2016-11-04', '2016-11-11', '0000-00-00', '0000-00-00', 0, '', '', 2, '', '', 1, 2780000, 50000, '2016-11-04 12:10:04', 1, '2016-11-04 12:59:17', 1),
(6, 0, '-', '2016-11-07', '2016-11-26', '0000-00-00', '0000-00-00', 0, '', '', 2, '', '', 1, 6000000, 50000, '2016-11-07 14:39:21', 1, '0000-00-00 00:00:00', 0),
(7, 0, '-', '2016-12-04', '2016-12-10', '0000-00-00', '0000-00-00', 0, '', '', 1, '', '', 1, 5850000, 60000, '2016-11-09 09:37:24', 1, '2016-11-09 09:39:23', 1),
(12, 1, '', '2016-11-11', '2016-11-15', '2016-11-10', '2016-11-12', 0, 'Test', 'Test', 6, 'hendarsyahss5@gmail.com', '08522208555', 1, 9000, 0, '2016-11-11 08:59:44', 6, '2016-11-14 11:54:53', 1),
(13, 0, '0244.11/XI/2016', '2016-11-15', '2016-11-19', '2016-11-18', '2016-11-18', 1, 'Jakarta', 'Kalideres Tangerang', 2, '', '', 1, 170000, 0, '2016-11-15 10:56:18', 1, '2016-11-18 10:53:06', 1),
(14, 0, '0245.11/XI/2016', '2016-11-15', '2016-11-19', '0000-00-00', '0000-00-00', 0, 'Bandara Soekawro', 'Rumah', 1, '', '', 1, 102000, 0, '2016-11-15 11:04:43', 1, '0000-00-00 00:00:00', 0),
(15, 0, '0246.11/XI/2016', '2016-11-16', '2016-11-19', '0000-00-00', '0000-00-00', 0, 'Cengkrang - Jakarta', 'Cengkrang - Jakarta', 1, '', '', 1, 1500000, 0, '2016-11-16 15:52:31', 1, '2016-11-16 15:53:24', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_order_costs`
--

CREATE TABLE `sales_order_costs` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cost` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_order_costs`
--

INSERT INTO `sales_order_costs` (`id`, `sales_order_id`, `description`, `cost`) VALUES
(3, 4, 'Biaya Penjemputan Ke Tangerang', 50000),
(5, 6, 'Biaya Penjemputan', 50000),
(6, 7, 'Biaya Penjemputan ke Tangerang', 60000),
(8, 14, 'Jemput Bandara Soekawro', 100000),
(12, 13, 'Biaya Penjemputan Kalideres', 80000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_order_details`
--

CREATE TABLE `sales_order_details` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) NOT NULL,
  `armada_category_id` int(11) NOT NULL,
  `armada_category_name` varchar(255) NOT NULL,
  `armada_capacity` int(3) NOT NULL,
  `qty` int(3) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `days` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_order_details`
--

INSERT INTO `sales_order_details` (`id`, `sales_order_id`, `armada_category_id`, `armada_category_name`, `armada_capacity`, `qty`, `description`, `price`, `days`) VALUES
(5, 4, 4, '', 0, 0, 'Jakarta Bandung 3 Hari ', 900000, 3),
(6, 4, 6, '', 0, 0, 'Testing', 80000, 1),
(9, 6, 1, '', 0, 3, 'Untuk 3 Hari', 1000000, 5),
(10, 6, 1, '', 0, 3, 'Untuk 3 Hari', 1000000, 3),
(12, 7, 1, '', 0, 3, '-', 1350000, 3),
(13, 7, 2, '', 0, 1, '1 (satu) Unit HiAce Commuter 19 Seat 3 (Tiga) hari ; Selasa - Kamis, 18 - 20 Oktober 2016 Dalam Kota Jakarta dan Tangerang', 1800000, 3),
(19, 12, 1, '', 0, 3, 'SSD', 2000, 3),
(20, 12, 2, '', 0, 3, 'XXX', 1000, 3),
(23, 14, 1, '', 0, 1, 'Bandara Soekawro', 2000, 1),
(24, 15, 1, '', 0, 1, '1 Unit Commuter Hi Ace 15 Jakarta Surabaya', 1500000, 1),
(29, 13, 1, '', 0, 1, 'Test', 10000, 1),
(30, 13, 4, '', 0, 1, 'Jakarta Tangerang', 80000, 1),
(31, 1, 3, '', 0, 3, 'Pembelian 3 Biji', 120000, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `int` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`int`, `key`, `value`) VALUES
(1, 'company_address', 'Jln.Pondok Randu Raya No.8 Duri Kosambi, Cengkareng'),
(2, 'company_city', 'Jakarta Barat'),
(3, 'company_zip_code', '11750'),
(4, 'company_telephone_number', '021-9890 2175 / 0852 1360 5352'),
(5, 'company_email', 'office@luthansa.co.id / luthansagroup@gmail.com'),
(6, 'company_website', 'www.luthansa.co.id'),
(7, 'company_name', 'PT Anther Prima Persada'),
(8, 'company_signature_name', 'Luthansa Group'),
(9, 'invoice_starting_number', '239'),
(10, 'invoice_days_notification_due_date', '3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL DEFAULT '1',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `user_group_id`, `first_name`, `last_name`, `email`, `password`, `remember_token`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 'Faky', 'Faky', 'it@luthansa.co.id', '$2y$10$ZDEQU.oXaMO6M0DpMtFtoegM7Y0.S3.f2tBLGRYlMAvYhjb2vH3eW', 'OQT4FMGzTN9vjseqPYJ1aiFax6zQYB7qNQ1jMG4b8baWbfLHYAREOkczIW9z', 1, '2016-09-01 00:00:00', 1, '2016-11-09 16:33:12', 1),
(2, 2, 'Hendarsyah', 'Suhendar', 'hendarsyahss@luthansa.co.id', '$2y$10$22SPvnPG2byd0Ub0Oog7De/6krOfIy8NP7WdsRjfAdpGzPebK/mSW', '98OqbGAPX00Imvlx2jj594WUOVq0bMRX6Zzu1DHcEW2sy0k42KMVQDplgGQf', 1, '2016-10-31 16:15:20', NULL, '2016-11-23 11:06:09', 1),
(3, 1, 'Sukandar', 'Hendrawinata', 'sukandar@gmail.com', '$2y$10$.akF.sQQXGAQ29q5O5zy9uDnhbav04ECQjwqRzX3JaaAthgV9Wjym', '', 1, '2016-11-01 09:38:33', 0, '2016-11-23 10:49:23', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_groups`
--

INSERT INTO `user_groups` (`id`, `name`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'administrator', 1, '2016-11-01 05:00:00', 0, '0000-00-00 00:00:00', 0),
(2, 'cashier', 1, '2016-11-16 00:00:00', 0, '2016-11-23 14:58:02', 1),
(6, 'owner', 0, '2016-11-23 10:32:18', 1, '2016-11-23 10:34:31', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_group_modules`
--

CREATE TABLE `user_group_modules` (
  `id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `module_slug` varchar(100) NOT NULL,
  `access` enum('r','c','u','d') NOT NULL DEFAULT 'r'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_group_modules`
--

INSERT INTO `user_group_modules` (`id`, `user_group_id`, `module_slug`, `access`) VALUES
(17, 6, 'account-bank', 'r'),
(18, 6, 'account-bank', 'c'),
(19, 6, 'account-bank', 'u'),
(20, 6, 'account-bank', 'd'),
(21, 6, 'profile', 'r'),
(22, 6, 'user', 'r'),
(269, 2, 'account-bank', 'r'),
(270, 2, 'account-bank', 'c'),
(271, 2, 'account-bank', 'u'),
(272, 2, 'account-bank', 'd'),
(273, 2, 'profile', 'r'),
(274, 2, 'profile', 'c'),
(275, 2, 'profile', 'u'),
(276, 2, 'profile', 'd'),
(277, 2, 'user', 'r'),
(278, 2, 'user', 'c'),
(279, 2, 'user', 'u'),
(280, 2, 'user', 'd'),
(281, 2, 'setting', 'r'),
(282, 2, 'setting', 'c'),
(283, 2, 'setting', 'u'),
(284, 2, 'setting', 'd'),
(285, 2, 'sales-order', 'r'),
(286, 2, 'sales-order', 'c'),
(287, 2, 'sales-order', 'u'),
(288, 2, 'sales-order', 'd'),
(289, 2, 'sales-invoice', 'r'),
(290, 2, 'sales-invoice', 'c'),
(291, 2, 'sales-invoice', 'u'),
(292, 2, 'sales-invoice', 'd'),
(293, 2, 'report-sales-summary', 'r'),
(294, 2, 'report-sales-summary', 'c'),
(295, 2, 'report-sales-summary', 'u'),
(296, 2, 'report-sales-summary', 'd'),
(297, 2, 'armada', 'r'),
(298, 2, 'armada', 'c'),
(299, 2, 'armada', 'u'),
(300, 2, 'armada', 'd'),
(301, 2, 'department', 'r'),
(302, 2, 'department', 'c'),
(303, 2, 'department', 'u'),
(304, 2, 'department', 'd'),
(305, 2, 'dashboard', 'r'),
(306, 2, 'customer', 'r'),
(307, 2, 'customer', 'c'),
(308, 2, 'customer', 'u'),
(309, 2, 'customer', 'd'),
(310, 2, 'company', 'r'),
(311, 2, 'company', 'c'),
(312, 2, 'company', 'u'),
(313, 2, 'company', 'd'),
(314, 2, 'bank', 'r'),
(315, 2, 'bank', 'c'),
(316, 2, 'bank', 'u'),
(317, 2, 'bank', 'd'),
(318, 2, 'armada-category', 'r'),
(319, 2, 'armada-category', 'c'),
(320, 2, 'armada-category', 'u'),
(321, 2, 'armada-category', 'd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `armada`
--
ALTER TABLE `armada`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `armada_categories`
--
ALTER TABLE `armada_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoices`
--
ALTER TABLE `sales_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoice_armada`
--
ALTER TABLE `sales_invoice_armada`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoice_costs`
--
ALTER TABLE `sales_invoice_costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoice_details`
--
ALTER TABLE `sales_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoice_expense`
--
ALTER TABLE `sales_invoice_expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoice_payments`
--
ALTER TABLE `sales_invoice_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_costs`
--
ALTER TABLE `sales_order_costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_details`
--
ALTER TABLE `sales_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`int`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group_modules`
--
ALTER TABLE `user_group_modules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `armada`
--
ALTER TABLE `armada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `armada_categories`
--
ALTER TABLE `armada_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sales_invoices`
--
ALTER TABLE `sales_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `sales_invoice_armada`
--
ALTER TABLE `sales_invoice_armada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `sales_invoice_costs`
--
ALTER TABLE `sales_invoice_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `sales_invoice_details`
--
ALTER TABLE `sales_invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `sales_invoice_expense`
--
ALTER TABLE `sales_invoice_expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sales_invoice_payments`
--
ALTER TABLE `sales_invoice_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `sales_order_costs`
--
ALTER TABLE `sales_order_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `sales_order_details`
--
ALTER TABLE `sales_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `int` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_group_modules`
--
ALTER TABLE `user_group_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

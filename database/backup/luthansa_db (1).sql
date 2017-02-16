-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Inang: localhost:3306
-- Waktu pembuatan: 13 Feb 2017 pada 10.39
-- Versi Server: 5.5.54-cll
-- Versi PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `luthansa_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_id` int(11) NOT NULL,
  `account_no` varchar(100) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `accounts`
--

INSERT INTO `accounts` (`id`, `bank_id`, `account_no`, `account_name`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, '428-194-0303', 'PT Anther Prima Persada', 1, '0000-00-00 00:00:00', 0, '2017-01-02 22:49:05', 1),
(2, 2, '101-0006-3789-03', 'Faky Saiful Bahri', 1, '0000-00-00 00:00:00', 0, '2016-12-17 21:57:56', 1),
(4, 1, '5245040127', 'Suhendar', 0, '2016-11-15 10:28:29', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `armada`
--

CREATE TABLE IF NOT EXISTS `armada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `armada_category_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `number` varchar(30) NOT NULL,
  `body_number` varchar(100) DEFAULT NULL,
  `lambung_number` varchar(28) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_booking` tinyint(1) NOT NULL,
  `booking_from_date` date NOT NULL,
  `booking_to_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data untuk tabel `armada`
--

INSERT INTO `armada` (`id`, `armada_category_id`, `company_id`, `number`, `body_number`, `lambung_number`, `is_active`, `is_booking`, `booking_from_date`, `booking_to_date`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 3, 1, 'B 7057 VAA', 'B 7057 VAA', 'LT2102', 1, 0, '0000-00-00', '0000-00-00', 0, '0000-00-00 00:00:00', 1, '2016-12-30 21:22:32'),
(2, 4, 1, 'B 7108 VGA', NULL, NULL, 1, 0, '0000-00-00', '0000-00-00', 1, '2016-11-01 15:47:52', 1, '2016-11-26 22:27:11'),
(4, 3, 1, 'B 7071 VAA', NULL, NULL, 1, 0, '0000-00-00', '0000-00-00', 1, '2016-11-26 22:28:44', 1, '2016-11-26 22:29:13'),
(5, 2, 1, 'B 7058 VAA', NULL, NULL, 1, 0, '0000-00-00', '0000-00-00', 1, '2016-11-26 22:30:06', NULL, NULL),
(6, 2, 1, 'B 7073 VAA', NULL, NULL, 1, 0, '0000-00-00', '0000-00-00', 1, '2016-11-26 22:30:49', NULL, NULL),
(7, 1, 1, 'B 7174 NAA', NULL, NULL, 1, 0, '0000-00-00', '0000-00-00', 1, '2016-11-26 22:31:38', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `armada_categories`
--

CREATE TABLE IF NOT EXISTS `armada_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `capacity` int(3) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data untuk tabel `armada_categories`
--

INSERT INTO `armada_categories` (`id`, `name`, `capacity`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Commuter Hi-Ace 15 Seats', 15, 1, 1, '2016-10-11 05:00:00', 1, '2016-11-30 00:27:44'),
(2, 'ELF Long 19 Seats', 19, 1, 1, '2016-10-11 05:00:00', 1, '2016-11-30 00:27:22'),
(3, 'ELF Long 21 Seats', 21, 1, 1, '2016-10-03 07:00:00', 1, '2016-11-30 00:27:01'),
(4, 'Medium Bus', 33, 1, 1, '2016-10-11 05:00:00', 1, '2016-12-26 19:22:45'),
(5, 'Big Bus 48/59 Seats', 48, 1, 1, '2016-10-11 20:00:00', 1, '2016-12-21 23:25:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `banks`
--

CREATE TABLE IF NOT EXISTS `banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `banks`
--

INSERT INTO `banks` (`id`, `name`, `branch`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Bank BCA', 'Cengkareng L40', 1, '0000-00-00 00:00:00', 0, '2016-11-09 11:13:17', 1),
(2, 'Bank Mandiri', 'Menara Prima Jaksel', 1, '0000-00-00 00:00:00', 0, '2016-11-09 11:14:38', 1),
(3, 'Bank BTN', 'Tangerang Mall', 1, '2016-11-15 10:29:08', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `companies`
--

INSERT INTO `companies` (`id`, `name`, `contact_name`, `phone_number`, `fax_number`, `contact_mobile_number`, `address`, `city`, `zip_code`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'LUTHANSA Luxury Coach', 'Faky Bahri', '0218700000', '0218700000', '0852222054064', 'Jln Kebagusan Raya No.80', 'Jakarta Selatan', '40252', 1, 1, '2016-10-11 06:00:00', 1, '2016-11-30 00:26:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_decrypt` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'Individual',
  `phone_number` varchar(18) NOT NULL,
  `contact_person` varchar(150) NOT NULL,
  `mobile_number` varchar(18) DEFAULT NULL,
  `fax_number` varchar(18) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(255) NOT NULL,
  `request_forgot_password` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` date NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `password_decrypt`, `type`, `phone_number`, `contact_person`, `mobile_number`, `fax_number`, `address`, `city`, `zip_code`, `is_active`, `remember_token`, `request_forgot_password`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(13, 'A HOLIDAY ( PT Anugrah Jaya Karunia )', 'oriana@a-holiday.net', '$2y$10$hzuyGGkp9Ong88zO4pcsyOuzNNY22bvzLJRlm975O/BOC0x8K6Mre', '', 'Corporate', '+62 21 24520611 ', 'Safuellah', '085222000878', '', 'Komplek Ruko Gading Kirana Utara Blok F 10 No.7\r\nKelapa Gading', 'Jakarta Utara', '14240', 1, '', 0, '0000-00-00', 0, '2016-12-13', 1),
(18, 'PT Inactive Indonesia', 'support@bdoindonesia.com', '$2y$10$tppLmwOga8zpf9I./fDd1.RtqrlgCPK7Cff5TIaenT.RKUhQ2OcOe', '', 'Corporate', '', 'Suhendar', '', '', '', '', '', 1, 'FNKAjB74vQjNDttQLGTwSnnC3QnPyeRety9jqLxYvvCSWpCGssRBZ5wFGGrY', 0, '2016-12-07', 1, '2016-12-13', 1),
(20, 'SMPI AL-FALAAH', '', '', '', 'Corporate', '021 – 74632345', 'NIA ERVIYANA', '0000000000000', '', 'Jl. Intan no 18 – Vila Mutiara\r\nSawah Baru – Ciputat\r\nTangerang Selatan', 'Banten', '15413', 1, '', 0, '2016-12-17', 1, '2017-01-21', 1),
(21, 'SMK TUNAS JAKASAMPURNA', 'ekawati001@gmail.com', '$2y$10$AHCrESG4WRY5KcCDJPhQpOafQnQ26SyOzLvc1ZmjywzLxPUEimMDK', '6c3d0a24', 'Corporate', '08561747095', 'IBU EKA', '08561747095', '', 'Grand Galaxi City, Jl. Taman Agave Blok H3 No. 16', 'BEKASI', '17147', 1, '', 0, '2016-12-21', 1, '2016-12-21', 1),
(22, 'PT AKR Corporindo Tbk', 'catherine.constantin@akr.co.id', '', '', 'Corporate', '', 'Catherine', '081290633338', '', '', '', '', 1, '', 0, '2016-12-24', 1, '0000-00-00', 0),
(23, 'SEKOLAH TINGGI PARIWISATA SAHID', 'rinakurniawati@stpsahid.ac.id', '', '', 'Corporate', '0217402329', 'Rina Kurniawati, S.Pd, MM, MBA', '02155760186', '0217428152', 'Jl. Kemiri Raya No.22\r\nPondok Cabe Tangerang Selatan Banten\r\n', 'Tangerang Selatan', '', 1, '', 0, '2016-12-26', 1, '2016-12-26', 1),
(25, 'ARWANI', '', '', '', 'Individual', '', '', '085782804111', '', '', '', '', 1, '', 0, '2016-12-31', 1, '0000-00-00', 0),
(27, 'ANNISA TRAVEL', 'mice@annisatravel.com', '', '', 'Corporate', '02129127777', 'FADLIL', '085779631350', '', 'Jl. Raya Lenteng Agung Barat Blok A No. 8 RT 5/RW 8, Lenteng Agung ', 'Jakarta Selatan', '12610', 1, '', 0, '2017-01-05', 1, '2017-01-05', 1),
(31, 'PT Forkabe', 'madigandante@gmail.com', '', '', 'Corporate', '', 'Madigandante', '081586413075', '', '', '', '', 1, '', 0, '2017-01-18', 1, '0000-00-00', 0),
(32, 'DPR-RI', 'risalahdpd@gmail.com', '', '', 'Corporate', '02157900757', 'Mrs. Devi', '081218034293', '02157900753', 'Jl. Jenderal Gatot Subroto, Senayan, Kebayoran Baru, RT.1/RW.3, Gelora', 'Jakarta Selatan', '10270', 1, '', 0, '2017-02-06', 1, '2017-02-06', 1),
(33, 'Hendarsyah', 'hendarsyahss@gmail.com', '$2y$10$hOWw.iW2c3EpMAammpUcWeFhiwUNDlDiEqvjLErFzgXS7ggd16m8K', '4aae42b0', 'Corporate', '', '', '085222054064', '', '', '', '', 0, '', 0, '2017-02-13', 1, '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `departments`
--

INSERT INTO `departments` (`id`, `name`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Office', 1, '2016-11-26 05:00:00', 1, '2016-11-27 01:22:51', 1),
(2, 'Operational', 1, '2016-11-26 05:00:00', 1, '2016-11-27 00:56:00', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `department_id` int(11) NOT NULL,
  `position` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `identity_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `identity_validity_period` date NOT NULL,
  `sim_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sim_validity_period` date NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bank_account_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bank_account_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id`, `nip`, `department_id`, `position`, `name`, `birth_date`, `birth_place`, `identity_number`, `identity_validity_period`, `sim_number`, `sim_validity_period`, `gender`, `email`, `phone_number`, `address`, `zip_code`, `city`, `bank_account_no`, `bank_account_name`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '', 2, 'PENGEMUDI', 'HERIYANA', '1973-02-25', 'BANDUNG', '3273212502730003', '2020-02-25', '730213053966', '2020-02-25', 'Male', 'heriyana@luthansa.co.id', '0821 2906 9868', 'BATUNUNGGAL NO.46 RT002 RW 010\r\nBANDUNG ', '12090', 'JAWA BARAT', '', '', 1, '2016-11-26 05:00:00', 1, '2016-12-02 01:18:12', 1),
(2, '', 2, '', 'KARTONO', '0000-00-00', '', '', '0000-00-00', '', '0000-00-00', '', '', '0818 0773 0143', 'JL. BATURADEN IV RT007/007\r\nBANDUNG JAWA BARAT', '12225', 'JAWA BARAT', '', '', 1, '2016-11-27 01:22:17', 1, '0000-00-00 00:00:00', 0),
(3, '', 2, 'Helper', 'UUD MASUD', '1985-06-17', 'CIAMIS', '3207081706850004', '2019-06-17', '111111', '2017-11-29', 'Male', 'uud@luthansa.co.id', '00000', 'DUSUN SUKASIRNA RT039/016\r\nMAPARAH, PANJALU', '11111', 'JAWA BARAT', '', '', 1, '2016-11-27 01:24:35', 1, '2016-12-02 01:20:23', 1),
(4, '', 1, 'Customer Service Officer', 'ENDANG FITRI JUMIYANTI', '1990-04-01', 'Jakarta', '3173044104900007', '2016-04-01', '111111111', '2018-01-01', 'Female', 'endang@luthansa.co.id', '081293963030', 'TN SEREAL XIII, GG UNITAS/28 RT010/009, TANAH SEREAL - TAMBORA', '11111', 'JAKARTA BARAT', '', '', 1, '2016-11-27 01:25:59', 1, '2016-12-02 01:13:21', 1),
(5, '', 2, '', 'H. SOFYAN PURNAMA', '0000-00-00', '', '', '0000-00-00', '', '0000-00-00', '', '', '0812 8630 0506', 'KP. GUNUNG PIPISAN NO. 26 RT 01/04 KEL. BOJONGMANGU KEC. PDMEUNGPEUK', '11111', 'JAWA BARAT', '', '', 1, '2016-11-27 01:27:05', 1, '0000-00-00 00:00:00', 0),
(6, '10000200', 2, 'Office', 'DWI', '2016-12-15', 'Bandung', '10025580000', '2016-12-15', '10250000852000', '2016-12-30', 'Female', 'dwi@luthansa.co.id', '083807836495', 'JAKARTA', '11111', 'JAKARTA BARAT', '54250425668', 'BCA Dwi', 1, '2016-11-27 01:28:18', 1, '2016-12-15 08:53:05', 1),
(7, '', 2, '', 'JOHAN', '0000-00-00', '', '', '0000-00-00', '', '0000-00-00', '', '', '082260424363', 'CIBINONG', '11111', 'JAWA BARAT', '', '', 1, '2016-11-27 01:29:15', 1, '0000-00-00 00:00:00', 0),
(8, '', 1, 'Komisaris', 'FAKY SAIFUL BAHRI', '1989-11-29', 'Jakarta', '3174102911890004', '2017-11-29', '891112051349', '2017-11-29', 'Male', 'fahri@luthansa.co.id', '085213605352', 'JL. H. ROHIMIN NO. 33 RT 017/003 ULUJAMI PESANGGRAHAN', '12250', 'JAKARTA SELATAN', '4761076501', '1010006378903', 1, '2016-11-27 01:31:35', 1, '2016-12-02 01:15:34', 1),
(9, '', 1, 'Direktur Utama', 'ANDI PRIHANTORO', '1986-09-12', 'Klaten', '3671121209860002', '2017-09-12', '11111', '2018-09-02', 'Male', 'andhire@luthansa.co.id', '081317912727', 'JL. KARYAWAN II NO. 61 RT001/005 KARANG TENGAH, KARANG TENGAH', '15157', 'TANGERANG', '', '', 1, '2016-11-27 01:33:42', 1, '2016-12-02 01:10:53', 1),
(10, '', 1, 'Direktur', 'LUTHER HALIM', '1995-03-16', 'Jambi', '1506011603950001', '2017-03-16', '01665', '2017-01-04', 'Male', 'luther@luthansa.co.id', '081314932432', 'Jl. TB Angke No. 34 F RT 016/010 Jelambar Baru, Grogol Petamburan', '11111', 'JAKARTA BARAT', '', '', 1, '2016-11-27 01:34:45', 1, '2016-12-02 01:09:12', 1),
(11, '', 2, '', 'ANDRI', '0000-00-00', '', '', '0000-00-00', '', '0000-00-00', '', '', '081395060106', 'CIBITUNG', '11111', 'JAWA BARAT', '', '', 1, '2016-11-27 01:36:55', 1, '0000-00-00 00:00:00', 0),
(12, '', 1, '', 'OCIM', '0000-00-00', '', '', '0000-00-00', '', '0000-00-00', '', '', '000000000000000000', '00000000', '11111', '1111111111', '', '', 1, '2016-11-27 01:38:28', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoices`
--

CREATE TABLE IF NOT EXISTS `sales_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(15) NOT NULL DEFAULT 'Tour',
  `sales_order_id` int(11) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT '0',
  `number` varchar(100) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_company_name` varchar(255) NOT NULL,
  `booking_from_date` date NOT NULL,
  `booking_to_date` date NOT NULL,
  `booking_total_days` int(3) NOT NULL,
  `total_passenger` int(6) NOT NULL,
  `pick_up_point` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `total` double NOT NULL,
  `expense` double NOT NULL,
  `payment` double NOT NULL,
  `is_trash` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Active,1=Trash',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data untuk tabel `sales_invoices`
--

INSERT INTO `sales_invoices` (`id`, `type`, `sales_order_id`, `order_number`, `number`, `invoice_date`, `due_date`, `customer_id`, `customer_company_name`, `booking_from_date`, `booking_to_date`, `booking_total_days`, `total_passenger`, `pick_up_point`, `destination`, `status`, `total`, `expense`, `payment`, `is_trash`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 'Tour', 4, 0, '-', '2016-11-04', '2016-11-11', 1, '', '0000-00-00', '0000-00-00', 1, 0, '', '', 0, 170000, 80000, 0, 1, '2016-11-04 13:19:32', 1, '2016-12-19 15:25:03', 1),
(3, 'Tour', 6, 0, '-', '2016-11-07', '2016-11-26', 2, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 2, 27130000, 10000, 27130000, 1, '2016-11-07 14:40:12', 1, '2016-11-07 16:49:32', 1),
(4, 'Tour', 7, 0, '-', '2016-12-04', '2016-12-10', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 0, 5850000, 0, 0, 1, '2016-11-09 09:52:22', 1, '0000-00-00 00:00:00', 0),
(5, 'Tour', 12, 0, '', '2016-11-11', '2016-11-15', 6, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, 9000, 0, 4500, 1, '2016-11-14 15:02:09', 1, '0000-00-00 00:00:00', 0),
(6, 'Tour', 1, 0, '-', '2016-11-02', '2016-11-10', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 0, 0, 0, 0, 1, '2016-11-15 16:11:46', 1, '0000-00-00 00:00:00', 0),
(7, 'Tour', 13, 0, '0006.11/XI/2016', '2016-11-15', '2016-11-19', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 2, 200000, 886640000, 200000, 1, '2016-11-15 16:52:44', 1, '0000-00-00 00:00:00', 0),
(8, 'Tour', 14, 0, '0007.11/XI/2016', '2016-11-09', '2016-11-17', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, 880000, 0, 440000, 1, '2016-11-16 09:41:02', 1, '0000-00-00 00:00:00', 0),
(9, 'Tour', 15, 0, '0008.11/XI/2016', '2016-11-16', '2016-11-19', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, 1000, 0, 500, 1, '2016-12-09 09:43:56', 1, '0000-00-00 00:00:00', 0),
(10, 'Tour', 15, 0, '0008.11/XI/2016', '2016-11-16', '2016-11-19', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, 1000, 0, 500, 1, '2016-12-09 09:45:05', 1, '0000-00-00 00:00:00', 0),
(11, 'Tour', 15, 0, '0008.11/XI/2016', '2016-11-16', '2016-11-19', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, 1000, 0, 500, 1, '2016-12-09 09:47:02', 0, '0000-00-00 00:00:00', 0),
(12, 'Tour', 15, 0, '0008.11/XI/2016', '2016-11-16', '2016-11-19', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, 1000, 0, 500, 1, '2016-12-09 09:48:39', 1, '0000-00-00 00:00:00', 0),
(13, 'Tour', 15, 0, '0008.11/XI/2016', '2016-11-16', '2016-11-19', 1, '', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, 1000, 0, 500, 1, '2016-12-09 09:55:49', 1, '0000-00-00 00:00:00', 0),
(14, 'Tour', 25, 0, '0016.12/XII/2016', '2016-12-13', '2016-12-13', 13, '', '2016-12-13', '2016-12-13', 1, 0, 'Test', 'Test', 1, 241000, 0, 120500, 1, '2016-12-13 15:59:14', 1, '2016-12-13 17:00:34', 1),
(15, 'Tour', 26, 0, '0017.12/XII/2016', '2016-12-14', '2016-12-14', 1, '', '2016-12-14', '2016-12-14', 1, 80, 'test', 'test', 0, 20000, 42000, 0, 1, '2016-12-14 14:31:54', 1, '0000-00-00 00:00:00', 0),
(16, 'Tour', 29, 0, '0020.12/XII/2016', '2016-12-15', '2016-12-15', 19, '', '2016-12-15', '2016-12-15', 1, 0, 'Tangerang Karawaci ', 'Bandung', 1, 5050000, 900000, 0, 1, '2016-12-15 10:20:00', 1, '0000-00-00 00:00:00', 0),
(19, 'Tour', 30, 0, '0021.12/XII/2016', '2016-12-06', '2017-02-15', 20, '', '2017-03-15', '2017-03-17', 3, 72, 'BINTARO-GAMBIR-ST. YOGYAKARTA-GAMBIR-BINTARO', 'CANDI BOROBUDUR-LAVA TOUR MERAPI-CANDI PRAMBANAN-MUSEUM DIRGANTARA-KRATON YOGYAKARTA-CITY TOUR YOGYAKARTA', 1, 144000000, 0, 50000000, 0, '2016-12-17 22:47:02', 1, '2016-12-21 23:13:33', 1),
(20, 'Transport', 31, 0, '0022.12/XII/2016', '2016-12-21', '2017-01-02', 21, '', '2017-01-09', '2017-01-09', 1, 75, 'Grand Galaxi City, Jl. Taman Agave Blok H3 No. 16, Bekasi 17147', 'MD. Animation (Jl. Setiabudi Selatan No. 7, Jakarta Selatan ( bus stand by) - Camp Hulu Cai, Jl. Babakan Ciaul (Jl. Veteran III Desa Cibedug, Ciawi Bogor) - DROP OFF', 2, 6000000, 0, 6000000, 0, '2016-12-21 23:56:59', 1, '0000-00-00 00:00:00', 0),
(21, 'Transport', 32, 0, '0023.12/XII/2016', '2016-12-21', '2017-01-02', 21, '', '2017-01-11', '2017-01-11', 1, 75, 'Camp Hulu Cai, Jl. Babakan Ciaul (Jl. Veteran III Desa Cibedug, Ciawi Bogor)', 'SMK Tunas Jakasampurna Bekasi', 2, 6000000, 0, 6000000, 0, '2016-12-22 00:04:12', 1, '0000-00-00 00:00:00', 0),
(22, 'Transport', 33, 0, '.12/XII/2016', '2016-12-22', '2017-01-23', 22, '', '2017-01-28', '2017-01-29', 2, 33, 'WTC Matahari, Jl Raya Serpong No 39, Tangerang Selatan', 'Jl. Kol Masturi Lembang - Bandung', 2, 5600000, 0, 5600000, 0, '2016-12-24 23:16:14', 1, '2017-01-31 18:43:53', 2),
(23, 'Transport', 35, 0, '0026.12/XII/2016', '2016-12-26', '2017-01-16', 23, '', '2017-01-23', '2017-01-27', 5, 33, 'SEKOLAH TINGGI PARIWISATA SAHID\r\nJl. Kemiri Raya No.22\r\nPondok Cabe Tangerang Selatan Banten', 'jakarta-bandung-lembang-garut-pangandaran-jakarta', 2, 13500000, 0, 13500000, 0, '2016-12-26 19:17:23', 1, '0000-00-00 00:00:00', 0),
(24, 'Transport', 37, 27, '0027.11/XI/2016', '2016-11-30', '2016-12-31', 25, '', '2017-01-04', '2017-01-08', 5, 0, 'DEPAN ALFA MIDI PASAR KEMBANG RAWA BELONG - JAKARTA BARAT', 'WISATA 9 WALI / WALISONGO 5HARI', 1, 12500000, 0, 12000000, 0, '2016-12-31 20:33:07', 1, '0000-00-00 00:00:00', 0),
(27, 'Transport', 52, 30, '0030.01/I/2017', '2017-01-18', '2017-01-18', 31, '', '2017-01-19', '2017-01-19', 1, 0, 'Gatot subroto', 'Caringin - Bogor', 2, 2700000, 0, 2700000, 0, '2017-01-18 10:28:53', 1, '2017-01-19 01:59:21', 1),
(30, 'Transport', 55, 31, '0031.01/I/2017', '2017-01-10', '2017-01-10', 27, '', '2017-01-10', '2017-01-10', 1, 0, '', '', 2, 600000, 0, 600000, 0, '2017-01-19 01:56:46', 1, '0000-00-00 00:00:00', 0),
(31, 'Transport', 56, 32, '0032.02/II/2017', '2017-02-06', '2017-02-15', 32, '', '2017-02-18', '2017-02-18', 1, 31, 'Jl. Jend. Gatot Subroto, Senayan Kebayoran Baru, Jakarta Selatan', 'Taman Safari Indonesia', 0, 2250000, 0, 0, 0, '2017-02-06 15:09:48', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_armada`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_armada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_invoice_id` int(11) NOT NULL,
  `armada_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `helper_id` int(11) DEFAULT NULL,
  `hour_pick_up` varchar(100) NOT NULL,
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
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_armada_bookings`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_armada_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_invoice_armada_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `sales_invoice_armada_bookings`
--

INSERT INTO `sales_invoice_armada_bookings` (`id`, `sales_invoice_armada_id`, `booking_date`, `customer_name`, `destination`, `created_at`, `created_by`) VALUES
(3, 9, '2016-12-15', 'Sumantri', 'Bandung', '2016-12-28 21:06:40', 1),
(4, 8, '2016-12-14', 'Suhendar', 'test', '2016-12-28 21:08:23', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_costs`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_costs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_invoice_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data untuk tabel `sales_invoice_costs`
--

INSERT INTO `sales_invoice_costs` (`id`, `sales_invoice_id`, `description`, `cost`) VALUES
(10, 3, 'Biaya Penjemputan', 50000),
(11, 4, 'Biaya Penjemputan ke Tangerang', 60000),
(12, 8, 'Penjemputan Bandara Soekarwo', 80000),
(13, 9, 'Test', 1000),
(14, 10, 'Test', 1000),
(15, 11, 'Test', 1000),
(16, 12, 'Test', 1000),
(21, 14, 'Test', 1000),
(22, 16, 'Penjemputan ke Lokasi', 50000),
(23, 2, 'Biaya Bus', 90000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_details`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_invoice_id` int(11) NOT NULL,
  `armada_category_id` int(11) NOT NULL,
  `qty` int(3) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `days` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81 ;

--
-- Dumping data untuk tabel `sales_invoice_details`
--

INSERT INTO `sales_invoice_details` (`id`, `sales_invoice_id`, `armada_category_id`, `qty`, `description`, `price`, `days`) VALUES
(4, 1, 3, 1, 'Pembelian 3 Biji', 120000, 3),
(30, 3, 1, 3, 'Untuk 3 Hari', 1000000, 5),
(31, 3, 2, 1, 'Testing', 8000, 10),
(32, 4, 1, 3, '1 (satu) Unit HiAce Commuter 15 Seat\n3 (Tiga) hari ; Selasa - Kamis, 18 - 20 Oktober 2016\nDalam Kota Jakarta dan Tangerang', 1350000, 3),
(33, 4, 2, 1, '1 (satu) Unit HiAce Commuter 19 Seat 3 (Tiga) hari ; Selasa - Kamis, 18 - 20 Oktober 2016 Dalam Kota Jakarta dan Tangerang', 1800000, 3),
(34, 5, 1, 3, 'SSD', 2000, 3),
(35, 5, 2, 3, 'XXX', 1000, 3),
(36, 6, 3, 1, 'Pembelian 3 Biji', 120000, 3),
(37, 7, 1, 1, 'unit rent hi ace 15', 200000, 1),
(38, 8, 1, 1, 'Ace 15 selama 1 Hari', 800000, 1),
(42, 14, 2, 3, 'Test', 80000, 1),
(43, 15, 1, 1, 'test', 20000, 1),
(44, 16, 4, 1, 'Perjalanan Haji Mabrur', 5000000, 1),
(46, 17, 1, 1, 'PAKET TOUR JOGYA = 3H2M VIA KERETA API', 2000000, 3),
(47, 17, 1, 36, 'PAKET TOUR JOGYA = 3H2M VIA KERETA API', 2000000, 3),
(53, 18, 1, 24, 'PAKET TOUR JOGYA = 3H2M 72 PAX \nVIA KERETA API', 2000000, 3),
(57, 2, 4, 1, 'Testing2', 80000, 1),
(58, 19, 1, 24, 'PAKET TOUR JOGYA = 3H2M 72 PAX\nVIA KERETA API', 2000000, 3),
(59, 20, 4, 1, 'BEKASI-CITY TOUR JAKARTA-CIAWI BOGOR\nALL IN PACKED', 2500000, 1),
(60, 20, 5, 1, 'BEKASI-CITY TOUR JAKARTA-CIAWI BOGOR\nALL IN PACKED', 3500000, 1),
(61, 21, 4, 1, 'Camp Hulu Cai - SMK Tunas Jakasampurna Bekasi\nALL IN PACKED', 2500000, 1),
(62, 21, 5, 1, 'Camp Hulu Cai - SMK Tunas Jakasampurna Bekasi\nALL IN PACKED', 3500000, 1),
(64, 23, 4, 1, 'Medium Bus Luxury Class 33 seat\nTour 5 Hari ', 2700000, 5),
(66, 24, 4, 1, 'WISATA 9 WALI / WALISONGO 5HARI', 2500000, 5),
(68, 25, 1, 1, 'AIRPORT-SUBANG JAWA BARAT (DROP)', 2400000, 1),
(71, 26, 4, 1, 'jogja', 2500000, 5),
(75, 28, 1, 4, 'Overtime 2unit', 3000000, 1),
(76, 29, 1, 1, 'Overtime 2 unit', 300000, 1),
(77, 30, 1, 2, 'Overtime Hi-Ace 2 Unit', 300000, 1),
(78, 27, 5, 1, 'Bigbus 59seat', 2700000, 1),
(79, 22, 4, 1, 'Medium Bus Luxury 33 seat tanggal 28 – 29 Januari 2017 Lembang-Bandung', 2800000, 2),
(80, 31, 4, 1, 'Medium Bus Luxury', 2250000, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_expense`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_invoice_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `expense` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data untuk tabel `sales_invoice_expense`
--

INSERT INTO `sales_invoice_expense` (`id`, `sales_invoice_id`, `description`, `expense`, `created_by`, `created_at`) VALUES
(7, 3, 'Biaya Tambahan Bensin', 10000, 0, '0000-00-00 00:00:00'),
(8, 2, 'Biaya Perawatan', 80000, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_invoice_payments`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_invoice_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `percentage` double NOT NULL,
  `value` double NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data untuk tabel `sales_invoice_payments`
--

INSERT INTO `sales_invoice_payments` (`id`, `sales_invoice_id`, `account_id`, `payment_date`, `percentage`, `value`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 3, 0, '2016-11-07', 50, 13565000, 'Pembayaran Uang Muka', '2016-11-08 10:36:57', 1, '0000-00-00 00:00:00', 0),
(3, 3, 0, '2016-11-16', 50, 13565000, 'Pembayaran Pelunasan', '2016-11-08 11:07:04', 1, '0000-00-00 00:00:00', 0),
(4, 5, 0, '2016-11-11', 50, 4500, 'Pembayaran Pertama untuk DP', '2016-11-14 15:02:50', 1, '0000-00-00 00:00:00', 0),
(5, 7, 0, '2016-11-15', 50, 100000, 'Pembayaran DP Pertama sebesar 50%', '2016-11-16 09:18:19', 1, '0000-00-00 00:00:00', 0),
(6, 7, 0, '2016-11-15', 50, 100000, 'Pembayaran DP Pertama sebesar 50%', '2016-11-16 09:19:52', 1, '0000-00-00 00:00:00', 0),
(7, 8, 1, '2016-11-10', 50, 440000, 'DP Pembyaran', '2016-11-16 09:51:08', 1, '0000-00-00 00:00:00', 0),
(8, 9, 1, '2016-12-08', 50, 500, 'Confirm Payment from user', '2016-12-09 09:43:56', 1, '0000-00-00 00:00:00', 0),
(9, 10, 1, '2016-12-08', 50, 500, 'Confirm Payment from user', '2016-12-09 09:45:05', 1, '0000-00-00 00:00:00', 0),
(10, 11, 1, '2016-12-08', 50, 500, 'Confirm Payment from user', '2016-12-09 09:47:02', 1, '0000-00-00 00:00:00', 0),
(11, 12, 1, '2016-12-08', 50, 500, 'Confirm Payment from user', '2016-12-09 09:48:39', 1, '0000-00-00 00:00:00', 0),
(12, 13, 1, '2016-12-08', 50, 500, 'Confirm Payment from user', '2016-12-09 09:55:49', 1, '0000-00-00 00:00:00', 0),
(13, 14, 1, '2016-12-13', 50, 120500, 'Test', '2016-12-13 17:15:57', 1, '0000-00-00 00:00:00', 0),
(15, 18, 2, '2016-12-06', 35, 50400000, 'DP UANG MUKA PAKET TOUR 72 PAX', '2016-12-17 22:15:30', 1, '0000-00-00 00:00:00', 0),
(16, 18, 2, '2016-12-06', 34, 48960000, 'UANG MUKA TOUR J', '2016-12-17 22:40:03', 1, '0000-00-00 00:00:00', 0),
(25, 19, 2, '2016-12-06', 34.722222222222, 50000000, 'DP AWAL TOUR JOGJA SMPI AL-FALAAH', '2016-12-21 23:18:13', 1, '0000-00-00 00:00:00', 0),
(26, 20, 2, '2016-12-21', 100, 6000000, 'DP DROP-PICKUP SMK TUNAS JAKASAMPURNA 9 & 11 JANUARI 2017', '2016-12-21 23:58:15', 1, '0000-00-00 00:00:00', 0),
(27, 22, 1, '2016-12-22', 50, 2800000, 'DP 50%', '2016-12-26 19:20:07', 1, '0000-00-00 00:00:00', 0),
(28, 23, 1, '2016-12-26', 14.814814814815, 2000000, 'Payment blok', '2016-12-26 19:21:33', 1, '0000-00-00 00:00:00', 0),
(29, 24, 0, '2016-11-30', 32, 4000000, 'DP CASH MEDIUM BUS WISATA 9 WALI / WALISONGO 5HARI', '2016-12-31 20:33:56', 1, '0000-00-00 00:00:00', 0),
(30, 21, 1, '2017-01-06', 83.333333333333, 5000000, 'PEMBAYARAN DP JEMPUT', '2017-01-10 19:15:56', 1, '0000-00-00 00:00:00', 0),
(31, 21, 1, '2017-01-10', 16.666666666667, 1000000, 'PELUNASAN ', '2017-01-10 19:21:53', 1, '0000-00-00 00:00:00', 0),
(32, 24, 0, '2017-01-01', 64, 8000000, 'REMPONG', '2017-01-10 19:22:59', 1, '0000-00-00 00:00:00', 0),
(33, 30, 0, '2017-01-10', 100, 600000, 'Payment Overtime Cash Via Pengemudi 2 Unit Hiace', '2017-01-19 01:57:59', 1, '0000-00-00 00:00:00', 0),
(34, 27, 0, '2017-01-18', 100, 2700000, 'full payment', '2017-01-19 02:00:01', 1, '0000-00-00 00:00:00', 0),
(35, 23, 1, '2017-01-18', 85.185185185185, 11500000, 'PELUNASAN ', '2017-01-19 02:01:47', 1, '0000-00-00 00:00:00', 0),
(36, 22, 1, '2017-01-27', 50, 2800000, 'pelunasan', '2017-01-31 18:46:48', 2, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_orders`
--

CREATE TABLE IF NOT EXISTS `sales_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(15) NOT NULL DEFAULT 'Tour',
  `source` tinyint(1) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT '0',
  `number` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `due_date` date NOT NULL,
  `booking_from_date` date NOT NULL,
  `booking_to_date` date NOT NULL,
  `booking_total_days` int(3) NOT NULL,
  `total_passenger` int(6) NOT NULL,
  `pick_up_point` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_company_name` varchar(255) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone_number` varchar(18) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `total` double NOT NULL,
  `expense` double NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Dumping data untuk tabel `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `type`, `source`, `order_number`, `number`, `order_date`, `due_date`, `booking_from_date`, `booking_to_date`, `booking_total_days`, `total_passenger`, `pick_up_point`, `destination`, `customer_id`, `customer_company_name`, `customer_email`, `customer_phone_number`, `status`, `total`, `expense`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Tour', 0, 0, '-', '2016-11-02', '2016-11-10', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, '', '', '', 1, 0, 0, '2016-11-02 16:56:54', 1, '0000-00-00 00:00:00', 0),
(4, 'Tour', 0, 0, '-', '2016-11-04', '2016-11-11', '0000-00-00', '0000-00-00', 0, 0, '', '', 2, '', '', '', 1, 2780000, 50000, '2016-11-04 12:10:04', 1, '2016-11-04 12:59:17', 1),
(6, 'Tour', 0, 0, '-', '2016-11-07', '2016-11-26', '0000-00-00', '0000-00-00', 0, 0, '', '', 2, '', '', '', 1, 6000000, 50000, '2016-11-07 14:39:21', 1, '0000-00-00 00:00:00', 0),
(7, 'Tour', 0, 0, '-', '2016-12-04', '2016-12-10', '0000-00-00', '0000-00-00', 0, 0, '', '', 1, '', '', '', 1, 5850000, 60000, '2016-11-09 09:37:24', 1, '2016-11-09 09:39:23', 1),
(12, 'Tour', 1, 0, '', '2016-11-11', '2016-11-15', '2016-11-10', '2016-11-12', 0, 0, 'Test', 'Test', 6, '', 'hendarsyahss5@gmail.com', '08522208555', 1, 9000, 0, '2016-11-11 08:59:44', 6, '2016-11-14 11:54:53', 1),
(13, 'Tour', 0, 0, '0006.11/XI/2016', '2016-11-15', '2016-11-19', '0000-00-00', '0000-00-00', 0, 0, 'Testing', 'Testing', 1, '', '', '', 1, 200000, 0, '2016-11-15 16:40:03', 1, '0000-00-00 00:00:00', 0),
(14, 'Tour', 0, 0, '0007.11/XI/2016', '2016-11-09', '2016-11-17', '0000-00-00', '0000-00-00', 0, 0, 'Tangerang Km 10', 'Tangerang Km 10', 2, '', '', '', 1, 880000, 0, '2016-11-16 09:28:08', 1, '2016-11-16 09:29:35', 1),
(17, 'Tour', 1, 0, '', '2016-11-29', '0000-00-00', '2016-12-23', '2016-12-26', 0, 0, 'Mitra 10 Tangerang', 'Yogyakarta', 10, '', 'faky.bahri@gmail.com', '085213605352', 2, 0, 0, '2016-11-29 08:36:34', 10, '0000-00-00 00:00:00', 0),
(19, 'Tour', 1, 0, '', '2016-11-30', '0000-00-00', '2016-12-02', '2016-12-04', 0, 0, 'BANDARA SOEKARNO HATTA', 'CIATER', 12, '', 'luthansagroup@gmail.com', '+62896-86317299', 0, 0, 0, '2016-11-30 17:11:01', 12, '2016-12-01 00:12:52', 1),
(26, 'Tour', 0, 0, '0017.12/XII/2016', '2016-12-14', '2016-12-14', '2016-12-14', '2016-12-14', 1, 80, 'test', 'test', 1, '', '', '', 1, 20000, 0, '2016-12-14 14:21:30', 1, '0000-00-00 00:00:00', 0),
(29, 'Tour', 0, 0, '0020.12/XII/2016', '2016-12-15', '2016-12-15', '2016-12-15', '2016-12-15', 1, 0, 'Tangerang Karawaci ', 'Bandung', 19, '', '', '', 1, 5050000, 0, '2016-12-15 10:08:21', 1, '2016-12-15 10:14:44', 1),
(30, 'Tour', 0, 0, '0021.12/XII/2016', '2016-12-06', '2017-02-15', '2017-03-15', '2017-03-17', 3, 72, 'BINTARO-GAMBIR-ST. YOGYAKARTA-GAMBIR=BINTARO', 'CANDI BOROBUDUR =LAVA TOUR MERAPI-CANDI PRAMBANAN=MUSEUM DIRGANTARA-KRATON YOGYAKARTA-CITY TOUR YOGYAKARTA', 20, '', '', '', 1, 18000000, 0, '2016-12-17 21:45:40', 1, '2016-12-17 22:46:43', 1),
(31, 'Transport', 0, 0, '0022.12/XII/2016', '2016-12-21', '2017-01-02', '2017-01-09', '2017-01-09', 1, 75, 'Grand Galaxi City, Jl. Taman Agave Blok H3 No. 16, Bekasi 17147', 'MD. Animation (Jl. Setiabudi Selatan No. 7, Jakarta Selatan ( bus stand by) - Camp Hulu Cai, Jl. Babakan Ciaul (Jl. Veteran III Desa Cibedug, Ciawi Bogor) - DROP OFF', 21, '', '', '', 1, 6000000, 0, '2016-12-21 23:34:13', 1, '2016-12-21 23:56:27', 1),
(32, 'Transport', 0, 0, '0023.12/XII/2016', '2016-12-21', '2017-01-02', '2017-01-11', '2017-01-11', 1, 75, 'Camp Hulu Cai, Jl. Babakan Ciaul (Jl. Veteran III Desa Cibedug, Ciawi Bogor)', 'SMK Tunas Jakasampurna Bekasi', 21, '', '', '', 1, 6000000, 0, '2016-12-22 00:03:58', 1, '0000-00-00 00:00:00', 0),
(33, 'Transport', 0, 0, '0024.12/XII/2016', '2016-12-22', '2017-01-23', '2017-01-28', '2017-01-29', 2, 33, 'WTC Matahari, Jl Raya Serpong No 39, Tangerang Selatan', 'Jl. Kol Masturi Lembang - Bandung', 22, '', '', '', 1, 5600000, 0, '2016-12-24 23:12:57', 1, '2016-12-24 23:15:33', 1),
(35, 'Transport', 0, 0, '0026.12/XII/2016', '2016-12-26', '2017-01-16', '2017-01-23', '2017-01-27', 5, 33, 'SEKOLAH TINGGI PARIWISATA SAHID\r\nJl. Kemiri Raya No.22\r\nPondok Cabe Tangerang Selatan Banten', 'jakarta-bandung-lembang-garut-pangandaran-jakarta', 23, '', '', '', 1, 13500000, 0, '2016-12-26 19:14:52', 1, '0000-00-00 00:00:00', 0),
(37, 'Transport', 0, 27, '0027.11/XI/2016', '2016-11-30', '2016-12-31', '2017-01-04', '2017-01-08', 5, 0, 'DEPAN ALFA MIDI PASAR KEMBANG RAWA BELONG - JAKARTA BARAT', 'WISATA 9 WALI / WALISONGO 5HARI', 25, '', '', '', 1, 12500000, 0, '2016-12-31 20:32:56', 1, '0000-00-00 00:00:00', 0),
(52, 'Transport', 0, 30, '0030.01/I/2017', '2017-01-18', '2017-01-18', '2017-01-19', '2017-01-19', 1, 0, 'Gatot subroto', 'Caringin - Bogor', 31, '', '', '', 1, 2700000, 0, '2017-01-18 10:26:33', 1, '2017-01-18 10:28:19', 1),
(55, 'Transport', 0, 31, '0031.01/I/2017', '2017-01-10', '2017-01-10', '2017-01-10', '2017-01-10', 1, 0, '', '', 27, '', '', '', 1, 600000, 0, '2017-01-19 01:56:20', 1, '0000-00-00 00:00:00', 0),
(56, 'Transport', 0, 32, '0032.02/II/2017', '2017-02-06', '2017-02-15', '2017-02-18', '2017-02-18', 1, 31, 'Jl. Jend. Gatot Subroto, Senayan Kebayoran Baru, Jakarta Selatan', 'Taman Safari Indonesia', 32, '', '', '', 1, 2250000, 0, '2017-02-06 15:05:43', 1, '2017-02-06 15:09:35', 1),
(57, 'Tour', 1, 0, '', '2017-02-13', '0000-00-00', '2017-02-13', '2017-02-14', 2, 2, 'Jl Kebayoran Baru no.90', 'Test', 33, 'OJK', 'hendarsyahss@gmail.com', '085222054064', 2, 0, 0, '2017-02-13 03:03:18', 33, '0000-00-00 00:00:00', 0),
(58, 'Tour', 1, 0, '', '2017-02-13', '0000-00-00', '2017-02-13', '2017-02-14', 2, 12, 'Jl Kebayoran Baru no.90', 'Test', 33, 'OJK', 'hendarsyahss@gmail.com', '085222054064', 2, 0, 0, '2017-02-13 03:04:45', 33, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_order_confirm_payments`
--

CREATE TABLE IF NOT EXISTS `sales_order_confirm_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `account_id` int(11) NOT NULL,
  `total_bill` double NOT NULL,
  `total_payment` double NOT NULL,
  `balanced` double NOT NULL,
  `from_account_no` varchar(100) NOT NULL,
  `from_account_name` varchar(100) NOT NULL,
  `from_bank_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `sales_order_confirm_payments`
--

INSERT INTO `sales_order_confirm_payments` (`id`, `sales_order_id`, `payment_date`, `account_id`, `total_bill`, `total_payment`, `balanced`, `from_account_no`, `from_account_name`, `from_bank_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 15, '2016-12-08', 1, 1000, 500, 500, '1238900000', 'Suhendar', 'BCA', 1, '2016-12-08 08:53:26', '2016-12-08 09:34:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_order_costs`
--

CREATE TABLE IF NOT EXISTS `sales_order_costs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data untuk tabel `sales_order_costs`
--

INSERT INTO `sales_order_costs` (`id`, `sales_order_id`, `description`, `cost`) VALUES
(3, 4, 'Biaya Penjemputan Ke Tangerang', 50000),
(5, 6, 'Biaya Penjemputan', 50000),
(6, 7, 'Biaya Penjemputan ke Tangerang', 60000),
(7, 14, 'Penjemputan Bandara Soekarwo', 80000),
(12, 29, 'Penjemputan ke Lokasi', 50000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_order_details`
--

CREATE TABLE IF NOT EXISTS `sales_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(11) NOT NULL,
  `armada_category_id` int(11) NOT NULL,
  `armada_category_name` varchar(255) NOT NULL,
  `armada_capacity` int(3) NOT NULL,
  `qty` int(3) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `days` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Dumping data untuk tabel `sales_order_details`
--

INSERT INTO `sales_order_details` (`id`, `sales_order_id`, `armada_category_id`, `armada_category_name`, `armada_capacity`, `qty`, `description`, `price`, `days`) VALUES
(1, 1, 3, '', 0, 1, 'Pembelian 3 Biji', 120000, 3),
(5, 4, 4, '', 0, 0, 'Jakarta Bandung 3 Hari ', 900000, 3),
(6, 4, 6, '', 0, 0, 'Testing', 80000, 1),
(9, 6, 1, '', 0, 3, 'Untuk 3 Hari', 1000000, 5),
(10, 6, 1, '', 0, 3, 'Untuk 3 Hari', 1000000, 3),
(12, 7, 1, '', 0, 3, '-', 1350000, 3),
(13, 7, 2, '', 0, 1, '1 (satu) Unit HiAce Commuter 19 Seat 3 (Tiga) hari ; Selasa - Kamis, 18 - 20 Oktober 2016 Dalam Kota Jakarta dan Tangerang', 1800000, 3),
(19, 12, 1, '', 0, 3, 'SSD', 2000, 3),
(20, 12, 2, '', 0, 3, 'XXX', 1000, 3),
(21, 13, 1, '', 0, 1, 'unit rent hi ace 15', 200000, 1),
(23, 14, 1, '', 0, 1, 'Ace 15 selama 1 Hari', 800000, 1),
(25, 17, 1, 'Commuter Hi-Ace', 15, 1, '', 0, 4),
(30, 19, 2, 'ELF Long 19 Seats', 19, 1, '-', 0, 3),
(38, 26, 1, '', 0, 1, 'test', 20000, 1),
(39, 29, 4, '', 0, 1, 'Perjalanan Haji Mabrur', 5000000, 1),
(45, 30, 1, '', 0, 3, 'PAKET TOUR JOGYA = 3H2M VIA KERETA API', 2000000, 3),
(55, 31, 4, '', 0, 1, 'BEKASI-CITY TOUR JAKARTA-CIAWI BOGOR\nALL IN PACKED', 2500000, 1),
(56, 31, 5, '', 0, 1, 'BEKASI-CITY TOUR JAKARTA-CIAWI BOGOR\nALL IN PACKED', 3500000, 1),
(57, 32, 4, '', 0, 1, 'Camp Hulu Cai - SMK Tunas Jakasampurna Bekasi\nALL IN PACKED', 2500000, 1),
(58, 32, 5, '', 0, 1, 'Camp Hulu Cai - SMK Tunas Jakasampurna Bekasi\nALL IN PACKED', 3500000, 1),
(61, 33, 4, '', 0, 1, 'Medium Bus Luxury 33 seat tanggal 28 – 29 Januari 2017 Lembang-Bandung', 2800000, 2),
(65, 35, 4, '', 0, 1, 'Medium Bus Luxury Class 33 seat\nTour 5 Hari ', 2700000, 5),
(67, 37, 4, '', 0, 1, 'WISATA 9 WALI / WALISONGO 5HARI', 2500000, 5),
(83, 52, 5, '', 0, 1, 'Bigbus 59seat', 2700000, 1),
(90, 55, 1, '', 0, 2, 'Overtime Hi-Ace 2 Unit', 300000, 1),
(92, 56, 4, '', 0, 1, 'Medium Bus Luxury', 2250000, 1),
(93, 58, 1, 'Commuter Hi-Ace 15 Seats', 15, 1, '', 0, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `int` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`int`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`int`, `key`, `value`) VALUES
(1, 'company_address', 'Jln. Pondok Randu Raya No.8 Duri Kosambi, Cengkareng'),
(2, 'company_city', 'Jakarta Barat'),
(3, 'company_zip_code', '11750'),
(4, 'company_telephone_number', '021-2972 5264'),
(5, 'company_email', 'office@luthansa.co.id  -  luthansagroup@gmail.com'),
(6, 'company_website', 'www.luthansa.co.id'),
(7, 'company_name', 'PT Anther Prima Persada'),
(8, 'company_signature_name', 'Luthansa Group'),
(9, 'invoice_starting_number', '1'),
(10, 'invoice_days_notification_due_date', '1'),
(11, 'invoice_email_notifications', 'fahri@luthansa.co.id;hendarsyahss@gmail.com;');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `user_group_id`, `first_name`, `last_name`, `email`, `password`, `remember_token`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 'Admin', ' Luthansa', 'it@luthansa.co.id', '$2y$10$qlTCAH24OXWWnx0ljJ9Ne.Iq2mdExS.FsR4bfbMz6eO.SlyvW9/Ai', 'EcunUdl7eRyETVEQ4VTJE13VVMVcF8C3tOcJziZbETKBWWzb73MerdOOEbkv', 1, '2016-09-01 00:00:00', 1, '2016-12-29 17:17:10', 1),
(2, 1, 'Faky', 'Bahri', 'fahri@luthansa.co.id', '$2y$10$D9Bq6.rycdPaU9iLv/XzL.XY6uAMxh/32Dx9WTneWzrsM0ii4xqgC', '0NFP8ljQsxzpeHGLP34c3O9bcLQ58iC8SG5jfXeh0vYUDeevWy6FOiIdl1n8', 1, '2016-11-01 09:03:09', NULL, '2016-12-02 01:23:38', 0),
(3, 2, 'Endang', 'Jumiyati', 'operational@luthansa.co.id', '$2y$10$q37DRcbZECaglY4Y9TRkJ.nAg/t11UqVnL.x9b.jhPKPu7d58j1Na', 'sEtNLCM46s4NDH7oLtRUvHIYyowFd8R7MPaJc6NQ7ka6lQf0rGvSTQFR1t4l', 1, '2016-11-27 22:13:29', 1, '2016-12-03 08:15:34', 1),
(4, 3, 'Luther', 'Halim', 'luther@luthansa.co.id', '$2y$10$7nw/h/KUQGw8DwwYyvHMmuDeuFQRB0S6bIl9vaAtODqhc2LUqYZFa', 'K2cGjaTMKKjjlEINbHRjIUWiO2GNHQJc1kEyodrZBKo6qktghpcpuwCmC47h', 1, '2016-12-02 01:24:22', 1, '2016-12-03 09:49:14', 0),
(5, 4, 'ANDI', 'PRIHANTORO', 'andhie@luthansa.co.id', '$2y$10$R1x5ey0O5o8vLUgPz39ALejHnPUPjBJb7badqEc9PMqtYpv.UTlfm', 'omSHNAtgRlAMCe7S6LnqKfeLMe9Nv7fiVbEjEzUxImr7Z3QHZZQA7lnO724f', 1, '2016-12-02 01:25:46', 1, '2016-12-03 10:02:33', 1),
(6, 1, 'Suhendar', 'Suhendar', 'hendarsyahss@gmail.com', '$2y$10$ii3ZIVSghaayYqwhw3RjnOMVRlb/PTWvvY4fbLdGFcq/0MQcRfoqS', '', 1, '2016-12-09 14:38:05', 1, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `user_groups`
--

INSERT INTO `user_groups` (`id`, `name`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Administrator', 1, '2016-11-26 05:00:00', 1, '0000-00-00 00:00:00', 0),
(2, 'Operational', 1, '2016-12-03 08:15:03', 1, '2016-12-03 09:59:41', 1),
(3, 'Office', 1, '2016-12-03 09:48:06', 1, '2016-12-04 10:49:31', 1),
(4, 'Sales', 1, '2016-12-03 10:00:53', 1, '2016-12-03 14:55:27', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_group_modules`
--

CREATE TABLE IF NOT EXISTS `user_group_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `module_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `access` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=325 ;

--
-- Dumping data untuk tabel `user_group_modules`
--

INSERT INTO `user_group_modules` (`id`, `user_group_id`, `module_slug`, `access`) VALUES
(190, 2, 'armada', 'd'),
(189, 2, 'armada', 'u'),
(188, 2, 'armada', 'c'),
(187, 2, 'armada', 'r'),
(186, 2, 'armada-category', 'r'),
(185, 2, 'profile', 'd'),
(184, 2, 'profile', 'u'),
(183, 2, 'profile', 'c'),
(182, 2, 'profile', 'r'),
(181, 2, 'employee', 'd'),
(180, 2, 'employee', 'u'),
(179, 2, 'employee', 'c'),
(178, 2, 'employee', 'r'),
(177, 2, 'customer', 'd'),
(176, 2, 'customer', 'u'),
(175, 2, 'customer', 'c'),
(174, 2, 'customer', 'r'),
(173, 2, 'department', 'd'),
(172, 2, 'department', 'u'),
(171, 2, 'department', 'c'),
(170, 2, 'department', 'r'),
(323, 3, 'sales-order', 'u'),
(322, 3, 'sales-order', 'c'),
(321, 3, 'sales-order', 'r'),
(320, 3, 'company', 'd'),
(319, 3, 'company', 'u'),
(318, 3, 'company', 'c'),
(317, 3, 'company', 'r'),
(316, 3, 'sales-invoice', 'd'),
(315, 3, 'sales-invoice', 'u'),
(314, 3, 'sales-invoice', 'c'),
(313, 3, 'sales-invoice', 'r'),
(312, 3, 'report-sales-summary', 'd'),
(311, 3, 'report-sales-summary', 'u'),
(310, 3, 'report-sales-summary', 'c'),
(309, 3, 'report-sales-summary', 'r'),
(308, 3, 'sales-spj', 'd'),
(307, 3, 'sales-spj', 'u'),
(306, 3, 'sales-spj', 'c'),
(305, 3, 'sales-spj', 'r'),
(304, 3, 'armada', 'd'),
(303, 3, 'armada', 'u'),
(302, 3, 'armada', 'c'),
(301, 3, 'armada', 'r'),
(300, 3, 'armada-category', 'd'),
(299, 3, 'armada-category', 'u'),
(298, 3, 'armada-category', 'c'),
(297, 3, 'armada-category', 'r'),
(296, 3, 'profile', 'd'),
(295, 3, 'profile', 'u'),
(294, 3, 'profile', 'c'),
(293, 3, 'profile', 'r'),
(292, 3, 'session', 'd'),
(291, 3, 'session', 'u'),
(290, 3, 'session', 'c'),
(289, 3, 'session', 'r'),
(288, 3, 'employee', 'd'),
(287, 3, 'employee', 'u'),
(286, 3, 'employee', 'c'),
(285, 3, 'employee', 'r'),
(191, 2, 'sales-spj', 'r'),
(192, 2, 'sales-spj', 'c'),
(193, 2, 'sales-spj', 'u'),
(194, 2, 'sales-spj', 'd'),
(195, 2, 'report-sales-summary', 'r'),
(196, 2, 'company', 'r'),
(218, 4, 'sales-invoice', 'c'),
(217, 4, 'sales-invoice', 'r'),
(216, 4, 'report-sales-summary', 'd'),
(215, 4, 'report-sales-summary', 'u'),
(214, 4, 'report-sales-summary', 'c'),
(213, 4, 'report-sales-summary', 'r'),
(212, 4, 'profile', 'd'),
(211, 4, 'profile', 'u'),
(210, 4, 'profile', 'c'),
(209, 4, 'profile', 'r'),
(219, 4, 'sales-invoice', 'u'),
(220, 4, 'sales-invoice', 'd'),
(221, 4, 'sales-order', 'r'),
(222, 4, 'sales-order', 'c'),
(223, 4, 'sales-order', 'u'),
(224, 4, 'sales-order', 'd'),
(284, 3, 'customer', 'd'),
(283, 3, 'customer', 'u'),
(282, 3, 'customer', 'c'),
(281, 3, 'customer', 'r'),
(280, 3, 'department', 'd'),
(279, 3, 'department', 'u'),
(278, 3, 'department', 'c'),
(277, 3, 'department', 'r'),
(324, 3, 'sales-order', 'd');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

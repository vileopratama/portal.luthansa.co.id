-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 28 Des 2016 pada 11.13
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
-- Struktur dari tabel `sales_invoice_armada_bookings`
--

CREATE TABLE `sales_invoice_armada_bookings` (
  `id` int(11) NOT NULL,
  `sales_invoice_armada_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_invoice_armada_bookings`
--

INSERT INTO `sales_invoice_armada_bookings` (`id`, `sales_invoice_armada_id`, `booking_date`, `customer_name`, `destination`, `created_at`, `created_by`) VALUES
(18, 11, '2016-11-18', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(19, 11, '2016-11-19', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(20, 11, '2016-11-20', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(21, 11, '2016-11-21', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(22, 11, '2016-11-22', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(23, 11, '2016-11-23', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(24, 11, '2016-11-24', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(25, 11, '2016-11-25', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:57', 1),
(26, 11, '2016-11-26', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(27, 11, '2016-11-27', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(28, 11, '2016-11-28', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(29, 11, '2016-11-29', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(30, 11, '2016-11-30', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(31, 11, '2016-12-01', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(32, 11, '2016-12-02', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(33, 11, '2016-12-03', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(34, 11, '2016-12-04', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(35, 11, '2016-12-05', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(36, 11, '2016-12-06', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(37, 11, '2016-12-07', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(38, 11, '2016-12-08', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(39, 11, '2016-12-09', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(40, 11, '2016-12-10', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(41, 11, '2016-12-11', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(42, 11, '2016-12-12', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(43, 11, '2016-12-13', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(44, 11, '2016-12-14', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(45, 11, '2016-12-15', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(46, 11, '2016-12-16', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(47, 11, '2016-12-17', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(48, 11, '2016-12-18', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(49, 11, '2016-12-19', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(50, 11, '2016-12-20', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(51, 11, '2016-12-21', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(52, 11, '2016-12-22', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(53, 11, '2016-12-23', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:15:58', 1),
(54, 13, '2016-11-18', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(55, 13, '2016-11-19', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(56, 13, '2016-11-20', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(57, 13, '2016-11-21', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(58, 13, '2016-11-22', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(59, 13, '2016-11-23', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(60, 13, '2016-11-24', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(61, 13, '2016-11-25', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(62, 13, '2016-11-26', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(63, 13, '2016-11-27', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(64, 13, '2016-11-28', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(65, 13, '2016-11-29', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(66, 13, '2016-11-30', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(67, 13, '2016-12-01', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(68, 13, '2016-12-02', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(69, 13, '2016-12-03', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(70, 13, '2016-12-04', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(71, 13, '2016-12-05', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(72, 13, '2016-12-06', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(73, 13, '2016-12-07', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(74, 13, '2016-12-08', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(75, 13, '2016-12-09', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(76, 13, '2016-12-10', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(77, 13, '2016-12-11', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(78, 13, '2016-12-12', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(79, 13, '2016-12-13', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(80, 13, '2016-12-14', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(81, 13, '2016-12-15', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(82, 13, '2016-12-16', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(83, 13, '2016-12-17', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(84, 13, '2016-12-18', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(85, 13, '2016-12-19', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(86, 13, '2016-12-20', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(87, 13, '2016-12-21', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(88, 13, '2016-12-22', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1),
(89, 13, '2016-12-23', 'PT One Piece Sekatung', 'Kalideres Tangerang', '2016-12-28 14:16:37', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sales_invoice_armada_bookings`
--
ALTER TABLE `sales_invoice_armada_bookings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sales_invoice_armada_bookings`
--
ALTER TABLE `sales_invoice_armada_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

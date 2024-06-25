-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2024 at 06:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_badminton`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `tambah_customer` (IN `nama_customer` VARCHAR(100), IN `kontak_customer` VARCHAR(20), IN `id_membership` INT)   BEGIN
    INSERT INTO t_customer (nama_customer, kontak_customer, id_membership)
    VALUES (nama_customer, kontak_customer, id_membership);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_barang`
--

CREATE TABLE `t_barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `stok_barang` int(11) DEFAULT NULL,
  `harga_barang` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_barang`
--

INSERT INTO `t_barang` (`id_barang`, `nama_barang`, `stok_barang`, `harga_barang`) VALUES
(1, 'Shuttlecock', 73, 10000.00),
(2, 'Raket Badminton', 15, 15000.00),
(3, 'Aqua Botol', 96, 5000.00),
(4, 'Teh Pucuk', 100, 7000.00),
(5, 'Chiki Chitato', 75, 7000.00),
(6, 'Sariroti', 74, 8000.00);

-- --------------------------------------------------------

--
-- Table structure for table `t_booking`
--

CREATE TABLE `t_booking` (
  `id_booking` int(11) NOT NULL,
  `tanggal_booking` date DEFAULT NULL,
  `status_booking` varchar(20) DEFAULT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `id_voucher` int(11) DEFAULT 3,
  `id_fasilitas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_booking`
--

INSERT INTO `t_booking` (`id_booking`, `tanggal_booking`, `status_booking`, `id_customer`, `id_jadwal`, `id_voucher`, `id_fasilitas`) VALUES
(1, '2024-06-13', 'Berhasil', 5, 57, 1, 3),
(2, '2024-06-13', 'Berhasil', 6, 88, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_customer`
--

CREATE TABLE `t_customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `kontak_customer` varchar(20) DEFAULT NULL,
  `id_membership` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_customer`
--

INSERT INTO `t_customer` (`id_customer`, `nama_customer`, `kontak_customer`, `id_membership`) VALUES
(5, 'Bening', '082122324658', 1),
(6, 'Marco', '088211622514', 1),
(7, 'Jule', '081322503073', 1),
(8, 'Aslam', '0895324690365', 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_extra`
--

CREATE TABLE `t_extra` (
  `id_extra` int(11) NOT NULL,
  `kuantitas_extra` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_booking` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_extra`
--

INSERT INTO `t_extra` (`id_extra`, `kuantitas_extra`, `id_barang`, `id_booking`) VALUES
(1, 2, 1, 1),
(2, 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `t_fasilitas`
--

CREATE TABLE `t_fasilitas` (
  `id_fasilitas` int(11) NOT NULL,
  `nama_fasilitas` varchar(100) DEFAULT NULL,
  `deskripsi_fasilitas` varchar(255) DEFAULT NULL,
  `harga_fasilitas` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_fasilitas`
--

INSERT INTO `t_fasilitas` (`id_fasilitas`, `nama_fasilitas`, `deskripsi_fasilitas`, `harga_fasilitas`) VALUES
(1, 'Lapang 1', 'Sewa lapangan 1 badminton untuk 1 jam', 40000.00),
(2, 'Lapang 2', 'Sewa lapangan 2 badminton untuk 1 jam', 40000.00),
(3, 'Lapang 3', 'Sewa lapangan 3 badminton untuk 1 jam', 40000.00);

-- --------------------------------------------------------

--
-- Table structure for table `t_jadwal`
--

CREATE TABLE `t_jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `id_tanggal` int(11) DEFAULT NULL,
  `id_waktu` int(11) DEFAULT NULL,
  `id_fasilitas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_jadwal`
--

INSERT INTO `t_jadwal` (`id_jadwal`, `status`, `id_tanggal`, `id_waktu`, `id_fasilitas`) VALUES
(1, 0, 1, 1, 1),
(2, 0, 1, 1, 2),
(3, 0, 1, 1, 3),
(4, 0, 1, 2, 1),
(5, 0, 1, 2, 2),
(6, 0, 1, 2, 3),
(7, 0, 1, 3, 1),
(8, 0, 1, 3, 2),
(9, 0, 1, 3, 3),
(10, 0, 1, 4, 1),
(11, 0, 1, 4, 2),
(12, 0, 1, 4, 3),
(13, 0, 1, 5, 1),
(14, 0, 1, 5, 2),
(15, 0, 1, 5, 3),
(16, 0, 1, 6, 1),
(17, 0, 1, 6, 2),
(18, 0, 1, 6, 3),
(19, 0, 1, 7, 1),
(20, 0, 1, 7, 2),
(21, 0, 1, 7, 3),
(22, 0, 1, 8, 1),
(23, 0, 1, 8, 2),
(24, 0, 1, 8, 3),
(25, 0, 1, 9, 1),
(26, 0, 1, 9, 2),
(27, 0, 1, 9, 3),
(28, 0, 1, 10, 1),
(29, 0, 1, 10, 2),
(30, 0, 1, 10, 3),
(31, 0, 1, 11, 1),
(32, 0, 1, 11, 2),
(33, 0, 1, 11, 3),
(34, 0, 1, 12, 1),
(35, 0, 1, 12, 2),
(36, 0, 1, 12, 3),
(37, 0, 1, 13, 1),
(38, 0, 1, 13, 2),
(39, 0, 1, 13, 3),
(40, 0, 1, 14, 1),
(41, 0, 1, 14, 2),
(42, 0, 1, 14, 3),
(43, 0, 1, 15, 1),
(44, 0, 1, 15, 2),
(45, 0, 1, 15, 3),
(46, 0, 2, 1, 1),
(47, 0, 2, 1, 2),
(48, 0, 2, 1, 3),
(49, 0, 2, 2, 1),
(50, 0, 2, 2, 2),
(51, 0, 2, 2, 3),
(52, 0, 2, 3, 1),
(53, 0, 2, 3, 2),
(54, 0, 2, 3, 3),
(55, 0, 2, 4, 1),
(56, 0, 2, 4, 2),
(57, 1, 2, 4, 3),
(58, 0, 2, 5, 1),
(59, 0, 2, 5, 2),
(60, 0, 2, 5, 3),
(61, 0, 2, 6, 1),
(62, 0, 2, 6, 2),
(63, 0, 2, 6, 3),
(64, 0, 2, 7, 1),
(65, 0, 2, 7, 2),
(66, 0, 2, 7, 3),
(67, 0, 2, 8, 1),
(68, 0, 2, 8, 2),
(69, 0, 2, 8, 3),
(70, 0, 2, 9, 1),
(71, 0, 2, 9, 2),
(72, 0, 2, 9, 3),
(73, 0, 2, 10, 1),
(74, 0, 2, 10, 2),
(75, 0, 2, 10, 3),
(76, 0, 2, 11, 1),
(77, 0, 2, 11, 2),
(78, 0, 2, 11, 3),
(79, 0, 2, 12, 1),
(80, 0, 2, 12, 2),
(81, 0, 2, 12, 3),
(82, 0, 2, 13, 1),
(83, 0, 2, 13, 2),
(84, 0, 2, 13, 3),
(85, 0, 2, 14, 1),
(86, 0, 2, 14, 2),
(87, 0, 2, 14, 3),
(88, 1, 2, 15, 1),
(89, 0, 2, 15, 2),
(90, 0, 2, 15, 3),
(91, 0, 3, 1, 1),
(92, 0, 3, 1, 2),
(93, 0, 3, 1, 3),
(94, 0, 3, 2, 1),
(95, 0, 3, 2, 2),
(96, 0, 3, 2, 3),
(97, 0, 3, 3, 1),
(98, 0, 3, 3, 2),
(99, 0, 3, 3, 3),
(100, 0, 3, 4, 1),
(101, 0, 3, 4, 2),
(102, 0, 3, 4, 3),
(103, 0, 3, 5, 1),
(104, 0, 3, 5, 2),
(105, 0, 3, 5, 3),
(106, 0, 3, 6, 1),
(107, 0, 3, 6, 2),
(108, 0, 3, 6, 3),
(109, 0, 3, 7, 1),
(110, 0, 3, 7, 2),
(111, 0, 3, 7, 3),
(112, 0, 3, 8, 1),
(113, 0, 3, 8, 2),
(114, 0, 3, 8, 3),
(115, 0, 3, 9, 1),
(116, 0, 3, 9, 2),
(117, 0, 3, 9, 3),
(118, 0, 3, 10, 1),
(119, 0, 3, 10, 2),
(120, 0, 3, 10, 3),
(121, 0, 3, 11, 1),
(122, 0, 3, 11, 2),
(123, 0, 3, 11, 3),
(124, 0, 3, 12, 1),
(125, 0, 3, 12, 2),
(126, 0, 3, 12, 3),
(127, 0, 3, 13, 1),
(128, 0, 3, 13, 2),
(129, 0, 3, 13, 3),
(130, 0, 3, 14, 1),
(131, 0, 3, 14, 2),
(132, 0, 3, 14, 3),
(133, 0, 3, 15, 1),
(134, 0, 3, 15, 2),
(135, 0, 3, 15, 3),
(136, 0, 4, 1, 1),
(137, 0, 4, 1, 2),
(138, 0, 4, 1, 3),
(139, 0, 4, 2, 1),
(140, 0, 4, 2, 2),
(141, 0, 4, 2, 3),
(142, 0, 4, 3, 1),
(143, 0, 4, 3, 2),
(144, 0, 4, 3, 3),
(145, 0, 4, 4, 1),
(146, 0, 4, 4, 2),
(147, 0, 4, 4, 3),
(148, 0, 4, 5, 1),
(149, 0, 4, 5, 2),
(150, 0, 4, 5, 3),
(151, 0, 4, 6, 1),
(152, 0, 4, 6, 2),
(153, 0, 4, 6, 3),
(154, 0, 4, 7, 1),
(155, 0, 4, 7, 2),
(156, 0, 4, 7, 3),
(157, 0, 4, 8, 1),
(158, 0, 4, 8, 2),
(159, 0, 4, 8, 3),
(160, 0, 4, 9, 1),
(161, 0, 4, 9, 2),
(162, 0, 4, 9, 3),
(163, 0, 4, 10, 1),
(164, 0, 4, 10, 2),
(165, 0, 4, 10, 3),
(166, 0, 4, 11, 1),
(167, 0, 4, 11, 2),
(168, 0, 4, 11, 3),
(169, 0, 4, 12, 1),
(170, 0, 4, 12, 2),
(171, 0, 4, 12, 3),
(172, 0, 4, 13, 1),
(173, 0, 4, 13, 2),
(174, 0, 4, 13, 3),
(175, 0, 4, 14, 1),
(176, 0, 4, 14, 2),
(177, 0, 4, 14, 3),
(178, 0, 4, 15, 1),
(179, 0, 4, 15, 2),
(180, 0, 4, 15, 3),
(181, 0, 5, 1, 1),
(182, 0, 5, 1, 2),
(183, 0, 5, 1, 3),
(184, 0, 5, 2, 1),
(185, 0, 5, 2, 2),
(186, 0, 5, 2, 3),
(187, 0, 5, 3, 1),
(188, 0, 5, 3, 2),
(189, 0, 5, 3, 3),
(190, 0, 5, 4, 1),
(191, 0, 5, 4, 2),
(192, 0, 5, 4, 3),
(193, 0, 5, 5, 1),
(194, 0, 5, 5, 2),
(195, 0, 5, 5, 3),
(196, 0, 5, 6, 1),
(197, 0, 5, 6, 2),
(198, 0, 5, 6, 3),
(199, 0, 5, 7, 1),
(200, 0, 5, 7, 2),
(201, 0, 5, 7, 3),
(202, 0, 5, 8, 1),
(203, 0, 5, 8, 2),
(204, 0, 5, 8, 3),
(205, 0, 5, 9, 1),
(206, 0, 5, 9, 2),
(207, 0, 5, 9, 3),
(208, 0, 5, 10, 1),
(209, 0, 5, 10, 2),
(210, 0, 5, 10, 3),
(211, 0, 5, 11, 1),
(212, 0, 5, 11, 2),
(213, 0, 5, 11, 3),
(214, 0, 5, 12, 1),
(215, 0, 5, 12, 2),
(216, 0, 5, 12, 3),
(217, 0, 5, 13, 1),
(218, 0, 5, 13, 2),
(219, 0, 5, 13, 3),
(220, 0, 5, 14, 1),
(221, 0, 5, 14, 2),
(222, 0, 5, 14, 3),
(223, 0, 5, 15, 1),
(224, 0, 5, 15, 2),
(225, 0, 5, 15, 3);

-- --------------------------------------------------------

--
-- Table structure for table `t_membership`
--

CREATE TABLE `t_membership` (
  `id_membership` int(11) NOT NULL,
  `jenis_membership` varchar(50) DEFAULT NULL,
  `harga_membership` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_membership`
--

INSERT INTO `t_membership` (`id_membership`, `jenis_membership`, `harga_membership`) VALUES
(1, 'pelanggan-biasa', 0.00),
(2, 'member', 250000.00);

-- --------------------------------------------------------

--
-- Table structure for table `t_pembayaran`
--

CREATE TABLE `t_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `harga_total` decimal(10,2) DEFAULT NULL,
  `jenis_bayar` varchar(10) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `status_pembayaran` varchar(20) DEFAULT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `id_extra` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_pembayaran`
--

INSERT INTO `t_pembayaran` (`id_pembayaran`, `harga_total`, `jenis_bayar`, `tanggal_pembayaran`, `status_pembayaran`, `id_booking`, `id_extra`) VALUES
(1, 50000.00, 'Transfer', '2024-06-12', 'Lunas', 1, 1),
(2, 50000.00, 'Transfer', '2024-06-12', 'Lunas', 2, 2);

--
-- Triggers `t_pembayaran`
--
DELIMITER $$
CREATE TRIGGER `menghitung_total_pembayaran` BEFORE INSERT ON `t_pembayaran` FOR EACH ROW BEGIN
    DECLARE total_harga_fasilitas DECIMAL(10,2);  
    DECLARE total_harga_extra DECIMAL(10,2);
    DECLARE harga_setelah_potongan DECIMAL(10,2);

    -- Menghitung total harga dari fasilitas pada booking
    SELECT SUM(f.harga_fasilitas)
    INTO total_harga_fasilitas
    FROM t_fasilitas f
    INNER JOIN t_booking b ON b.id_fasilitas = f.id_fasilitas
    WHERE b.id_booking = NEW.id_booking;

    -- Menghitung total harga dari extra pada booking
    IF NEW.id_extra IS NOT NULL THEN
        SELECT SUM(b.harga_barang * e.kuantitas_extra)
        INTO total_harga_extra
        FROM t_barang b
        INNER JOIN t_extra e ON e.id_barang = b.id_barang
        WHERE e.id_extra = NEW.id_extra;
    ELSE
        SET total_harga_extra = 0;
    END IF;

    -- Mengambil potongan harga dari voucher jika ada
    SELECT potongan_harga
    INTO harga_setelah_potongan
    FROM t_voucher
    WHERE id_voucher = (SELECT id_voucher FROM t_booking WHERE id_booking = NEW.id_booking);

    -- Jika tidak ada potongan harga, set harga_setelah_potongan sama dengan total harga fasilitas
    IF harga_setelah_potongan IS NULL THEN
        SET harga_setelah_potongan = total_harga_fasilitas;
    ELSE
        -- Mengurangkan potongan harga dari total harga fasilitas
        SET harga_setelah_potongan = total_harga_fasilitas - harga_setelah_potongan;
    END IF;

    -- Menambahkan total harga extra ke harga yang telah dipotong
    SET NEW.harga_total = harga_setelah_potongan + total_harga_extra;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_status_belum_dibayar` BEFORE INSERT ON `t_pembayaran` FOR EACH ROW BEGIN
    -- Jika status_pembayaran bernilai NULL, maka set status_booking menjadi "Belum Dibayar"
    IF NEW.status_pembayaran IS NULL THEN
        SET NEW.status_pembayaran = 'Belum Dibayar';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_mengembalikan_stok_barang` AFTER DELETE ON `t_pembayaran` FOR EACH ROW BEGIN
    DECLARE id_barang INT;
    DECLARE kuantitas INT;

    -- Mengambil id_barang dan kuantitas dari tabel t_extra terkait booking
    SELECT id_barang, kuantitas_extra INTO id_barang, kuantitas
    FROM t_extra
    WHERE id_booking = OLD.id_booking;

    -- Menambah stok barang
    UPDATE t_barang
    SET stok_barang = stok_barang + kuantitas
    WHERE id_barang = id_barang;

    -- Hapus data t_extra terkait
    DELETE FROM t_extra
    WHERE id_booking = OLD.id_booking;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_mengurangi_stok_barang` AFTER INSERT ON `t_pembayaran` FOR EACH ROW BEGIN
    -- Mengurangi stok barang yang digunakan dalam extra
    IF NEW.id_extra IS NOT NULL THEN
        UPDATE t_barang
        SET stok_barang = stok_barang - (SELECT kuantitas_extra FROM t_extra WHERE id_extra = NEW.id_extra)
        WHERE id_barang = (SELECT id_barang FROM t_extra WHERE id_extra = NEW.id_extra);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ubah_status_pemesanan_setelah_pembayaran` AFTER UPDATE ON `t_pembayaran` FOR EACH ROW BEGIN
    IF NEW.status_pembayaran = 'Lunas' THEN
        UPDATE t_booking
        SET status_booking = 'Berhasil'
        WHERE id_booking = NEW.id_booking;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tanggal_pembayaran` BEFORE UPDATE ON `t_pembayaran` FOR EACH ROW BEGIN

    IF NEW.tanggal_pembayaran IS NULL THEN

        SET NEW.tanggal_pembayaran = CURDATE();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_tanggal`
--

CREATE TABLE `t_tanggal` (
  `id_tanggal` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_tanggal`
--

INSERT INTO `t_tanggal` (`id_tanggal`, `tanggal`) VALUES
(1, '2024-06-12'),
(2, '2024-06-13'),
(3, '2024-06-14'),
(4, '2024-06-15'),
(5, '2024-06-16');

--
-- Triggers `t_tanggal`
--
DELIMITER $$
CREATE TRIGGER `after_insert_t_tanggal` AFTER INSERT ON `t_tanggal` FOR EACH ROW BEGIN
  DECLARE i INT DEFAULT 1;
  DECLARE j INT DEFAULT 1;

  -- Insert 45 rows (15 time slots * 3 facilities)
  WHILE i <= 15 DO
    WHILE j <= 3 DO
      INSERT INTO t_jadwal (status, id_tanggal, id_waktu, id_fasilitas)
      VALUES (0, NEW.id_tanggal, i, j);
      SET j = j + 1;
    END WHILE;
    SET j = 1;
    SET i = i + 1;
  END WHILE;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_voucher`
--

CREATE TABLE `t_voucher` (
  `id_voucher` int(11) NOT NULL,
  `nama_voucher` varchar(50) DEFAULT NULL,
  `potongan_harga` decimal(10,2) DEFAULT NULL,
  `mm_berlaku` date DEFAULT NULL,
  `mm_selesai` date DEFAULT NULL,
  `deskripsi_voucher` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_voucher`
--

INSERT INTO `t_voucher` (`id_voucher`, `nama_voucher`, `potongan_harga`, `mm_berlaku`, `mm_selesai`, `deskripsi_voucher`) VALUES
(1, 'Voucher Diskon 10%', 10000.00, '2024-06-01', '2024-06-30', 'Diskon 10% untuk semua penyewaan selama bulan Juni 2024'),
(2, 'Voucher Diskon 20%', 20000.00, '2024-08-01', '2024-08-15', 'Diskon 20% perayaan ulang tahun anak pemilik'),
(3, 'Tidak Ada', 0.00, '2024-06-01', '2024-12-31', 'Tidak Ada');

-- --------------------------------------------------------

--
-- Table structure for table `t_waktu`
--

CREATE TABLE `t_waktu` (
  `id_waktu` int(11) NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_waktu`
--

INSERT INTO `t_waktu` (`id_waktu`, `waktu_mulai`, `waktu_selesai`) VALUES
(1, '07:00:00', '08:00:00'),
(2, '08:00:00', '09:00:00'),
(3, '09:00:00', '10:00:00'),
(4, '10:00:00', '11:00:00'),
(5, '11:00:00', '12:00:00'),
(6, '12:00:00', '13:00:00'),
(7, '13:00:00', '14:00:00'),
(8, '14:00:00', '15:00:00'),
(9, '15:00:00', '16:00:00'),
(10, '16:00:00', '17:00:00'),
(11, '17:00:00', '18:00:00'),
(12, '18:00:00', '19:00:00'),
(13, '19:00:00', '20:00:00'),
(14, '20:00:00', '21:00:00'),
(15, '21:00:00', '22:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_barang`
--
ALTER TABLE `t_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `t_booking`
--
ALTER TABLE `t_booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_voucher` (`id_voucher`),
  ADD KEY `id_fasilitas` (`id_fasilitas`);

--
-- Indexes for table `t_customer`
--
ALTER TABLE `t_customer`
  ADD PRIMARY KEY (`id_customer`),
  ADD KEY `id_membership` (`id_membership`);

--
-- Indexes for table `t_extra`
--
ALTER TABLE `t_extra`
  ADD PRIMARY KEY (`id_extra`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_booking` (`id_booking`);

--
-- Indexes for table `t_fasilitas`
--
ALTER TABLE `t_fasilitas`
  ADD PRIMARY KEY (`id_fasilitas`);

--
-- Indexes for table `t_jadwal`
--
ALTER TABLE `t_jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_tanggal` (`id_tanggal`),
  ADD KEY `id_waktu` (`id_waktu`),
  ADD KEY `fk_fasilitas_jadwal` (`id_fasilitas`);

--
-- Indexes for table `t_membership`
--
ALTER TABLE `t_membership`
  ADD PRIMARY KEY (`id_membership`);

--
-- Indexes for table `t_pembayaran`
--
ALTER TABLE `t_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_booking` (`id_booking`),
  ADD KEY `t_pembayaran_ibfk_2` (`id_extra`);

--
-- Indexes for table `t_tanggal`
--
ALTER TABLE `t_tanggal`
  ADD PRIMARY KEY (`id_tanggal`);

--
-- Indexes for table `t_voucher`
--
ALTER TABLE `t_voucher`
  ADD PRIMARY KEY (`id_voucher`);

--
-- Indexes for table `t_waktu`
--
ALTER TABLE `t_waktu`
  ADD PRIMARY KEY (`id_waktu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_barang`
--
ALTER TABLE `t_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_booking`
--
ALTER TABLE `t_booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_customer`
--
ALTER TABLE `t_customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `t_extra`
--
ALTER TABLE `t_extra`
  MODIFY `id_extra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_fasilitas`
--
ALTER TABLE `t_fasilitas`
  MODIFY `id_fasilitas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_jadwal`
--
ALTER TABLE `t_jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `t_membership`
--
ALTER TABLE `t_membership`
  MODIFY `id_membership` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_pembayaran`
--
ALTER TABLE `t_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_tanggal`
--
ALTER TABLE `t_tanggal`
  MODIFY `id_tanggal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_voucher`
--
ALTER TABLE `t_voucher`
  MODIFY `id_voucher` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_waktu`
--
ALTER TABLE `t_waktu`
  MODIFY `id_waktu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_booking`
--
ALTER TABLE `t_booking`
  ADD CONSTRAINT `t_booking_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `t_customer` (`id_customer`),
  ADD CONSTRAINT `t_booking_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `t_jadwal` (`id_jadwal`),
  ADD CONSTRAINT `t_booking_ibfk_3` FOREIGN KEY (`id_voucher`) REFERENCES `t_voucher` (`id_voucher`),
  ADD CONSTRAINT `t_booking_ibfk_4` FOREIGN KEY (`id_fasilitas`) REFERENCES `t_fasilitas` (`id_fasilitas`);

--
-- Constraints for table `t_customer`
--
ALTER TABLE `t_customer`
  ADD CONSTRAINT `t_customer_ibfk_1` FOREIGN KEY (`id_membership`) REFERENCES `t_membership` (`id_membership`);

--
-- Constraints for table `t_extra`
--
ALTER TABLE `t_extra`
  ADD CONSTRAINT `t_extra_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `t_barang` (`id_barang`),
  ADD CONSTRAINT `t_extra_ibfk_2` FOREIGN KEY (`id_booking`) REFERENCES `t_booking` (`id_booking`);

--
-- Constraints for table `t_jadwal`
--
ALTER TABLE `t_jadwal`
  ADD CONSTRAINT `fk_fasilitas_jadwal` FOREIGN KEY (`id_fasilitas`) REFERENCES `t_fasilitas` (`id_fasilitas`),
  ADD CONSTRAINT `t_jadwal_ibfk_1` FOREIGN KEY (`id_tanggal`) REFERENCES `t_tanggal` (`id_tanggal`),
  ADD CONSTRAINT `t_jadwal_ibfk_2` FOREIGN KEY (`id_waktu`) REFERENCES `t_waktu` (`id_waktu`);

--
-- Constraints for table `t_pembayaran`
--
ALTER TABLE `t_pembayaran`
  ADD CONSTRAINT `t_pembayaran_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `t_booking` (`id_booking`),
  ADD CONSTRAINT `t_pembayaran_ibfk_2` FOREIGN KEY (`id_extra`) REFERENCES `t_extra` (`id_extra`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

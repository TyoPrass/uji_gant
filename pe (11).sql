-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2025 at 07:52 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pe`
--

-- --------------------------------------------------------

--
-- Table structure for table `checklist_katakanesha`
--

CREATE TABLE `checklist_katakanesha` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `checklist_data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_customer` int NOT NULL,
  `id_part` int NOT NULL,
  `id_proses` int NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `nama_part` varchar(100) NOT NULL,
  `proses` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `checklist_katakanesha`
--

INSERT INTO `checklist_katakanesha` (`id`, `date`, `checklist_data`, `created_at`, `id_customer`, `id_part`, `id_proses`, `nama_customer`, `nama_part`, `proses`) VALUES
(15, '2025-04-10', '[{\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"asdf\", \"P2\": \"asdf\", \"P3\": \"asdf\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\", \"keterangan\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\"}, {\"P1\": \"\", \"P2\": \"\", \"P3\": \"\"}]', '2025-04-10 03:13:28', 17, 45, 9, 'TES123', 'TES123', '1/5 ');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int NOT NULL,
  `nama_customer` varchar(255) NOT NULL,
  `project` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `project`) VALUES
(17, 'TES123', 'TES123');

-- --------------------------------------------------------

--
-- Table structure for table `data_part`
--

CREATE TABLE `data_part` (
  `id_part` int NOT NULL,
  `nama_part` varchar(255) NOT NULL,
  `gambar_part` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `id_customer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data_part`
--

INSERT INTO `data_part` (`id_part`, `nama_part`, `gambar_part`, `tanggal`, `tgl_selesai`, `id_customer`) VALUES
(45, 'TES123', 'png-transparent-mitsubishi-lancer-evolution-mitsubishi-motors-mitsubishi-ek-car-cars-logo-brands-angle-text-logo.png', '2025-04-08', '2025-04-26', 17);

-- --------------------------------------------------------

--
-- Table structure for table `gant_customer`
--

CREATE TABLE `gant_customer` (
  `id_gant` int NOT NULL,
  `id_customer` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `task_data` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gant_customer`
--

INSERT INTO `gant_customer` (`id_gant`, `id_customer`, `tanggal`, `task_data`) VALUES
(53, 'JOJO', '2025-04-15', '[{\"id\": \"temp_1744703368642_664\", \"text\": \"New task\", \"parent\": 0, \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}, {\"id\": \"temp_1744703372090_767\", \"text\": \"New task\", \"parent\": \"temp_1744703368642_664\", \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}, {\"id\": \"temp_1744703373882_832\", \"text\": \"New task\", \"parent\": \"temp_1744703368642_664\", \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}, {\"id\": \"temp_1744703384106_865\", \"text\": \"JAMAL\", \"parent\": 0, \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}, {\"id\": \"temp_1744703389282_539\", \"text\": \"JAJA\", \"parent\": 0, \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}]'),
(54, 'jerapah', '2025-04-15', '[{\"id\": \"temp_1744703456259_915\", \"text\": \"New task\", \"parent\": 0, \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}, {\"id\": \"temp_1744703457474_890\", \"text\": \"New task\", \"parent\": 0, \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}]'),
(55, 'YYYh', '2025-04-15', '[{\"id\": \"temp_1744703471210_818\", \"text\": \"New task\", \"parent\": 0, \"duration\": 1, \"progress\": 0, \"start_date\": \"2025-04-14\"}, {\"id\": \"temp_1744703492386_694\", \"text\": \"New task\", \"parent\": 0, \"duration\": 10, \"progress\": 0, \"start_date\": \"2025-04-14\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `nama_customer` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proses`
--

CREATE TABLE `proses` (
  `id_proses` int NOT NULL,
  `part_no` varchar(20) NOT NULL,
  `Proses` varchar(50) NOT NULL,
  `mat_spec` varchar(50) NOT NULL,
  `mat_size` varchar(50) NOT NULL,
  `part_sketch` varchar(255) NOT NULL,
  `layout_sketch` varchar(255) NOT NULL,
  `target_trial` varchar(255) NOT NULL,
  `id_part` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `proses`
--

INSERT INTO `proses` (`id_proses`, `part_no`, `Proses`, `mat_spec`, `mat_size`, `part_sketch`, `layout_sketch`, `target_trial`, `id_part`) VALUES
(9, 'TES123', '1/5 ', '33', '34', 'png-transparent-mitsubishi-lancer-evolution-mitsubishi-motors-mitsubishi-ek-car-cars-logo-brands-angle-text-logo.png', 'Screenshot 2023-06-15 084240.jpg', '23', 45);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `text` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `duration` int NOT NULL,
  `progress` float DEFAULT '0',
  `parent` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trial`
--

CREATE TABLE `trial` (
  `id_trial` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_start` varchar(25) NOT NULL,
  `jam_finish` varchar(25) NOT NULL,
  `mc_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `trial` varchar(255) NOT NULL,
  `kapasitas` varchar(50) NOT NULL,
  `cush_prec` varchar(50) NOT NULL,
  `pin_cus_qtt` varchar(50) NOT NULL,
  `die_height` varchar(50) NOT NULL,
  `die_dim` varchar(50) NOT NULL,
  `problem_tool` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `analisa_sebab_tool` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `counter_measure_tool` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pic_tool` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `target_tool` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan_tool` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `problem_part` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `analisa_sebab_part` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `counter_measure_part` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `PIC` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `target` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kelengkapan_dies` varchar(50) NOT NULL,
  `accuracy_part` varchar(50) NOT NULL,
  `id_proses` int NOT NULL,
  `id_part` int NOT NULL,
  `id_customer` int NOT NULL,
  `qty_trial` varchar(255) NOT NULL,
  `jumlah_ok` varchar(255) NOT NULL,
  `jumlah_ng` varchar(255) NOT NULL,
  `visual` varchar(255) NOT NULL,
  `dimensi` varchar(255) NOT NULL,
  `fungsi` varchar(255) NOT NULL,
  `judgement` varchar(255) NOT NULL,
  `dibuat` varchar(255) NOT NULL,
  `diperiksa` varchar(255) NOT NULL,
  `diketahui` varchar(255) NOT NULL,
  `peserta` varchar(255) NOT NULL,
  `part_no` varchar(255) NOT NULL,
  `project` varchar(255) NOT NULL,
  `mat_spec` varchar(255) NOT NULL,
  `mat_size` varchar(255) NOT NULL,
  `nama_customer` varchar(255) NOT NULL,
  `nama_part` varchar(255) NOT NULL,
  `Proses` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`) VALUES
(9, 'Tono', '$2y$10$20.tKwOqu/JzwjoyCtH7SO/MpxqcFso3g7p1XHJzO1OVvrIka3fFm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checklist_katakanesha`
--
ALTER TABLE `checklist_katakanesha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_part` (`id_part`),
  ADD KEY `id_proses` (`id_proses`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `data_part`
--
ALTER TABLE `data_part`
  ADD PRIMARY KEY (`id_part`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `gant_customer`
--
ALTER TABLE `gant_customer`
  ADD PRIMARY KEY (`id_gant`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `proses`
--
ALTER TABLE `proses`
  ADD PRIMARY KEY (`id_proses`),
  ADD KEY `id_part` (`id_part`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trial`
--
ALTER TABLE `trial`
  ADD PRIMARY KEY (`id_trial`),
  ADD KEY `id_proses` (`id_proses`),
  ADD KEY `id_part` (`id_part`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checklist_katakanesha`
--
ALTER TABLE `checklist_katakanesha`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `data_part`
--
ALTER TABLE `data_part`
  MODIFY `id_part` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `gant_customer`
--
ALTER TABLE `gant_customer`
  MODIFY `id_gant` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proses`
--
ALTER TABLE `proses`
  MODIFY `id_proses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trial`
--
ALTER TABLE `trial`
  MODIFY `id_trial` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checklist_katakanesha`
--
ALTER TABLE `checklist_katakanesha`
  ADD CONSTRAINT `checklist_katakanesha_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `checklist_katakanesha_ibfk_2` FOREIGN KEY (`id_part`) REFERENCES `data_part` (`id_part`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `checklist_katakanesha_ibfk_3` FOREIGN KEY (`id_proses`) REFERENCES `proses` (`id_proses`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `data_part`
--
ALTER TABLE `data_part`
  ADD CONSTRAINT `data_part_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `proses`
--
ALTER TABLE `proses`
  ADD CONSTRAINT `proses_ibfk_1` FOREIGN KEY (`id_part`) REFERENCES `data_part` (`id_part`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `trial`
--
ALTER TABLE `trial`
  ADD CONSTRAINT `trial_ibfk_1` FOREIGN KEY (`id_proses`) REFERENCES `proses` (`id_proses`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `trial_ibfk_2` FOREIGN KEY (`id_part`) REFERENCES `data_part` (`id_part`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `trial_ibfk_3` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2026 at 05:02 PM
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
-- Database: `webcafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `Hoso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `Name`, `Email`, `password`, `Hoso`) VALUES
('1', 'Viet', 'nquocviet0905@gmail.com', '123456', '');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` varchar(255) DEFAULT NULL,
  `product_id` int(100) NOT NULL,
  `Giaban` decimal(50,3) NOT NULL,
  `qty` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dathang`
--

CREATE TABLE `dathang` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `DienThoai` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `address_type` varchar(10) NOT NULL,
  `method` varchar(50) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `Giaban` decimal(50,3) NOT NULL,
  `qty` varchar(2) NOT NULL,
  `date` date NOT NULL,
  `Trangthai` varchar(50) NOT NULL,
  `payment_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dathang`
--

INSERT INTO `dathang` (`id`, `user_id`, `Name`, `DienThoai`, `Email`, `address`, `address_type`, `method`, `product_id`, `Giaban`, `qty`, `date`, `Trangthai`, `payment_status`) VALUES
('bbumYq6ubpdgRtZsZMzJ', '4m7auUd30xD7zCrj4hSL', 's', 1234545234, 'songkhanh99999@gmail.com', '66,cao bá quát,s,s,123456', 'home', 'cash on delivery', '13', 40.000, '1', '2026-04-20', 'Chờ xử lý', 'Chưa thanh toán'),
('TiNL6u1sRNwLwQPCZGj1', '4m7auUd30xD7zCrj4hSL', 's', 1234545234, 'songkhanh99999@gmail.com', '66,cao bá quát,s,s,123456', 'home', 'cash on delivery', '14', 100.000, '1', '2026-04-20', 'Chờ xử lý', 'hoàn thành');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `id` varchar(2) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Giaban` decimal(10,3) NOT NULL,
  `image` varchar(255) NOT NULL,
  `product_detail` varchar(1000) NOT NULL,
  `Trangthai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`id`, `Name`, `Giaban`, `image`, `product_detail`, `Trangthai`) VALUES
('1', 'Trà xanh', 150.000, 'card.jpg', 'Trà tự nhiên', ''),
('10', 'socolanong', 30.000, 'socolanong.jpg', '', ''),
('13', 'chanhtuyet', 40.000, 'chanhtuyet.jpg', '', ''),
('14', 'card2', 100.000, 'card2.jpg', '', ''),
('2', 'bacxiu', 30.000, 'bacxiu.jpeg', '', ''),
('3', 'banh', 35.000, 'banh.jpg', '', ''),
('4', 'banh1', 30.000, 'banh1.jpg', '', ''),
('5', 'banh2', 35.000, 'banh2.jpg', '', ''),
('6', 'banh3', 50.000, 'banh3.jpg', '', ''),
('7', 'banh5', 60.000, 'banh5.jpg', '', ''),
('8', 'banh11', 60.000, 'banh11.jpg', '', ''),
('9', 'Cappuccino', 40.000, 'Cappuccino.jpg', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Matkhau` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Name`, `Email`, `Matkhau`, `user_type`) VALUES
('4m7auUd30xD7zCrj4hSL', '', 'songkhanh9999@gmail.com', '123456', ''),
('OuMuZegE84FBNSANEBsa', '', 'minh6868@gmail.com', '123', ''),
('Ywv5qaazfJ9LH5gIVgcx', '', 'songkhanh99999@gmail.com', '123456', ''),
('HKpwu5H0l2aoQWnr2Tgg', '', 'tuan989898@gmail.com', '123456', ''),
('qqULw85CLFi1OoouzVvM', 'minh', 'minh123@gmail.com', '123456', ''),
('PgUKuKC6to6WuXGioRZR', 'bảo', 'bao456@gmail.com', '123456', ''),
('QQiE3BuihGmhqzWBOkYd', 'quang anh', 'quanganh123@gmail.com', '123456', ''),
('n2kRgbCZeO4A65fyfOvj', 'khanh123', 'khanh@gmail.com', '123456', ''),
('e1csD3AG59UwdYEjjOgG', 'khanh1234', 'khanh123@gmail.com', '123456', ''),
('s8Y5RRlXWebjRh8MGfEf', 'khanh45', 'khanhvu@gmail.com', '123456', ''),
('cyIHGu3FaWRVkoeeDj3L', 'khanh', 'songkhanh123@gmail.com', 'khanh', ''),
('i8QohgN3l2jqX6lfzLIt', 'khanhvv', 'qa123@gmail.com', '123456', ''),
('69e62d30da4b4', 'anh7', 'anh7@gmail.com', '$2y$10$CtgNHjcfeBTaQEjvkzDc2uOLDj6cW4yJXDOTov0wMNe', ''),
('69e62da665cf9', 'quynhyeulinh', 'quynhvalinh@gmail.com', '$2y$10$gpP8dxAOIweg2Cf.1bcmXucCDmRbKDkA4I.j10SxdLyD3WAGYK06e', ''),
('69e6340f06dfa', 'khanh', 'khanhww@gmail.com', '$2y$10$DbWJUiVrTTvf/MQOtpapuuQc1Bgm/9bSWwbX4gmHjTnuZZ9hAhoce', '');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `product_id` varchar(100) NOT NULL,
  `Giaban` decimal(50,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `Giaban`) VALUES
(9, '69e62da665cf9', '10', 30.000),
(10, '4m7auUd30xD7zCrj4hSL', '13', 40.000),
(11, '4m7auUd30xD7zCrj4hSL', '1', 150.000),
(12, '4m7auUd30xD7zCrj4hSL', '10', 30.000),
(17, '69e6340f06dfa', '1', 150.000),
(18, '69e6340f06dfa', '10', 30.000),
(19, '69e6340f06dfa', '13', 40.000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_product` (`user_id`,`product_id`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2023-05-05 15:16:33
-- 伺服器版本： 10.11.2-MariaDB
-- PHP 版本： 8.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `can302_ass2`
--

-- --------------------------------------------------------

--
-- 資料表結構 `category`
--

CREATE TABLE `category` (
  `id` int(8) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL,
  `owner` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `owner`) VALUES
(0, '', '', 0),
(1, 'Electronics', 'Camera, laptop, PC, smart phones...', 1),
(2, 'Watch', 'watches...', 1),
(3, 'Fruit', 'Apple, pineapple, banana, potato', 1),
(7, 'Clothes', 'Our clothes are expensive and ugly.', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(8) NOT NULL,
  `time` datetime NOT NULL,
  `product_id` int(8) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` double(10,2) NOT NULL,
  `product_owner` int(8) NOT NULL,
  `quantity` int(8) NOT NULL,
  `customer_id` int(8) NOT NULL,
  `customer_name` varchar(55) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`id`, `time`, `product_id`, `product_name`, `product_price`, `product_owner`, `quantity`, `customer_id`, `customer_name`, `address`, `tel`) VALUES
(7, '2023-05-05 15:14:40', 1, 'FinePix Pro2 3D Camera', 1500.00, 1, 2, 2, 'Mr. Jingyi Wang', 'Post box 10086\r\nXicheng District, Beijing', '18537820068'),
(8, '2023-05-05 15:14:40', 3, 'Golden Dial Ladies Watch which is a good watch', 300.00, 1, 3, 2, 'Mr. Jingyi Wang', 'Post box 10086\r\nXicheng District, Beijing', '18537820068'),
(10, '2023-05-05 15:14:40', 8, 'Daily Ritual Womens Fine Rib Sleeveless Racerback Maxi Dress', 35.00, 1, 1, 2, 'Mr. Jingyi Wang', 'Post box 10086\r\nXicheng District, Beijing', '18537820068'),
(11, '2023-05-05 15:39:41', 3, 'Golden Dial Ladies Watch which is a good watch', 300.00, 1, 2, 5, 'Dr. San Zhang', 'No. 333, Floor 3,\r\nSuzhou Center Building\r\nSuzhou Jiangsu 215123 China', '16601012345'),
(12, '2023-05-05 15:39:41', 8, 'Daily Ritual Womens Fine Rib Sleeveless Racerback Maxi Dress', 35.00, 1, 2, 5, 'Dr. San Zhang', 'No. 333, Floor 3,\r\nSuzhou Center Building\r\nSuzhou Jiangsu 215123 China', '16601012345');

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `id` int(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` mediumtext NOT NULL,
  `price` double(10,2) NOT NULL,
  `supply` int(8) NOT NULL,
  `cat_id` int(8) NOT NULL,
  `owner` int(8) NOT NULL,
  `description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `product`
--

INSERT INTO `product` (`id`, `name`, `image`, `price`, `supply`, `cat_id`, `owner`, `description`) VALUES
(0, '', 'product-images/error.jpg', 0.00, 0, 0, 0, ''),
(1, 'FinePix Pro2 3D Camera', 'product-images/camera.jpg', 1500.00, 100, 1, 1, 'The worest camera in the world. However, it is not cheap.'),
(2, 'EXP Portable Hard Drive', 'product-images/external-hard-drive.jpg', 800.00, 100, 1, 1, 'Volume: 2TB. Super low power consumption.'),
(3, 'Golden Dial Ladies Watch which is a good watch', 'product-images/watch.jpg', 300.00, 100, 2, 1, 'Silver-tone stainless steel case and bracelet. Fixed silver-tone bezel with diamond set. Silver dial with blue hands and alternating Roman numeral and index hour markers.'),
(4, 'XP 1155 Intel Core Laptop', 'product-images/laptop.jpg', 800.00, 100, 1, 1, 'Intel 23th CPU. In-memory computing. ChatGPT6 integrated.'),
(5, 'FinePix Pro2 88D Camera', 'product-images/camera.jpg', 3300.00, 223, 1, 1, 'The best and most expensive camera. Full-size (35mm) CMOS sensor.'),
(8, 'Daily Ritual Womens Fine Rib Sleeveless Racerback Maxi Dress', 'product-images/dress.jpg', 35.00, 100, 7, 1, '95% Viscose, 5% Elastane\r\nImported\r\nNo Closure closure\r\nMachine Wash\r\nThis tank maxi dress features a scoop neck and a racer back for versatile, everyday wear\r\nThis versatile full-length maxi dress is cut to flatter any body type\r\nOur signature rayon-spandex blend gets a fresh update with fine ribbing');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(8) NOT NULL,
  `name` varchar(25) NOT NULL,
  `pass` varchar(65) NOT NULL,
  `disp_name` varchar(45) DEFAULT NULL,
  `title` varchar(10) DEFAULT NULL,
  `role` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `name`, `pass`, `disp_name`, `title`, `role`, `address`, `tel`, `image`) VALUES
(0, '', '', '', '', '', '', '', ''),
(1, 'can302', '$2y$10$h8I9sc/ddHk.dbTd0ecVduowWTf0Gig6WsyeHggQqbb1qliIAXWke', 'Super Admin', 'Mr.', 'admin', 'IR724, IR Building, South Campus\r\nNo. 8 Chongwen Road\r\nSuzhou Industrial Park', '0512 8818 9919', 'user-images/1.png'),
(2, 'jwang', '$2y$10$h8I9sc/ddHk.dbTd0ecVduowWTf0Gig6WsyeHggQqbb1qliIAXWke', 'Jingyi Wang', 'Mr.', 'customer', 'Post box 10086\r\nXicheng District, Beijing', '18537820068', 'user-images/2.png'),
(5, 'sanz', '$2y$10$0qKhgaDSodpLCTXL4RlhrOanGg4hWF6/bQXfPxV8Cr1KWvP8nYSLu', 'San Zhang', 'Dr.', 'customer', 'No. 333, Floor 3,\r\nSuzhou Center Building\r\nSuzhou Jiangsu 215123 China', '16601012345', 'user-images/3.png');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `category`
--
ALTER TABLE `category`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 05:22 AM
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
-- Database: `wac_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ArchiveAndDeleteOldCarPhotos` ()   BEGIN
    CREATE TABLE IF NOT EXISTS `car_photos_archive` LIKE `car_photos`;

    INSERT INTO `car_photos_archive`
    SELECT * FROM `car_photos`
    WHERE `recorded_date` < DATE_SUB(CURDATE(), INTERVAL 1 MONTH);

    DELETE FROM `car_photos`
    WHERE `recorded_date` < DATE_SUB(CURDATE(), INTERVAL 1 MONTH);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `car_photos`
--

CREATE TABLE `car_photos` (
  `id` int(11) NOT NULL,
  `car_number` varchar(20) NOT NULL,
  `front_image` varchar(255) NOT NULL,
  `left_image` varchar(255) NOT NULL,
  `back_image` varchar(255) NOT NULL,
  `right_image` varchar(255) NOT NULL,
  `dashboard_image` varchar(255) NOT NULL,
  `recorded_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_photos`
--

INSERT INTO `car_photos` (`id`, `car_number`, `front_image`, `left_image`, `back_image`, `right_image`, `dashboard_image`, `recorded_date`) VALUES
(5, 'BP-9-AAaaaaa', 'photo_1747126933899.webp', 'photo_1747126937842.webp', 'photo_1747126944057.webp', 'photo_1747126952486.webp', 'photo_1747126949434.webp', '2025-05-13'),
(6, 'BP-1561-ET', 'photo_1747127192153.webp', 'photo_1747127204722.webp', 'photo_1747127214955.webp', 'photo_1747127223877.webp', 'photo_1747127228046.webp', '2025-05-13'),
(7, 'Ax-23by', 'photo_1747130092357.webp', 'photo_1747130095721.webp', 'photo_1747130098989.webp', 'photo_1747130101800.webp', 'photo_1747130104796.webp', '2025-05-13'),
(8, 'Testplat', 'photo_1747130422540.webp', 'photo_1747130425879.webp', 'photo_1747130432894.webp', 'photo_1747130444357.webp', 'photo_1747130439210.webp', '2025-05-13'),
(9, 'BP-9-AA', 'photo_1747192525112.webp', 'photo_1747192543600.webp', 'photo_1747192546824.webp', 'photo_1747192562153.webp', 'photo_1747192550020.webp', '2025-05-14'),
(10, 'Kek', 'photo_1747194645688.webp', 'photo_1747194649020.webp', 'photo_1747194666697.webp', 'photo_1747194672598.webp', 'photo_1747194677034.webp', '2025-05-14'),
(11, 'W', 'photo_1747194931190.webp', 'photo_1747194935096.webp', 'photo_1747194943706.webp', 'photo_1747194939762.webp', 'photo_1747194947848.webp', '2025-05-14'),
(12, 'Wuaaa', 'photo_1747195307971.webp', 'photo_1747195314328.webp', 'photo_1747195318643.webp', 'photo_1747195325158.webp', 'photo_1747195321526.webp', '2025-05-14'),
(13, 'Ya', 'photo_1747195564156.webp', 'photo_1747195567464.webp', 'photo_1747195578149.webp', 'photo_1747195571185.webp', 'photo_1747195574200.webp', '2025-05-14'),
(14, '2232', 'photo_1747196960224.webp', 'photo_1747196987710.webp', 'photo_1747196964899.webp', 'photo_1747196969872.webp', 'photo_1747196976302.webp', '2025-05-14');

-- --------------------------------------------------------

--
-- Table structure for table `car_photos_archive`
--

CREATE TABLE `car_photos_archive` (
  `id` int(11) NOT NULL,
  `car_number` varchar(20) NOT NULL,
  `front_image` varchar(255) NOT NULL,
  `left_image` varchar(255) NOT NULL,
  `back_image` varchar(255) NOT NULL,
  `right_image` varchar(255) NOT NULL,
  `dashboard_image` varchar(255) NOT NULL,
  `recorded_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_photos_archive`
--

INSERT INTO `car_photos_archive` (`id`, `car_number`, `front_image`, `left_image`, `back_image`, `right_image`, `dashboard_image`, `recorded_date`) VALUES
(1, 'BP-2222-333', '1742783502250.webp', '67d28fbdc42c4_1741852605.webp', '67d28fbdc42c4_1741852605.webp', '1742783502250.webp', '1742783502250.webp', '2025-04-12'),
(2, 'BP-2222-333', '1742783502250.webp', '1742783502250.webp', '1742783502250.webp', '1742783502250.webp', '1742783502250.webp', '2025-04-12'),
(3, 'BP-2222-333', '67d28fbdc42c4_1741852605.webp', '1742783502250.webp', '1742783502250.webp', '67d28fbdc42c4_1741852605.webp', '67d28fbdc42c4_1741852605.webp', '2025-04-12'),
(4, 'BP-2222-241', '1745377630206-removebg-preview.webp', 'WhatsAppImage2025-04-23at09.01.131.webp', 'WhatsApp_Image_2025-04-23_at_09.01.13__1_-removebg-preview1.webp', 'WhatsAppImage2025-05-03at12.10.06.webp', 'WhatsApp_Image_2025-04-23_at_09.01.13__1_-removebg-preview.webp', '2025-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$bExBce2PU57.Aw/X4YZqVORIhspc48o/MW0WmjUoh5S946xrKjsPe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car_photos`
--
ALTER TABLE `car_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_photos_archive`
--
ALTER TABLE `car_photos_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car_photos`
--
ALTER TABLE `car_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `car_photos_archive`
--
ALTER TABLE `car_photos_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_old_records` ON SCHEDULE EVERY 1 DAY STARTS '2025-05-06 11:39:24' ON COMPLETION NOT PRESERVE ENABLE DO CALL ArchiveAndDeleteOldCarPhotos()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

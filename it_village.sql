-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 24 март 2019 в 09:34
-- Версия на сървъра: 10.1.37-MariaDB
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_village`
--

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(40) NOT NULL,
  `played_games` int(11) NOT NULL DEFAULT '0',
  `victories` int(11) NOT NULL DEFAULT '0',
  `defeats` int(11) NOT NULL DEFAULT '0',
  `in_progress` varchar(3) NOT NULL DEFAULT 'NO',
  `last_money` int(11) DEFAULT NULL,
  `remaining_moves` int(2) DEFAULT NULL,
  `last_field` int(2) DEFAULT NULL,
  `motels` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `played_games`, `victories`, `defeats`, `in_progress`, `last_money`, `remaining_moves`, `last_field`, `motels`) VALUES
(1, 'player_123', 'pass_123', 65, 38, 27, 'NO', NULL, NULL, NULL, NULL),
(42, 'martin_georgiev', 'marto_g', 0, 0, 0, 'NO', NULL, NULL, NULL, NULL),
(45, 'az_sum_bobbi', 'bobi2002', 41, 36, 5, 'NO', NULL, NULL, NULL, NULL),
(46, 'user_2019', 'pass_2019', 0, 0, 0, 'NO', NULL, NULL, NULL, NULL),
(47, 'bg_gamer', 'gamer_master', 3, 1, 2, 'NO', NULL, NULL, NULL, NULL),
(48, 'test10032019', 'test10032019', 84, 71, 13, 'NO', NULL, NULL, NULL, NULL),
(49, 'test11032019', 'test11032019', 16, 10, 6, 'NO', NULL, NULL, NULL, NULL),
(50, 'player123', 'pass123', 20, 11, 9, 'NO', NULL, NULL, NULL, NULL),
(59, 'rumen123', 'rumen123', 17, 11, 6, 'NO', NULL, NULL, NULL, NULL),
(60, 'rum12345', 'rum12345', 34, 20, 14, 'NO', NULL, NULL, NULL, NULL),
(62, 'boy12345', 'boy12345', 1, 1, 0, 'YES', 500, 2, 6, 0),
(63, 'test100320191', 'test100320191', 26, 17, 9, 'NO', NULL, NULL, NULL, NULL),
(64, 'test22032019', 'test22032019', 9, 7, 2, 'NO', NULL, NULL, NULL, NULL),
(65, 'test23032019', 'test23032019', 26, 13, 13, 'NO', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

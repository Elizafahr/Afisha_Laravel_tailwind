-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 06 2025 г., 02:18
-- Версия сервера: 10.8.4-MariaDB-log
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `app_Afisha`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `registration_date` timestamp NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `email`, `password_hash`, `phone`, `role`, `registration_date`, `is_active`, `updated_at`, `created_at`, `deleted_at`) VALUES
(1, 'ivanov', 'ivanov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79161234567', 'user', '2025-05-13 06:03:08', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 'petrova', 'petrova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79167654321', 'user', '2025-05-13 06:03:08', 1, '2025-06-01 10:27:00', '0000-00-00 00:00:00', '2025-06-01 10:27:00'),
(3, 'sidorov', 'sidorov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79165554433', 'user', '2025-05-13 06:03:08', 1, '2025-05-28 22:18:41', '0000-00-00 00:00:00', NULL),
(4, 'admins', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79160000000', 'admin', '2025-05-13 06:03:08', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(5, 'user', 'user@user', '$2y$12$jO5sBIDCEsSY6PCTdj0Kfe0g/eWUzb7/d.zIIgXVNsOlB3ITo1Fn.', '88005553535', 'user', '2025-05-28 18:42:55', 1, '2025-05-28 18:42:55', '2025-05-28 18:42:55', NULL),
(6, 'admin', 'admin@admin', '$2y$12$wQgdBh5Z2VnlNo1KzbE3OuwAaS05.irt8MV5uY9bmkWmWgIjTKiLC', '88005553535', 'admin', '2025-05-28 20:33:42', 1, '2025-05-28 20:33:42', '2025-05-28 20:33:42', NULL),
(12, 'qwertyuioop', 'qwertyuioop@qwertyuioop', '$2y$12$QRG.U3TePXlzOndY2J3/u.iqsuMa/HgJQqxmk.HbBHw9GMLawv4PG', 'qwertyuioop', 'organizer', '2025-06-01 09:50:46', 1, '2025-06-01 09:50:46', '2025-06-01 09:50:46', NULL),
(13, 'organizer@organizers', 'organizer@organizers', '$2y$12$uEhd70LE/5GsiNqXpNuLGOjDsO/M8rD3ax.ePsFBuzgL056PGFMU2', 'organizer@organizers', 'organizer', '2025-06-01 11:55:39', 1, '2025-06-01 11:55:39', '2025-06-01 11:55:39', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

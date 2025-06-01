-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 01 2025 г., 12:41
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
-- Структура таблицы `Bookings`
--

CREATE TABLE `Bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `seat_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_date` timestamp NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Bookings`
--

INSERT INTO `Bookings` (`booking_id`, `user_id`, `ticket_id`, `seat_id`, `quantity`, `total_price`, `booking_date`, `status`, `payment_method`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 2, NULL, 1, 1, '1200.00', '2025-05-13 06:03:09', 'confirmed', 'online_payment', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 3, NULL, 1, '800.00', '2025-05-13 06:03:09', 'pending', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 5, NULL, NULL, 1, '1500.00', '2025-05-29 08:03:11', 'confirmed', NULL, '2025-05-29 08:03:11', '2025-05-29 08:03:11', '0000-00-00 00:00:00'),
(5, 5, NULL, NULL, 1, '1500.00', '2025-05-29 08:03:20', 'confirmed', NULL, '2025-05-29 08:03:20', '2025-05-29 08:03:20', '0000-00-00 00:00:00'),
(6, 5, NULL, NULL, 1, '1500.00', '2025-05-29 08:04:07', 'confirmed', NULL, '2025-05-29 08:04:07', '2025-05-29 08:04:07', '0000-00-00 00:00:00'),
(7, 5, NULL, NULL, 1, '800.00', '2025-05-29 08:10:10', 'confirmed', NULL, '2025-05-29 08:10:10', '2025-05-29 08:10:10', '0000-00-00 00:00:00'),
(8, 5, NULL, NULL, 1, '1500.00', '2025-05-29 08:23:45', 'cancelled', NULL, '2025-05-29 08:23:45', '2025-06-01 09:19:34', '2025-06-01 09:19:34'),
(9, 5, NULL, NULL, 1, '800.00', '2025-05-29 08:26:51', 'confirmed', NULL, '2025-05-29 08:26:51', '2025-06-01 09:15:12', '2025-06-01 09:15:12');

-- --------------------------------------------------------

--
-- Структура таблицы `Events`
--

CREATE TABLE `Events` (
  `event_id` int(11) NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `age_restriction` int(11) DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `is_free` int(11) NOT NULL,
  `is_featured` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `is_booking` int(11) DEFAULT 1,
  `link` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `booking_type` enum('seated','general') NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Events`
--

INSERT INTO `Events` (`event_id`, `organizer_id`, `title`, `description`, `category`, `start_datetime`, `end_datetime`, `location`, `age_restriction`, `poster_url`, `is_published`, `created_at`, `is_free`, `is_featured`, `price`, `is_booking`, `link`, `updated_at`, `booking_type`, `deleted_at`) VALUES
(1, 1, 'Рок-фестиваль \"Лето\"', 'Ежегодный рок-фестиваль с участием местных и приглашенных групп', 'concert', '2025-06-15 18:00:00', '2025-06-15 23:00:00', 'Парк культуры и отдыха, Каменск-Уральский', 16, 'https://example.com/posters/rock_fest.jpg', 1, '2025-05-13 06:03:08', 0, 0, 345, 0, 'https://usadbagrebnevo.com/meropriyatiya/15-17-avgusta-festival-rok-uikend-v-grebnevo/', '0000-00-00 00:00:00', 'seated', '0000-00-00 00:00:00'),
(2, 1, 'Джаз под звездами', 'Вечер джазовой музыки с участием лучших джазовых коллективов города', 'concert', '2025-07-20 19:00:00', '2025-07-20 22:00:00', 'Дворец культуры \"Металлург\", Каменск-Уральский', 12, 'https://example.com/posters/jazz_night.jpg', 1, '2025-05-13 06:03:08', 0, 0, 555, 1, '', '0000-00-00 00:00:00', 'seated', '0000-00-00 00:00:00'),
(3, 2, 'Городской день рождения', 'Торжественное празднование дня города с концертами, ярмаркой и фейерверком', 'festival', '2025-08-30 12:00:00', '2025-08-30 23:59:00', 'Центральная площадь, Каменск-Уральский', 0, 'https://example.com/posters/city_day.jpg', 1, '2025-05-13 06:03:08', 0, 0, 66, 1, '', '0000-00-00 00:00:00', 'seated', '0000-00-00 00:00:00'),
(4, 1, 'Фестиваль уральской кухни1', 'Гастрономический фестиваль с блюдами уральской кухни и мастер-классами', 'festival', '2025-06-10 11:00:00', '2025-06-10 20:00:00', 'Набережная реки Исеть, Каменск-Уральский', 0, 'https://example.com/posters/ural_food.jpg', 1, '2025-05-28 18:46:00', 1, 0, 0, 1, '', '2025-06-01 09:23:14', 'seated', '0000-00-00 00:00:00'),
(5, 2, 'Выставка \"Каменск исторический\"', 'Выставка фотографий и артефактов, рассказывающих об истории города', 'exhibition', '2025-07-15 10:00:00', '2025-08-15 18:00:00', 'Краеведческий музей им. И.Я. Стяжкина, Каменск-Уральский', 0, 'https://example.com/posters/history_exhibition.jpg', 1, '2025-05-28 18:46:00', 1, 1, 0, 1, '', '0000-00-00 00:00:00', 'general', '0000-00-00 00:00:00'),
(6, 1, 'Концерт группы \"Уральские пельмени\"', 'Юмористическое шоу от известного коллектива', 'concert', '2025-09-05 19:00:00', '2025-09-05 22:00:00', 'Дворец культуры \"Юность\", Каменск-Уральский', 16, 'https://example.com/posters/ural_pelmeni.jpg', 1, '2025-05-28 18:46:00', 0, 1, 1500, 1, '', '0000-00-00 00:00:00', 'seated', '0000-00-00 00:00:00'),
(7, 2, 'День металлурга', 'Городской праздник с концертом, конкурсами и фейерверком', 'festival', '2025-07-19 12:00:00', '2025-07-19 23:00:00', 'Парк Победы, Каменск-Уральский', 0, 'https://example.com/posters/metallurg_day.jpg', 1, '2025-05-28 18:46:00', 1, 0, 0, 1, '', '0000-00-00 00:00:00', 'seated', '0000-00-00 00:00:00'),
(8, 1, 'Театральный фестиваль \"Малая сцена\"', 'Выступления театральных коллективов со всей области', 'theater', '2025-10-01 18:00:00', '2025-10-05 22:00:00', 'Драматический театр, Каменск-Уральский', 12, 'https://example.com/posters/theater_fest.jpg', 1, '2025-05-28 18:46:00', 0, 1, 800, 1, '', '0000-00-00 00:00:00', 'general', '0000-00-00 00:00:00'),
(9, 4, 'Театральный фестиваль \"Малая сцена\"', 'Выступления театральных коллективов со всей области', 'theater', '2025-10-01 18:00:00', '2025-10-05 22:00:00', 'Драматический театр, Каменск-Уральский', 12, 'https://example.com/posters/theater_fest.jpg', 1, '2025-05-28 18:46:00', 0, 1, 800, 1, '', '0000-00-00 00:00:00', 'general', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `Favorites`
--

CREATE TABLE `Favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `added_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Favorites`
--

INSERT INTO `Favorites` (`favorite_id`, `user_id`, `event_id`, `added_at`, `updated_at`, `created_at`) VALUES
(1, 1, 2, '2025-05-13 06:03:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2, 1, '2025-05-13 06:03:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2, 3, '2025-05-13 06:03:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 5, 7, '2025-05-28 19:25:35', '2025-05-28 19:25:35', '2025-05-28 19:25:35'),
(6, 5, 1, '2025-05-29 06:14:25', '2025-05-29 06:14:25', '2025-05-29 06:14:25'),
(7, 5, 5, '2025-05-29 07:24:37', '2025-05-29 07:24:37', '2025-05-29 07:24:37'),
(8, 5, 4, '2025-05-29 08:25:33', '2025-05-29 08:25:33', '2025-05-29 08:25:33');

-- --------------------------------------------------------

--
-- Структура таблицы `News`
--

CREATE TABLE `News` (
  `news_id` int(11) NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `is_pinned` tinyint(1) DEFAULT 0,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `News`
--

INSERT INTO `News` (`news_id`, `organizer_id`, `title`, `content`, `image_url`, `created_at`, `is_pinned`, `category`) VALUES
(1, 1, 'Изменение в программе рок-фестиваля', 'Группа \"Алиса\" не сможет выступить на фестивале, вместо нее будет группа \"Кино\"', 'https://example.com/news/rock_change.jpg', '2025-05-13 06:03:08', 1, ''),
(2, 2, 'Программа дня города', 'Опубликована полная программа празднования дня города', 'https://example.com/news/city_program.jpg', '2025-05-13 06:03:08', 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `Notifications`
--

CREATE TABLE `Notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Notifications`
--

INSERT INTO `Notifications` (`notification_id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'Ваше бронирование на рок-фестиваль подтверждено', 1, '2025-05-13 06:03:09'),
(2, 2, 'Осталось 3 дня до начала джазового вечера', 0, '2025-05-13 06:03:09');

-- --------------------------------------------------------

--
-- Структура таблицы `Organizers`
--

CREATE TABLE `Organizers` (
  `organizer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organization_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `contact_info` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Organizers`
--

INSERT INTO `Organizers` (`organizer_id`, `user_id`, `organization_name`, `description`, `logo_url`, `contact_person`, `is_verified`, `contact_info`, `updated_at`, `created_at`) VALUES
(1, 3, 'Концертное агентство \"Мелодия\"', 'Организация концертов и музыкальных мероприятий', 'https://example.com/logos/melody.jpg', 'Сидоров Алексей', 1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00.000000'),
(2, 4, 'Администрация города', 'Официальные городские мероприятия', 'https://example.com/logos/city_admin.jpg', 'Иванова Мария', 1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00.000000'),
(3, 10, 'www@www', 'www@www', NULL, 'www@www', 0, 'www@www', '2025-05-30 02:27:04', '2025-05-30 05:27:04.000000'),
(4, 11, 'rrrrrrrrrrr@rrrrrrrrrrr', 'rrrrrrrrrrr@rrrrrrrrrrr', NULL, 'rrrrrrrrrrr@rrrrrrrrrrr', 0, 'rrrrrrrrrrr@rrrrrrrrrrr', '2025-05-30 04:03:43', '2025-05-30 07:03:43.000000');

-- --------------------------------------------------------

--
-- Структура таблицы `Reviews`
--

CREATE TABLE `Reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Reviews`
--

INSERT INTO `Reviews` (`review_id`, `user_id`, `event_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Отличный фестиваль, жду следующего года!', '2025-05-13 06:03:09'),
(2, 2, 1, 4, 'Хорошая организация, но мало туалетов', '2025-05-13 06:03:09');

-- --------------------------------------------------------

--
-- Структура таблицы `Seats`
--

CREATE TABLE `Seats` (
  `seat_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `zone` varchar(50) DEFAULT NULL,
  `seat_row` int(11) DEFAULT NULL COMMENT 'Номер ряда (вместо row_number, который является зарезервированным словом)',
  `is_reserved` tinyint(1) DEFAULT 0,
  `price_multiplier` decimal(3,2) DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Seats`
--

INSERT INTO `Seats` (`seat_id`, `event_id`, `seat_number`, `zone`, `seat_row`, `is_reserved`, `price_multiplier`) VALUES
(1, 2, 'A1', 'Партер', 1, 0, '1.50'),
(2, 2, 'A2', 'Партер', 1, 0, '1.50'),
(3, 2, 'B1', 'Балкон', 2, 0, '1.00'),
(4, 2, 'B2', 'Балкон', 2, 0, '1.00'),
(5, 2, 'C1', 'Ложа', 3, 0, '2.00'),
(6, 6, 'A1', 'Партер', 1, 0, '1.50'),
(7, 6, 'A2', 'Партер', 1, 0, '1.50'),
(8, 6, 'B1', 'Балкон', 2, 0, '1.00'),
(9, 6, 'B2', 'Балкон', 2, 0, '1.00'),
(10, 8, 'A1', 'Партер', 1, 0, '1.20'),
(11, 8, 'A2', 'Партер', 1, 0, '1.20'),
(12, 8, 'B1', 'Балкон', 2, 0, '1.00'),
(13, 8, 'B2', 'Балкон', 2, 0, '1.00');

-- --------------------------------------------------------

--
-- Структура таблицы `Tickets`
--

CREATE TABLE `Tickets` (
  `ticket_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity_available` int(11) NOT NULL,
  `booking_start` datetime DEFAULT NULL,
  `booking_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Tickets`
--

INSERT INTO `Tickets` (`ticket_id`, `event_id`, `ticket_type`, `price`, `quantity_available`, `booking_start`, `booking_end`) VALUES
(1, 1, 'Стандарт', '1500.00', 200, '2025-05-01 00:00:00', '2025-06-14 23:59:59'),
(2, 1, 'VIP', '3500.00', 50, '2025-05-01 00:00:00', '2025-06-14 23:59:59'),
(3, 2, 'Общий вход', '800.00', 150, '2025-06-01 00:00:00', '2025-07-19 23:59:59'),
(4, 3, 'Бесплатный вход', '0.00', 1000, '2025-07-01 00:00:00', '2025-08-29 23:59:59'),
(5, 4, 'Бесплатный вход', '0.00', 500, '2025-05-20 00:00:00', '2025-06-09 23:59:59'),
(6, 5, 'Бесплатный вход', '0.00', 200, '2025-06-01 00:00:00', '2025-08-14 23:59:59'),
(7, 6, 'Стандарт', '1500.00', 300, '2025-06-01 00:00:00', '2025-09-04 23:59:59'),
(8, 6, 'VIP', '2500.00', 50, '2025-06-01 00:00:00', '2025-09-04 23:59:59'),
(9, 7, 'Бесплатный вход', '0.00', 1000, '2025-06-01 00:00:00', '2025-07-18 23:59:59'),
(10, 8, 'Дневной сеанс', '500.00', 100, '2025-08-01 00:00:00', '2025-09-30 23:59:59'),
(11, 8, 'Вечерний сеанс', '800.00', 150, '2025-08-01 00:00:00', '2025-09-30 23:59:59');

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
(2, 'petrova', 'petrova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79167654321', 'user', '2025-05-13 06:03:08', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 'sidorov', 'sidorov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79165554433', 'user', '2025-05-13 06:03:08', 1, '2025-05-28 22:18:41', '0000-00-00 00:00:00', NULL),
(4, 'admins', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79160000000', 'admin', '2025-05-13 06:03:08', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(5, 'user', 'user@user', '$2y$12$jO5sBIDCEsSY6PCTdj0Kfe0g/eWUzb7/d.zIIgXVNsOlB3ITo1Fn.', '88005553535', 'user', '2025-05-28 18:42:55', 1, '2025-05-28 18:42:55', '2025-05-28 18:42:55', NULL),
(6, 'admin', 'admin@admin', '$2y$12$wQgdBh5Z2VnlNo1KzbE3OuwAaS05.irt8MV5uY9bmkWmWgIjTKiLC', '88005553535', 'admin', '2025-05-28 20:33:42', 1, '2025-05-28 20:33:42', '2025-05-28 20:33:42', NULL),
(7, 'organizer@organizer', 'organizer@organizer', '$2y$12$quJIkHVdwGeDHpChz0wYBO3vUXUEkzD/zb0Bv1Q4eavZ371ltnjUm', '88005553535', 'organizer', '2025-05-30 04:31:40', 1, '2025-05-30 04:31:40', '2025-05-30 04:31:40', NULL),
(8, 'organizer@organizer1', 'organizer@organizer1', '$2y$12$cScv449kg0Gv5NL/3ThBM.wwcmKad6.DQZV.WE7NwIVJ0ZQDzaSS.', '88005553535', 'organizer', '2025-05-30 04:32:38', 1, '2025-05-30 04:32:38', '2025-05-30 04:32:38', NULL),
(10, 'www', 'www@www', '$2y$12$n/urbR73nD.mH3UIX3aOYe1c7.TwUMWfA.EGsnnAslNF5nzDLmOA2', 'www@www', 'organizer', '2025-05-30 05:27:04', 1, '2025-05-30 05:27:04', '2025-05-30 05:27:04', NULL),
(11, 'rrrrrrrrrrr', '777@rrrrrrrrrrr', '$2y$12$7wvAjk6MuXV9zgrzbUwnnOYuscF.jEHj/GgLHreQszCuh2dDe0oqu', 'rrrrrrrrrrr', 'user', '2025-05-30 07:03:43', 1, '2025-05-31 01:10:46', '2025-05-30 07:03:43', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Bookings`
--
ALTER TABLE `Bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Индексы таблицы `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Индексы таблицы `Favorites`
--
ALTER TABLE `Favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Индексы таблицы `News`
--
ALTER TABLE `News`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Индексы таблицы `Notifications`
--
ALTER TABLE `Notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Organizers`
--
ALTER TABLE `Organizers`
  ADD PRIMARY KEY (`organizer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Индексы таблицы `Seats`
--
ALTER TABLE `Seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Индексы таблицы `Tickets`
--
ALTER TABLE `Tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `event_id` (`event_id`);

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
-- AUTO_INCREMENT для таблицы `Bookings`
--
ALTER TABLE `Bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `Events`
--
ALTER TABLE `Events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `Favorites`
--
ALTER TABLE `Favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `News`
--
ALTER TABLE `News`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Notifications`
--
ALTER TABLE `Notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Organizers`
--
ALTER TABLE `Organizers`
  MODIFY `organizer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Seats`
--
ALTER TABLE `Seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `Tickets`
--
ALTER TABLE `Tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Bookings`
--
ALTER TABLE `Bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `Tickets` (`ticket_id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`seat_id`) REFERENCES `Seats` (`seat_id`);

--
-- Ограничения внешнего ключа таблицы `Events`
--
ALTER TABLE `Events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `Organizers` (`organizer_id`);

--
-- Ограничения внешнего ключа таблицы `Favorites`
--
ALTER TABLE `Favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `Events` (`event_id`);

--
-- Ограничения внешнего ключа таблицы `News`
--
ALTER TABLE `News`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `Organizers` (`organizer_id`);

--
-- Ограничения внешнего ключа таблицы `Notifications`
--
ALTER TABLE `Notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `Organizers`
--
ALTER TABLE `Organizers`
  ADD CONSTRAINT `organizers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `Events` (`event_id`);

--
-- Ограничения внешнего ключа таблицы `Seats`
--
ALTER TABLE `Seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`event_id`);

--
-- Ограничения внешнего ключа таблицы `Tickets`
--
ALTER TABLE `Tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

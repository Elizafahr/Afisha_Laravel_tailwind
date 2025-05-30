-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 13 2025 г., 09:03
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `а`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Bookings`
--

CREATE TABLE `Bookings` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `ticket_id` int DEFAULT NULL,
  `seat_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL
) ;

--
-- Дамп данных таблицы `Bookings`
--

INSERT INTO `Bookings` (`booking_id`, `user_id`, `ticket_id`, `seat_id`, `quantity`, `total_price`, `booking_date`, `status`, `payment_method`) VALUES
(1, 1, 1, NULL, 2, '3000.00', '2025-05-13 06:03:09', 'confirmed', 'credit_card'),
(2, 2, NULL, 1, 1, '1200.00', '2025-05-13 06:03:09', 'confirmed', 'online_payment'),
(3, 1, 3, NULL, 1, '800.00', '2025-05-13 06:03:09', 'pending', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `Events`
--

CREATE TABLE `Events` (
  `event_id` int NOT NULL,
  `organizer_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `category` varchar(50) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `age_restriction` int DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Events`
--

INSERT INTO `Events` (`event_id`, `organizer_id`, `title`, `description`, `category`, `start_datetime`, `end_datetime`, `location`, `age_restriction`, `poster_url`, `is_published`, `created_at`) VALUES
(1, 1, 'Рок-фестиваль \"Лето\"', 'Ежегодный рок-фестиваль с участием лучших групп страны', 'concert', '2025-06-15 18:00:00', '2025-06-15 23:00:00', 'Парк Горького, главная сцена', 16, 'https://example.com/posters/rock_fest.jpg', 1, '2025-05-13 06:03:08'),
(2, 1, 'Джаз под звездами', 'Вечер джазовой музыки под открытым небом', 'concert', '2025-07-20 19:00:00', '2025-07-20 22:00:00', 'Летний театр', 12, 'https://example.com/posters/jazz_night.jpg', 1, '2025-05-13 06:03:08'),
(3, 2, 'Городской день рождения', 'Празднование дня города с фейерверком', 'festival', '2025-08-30 12:00:00', '2025-08-30 23:59:00', 'Центральная площадь', 0, 'https://example.com/posters/city_day.jpg', 1, '2025-05-13 06:03:08');

-- --------------------------------------------------------

--
-- Структура таблицы `Favorites`
--

CREATE TABLE `Favorites` (
  `favorite_id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Favorites`
--

INSERT INTO `Favorites` (`favorite_id`, `user_id`, `event_id`, `added_at`) VALUES
(1, 1, 2, '2025-05-13 06:03:09'),
(2, 2, 1, '2025-05-13 06:03:09'),
(3, 2, 3, '2025-05-13 06:03:09');

-- --------------------------------------------------------

--
-- Структура таблицы `News`
--

CREATE TABLE `News` (
  `news_id` int NOT NULL,
  `organizer_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_pinned` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `News`
--

INSERT INTO `News` (`news_id`, `organizer_id`, `title`, `content`, `image_url`, `created_at`, `is_pinned`) VALUES
(1, 1, 'Изменение в программе рок-фестиваля', 'Группа \"Алиса\" не сможет выступить на фестивале, вместо нее будет группа \"Кино\"', 'https://example.com/news/rock_change.jpg', '2025-05-13 06:03:08', 1),
(2, 2, 'Программа дня города', 'Опубликована полная программа празднования дня города', 'https://example.com/news/city_program.jpg', '2025-05-13 06:03:08', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `Notifications`
--

CREATE TABLE `Notifications` (
  `notification_id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `organizer_id` int NOT NULL,
  `user_id` int NOT NULL,
  `organization_name` varchar(100) NOT NULL,
  `description` text,
  `logo_url` varchar(255) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Organizers`
--

INSERT INTO `Organizers` (`organizer_id`, `user_id`, `organization_name`, `description`, `logo_url`, `contact_person`, `is_verified`) VALUES
(1, 3, 'Концертное агентство \"Мелодия\"', 'Организация концертов и музыкальных мероприятий', 'https://example.com/logos/melody.jpg', 'Сидоров Алексей', 1),
(2, 4, 'Администрация города', 'Официальные городские мероприятия', 'https://example.com/logos/city_admin.jpg', 'Иванова Мария', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `Reviews`
--

CREATE TABLE `Reviews` (
  `review_id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `seat_id` int NOT NULL,
  `event_id` int NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `zone` varchar(50) DEFAULT NULL,
  `seat_row` int DEFAULT NULL COMMENT 'Номер ряда (вместо row_number, который является зарезервированным словом)',
  `is_reserved` tinyint(1) DEFAULT '0',
  `price_multiplier` decimal(3,2) DEFAULT '1.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Seats`
--

INSERT INTO `Seats` (`seat_id`, `event_id`, `seat_number`, `zone`, `seat_row`, `is_reserved`, `price_multiplier`) VALUES
(1, 2, 'A1', 'Партер', 1, 0, '1.50'),
(2, 2, 'A2', 'Партер', 1, 0, '1.50'),
(3, 2, 'B1', 'Балкон', 2, 0, '1.00'),
(4, 2, 'B2', 'Балкон', 2, 0, '1.00'),
(5, 2, 'C1', 'Ложа', 3, 0, '2.00');

-- --------------------------------------------------------

--
-- Структура таблицы `Tickets`
--

CREATE TABLE `Tickets` (
  `ticket_id` int NOT NULL,
  `event_id` int NOT NULL,
  `ticket_type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity_available` int NOT NULL,
  `booking_start` datetime DEFAULT NULL,
  `booking_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Tickets`
--

INSERT INTO `Tickets` (`ticket_id`, `event_id`, `ticket_type`, `price`, `quantity_available`, `booking_start`, `booking_end`) VALUES
(1, 1, 'Стандарт', '1500.00', 200, '2025-05-01 00:00:00', '2025-06-14 23:59:59'),
(2, 1, 'VIP', '3500.00', 50, '2025-05-01 00:00:00', '2025-06-14 23:59:59'),
(3, 2, 'Общий вход', '800.00', 150, '2025-06-01 00:00:00', '2025-07-19 23:59:59'),
(4, 3, 'Бесплатный вход', '0.00', 1000, '2025-07-01 00:00:00', '2025-08-29 23:59:59');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `email`, `password_hash`, `phone`, `role`, `registration_date`, `is_active`) VALUES
(1, 'ivanov', 'ivanov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79161234567', 'user', '2025-05-13 06:03:08', 1),
(2, 'petrova', 'petrova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79167654321', 'user', '2025-05-13 06:03:08', 1),
(3, 'sidorov', 'sidorov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79165554433', 'organizer', '2025-05-13 06:03:08', 1),
(4, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+79160000000', 'admin', '2025-05-13 06:03:08', 1);

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
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Events`
--
ALTER TABLE `Events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Favorites`
--
ALTER TABLE `Favorites`
  MODIFY `favorite_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `News`
--
ALTER TABLE `News`
  MODIFY `news_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Notifications`
--
ALTER TABLE `Notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Organizers`
--
ALTER TABLE `Organizers`
  MODIFY `organizer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Seats`
--
ALTER TABLE `Seats`
  MODIFY `seat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Tickets`
--
ALTER TABLE `Tickets`
  MODIFY `ticket_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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

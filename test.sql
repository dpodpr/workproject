-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 19 2025 г., 23:30
-- Версия сервера: 5.6.51
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `citizenship` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `passport` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passport_expiry` date NOT NULL,
  `passport_pdf` blob,
  `signed_docs` blob,
  `in_russia` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `speak_russian` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agree` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `fullname`, `citizenship`, `birthdate`, `passport`, `passport_expiry`, `passport_pdf`, `signed_docs`, `in_russia`, `speak_russian`, `agree`, `created_at`) VALUES
(1, '1111111', '$2y$10$T2dRENcY.zh/cTs5KY97Tu6xGuxHVcxcouytGaIuJFAwYASC.YY2W', '11111111111', '1111111111', '0000-00-00', '11111111111111', '0000-00-00', 0x75706c6f6164732f, 0x75706c6f6164732f, 'yes', 'yes', 1, '2025-04-19 11:05:19'),
(2, 'submitForm', '$2y$10$OFTS4MPgS3AD3oPHyinZ0ebDaa9ci8Sa2YAd.JjBr/cHBwhgW4kmC', 'submitForm', 'submitForm', '0000-00-00', '111111111111111', '0000-00-00', 0x75706c6f6164732f, 0x75706c6f6164732f, 'yes', 'yes', 1, '2025-04-19 11:12:34'),
(3, '555555', '$2y$10$aORq.Jjr.76s5zot0za4fOAIT9zinpSY3Fq5/Nvn.SJsRx3BaSooq', '5555555555', '55555555', '0000-00-00', '555555555555', '0000-00-00', 0x75706c6f6164732f, 0x75706c6f6164732f, 'yes', 'yes', 1, '2025-04-19 12:36:08'),
(4, '66666', '$2y$10$KPrrC6qSulLdwSdc6dLQiuJzJ1FEZfRdpdwT6IZtDMfaXbRV0OZ1y', '66666', '66666', '0000-00-00', '66666', '0000-00-00', 0x75706c6f6164732f, 0x75706c6f6164732f, 'yes', 'yes', 1, '2025-04-19 13:25:13'),
(5, '23423234', '$2y$10$dMCdAdGsAHkqIiaj3p5KYe51QnK2HByaXRpA5SPwYNTSkl52Ow/Fe', '23423234', '23423234', '0000-00-00', '23423234', '0000-00-00', NULL, NULL, 'yes', 'yes', 1, '2025-04-19 14:21:38'),
(6, 'hui111', '$2y$10$I8eTQVKLEhiHR4QiUfhkx.arlxmIOCxZyWK6.TN03NvSI8Grd0rTK', 'hui111', 'hui111', '0000-00-00', '1111111111', '0000-00-00', NULL, NULL, 'yes', 'yes', 1, '2025-04-19 14:25:04');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

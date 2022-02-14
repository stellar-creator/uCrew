-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Фев 13 2022 г., 20:11
-- Версия сервера: 5.7.37-0ubuntu0.18.04.1
-- Версия PHP: 7.2.24-0ubuntu0.18.04.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ucrew_database`
--
DROP DATABASE IF EXISTS `ucrew_database`;
CREATE DATABASE IF NOT EXISTS `ucrew_database` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ucrew_database`;

-- --------------------------------------------------------

--
-- Структура таблицы `uc_events`
--

DROP TABLE IF EXISTS `uc_events`;
CREATE TABLE `uc_events` (
  `event_id` int(11) NOT NULL,
  `event_user_id` int(11) NOT NULL,
  `event_name` varchar(1024) NOT NULL,
  `event_text` varchar(2048) NOT NULL,
  `event_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_wasviewed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `uc_groups`
--

DROP TABLE IF EXISTS `uc_groups`;
CREATE TABLE `uc_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(128) NOT NULL,
  `group_privileges` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `uc_locations`
--

DROP TABLE IF EXISTS `uc_locations`;
CREATE TABLE `uc_locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `uc_posts`
--

DROP TABLE IF EXISTS `uc_posts`;
CREATE TABLE `uc_posts` (
  `post_id` int(11) NOT NULL,
  `post_name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `uc_users`
--

DROP TABLE IF EXISTS `uc_users`;
CREATE TABLE `uc_users` (
  `user_id` int(11) NOT NULL COMMENT 'Inuque user id',
  `user_name` varchar(128) NOT NULL COMMENT 'User name',
  `user_email` varchar(128) NOT NULL COMMENT 'User E-mail',
  `user_password` varchar(128) NOT NULL,
  `user_status` int(3) NOT NULL DEFAULT '0' COMMENT 'User system status',
  `user_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'User register date',
  `user_image` varchar(2048) NOT NULL COMMENT 'User image (avatar)',
  `user_phone` varchar(32) NOT NULL COMMENT 'User phone number',
  `user_location` int(11) NOT NULL,
  `user_post` int(11) NOT NULL,
  `user_groups` varchar(256) NOT NULL DEFAULT '0;' COMMENT 'User groups privileges list'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='uCrew users table';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `uc_events`
--
ALTER TABLE `uc_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Индексы таблицы `uc_groups`
--
ALTER TABLE `uc_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Индексы таблицы `uc_locations`
--
ALTER TABLE `uc_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Индексы таблицы `uc_posts`
--
ALTER TABLE `uc_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Индексы таблицы `uc_users`
--
ALTER TABLE `uc_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `uc_events`
--
ALTER TABLE `uc_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uc_groups`
--
ALTER TABLE `uc_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uc_locations`
--
ALTER TABLE `uc_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uc_posts`
--
ALTER TABLE `uc_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uc_users`
--
ALTER TABLE `uc_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Inuque user id';--
-- База данных: `ucrew_storage`
--
DROP DATABASE IF EXISTS `ucrew_storage`;
CREATE DATABASE IF NOT EXISTS `ucrew_storage` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ucrew_storage`;

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_categories`
--

DROP TABLE IF EXISTS `ucs_categories`;
CREATE TABLE `ucs_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(256) NOT NULL,
  `category_description` varchar(2048) NOT NULL,
  `category_for` int(11) NOT NULL DEFAULT '0',
  `category_image` varchar(2048) NOT NULL,
  `category_status` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_items`
--

DROP TABLE IF EXISTS `ucs_items`;
CREATE TABLE `ucs_items` (
  `item_id` int(11) NOT NULL,
  `item_category` int(11) NOT NULL,
  `item_name` varchar(1024) NOT NULL,
  `item_data` text NOT NULL,
  `item_composite` int(2) NOT NULL,
  `item_components` text NOT NULL,
  `item_ manufacturer` int(11) NOT NULL,
  `item_suppliers` varchar(128) NOT NULL,
  `item_location` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_locations`
--

DROP TABLE IF EXISTS `ucs_locations`;
CREATE TABLE `ucs_locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_suppliers`
--

DROP TABLE IF EXISTS `ucs_suppliers`;
CREATE TABLE `ucs_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(512) NOT NULL,
  `supplier_description` text NOT NULL,
  `supplier_address` varchar(1024) NOT NULL,
  `supplier_site` varchar(1024) NOT NULL,
  `supplier_phone` varchar(32) NOT NULL,
  `supplier_email` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ucs_categories`
--
ALTER TABLE `ucs_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `ucs_items`
--
ALTER TABLE `ucs_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Индексы таблицы `ucs_locations`
--
ALTER TABLE `ucs_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Индексы таблицы `ucs_suppliers`
--
ALTER TABLE `ucs_suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ucs_categories`
--
ALTER TABLE `ucs_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ucs_items`
--
ALTER TABLE `ucs_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ucs_locations`
--
ALTER TABLE `ucs_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ucs_suppliers`
--
ALTER TABLE `ucs_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Мар 04 2022 г., 00:21
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
-- Структура таблицы `uc_chats`
--
-- Создание: Фев 15 2022 г., 05:43
--

DROP TABLE IF EXISTS `uc_chats`;
CREATE TABLE `uc_chats` (
  `chat_id` int(11) NOT NULL,
  `chat_from` int(11) NOT NULL,
  `chat_to` int(11) NOT NULL,
  `chat_primary` int(11) NOT NULL,
  `chat_activated` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_chats`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_events`
--
-- Создание: Фев 06 2022 г., 12:57
-- Последнее обновление: Мар 03 2022 г., 18:37
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

--
-- СВЯЗИ ТАБЛИЦЫ `uc_events`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_groups`
--
-- Создание: Фев 06 2022 г., 11:58
--

DROP TABLE IF EXISTS `uc_groups`;
CREATE TABLE `uc_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(128) NOT NULL,
  `group_privileges` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_groups`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_locations`
--
-- Создание: Фев 06 2022 г., 16:43
--

DROP TABLE IF EXISTS `uc_locations`;
CREATE TABLE `uc_locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_locations`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_messages`
--
-- Создание: Фев 15 2022 г., 05:22
--

DROP TABLE IF EXISTS `uc_messages`;
CREATE TABLE `uc_messages` (
  `message_id` int(11) NOT NULL,
  `message_chat` int(11) NOT NULL,
  `message_from` int(11) NOT NULL,
  `message_to` int(11) NOT NULL,
  `message_title` varchar(1024) NOT NULL,
  `message_text` text NOT NULL,
  `message_read` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_messages`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_posts`
--
-- Создание: Фев 06 2022 г., 17:38
--

DROP TABLE IF EXISTS `uc_posts`;
CREATE TABLE `uc_posts` (
  `post_id` int(11) NOT NULL,
  `post_name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_posts`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_settings`
--
-- Создание: Фев 19 2022 г., 15:58
--

DROP TABLE IF EXISTS `uc_settings`;
CREATE TABLE `uc_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(128) NOT NULL,
  `setting_value` varchar(32) NOT NULL,
  `setting_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_settings`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_users`
--
-- Создание: Фев 06 2022 г., 16:52
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
-- СВЯЗИ ТАБЛИЦЫ `uc_users`:
--

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `uc_chats`
--
ALTER TABLE `uc_chats`
  ADD PRIMARY KEY (`chat_id`);

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
-- Индексы таблицы `uc_messages`
--
ALTER TABLE `uc_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Индексы таблицы `uc_posts`
--
ALTER TABLE `uc_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Индексы таблицы `uc_settings`
--
ALTER TABLE `uc_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Индексы таблицы `uc_users`
--
ALTER TABLE `uc_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `uc_chats`
--
ALTER TABLE `uc_chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT для таблицы `uc_messages`
--
ALTER TABLE `uc_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uc_posts`
--
ALTER TABLE `uc_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uc_settings`
--
ALTER TABLE `uc_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uc_users`
--
ALTER TABLE `uc_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Inuque user id';

--
-- Метаданные
--
USE `phpmyadmin`;

--
-- Метаданные для таблицы uc_chats
--

--
-- Метаданные для таблицы uc_events
--

--
-- Дамп данных таблицы `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'ucrew_database', 'uc_events', '{\"sorted_col\":\"`uc_events`.`event_id`  DESC\"}', '2022-02-06 14:17:28');

--
-- Метаданные для таблицы uc_groups
--

--
-- Метаданные для таблицы uc_locations
--

--
-- Дамп данных таблицы `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'ucrew_database', 'uc_locations', '{\"sorted_col\":\"`uc_locations`.`location_id` ASC\"}', '2022-02-06 17:40:06');

--
-- Метаданные для таблицы uc_messages
--

--
-- Метаданные для таблицы uc_posts
--

--
-- Дамп данных таблицы `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'ucrew_database', 'uc_posts', '[]', '2022-02-06 17:38:32');

--
-- Метаданные для таблицы uc_settings
--

--
-- Метаданные для таблицы uc_users
--

--
-- Дамп данных таблицы `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'ucrew_database', 'uc_users', '{\"sorted_col\":\"`uc_users`.`user_name` ASC\"}', '2022-02-15 04:49:52');

--
-- Метаданные для базы данных ucrew_database
--
--
-- База данных: `ucrew_projects`
--
DROP DATABASE IF EXISTS `ucrew_projects`;
CREATE DATABASE IF NOT EXISTS `ucrew_projects` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ucrew_projects`;

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_data`
--
-- Создание: Мар 02 2022 г., 17:51
--

DROP TABLE IF EXISTS `ucp_data`;
CREATE TABLE `ucp_data` (
  `data_id` int(11) NOT NULL,
  `data_name` varchar(256) NOT NULL,
  `data_value` int(11) NOT NULL,
  `data_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_data`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_mechanics`
--
-- Создание: Мар 02 2022 г., 18:49
-- Последнее обновление: Мар 02 2022 г., 18:54
--

DROP TABLE IF EXISTS `ucp_mechanics`;
CREATE TABLE `ucp_mechanics` (
  `mechanic_id` int(11) NOT NULL,
  `mechanic_name` varchar(256) NOT NULL,
  `mechanic_description` varchar(2048) NOT NULL,
  `mechanic_codename` varchar(256) NOT NULL,
  `mechanic_author_id` int(1) NOT NULL,
  `mechanic_create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mechanic_status` int(2) NOT NULL,
  `mechanic_image` varchar(1024) NOT NULL,
  `mechanic_data` text NOT NULL,
  `mechanic_activation` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_mechanics`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_pcb`
--
-- Создание: Мар 02 2022 г., 17:53
--

DROP TABLE IF EXISTS `ucp_pcb`;
CREATE TABLE `ucp_pcb` (
  `pcb_id` int(11) NOT NULL,
  `pcb_name` varchar(256) NOT NULL,
  `pcb_description` varchar(1024) NOT NULL,
  `pcb_codename` varchar(32) NOT NULL,
  `pcb_author_id` int(11) NOT NULL,
  `pcb_create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pcb_status` int(2) NOT NULL,
  `pcb_image` varchar(1024) NOT NULL,
  `pcb_data` text NOT NULL,
  `pcb_activation` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_pcb`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_projects`
--
-- Создание: Мар 02 2022 г., 17:52
--

DROP TABLE IF EXISTS `ucp_projects`;
CREATE TABLE `ucp_projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(256) NOT NULL,
  `project_description` varchar(2048) NOT NULL,
  `project_codename` varchar(256) NOT NULL,
  `project_author_id` int(11) NOT NULL,
  `project_create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_status` int(2) NOT NULL,
  `project_image` varchar(1024) NOT NULL,
  `project_data` text NOT NULL,
  `project_activation` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_projects`:
--

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ucp_data`
--
ALTER TABLE `ucp_data`
  ADD PRIMARY KEY (`data_id`);

--
-- Индексы таблицы `ucp_mechanics`
--
ALTER TABLE `ucp_mechanics`
  ADD PRIMARY KEY (`mechanic_id`);

--
-- Индексы таблицы `ucp_pcb`
--
ALTER TABLE `ucp_pcb`
  ADD PRIMARY KEY (`pcb_id`);

--
-- Индексы таблицы `ucp_projects`
--
ALTER TABLE `ucp_projects`
  ADD PRIMARY KEY (`project_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ucp_data`
--
ALTER TABLE `ucp_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ucp_mechanics`
--
ALTER TABLE `ucp_mechanics`
  MODIFY `mechanic_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ucp_pcb`
--
ALTER TABLE `ucp_pcb`
  MODIFY `pcb_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ucp_projects`
--
ALTER TABLE `ucp_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Метаданные
--
USE `phpmyadmin`;

--
-- Метаданные для таблицы ucp_data
--

--
-- Метаданные для таблицы ucp_mechanics
--

--
-- Дамп данных таблицы `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'ucrew_projects', 'ucp_mechanics', '{\"sorted_col\":\"`ucp_mechanics`.`mechanic_codename`  DESC\"}', '2022-03-02 18:56:09');

--
-- Метаданные для таблицы ucp_pcb
--

--
-- Метаданные для таблицы ucp_projects
--

--
-- Метаданные для базы данных ucrew_projects
--
--
-- База данных: `ucrew_storage`
--
DROP DATABASE IF EXISTS `ucrew_storage`;
CREATE DATABASE IF NOT EXISTS `ucrew_storage` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ucrew_storage`;

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_categories`
--
-- Создание: Фев 07 2022 г., 14:37
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

--
-- СВЯЗИ ТАБЛИЦЫ `ucs_categories`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_items`
--
-- Создание: Фев 14 2022 г., 16:45
--

DROP TABLE IF EXISTS `ucs_items`;
CREATE TABLE `ucs_items` (
  `item_id` int(11) NOT NULL,
  `item_category` int(11) NOT NULL,
  `item_name` varchar(1024) NOT NULL,
  `item_data` text NOT NULL,
  `item_deleted` int(1) NOT NULL DEFAULT '0',
  `item_count` int(11) NOT NULL,
  `item_composite` int(2) NOT NULL,
  `item_components` text NOT NULL,
  `item_ manufacturer` int(11) NOT NULL,
  `item_suppliers` varchar(128) NOT NULL,
  `item_location` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucs_items`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_locations`
--
-- Создание: Фев 08 2022 г., 15:28
--

DROP TABLE IF EXISTS `ucs_locations`;
CREATE TABLE `ucs_locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucs_locations`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_suppliers`
--
-- Создание: Фев 08 2022 г., 15:27
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
-- СВЯЗИ ТАБЛИЦЫ `ucs_suppliers`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucs_templates`
--
-- Создание: Фев 15 2022 г., 09:32
--

DROP TABLE IF EXISTS `ucs_templates`;
CREATE TABLE `ucs_templates` (
  `template_id` int(11) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_data` text NOT NULL,
  `template_activated` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucs_templates`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `usc_releases`
--
-- Создание: Фев 19 2022 г., 19:23
--

DROP TABLE IF EXISTS `usc_releases`;
CREATE TABLE `usc_releases` (
  `release_id` int(11) NOT NULL,
  `release_item` int(11) NOT NULL,
  `release_count` int(11) NOT NULL,
  `release_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `release_user` int(11) NOT NULL,
  `release_comment` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `usc_releases`:
--

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
-- Индексы таблицы `ucs_templates`
--
ALTER TABLE `ucs_templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Индексы таблицы `usc_releases`
--
ALTER TABLE `usc_releases`
  ADD PRIMARY KEY (`release_id`);

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
--
-- AUTO_INCREMENT для таблицы `ucs_templates`
--
ALTER TABLE `ucs_templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `usc_releases`
--
ALTER TABLE `usc_releases`
  MODIFY `release_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Метаданные
--
USE `phpmyadmin`;

--
-- Метаданные для таблицы ucs_categories
--

--
-- Метаданные для таблицы ucs_items
--

--
-- Метаданные для таблицы ucs_locations
--

--
-- Дамп данных таблицы `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'ucrew_storage', 'ucs_locations', '{\"sorted_col\":\"`ucs_locations`.`location_id`  DESC\"}', '2022-02-08 16:23:31');

--
-- Метаданные для таблицы ucs_suppliers
--

--
-- Дамп данных таблицы `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'ucrew_storage', 'ucs_suppliers', '{\"sorted_col\":\"`ucs_suppliers`.`supplier_id`  DESC\"}', '2022-02-08 16:24:21');

--
-- Метаданные для таблицы ucs_templates
--

--
-- Метаданные для таблицы usc_releases
--

--
-- Метаданные для базы данных ucrew_storage
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

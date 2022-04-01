-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Апр 02 2022 г., 01:02
-- Версия сервера: 5.7.37-0ubuntu0.18.04.1
-- Версия PHP: 7.2.24-0ubuntu0.18.04.11

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
CREATE TABLE IF NOT EXISTS `uc_chats` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_from` int(11) NOT NULL,
  `chat_to` int(11) NOT NULL,
  `chat_primary` int(11) NOT NULL,
  `chat_activated` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_chats`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_events`
--
-- Создание: Фев 06 2022 г., 12:57
-- Последнее обновление: Апр 01 2022 г., 19:49
--

DROP TABLE IF EXISTS `uc_events`;
CREATE TABLE IF NOT EXISTS `uc_events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_user_id` int(11) NOT NULL,
  `event_name` varchar(1024) NOT NULL,
  `event_text` varchar(2048) NOT NULL,
  `event_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_wasviewed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
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
CREATE TABLE IF NOT EXISTS `uc_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(128) NOT NULL,
  `group_privileges` varchar(1024) NOT NULL,
  PRIMARY KEY (`group_id`)
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
CREATE TABLE IF NOT EXISTS `uc_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(2048) NOT NULL,
  PRIMARY KEY (`location_id`)
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
CREATE TABLE IF NOT EXISTS `uc_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_chat` int(11) NOT NULL,
  `message_from` int(11) NOT NULL,
  `message_to` int(11) NOT NULL,
  `message_title` varchar(1024) NOT NULL,
  `message_text` text NOT NULL,
  `message_read` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
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
CREATE TABLE IF NOT EXISTS `uc_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_name` varchar(512) NOT NULL,
  PRIMARY KEY (`post_id`)
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
CREATE TABLE IF NOT EXISTS `uc_settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(128) NOT NULL,
  `setting_value` varchar(32) NOT NULL,
  `setting_text` text NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `uc_settings`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `uc_users`
--
-- Создание: Апр 01 2022 г., 16:51
-- Последнее обновление: Апр 01 2022 г., 19:27
--

DROP TABLE IF EXISTS `uc_users`;
CREATE TABLE IF NOT EXISTS `uc_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Inuque user id',
  `user_name` varchar(128) NOT NULL COMMENT 'User name',
  `user_email` varchar(128) NOT NULL COMMENT 'User E-mail',
  `user_password` varchar(128) NOT NULL,
  `user_status` int(3) NOT NULL DEFAULT '0' COMMENT 'User system status',
  `user_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'User register date',
  `user_image` varchar(2048) NOT NULL COMMENT 'User image (avatar)',
  `user_phone` varchar(32) NOT NULL COMMENT 'User phone number',
  `user_location` int(11) NOT NULL,
  `user_post` int(11) NOT NULL,
  `user_groups` varchar(256) NOT NULL DEFAULT '0;' COMMENT 'User groups privileges list',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='uCrew users table';

--
-- СВЯЗИ ТАБЛИЦЫ `uc_users`:
--


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
-- Структура таблицы `ucp_cables`
--
-- Создание: Мар 27 2022 г., 15:21
--

DROP TABLE IF EXISTS `ucp_cables`;
CREATE TABLE IF NOT EXISTS `ucp_cables` (
  `cable_id` int(11) NOT NULL AUTO_INCREMENT,
  `cable_name` varchar(256) NOT NULL,
  `cable_description` varchar(2048) NOT NULL,
  `cable_codename` varchar(256) NOT NULL,
  `cable_author_id` int(1) NOT NULL,
  `cable_create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cable_status` int(2) NOT NULL,
  `cable_image` varchar(1024) NOT NULL,
  `cable_data` text NOT NULL,
  `cable_activation` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_cables`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_data`
--
-- Создание: Мар 27 2022 г., 06:38
--

DROP TABLE IF EXISTS `ucp_data`;
CREATE TABLE IF NOT EXISTS `ucp_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `data_name` varchar(256) NOT NULL,
  `data_value` int(11) NOT NULL DEFAULT '0',
  `data_text` text NOT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_data`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_mechanics`
--
-- Создание: Мар 13 2022 г., 10:02
--

DROP TABLE IF EXISTS `ucp_mechanics`;
CREATE TABLE IF NOT EXISTS `ucp_mechanics` (
  `mechanic_id` int(11) NOT NULL AUTO_INCREMENT,
  `mechanic_name` varchar(256) NOT NULL,
  `mechanic_description` varchar(2048) NOT NULL,
  `mechanic_codename` varchar(256) NOT NULL,
  `mechanic_author_id` int(1) NOT NULL,
  `mechanic_create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mechanic_status` int(2) NOT NULL,
  `mechanic_image` varchar(1024) NOT NULL,
  `mechanic_data` text NOT NULL,
  `mechanic_activation` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`mechanic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_mechanics`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_pcbs`
--
-- Создание: Мар 27 2022 г., 06:29
--

DROP TABLE IF EXISTS `ucp_pcbs`;
CREATE TABLE IF NOT EXISTS `ucp_pcbs` (
  `pcb_id` int(11) NOT NULL AUTO_INCREMENT,
  `pcb_name` varchar(256) NOT NULL,
  `pcb_description` varchar(2048) NOT NULL,
  `pcb_codename` varchar(256) NOT NULL,
  `pcb_author_id` int(1) NOT NULL,
  `pcb_create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pcb_status` int(2) NOT NULL,
  `pcb_image` varchar(1024) NOT NULL,
  `pcb_data` text NOT NULL,
  `pcb_activation` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pcb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_pcbs`:
--

-- --------------------------------------------------------

--
-- Структура таблицы `ucp_projects`
--
-- Создание: Мар 02 2022 г., 17:52
--

DROP TABLE IF EXISTS `ucp_projects`;
CREATE TABLE IF NOT EXISTS `ucp_projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(256) NOT NULL,
  `project_description` varchar(2048) NOT NULL,
  `project_codename` varchar(256) NOT NULL,
  `project_author_id` int(11) NOT NULL,
  `project_create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_status` int(2) NOT NULL,
  `project_image` varchar(1024) NOT NULL,
  `project_data` text NOT NULL,
  `project_activation` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `ucp_projects`:
--


--
-- Метаданные
--
USE `phpmyadmin`;

--
-- Метаданные для таблицы ucp_cables
--

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
('root', 'ucrew_projects', 'ucp_mechanics', '{\"sorted_col\":\"`mechanic_id`  DESC\"}', '2022-03-27 15:23:34');

--
-- Метаданные для таблицы ucp_pcbs
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
CREATE TABLE IF NOT EXISTS `ucs_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(256) NOT NULL,
  `category_description` varchar(2048) NOT NULL,
  `category_for` int(11) NOT NULL DEFAULT '0',
  `category_image` varchar(2048) NOT NULL,
  `category_status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
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
CREATE TABLE IF NOT EXISTS `ucs_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_category` int(11) NOT NULL,
  `item_name` varchar(1024) NOT NULL,
  `item_data` text NOT NULL,
  `item_deleted` int(1) NOT NULL DEFAULT '0',
  `item_count` int(11) NOT NULL,
  `item_composite` int(2) NOT NULL,
  `item_components` text NOT NULL,
  `item_ manufacturer` int(11) NOT NULL,
  `item_suppliers` varchar(128) NOT NULL,
  `item_location` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
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
CREATE TABLE IF NOT EXISTS `ucs_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(512) NOT NULL,
  PRIMARY KEY (`location_id`)
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
CREATE TABLE IF NOT EXISTS `ucs_suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(512) NOT NULL,
  `supplier_description` text NOT NULL,
  `supplier_address` varchar(1024) NOT NULL,
  `supplier_site` varchar(1024) NOT NULL,
  `supplier_phone` varchar(32) NOT NULL,
  `supplier_email` varchar(256) NOT NULL,
  PRIMARY KEY (`supplier_id`)
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
CREATE TABLE IF NOT EXISTS `ucs_templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_category` int(11) NOT NULL,
  `template_data` text NOT NULL,
  `template_activated` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`template_id`)
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
CREATE TABLE IF NOT EXISTS `usc_releases` (
  `release_id` int(11) NOT NULL AUTO_INCREMENT,
  `release_item` int(11) NOT NULL,
  `release_count` int(11) NOT NULL,
  `release_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `release_user` int(11) NOT NULL,
  `release_comment` varchar(1024) NOT NULL,
  PRIMARY KEY (`release_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `usc_releases`:
--


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

-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.1.40-community - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              8.1.0.4545
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных user_base
CREATE DATABASE IF NOT EXISTS `user_base` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `user_base`;


-- Дамп структуры для таблица user_base.user_info
CREATE TABLE IF NOT EXISTS `user_info` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT 'Jhon Dou',
  `mail` varchar(50) NOT NULL,
  `date_birth` date DEFAULT NULL,
  `sex` tinyint(1) NOT NULL,
  `pass` char(32) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `id` (`user_id`),
  UNIQUE KEY `mail` (`mail`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица user_base.user_pic
CREATE TABLE IF NOT EXISTS `user_pic` (
  `user_id` int(11) NOT NULL,
  `pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `user_pic_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_info` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица user_base.user_session
CREATE TABLE IF NOT EXISTS `user_session` (
  `user_id` int(11) NOT NULL,
  `hash` char(32) DEFAULT NULL,
  `user_ip` char(16) DEFAULT NULL,
  `lang` char(5) NOT NULL DEFAULT 'ru',
  `last_login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `hash` (`hash`),
  CONSTRAINT `user_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_info` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

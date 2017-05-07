CREATE DATABASE IF NOT EXISTS `yeticave_db`;
USE `yeticave_db`;

CREATE TABLE IF NOT EXISTS `binds` (
  `id` INT(10)  unsigned NOT NULL AUTO_INCREMENT COMMENT 'bind id',
  `user_id` INT(10)  unsigned NOT NULL COMMENT 'user id',
  `lot_id` INT(10)  unsigned NOT NULL COMMENT 'lot id',
  `price` INT(10)  unsigned NOT NULL COMMENT 'user bind for lot',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='сделанные ставки для лотов';

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT(10)  unsigned NOT NULL AUTO_INCREMENT COMMENT 'category id',
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='описание категорий';


CREATE TABLE IF NOT EXISTS `lots` (
  `id` INT(10)  unsigned NOT NULL AUTO_INCREMENT COMMENT 'lot id',
  `user_id` INT(10)  unsigned NOT NULL COMMENT 'user id',
  `category_id` INT(10)  unsigned NOT NULL COMMENT 'category id',
  `name` varchar(2500) NOT NULL COMMENT 'lot name (title)',
  `descriptioin` text COMMENT 'lot desc',
  `img_path` varchar(250) NOT NULL COMMENT 'lot image path',
  `start_price` INT(10)  NOT NULL DEFAULT '0' COMMENT 'start price of lot',
  `step` INT(10)  NOT NULL DEFAULT '0' COMMENT 'bind step',
  `end_date` datetime NOT NULL COMMENT 'when lot will be closed',
  `add_date` datetime NOT NULL COMMENT 'when lot has added',
  `winner` INT(10)  unsigned DEFAULT NULL COMMENT 'who win lot',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='описание лотов';


CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(10)  unsigned NOT NULL AUTO_INCREMENT COMMENT 'user id',
  `email` varchar(250) NOT NULL COMMENT 'user e-mail',
  `password` varchar(250) NOT NULL COMMENT 'hash of user password',
  `name` varchar(250) NOT NULL COMMENT 'user name',
  `contacts` varchar(250) NOT NULL COMMENT 'user contancts',
  `avatar_img` varchar(250) DEFAULT 'img/anon.jpg' COMMENT 'path to user avatar image',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Хранение данных о пользователях';

CREATE TABLE IF NOT EXISTS `favourites` (
	`id` INT(10)  UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT(10)  UNSIGNED NOT NULL,
	`lot_id` INT(10)  UNSIGNED NOT NULL,
	`count` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='для хранения количество добавлений в избранное';
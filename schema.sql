CREATE DATABASE IF NOT EXISTS `yeticave_db`;
USE `yeticave_db`;

CREATE TABLE IF NOT EXISTS `binds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'bind id',
  `u_id` bigint(20) unsigned NOT NULL COMMENT 'user id',
  `l_id` bigint(20) unsigned NOT NULL COMMENT 'lot id',
  `price` bigint(20) unsigned NOT NULL COMMENT 'user bind for lot',
  `bind_date` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='сделанные ставки для лотов';

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'category id',
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='описание категорий';


CREATE TABLE IF NOT EXISTS `lots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'lot id',
  `u_id` bigint(20) unsigned NOT NULL COMMENT 'user id',
  `c_id` bigint(20) unsigned NOT NULL COMMENT 'category id',
  `name` varchar(2500) NOT NULL COMMENT 'lot name (title)',
  `descriptioin` text COMMENT 'lot desc',
  `lot_img` varchar(250) NOT NULL COMMENT 'lot image path',
  `start_price` bigint(20) NOT NULL DEFAULT '0' COMMENT 'start price of lot',
  `bind_step` bigint(20) NOT NULL DEFAULT '0' COMMENT 'bind step',
  `end_date` datetime NOT NULL COMMENT 'when lot will be closed',
  `add_date` timestamp NOT NULL COMMENT 'when lot has added',
  `winner` bigint(20) unsigned DEFAULT NULL COMMENT 'who win lot',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='описание лотов';


CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'user id',
  `email` varchar(250) NOT NULL COMMENT 'user e-mail',
  `password` varchar(250) NOT NULL COMMENT 'hash of user password',
  `name` varchar(250) NOT NULL COMMENT 'user name',
  `contacts` varchar(250) NOT NULL COMMENT 'user contancts',
  `avatar_img` varchar(250) DEFAULT 'img/anon.jpg' COMMENT 'path to user avatar image',
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp when user register',
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp of last login',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Хранение данных о пользователях';


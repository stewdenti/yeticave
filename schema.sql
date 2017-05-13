CREATE DATABASE IF NOT EXISTS `yeticave_db`;
USE `yeticave_db`;

CREATE TABLE IF NOT EXISTS `binds` (
  `id` INT  unsigned NOT NULL AUTO_INCREMENT COMMENT 'bind id',
  `user_id` INT  unsigned NOT NULL COMMENT 'user id',
  `lot_id` INT  unsigned NOT NULL COMMENT 'lot id',
  `price` INT  unsigned NOT NULL COMMENT 'user bind for lot',
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='сделанные ставки для лотов';

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT  unsigned NOT NULL AUTO_INCREMENT COMMENT 'category id',
  `name` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='описание категорий';


CREATE TABLE IF NOT EXISTS `lots` (
  `id` INT  unsigned NOT NULL AUTO_INCREMENT COMMENT 'lot id',
  `user_id` INT  unsigned NOT NULL COMMENT 'user id',
  `category_id` INT  unsigned NOT NULL COMMENT 'category id',
  `name` VARCHAR(2500) NOT NULL COMMENT 'lot name (title)',
  `description` TEXT COMMENT 'lot desc',
  `img_path` VARCHAR(250) NOT NULL COMMENT 'lot image path',
  `start_price` INT  NOT NULL DEFAULT '0' COMMENT 'start price of lot',
  `step` INT  NOT NULL DEFAULT '0' COMMENT 'bind step',
  `end_date` DATETIME NOT NULL COMMENT 'when lot will be closed',
  `add_date` DATETIME NOT NULL COMMENT 'when lot has added',
  `winner` INT  unsigned DEFAULT NULL COMMENT 'who win lot',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='описание лотов';


CREATE TABLE IF NOT EXISTS `users` (
  `id` INT  unsigned NOT NULL AUTO_INCREMENT COMMENT 'user id',
  `email` VARCHAR(250) NOT NULL COMMENT 'user e-mail',
  `password` VARCHAR(250) NOT NULL COMMENT 'hash of user password',
  `name` VARCHAR(250) NOT NULL COMMENT 'user name',
  `contacts` VARCHAR(250) NOT NULL COMMENT 'user contancts',
  `avatar_img` VARCHAR(250) DEFAULT 'img/anon.jpg' COMMENT 'path to user avatar image',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Хранение данных о пользователях';

CREATE INDEX user_id ON binds(user_id);
CREATE INDEX lot_id ON binds(lot_id);
CREATE INDEX category_id ON lots(category_id);
CREATE UNIQUE INDEX `email` ON users(`email`);






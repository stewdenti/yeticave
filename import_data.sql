INSERT INTO `categories` (`name`) VALUES
  ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Одежда'),
  ('Инструменты'),
  ('Разное');

  INSERT INTO `users` (`email`, `password`, `name`, `contacts`, `avatar_img`) VALUES
  ('ignat.v@gmail.com', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', 'Игнат', '88005555555', 'img/anon.jpg'),
  ('kitty_93@li.ru', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', 'Леночка', '88006666666', 'img/anon.jpg'),
  ('warrior07@mail.ru', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', 'Руслан', '88007777777', 'img/anon.jpg');

INSERT INTO `lots` (`user_id`, `category_id`, `name`, `description`, `img_path`, `start_price`, `step`, `end_date`, `add_date`, `winner`) VALUES
  (1, 1, '014 Rossignol District Snowboard', 'NULL', '/img/lot-1.jpg', 10999, 500, '2017-06-06 19:57:41', '2017-05-06 19:57:45', NULL),
  (1, 1, 'DC Ply Mens 2016/2017 Snowboard', NULL, '/img/lot-2.jpg', 15999, 1000, '2017-05-19 20:00:32', '2017-05-06 20:00:39', NULL),
  (1, 2, 'Крепления Union Contact Pro 2015 года размер L/XL', NULL, '/img/lot-3.jpg', 8000, 500, '2017-05-31 22:02:12', '2017-05-06 20:02:20', NULL),
  (1, 3, 'Ботинки для сноуборда DC Mutiny Charocal', NULL, '/img/lot-4.jpg', 11111, 50, '2017-08-07 08:03:19', '2017-05-06 20:03:25', NULL),
  (1, 4, 'Куртка для сноуборда DC Mutiny Charocal', NULL, '/img/lot-5.jpg', 7500, 150, '2017-12-06 20:05:11', '2017-05-06 20:05:20', NULL),
  (1, 6, 'Маска Oakley Canopy', NULL, '/img/lot-6.jpg', 5400, 60, '2018-06-06 20:07:17', '2017-05-06 20:07:23', NULL);

INSERT INTO `binds` (`user_id`, `lot_id`, `price`, `date`) VALUES
  (2, 1, 13900, '2017-05-06 20:08:28'),
  (3, 1, 14900, '2017-05-06 20:09:28'),
  (2, 1, 18000, '2017-05-06 20:10:29'),
  (3, 1, 16000, '2017-05-06 20:10:57'),
  (2, 1, 10000, '2017-05-06 20:13:20');

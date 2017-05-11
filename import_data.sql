INSERT INTO `categories` (`id`, `name`) VALUES
  (1, 'Доски и лыжи'),
  (2, 'Крепления'),
  (3, 'Ботинки'),
  (4, 'Одежда'),
  (5, 'Инструменты'),
  (6, 'Разное');

  INSERT INTO `users` (`id`, `email`, `password`, `name`, `contacts`, `avatar_img`) VALUES
  (1, 'ignat.v@gmail.com', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', 'Игнат', '88005555555', 'img/anon.jpg'),
  (2, 'kitty_93@li.ru', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', 'Леночка', '88006666666', 'img/anon.jpg'),
  (3, 'warrior07@mail.ru', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', 'Руслан', '88007777777', 'img/anon.jpg');

INSERT INTO `lots` (`id`, `user_id`, `category_id`, `name`, `descriptioin`, `img_path`, `start_price`, `step`, `end_date`, `add_date`, `winner`) VALUES
  (1, 1, 1, '2014 Rossignol District Snowboard', 'NULL', '/img/lot-1.jpg', 10999, 500, '2017-06-06 19:57:41', '2017-05-06 19:57:45', 3),
  (2, 1, 1, 'DC Ply Mens 2016/2017 Snowboard', NULL, '/img/lot-2.jpg', 15999, 1000, '2017-05-15 20:00:32', '2017-05-06 20:00:39', 2),
  (3, 1, 2, 'Крепления Union Contact Pro 2015 года размер L/XL', NULL, '/img/lot-3.jpg', 8000, 500, '2017-05-13 22:02:12', '2017-05-06 20:02:20', 3),
  (4, 1, 3, 'Ботинки для сноуборда DC Mutiny Charocal', NULL, '/img/lot-4.jpg', 11111, 50, '2017-05-07 08:03:19', '2017-05-06 20:03:25', NULL),
  (5, 1, 4, 'Куртка для сноуборда DC Mutiny Charocal', NULL, '/img/lot-5.jpg', 7500, 150, '2017-12-06 20:05:11', '2017-05-06 20:05:20', NULL),
  (6, 1, 6, 'Маска Oakley Canopy', NULL, '/img/lot-6.jpg', 5400, 60, '2018-05-06 20:07:17', '2017-05-06 20:07:23', 6);

INSERT INTO `binds` (`id`, `user_id`, `lot_id`, `price`, `date`) VALUES
  (1, 2, 1, 13900, '2017-05-06 20:08:28'),
  (2, 3, 1, 14900, '2017-05-06 20:09:28'),
  (3, 2, 1, 18000, '2017-05-06 20:10:29'),
  (4, 3, 1, 16000, '2017-05-06 20:10:57'),
  (5, 2, 1, 10000, '2017-05-06 20:13:20');

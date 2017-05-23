<?php
include ("functions.php");
//инициализируем сессию
Authorization::logout();
//перенапровляем на главную страницу
header("Location: /index.php");


?>

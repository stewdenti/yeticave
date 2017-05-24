<?php
include ("functions.php");
//инициализируем сессию
session_start();

Authorization::logout();
//перенапровляем на главную страницу
header("Location: /index.php");


?>

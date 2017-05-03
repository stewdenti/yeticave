<?php
//инициализируем сессию
session_start();
//сбрасываем все переменные
session_unset();
//разрушаем сессию
session_destroy();
//перенапровляем на главную страницу
header("Location: /index.php");


?>
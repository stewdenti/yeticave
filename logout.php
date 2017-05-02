<?php
session_start();
//разлогинить пользователя
unset($_SESSION['user']);

header("Location: /index.php");


?>
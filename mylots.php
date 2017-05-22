<?php
include ('functions.php');
include ('Classes/DB.php');
include ('Classes/Authenticate.php');
include ("Classes/Categories.php");
include ("Classes/Binds.php");
include ("Classes/Templates.php");

// проверяем авторизацию пользователя
session_start();
// $user_data = requireAuthentication(true);
DB::getConnection();
$user = new Authenticate();
$user->blockAccess();

$categories = Categories::getAll();
$header_data = $user->getAuthorizedData();

$data_footer["categories_equipment"] = $categories;

$data["rates"] = Binds::getAllLotsByUser($user->getAuthorizedData("id"));

echo Templates::render("templates/header.php", $header_data);
echo Templates::render("templates/mylot-main.php", $data);
echo Templates::render("templates/footer.php", $data_footer);

?>

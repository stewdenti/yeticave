<?php
include ('functions.php');
include ('Classes/DB.php');
include ('Classes/Authenticate.php');

// проверяем авторизацию пользователя
session_start();
// $user_data = requireAuthentication(true);
DB::getConnection();
$user = new Authenticate();
$user->blockAccess();

$categories = getAllCategories();
$header_data = $user->getAuthorizedData();

$data_footer["categories_equipment"] = $categories;

$data["rates"] = getAllBindedLotsByUser($user->getAuthorizedData("id"));

echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/mylot-main.php", $data);
echo connectTemplates("templates/footer.php", $data_footer);

?>

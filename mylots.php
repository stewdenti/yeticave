<?php
include ('functions.php');
// проверяем авторизацию пользователя
// $user_data = requireAuthentication(true);
Authorization::blockAccess();

$categories = Category::getAll();
$header_data = Authorization::getAuthData();

$data_footer["categories_equipment"] = $categories;

$data["rates"] = Lot::getAllBindsByUser($header_data["user_id"]);

echo Templates::render("templates/header.php", $header_data);
echo Templates::render("templates/mylot-main.php", $data);
echo Templates::render("templates/footer.php", $data_footer);

?>

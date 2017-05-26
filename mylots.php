<?php

include ('autoload.php');

session_start();
// проверяем авторизацию пользователя
// $user_data = requireAuthentication(true);
Authorization::blockAccess();

$categories = CategoryFinder::getAll();
$header_data["user"] = Authorization::getAuthData();

$data_footer["categories_equipment"] = $categories;

$data["rates"] = BindFinder::getByUserId($header_data["user"]->id);

echo Templates::render("templates/header.php", $header_data);
echo Templates::render("templates/mylot-main.php", $data);
echo Templates::render("templates/footer.php", $data_footer);

?>

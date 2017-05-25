<?php

include ('autoload.php');

session_start();


$header_data["user"] = Authorization::getAuthData();

$categories_list = Category::getAll();

if (!empty($_REQUEST["id"])) {
    $lots_list = Lot::getByCategoryId($_REQUEST["id"]);
}else {
    $lots_list = Lot::getAllOpened();
}

$data = array(
    "categories_equipment" => $categories_list,
    "announcement_list" => $lots_list,
);

$footer_data = array (
    "categories_equipment" => $categories_list,
);

echo Templates::render("templates/header.php", $header_data);
echo Templates::render("templates/main.php", $data);
echo Templates::render("templates/footer.php", $footer_data);

?>

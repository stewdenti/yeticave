<?php
include ('functions.php');
include ('Classes/DB.php');
include ('Classes/Authenticate.php');
include ("Classes/Categories.php");
include ("Classes/Lots.php");
include ("Classes/Templates.php");

session_start();

DB::getConnection();
$user = new Authenticate();

$categories_list = Categories::getAll();

if (!empty($_REQUEST["id"])) {
    $lots_list = Lots::getByCategoryId($_REQUEST["id"]);
}else {
    $lots_list = Lots::getAllOpened();
}

$header_data = $user->getAuthorizedData();

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

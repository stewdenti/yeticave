<?php
include ('functions.php');
include ('Classes/DB.php');
include ('Classes/Authenticate.php');

session_start();
// $link = create_connect();
DB::getConnection();
$user = new Authenticate();

$categories_list = getAllCategories();

if (!empty($_REQUEST["id"])) {
    $lots_list = getLotsByCategoryId($_REQUEST["id"]);
}else {
    $lots_list = getAllOpenLots();
}

$header_data = $user->getAuthorizedData();

$data = array(
    "categories_equipment" => $categories_list,
    "announcement_list" => $lots_list,
);

$footer_data = array (
    "categories_equipment" => $categories_list,
);

echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/main.php", $data);
echo connectTemplates("templates/footer.php", $footer_data);


?>

<?php 
include ('functions.php');
include ('Classes/DB.php');

session_start();
// $link = create_connect();
$link = DB::getConnection();

if (!$link) {
    echo mysqli_connect_error();
    exit ();
}

$categories_list = getAllCategories($link);

if (!empty($_REQUEST["id"])) {
    $lots_list = getLotsByCategoryId($link, $_REQUEST["id"]);
}else {
    $lots_list = getAllOpenLots($link);
}

$header_data = requireAuthentication();

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

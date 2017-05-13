<?php 
include ('functions.php');

session_start();
if (!$link = create_connect()) {
    echo mysqli_connect_errno();
    exit ();
}

$categories_list = getCategories($link);
if (!empty($_REQUEST["id"])) {
    $lots_list = getLots($link,$_REQUEST["id"]);
}else {
    $lots_list = getLots($link);
}

$data = array(
    "categories_equipment" => $categories_list,
    "announcement_list" => $lots_list,
);

if (isset($_SESSION["user_name"])) {

    $header_data = array ("username"=>$_SESSION["user_name"],
                            "avatar" => $_SESSION["avatar_img"]);
   



} else {
   $header_data = array();
}
$footer_data = array (
    "categories_equipment"=>$categories_list,
);

echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/main.php", $data);
echo connectTemplates("templates/footer.php", $footer_data);


?>

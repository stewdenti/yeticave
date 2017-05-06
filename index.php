<?php 
include ('functions.php');

session_start();
$data = array(
    "categories_equipment" => getCategories(),
    "announcement_list" => getLots(),
    "lot_time_remaining" => getLotTimeRemaining(),
    
);

if (isset($_SESSION["user"])) {
   $header_data = array ("username"=>$_SESSION["user"]);

} else {
   $header_data = array();
}


echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/main.php", $data);
echo connectTemplates("templates/footer.php", array());


?>

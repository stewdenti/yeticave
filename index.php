<?php 
include ('functions.php');
include ('arrayLot.php');

$data = array (
    "categories_equipment" => getCategories(),
    "announcement_list"    => $announcement_list,
    "lot_time_remaining" => getLotTimeRemaining(),
);

echo connectTemplates("templates/header.php", array());
echo connectTemplates("templates/main.php", $data);
echo connectTemplates("templates/footer.php", array());
?>

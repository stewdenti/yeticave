<?php
include ('functions.php');


session_start();


$header_data = requireAuthentication();

if (!$link = create_connect()) {
    echo mysqli_connect_errno();
    exit ();
}
$categories = getCategories($link);
$data_footer["categories_equipment"] = $categories;


$sql = "SELECT lots.id, lots.name, lots.img_path, categories.name AS category, binds.price, binds.date, lots.end_date 
FROM lots
JOIN binds on binds.lot_id=lots.id
JOIN categories on lots.category_id=categories.id
WHERE binds.user_id=? AND lots.start_price !=binds.price AND `end_date` > NOW()";

$result = dataRetrieval($link,$sql,[$_SESSION["user_id"]]);

$fields = ["rates_id","rates_title","rates_img","rates_category","rates_price","rates_time","rates_timer"];
$lot_bind_data = array();

foreach ($result as $row) {
    $i=0;
    $lot_bind=[];
    foreach ($fields as $key) {
        $lot_bind[$key] = $row[$i];
        $i++;
    }
    $lot_bind_data[] = $lot_bind;
}

$data["rates"] = $lot_bind_data;




echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/mylot-main.php", $data);
echo connectTemplates("templates/footer.php", $data_footer);



?>
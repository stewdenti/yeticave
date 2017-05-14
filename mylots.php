<?php
include ('functions.php');
// проверяем авторизацию пользователя
session_start();
$user_data = requireAuthentication(true);

$link = create_connect();
if (!$link) {
    echo mysqli_connect_errno();
    exit ();
}

$categories = getAllCategories($link);
$header_data = $user_data;

$data_footer["categories_equipment"] = $categories;

$data["rates"] = getAllBindedLotsByUser($link,$user_data["user_id"]);

echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/mylot-main.php", $data);
echo connectTemplates("templates/footer.php", $data_footer);

?>
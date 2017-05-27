<?php

include ('autoload.php');

session_start();


$header_data["user"] = Authorization::getAuthData();

$categories_list = CategoryFinder::getAll();

$template_path = "templates/main.php";

if (!empty($_REQUEST["id"])) {
    $lots_list = LotFinder::getByCategoryId($_REQUEST["id"]);
    $data["for_category"] = CategoryFinder::getById(protectXSS($_REQUEST["id"]))->name;
} else if (isset ($_REQUEST["find"]) && !empty(trim($_REQUEST["search"]))){
    $search = protectXSS(trim($_REQUEST["search"]));
    $lots_list = LotFinder::searchByString($search);
    $data["search_string"] = $search;
} else {
    $lots_list = LotFinder::getAllOpened();
}

$data["categories_equipment"] = $categories_list;
$data["announcement_list"] = $lots_list;


$footer_data = array (
    "categories_equipment" => $categories_list,
);




echo Templates::render("templates/header.php", $header_data);
echo Templates::render("templates/main.php", $data);
echo Templates::render("templates/footer.php", $footer_data);

?>

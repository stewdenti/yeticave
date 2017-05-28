<?php

include ('autoload.php');

session_start();


$header_data["user"] = Authorization::getAuthData();
$data["search_string"] = null;
$data["category_id"] = null;
$data["category_name"] = null;

$categories_list = CategoryFinder::getAll();
if (!empty($_REQUEST["page"])){
    $p = protectXSS($_REQUEST["page"]);
} else {
    $p = 1;
}

if (!empty($_REQUEST["id"])) {
    $page = Paginator::buildPages($p, protectXSS($_REQUEST["id"]));
    $lots_list = LotFinder::getByCategoryId(protectXSS($_REQUEST["id"]),$page->offset);
    $current_category = CategoryFinder::getById(protectXSS($_REQUEST["id"]));
    $data["category_id"] = $current_category->id;
    $data["category_name"] = $current_category->name;
} else if (isset($_REQUEST["search"]) && !empty(trim($_REQUEST["search"]))){
    $search = protectXSS(trim($_REQUEST["search"]));
    $page = Paginator::buildPages($p, null, $search);
    $lots_list = LotFinder::searchByString($search, $page->offset);
    $data["search_string"] = $search;
} else {
    $page = Paginator::buildPages($p);
    $lots_list = LotFinder::getAllOpened($page->offset);
}

$data["categories_equipment"] = $categories_list;
$data["announcement_list"] = $lots_list;
if ($page->total > 1){
    $data["pages"] = $page;
}

$footer_data = array (
    "categories_equipment" => $categories_list,
);




echo Templates::render("templates/header.php", $header_data);
echo Templates::render("templates/main.php", $data);
echo Templates::render("templates/footer.php", $footer_data);

?>

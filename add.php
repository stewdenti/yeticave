<?php
include ('autoload.php');
session_start();

Authorization::blockAccess();

$user_data = Authorization::getAuthData();
$header_data["user"] = $user_data;

$categories = CategoryFinder::getAll();
$data ["categories_equipment"] = $categories;
$data_footer["categories_equipment"] = $categories;

if (isset($_POST["AddForm"])) {
    $form = AddForm::getFormData();
    $data = array (
        "categories_equipment" => $categories,
    );

    if ($form->isValid()) {
        $lot_item = $form->getData();

        $lot_item["user_id"] = $user_data->id;
        $lot_item["add_date"] = date("Y:m:d H:i:s");
        $lot_item["end_date"] = date("Y:m:d H:i", strtotime($lot_item["end_date"]));
        $l = new Lot($lot_item);
        $l->insert();
        header("Location: /lot.php?id=".$l->id);
        exit();
    } else {
        $data["error"] = $form->getErrors();
        $data["lot_item"] = $form->getData();

        echo Templates::render("templates/header.php", $header_data);
        echo Templates::render("templates/form.php", $data);
        echo Templates::render("templates/footer.php", $data_footer);
    }

} else {
    $data = array (
        "error" => array(),
        "lot_item" => array(),
        "categories_equipment" => $categories,
    );
    echo Templates::render("templates/header.php", $header_data);
    echo Templates::render("templates/form.php", $data);
    echo Templates::render("templates/footer.php", $data_footer);
}

?>

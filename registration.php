<?php

include ('autoload.php');

$data_footer["categories_equipment"] = CategoryFinder::getAll();

if (isset($_POST["RegForm"])) {
    $form = RegForm::getFormData();

    if ($form->isValid()){
        $user = new User($form->getData());
        $user->insert();
        header("Location: /login.php?welcome=".$user->name);
        exit();
    } else {
        $data["error"] = $form->getErrors();
        $data["form_item"] = $form->getData();

        echo Templates::render("templates/header.php", array());
        echo Templates::render("templates/registration-main.php", $data);
        echo Templates::render("templates/footer.php", $data_footer);
    }

} else {
    $data = array (
        "error" => array(),
        "form_item" => array(),
    );
    echo Templates::render("templates/header.php", array());
    echo Templates::render("templates/registration-main.php", $data);
    echo Templates::render("templates/footer.php", $data_footer);
}

?>

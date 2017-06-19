<?php
require_once 'functions.php';

$map = [
    "Application" => "classes/Application.php",
    "Router" => "classes/Router.php",
    "BaseController" => "classes/controllers/BaseController.php",
    "Main" => "classes/controllers/Main.php",
    "ConfigManager" => "classes/ConfigManager.php",
    'BaseRecord' => 'classes/records/BaseRecord.php',
    'Bind' => 'classes/records/Bind.php',
    'Category' => 'classes/records/Category.php',
    'Lot' => 'classes/records/Lot.php',
    'User' => 'classes/records/User.php',
    "BaseFinder" => "classes/finders/BaseFinder.php",
    "CategoryFinder" => "classes/finders/CategoryFinder.php",
    "BindFinder" => "classes/finders/BindFinder.php",
    "LotFinder" => "classes/finders/LotFinder.php",
    "UserFinder" => "classes/finders/UserFinder.php",
    "BaseForm" => "classes/forms/BaseForm.php",
    "AuthForm" => "classes/forms/AuthForm.php",
    "RegForm" => "classes/forms/RegForm.php",
    "AddForm" => "classes/forms/AddForm.php",
    "AddBindForm" => "classes/forms/AddBindForm.php",

    'Authorization' => 'classes/Authorization.php',
    'DB' => 'classes/DB.php',
    'Templates' => 'classes/Templates.php',
    'Paginator' => 'classes/Paginator.php'

];

spl_autoload_register(function($className) {
    global $map;
    if (isset($map[$className])) {
        require_once $map[$className];
    }
});

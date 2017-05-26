<?php

require_once 'functions.php';

$map = [
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

    'Authorization' => 'classes/Authorization.php',
    'DB' => 'classes/DB.php',
    'Templates' => 'classes/Templates.php',

];

spl_autoload_register(function($className) {
    global $map;
    if (isset($map[$className])) {
        require_once $map[$className];
    }
});
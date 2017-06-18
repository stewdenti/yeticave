<?php

class ConfigManager
{

    public static function get()
    {
        require_once ("config/config.php");
        return $config; 
    }

}


?>